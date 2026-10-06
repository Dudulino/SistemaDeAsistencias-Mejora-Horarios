<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/areas/index.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <title>INSPINIA| Áreas</title>
</head>

<body>

    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-4">
                <h2>Áreas</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Áreas</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-6 d-flex align-items-center gap-10">
                <h2>Cant. Áreas: <span class="font-bold text-danger">{{$countAreas}}</span></h2>
                <h2>Cant. Colaboradores: <span class="font-bold text-danger">{{$countColabs}}</span></h2>
            </div>
            <div class="col-lg-2 d-flex align-items-center justify-content-end">
                <button class="btn btn-success dim float-right" href="#modal-form-add" data-toggle="modal"
                    type="button">Agregar</button>
                <div id="modal-form-add" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form role="form" method="POST" action="{{ route('areas.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                    <input type="hidden" name="form_type" value="create">
                                    <div class="row">
                                        <div class="col-sm-6 b-r">
                                            <h3 class="m-t-none m-b">Ingrese los Datos</h3>
                                            <div class="form-group"><label>Especialización</label> <input type="text"
                                                    placeholder="....." class="form-control" name="especializacion"
                                                    >
                                            </div>
                                            @error('especializacion')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group"><label>Descripción</label> <input type="text"
                                                    placeholder="....." class="form-control" name="descripcion"
                                                    >
                                            </div>
                                            @error('descripcion')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group"><label>Color Hex</label>
                                                <input type="color" placeholder="....." class="form-control"
                                                    name="color_hex" >
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <h4>Subir Ícono</h4>
                                            <input type="file" class="form-control-file" id="icono" name="icono"
                                                style="display: none;">
                                            <!-- Icono que simula el clic en el botón de subir archivos -->
                                            <button type="button" class="btn btn-link" id="icon-upload">
                                                <i class="fa fa-cloud-download big-icon"></i>
                                            </button>
                                            <div class="form-group"><label>Salón</label>
                                                <select class="form-control" name="salon_id" required>
                                                    @foreach($salones as $key => $salon)
                                                    <option value="{{ $salon->id }}">{{ $salon->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <button class="btn btn-primary btn-sm m-t-n-xs float-right" type="submit"><i
                                                    class="fa fa-check"></i>&nbsp;Confirmar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($warning))
            <div class="alert alert-warning">
                <strong>Advertencia:</strong> {{ $warning }}
            </div>
        @endif


        <!-- From Uiverse.io by themrsami -->
        <form method="GET" action="{{ route('areas.buscar') }}" class="d-flex form-content">
            <div class="search-container">

                    <input id="input-buscar" class="search-input" type="text" name="buscar_area"
                        placeholder="Buscar Área..." autocomplete="off" value="{{ request('buscar_area') }}">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>

                {{-- <form>
                    <button id="btn-limpiar" class="btn btn-outline-success my-2 my-sm-0" type="button">Limpiar</button>
                </form> --}}

            </div>
        </form>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                @foreach ($areas as $index => $area)
                <div  class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                    {{-- <button class="ibox" type="button" data-toggle="modal" href="#modal-form{{ $area->id }}"> --}}
                        <div class="ibox">
                            <div class="ibox-content product-box">
                                <div class="product-imitation" style="object-fit: cover; padding: 0px; height: 225px;" onclick="onClickArea('{{ $area->id }}')">
                                    <img src="{{ asset('storage/areas/' . $area->icono) }}" alt="" style="height: 100%; width: 100%; object-fit: cover"  class="img-cover">
                                </div>
                                <div class="product-desc">
                                    {{-- CAMBIO DE ESTADO ÁREAS --}}
                                    <form action="{{ route('areas.activarInactivar', $area->id) }}" method="POST">
                                        @csrf
                                        @method('put')
                                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">

                                    </form>

                                    <div>
                                        {{-- btn cambiar estado JS --}}
                                        <button type="button" class="btn btn-{{ $area->estado ? 'outline-success' : 'danger' }} btn-primary dim" onclick="confirmState({{ $area->id }})">
                                            <span>{{ $area->estado ? 'ON' : 'OFF' }}</span>
                                        </button>
                                    </div>
                                    <small class="text-muted">ID: {{ $area->id }} Salón: {{$area->salon->nombre}} Cant. Integrantes: {{$area->count_colabs}}</small>
                                    <a href="#"  class="product-name">{{ $area->especializacion }}</a>
                                    <div class="small m-t-xs">
                                        {{ $area->descripcion }}
                                    </div>
                                    <div style="display: flex; gap: 4px" class="m-t text-left">
                                        {{-- <button class="btn btn-danger" type="button"
                                            onclick="confirmDelete({{ $area->id }})"><i class="fa fa-trash-o"></i></button>
                                        --}}
                                        {{-- <button class="btn btn-info" type="button" href="#modal-form{{ $area->id }}"
                                            data-toggle="modal"><i class="fa fa-paste"></i> Edit</button> --}}

                                            {{-- botones --}}
                                        <form role="form" method="GET" action="{{ route('areas.getHorario', $area->id) }}">
                                            <x-uiverse.tooltip nameTool="Horarios">
                                                <button class="btn btn-primary fa fa-clock-o" style="font-size: 20px;"></button>
                                            </x-uiverse.tooltip>
                                        </form>
                                        <form role="form" method="GET"
                                            action="{{ route('areas.getReuniones', $area->id) }}">

                                            <x-uiverse.tooltip nameTool="Reuniones">
                                                <button class="btn btn-success fa fa-video-camera"
                                                style="font-size: 20px;"></button>
                                            </x-uiverse.tooltip>

                                        </form>
                                        <form role="form" method="GET" action="{{route('areas.getMaquinas', $area->id)}}">

                                            <x-uiverse.tooltip nameTool="Máquinas">
                                                <button class="btn btn-secondary fa fa-desktop" style="font-size: 20px;">
                                                </button>
                                            </x-uiverse.tooltip>

                                        </form>

                                        {{-- Botón para abrir modal --}}
                                        <x-uiverse.tooltip nameTool="Evaluaciones">
                                            @if($area->desactivacion)
                                                <!-- Botón para editar desactivación existente -->
                                                <button
                                                    type="button"
                                                    class="btn btn-warning fa fa-edit modal-trigger-btn"
                                                    style="font-size: 20px;"
                                                    data-area-id="{{$area->id}}"
                                                    data-area-nombre="{{$area->nombre}}"
                                                    data-tiene-desactivacion="true"
                                                    data-fecha-inicio="{{$area->desactivacion->fecha_inicio}}"
                                                    data-fecha-fin="{{$area->desactivacion->fecha_fin}}">
                                                </button>
                                            @else
                                                <!-- Botón para crear nueva desactivación -->
                                                <button
                                                    type="button"
                                                    class="btn btn-secondary fa fa-calendar modal-trigger-btn"
                                                    style="font-size: 20px;"
                                                    data-area-id="{{$area->id}}"
                                                    data-area-nombre="{{$area->nombre}}"
                                                    data-tiene-desactivacion="false">
                                                </button>
                                            @endif
                                        </x-uiverse.tooltip>

                                        {{-- proyectos btn abrir modal --}}
                                        <x-uiverse.tooltip nameTool="Proyectos">
                                            <button
                                                type="button"
                                                class="btn btn-success fa fa-folder-open"
                                                style="font-size: 20px;"
                                                data-toggle="modal"
                                                data-target="#proyectosModal{{ $area->id }}"                                              "
                                            >
                                            </button>
                                        </x-uiverse.tooltip>

                                        <!-- Modal Proyectos -->
                                        <div class="modal inmodal" id="proyectosModal{{ $area->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content animated bounceInRight">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                                        </button>

                                                        <i class="fa fa-laptop modal-icon"></i>
                                                        <h4 class="modal-title">Proyectos del Área: {{ $area->especializacion }}</h4>
                                                        <small class="font-bold">Aquí se muestran los últimos 3 proyectos del área.</small>
                                                        <div style="display: flex; justify-content: center;">
                                                            <button type="button"
                                                                    class="btn btn-info ml-2 open-another-modal"
                                                                    data-current="#proyectosModal{{ $area->id }}"
                                                                    data-target="#nuevoModal-{{ $area->id }}">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="modal-body">
                                                        @if($area->proyectos->count() > 0)
                                                            <div class="table-responsive">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>Nombre</th>
                                                                        <th>Descripción</th>
                                                                        <th>Fecha Inicio</th>
                                                                        <th>Fecha Fin</th>
                                                                        <th>Progreso</th>
                                                                        <th>Editar</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($area->proyectos as $proyecto)
                                                                    <tr>
                                                                        <td>{{ $proyecto->nombre }}</td>
                                                                        <td>{{ $proyecto->descripcion }}</td>
                                                                        <td>{{ $proyecto->fecha_inicio }}</td>
                                                                        <td>{{ $proyecto->fecha_fin }}</td>
                                                                        <td>
                                                                            <div class="progress">
                                                                                <div class="progress-bar"
                                                                                    role="progressbar"
                                                                                    style="width: {{ $proyecto->porcentaje }}%;"
                                                                                    aria-valuenow="{{ $proyecto->porcentaje }}"
                                                                                    aria-valuemin="0"
                                                                                    aria-valuemax="100">
                                                                                    {{ $proyecto->porcentaje }}%
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <button
                                                                                class="btn btn-info"
                                                                                type="button"
                                                                                onclick="abrirModalEdicion('{{ $area->id }}', '{{ $proyecto->id }}')">
                                                                                <i class="fa fa-edit"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <p>Esta área no tiene proyectos registrados.</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Crear Proyecto -->
                                        <div class="modal inmodal" id="nuevoModal-{{ $area->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content animated bounceInRight">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                                        </button>
                                                        <i class="fa fa-info-circle modal-icon"></i>
                                                        <h4 class="modal-title">Nuevo Proyecto</h4>
                                                        <small class="font-bold">Área {{ $area->especializacion }}</small>
                                                    </div>

                                                    <form action="{{ route('proyectos.crear') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="area_id" value="{{ $area->id }}">

                                                        <div class="modal-body modal-scroll">
                                                            <div class="form-group">
                                                                <label for="nombre">Nombre del proyecto</label>
                                                                <input type="text" class="form-control" name="nombre" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="descripcion">Descripción</label>
                                                                <textarea class="form-control" name="descripcion" rows="3"></textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="fecha_inicio">Fecha de inicio</label>
                                                                <input type="date" class="form-control" name="fecha_inicio">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="fecha_fin">Fecha de fin</label>
                                                                <input type="date" class="form-control" name="fecha_fin">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Porcentaje:</label>
                                                                <input
                                                                    type="range"
                                                                    min="0"
                                                                    max="100"
                                                                    step="1"
                                                                    name="porcentaje"
                                                                    value="{{ old('porcentaje', 0) }}"
                                                                    class="form-control-range"
                                                                    data-target="progressBar-{{ $area->id }}"
                                                                    oninput="updateProgress(this)">

                                                                <div class="progress mt-2">
                                                                    <div id="progressBar"
                                                                        class="progress-bar bg-info progressBar-{{ $area->id }}"
                                                                        style="width: 0%;">
                                                                        0%
                                                                    </div>
                                                                </div>

                                                                @error('porcentaje')
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar Proyecto</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Editar Proyecto -->
                                        @foreach($area->proyectos as $proyecto)
                                        <div class="modal inmodal" id="editarProyectoModal-{{ $proyecto->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content animated bounceInRight">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                                        </button>
                                                        <i class="fa fa-edit modal-icon"></i>
                                                        <h4 class="modal-title">Editar Proyecto</h4>
                                                        <small class="font-bold">Área {{ $area->especializacion }}</small>
                                                    </div>

                                                    <form action="{{ route('proyectos.actualizar', $proyecto->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="modal-body modal-scroll">
                                                            <div class="form-group">
                                                                <label for="nombre">Nombre del proyecto</label>
                                                                <input type="text" class="form-control" name="nombre" value="{{ $proyecto->nombre }}" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="descripcion">Descripción</label>
                                                                <textarea class="form-control" name="descripcion" rows="3">{{ $proyecto->descripcion }}</textarea>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="fecha_inicio">Fecha de inicio</label>
                                                                <input type="date" class="form-control" name="fecha_inicio" value="{{ $proyecto->fecha_inicio }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="fecha_fin">Fecha de fin</label>
                                                                <input type="date" class="form-control" name="fecha_fin" value="{{ $proyecto->fecha_fin }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Porcentaje:</label>
                                                                <input
                                                                    type="range"
                                                                    min="0"
                                                                    max="100"
                                                                    step="1"
                                                                    name="porcentaje"
                                                                    value="{{ $proyecto->porcentaje }}"
                                                                    class="form-control-range"
                                                                    oninput="updateEditProgress(this, 'editProgressBar-{{ $proyecto->id }}')">

                                                                <div class="progress mt-2">
                                                                    <div id="editProgressBar-{{ $proyecto->id }}"
                                                                        class="progress-bar bg-info"
                                                                        style="width: {{ $proyecto->porcentaje }}%;">
                                                                        {{ $proyecto->porcentaje }}%
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Actualizar Proyecto</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- modal desactivar evaluaciones --}}
                                        <div class="modal fade"
                                            id="modalDesactivarEvaluaciones{{$area->id}}"
                                            tabindex="-1"
                                            aria-labelledby="modalDesactivarEvaluacionesLabel{{$area->id}}"
                                            aria-hidden="true"
                                            data-bs-backdrop="static"
                                            data-bs-keyboard="false">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header text-dark">
                                                        <h5 class="modal-title" id="modalDesactivarEvaluacionesLabel{{$area->id}}">
                                                            <i class="fas fa-calendar-times me-2"></i>
                                                            Desactivar Evaluaciones - <span id="areaNombre{{$area->id}}">{{$area->especializacion}}</span>
                                                        </h5>
                                                    </div>
                                                    <form method="POST" id="formDesactivarEvaluaciones{{$area->id}}" action="{{ route('desactivarEvaluacionUpdate.area', $area->id) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <!-- agregar un scroball -->
                                                             <style>
                                                                #modalDesactivarEvaluaciones{{$area->id}} .modal-body {
                                                                    max-height: 80vh;
                                                                    overflow-y: auto;
                                                                }
                                                                #modalDesactivarEvaluaciones{{$area->id}} .modal-body::-webkit-scrollbar {
                                                                    width: 8px;
                                                                    background: #f1f1f1;
                                                                    border-radius: 4px;
                                                                }
                                                            </style>
                                                            <div class="alert alert-info">
                                                                Seleccione el período durante el cual las evaluaciones estarán desactivadas para esta área.
                                                            </div>

                                                            <div class="row">
                                                                {{-- fecha_inicio --}}
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3">
                                                                        <label for="fecha_inicio{{$area->id}}" class="form-label fw-bold">
                                                                            <i class="fas fa-calendar-plus text-success me-1"></i>
                                                                            Fecha de Inicio
                                                                        </label>
                                                                        <input type="date"
                                                                            class="form-control form-control-lg"
                                                                            id="fecha_inicio{{$area->id}}"
                                                                            name="fecha_inicio"
                                                                            required
                                                                            min="{{date('Y-m-d')}}"
                                                                            onchange="validarFechas({{$area->id}})">
                                                                        <small class="form-text text-muted">
                                                                            Fecha desde cuando se desactivarán las evaluaciones
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                                {{-- fecha_fin --}}
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3">
                                                                        <label for="fecha_fin{{$area->id}}" class="form-label fw-bold">
                                                                            <i class="fas fa-calendar-minus text-danger me-1"></i>
                                                                            Fecha de Fin
                                                                        </label>
                                                                        <input type="date"
                                                                            class="form-control form-control-lg"
                                                                            id="fecha_fin{{$area->id}}"
                                                                            name="fecha_fin"
                                                                            required
                                                                            min="{{date('Y-m-d')}}"
                                                                            onchange="validarFechas({{$area->id}})">
                                                                        <small class="form-text text-muted">
                                                                            Fecha hasta cuando estarán desactivadas
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mt-3">
                                                                <div class="card bg-light">
                                                                    <div class="card-body">
                                                                        <h6 class="card-title">
                                                                            <i class="fas fa-clock me-1"></i>
                                                                            Resumen del Período
                                                                        </h6>
                                                                        <p class="card-text" id="resumenPeriodo{{$area->id}}">
                                                                            Seleccione las fechas para ver el resumen del período de desactivación.
                                                                        </p>

                                                                        <style>
                                                                            .desactivacion-text {
                                                                                font-weight: bold;
                                                                            }
                                                                        </style>
                                                                       @if($area->ultima_desactivacion)
                                                                            <div>
                                                                                <span class="desactivacion-text">Fechas desactivadas</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="desactivacion-text">Fecha Inicio: {{ \Carbon\Carbon::parse($area->ultima_desactivacion->fecha_inicio)->format('Y-m-j') }}</span>
                                                                            </div>
                                                                            <div>
                                                                                <span class="desactivacion-text">Fecha Fin: {{ \Carbon\Carbon::parse($area->ultima_desactivacion->fecha_fin)->format('Y-m-j') }}</span>
                                                                            </div>
                                                                        @else
                                                                            <div>
                                                                                <span class="desactivacion-text">Sin fecha de desactivación</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary" id="btnConfirmarDesactivacion{{$area->id}}" disabled>
                                                                Desactivar Evaluaciones
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- </button> --}}
                </div>
                <div id="modal-form{{ $area->id }}" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <form role="form" method="POST"
                                    action="{{ route('areas.update', $area->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                    <input type="hidden" name="form_type" value="edit">
                                    <input type="hidden" name="area_id" value="{{ $area->id }}">
                                    <div class="row">
                                        <div class="col-sm-6 b-r">
                                            <h3 class="m-t-none m-b">Ingrese los Datos</h3>
                                            <div class="form-group">
                                                <label>Especializacion</label>
                                                <input type="text" placeholder="....."
                                                    class="form-control" name="especializacion"
                                                    id="especializacion"
                                                    value="{{ $area->especializacion }}">
                                                    @error('especializacion'.$area->id)
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>
                                            <div class="form-group"><label>Descripción</label>
                                                <input type="text" placeholder="....."
                                                    class="form-control" name="descripcion"
                                                    id="descripcion"
                                                    value="{{ $area->descripcion }}">
                                                    @error('descripcion'.$area->id)
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>
                                            <div class="form-group"><label>Color Hex</label>
                                                <input type="color" placeholder="....."
                                                    class="form-control" name="color_hex"
                                                    id="color_hex"
                                                    value="{{ $area->color_hex }}">

                                            </div>
                                            <div class="form-group"><label>Jefe del Área</label>
                                                <select class="form-control" name="jefe_area_id" id="">
                                                    <option @if($area->hasBoss == false) selected @endif value="0">Sin jefe</option>
                                                    @foreach($area->integrantes as $integrante)
                                                        <option @if($integrante->jefe_area) selected @endif value="{{$integrante->id}}">{{$integrante->colaborador->candidato->nombre}} {{$integrante->colaborador->candidato->apellido}}</option>
                                                    @endforeach
                                                </select>

                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <h4>Subir Icono</h4>
                                            <input type="file" class="form-control-file"
                                                id="icono-{{ $area->id }}" name="icono"
                                                value="{{ old('icono', $area->icono) }}"
                                                style="display: none;">
                                            <button type="button" class="btn btn-link"
                                                id="icon-upload-{{ $area->id }}">
                                                <i class="fa fa-cloud-download big-icon"></i>
                                            </button>
                                            <script>
                                                document.getElementById('icon-upload-{{ $area->id }}').addEventListener('click', function() {
                                                        document.getElementById('icono-{{ $area->id }}').click();
                                                    });
                                            </script>
                                            <div class="form-group"><label>Salón</label>
                                                <select class="form-control" name="salon_id"
                                                    required>
                                                    @foreach($salones as $key => $salon)
                                                    <option value="{{ $salon->id }}" @if($salon->id
                                                        == $area->salon_id) selected @endif
                                                        >{{ $salon->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <button
                                                    class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                    type="submit"><i
                                                        class="fa fa-check"></i>&nbsp;Confirmar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($hasPagination === true)
                <div class="row mb-5 mb-md-4">
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-10 my-3">
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== 1)
                            <a href="{{ $areas->url(1) }}" class="btn btn-outline-dark rounded-5">
                                <i class="fa fa-arrow-circle-left"></i> First
                            </a>
                        @endif
                        @if($pageData->currentPage > 1)
                            <a href="{{$pageData->previousPageUrl}}" class="btn btn-outline-dark rounded-5">
                                <i class="fa fa-arrow-circle-left"></i> Anterior
                            </a>
                        @endif
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end align-items-center gap-10">
                        @if($pageData->currentPage < $pageData->lastPage)
                            <a href="{{ $pageData->nextPageUrl }}" class="btn btn-outline-dark rounded-5">
                                Siguiente <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endif
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== $pageData->lastPage)
                            <a href="{{ $pageData->lastPageUrl }}" class="btn btn-outline-dark rounded-5">
                                Last <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>


        @include('components.inspinia.footer-inspinia')

    </div>
    </div>
    @if ($errors->any())
        <script>
            // Reabrir el modal de creación si el error proviene del formulario de creación
            console.log(@json($errors->all()));
            @if (old('form_type') == 'create')
                $('#modal-form-add').modal('show');
            @endif

            // Reabrir el modal de edición si el error proviene del formulario de edición
            @if (old('form_type') == 'edit' && old('area_id'))
                $('#modal-form' + {{ old('area_id') }}).modal('show');
            @endif
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const area = document.getElementById('areas');
            if (area) {
                area.classList.add('active');
            }
        });
    </script>

    <script src="{{ asset('js/asistencia/areas/index.js') }}"></script>


    <script>
        function confirmState(id) {
            Swal.fire({
                    title: "¿Deseas cambiar el estado de esta área?",
                    showCancelButton: true,
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';
                    //form.action = `/areas/activarInactivar/${areaId}`;

                    let routeTemplate = "<?php echo route('areas.activarInactivar', ':id'); ?>";
                    form.action = routeTemplate.replace(':id', id);

                    //console.log(routeTemplate);
                    form.innerHTML = `
                        @csrf @method("PUT")
                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                        `;

                    // if (currentURL != null) {
                    //     let inputHidden = document.createElement('input');
                    //     inputHidden.type = 'hidden';
                    //     inputHidden.name = 'currentURL';
                    //     inputHidden.value = currentURL;
                    //     form.appendChild(inputHidden);
                    // }

                    document.body.appendChild(form);
                    form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "No se cambió el estado",
                            icon: "info",
                            customClass: {
                                content: 'swal-content'
                            }
                        });

                        const style = document.createElement('style');
                        style.innerHTML = `
                            .swal2-html-container{
                                color: #FFFFFF;
                            }
                        `;
                        document.head.appendChild(style);
                    }
                    });

    }

    </script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.modal-trigger-btn');
        if (!button) return;

        e.preventDefault();
        e.stopPropagation();

        const areaId = button.getAttribute('data-area-id');
        const areaNombre = button.getAttribute('data-area-nombre');
        const tieneDesactivacion = button.getAttribute('data-tiene-desactivacion') === 'true';
        const fechaInicio = button.getAttribute('data-fecha-inicio') || '';
        const fechaFin = button.getAttribute('data-fecha-fin') || '';

        const form = document.getElementById('formDesactivarEvaluaciones' + areaId);
        const btnSubmit = document.getElementById('btnConfirmarDesactivacion' + areaId);

        const existingMethod = form.querySelector('input[name="_method"]');
        if (existingMethod) {
            existingMethod.remove();
        }

        const baseAction = form.action;

        if (tieneDesactivacion) {
            form.action = baseAction;
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            form.appendChild(methodInput);
            btnSubmit.innerHTML = '<i class="fas fa-edit me-1"></i>Actualizar Desactivación';
            btnSubmit.className = 'btn btn-warning';

            const fechaInicioInput = document.getElementById('fecha_inicio' + areaId);
            const fechaFinInput = document.getElementById('fecha_fin' + areaId);

            if (fechaInicioInput) fechaInicioInput.value = fechaInicio;
            if (fechaFinInput) fechaFinInput.value = fechaFin;

            validarFechas(areaId);
        } else {
            form.action = baseAction;
            btnSubmit.innerHTML = 'Desactivar Evaluaciones';
            btnSubmit.className = 'btn btn-danger';

            const fechaInicioInput = document.getElementById('fecha_inicio' + areaId);
            const fechaFinInput = document.getElementById('fecha_fin' + areaId);

            if (fechaInicioInput) fechaInicioInput.value = '';
            if (fechaFinInput) fechaFinInput.value = '';

            btnSubmit.disabled = true;

            const resumen = document.getElementById('resumenPeriodo' + areaId);
            if (resumen) {
                resumen.innerHTML = 'Seleccione las fechas para ver el resumen del período de desactivación.';
            }
        }

        showModal(areaId);
    });

    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form.id || !form.id.startsWith('formDesactivarEvaluaciones')) return;

        e.preventDefault();

        const areaId = form.id.replace('formDesactivarEvaluaciones', '');
        const fechaInicio = document.getElementById(`fecha_inicio${areaId}`)?.value;
        const fechaFin = document.getElementById(`fecha_fin${areaId}`)?.value;

        if (!fechaInicio || !fechaFin) {
            // console.log('Fechas incompletas');
            return;
        }

        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);

        if (fin <= inicio) {
            // console.log('Error: La fecha fin debe ser posterior a la fecha de inicio');
            return;
        }

        const formData = new FormData(form);
        form.submit();
    });

    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-bs-dismiss="modal"]') ||
            e.target.closest('[data-bs-dismiss="modal"]')) {

            const modal = e.target.closest('.modal');
            if (modal) {
                const modalId = modal.id;
                const areaId = modalId.replace('modalDesactivarEvaluaciones', '');
                hideModal(areaId);
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(function(modal) {
                if (modal.id.startsWith('modalDesactivarEvaluaciones')) {
                    const areaId = modal.id.replace('modalDesactivarEvaluaciones', '');
                    hideModal(areaId);
                }
            });
        }
    });
});

