<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Colaboradores;
use App\Models\Colaboradores_por_Area;
use App\Models\Cumplio_Responsabilidad_Semanal;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Horarios_Presenciales;
use App\Models\Responsabilidades_semanales;
use App\Models\Semanas;
use App\Models\ReunionesProgramadas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\UsuarioAdministrador;
use App\Models\UsuarioJefeArea;
use Illuminate\Support\Facades\DB;

class HomePageController extends Controller
{
    public function home(){

        $userData = FunctionHelperController::getUserRol();
        $returning = [];

        // $returning['selectedAreasColab'] = [];

        if($userData['isAdmin']){
            $areasProm = $this->getMonthPromAreas();
            $reunionesProgramadas = $this->getTodayProgramReu();
            $areas = $this->getAreasToday();
            $asistencia = $this->getAsistenciaMesAnterior();

            $returning['areasProm'] = $areasProm;
            $returning['reunionesProgramadas'] = $reunionesProgramadas;
            $returning['areas'] = $areas;
            $returning['asistencia'] = $asistencia;
            // return $asistencia;
        }

        if($userData['isBoss']){
            $areasJefeId = $userData['Jefeareas']->pluck('area_id');
            $selectedAreas = Area::whereIn('id', $areasJefeId)->where('estado', 1)->get();
            foreach($selectedAreas as $area){
                $colaboradoresAreaCount = Colaboradores_por_Area::where('area_id', $area->id)->where('estado', 1)->count();
                $area->count_colabs = $colaboradoresAreaCount;
            }

            $returning['selectedAreas'] = $selectedAreas;
        }

        // if ($userData['isColab'] && isset($userData['colabsArea']) && $userData['colabsArea']) {
        //     $areasColabId = is_array($userData['colabsArea']) ? collect($userData['colabsArea'])->pluck('area_id') : collect([$userData['colabsArea']->area_id]);

        //     $selectedAreasColab = Area::whereIn('id', $areasColabId)
        //         ->withCount(['colaborador_por_area' => function ($query) {
        //             $query->where('estado', 1);
        //         }])
        //         ->get();

        //     $returning['selectedAreasColab'] = $selectedAreasColab;
        // }
        if ($userData['isColab'] && isset($userData['colabsArea']) && $userData['colabsArea']) {
            $areasColabId = is_array($userData['colabsArea'])
                ? collect($userData['colabsArea'])->pluck('area_id')
                : collect([$userData['colabsArea']->area_id]);
            // sacar a los que son jefes de area para evitar duplicados
            $areasJefeId = $userData['Jefeareas']->pluck('area_id');

            $selectedAreasColab = Area::whereIn('id', $areasColabId)->whereNotIn('id', $areasJefeId)->where('estado', 1)->withCount(['colaborador_por_area' => function ($query) {
                    $query->where('estado', 1);
                }])->get();

            $returning['selectedAreasColab'] = $selectedAreasColab;
        }

        // return $returning;
        return view('dashboard', $returning);
    }

