<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores;
use App\Models\Responsabilidades_semanales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cumplio_Responsabilidad_Semanal;
use App\Models\Area;
use App\Models\Semanas;
use App\Models\Colaboradores_por_Area;
use App\Models\InformeSemanal;
use App\Models\RegistroResponsabilidad;
use Illuminate\Support\Facades\DB;
use App\Models\AreaSemanaDesactivacion;
use Exception;


class Cumplio_Responsabilidad_SemanalController extends Controller
{
    public function index(Request $request)
    {
        $userData = FunctionHelperController::getUserRol();
        $warning = null;
        if ($userData['isAdmin']) {
            $buscar = $request->buscar_responsabilidad;

            if($buscar) {
                $resultado = $this->buscarResponsabilidades($buscar);
                $areas = $resultado['areas'];
                $warning = $resultado['warning'];
            } else {
                $areas = Area::with('salon')->where('estado', 1)->paginate(12);
            }
        } else if ($userData['isBoss']) {
            $bossAreasId = $userData['Jefeareas']->pluck('area_id');

            // return $bossAreasId;
            $areas = Area::with('salon')->where('estado', 1)->whereIn('id', $bossAreasId)->paginate(12);
        } else {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar áreas. No lo intente denuevo o puede ser baneado.');
        }

        $Years = FunctionHelperController::getYears();
        $countYears = count($Years);
        $currentYear = last($Years);

        $pageData = FunctionHelperController::getPageData($areas);
        $hasPagination = true;

        return view('inspiniaViews.responsabilidades.index', [
            'countYears' => $countYears,
            'currentYear' => $currentYear,
            'areas' => $areas,
            'hasPagination' => $hasPagination,
            'pageData' => $pageData,
            'warning' => $warning
        ]);
    }

