<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReunionesProgramadas;
use App\Models\IntegrantesReuniones;
use App\Mail\ReunionProgramadaMailable;
use App\Mail\ReunionModificadaMailable;
use App\Mail\ReunionIntegranteRemovidoMailable;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
//Import Mail:
//doit


class ReunionesProgramadasController extends Controller
{
    public function getAllProgramReuToCalendar(){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $reunionesProgramadas = ReunionesProgramadas::get();
        foreach ($reunionesProgramadas as $horario) {
            $horaInicial = (int) date('H', strtotime($horario->hora_inicial));
            $horaFinal = (int) date('H', strtotime($horario->hora_final));
            $year = date('Y', strtotime($horario->fecha));
            $month = date('m', strtotime($horario->fecha));
            $day = date('d', strtotime($horario->fecha));

            $month = $month -1;

            $horariosFormateados = [
                'hora_inicial' => $horaInicial,
                'hora_final' => $horaFinal,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'url' => route('reunionesProgramadas.show', $horario->id),
            ];
            $horario->horario_modificado = $horariosFormateados;
        }
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
        $colaboradores = Colaboradores::where('estado', 1)->get();
        // return $reunionesProgramadas;
        return view('inspiniaViews.horarios.reuniones_programadas_calendar', [
            'reunionesProgramadas' => $reunionesProgramadas,
            'horas' => $horas,
            'colaboradores' => $colaboradores,
        ]);

    }

