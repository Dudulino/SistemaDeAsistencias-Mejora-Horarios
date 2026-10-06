<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores_por_Area;
use App\Models\Semanas;
use Illuminate\Http\Request;
use App\Models\RegistroActividad;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class RegistroActividadController extends Controller
{
    public static function crearRegistro($colaboradorArea_id, $estado){
        DB::beginTransaction();
        try{
            $today = Carbon::today();
            // return $today->toDateString();
            $colaboradorArea = Colaboradores_por_Area::findOrFail($colaboradorArea_id);
            if($colaboradorArea){
                RegistroActividad::create([
                    'colaborador_area_id' => $colaboradorArea_id,
                    'estado' => $estado,
                    'fecha' => $today->toDateString(),
                ]);
                DB::commit();
                return response()->json(['status' => true, 'message' => 'registro creado exitosamente']);
            } else{
                DB::commit();
                return response()->json(['status' => false, 'message' => 'No se encontró el colaborador Area con ese Id']);

            }
        } catch(Exception $e){
            DB::rollBack();
            return response()->json(['status' => false,'message'=> $e->getMessage()]);
        }
    }

    public static function obtenerInactividad($colaboradorAreaId){
        //Se obtienen los registros de actividad del colaborador por area segun el id
        $registros = RegistroActividad::where('colaborador_area_id', $colaboradorAreaId)->get();
        //Se inicializa un array de las inactividades del usuario
        $inactividades = [];
        //Se inicializa una variable para llevar el control de la inactividad actual
        $inactividadActual = null;

        foreach ($registros as $registro) {
            if ($registro->estado == 0) {
                // Si no hay una inactividad actual en curso, inicia una nueva
                if ($inactividadActual === null) {
                    $inactividadActual = [
                        //Se toma la fecha inicial segun la fecha del registro con estado 0
                        'desde' => $registro->fecha,
                        'hasta' => null,
                        'semanas' => [],
                    ];
                }
            } else if ($registro->estado == 1) {
                // Si hay una inactividad actual en curso, se retoma y se finaliza
                if ($inactividadActual !== null) {
                    //Se toma la fecha final segun la fecha de este registro con estado 1
                    $inactividadActual['hasta'] = $registro->fecha;
                    //Se guarda la fecha inicial y final
                    $desde = $inactividadActual['desde'];
                    $hasta = $inactividadActual['hasta'];
                    //Se obtienen los registros de semana que mas se acerquen segun el día
                    $semanaDesde = FunctionHelperController::getSemanaByDay($desde);
                    $semanaHasta = FunctionHelperController::getSemanaByDay($hasta);
                    //Se inicia array para los Id de las semanas
                    $semanasId = [];
                    //mientras el id de la semana inicial sea menor al id de la semana final, se agrega al array de semanasId
                    for($i = $semanaDesde->id; $i<$semanaHasta->id; $i++){
                        $semanasId[] = $i;
                    }
                    //Se busca las semanas por los id de las semanasId
                    $semanasInactivas = Semanas::whereIn('id', $semanasId)->get();
                    //Se guardan las semanas en el array de inactividadActual
                    $inactividadActual['semanas'] = $semanasInactivas;
                    //Se agrega la inactividad al array de inactividades
                    $inactividades[] = $inactividadActual;
                    //Se reinicia la inactividad actual para que no se repita en el siguiente registro con estado 1
                    $inactividadActual = null;
                }
            }
        }

        // Si al final del procesamiento aún hay una inactividad en curso
        if ($inactividadActual !== null) {
            $inactividadActual['hasta'] = null; // La fecha final es indefinida
            //Se guarda la fecha inicial
            $desde = $inactividadActual['desde'];
            //Se obtiene la semana segun el día de la fecha inicial
            $semanaDesde = FunctionHelperController::getSemanaByDay($desde);
            //Se obtienen las semanas que tengan un id mayor al id de la semana inicial
            $semanasInactivas = Semanas::where('id', '>=',$semanaDesde->id)->get();
            //Se guardan las semanas en el array de inactividadActual
            $inactividadActual['semanas'] = $semanasInactivas;
            //Se agrega la inactividad al array de inactividades
            $inactividades[] = $inactividadActual;
        }
        //Se retorna el array de inactividades
        return $inactividades;

    }

    public static function getColabSemanasInactivas($colaboradorAreaId){
        $inactividades = RegistroActividadController::obtenerInactividad($colaboradorAreaId);
        $semanasInactivas = [];
        foreach($inactividades as $inactividad){
            $semanas = $inactividad['semanas'];
            foreach($semanas as $semana){
                $semanasInactivas[] = $semana;
            }
        }
        return $semanasInactivas;
    }

    public static function verifyColaboradorInactivity($colabAreaId, $semana_id)
    {
        $inactividades = RegistroActividadController::obtenerInactividad($colabAreaId);
        $estado = true;
        foreach ($inactividades as $inactividad) {
            $semanasInactivas = $inactividad['semanas'];
            foreach ($semanasInactivas as $semanaInactiva) {
                if ($semana_id === $semanaInactiva['id']) {
                    // $colaboradoresAreaId->forget($colabKey);
                    //$colaboradoresOtherAreas->forget($key);
                    // echo "here" + $colabAreaId;
                    $estado = false;
                    break 2; // Salir de ambos bucles si el colaborador está inactivo
                }
            }
        }
        // Si el colaborador está activo, añadirlo al array temporal
        return $estado;
    }


}
