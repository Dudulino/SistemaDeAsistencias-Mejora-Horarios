<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHorario_de_ClasesRequest;
use App\Models\Horario_de_Clases;
use App\Models\Colaboradores;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HorarioDeClasesController extends Controller
{
    public function index()
    {
        try {
            $horario_de_clases = Horario_de_Clases::with('colaboradores')->get();

            if (count($horario_de_clases) == 0) {
                return response()->json(["resp" => "No hay registros insertados"]);
            }

            return response()->json(["data" => $horario_de_clases, "conteo" => count($horario_de_clases)]);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }

    }

    public function getCalendariosColaborador($colaborador_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        try{
            $horariosDeClases = Horario_de_Clases::where('colaborador_id', $colaborador_id)->get();
            $horariosFormateados = [];

            $colaborador = Colaboradores::findOrFail($colaborador_id);

            $horas = [
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
            ];

            $days = FunctionHelperController::getDays();

            foreach ($horariosDeClases as $horario) {
                $horaInicial = (int) date('H', strtotime($horario->hora_inicial));
                $horaFinal = (int) date('H', strtotime($horario->hora_final));

                $horariosFormateados[] = [
                    'hora_inicial' => $horaInicial,
                    'hora_final' => $horaFinal,
                    'dia' => $horario->dia,
                    'justificacion' => $horario->justificacion,
                ];
            }

            DB::beginTransaction();
            return view('inspiniaViews.colaboradores.horario_clases', [
                'horariosDeClases' => $horariosDeClases,
                'horariosFormateados' => $horariosFormateados,
                'colaborador' => $colaborador,
                'horas' => $horas,
                'days' => $days,
            ]);
        }catch(Exception $e){
            return redirect()->route('colaboradores.horarioClase')->with('error', 'Ocurrió un error al crear una reunión, intente de nuevo. Si este error persiste, contacte a su equipo de soporte.');
        }

    }


    public function store(StoreHorario_de_ClasesRequest $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            // return $request;
            // $request->validate([
            //     'colaborador_id' => 'required|integer',
            //     'horarios' => 'required|array',
            //     'horarios.*.hora_inicial' => 'required|date_format:H:i',
            //     'horarios.*.hora_final' => 'required|date_format:H:i',
            //     'horarios.*.dia' => 'required|string',
            //     'horarios.*.justificacion' => 'required|string',
            // ]);
            foreach ($request->horarios as $horario) {
                Horario_de_Clases::create([
                    'colaborador_id' => $request->colaborador_id,
                    'hora_inicial' => $horario['hora_inicial'],
                    'hora_final' => $horario['hora_final'],
                    'dia' => $horario['dia'],
                    'justificacion' => $horario['justificacion'],
                ]);
            }
            DB::commit();
            return redirect()->route('colaboradores.horarioClase', ['colaborador_id' => $request->colaborador_id]);
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('colaboradores.horarioClase')->with('error', 'Ocurrió un error al crear una reunión, intente de nuevo. Si este error persiste, contacte a su equipo de soporte.');
            // return redirect()->route('colaboradores.horarioClase', ['colaborador_id' => $request->colaborador_id]);

        }
    }


    public function update(Request $request, $horario_de_clases_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $horario_de_clases = Horario_de_Clases::find($horario_de_clases_id);
            // return $request;
            $request->validate([
                'dia' => 'required|string|min:1|max:100',
                'hora_inicial' => 'required',
                'hora_final' => 'required',
                'justificacion' => 'required',
            ]);

            //$horario_de_clases->update($request->all());
            $horario_de_clases->fill([
                "hora_inicial" => $request->hora_inicial,
                "hora_final" => $request->hora_final,
                "dia" => $request->dia,
                "justificacion" => $request->justificacion,
            ])->save();
            DB::commit();
            return redirect()->route('colaboradores.horarioClase', ['colaborador_id' => $horario_de_clases->colaborador_id]);
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('colaboradores.horarioClase')->with('error', 'Ocurrió un error al crear una reunión, intente de nuevo. Si este error persiste, contacte a su equipo de soporte.');

        }
    }


    public function destroy($horario_de_clases_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $horario_de_clases = Horario_de_Clases::findOrFail($horario_de_clases_id);

            $horario_de_clases->delete();

            DB::commit();
            return redirect()->route('colaboradores.horarioClase', ['colaborador_id' => $horario_de_clases->colaborador_id]);
        }catch(Exception $e){
            DB::rollback();
            return redirect()->route('colaboradores.horarioClase')->with('error', 'Ocurrió un error al crear una reunión, intente de nuevo. Si este error persiste, contacte a su equipo de soporte.');
        }



    }
}