    public function createReunionProgramada(Request $request){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        // return $request;
        DB::beginTransaction();
        try{
            $request->validate([
                'fecha' => 'required',
                'hora_inicial' => 'required',
                'hora_final' => 'required',
                'disponibilidad' => 'required|string',
                'url' => 'sometimes',
                'direccion' => 'sometimes',
                'descripcion' => 'sometimes',
                'colaboradores_id*' => 'required',
            ]);
            $descripcion = null;
            if($request->descripcion){
                $descripcion = $request->descripcion;
            }

            $url = null;
            $direccion = null;
            if($request->disponibilidad == 'Virtual'){
                $url = $request->url;
            } else if($request->disponibilidad == 'Presencial'){
                $direccion = $request->direccion;
            }

            $newReunion = ReunionesProgramadas::create([
                "fecha" => $request->fecha,
                "hora_inicial" => $request->hora_inicial,
                "hora_final" => $request->hora_final,
                "disponibilidad" => $request->disponibilidad,
                "url" => $url,
                "direccion" => $direccion,
                "descripcion" => $descripcion,
            ]);

            if($newReunion){
                foreach($request->colaboradores_id as $colaborador_id){
                    $colaborador = Colaboradores::with('candidato')->findOrFail($colaborador_id);
                    if($colaborador){
                        IntegrantesReuniones::create([
                            "reunion_programada_id" => $newReunion->id,
                            "colaborador_id" => $colaborador_id,
                        ]);
                        if($colaborador->candidato->correo != null){
                            Mail::to($colaborador->candidato->correo)->send(new ReunionProgramadaMailable($colaborador, $newReunion));
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('reunionesProgramadas.allReu');
        } catch(Exception $e){
            // return $e;
            DB::rollBack();
            // return redirect()->route('reunionesProgramadas.allReu');
            return redirect()->route('reunionesProgramadas.allReu')->with('error', 'Ocurrió un error al crear una reunión, intente de nuevo. Si este error persiste, contacte a su equipo de soporte.');
            // return $e;
        }
    }

    public function showReunionProgramada($reunion_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $reunion = ReunionesProgramadas::findOrFail($reunion_id);
        if($reunion){
            $integrantes = IntegrantesReuniones::where('reunion_programada_id', $reunion_id)->where('estado', 1)->get();
            $colaboradores = Colaboradores::where('estado', 1)->get();
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

            return view('inspiniaViews.horarios.show_reunion_programada', [
                'reunion' => $reunion,
                'integrantes' => $integrantes,
                'colaboradores' => $colaboradores,
                'horas' => $horas,
            ]);
        } else{
            return redirect()->route('reunionesProgramadas.allReu');
        }
    }

    public function update(Request $request, $reunion_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        // return $request;
        DB::beginTransaction();
        try{
            $request->validate([
                'fecha' => 'required',
                'hora_inicial' => 'required',
                'hora_final' => 'required',
                'disponibilidad' => 'required|string',
                'url' => 'sometimes',
                'direccion' => 'sometimes',
                'descripcion' => 'sometimes',
                'colaboradores_id*' => 'required',
            ]);
            $reunion = ReunionesProgramadas::findOrFail($reunion_id);
            $Prevreunion = ReunionesProgramadas::findOrFail($reunion_id);
            $url = null;
            $direccion = null;
            if($request->disponibilidad == 'Virtual'){
                $url = $request->url;
            } else if($request->disponibilidad == 'Presencial'){
                $direccion = $request->direccion;
            }
            if($reunion){
                $integrantesExcluidos = IntegrantesReuniones::where('reunion_programada_id', $reunion_id)->whereNotIn('colaborador_id', $request->colaboradores_id)->where('estado', 1)->get();

                foreach($integrantesExcluidos as $integrante){
                    //Se inactiva
                    $integrante->update(["estado" => 0]);
                    $colaborador = Colaboradores::with('candidato')->findOrFail($integrante->colaborador_id);
                    if($colaborador){
                        if($colaborador->candidato->correo != null){
                            Mail::to($colaborador->candidato->correo)->send(new ReunionIntegranteRemovidoMailable($colaborador, $reunion));
                        }
                    }
                }

                $reunion->update([
                    "fecha" => $request->fecha,
                    "hora_inicial" => $request->hora_inicial,
                    "hora_final" => $request->hora_final,
                    "disponibilidad" => $request->disponibilidad,
                    "url" => $url,
                    "direccion" => $direccion,
                    "descripcion" => $request->descripcion,
                ]);

                foreach($request->colaboradores_id as $colaborador_id){
                    $integranteReunion = IntegrantesReuniones::where('reunion_programada_id', $reunion_id)->where('colaborador_id', $colaborador_id)->first();
                    $colaborador = Colaboradores::with('candidato')->findOrFail($colaborador_id);
                    if($colaborador){
                        if($integranteReunion){
                            if($integranteReunion->estado == 0){
                                //Si está inactivo se activa
                                $integranteReunion->update(["estado" => 1]);
                                if($colaborador->candidato->correo != null){
                                    Mail::to($colaborador->candidato->correo)->send(new ReunionProgramadaMailable($colaborador, $reunion));
                                }
                            } else{
                                if($Prevreunion->fecha != $request->fecha || $Prevreunion->hora_inicial != $request->hora_inicial || $Prevreunion->hora_final != $request->hora_final || $Prevreunion->disponibilidad != $request->disponibilidad || $Prevreunion->url != $request->url || $Prevreunion->direccion != $request->direccion){
                                    if($colaborador->candidato->correo != null){
                                        Mail::to($colaborador->candidato->correo)->send(new ReunionModificadaMailable($colaborador, $reunion));
                                    }
                                }
                            }
                        }else{
                            IntegrantesReuniones::create([
                                "reunion_programada_id" => $reunion->id,
                                "colaborador_id" => $colaborador_id,
                            ]);
                            if($colaborador->candidato->correo != null){
                                Mail::to($colaborador->candidato->correo)->send(new ReunionProgramadaMailable($colaborador, $reunion));
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('reunionesProgramadas.show', ["reunion_id" => $reunion_id]);
        } catch(Exception $e){
            // return $e;
            DB::rollBack();
            return redirect()->route('reunionesProgramadas.show', ["reunion_id" => $reunion_id]);
        }
    }
}