function showModal(areaId) {
    const modalElement = document.getElementById('modalDesactivarEvaluaciones' + areaId);

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modal = new bootstrap.Modal(modalElement, {
            backdrop: true,
            keyboard: true
        });
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalElement).modal({
            backdrop: true,
            keyboard: true
        }).modal('show');
    } else {
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        document.body.classList.add('modal-open');
    }
}

function hideModal(areaId) {
    const modalElement = document.getElementById('modalDesactivarEvaluaciones' + areaId);

    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
            modalInstance.hide();
        } else {
            const modal = new bootstrap.Modal(modalElement);
            modal.hide();
        }
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modalElement).modal('hide');
    } else {
        // console.error('Bootstrap no encontrado');
        modalElement.style.display = 'none';
        modalElement.classList.remove('show');
        document.body.classList.remove('modal-open');

        // Remover backdrop si existe
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

function validarFechas(areaId) {
    const fechaInicio = document.getElementById('fecha_inicio' + areaId)?.value;
    const fechaFin = document.getElementById('fecha_fin' + areaId)?.value;
    const btnConfirmar = document.getElementById('btnConfirmarDesactivacion' + areaId);
    const resumen = document.getElementById('resumenPeriodo' + areaId);
    const fechaFinInput = document.getElementById('fecha_fin' + areaId);

    if (!btnConfirmar || !resumen) {
        // console.error('No se encontraron elementos necesarios para validación');
        return;
    }

    if (fechaInicio && fechaFinInput) {
        fechaFinInput.min = fechaInicio;
    }

    if (fechaInicio && fechaFin) {
        const inicio = new Date(fechaInicio);
        const fin = new Date(fechaFin);

        if (fin <= inicio) {
            if (fechaFinInput) {
                fechaFinInput.setCustomValidity('La fecha fin debe ser posterior a la fecha de inicio');
            }
            btnConfirmar.disabled = true;
            resumen.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>La fecha fin debe ser posterior a la fecha de inicio.</span>';
        } else {
            if (fechaFinInput) {
                fechaFinInput.setCustomValidity('');
            }
            btnConfirmar.disabled = false;

            const timeDiff = fin.getTime() - inicio.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            const fechaInicioFormat = inicio.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const fechaFinFormat = fin.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            resumen.innerHTML = `
                <strong>Período seleccionado:</strong><br>
                <i class="fas fa-play text-success me-1"></i> <strong>Inicio:</strong> ${fechaInicioFormat}<br>
                <i class="fas fa-stop text-danger me-1"></i> <strong>Fin:</strong> ${fechaFinFormat}<br>
                <i class="fas fa-calendar-day text-primary me-1"></i> <strong>Duración:</strong> ${daysDiff} día${daysDiff > 1 ? 's' : ''}
            `;
        }
    } else {
        btnConfirmar.disabled = true;
        resumen.innerHTML = 'Seleccione las fechas para ver el resumen del período de desactivación.';
    }
}
</script>

<script>
    const buscador = document.getElementById("input-buscar");
    // const btnLimpiar = document.getElementById("btn-limpiar");

    function limpiarBuscador(){
        buscador.value = "";
    }

</script>

<script>
    $(document).ready(function () {
        let lastModal = null;

        $(document).on('click', '.open-another-modal', function (e) {
            e.preventDefault();

            const currentModalId = $(this).data('current');
            const targetModalId = $(this).data('target');

            lastModal = currentModalId;

            $(currentModalId).one('hidden.bs.modal', function () {
                $(targetModalId).modal('show');
            }).modal('hide');
        });

        $(document).on('hidden.bs.modal', '.modal', function () {
            const closedModalId = '#' + $(this).attr('id');

            if (lastModal && closedModalId === $(lastModal).data('target')) {
                $(lastModal).modal('show');
                lastModal = null;
            }
        });
    });
</script>


<script>
    function abrirModalEdicion(areaId, proyectoId) {
        $('#proyectosModal' + areaId).modal('hide');
        setTimeout(function () {
            $('#editarProyectoModal-' + proyectoId).modal('show');
        }, 400); // espera 400ms por la animación
    }
</script>


<script>
    function updateProgress(inputElement) {
        var target = $(inputElement).data('target');
        var progressBar = $('.' + target);
        // console.log(target);
        // console.log(progressBar);

        progressBar.css('width', inputElement.value + '%');
        progressBar.text(inputElement.value + '%');
    }
</script>

<script>
function updateEditProgress(input, barId) {
    const progressBar = document.getElementById(barId);
    const value = input.value;
    progressBar.style.width = value + '%';
    progressBar.textContent = value + '%';
}
</script>


<script>
    $(document).on('show.bs.modal', '[id^="editarProyectoModal-"]', function () {

        $('.modal.show[id^="nuevoModal-"]').each(function () {
            $(this).modal('hide');
        });

        $('.modal.show').not(this).modal('hide');
    });

    $(document).on('hidden.bs.modal', '[id^="editarProyectoModal-"]', function () {

        if ($('.modal.show').length === 0) {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        }
    });

    $(document).on('show.bs.modal', '[id^="nuevoModal-"]', function () {
        $('.modal.show[id^="editarProyectoModal-"]').each(function () {
            $(this).modal('hide');
        });
    });

</script>


<style>

.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

.modal-scroll {
    max-height: 400px;
    overflow-y: auto;
}


</style>
</body>

</html>
