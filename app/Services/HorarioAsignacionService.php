<?php

namespace App\Services;

use App\Models\AsignacionPracticante;
use App\Models\Colaboradores;
use App\Models\Colaboradores_por_Area;
use App\Models\ConfiguracionBloqueHorario;
use App\Models\DisponibilidadPracticante;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Horario_de_Clases;
use App\Models\PropuestaHorario;
use App\Models\PropuestaHorarioDetalle;
use App\Models\SolicitudCambioHorario;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HorarioAsignacionService
{
    private int $nodosVisitados = 0;
    private int $limiteNodos = 5000;

    /**
     * Crea la configuración del motor para los bloques de horario que ya existen
     * en el sistema corporativo. No reemplaza los horarios base: los complementa
     * con turno, modalidad y límites de aforo para el prototipo.
     */
    public function sincronizarBloques(): Collection
    {
        $bloquesBase = Horario_Presencial_Asignado::with(['horario_presencial', 'area'])
            ->whereHas('area', fn ($q) => $q->where('estado', 1))
            ->get();

        foreach ($bloquesBase as $bloqueBase) {
            $hora = $bloqueBase->horario_presencial?->hora_inicial ?? '08:00:00';
            ConfiguracionBloqueHorario::firstOrCreate(
                ['horario_presencial_asignado_id' => $bloqueBase->id],
                [
                    'turno' => $this->inferirTurno($hora),
                    'cupo_min' => 2,
                    'cupo_max' => 5,
                    'modalidad' => 'Presencial',
                    'estado' => true,
                ]
            );
        }

        return ConfiguracionBloqueHorario::with([
            'bloqueBase.area',
            'bloqueBase.horario_presencial',
        ])->orderBy('id')->get();
    }

    public function generarPropuesta(?int $userId): PropuestaHorario
    {
        $inicioProceso = microtime(true);
        $bloques = $this->sincronizarBloques()
            ->filter(fn ($b) => $b->estado && $b->bloqueBase && $b->bloqueBase->area && $b->bloqueBase->horario_presencial)
            ->values();

        if ($bloques->isEmpty()) {
            throw new RuntimeException('No existen bloques activos configurados por área. Configure primero los horarios presenciales de las áreas.');
        }

        foreach ($bloques as $bloque) {
            if ($bloque->cupo_min < 1 || $bloque->cupo_max < $bloque->cupo_min) {
                throw new RuntimeException("El bloque #{$bloque->id} tiene cupos inválidos. Revise mínimo y máximo antes de generar.");
            }
        }

        $colaboradores = Colaboradores::with('candidato')
            ->where('estado', 1)
            ->get();

        $dominios = [];
        foreach ($bloques as $bloque) {
            $dominios[$bloque->id] = [];
            foreach ($colaboradores as $colaborador) {
                $evaluacion = $this->evaluarCandidato($colaborador, $bloque);
                if ($evaluacion['factible']) {
                    $dominios[$bloque->id][] = [
                        'colaborador_id' => $colaborador->id,
                        'puntaje' => $evaluacion['puntaje'],
                        'explicacion' => $evaluacion['explicacion'],
                    ];
                }
            }
            usort($dominios[$bloque->id], fn ($a, $b) => $b['puntaje'] <=> $a['puntaje']);
        }

        // Heurística CSP: procesar primero el bloque con menor dominio disponible.
        $bloquesOrdenados = $bloques->sortBy(fn ($b) => count($dominios[$b->id]))->values()->all();
        $solucion = [];
        $agendaTemporal = [];
        $this->nodosVisitados = 0;

        $puedeSerCompleta = true;
        foreach ($bloquesOrdenados as $bloque) {
            if (count($dominios[$bloque->id]) < $bloque->cupo_min) {
                $puedeSerCompleta = false;
                break;
            }
        }

        $factible = $puedeSerCompleta
            ? $this->backtracking($bloquesOrdenados, $dominios, 0, $solucion, $agendaTemporal)
            : false;

        $pendientes = [];
        if (!$factible) {
            [$solucion, $pendientes] = $this->construirSolucionParcial($bloquesOrdenados, $dominios);
        }

        $puntajeTotal = 0;
        $cantidadAsignaciones = 0;
        foreach ($solucion as $items) {
            foreach ($items as $item) {
                $puntajeTotal += $item['puntaje_final'] ?? $item['puntaje'];
                $cantidadAsignaciones++;
            }
        }

        $tiempoMs = round((microtime(true) - $inicioProceso) * 1000, 2);

        $propuesta = DB::transaction(function () use ($userId, $factible, $puntajeTotal, $pendientes, $solucion, $bloques, $cantidadAsignaciones, $colaboradores, $tiempoMs) {
            PropuestaHorario::where('estado', 'borrador')->update(['estado' => 'descartada']);

            $propuesta = PropuestaHorario::create([
                'generado_por' => $userId,
                'estado' => 'borrador',
                'factible' => $factible,
                'puntaje_total' => $puntajeTotal,
                'pendientes' => $pendientes,
                'resumen' => [
                    'algoritmo' => 'Scoring explicable + CSP con backtracking',
                    'bloques_evaluados' => $bloques->count(),
                    'practicantes_evaluados' => $colaboradores->count(),
                    'asignaciones_propuestas' => $cantidadAsignaciones,
                    'nodos_backtracking' => $this->nodosVisitados,
                    'tiempo_generacion_ms' => $tiempoMs,
                    'restricciones_duras' => [
                        'Disponibilidad declarada',
                        'Sin cruce con clases',
                        'Área autorizada',
                        'Sin solapamiento entre bloques',
                        'Cupo mínimo y máximo',
                    ],
                    'criterio_blando' => 'Prioriza compatibilidad y balance de carga entre practicantes.',
                ],
            ]);

            foreach ($solucion as $bloqueId => $items) {
                foreach ($items as $item) {
                    PropuestaHorarioDetalle::create([
                        'propuesta_id' => $propuesta->id,
                        'colaborador_id' => $item['colaborador_id'],
                        'configuracion_bloque_id' => $bloqueId,
                        'puntaje' => $item['puntaje_final'] ?? $item['puntaje'],
                        'explicacion' => $item['explicacion'],
                    ]);
                }
            }

            return $propuesta;
        });

        return $propuesta->load([
            'detalles.colaborador.candidato',
            'detalles.bloque.bloqueBase.area',
            'detalles.bloque.bloqueBase.horario_presencial',
        ]);
    }

    public function confirmarPropuesta(int $propuestaId): PropuestaHorario
    {
        $propuesta = PropuestaHorario::with([
            'detalles.colaborador',
            'detalles.bloque.bloqueBase.area',
            'detalles.bloque.bloqueBase.horario_presencial',
        ])->findOrFail($propuestaId);

        if ($propuesta->estado !== 'borrador') {
            throw new RuntimeException('La propuesta ya no está disponible para confirmación.');
        }
        if (!$propuesta->factible) {
            throw new RuntimeException('No se puede confirmar una propuesta parcial. Primero corrija los bloques pendientes y vuelva a generar.');
        }

        $porBloque = $propuesta->detalles->groupBy('configuracion_bloque_id');
        $agenda = [];

        foreach ($this->sincronizarBloques()->where('estado', true) as $bloque) {
            $detalles = $porBloque->get($bloque->id, collect());
            if ($detalles->count() < $bloque->cupo_min || $detalles->count() > $bloque->cupo_max) {
                throw new RuntimeException("El bloque #{$bloque->id} ya no cumple los límites de cupo. Genere nuevamente la propuesta.");
            }
        }

        foreach ($propuesta->detalles as $detalle) {
            $evaluacion = $this->evaluarCandidato($detalle->colaborador, $detalle->bloque);
            if (!$evaluacion['factible']) {
                throw new RuntimeException('La disponibilidad o los datos cambiaron después de generar la propuesta. Vuelva a generarla antes de confirmar.');
            }
            if ($this->conflictaConAgenda($detalle->colaborador_id, $detalle->bloque, $agenda)) {
                throw new RuntimeException('La propuesta contiene un cruce de horario detectado en la revalidación.');
            }
            $agenda[$detalle->colaborador_id][] = $detalle->bloque;
        }

        DB::transaction(function () use ($propuesta) {
            AsignacionPracticante::where('estado', 'activa')->update([
                'estado' => 'reemplazada',
                'vigencia_hasta' => now()->toDateString(),
            ]);
            PropuestaHorario::where('estado', 'confirmada')->update(['estado' => 'reemplazada']);

            foreach ($propuesta->detalles as $detalle) {
                AsignacionPracticante::create([
                    'colaborador_id' => $detalle->colaborador_id,
                    'configuracion_bloque_id' => $detalle->configuracion_bloque_id,
                    'propuesta_id' => $propuesta->id,
                    'puntaje' => $detalle->puntaje,
                    'explicacion' => $detalle->explicacion,
                    'estado' => 'activa',
                    'vigencia_desde' => now()->startOfWeek()->toDateString(),
                ]);
            }

            $propuesta->update(['estado' => 'confirmada']);
        });

        return $propuesta->fresh();
    }

    public function resolverSolicitud(int $solicitudId, int $userId): SolicitudCambioHorario
    {
        $solicitud = SolicitudCambioHorario::with([
            'asignacion.bloque.bloqueBase.area',
            'asignacion.bloque.bloqueBase.horario_presencial',
            'colaborador',
        ])->findOrFail($solicitudId);

        if ($solicitud->estado !== 'pendiente') {
            throw new RuntimeException('La solicitud ya fue gestionada.');
        }
        if (!$solicitud->asignacion || $solicitud->asignacion->estado !== 'activa') {
            throw new RuntimeException('La asignación original ya no está activa.');
        }

        $original = $solicitud->asignacion;
        $bloqueOriginal = $original->bloque;

        // Si retirar al solicitante deja el bloque original por debajo del mínimo,
        // el motor busca primero un sustituto compatible para conservar la restricción dura.
        $ocupacionOriginal = AsignacionPracticante::where('configuracion_bloque_id', $bloqueOriginal->id)
            ->where('estado', 'activa')->count();
        $sustituto = null;
        if (($ocupacionOriginal - 1) < $bloqueOriginal->cupo_min) {
            $idsYaAsignados = AsignacionPracticante::where('configuracion_bloque_id', $bloqueOriginal->id)
                ->where('estado', 'activa')->pluck('colaborador_id');

            $posiblesSustitutos = Colaboradores::where('estado', 1)
                ->whereNotIn('id', $idsYaAsignados)
                ->get();

            foreach ($posiblesSustitutos as $posible) {
                $evaluacionSustituto = $this->evaluarCandidato($posible, $bloqueOriginal);
                if (!$evaluacionSustituto['factible']) {
                    continue;
                }

                $asignacionesPosible = AsignacionPracticante::with('bloque.bloqueBase.horario_presencial')
                    ->where('colaborador_id', $posible->id)
                    ->where('estado', 'activa')->get();
                $cruza = $asignacionesPosible->contains(fn ($asig) => $asig->bloque && $this->bloquesSeCruzan($asig->bloque, $bloqueOriginal));
                if ($cruza) {
                    continue;
                }

                if (!$sustituto || $evaluacionSustituto['puntaje'] > $sustituto['puntaje']) {
                    $sustituto = [
                        'colaborador_id' => $posible->id,
                        'puntaje' => $evaluacionSustituto['puntaje'],
                        'explicacion' => array_merge($evaluacionSustituto['explicacion'], [
                            'Seleccionado como sustituto para conservar el cupo mínimo del bloque original.'
                        ]),
                    ];
                }
            }

            if (!$sustituto) {
                $solicitud->update([
                    'estado' => 'sin_alternativa',
                    'gestionado_por' => $userId,
                    'respuesta' => 'No se puede retirar al practicante porque el bloque original quedaría por debajo del cupo mínimo y no existe un sustituto compatible.',
                ]);
                return $solicitud->fresh();
            }
        }

        $candidatos = $this->sincronizarBloques()->filter(function ($bloque) use ($solicitud, $bloqueOriginal) {
            if (!$bloque->estado || $bloque->id === $bloqueOriginal->id) {
                return false;
            }

            $areaNueva = $bloque->bloqueBase->area_id;
            $areaOriginal = $bloqueOriginal->bloqueBase->area_id;

            if ($solicitud->tipo_cambio === 'turno') {
                return $areaNueva === $areaOriginal && $bloque->turno !== $bloqueOriginal->turno;
            }
            if ($solicitud->tipo_cambio === 'area') {
                return $areaNueva !== $areaOriginal;
            }
            return true;
        });

        $otrasAsignaciones = AsignacionPracticante::with('bloque.bloqueBase.horario_presencial')
            ->where('colaborador_id', $solicitud->colaborador_id)
            ->where('estado', 'activa')
            ->where('id', '!=', $original->id)
            ->get();

        $mejor = null;
        foreach ($candidatos as $bloque) {
            $ocupacion = AsignacionPracticante::where('configuracion_bloque_id', $bloque->id)
                ->where('estado', 'activa')->count();
            if ($ocupacion >= $bloque->cupo_max) {
                continue;
            }

            $evaluacion = $this->evaluarCandidato($solicitud->colaborador, $bloque);
            if (!$evaluacion['factible']) {
                continue;
            }

            $hayCruce = $otrasAsignaciones->contains(function ($asig) use ($bloque) {
                return $asig->bloque && $this->bloquesSeCruzan($asig->bloque, $bloque);
            });
            if ($hayCruce) {
                continue;
            }

            $puntaje = $evaluacion['puntaje'];
            if ($solicitud->tipo_cambio === 'turno' && $bloque->turno !== $bloqueOriginal->turno) {
                $puntaje += 10;
            }
            if ($solicitud->tipo_cambio === 'area' && $bloque->bloqueBase->area_id !== $bloqueOriginal->bloqueBase->area_id) {
                $puntaje += 10;
            }

            if (!$mejor || $puntaje > $mejor['puntaje']) {
                $mejor = [
                    'bloque' => $bloque,
                    'puntaje' => $puntaje,
                    'explicacion' => array_merge($evaluacion['explicacion'], [
                        'Reasignación seleccionada por mayor compatibilidad disponible.'
                    ]),
                ];
            }
        }

        if (!$mejor) {
            $solicitud->update([
                'estado' => 'sin_alternativa',
                'gestionado_por' => $userId,
                'respuesta' => 'El motor no encontró un bloque alternativo que cumpla disponibilidad, área autorizada, ausencia de cruces y cupo máximo.',
            ]);
            return $solicitud->fresh();
        }

        DB::transaction(function () use ($solicitud, $original, $mejor, $userId, $sustituto, $bloqueOriginal) {
            $original->update([
                'estado' => 'reemplazada',
                'vigencia_hasta' => now()->toDateString(),
            ]);

            if ($sustituto) {
                AsignacionPracticante::create([
                    'colaborador_id' => $sustituto['colaborador_id'],
                    'configuracion_bloque_id' => $bloqueOriginal->id,
                    'propuesta_id' => null,
                    'puntaje' => $sustituto['puntaje'],
                    'explicacion' => $sustituto['explicacion'],
                    'estado' => 'activa',
                    'vigencia_desde' => now()->toDateString(),
                ]);
            }

            $nueva = AsignacionPracticante::create([
                'colaborador_id' => $solicitud->colaborador_id,
                'configuracion_bloque_id' => $mejor['bloque']->id,
                'propuesta_id' => null,
                'puntaje' => $mejor['puntaje'],
                'explicacion' => $mejor['explicacion'],
                'estado' => 'activa',
                'vigencia_desde' => now()->toDateString(),
            ]);

            $solicitud->update([
                'estado' => 'aprobada',
                'nueva_asignacion_id' => $nueva->id,
                'gestionado_por' => $userId,
                'respuesta' => $sustituto
                    ? 'Reasignación automática completada; además se asignó un sustituto compatible para conservar el cupo mínimo del bloque original.'
                    : 'Reasignación automática completada sin cruces y respetando los cupos configurados.',
            ]);
        });

        return $solicitud->fresh(['nuevaAsignacion.bloque.bloqueBase.area', 'nuevaAsignacion.bloque.bloqueBase.horario_presencial']);
    }

    public function evaluarCandidato(Colaboradores $colaborador, ConfiguracionBloqueHorario $bloque): array
    {
        $bloque->loadMissing('bloqueBase.area', 'bloqueBase.horario_presencial');
        $base = $bloque->bloqueBase;
        $horario = $base?->horario_presencial;

        if (!$base || !$horario || !$base->area) {
            return $this->resultadoNoFactible('Bloque incompleto o sin área asociada.');
        }

        $areaAutorizada = Colaboradores_por_Area::where('colaborador_id', $colaborador->id)
            ->where('area_id', $base->area_id)
            ->where('estado', 1)
            ->exists();

        if (!$areaAutorizada) {
            return $this->resultadoNoFactible('El practicante no está autorizado para esta área.');
        }

        $registrosDia = DisponibilidadPracticante::where('colaborador_id', $colaborador->id)
            ->where('dia', $horario->dia)
            ->where('estado', 1)
            ->get();

        if ($registrosDia->isEmpty()) {
            return $this->resultadoNoFactible('No existe disponibilidad declarada para este día.');
        }

        $bloqueInicio = $this->minutos($horario->hora_inicial);
        $bloqueFin = $this->minutos($horario->hora_final);

        $bloqueado = $registrosDia->where('tipo', 'no_disponible')->contains(function ($registro) use ($bloqueInicio, $bloqueFin) {
            return $this->intervalosSeCruzan(
                $bloqueInicio,
                $bloqueFin,
                $this->minutos($registro->hora_inicial),
                $this->minutos($registro->hora_final)
            );
        });
        if ($bloqueado) {
            return $this->resultadoNoFactible('La franja fue declarada como no disponible por estudio, trabajo u otro compromiso.');
        }

        $disponibles = $registrosDia->where('tipo', 'disponible');
        $cubreBloque = $disponibles->contains(function ($registro) use ($bloqueInicio, $bloqueFin, $bloque) {
            $modalidadCompatible = strtolower($registro->modalidad) === strtolower($bloque->modalidad)
                || strtolower($registro->modalidad) === 'mixta';
            return $modalidadCompatible
                && $this->minutos($registro->hora_inicial) <= $bloqueInicio
                && $this->minutos($registro->hora_final) >= $bloqueFin;
        });
        if (!$cubreBloque) {
            return $this->resultadoNoFactible('La disponibilidad declarada no cubre completamente este bloque o la modalidad no coincide.');
        }

        $cruceClase = Horario_de_Clases::where('colaborador_id', $colaborador->id)
            ->where('dia', $horario->dia)
            ->get()
            ->contains(function ($clase) use ($bloqueInicio, $bloqueFin) {
                return $this->intervalosSeCruzan(
                    $bloqueInicio,
                    $bloqueFin,
                    $this->minutos($clase->hora_inicial),
                    $this->minutos($clase->hora_final)
                );
            });
        if ($cruceClase) {
            return $this->resultadoNoFactible('Existe cruce con el horario de clases registrado.');
        }

        $cargaHistorica = AsignacionPracticante::where('colaborador_id', $colaborador->id)
            ->where('estado', 'activa')->count();

        $puntaje = 70; // disponibilidad completa
        $explicacion = [
            'Disponibilidad completa: +70',
            'Área autorizada: +10',
            'Modalidad compatible: +10',
        ];
        $puntaje += 10;
        $puntaje += 10;

        $bonoBalance = max(0, 10 - ($cargaHistorica * 2));
        $puntaje += $bonoBalance;
        $explicacion[] = "Balance de carga: +{$bonoBalance}";

        return [
            'factible' => true,
            'puntaje' => $puntaje,
            'explicacion' => $explicacion,
        ];
    }

    private function backtracking(array $bloques, array $dominios, int $indice, array &$solucion, array &$agenda): bool
    {
        if ($indice >= count($bloques)) {
            return true;
        }
        if (++$this->nodosVisitados > $this->limiteNodos) {
            return false;
        }

        /** @var ConfiguracionBloqueHorario $bloque */
        $bloque = $bloques[$indice];
        $candidatos = [];

        foreach ($dominios[$bloque->id] as $candidato) {
            if (!$this->conflictaConAgenda($candidato['colaborador_id'], $bloque, $agenda)) {
                $cargaTemporal = count($agenda[$candidato['colaborador_id']] ?? []);
                $candidato['puntaje_final'] = max(0, $candidato['puntaje'] - ($cargaTemporal * 8));
                $candidatos[] = $candidato;
            }
        }

        usort($candidatos, fn ($a, $b) => $b['puntaje_final'] <=> $a['puntaje_final']);
        if (count($candidatos) < $bloque->cupo_min) {
            return false;
        }

        // Se limita el dominio superior para que el prototipo responda rápido aun con muchos practicantes.
        $candidatos = array_slice($candidatos, 0, 10);
        $combinaciones = $this->combinaciones($candidatos, $bloque->cupo_min, 30);
        usort($combinaciones, function ($a, $b) {
            $sa = array_sum(array_column($a, 'puntaje_final'));
            $sb = array_sum(array_column($b, 'puntaje_final'));
            return $sb <=> $sa;
        });

        foreach ($combinaciones as $combo) {
            $solucion[$bloque->id] = $combo;
            foreach ($combo as $item) {
                $agenda[$item['colaborador_id']][] = $bloque;
            }

            if ($this->backtracking($bloques, $dominios, $indice + 1, $solucion, $agenda)) {
                return true;
            }

            foreach ($combo as $item) {
                array_pop($agenda[$item['colaborador_id']]);
                if (empty($agenda[$item['colaborador_id']])) {
                    unset($agenda[$item['colaborador_id']]);
                }
            }
            unset($solucion[$bloque->id]);
        }

        return false;
    }

    private function construirSolucionParcial(array $bloques, array $dominios): array
    {
        $solucion = [];
        $pendientes = [];
        $agenda = [];

        foreach ($bloques as $bloque) {
            $seleccionados = [];
            $candidatos = $dominios[$bloque->id] ?? [];
            foreach ($candidatos as $candidato) {
                if (count($seleccionados) >= $bloque->cupo_min) {
                    break;
                }
                if ($this->conflictaConAgenda($candidato['colaborador_id'], $bloque, $agenda)) {
                    continue;
                }

                $cargaTemporal = count($agenda[$candidato['colaborador_id']] ?? []);
                $candidato['puntaje_final'] = max(0, $candidato['puntaje'] - ($cargaTemporal * 8));
                $seleccionados[] = $candidato;
                $agenda[$candidato['colaborador_id']][] = $bloque;
            }
            $solucion[$bloque->id] = $seleccionados;

            if (count($seleccionados) < $bloque->cupo_min) {
                $base = $bloque->bloqueBase;
                $h = $base->horario_presencial;
                $pendientes[] = [
                    'bloque_id' => $bloque->id,
                    'area' => $base->area->especializacion,
                    'dia' => $h->dia,
                    'horario' => substr($h->hora_inicial, 0, 5) . ' - ' . substr($h->hora_final, 0, 5),
                    'turno' => $bloque->turno,
                    'requeridos' => $bloque->cupo_min,
                    'asignados' => count($seleccionados),
                    'faltantes' => $bloque->cupo_min - count($seleccionados),
                    'motivo' => 'No hay suficientes practicantes compatibles sin cruces para cumplir el cupo mínimo.',
                ];
            }
        }

        return [$solucion, $pendientes];
    }

    private function combinaciones(array $items, int $k, int $limite): array
    {
        $resultado = [];
        $this->combinarRecursivo($items, $k, 0, [], $resultado, $limite);
        return $resultado;
    }

    private function combinarRecursivo(array $items, int $k, int $inicio, array $actual, array &$resultado, int $limite): void
    {
        if (count($resultado) >= $limite) {
            return;
        }
        if (count($actual) === $k) {
            $resultado[] = $actual;
            return;
        }

        for ($i = $inicio; $i < count($items); $i++) {
            $siguiente = $actual;
            $siguiente[] = $items[$i];
            $this->combinarRecursivo($items, $k, $i + 1, $siguiente, $resultado, $limite);
            if (count($resultado) >= $limite) {
                return;
            }
        }
    }

    private function conflictaConAgenda(int $colaboradorId, ConfiguracionBloqueHorario $bloque, array $agenda): bool
    {
        foreach ($agenda[$colaboradorId] ?? [] as $otroBloque) {
            if ($this->bloquesSeCruzan($otroBloque, $bloque)) {
                return true;
            }
        }
        return false;
    }

    private function bloquesSeCruzan(ConfiguracionBloqueHorario $a, ConfiguracionBloqueHorario $b): bool
    {
        $a->loadMissing('bloqueBase.horario_presencial');
        $b->loadMissing('bloqueBase.horario_presencial');
        $ha = $a->bloqueBase?->horario_presencial;
        $hb = $b->bloqueBase?->horario_presencial;
        if (!$ha || !$hb || $ha->dia !== $hb->dia) {
            return false;
        }

        return $this->intervalosSeCruzan(
            $this->minutos($ha->hora_inicial),
            $this->minutos($ha->hora_final),
            $this->minutos($hb->hora_inicial),
            $this->minutos($hb->hora_final)
        );
    }

    private function intervalosSeCruzan(int $inicioA, int $finA, int $inicioB, int $finB): bool
    {
        return $inicioA < $finB && $inicioB < $finA;
    }

    private function minutos(string $hora): int
    {
        $partes = explode(':', $hora);
        return ((int) ($partes[0] ?? 0) * 60) + (int) ($partes[1] ?? 0);
    }

    private function inferirTurno(string $hora): string
    {
        return $this->minutos($hora) < (13 * 60) ? 'Mañana' : 'Tarde';
    }

    private function resultadoNoFactible(string $motivo): array
    {
        return ['factible' => false, 'puntaje' => 0, 'explicacion' => [$motivo]];
    }
}