    public function getYearsArea($area_id)
    {
        $access = FunctionHelperController::verifyAreaAccess($area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        $Years = FunctionHelperController::getYears();

        return view(
            'inspiniaViews.responsabilidades.years',
            [
                'area_id' => $area_id,
                'Years' => $Years
            ]
        );
    }

    public function getMesesAreas($year, $area_id){
        $access = FunctionHelperController::verifyAreaAccess($area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        $Meses = FunctionHelperController::getMonths();
        $area = Area::findOrFail($area_id);

        $colaboradoresArea = Colaboradores_por_Area::where('area_id', $area_id)->get();
        $colaboradoresAreaIds = $colaboradoresArea->pluck('id');

        // Obtener todas las evaluaciones existentes para el área y año
        $Cumplio_res_Area = Cumplio_Responsabilidad_Semanal::with('semana')
            ->whereIn('colaborador_area_id', $colaboradoresAreaIds)
            ->whereHas('semana', function ($query) use ($year) {
                $query->whereYear('fecha_lunes', $year);
            })
            ->get();

        $agrupadosPorMes = [];

        foreach ($Meses as $mes) {
            $agrupadosPorMes[$mes['nombre']] = [
                'tipo' => 'Accesible',
                'total_semanas' => 0,
                'semanas_evaluadas' => 0,
                'semanas_sin_evaluar' => 0,
                'evaluaciones_existentes' => 0,
            ];
        }

        $semanasTotales = Semanas::whereYear('fecha_lunes', $year)->get();
        $lastWeek = $semanasTotales->last();

        foreach ($Meses as $mes) {
            // Filtrar semanas del mes y año especificados
            $semanasMes = $semanasTotales->filter(function ($semana) use ($mes, $year) {
                return date('Y', strtotime($semana->fecha_lunes)) == $year &&
                    date('m', strtotime($semana->fecha_lunes)) == $mes['id'];
            });

            // Colección para las semanas que se contarán
            $semanasParaConteo = collect();

          foreach ($semanasMes as $semana) {
                // Calcular las fechas de la semana (lunes a domingo)
                $fechaLunes = \Carbon\Carbon::parse($semana->fecha_lunes);
                $fechaDomingo = $fechaLunes->copy()->addDays(6);

                $desactivada = AreaSemanaDesactivacion::where('area_id', $area_id)
                                ->where('fecha_inicio', '<=', $fechaDomingo)
                                ->where('fecha_fin', '>=', $fechaLunes)
                                ->where('desactivada', true)
                                ->where(function($query) use ($year) {
                                    $query->whereYear('fecha_inicio', $year)
                                        ->orWhereYear('fecha_fin', $year);
                                })
                                ->exists();

                // Verificar si la semana tiene evaluaciones
                $tieneEvaluaciones = $Cumplio_res_Area->where('semana_id', $semana->id)->isNotEmpty();

                if ($tieneEvaluaciones) {
                    $semanasParaConteo->push($semana);
                } elseif (!$desactivada) {
                    // Si no está desactivada, verificar si tiene colaboradores activos
                    $colaboradoresArea = Colaboradores_por_Area::where('area_id', $area_id)
                        ->where('semana_inicio_id', '<=', $semana->id)
                        ->get();

                    $countColabsActivos = 0;

                    foreach ($colaboradoresArea as $colabArea) {
                        $inactividades = RegistroActividadController::obtenerInactividad($colabArea->id);
                        $activo = true;

                        foreach ($inactividades as $inactividad) {
                            $semanasInactivas = $inactividad['semanas'];
                            foreach ($semanasInactivas as $semanaInactiva) {
                                if ($semana->id === $semanaInactiva['id']) {
                                    $activo = false;
                                    break 2;
                                }
                            }
                        }

                        if ($activo) {
                            $countColabsActivos++;
                        }
                    }

                    if ($countColabsActivos > 0) {
                        // Incluir la semana si está activa y tiene al menos un colaborador activo
                        $semanasParaConteo->push($semana);
                    }
                }
            }

            if ($semanasParaConteo->count() <= 0) {
                // Determinar si el mes es 'Próximo' o 'Anterior'
                if (date('Y', strtotime($lastWeek->fecha_lunes)) < $year) {
                    $agrupadosPorMes[$mes['nombre']]['tipo'] = 'Próximo';
                } elseif (date('Y', strtotime($lastWeek->fecha_lunes)) == $year) {
                    if (date('m', strtotime($lastWeek->fecha_lunes)) < $mes['id']) {
                        $agrupadosPorMes[$mes['nombre']]['tipo'] = 'Próximo';
                    } else {
                        $agrupadosPorMes[$mes['nombre']]['tipo'] = 'Anterior';
                    }
                } else {
                    $agrupadosPorMes[$mes['nombre']]['tipo'] = 'Anterior';
                }
            } else {
                // Total de semanas para mostrar
                $agrupadosPorMes[$mes['nombre']]['total_semanas'] = $semanasParaConteo->count();

                // Contar semanas con evaluaciones
                $semanasEvaluadas = $Cumplio_res_Area->whereIn('semana_id', $semanasParaConteo->pluck('id'))
                    ->unique('semana_id')
                    ->count();

                $agrupadosPorMes[$mes['nombre']]['semanas_evaluadas'] = $semanasEvaluadas;
                $agrupadosPorMes[$mes['nombre']]['semanas_sin_evaluar'] = $agrupadosPorMes[$mes['nombre']]['total_semanas'] - $semanasEvaluadas;
            }
        }

        return view('inspiniaViews.responsabilidades.meses', ['area_id' => $area_id, 'year' => $year], compact('agrupadosPorMes'));
    }

    public function getFormAsistencias($year, $mes, $area_id){
        $access = FunctionHelperController::verifyAreaAccess($area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        $area = Area::findOrFail($area_id);
        $responsabilidades = Responsabilidades_semanales::get();
        $Meses = FunctionHelperController::getMonths();

        //Obtener las semanas del mes
        $semanasMes = [];
        $semanasMesId = [];
        $semanasTotales = Semanas::get();

        $informesSemanales = InformeSemanal::get();

        foreach ($Meses as $Month) {
            if ($Month['nombre'] === $mes) {
                foreach ($semanasTotales as $semana) {
                    $mesFecha = date('m', strtotime($semana->fecha_lunes));
                    $yearFecha = date('Y', strtotime($semana->fecha_lunes));
                    if ($mesFecha == $Month['id'] && $yearFecha == $year) {
                        // Verificar si la semana está desactivada para esta área
                        $desactivada = AreaSemanaDesactivacion::where('area_id', $area_id)
                            ->where('fecha_inicio', '<=', $semana->fecha_lunes)
                            ->where('fecha_fin', '>=', $semana->fecha_lunes)
                            ->where('desactivada', true)
                            ->exists();

                        // Solo agregar si NO está desactivada
                        if (!$desactivada) {
                            $semanasMes[] = $semana;
                            $semanasMesId[] = $semana->id;
                        }
                    }
                }
            }
        }

        $colaboradoresArea = [];

        foreach ($semanasMes as $index => &$semana) {
            $informesSemanalesArea = InformeSemanal::where('semana_id', $semana->id)->where('area_id', $area_id)->get();
            $semana->informesSemanales = $informesSemanalesArea;

            $colaboradoresArea = Colaboradores_por_Area::where('area_id', $area_id)->where('semana_inicio_id', '<=', $semana->id)->with('colaborador', 'semana')->get();
            $colaboradoresAreaId = $colaboradoresArea->pluck('id');
            $colaboradoresActivosId = [];
            $responsabilidadesSemana = [];

            $datosSemana = FunctionHelperController::getWeekFromToDisponible($semana->id);
            $semana->desde = $datosSemana['desde'];
            $semana->hasta = $datosSemana['hasta'];
            $semana->disponible = $datosSemana['disponible'];

            foreach($responsabilidades as $responsabilidad){
                $activo = RegistroResponsabilidadController::verifyResponsabilidadInactivity($responsabilidad->id, $semana->id);
                if($activo){
                    $responsabilidadesSemana[] = $responsabilidad;
                }
            }
            $semana->responsabilidades = $responsabilidadesSemana;

            $countColabsActivos = 0;
            foreach ($colaboradoresAreaId as $colabKey => $colabAreaId) {
                $inactividades = RegistroActividadController::obtenerInactividad($colabAreaId);
                $activo = true;
                foreach ($inactividades as $inactividad) {
                    $semanasInactivas = $inactividad['semanas'];
                    foreach ($semanasInactivas as $semanaInactiva) {
                        if ($semana->id === $semanaInactiva['id']) {
                            $activo = false;
                            break 2;
                        }
                    }
                }
                if ($activo === true) {
                    $colaboradoresActivosId[] = $colabAreaId;
                    $countColabsActivos++;
                }
            }

            $colaboradoresActivosToAdd = Colaboradores_por_Area::whereIn('id', $colaboradoresActivosId)->get();
            $semana->colaboradores = $colaboradoresActivosToAdd;

            $semanaCumplida = Cumplio_Responsabilidad_Semanal::where("semana_id", $semana->id)->whereIn("colaborador_area_id", $colaboradoresActivosId)->first();
            if ($semanaCumplida) {
                $semana->cumplido = true;
            } else {
                $semana->cumplido = false;
            }

            if ($countColabsActivos < 1) {
                unset($semanasMes[$index]);
            }
        }

        $semanasMes = array_values($semanasMes);
        $registros = Cumplio_Responsabilidad_Semanal::get();
        $comeBackUri = route('responsabilidades.meses', ["year" => $year, "area_id" => $area_id]);

        return view('inspiniaViews.responsabilidades.asistencia', [
            'year' => $year,
            'mes' => $mes,
            'registros' => $registros,
            'area' => $area,
            'responsabilidades' => $responsabilidades,
            'colaboradoresArea' => $colaboradoresArea,
            'semanasMes' => $semanasMes,
            'informesSemanales' => $informesSemanales,
            'comeBackUri' => $comeBackUri,
        ]);
    }

    public function store(Request $request){
    DB::beginTransaction();
    try {
        $request->validate([
            'colaborador_area_id.*' => 'required|integer|min:1',
            'responsabilidad_id.*' => 'required|integer|min:1',
            'semana_id' => 'required|integer|min:1|max:100',
            'cumplio.*' => 'required|boolean|min:0|max:1',
            'year' => 'required|integer',
            'mes' => 'required|string',
            'area_id' => 'required|integer',
        ]);

        $year = $request->year;
        $mes = $request->mes;
        $area_id = $request->area_id;
        $semana_id = $request->semana_id;

        $access = FunctionHelperController::verifyAreaAccess($area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
        }

        // Verificar que estemos en una semana posterior a la que se esta registrando
        $thisWeekMonday = Carbon::today()->startOfWeek()->toDateString();
        $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();
        $semana = Semanas::find($semana_id);

        if (!$semana) {
            DB::rollBack();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                ->with('error', 'La semana seleccionada no existe.')
                ->with('current_semana_id', $request->index);
        }

        $today = Carbon::today();
        $isSunday = $today->dayOfWeek == Carbon::SUNDAY;

        if ($isSunday) {
            $nextWeekMonday = $today->copy()->addDay()->toDateString();
            $nextSemana = Semanas::where('fecha_lunes', $nextWeekMonday)->first();

            if ($semana->id >= ($nextSemana ? $nextSemana->id : PHP_INT_MAX)) {
                DB::rollBack();
                return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                    ->with('EvaluacionWarning', 'No se puede evaluar semanas que aún no concluyen.')
                    ->with('current_semana_id', $request->index);
            }
        } else {
            if ($semana->id >= $thisSemana->id) {
                DB::rollBack();
                return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                    ->with('EvaluacionWarning', 'No se puede evaluar semanas que aún no concluyen.')
                    ->with('current_semana_id', $request->index);
            }
        }

        $informeSemanal = InformeSemanal::where('semana_id', $semana_id)
            ->where('area_id', $area_id)
            ->first();

        if (!$informeSemanal) {
            DB::rollBack();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                ->with('EvaluacionWarning', 'Debe crear un informe semanal antes de registrar las responsabilidades.')
                ->with('current_semana_id', $request->index);
        }

        $responsabilidades = Responsabilidades_semanales::get();
        $responsabilidadesSemana = [];
        foreach($responsabilidades as $resp){
            $activo = RegistroResponsabilidadController::verifyResponsabilidadInactivity($resp->id, $semana->id);
            if($activo){
                $responsabilidadesSemana[] = $resp;
            }
        }

        // Verificar si ya existen registros para esta semana y área
        $existingRecords = Cumplio_Responsabilidad_Semanal::where('semana_id', $semana_id)
            ->whereIn('colaborador_area_id', $request->colaborador_area_id)
            ->count();

        if ($existingRecords > 0) {
            DB::rollBack();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                ->with('EvaluacionWarning', 'Ya existen registros para esta semana. No se puede duplicar la evaluación.')
                ->with('current_semana_id', $request->index);
        }

        $contador = 0;
        $indiceColab = 0;
        foreach ($request->responsabilidad_id as $keyResp => $responsabilidad_id) {
            $colaborador_area_id = $request->colaborador_area_id[$indiceColab];

            Cumplio_Responsabilidad_Semanal::create([
                "colaborador_area_id" => $colaborador_area_id,
                "responsabilidad_id" => $responsabilidad_id,
                "semana_id" => $semana_id,
                "cumplio" => $request->cumplio[$keyResp]
            ]);

            $contador++;

            if ($contador >= count($responsabilidadesSemana)) {
                $contador = 0;
                $indiceColab++;
            }
        }

        DB::commit();
        return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
            ->with('success', 'Se guardó correctamente.');
    } catch (Exception $e) {
        DB::rollback();
        return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
            ->with('error', 'Ocurrió un error: ' . $e->getMessage());
    }
}

    public function actualizar(Request $request, $semana_id, $area_id)
    {
        DB::beginTransaction();
        try {
            $access = FunctionHelperController::verifyAreaAccess($area_id);

            if (!$access) {
                return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para evaluar esa area. No lo intente denuevo o puede ser baneado.');
            }

            $request->validate([
                'colaborador_area_id.*' => 'sometimes|integer',
                'responsabilidad_id.*' => 'sometimes|integer',
                'cumplio.*' => 'sometimes|boolean',
                'year' => 'required|integer',
                'mes' => 'required|string',
            ]);
            $year = $request->year;
            $mes = $request->mes;
            $today = Carbon::today();
            $isSunday = $today->dayOfWeek == Carbon::SUNDAY;
            $semana = Semanas::find($semana_id);
            $thisWeekMonday = $today->copy()->startOfWeek()->toDateString();
            $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();

            if ($isSunday) {
                $nextWeekMonday = $today->copy()->addDay()->toDateString();
                $nextSemana = Semanas::where('fecha_lunes', $nextWeekMonday)->first();

                if ($semana->id >= ($nextSemana ? $nextSemana->id : PHP_INT_MAX)) {
                    DB::rollBack();
                    return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                        ->with('EvaluacionWarning', 'No se puede evaluar semanas que aún no concluyen.')
                        ->with('current_semana_id', $request->index);
                }
            } else {
                if ($semana->id >= $thisSemana->id) {
                    DB::rollBack();
                    return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])
                        ->with('EvaluacionWarning', 'No se puede evaluar semanas que aún no concluyen.')
                        ->with('current_semana_id', $request->index);
                }
            }
            //Verificar solo por si acaso
            $colaboradoresAreaId = Colaboradores_por_Area::where('area_id', $area_id)->get()->pluck('id');
            $registros = Cumplio_Responsabilidad_Semanal::where('semana_id', $semana_id)
                ->whereIn('colaborador_area_id', $colaboradoresAreaId)
                ->get();

            foreach ($registros as $index => $registro) {
                $registro->cumplio = $request->cumplio[$index];
                $registro->save();
            }

            DB::commit();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])->with('success', 'Se guardó correctamente.');
        } catch (Exception $e) {
            return $e;
            DB::rollback();
            return redirect()->route('responsabilidades.asis', ['year' => $year, 'mes' => $mes, 'area_id' => $area_id])->with('error', 'Ocurrió un error.');
        }
    }


    public function getMonthProm($year, $mes, $area_id)
    {
        $access = FunctionHelperController::verifyAreaAccess($area_id);

        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para ver esa area. No lo intente denuevo o puede ser baneado.');
        }
        $area = Area::findOrFail($area_id);
        $responsabilidades = Responsabilidades_semanales::get();

        $Meses = FunctionHelperController::getMonths();

        $semanasMes = [];

        foreach ($Meses as $Month) {
            if ($Month['nombre'] == $mes) {
                $semanasTotales = Semanas::get();
                foreach ($semanasTotales as $semana) {
                    $mesFecha = date('m', strtotime($semana->fecha_lunes));
                    $yearFecha = date('Y', strtotime($semana->fecha_lunes));
                    if ($mesFecha == $Month['id'] && $yearFecha == $year) {
                        $semanasMes[] = $semana;
                    }
                }
            }
        }
        $totalSemanas = count($semanasMes);

        $firstWeek = $semanasMes[0];
        $firstWeek->fecha_lunes = date("d/m/Y", strtotime($firstWeek->fecha_lunes));
        $lastWeek = end($semanasMes);
        $fechaFinal = strtotime($lastWeek->fecha_lunes);
        $fechaFinal = strtotime('+4 days', $fechaFinal);
        $fechaFinal = date("d/m/Y", $fechaFinal);
        $lastWeek->fecha_lunes = $fechaFinal;

        $colaboradoresMes = [];
        //Recorrer semanas e ir agregando los colaboradores que tienen esten activos esa semana y tengan alguna responsabilidad cumplida en esa semana
        $responsabilidadesMes = [];
        foreach ($semanasMes as $semana) {
            $colaboradoresAreaId = Colaboradores_por_Area::where('area_id', $area_id)->where('semana_inicio_id', '<=', $semana->id)->with('colaborador', 'semana')->get()->pluck('id');
            $colaboradoresActivosId = [];

            foreach($responsabilidades as $responsabilidad){
                //Verificar si la responsabilidad estaba activa
                $activo = RegistroResponsabilidadController::verifyResponsabilidadInactivity($responsabilidad->id, $semana->id);
                if($activo){
                    //Si esta activa verificar si ya esta en el array
                    if(isset($responsabilidadesMes[$responsabilidad->nombre])){
                        //Si ya esta en el array se le agrega 1 a su conteo de semanas
                        $responsabilidadesMes[$responsabilidad->nombre]['conteoSemanas']++;
                    } else{
                        //Si no esta en el array se agrega con un conteo de 1
                        $responsabilidadesMes[$responsabilidad->nombre] = [
                            "id" => $responsabilidad->id,
                            "nombre" => $responsabilidad->nombre,
                            "conteoSemanas" => 1,
                        ];
                    }

                }
            }

            foreach($colaboradoresAreaId as $colabAreaId){
                $activo = RegistroActividadController::verifyColaboradorInactivity($colabAreaId, $semana->id);
                // Si el colaborador está activo, añadirlo al array temporal
                if ($activo === true) {
                    $colaboradoresActivosId[] = $colabAreaId;
                }
            }
            $colaboradoresActivosToAdd = Colaboradores_por_Area::whereIn('id', $colaboradoresActivosId)->get();

            foreach ($colaboradoresActivosToAdd as $colaboradorActivoToAdd) {
                $semanaCumplida = Cumplio_Responsabilidad_Semanal::where("semana_id", $semana->id)->where("colaborador_area_id", $colaboradorActivoToAdd->id)->first();
                if($semanaCumplida){
                    $existe = false;
                    foreach ($colaboradoresMes as &$colaboradorMes) {
                        if ($colaboradorMes['id'] === $colaboradorActivoToAdd->id) {
                            $existe = true;
                            break;
                        }
                    }
                    // Si el colaborador no está en $colaboradoresMes, agregarlo
                    if (!$existe) {
                        $candidato = $colaboradorActivoToAdd->colaborador->candidato;
                        $colaboradoresMes[] = [
                            'id' => $colaboradorActivoToAdd->id,
                            'colaborador' => $colaboradorActivoToAdd,
                            'nombre' => $candidato->nombre . ' ' . $candidato->apellido,
                        ];
                    }
                }
            }
        }
        foreach ($colaboradoresMes as &$colaboradorMes) {
            $promedioColab = FunctionHelperController::promedioColaborador($colaboradorMes['colaborador']->colaborador_id, $semanasMes);
            $colaboradorMes['sumNotas'] = $promedioColab['notasTotales'];
            $colaboradorMes['promedio'] = $promedioColab['promedioNotas'];
            $colaboradorMes['total'] = $promedioColab['promedio'];
        }
        unset($colaboradorMes);

        return view('inspiniaViews.responsabilidades.promediomes', [
            "colaboradoresMes" => $colaboradoresMes,
            "responsabilidades" => $responsabilidades,
            "responsabilidadesMes" => $responsabilidadesMes,
            "year" => $year,
            "mes" => $mes,
            "area" => $area,
            "totalSemanas" => $totalSemanas,
            "firstWeek" => $firstWeek,
            "lastWeek" => $lastWeek
        ]);
    }


    public function getMonthsProm(Request $request, $area_id)
    {
        $access = FunctionHelperController::verifyAreaAccess($area_id);

        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para ver esa area. No lo intente denuevo o puede ser baneado.');
        }
        $area = Area::findOrFail($area_id);
        $responsabilidades = Responsabilidades_semanales::get();

        $year = $request->year;
        $selectedMonths = json_decode($request->input('selected_months'), true);

        $Meses = FunctionHelperController::getMonths();
        $colaboradoresMeses = [];
        $semanasMeses = [];

        $responsabilidadesMeses = [];

        foreach ($selectedMonths as $mes) {
            $semanasMes = [];

            foreach ($Meses as $Month) {
                if ($Month['nombre'] == $mes) {
                    $semanasTotales = Semanas::get();
                    foreach ($semanasTotales as $semana) {
                        $mesFecha = date('m', strtotime($semana->fecha_lunes));
                        $yearFecha = date('Y', strtotime($semana->fecha_lunes));
                        if ($mesFecha == $Month['id'] && $yearFecha == $year) {
                            $semanasMes[] = $semana;
                        }
                    }
                }
            }
            $semanasMeses = array_merge($semanasMeses, $semanasMes);
            foreach ($semanasMes as $semana) {
                foreach($responsabilidades as $responsabilidad){
                    //Verificar si la responsabilidad estaba activa
                    $activo = RegistroResponsabilidadController::verifyResponsabilidadInactivity($responsabilidad->id, $semana->id);
                    if($activo){
                        //Si esta activa verificar si ya esta en el array
                        if(isset($responsabilidadesMeses[$responsabilidad->nombre])){
                            //Si ya esta en el array se le agrega 1 a su conteo de semanas
                            $responsabilidadesMeses[$responsabilidad->nombre]['conteoSemanas']++;
                        } else{
                            //Si no esta en el array se agrega con un conteo de 1
                            $responsabilidadesMeses[$responsabilidad->nombre] = [
                                "id" => $responsabilidad->id,
                                "nombre" => $responsabilidad->nombre,
                                "conteoSemanas" => 1,
                            ];
                        }
                    }
                }


                $colaboradoresAreaId = Colaboradores_por_Area::where('area_id', $area_id)->where('semana_inicio_id', '<=', $semana->id)->with('colaborador', 'semana')->get()->pluck('id');
                $colaboradoresActivosId = [];

                foreach ($colaboradoresAreaId as $colabAreaId) {
                    $activo = RegistroActividadController::verifyColaboradorInactivity($colabAreaId, $semana->id);

                    // Si el colaborador está activo, añadirlo al array temporal
                    if ($activo === true) {
                        $colaboradoresActivosId[] = $colabAreaId;
                    }
                }
                $colaboradoresActivosToAdd = Colaboradores_por_Area::whereIn('id', $colaboradoresActivosId)->get();

                foreach ($colaboradoresActivosToAdd as $colaboradorActivoToAdd) {
                    $semanaCumplida = Cumplio_Responsabilidad_Semanal::where("semana_id", $semana->id)->where("colaborador_area_id", $colaboradorActivoToAdd->id)->firstOrNew();
                    // Verificar si el colaborador ya está en $colaboradoresMeses
                    if ($semanaCumplida) {
                        $existe = false;
                        foreach ($colaboradoresMeses as &$colaboradorMes) {
                            if ($colaboradorMes['id'] === $colaboradorActivoToAdd->id) {
                                $existe = true;
                                break;
                            }
                        }

                        // Si el colaborador no está en $colaboradoresMeses, agregarlo
                        if (!$existe) {
                            $candidato = $colaboradorActivoToAdd->colaborador->candidato;
                            $colaboradoresMeses[] = [
                                'id' => $colaboradorActivoToAdd->id,
                                'colaborador' => $colaboradorActivoToAdd,
                                'nombre' => $candidato->nombre . ' ' . $candidato->apellido,
                            ];
                        }
                    }
                }
            }
        }

        foreach ($colaboradoresMeses as &$colaboradorMes) {
            $promedioColab = FunctionHelperController::promedioColaborador($colaboradorMes['colaborador']->colaborador_id, $semanasMeses);
            $colaboradorMes['sumNotas'] = $promedioColab['notasTotales'];
            $colaboradorMes['promedio'] = $promedioColab['promedioNotas'];
            $colaboradorMes['total'] = $promedioColab['promedio'];
        }
        unset($colaboradorMes);
        $totalSemanas = count($semanasMeses);

        $firstWeek = $semanasMeses[0];
        $firstWeek->fecha_lunes = date("d/m/Y", strtotime($firstWeek->fecha_lunes));
        $lastWeek = end($semanasMeses);
        $fechaFinal = strtotime($lastWeek->fecha_lunes);
        $fechaFinal = strtotime('+4 days', $fechaFinal);
        $fechaFinal = date("d/m/Y", $fechaFinal);
        $lastWeek->fecha_lunes = $fechaFinal;

        return view('inspiniaViews.responsabilidades.promediomeses', [
            "colaboradoresMeses" => $colaboradoresMeses,
            "responsabilidades" => $responsabilidades,
            "responsabilidadesMeses" => $responsabilidadesMeses,
            "selectedMonths" => $selectedMonths,
            "area" => $area,
            "totalSemanas" => $totalSemanas,
            "firstWeek" => $firstWeek,
            "lastWeek" => $lastWeek
        ]);
    }

    public function buscarResponsabilidades($busqueda) {
        $consulta = Area::with('salon')->where('estado', 1)->orderBy('especializacion', 'asc');

        if(!empty($busqueda)) {
            $consulta->where('especializacion', 'LIKE', '%' . $busqueda . '%');
        }

        $areas = $consulta->paginate(12);

        $warning = null;

        if($areas->isEmpty()) {
            $warning = 'No se encontraron responsabilidades que coincidan con su búsqueda';
        }

        return [
            'areas' => $areas,
            'warning' => $warning
        ];
    }
}
