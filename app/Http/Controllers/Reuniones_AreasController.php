<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReunionesAreasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reuniones_Areas;
use App\Models\Area;
use Exception;

class Reuniones_AreasController extends Controller
{

    public function getAllReu()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $areasActivasId = Area::where('estado', 1)->get()->pluck('id');

        $reuniones = Reuniones_Areas::with('area')->whereIn('area_id', $areasActivasId)->get();

        foreach ($reuniones as $horario) {
            $horaInicial = (int) date('H', strtotime($horario->hora_inicial));
            $horaFinal = (int) date('H', strtotime($horario->hora_final));

            $horariosFormateados = [
                'hora_inicial' => $horaInicial,
                'hora_final' => $horaFinal,
                'dia' => $horario->dia,
                'disponibilidad' => $horario->disponibilidad,
            ];
            $horario->horario_modificado = $horariosFormateados;
        }

        // return $reuniones;

        return view('InspiniaViews.horarios.reuniones_generales', ['reuniones' => $reuniones]);
    }

    public function reunionesGest($area_id)
    {
        $access = FunctionHelperController::verifyAreaAccess($area_id);
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para visualizar esa area. No lo intente denuevo o puede ser baneado.');
        }
        $area = Area::findOrFail($area_id);
        $reuniones = Reuniones_Areas::with('area')->where('area_id', $area_id)->get();
        $dias = FunctionHelperController::getDays();
        $horas = [
            "01:00",
            "02:00",
            "03:00",
            "04:00",
            "05:00",
            "06:00",
            "07:00",
            "08:00",
            "09:00",
            "10:00",
            "11:00",
            "12:00",
            "13:00",
            "14:00",
            "15:00",
            "16:00",
            "17:00",
            "18:00",
            "19:00",
            "20:00",
            "21:00",
            "22:00",
            "23:00",
            "24:00",
        ];
        $disponibilidades = ["Virtual", "Presencial"];
        $horariosFormateados = [];
        foreach ($reuniones as $reunion) {
            $horaInicial = (int) date('H', strtotime($reunion->hora_inicial));
            $horaFinal = (int) date('H', strtotime($reunion->hora_final));

            $horariosFormateados[] = [
                'hora_inicial' => $horaInicial,
                'hora_final' => $horaFinal,
                'dia' => $reunion->dia,
                'disponibilidad' => $reunion->disponibilidad,
            ];
        }

        // return response()->json(["reuniones" => $reuniones, "area" => $area]);
        return view('inspiniaViews.areas.gestReuniones', [
            "area" => $area,
            "reuniones" => $reuniones,
            "horariosFormateados" => $horariosFormateados,
            "dias" => $dias,
            "disponibilidades" => $disponibilidades,
            "horas" => $horas,
        ]);

    }

    public function store(StoreReunionesAreasRequest $request)
    {
        $access = FunctionHelperController::verifyAreaAccess($request->area_id);
            if (!$access) {
                return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para modificar esa area. No lo intente denuevo o puede ser baneado.');
            }
        DB::beginTransaction();
        try {
            // return $request;



            foreach ($request->reuniones as $reunion) {
                Reuniones_Areas::create([
                    'area_id' => $request->area_id,
                    'hora_inicial' => $reunion['hora_inicial'],
                    'hora_final' => $reunion['hora_final'],
                    'dia' => $reunion['dia'],
                    'disponibilidad' => $reunion['disponibilidad'],
                ]);
            }

            DB::commit();

            // return response()->json(["resp" => "Registro Creado Correctamente"]);

            return redirect()->route('areas.getReuniones', $request->area_id);

        } catch (Exception $e) {
            DB::rollBack();
            // return response()->json(["error" => $e->getMessage()]);
            return redirect()->route('areas.getReuniones', $request->area_id)->with('ReunionError', [$e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            // return $request;
            $reunion = Reuniones_Areas::findOrFail($id);
            $area_id = $reunion->area_id;
            $access = FunctionHelperController::verifyAreaAccess($area_id);
            if (!$access) {
                return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para modificar esa area. No lo intente denuevo o puede ser baneado.');
            }

            $request->validate([
                'dia' => 'sometimes',
                'hora_inicial' => 'sometimes',
                'hora_final' => 'sometimes',
                'disponibilidad' => 'sometimes',
            ]);
            $reunion->update($request->all());
            DB::commit();
            // return response()->json(["resp" => "Registro Actualizado Correctamente"]);
            return redirect()->route('areas.getReuniones', $area_id);

        } catch (Exception $e) {
            DB::rollBack();
            // return response()->json(["error" => $e->getMessage()]);
            return redirect()->route('areas.getReuniones', $area_id);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reunion = Reuniones_Areas::findOrFail($id);
            $area_id = $reunion->area_id;
            $access = FunctionHelperController::verifyAreaAccess($area_id);
            if (!$access) {
                return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para modificar esa area. No lo intente denuevo o puede ser baneado.');
            }
            $reunion->delete();
            DB::commit();
            // return response()->json(["resp" => "Registro Eliminado Correctamente"]);
            return redirect()->route('areas.getReuniones', $area_id)->with('success', 'Registro eliminado correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            // return response()->json(["error" => $e->getMessage()]);
            return redirect()->route('areas.getReuniones', $area_id)->with('error', 'Error al eliminar el registro');
        }
    }







}