    public function getAsistenciaMesAnterior() {
        $inicioMesAnterior = Carbon::today()->subMonth()->startOfMonth()->toDateString();
        $finMesAnterior = Carbon::today()->subMonth()->endOfMonth()->toDateString();

        $semanasDelMesAnterior = Semanas::whereBetween('fecha_lunes', [$inicioMesAnterior, $finMesAnterior])
                              ->orWhere(function($query) use ($inicioMesAnterior, $finMesAnterior) {
                                  $query->where('fecha_lunes', '<', $inicioMesAnterior)
                                        ->where(DB::raw('DATE_ADD(fecha_lunes, INTERVAL 6 DAY)'), '>=', $inicioMesAnterior);
                              })
                              ->get();

        if ($semanasDelMesAnterior->isEmpty()) {
            return [
                'asistieron' => 0,
                'faltaron' => 0,
                'faltantes' => [],
                'mes' => Carbon::today()->subMonth()->format('Y-m')
            ];
        }

        $idsSemanasDelMesAnterior = $semanasDelMesAnterior->pluck('id')->toArray();

        $responsabilidadAsistencia = Responsabilidades_semanales::where('nombre', 'Asistencia diaria')->first();
        $responsabilidadJustificacion = Responsabilidades_semanales::where('nombre', 'Faltas Justificadas')->first();

        if (!$responsabilidadAsistencia) {
            return [
                'asistieron' => 0,
                'faltaron' => 0,
                'faltantes' => [],
                'mes' => Carbon::today()->subMonth()->format('Y-m')
            ];
        }

        $colaboradoresActivos = Colaboradores_por_Area::where('estado', 1)->get();
        $idsColaboradoresActivos = $colaboradoresActivos->pluck('id')->toArray();

        $registrosAsistencia = Cumplio_Responsabilidad_Semanal::where('responsabilidad_id', $responsabilidadAsistencia->id)
                                ->whereIn('semana_id', $idsSemanasDelMesAnterior)
                                ->whereIn('colaborador_area_id', $idsColaboradoresActivos)
                                ->get();

        $registrosJustificacion = [];
        if ($responsabilidadJustificacion) {
            $registrosJustificacion = Cumplio_Responsabilidad_Semanal::where('responsabilidad_id', $responsabilidadJustificacion->id)
                                    ->whereIn('semana_id', $idsSemanasDelMesAnterior)
                                    ->whereIn('colaborador_area_id', $idsColaboradoresActivos)
                                    ->get();
        }

        $idsColaboradoresAsistieron = $registrosAsistencia->where('cumplio', 1)
                                                         ->pluck('colaborador_area_id')
                                                         ->unique()
                                                         ->count();

        $faltasPorColaborador = [];
        $idsColaboradoresFaltaron = [];

        foreach ($idsColaboradoresActivos as $colaboradorId) {
            $ausenciasReales = 0;

            foreach ($idsSemanasDelMesAnterior as $semanaId) {
                $registroAsistencia = $registrosAsistencia->where('colaborador_area_id', $colaboradorId)
                                                         ->where('semana_id', $semanaId)
                                                         ->first();

                $registroJustificacion = $registrosJustificacion->where('colaborador_area_id', $colaboradorId)
                                                              ->where('semana_id', $semanaId)
                                                              ->first();

                if ($registroAsistencia && $registroAsistencia->cumplio == 0) {
                    if (!$registroJustificacion || $registroJustificacion->cumplio == 0) {
                        $ausenciasReales++;
                    }
                }
            }

            if ($ausenciasReales > 0) {
                $idsColaboradoresFaltaron[] = $colaboradorId;

                $colaboradorArea = $colaboradoresActivos->where('id', $colaboradorId)->first();
                if ($colaboradorArea) {
                    $colaborador = Colaboradores::with('candidato')->find($colaboradorArea->colaborador_id);
                    if ($colaborador && $colaborador->candidato) {
                        $area = Area::find($colaboradorArea->area_id);
                        $faltasPorColaborador[] = [
                            'id' => $colaborador->id,
                            'nombre' => $colaborador->candidato->nombre . ' ' . $colaborador->candidato->apellido,
                            'area' => $area ? ($area->especializacion ?? 'Sin área') : 'Sin área',
                            'estado' => 'Ausente',
                            'veces_faltadas' => $ausenciasReales
                        ];
                    }
                }
            }
        }

        $colaboradoresFaltaron = count(array_unique($idsColaboradoresFaltaron));

        usort($faltasPorColaborador, function($a, $b) {
            return $b['veces_faltadas'] - $a['veces_faltadas'];
        });

        return [
            'asistieron' => $idsColaboradoresAsistieron,
            'faltaron' => $colaboradoresFaltaron,
            'faltantes' => $faltasPorColaborador,
            'mes' => Carbon::today()->subMonth()->format('Y-m')
        ];
    }


