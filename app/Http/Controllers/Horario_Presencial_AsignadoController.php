<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores_por_Area;
use App\Models\Horarios_Presenciales;
use App\Models\Maquina_reservada;
use Illuminate\Http\Request;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Area;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AreaController;
use Exception;

class Horario_Presencial_AsignadoController extends Controller
{
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        try {
            $areasActivasId = Area::where('estado', 1)->get()->pluck('id');
            $horarios_presenciales_Asignados = Horario_Presencial_Asignado::with(['horario_presencial', 'area'])->whereIn('area_id', $areasActivasId)->get();

            foreach ($horarios_presenciales_Asignados as $horario) {
                $horaInicial = (int) date('H', strtotime($horario->horario_presencial->hora_inicial));
                $horaFinal = (int) date('H', strtotime($horario->horario_presencial->hora_final));

                $horariosFormateados = [
                    'hora_inicial' => $horaInicial,
                    'hora_final' => $horaFinal,
                    'dia' => $horario->horario_presencial->dia,
                ];
                $horario->horario_modificado = $horariosFormateados;
            }
            // return $horarios_presenciales_Asignados;
            return view('inspiniaViews.horarios.horario-gp-areas',['horarios_presenciales_Asignados' => $horarios_presenciales_Asignados]);
            // return response()->json(["data" => $horarios_presenciales_Asignados]);

        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }

}

    public function indexPublic()
{
    try {
        $areasActivasId = Area::where('estado', 1)->get()->pluck('id');
        $horarios_presenciales_Asignados = Horario_Presencial_Asignado::with(['horario_presencial', 'area'])
            ->whereIn('area_id', $areasActivasId)
            ->get();

        foreach ($horarios_presenciales_Asignados as $horario) {
            $horaInicial = (int) date('H', strtotime($horario->horario_presencial->hora_inicial));
            $horaFinal = (int) date('H', strtotime($horario->horario_presencial->hora_final));

            $horariosFormateados = [
                'hora_inicial' => $horaInicial,
                'hora_final' => $horaFinal,
                'dia' => $horario->horario_presencial->dia,
            ];
            $horario->horario_modificado = $horariosFormateados;
        }

        return view('inspiniaViews.horarios.horario-gp-areas', [
            'horarios_presenciales_Asignados' => $horarios_presenciales_Asignados
        ]);
    } catch (Exception $e) {
        return response()->json(["error" => $e->getMessage()]);
    }
}



    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $request->validate([
                "horario_presencial_id.*" => "required|integer",
                "area_id" => "required|integer",
            ]);

            $area = Area::findOrFail($request->area_id);
            if($area){
                $horariosPresencialesRegistros = Horarios_Presenciales::whereIn('id', $request->horario_presencial_id)->get();

                foreach($horariosPresencialesRegistros as $horarioPresencialRegistro) {
                    $horarioExistente = Horario_Presencial_Asignado::where('horario_presencial_id', $horarioPresencialRegistro->id)
                        ->where('area_id', $request->area_id)->first();
                    if(!$horarioExistente){
                        Horario_Presencial_Asignado::create([
                            "horario_presencial_id" => $horarioPresencialRegistro->id,
                            "area_id" => $request->area_id
                        ]);
                    }
                }
                //Verificar y destruir si hay conflictos con las maquinas de otras áreas con el mismo horario y salón
                FunctionHelperController::destroySameMachines($area->id);
            }

            DB::commit();

            return redirect()->route('areas.getHorario', ['area_id' => $request->area_id]);
        }catch(Exception $e){
            DB::rollBack();
            // return $e;
            // return response()->json(["error" => $e]);
            return redirect()->route('areas.getHorario', ['area_id' => $request->area_id]);
        }
    }


    public function update(Request $request, $horario_presencial_asignado_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $request->validate([
                "horario_presencial_id" => "required|integer",
                "area_id" => "required|integer",
            ]);
            $horario_presencial_asignado = Horario_Presencial_Asignado::findOrFail($horario_presencial_asignado_id);

            if($horario_presencial_asignado){
                $area = Area::findOrFail($request->area_id);
                $horarioPresencial = Horarios_Presenciales::findOrFail($request->horario_presencial_id);
                if($area && $horarioPresencial){
                    $horario_presencial_asignado->update($request->all());
                    //Verificar y destruir si hay conflictos con las maquinas de otras áreas con el mismo horario y salón
                    FunctionHelperController::destroySameMachines($area->id);
                }
            }

            DB::commit();
            // return response()->json(["resp" => "Registro actualizado correctamente"]);
            return redirect()->route('areas.getHorario', ['area_id' => $request->area_id]);
        } catch(Exception $e){
            DB::rollBack();
            return redirect()->route('areas.getHorario', ['area_id' => $request->area_id]);
        }


    }



    public function destroy($area_id, $horario_presencial_asignado_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            $horario_presencial_asignado = Horario_Presencial_Asignado::findOrFail($horario_presencial_asignado_id);

            if ($horario_presencial_asignado){
                $horario_presencial_asignado->delete();
            }

            DB::commit();
            return redirect()->route('areas.getHorario', ['area_id' => $area_id]);
            // return response()->json(["resp" => "Candidato eliminado correctamente"]);
        } catch (Exception $e){
            DB::rollBack();
            // return response()->json(["error" => $e]);
            return redirect()->route('areas.getHorario', ['area_id' => $area_id]);
        }
    }

    public function redirectGestHorario($area_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        try {
            $area = Area::findOrFail($area_id);

            $areaController = new AreaController();
            return $areaController->getFormHorarios($area);

        } catch (Exception $e) {

            return redirect()->route('dashboard');

        }
    }
}
