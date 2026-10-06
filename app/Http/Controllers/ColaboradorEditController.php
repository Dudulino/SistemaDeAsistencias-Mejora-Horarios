<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Actividades;
use App\Models\AreaRecreativa;
use App\Models\Candidatos;
use App\Models\Carrera;
use App\Models\Colaboradores;
use App\Models\Especialista;
use App\Models\Distrito;
use App\Models\Sede;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColaboradorEditController extends Controller
{

    public function edit() {
        $user = auth()->user();

        $candidato = Candidatos::with(['sede', 'carrera', 'colaborador.especialista', 'colaborador.actividades'])->where('correo', $user->email)->first();

        $sedes = Sede::get();
        $carreras = Carrera::get();
        $especialistas = Especialista::get();
        $actividades = Actividades::where('estado', 1)->get();
        $distritos = Distrito::get();
        $colaborador = $candidato->colaborador;

        if ($colaborador && $colaborador->editable == 0) {
            return redirect()->route('dashboard')->with('warning', 'Su edición de datos no está activada. Por favor, consulte con RRHH.');
        }

        // return $actividades;

        return view('inspiniaViews.colaboradores.edit_colaborador', [
            'candidato' => $candidato,
            'colaborador' => $colaborador,
            'sedes' => $sedes,
            'carreras' => $carreras,
            'actividades' => $actividades,
            'especialistas' => $especialistas,
            'especialista' => $colaborador && $colaborador->especialista ? $colaborador->especialista->nombres : null,
            'distritos' => $distritos
        ]);
    }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $candidato = Candidatos::findOrFail($id);

            $colaborador = $candidato->colaborador;
            if ($colaborador && $colaborador->editable == 0) {
                return redirect()->route('dashboard')->with('warning', 'Su edición de datos no está activada. Por favor, consulte con RRHH.');
            }

            $errors = [];

            // validation nombre
            if(!isset($request->nombre)) {
                $errors['nombre'] = 'Campo obligatorio';
            }

            // validation apellido
            if(!isset($request->apellido)) {
                $errors['apellido'] = 'Campo obligatorio';
            }

            // validation dni
            if(!isset($request->dni)) {
                $errors['dni'] = 'Campo obligatorio';
            }else if (isset($request->dni) && strlen($request->dni) !== 8) {
                $errors['dni'] = 'Debe contener 8 dígitos';
            } else if (isset($request->dni)) {
                $candidatos = Candidatos::where('dni', $request->dni)->get();
                foreach ($candidatos as $cand) {
                    if ($cand->id != $id) {
                        $errors['dni'] = 'DNI en uso';
                        break;
                    }
                }
            }

            // validation correo
            if(!isset($request->correo)) {
                $errors['correo'] = 'Campo obligatorio';
            }else if(isset($request->correo)){
                $candidatos = Candidatos::where('correo', $request->correo)->get();
                foreach($candidatos as $cand) {
                    if ($cand->id != $id) {
                        $errors['correo'] = 'Correo en uso';
                        break;
                    }
                }
            }

            // validation id_senati
            if(!isset($request->id_senati)) {
                $errors['id_senati'] = 'Campo obligatorio';
            }else if(isset($request->id_senati)){
                $candidatos = Candidatos::where('id_senati', $request->id_senati)->get();
                foreach($candidatos as $cand) {
                    if ($cand->id != $id) {
                        $errors['id_senati'] = 'ID en uso';
                        break;
                    }
                }
            }

            // validation celular
            if(isset($request->celular) && strlen($request->celular) !== 9) {
                $errors['celular'] = 'Debe contener 9 dígitos';
            } else if(isset($request->celular)){
                $candidatos = Candidatos::where('celular', $request->celular)->get();
                foreach($candidatos as $cand) {
                    if ($cand->id != $id) {
                        $errors['celular'] = 'Celular en uso';
                        break;
                    }
                }
            }

            if(!empty($errors)) {
               return redirect()->route('colaboradorEdit.edit')->withErrors($errors)->withInput();
            }

            $candidato->update($request->all());


            if ($candidato->correo !== auth()->user()->email) {
                $user = auth()->user();
                $user->email = $candidato->correo;
                $user->save();
            }

            // Actualizar el colaborador si existe
            $colaborador = $candidato->colaborador;
            if ($colaborador) {
                $colaborador->update(['editable' => 0]);
                if (isset($request->especialista_id)) {
                    $colaborador->update(['especialista_id' => $request->especialista_id]);
                }
            }

            if (isset($request->actividades_id) && is_array($request->actividades_id)) {
                foreach ($request->actividades_id as $actividad_id) {
                    $actividad_recreativa = AreaRecreativa::where('colaborador_id', $colaborador->id)
                        ->where('actividad_id', $actividad_id)
                        ->first();

                    if (!$actividad_recreativa) {
                        AreaRecreativa::create([
                            'colaborador_id' => $colaborador->id,
                            'actividad_id' => $actividad_id,
                            'estado' => true
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Para volver a actualizar sus datos, consulte con RRHH para activar la edición de datos.');

        } catch (Exception $e) {
            // return $e;
            DB::rollBack();
            return redirect()->route('dashboard')->with('error', 'Ocurrió un error al actualizar el colaborador.');
        }
    }

}