    function getMonthPromAreas() {
        $areas = Area::where('estado', 1)->get();
        $responsabilidades = Responsabilidades_semanales::get();
        $today = Carbon::now()->format('Y-m-d');

        $mes = date('m', strtotime($today));
        $year = date('Y', strtotime($today));
        $thisWeek = FunctionHelperController::findThisWeek();

        if($thisWeek){
            $semanaPasada = Semanas::where('id', $thisWeek->id-1)->first();
            if($semanaPasada){
                $fecha = $semanaPasada->fecha_lunes;
                $mesSemanaPasada = date('m', strtotime($fecha));
                $yearSemanaPasada = date('Y', strtotime($fecha));

                if($yearSemanaPasada == $year){
                    if($mesSemanaPasada != $mes){
                        $mes = $mesSemanaPasada;
                    }
                }else{
                    $mes = $mesSemanaPasada;
                    $year = $yearSemanaPasada;
                }
            }
        }

        $Meses = FunctionHelperController::getMonths();
        $semanasTotales = Semanas::get();

        $semanasMes = [];

        foreach ($Meses as $Month) {
            if ($Month['id'] == $mes) {
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
        $areasProm = [];

        foreach ($areas as $area) {
            $area_id = $area->id;
            $colaboradoresMes = [];

            foreach ($semanasMes as $semana) {
                $colaboradoresArea = Colaboradores_por_Area::where('area_id', $area_id)
                    ->where('semana_inicio_id', '<=', $semana->id)
                    ->with('colaborador', 'semana')
                    ->get();

                $colaboradoresAreaId = $colaboradoresArea->pluck('id');
                $colaboradoresActivosId = [];

                foreach($colaboradoresAreaId as $colabAreaId){
                    $inactividades = RegistroActividadController::obtenerInactividad($colabAreaId);
                    $activo = true;

                    foreach($inactividades as $inactividad){
                        $semanasInactivas = $inactividad['semanas'];
                        foreach($semanasInactivas as $semanaInactiva){
                            if($semana->id === $semanaInactiva['id']){
                                $activo = false;
                                break 2;
                            }
                        }
                    }

                    if ($activo === true) {
                        $colaboradoresActivosId[] = $colabAreaId;
                    }
                }

                $colaboradoresActivosToAdd = Colaboradores_por_Area::whereIn('id', $colaboradoresActivosId)->get();

                foreach ($colaboradoresActivosToAdd as $colaboradorActivoToAdd) {
                    $semanaCumplida = Cumplio_Responsabilidad_Semanal::where("semana_id", $semana->id)
                        ->where("colaborador_area_id", $colaboradorActivoToAdd->id)
                        ->first();

                    if($semanaCumplida){
                        $existe = false;
                        foreach ($colaboradoresMes as &$colaboradorMes) {
                            if ($colaboradorMes['id'] === $colaboradorActivoToAdd->id) {
                                $colaboradorMes['semanasCount']++;
                                $colaboradorMes['semanas'][] = $semana->id;
                                $existe = true;
                                break;
                            }
                        }

                        if (!$existe) {
                            $candidato = $colaboradorActivoToAdd->colaborador->candidato;
                            $colaboradoresMes[] = [
                                'id' => $colaboradorActivoToAdd->id,
                                'semanasCount' => 1,
                                'semanas' => [$semana->id],
                                'colaborador' => $colaboradorActivoToAdd,
                                'nombre' => $candidato->nombre . ' ' . $candidato->apellido,
                            ];
                        }
                    }
                }
            }

            // Calcular notas por responsabilidad
            foreach ($colaboradoresMes as $index => &$colaboradorMes) {
                $sumNotes = [];
                foreach ($responsabilidades as $responsabilidad) {
                    $nombreResponsabilidad = $responsabilidad->nombre;
                    $sumNotes[$nombreResponsabilidad] = 0;
                }

                foreach ($colaboradorMes['semanas'] as $semanaId) {
                    $registrosCumplidosSemana = Cumplio_Responsabilidad_Semanal::where('semana_id', $semanaId)
                        ->where('colaborador_area_id', $colaboradorMes['id'])
                        ->get();

                    foreach ($registrosCumplidosSemana as $registro) {
                        $valorCumplio = $registro->cumplio == 1 ? 20 : 0;

                        foreach($responsabilidades as $responsabilidad){
                            if($responsabilidad->id == $registro->responsabilidad_id) {
                                $nombreResponsabilidad = $responsabilidad->nombre;
                                $sumNotes[$nombreResponsabilidad] += $valorCumplio;
                            }
                        }
                    }
                }
                $colaboradorMes['sumNotas'] = $sumNotes;
            }
            unset($colaboradorMes);

            // Calcular promedios normalizados
            foreach ($colaboradoresMes as $index => $colaboradorMes) {
                $semanasCount = $colaboradoresMes[$index]['semanasCount'];
                $PromNotas = [];

                foreach (array_keys($colaboradoresMes[$index]['sumNotas']) as $responsabilidad) {
                    // Normalizar para que no exceda 20
                    $promedioNormalizado = min(
                        20,
                        ($colaboradoresMes[$index]['sumNotas'][$responsabilidad]) / $semanasCount
                    );
                    $PromNotas[$responsabilidad] = number_format($promedioNormalizado, 1);
                }

                $colaboradoresMes[$index]['promedio'] = $PromNotas;

                // Calcular total normalizado
                $totalPromedio = number_format(
                    (array_sum($PromNotas)) / $responsabilidades->count(),
                    1
                );
                $colaboradoresMes[$index]['total'] = min(20, $totalPromedio);
            }

            // Calcular promedio del área
            if(count($colaboradoresMes) > 0){
                $areaTotal = 0;
                foreach($colaboradoresMes as $colaboradorMes){
                    $areaTotal += $colaboradorMes['total'];
                }

                $areaProm = number_format(
                    $areaTotal / count($colaboradoresMes),
                    0
                );

                $areasProm[] = [
                    "area" => $area,
                    "promedio" => min(20, $areaProm)
                ];
            }
        }

        return $areasProm;
    }



    function getTodayProgramReu(){
        $today = Carbon::now()->format('Y-m-d');
        // return $today;
        $reunionesProgramadas = ReunionesProgramadas::where('fecha', $today)->get();
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
        return $reunionesProgramadas;
    }

    function getAreasToday(){
        $dia_today = Carbon::now()->format('l');
        $dias = [
            "Monday" => "Lunes",
            "Tuesday" => "Martes",
            "Wednesday" => "Miércoles",
            "Thursday" => "Jueves",
            "Friday" => "Viernes",
            "Saturday" => "Sábado",
            "Sunday" => "Domingo"
        ];

        $dia_español = $dias[$dia_today];
        $horariosToday = Horarios_Presenciales::where('dia', $dia_español)->get();
        $horariosAreasToday = Horario_Presencial_Asignado::with('area', 'horario_presencial')->whereIn('horario_presencial_id', $horariosToday->pluck('id'))->get();
        $areasToday = [];
        foreach($horariosAreasToday as $horario){
            if($horario->area->estado == 1){
                $horaInicial = (int) date('H', strtotime($horario->horario_presencial->hora_inicial));
                $horaFinal = (int) date('H', strtotime($horario->horario_presencial->hora_final));
                $areasToday[] =
                    [
                        'especializacion' => $horario->area->especializacion,
                        'color' => $horario->area->color_hex,
                        'hora_inicial' => $horaInicial,
                        'hora_final' => $horaFinal,
                        'url' => route('areas.getHorario', $horario->area->id),
                    ];
            }
        }

        return $areasToday;
    }

}
