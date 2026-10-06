<?php

namespace App\Http\Controllers;

use App\Models\Semanas;
use App\Models\RegistroResponsabilidad;
use App\Models\Responsabilidades_semanales;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RegistroResponsabilidadController extends Controller
{
    public static function crearRegistro($responsabilidad_id, $estado)
    {
        DB::beginTransaction();
        try {
            $today = Carbon::today();
            $responsabilidad = Responsabilidades_semanales::findOrFail($responsabilidad_id);

            RegistroResponsabilidad::create([
                'responsabilidad_id' => $responsabilidad_id,
                'estado' => $responsabilidad->estado,
                'fecha' => $today->toDateString(),
            ]);
            DB::commit();
            return true;
            // return response()->json(['status' => true, 'message' => 'Registro creado exitosamente']);
            // if ($responsabilidad->estado == 0) {
            //     $registrosActivos = RegistroResponsabilidad::where('responsabilidad_id', $responsabilidad->id)->where('estado', 1)->get();

            //     foreach ($registrosActivos as $registro) {
            //         $registro->update(['estado' => 0]);
            //         RegistroResponsabilidad::create([
            //             'responsabilidad_id' => $responsabilidad_id,
            //             'estado' => $estado,
            //             'fecha' => $today->toDateString(),
            //         ]);
            //     }
            // }
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
            // return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }



    public static function obtenerInactividad($responsabilidadId)
    {
        $registros = RegistroResponsabilidad::where('responsabilidad_id', $responsabilidadId)->get();
        $inactividades = [];
        $inactividadActual = null;

        foreach ($registros as $registro) {
            if ($registro->estado == 0) {
                if ($inactividadActual === null) {
                    $inactividadActual = [
                        'desde' => $registro->fecha,
                        'hasta' => null,
                        'semanas' => [],
                    ];
                }
            } else if ($registro->estado == 1) {
                if ($inactividadActual !== null) {
                    $inactividadActual['hasta'] = $registro->fecha;
                    $desde = $inactividadActual['desde'];
                    // return
                    $hasta = $inactividadActual['hasta'];
                    $semanaDesde = FunctionHelperController::getSemanaByDay($desde);
                    $semanaHasta = FunctionHelperController::getSemanaByDay($hasta);
                    // return ["hasta" => $semanaHasta];
                    $semanasId = [];
                    for ($i = $semanaDesde->id; $i < $semanaHasta->id; $i++) {
                        $semanasId[] = $i;
                    }
                    $semanasInactivas = Semanas::whereIn('id', $semanasId)->get();
                    $inactividadActual['semanas'] = $semanasInactivas;
                    $inactividades[] = $inactividadActual;
                    $inactividadActual = null;
                } else{
                    $primeraSemana = Semanas::find(1);
                    $inactividadActual = [
                        'desde' => $primeraSemana->fecha_lunes,
                        'hasta' => $registro->fecha,
                        'semanas' => [],
                    ];
                    $semanaHasta = FunctionHelperController::getSemanaByDay($registro->fecha);
                    $semanasId = [];
                    for ($i = $primeraSemana->id; $i < $semanaHasta->id; $i++) {
                        $semanasId[] = $i;
                    }
                    $semanasInactivas = Semanas::whereIn('id', $semanasId)->get();
                    $inactividadActual['semanas'] = $semanasInactivas;
                    $inactividades[] = $inactividadActual;
                    $inactividadActual = null;
                }
            }
        }

        if ($inactividadActual !== null) {
            $inactividadActual['hasta'] = null;
            $desde = $inactividadActual['desde'];
            $semanaDesde = FunctionHelperController::getSemanaByDay($desde);
            $semanasInactivas = Semanas::where('id', '>=', $semanaDesde->id)->get();
            $inactividadActual['semanas'] = $semanasInactivas;
            $inactividades[] = $inactividadActual;
        }

        return $inactividades;
    }

    public static function verifyResponsabilidadInactivity($responsabilidad_id, $semana_id)
    {
        $inactividades = RegistroResponsabilidadController::obtenerInactividad($responsabilidad_id);
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

    public static function getSemanaByDay($date)
    {
        $date = Carbon::parse($date);
        $semanaDate = $date;
        if ($date->isMonday() || $date->isTuesday() || $date->isWednesday()) {
            $semanaDate = $date->copy()->startOfWeek();
        } else {
            while (!$semanaDate->isMonday()) {
                $semanaDate->addDay();
            }
        }
        return Semanas::where('fecha_lunes', $semanaDate->toDateString())->first();
    }
}
