<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDA | Asignación automática</title>
</head>
<body>
<div id="wrapper">
    @include('components.inspinia.side_nav_bar-inspinia')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Asignación automatizada de horarios</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                <li class="breadcrumb-item active"><strong>Asignación automática</strong></li>
            </ol>
        </div>
        <div class="col-lg-4 text-right" style="padding-top: 28px;">
            @if($userData['isAdmin'])
                <form method="POST" action="{{ route('asignacionHorarios.generar') }}" style="display:inline-block">
                    @csrf
                    <button class="btn btn-primary btn-lg" type="submit">
                        <i class="fa fa-magic"></i> Generar propuesta inteligente
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        @if(session('success'))
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> {{ session('warning') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="fa fa-times-circle"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Revise los datos ingresados:</strong>
                <ul class="m-b-none">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="alert alert-info">
            <strong><i class="fa fa-lightbulb-o"></i> Motor del prototipo:</strong>
            primero descarta incompatibilidades (disponibilidad, clases, área, cruces y cupos), luego aplica un puntaje explicable y finalmente ejecuta una búsqueda CSP con backtracking. No requiere una API externa ni credenciales adicionales.
            @if($canManage)
                <span class="m-l-sm">El seguimiento de trabajos y entregables continúa usando el módulo ya existente de <a href="{{ route('responsabilidades.index') }}"><strong>Responsabilidades</strong></a>.</span>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="ibox"><div class="ibox-content">
                    <h5>Practicantes con disponibilidad</h5>
                    <h1 class="no-margins">{{ $estadisticas['practicantes_con_disponibilidad'] }}</h1>
                    <small>RF-01: disponibilidad registrada</small>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox"><div class="ibox-content">
                    <h5>Bloques activos</h5>
                    <h1 class="no-margins">{{ $estadisticas['bloques_activos'] }}</h1>
                    <small>Área, turno y límites de cupo</small>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox"><div class="ibox-content">
                    <h5>Asignaciones vigentes</h5>
                    <h1 class="no-margins">{{ $estadisticas['asignaciones_activas'] }}</h1>
                    <small>Confirmadas y sin reemplazar</small>
                </div></div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="ibox"><div class="ibox-content">
                    <h5>Cambios pendientes</h5>
                    <h1 class="no-margins">{{ $estadisticas['cambios_pendientes'] }}</h1>
                    <small>Solicitudes por resolver</small>
                </div></div>
            </div>
        </div>

        <div class="tabs-container">
            <ul class="nav nav-tabs" role="tablist">
                <li><a class="nav-link active" data-toggle="tab" href="#tab-disponibilidad"><i class="fa fa-calendar-check-o"></i> Disponibilidad</a></li>
                @if($canManage)
                    <li><a class="nav-link" data-toggle="tab" href="#tab-bloques"><i class="fa fa-sliders"></i> Bloques y cupos</a></li>
                @endif
                @if($canManage)
                    <li><a class="nav-link" data-toggle="tab" href="#tab-propuesta"><i class="fa fa-magic"></i> Propuesta automática</a></li>
                @endif
                <li><a class="nav-link" data-toggle="tab" href="#tab-horarios"><i class="fa fa-clock-o"></i> Horarios asignados</a></li>
                <li><a class="nav-link" data-toggle="tab" href="#tab-cambios"><i class="fa fa-exchange"></i> Cambios</a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" id="tab-disponibilidad" class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="ibox">
                                    <div class="ibox-title"><h5>Registrar / actualizar franja</h5></div>
                                    <div class="ibox-content">
                                        <form id="formDisponibilidad" method="POST" action="{{ route('asignacionHorarios.disponibilidad.guardar') }}">
                                            @csrf
                                            <input type="hidden" name="disponibilidad_id" id="disponibilidad_id">
                                            @if($canManage)
                                                <div class="form-group">
                                                    <label>Practicante</label>
                                                    <select class="form-control" name="colaborador_id" id="disp_colaborador_id" required>
                                                        <option value="">Seleccione...</option>
                                                        @foreach($colaboradores as $colaborador)
                                                            <option value="{{ $colaborador->id }}">
                                                                {{ $colaborador->candidato->nombre ?? 'Sin nombre' }} {{ $colaborador->candidato->apellido ?? '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <div class="alert alert-light">
                                                    <strong>Practicante:</strong> {{ $colaboradorActual->candidato->nombre ?? '' }} {{ $colaboradorActual->candidato->apellido ?? '' }}
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label>Día</label>
                                                    <select class="form-control" name="dia" id="disp_dia" required>
                                                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                                                            <option value="{{ $dia }}">{{ $dia }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label>Tipo</label>
                                                    <select class="form-control" name="tipo" id="disp_tipo" required>
                                                        <option value="disponible">Disponible</option>
                                                        <option value="no_disponible">No disponible</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label>Desde</label>
                                                    <input class="form-control" type="time" name="hora_inicial" id="disp_inicio" required>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label>Hasta</label>
                                                    <input class="form-control" type="time" name="hora_final" id="disp_fin" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 form-group">
                                                    <label>Origen</label>
                                                    <select class="form-control" name="origen" id="disp_origen" required>
                                                        <option value="practicas">Prácticas</option>
                                                        <option value="estudio">Estudio</option>
                                                        <option value="trabajo">Trabajo</option>
                                                        <option value="otro">Otro</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-6 form-group">
                                                    <label>Modalidad</label>
                                                    <select class="form-control" name="modalidad" id="disp_modalidad" required>
                                                        <option value="Presencial">Presencial</option>
                                                        <option value="Virtual">Virtual</option>
                                                        <option value="Mixta">Mixta</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Observación</label>
                                                <input class="form-control" type="text" maxlength="255" name="observacion" id="disp_observacion" placeholder="Ej.: libre después de clases">
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="btn btn-white" onclick="limpiarDisponibilidad()">Limpiar</button>
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                <div class="ibox">
                                    <div class="ibox-title"><h5>Disponibilidad activa</h5></div>
                                    <div class="ibox-content table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                @if($canManage)<th>Practicante</th>@endif
                                                <th>Día</th><th>Franja</th><th>Tipo / origen</th><th>Modalidad</th><th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($disponibilidades as $disp)
                                                <tr>
                                                    @if($canManage)
                                                        <td>{{ $disp->colaborador->candidato->nombre ?? '' }} {{ $disp->colaborador->candidato->apellido ?? '' }}</td>
                                                    @endif
                                                    <td>{{ $disp->dia }}</td>
                                                    <td>{{ substr($disp->hora_inicial,0,5) }} - {{ substr($disp->hora_final,0,5) }}</td>
                                                    <td>
                                                        <span class="label label-{{ $disp->tipo === 'disponible' ? 'primary' : 'warning' }}">{{ str_replace('_',' ',ucfirst($disp->tipo)) }}</span>
                                                        <small class="text-muted">{{ ucfirst($disp->origen) }}</small>
                                                    </td>
                                                    <td>{{ $disp->modalidad }}</td>
                                                    <td style="white-space:nowrap">
                                                        <button type="button" class="btn btn-xs btn-info"
                                                            data-json='{{ json_encode([
                                                                "id"=>$disp->id,"colaborador_id"=>$disp->colaborador_id,"dia"=>$disp->dia,
                                                                "inicio"=>substr($disp->hora_inicial,0,5),"fin"=>substr($disp->hora_final,0,5),
                                                                "tipo"=>$disp->tipo,"origen"=>$disp->origen,"modalidad"=>$disp->modalidad,
                                                                "observacion"=>$disp->observacion
                                                            ]) }}'
                                                            onclick="editarDisponibilidad(JSON.parse(this.dataset.json))"><i class="fa fa-pencil"></i></button>
                                                        <form method="POST" action="{{ route('asignacionHorarios.disponibilidad.eliminar', $disp->id) }}" style="display:inline-block" onsubmit="return confirm('¿Quitar esta franja de la disponibilidad activa?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="{{ $canManage ? 6 : 5 }}" class="text-center text-muted">No hay disponibilidad registrada para el filtro actual.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($canManage)
                <div role="tabpanel" id="tab-bloques" class="tab-pane">
                    <div class="panel-body">
                        <div class="alert alert-warning">
                            <strong>Importante:</strong> estos registros parten de los horarios presenciales que ya estaban configurados por área. Aquí solo se agregan los parámetros que necesita el algoritmo: turno, modalidad y cupos.
                        </div>
                        <div class="ibox">
                            <div class="ibox-title"><h5>Configuración de bloques</h5></div>
                            <div class="ibox-content table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead><tr><th>Área</th><th>Día</th><th>Horario</th><th>Turno</th><th>Cupo mín.</th><th>Cupo máx.</th><th>Modalidad</th><th>Activo</th><th></th></tr></thead>
                                    <tbody>
                                    @forelse($bloques as $bloque)
                                        @php($base = $bloque->bloqueBase)
                                        @php($horario = $base?->horario_presencial)
                                        <tr>
                                                <td>{{ $base?->area?->especializacion ?? 'Sin área' }}</td>
                                                <td>{{ $horario?->dia ?? '-' }}</td>
                                                <td>{{ $horario ? substr($horario->hora_inicial,0,5).' - '.substr($horario->hora_final,0,5) : '-' }}</td>
                                                <td>
                                                    <select class="form-control input-sm" name="turno" form="bloque-form-{{ $bloque->id }}">
                                                        @foreach(['Mañana','Tarde','Personalizado'] as $turno)
                                                            <option value="{{ $turno }}" @selected($bloque->turno === $turno)>{{ $turno }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input class="form-control input-sm" type="number" min="1" max="20" name="cupo_min" value="{{ $bloque->cupo_min }}" form="bloque-form-{{ $bloque->id }}"></td>
                                                <td><input class="form-control input-sm" type="number" min="1" max="20" name="cupo_max" value="{{ $bloque->cupo_max }}" form="bloque-form-{{ $bloque->id }}"></td>
                                                <td>
                                                    <select class="form-control input-sm" name="modalidad" form="bloque-form-{{ $bloque->id }}">
                                                        @foreach(['Presencial','Virtual','Mixta'] as $modalidad)
                                                            <option value="{{ $modalidad }}" @selected($bloque->modalidad === $modalidad)>{{ $modalidad }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <input type="hidden" name="estado" value="0" form="bloque-form-{{ $bloque->id }}">
                                                    <input type="checkbox" name="estado" value="1" @checked($bloque->estado) form="bloque-form-{{ $bloque->id }}">
                                                </td>
                                                <td>
                                                    <form id="bloque-form-{{ $bloque->id }}" method="POST" action="{{ route('asignacionHorarios.bloques.actualizar', $bloque->id) }}">
                                                        @csrf @method('PUT')
                                                        <button class="btn btn-xs btn-primary"><i class="fa fa-save"></i></button>
                                                    </form>
                                                </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="9" class="text-center text-muted">No hay horarios presenciales asociados a las áreas activas.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($canManage)
                <div role="tabpanel" id="tab-propuesta" class="tab-pane">
                    <div class="panel-body">
                        @if(!$ultimaPropuesta)
                            <div class="text-center p-lg">
                                <i class="fa fa-magic fa-4x text-muted"></i>
                                <h3>Aún no existe una propuesta</h3>
                                <p>Registre disponibilidad, revise cupos y use “Generar propuesta inteligente”.</p>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="ibox">
                                        <div class="ibox-title">
                                            <h5>Propuesta #{{ $ultimaPropuesta->id }}</h5>
                                            <span class="label label-{{ $ultimaPropuesta->factible ? 'primary' : 'warning' }} pull-right">
                                                {{ $ultimaPropuesta->factible ? 'FACTIBLE' : 'PARCIAL' }}
                                            </span>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="row">
                                                <div class="col-sm-4"><strong>Estado</strong><br>{{ ucfirst($ultimaPropuesta->estado) }}</div>
                                                <div class="col-sm-4"><strong>Puntaje total</strong><br>{{ number_format($ultimaPropuesta->puntaje_total, 0) }}</div>
                                                <div class="col-sm-4"><strong>Asignaciones</strong><br>{{ $ultimaPropuesta->detalles->count() }}</div>
                                            </div>
                                            <hr>
                                            @if($ultimaPropuesta->resumen)
                                                <p><strong>Algoritmo:</strong> {{ $ultimaPropuesta->resumen['algoritmo'] ?? 'Scoring + CSP' }}</p>
                                                <p><strong>Tiempo de generación:</strong> {{ number_format($ultimaPropuesta->resumen['tiempo_generacion_ms'] ?? 0, 2) }} ms</p>
                                                <p><strong>Nodos evaluados por backtracking:</strong> {{ $ultimaPropuesta->resumen['nodos_backtracking'] ?? 0 }}</p>
                                                <p><strong>Restricciones duras:</strong> {{ implode(', ', $ultimaPropuesta->resumen['restricciones_duras'] ?? []) }}</p>
                                                <p><strong>Criterio blando:</strong> {{ $ultimaPropuesta->resumen['criterio_blando'] ?? '' }}</p>
                                            @endif
                                            @if($userData['isAdmin'] && $ultimaPropuesta->estado === 'borrador' && $ultimaPropuesta->factible)
                                                <form method="POST" action="{{ route('asignacionHorarios.confirmar', $ultimaPropuesta->id) }}" onsubmit="return confirm('Se revalidarán las restricciones y los horarios vigentes del módulo serán reemplazados por esta propuesta. ¿Continuar?')">
                                                    @csrf
                                                    <button class="btn btn-success"><i class="fa fa-check"></i> Confirmar y guardar horarios</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="ibox">
                                        <div class="ibox-title"><h5>Bloques pendientes</h5></div>
                                        <div class="ibox-content">
                                            @forelse(($ultimaPropuesta->pendientes ?? []) as $pendiente)
                                                <div class="alert alert-warning">
                                                    <strong>{{ $pendiente['area'] ?? 'Área' }} · {{ $pendiente['dia'] ?? '' }}</strong><br>
                                                    {{ $pendiente['horario'] ?? '' }} · {{ $pendiente['turno'] ?? '' }}<br>
                                                    Faltan: <strong>{{ $pendiente['faltantes'] ?? 0 }}</strong><br>
                                                    <small>{{ $pendiente['motivo'] ?? '' }}</small>
                                                </div>
                                            @empty
                                                <p class="text-success"><i class="fa fa-check-circle"></i> No hay bloques pendientes.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ibox">
                                <div class="ibox-title"><h5>Detalle explicable de la propuesta</h5></div>
                                <div class="ibox-content table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead><tr><th>Practicante</th><th>Área</th><th>Día / horario</th><th>Turno</th><th>Puntaje</th><th>¿Por qué fue elegido?</th></tr></thead>
                                        <tbody>
                                        @forelse($ultimaPropuesta->detalles as $detalle)
                                            @php($h = $detalle->bloque?->bloqueBase?->horario_presencial)
                                            <tr>
                                                <td>{{ $detalle->colaborador->candidato->nombre ?? '' }} {{ $detalle->colaborador->candidato->apellido ?? '' }}</td>
                                                <td>{{ $detalle->bloque?->bloqueBase?->area?->especializacion ?? '-' }}</td>
                                                <td>{{ $h?->dia ?? '-' }} · {{ $h ? substr($h->hora_inicial,0,5).' - '.substr($h->hora_final,0,5) : '-' }}</td>
                                                <td>{{ $detalle->bloque?->turno ?? '-' }}</td>
                                                <td><span class="badge badge-primary">{{ number_format($detalle->puntaje, 0) }}</span></td>
                                                <td><small>{{ implode(' | ', $detalle->explicacion ?? []) }}</small></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center text-muted">La propuesta no contiene asignaciones factibles.</td></tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <div role="tabpanel" id="tab-horarios" class="tab-pane">
                    <div class="panel-body">
                        @if($canManage)
                            <form method="GET" action="{{ route('asignacionHorarios.index') }}" class="row m-b-md">
                                <div class="col-md-3">
                                    <label>Practicante</label>
                                    <select class="form-control" name="colaborador_id">
                                        <option value="">Todos</option>
                                        @foreach($colaboradores as $colaborador)
                                            <option value="{{ $colaborador->id }}" @selected((string)request('colaborador_id') === (string)$colaborador->id)>
                                                {{ $colaborador->candidato->nombre ?? '' }} {{ $colaborador->candidato->apellido ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Área</label>
                                    <select class="form-control" name="area_id">
                                        <option value="">Todas</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" @selected((string)request('area_id') === (string)$area->id)>{{ $area->especializacion }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Turno</label>
                                    <select class="form-control" name="turno">
                                        <option value="">Todos</option>
                                        @foreach(['Mañana','Tarde','Personalizado'] as $turno)
                                            <option value="{{ $turno }}" @selected(request('turno') === $turno)>{{ $turno }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3" style="padding-top: 27px;">
                                    <button class="btn btn-primary"><i class="fa fa-filter"></i> Filtrar</button>
                                    <a class="btn btn-white" href="{{ route('asignacionHorarios.index') }}">Limpiar</a>
                                </div>
                            </form>
                        @endif

                        <div class="ibox">
                            <div class="ibox-title"><h5>Semana vigente / patrón semanal confirmado</h5></div>
                            <div class="ibox-content table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead><tr>@if($canManage)<th>Practicante</th>@endif<th>Día</th><th>Horario</th><th>Área</th><th>Turno</th><th>Modalidad</th><th>Puntaje</th><th>Cambio</th></tr></thead>
                                    <tbody>
                                    @forelse($asignaciones as $asignacion)
                                        @php($h = $asignacion->bloque?->bloqueBase?->horario_presencial)
                                        <tr>
                                            @if($canManage)
                                                <td>{{ $asignacion->colaborador->candidato->nombre ?? '' }} {{ $asignacion->colaborador->candidato->apellido ?? '' }}</td>
                                            @endif
                                            <td>{{ $h?->dia ?? '-' }}</td>
                                            <td>{{ $h ? substr($h->hora_inicial,0,5).' - '.substr($h->hora_final,0,5) : '-' }}</td>
                                            <td>{{ $asignacion->bloque?->bloqueBase?->area?->especializacion ?? '-' }}</td>
                                            <td>{{ $asignacion->bloque?->turno ?? '-' }}</td>
                                            <td>{{ $asignacion->bloque?->modalidad ?? '-' }}</td>
                                            <td>{{ number_format($asignacion->puntaje, 0) }}</td>
                                            <td><button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#cambio-{{ $asignacion->id }}"><i class="fa fa-exchange"></i></button></td>
                                        </tr>

                                    @empty
                                        <tr><td colspan="{{ $canManage ? 8 : 7 }}" class="text-center text-muted">No hay horarios confirmados para mostrar.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>

                                @foreach($asignaciones as $asignacionModal)
                                    @php($hModal = $asignacionModal->bloque?->bloqueBase?->horario_presencial)
                                    <div class="modal fade" id="cambio-{{ $asignacionModal->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document"><div class="modal-content">
                                            <div class="modal-header"><h4 class="modal-title">Solicitar cambio de asignación</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                            <form method="POST" action="{{ route('asignacionHorarios.cambio.solicitar', $asignacionModal->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <p><strong>Actual:</strong> {{ $asignacionModal->bloque?->bloqueBase?->area?->especializacion ?? '-' }} · {{ $hModal?->dia ?? '-' }} {{ $hModal ? substr($hModal->hora_inicial,0,5).' - '.substr($hModal->hora_final,0,5) : '' }} · {{ $asignacionModal->bloque?->turno }}</p>
                                                    <div class="form-group">
                                                        <label>Tipo de cambio</label>
                                                        <select class="form-control" name="tipo_cambio" required>
                                                            <option value="turno">Cambiar turno</option>
                                                            <option value="area">Cambiar área</option>
                                                            <option value="general">Buscar cualquier alternativa compatible</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Motivo</label>
                                                        <textarea class="form-control" name="motivo" rows="3" maxlength="500" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button><button class="btn btn-warning">Enviar solicitud</button></div>
                                            </form>
                                        </div></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" id="tab-cambios" class="tab-pane">
                    <div class="panel-body">
                        <div class="ibox">
                            <div class="ibox-title"><h5>Trazabilidad de solicitudes de cambio</h5></div>
                            <div class="ibox-content table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead><tr>@if($canManage)<th>Practicante</th>@endif<th>Tipo</th><th>Motivo</th><th>Estado</th><th>Respuesta</th>@if($userData['isAdmin'])<th>Acciones</th>@endif</tr></thead>
                                    <tbody>
                                    @forelse($solicitudes as $solicitud)
                                        <tr>
                                            @if($canManage)
                                                <td>{{ $solicitud->colaborador->candidato->nombre ?? '' }} {{ $solicitud->colaborador->candidato->apellido ?? '' }}</td>
                                            @endif
                                            <td>{{ ucfirst($solicitud->tipo_cambio) }}</td>
                                            <td>{{ $solicitud->motivo }}</td>
                                            <td>
                                                @php($claseEstado = $solicitud->estado === 'aprobada' ? 'primary' : ($solicitud->estado === 'pendiente' ? 'warning' : 'default'))
                                                <span class="label label-{{ $claseEstado }}">{{ str_replace('_',' ',ucfirst($solicitud->estado)) }}</span>
                                            </td>
                                            <td>{{ $solicitud->respuesta ?? '-' }}</td>
                                            @if($userData['isAdmin'])
                                            <td style="white-space:nowrap">
                                                @if($solicitud->estado === 'pendiente')
                                                    <form method="POST" action="{{ route('asignacionHorarios.cambio.resolver', $solicitud->id) }}" style="display:inline-block">
                                                        @csrf
                                                        <button class="btn btn-xs btn-primary" title="Buscar alternativa compatible"><i class="fa fa-magic"></i> Resolver</button>
                                                    </form>
                                                    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#rechazar-{{ $solicitud->id }}">Rechazar</button>
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr><td colspan="{{ 4 + ($canManage ? 1 : 0) + ($userData['isAdmin'] ? 1 : 0) }}" class="text-center text-muted">No hay solicitudes registradas.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                @if($userData['isAdmin'])
                                    @foreach($solicitudes as $solicitudModal)
                                        @if($solicitudModal->estado === 'pendiente')
                                            <div class="modal fade" id="rechazar-{{ $solicitudModal->id }}" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document"><div class="modal-content">
                                                    <div class="modal-header"><h4 class="modal-title">Rechazar solicitud</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
                                                    <form method="POST" action="{{ route('asignacionHorarios.cambio.rechazar', $solicitudModal->id) }}">
                                                        @csrf
                                                        <div class="modal-body"><label>Respuesta / justificación</label><textarea class="form-control" name="respuesta" rows="3" maxlength="500" required></textarea></div>
                                                        <div class="modal-footer"><button type="button" class="btn btn-white" data-dismiss="modal">Cancelar</button><button class="btn btn-danger">Rechazar</button></div>
                                                    </form>
                                                </div></div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.inspinia.footer-inspinia')
</div>

<script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('js/inspinia.js') }}"></script>

<script>
function editarDisponibilidad(data) {
    document.getElementById('disponibilidad_id').value = data.id || '';
    if (document.getElementById('disp_colaborador_id')) document.getElementById('disp_colaborador_id').value = data.colaborador_id || '';
    document.getElementById('disp_dia').value = data.dia;
    document.getElementById('disp_inicio').value = data.inicio;
    document.getElementById('disp_fin').value = data.fin;
    document.getElementById('disp_tipo').value = data.tipo;
    document.getElementById('disp_origen').value = data.origen;
    document.getElementById('disp_modalidad').value = data.modalidad;
    document.getElementById('disp_observacion').value = data.observacion || '';
    window.scrollTo({ top: 260, behavior: 'smooth' });
}
function limpiarDisponibilidad() {
    document.getElementById('formDisponibilidad').reset();
    document.getElementById('disponibilidad_id').value = '';
}
</script>
</body>
</html>
