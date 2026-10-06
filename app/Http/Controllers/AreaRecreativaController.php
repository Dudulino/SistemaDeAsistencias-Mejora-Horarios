<?php

namespace App\Http\Controllers;

use App\Models\AreaRecreativa;
use Illuminate\Http\Request;

class AreaRecreativaController extends Controller
{
    public static function getColabActividades($colaboradores){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $colabsConActividades = [];

        foreach($colaboradores as $colaborador){
            $colabActividades = AreaRecreativa::with('actividad')->where('colaborador_id', $colaborador->id)->where('estado', 1)->get();

            if($colabActividades->count() > 0){
                $actividades = [];
                foreach($colabActividades as $acti){
                    $actividades[] = $acti->actividad->nombre;
                }
                $colaborador->actividadesFavoritas = $actividades;
            } else {
                $colaborador->actividadesFavoritas = ['Sin actividades favoritas'];
            }
            $colabsConActividades[] = $colaborador;
        }
        return $colabsConActividades;
    }
}
