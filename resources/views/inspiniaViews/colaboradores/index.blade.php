<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/colaboradores/index.css') }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>INSPINIA | Colaboradores</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-3">
                <h2>Colaboradores ({{$countColaboradores}})</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a>Personal</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Colaboradores</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-7 flex-centered">
                <div class="flex-centered spc-per-90">

                    <form id="searchColaboradores" role="form" method="GET" action="" enctype="multipart/form-data" onsubmit="return prepareSearchActionURL()"
                        class="flex-centered gap-20 spc-per-100">
                        <input type="hidden" id="searchRoute" value="{{route('colaboradores.search', ['busqueda' => '/'])}}">
                        <input id="searchInput" class="form-control wdt-per-80" type="search"
                            placeholder="Buscar Colaborador..." aria-label="Search" required autocomplete="off">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-2">

                <div class="ibox-content">
                    <div class="text-center flex-centered gap-20">
                        <a class="btn btn-primary" href="{{route('candidatos.index')}}">
                            <i class="fa fa-long-arrow-left"></i> Agregar
                        </a>
                        <a data-toggle="modal" class="btn btn-success " href="#modal-filtrar"> Filtrar </a>
                        <a href="#" class="btn btn-warning" onclick="confirmEditAll()">Activar Edición</a>
                    </div>
                    <div id="modal-filtrar" class="modal fade" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <style>
                                    #modal-filtrar .modal-body{
                                        max-height: 80vh;
                                        overflow-y: auto;
                                    }
                                    #modal-filtrar .modal-body::-webkit-scrollbar {
                                        width: 8px;
                                        background: #f1f1f1;
                                        border-radius: 4px;
                                    }
                                </style>
                                    <form id="filtrarColaboradores" role="form" method="GET" action="" enctype="multipart/form-data" onsubmit="return prepareFilterActionURL()">
                                        <h2 class="m-t-none m-b font-bold text-center">Filtrar Colaboradores</h2>
                                        <div class="accordion" id="accordionExample">
                                            <!-- Estados -->
                                            <div class="card">
                                                <div class="card-header" id="headingEstados">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseEstados" aria-expanded="true" aria-controls="collapseEstados">
                                                            Estados
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseEstados" class="collapse show" aria-labelledby="headingEstados" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-estados"><span> Seleccionar todos</span>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-1" value="1">
                                                            <span for="checkbox-estados-1">Activo</span>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-0" value="0">
                                                            <span for="checkbox-estados-0">Inactivo</span>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-2" value="2">
                                                            <span for="checkbox-estados-2">Ex colaborador</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Areas -->
                                            <div class="card">
                                                <div class="card-header" id="headingAreas">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseAreas" aria-expanded="false" aria-controls="collapseAreas">
                                                            Áreas
                                                        </button>
                                                    </h5>
                                                </div>

                                                <div id="collapseAreas" class="collapse" aria-labelledby="headingAreas" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-areas"><span> Seleccionar todos</span>
                                                        </div>
                                                        @foreach($areasAll as $index => $area)
                                                        <div class="form-check">
                                                            <input type="checkbox" id="checkbox-areas-{{$index}}" class="form-check-input area-checkbox" value="{{ $area->id }}">
                                                            <span for="checkbox-areas-{{$index}}">{{$area->especializacion}}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Carreras -->
                                            <div class="card">
                                                <div class="card-header" id="headingCarreras">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseCarreras" aria-expanded="false" aria-controls="collapseCarreras">
                                                            Carreras
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseCarreras" class="collapse" aria-labelledby="headingCarreras" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-carreras"><span> Seleccionar todos</span>
                                                        </div>
                                                        @foreach($carrerasAll as $index => $carrera)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input carrera-checkbox" value="{{ $carrera->id }}">
                                                            <span for="checkbox-carreras-{{$index}}">{{ $carrera->nombre }}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Ciclos -->
                                            <div class="card">
                                                <div class="card-header" id="headingCiclosCandidatos">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseCiclosCandidatos" aria-expanded="false" aria-controls="collapseCiclosCandidatos">
                                                            Ciclos
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseCiclosCandidatos" class="collapse" aria-labelledby="headingCiclosCandidatos" data-parent="#accordionExampleCandidatos">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-ciclos"><span> Seleccionar todos</span>
                                                        </div>
                                                        @foreach($ciclosAll as $index => $ciclo)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input ciclo-checkbox" id="checkbox-ciclo-candidatos-{{ $ciclo }}" value="{{ $ciclo }}">
                                                            <span for="checkbox-ciclo-candidatos-{{ $ciclo }}">Ciclo {{ $ciclo }}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Instituciones -->
                                            <div class="card">
                                                <div class="card-header" id="headingInstituciones">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseInstituciones" aria-expanded="false" aria-controls="collapseInstituciones">
                                                            Instituciones
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseInstituciones" class="collapse" aria-labelledby="headingInstituciones" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-instituciones"><span> Seleccionar todos</span>
                                                        </div>
                                                        @foreach($institucionesAll as $index => $institucion)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input institucion-checkbox" value="{{ $institucion->id }}">
                                                            <span for="checkbox-instituciones-{{$index}}">{{ $institucion->nombre }}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sedes -->
                                            <div class="card">
                                                <div class="card-header" id="headingSedesCandidatos">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSedesCandidatos" aria-expanded="false" aria-controls="collapseSedesCandidatos">
                                                            Sedes
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseSedesCandidatos" class="collapse" aria-labelledby="headingSedesCandidatos" data-parent="#accordionExampleCandidatos">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-sedes"><span> Seleccionar todos</span>
                                                        </div>
                                                        @foreach($sedesAll as $index => $sede)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input sede-checkbox" id="checkbox-sede-candidatos-{{ $sede->id }}" value="{{ $sede->id }}">
                                                            <span for="checkbox-sede-candidatos-{{ $sede->id }}">{{ $sede->nombre }}</span>
                                                        </div>
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>


                                            <!-- Computadoras -->
                                            <div class="card">
                                                <div class="card-header" id="headingComputadoras">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseComputadoras" aria-expanded="false" aria-controls="collapseComputadoras">
                                                            Computadoras
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseComputadoras" class="collapse" aria-labelledby="headingComputadoras" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-computadoras"><span> Seleccionar todos</span>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input computadora-checkbox" id="checkbox-computadoras-registradas" value="registradas">
                                                            <span for="checkbox-computadoras-registradas">Registradas</span>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input computadora-checkbox" id="checkbox-computadoras-no_registradas" value="no_registradas">
                                                            <span for="checkbox-computadoras-no_registradas">No registradas</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- pagos --}}
                                            {{-- <div class="card">
                                                <div class="card-header d-flex" id="headingCarreras">
                                                    <div class="mb-0 pago-check-content">
                                                        <input type="checkbox" id="pagos-checkbox">
                                                        <h5>Colaboradores pagados</h5>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <!-- Submit Button -->
                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-primary px-5">Filtrar</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            @if(session('success'))
                <div id="alert" class="alert alert-success alert-dismissible fade show d-flex align-items-start" role="alert" style="position: relative;">
                    <div style="flex-grow: 1;">
                        <strong>Éxito:</strong> {{ session('success') }}
                    </div>
                    <button onclick="deleteAlert()" type="button" class="btn btn-outline-dark btn-xs" style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
                </div>
            @endif

            @if(session('warning'))
                <div id="alert" class="alert alert-warning alert-dismissible fade show d-flex align-items-start" role="alert" style="position: relative;">
                    <div style="flex-grow: 1;">
                        <strong>Advertencia:</strong> {{ session('warning') }}
                    </div>
                    <button onclick="deleteAlert()" type="button" class="btn btn-outline-dark btn-xs" style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div id="alert" class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert" style="position: relative;">
                    <div style="flex-grow: 1;">
                        <strong>Error:</strong> {{ session('error') }}
                    </div>
                    <button onclick="deleteAlert()" type="button" class="btn btn-outline-dark btn-xs" style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
                </div>
            @endif

            <!-- ver los colaboradores -->
            <div class="row">
                @foreach($colaboradores->data as $index => $colaborador)

                <div id="modal-form-view{{$colaborador->id}}" class="modal fade" aria-hidden="true" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div style='padding: 1em' class="col-sm-6 b-r">
                                        <h3 style="font-size: 1.1rem; font-weight: bold; border-bottom: 1px solid currentColor;" class="m-t-none m-b pb-1">Información Personal </h3>
                                        <style>
                                            #modal-form-view{{$colaborador->id}} .modal-body{
                                                max-height: 85vh;
                                                overflow-y: auto;
                                            }
                                        </style>
                                        <style>
                                            .form-group {
                                                margin: 0rem;

                                            }
                                            p{
                                                margin: 0px;
                                            }
                                        </style>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; '>Nombres:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->nombre}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Apellidos:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->apellido}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Dirección:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->direccion ?? 'Sin Direccion'}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Distrito:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->distrito ? $colaborador->candidato->distrito->nombre : 'No asignado'}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Institución - Sede:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->sede->nombre ?? 'Sin Institucion - Sede'}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Ciclo:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>
                                                {{$colaborador->candidato->ciclo_de_estudiante ?? 'Sin ciclo'}}°</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Correo:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->correo ?? 'Sin correo'}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Fecha de nacimiento:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->fecha_nacimiento ?? 'Sin fecha de nacimiento'}}</p>
                                        </div>

                                         @if(!empty($colaborador->candidato->dni))
                                            <div class="form-group">
                                                <p style="font-weight: bold; font-size: 1rem; margin: 0px;">DNI:</p>
                                                <p class="overflowing-skipt" style="font-size: 0.9rem;">
                                                    {{ $colaborador->candidato->dni }}
                                                </p>
                                            </div>
                                        @elseif(!empty($colaborador->candidato->carnet_extranjeria))
                                            <div class="form-group">
                                                <p style="font-weight: bold; font-size: 1rem; margin: 0px;">Carnet Extranjería:</p>
                                                <p class="overflowing-skipt" style="font-size: 0.9rem;">
                                                    {{ $colaborador->candidato->carnet_extranjeria }}
                                                </p>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">ID Senati:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->id_senati ?? 'Sin ID'}}</p>
                                        </div>

                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Celular:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->celular ?? 'Sin celular'}}</p>
                                        </div>


                                        <div class="form-group">
                                            <p style="font-weight: bold; font-size: 1rem; margin: 0px;">Especialista de Seguimiento:</p>
                                            <p class="overflowing-skipt" style="font-size: 0.9rem;">
                                                {{ $colaborador?->especialista?->nombres ?? 'Sin Especialista' }}
                                            </p>
                                        </div>
                                        {{-- horas colab --}}
                                        <div class="form-group">
                                            <p style="font-weight: bold; font-size: 1rem; margin: 0px;">Horas Prácticas:</p>
                                            <p class="overflowing-skipt" style="font-size: 0.9rem;">
                                                {{ $colaborador->horasPracticas }} horas
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Área(s):</p>
                                            <ol class="custom-list">
                                            @foreach($colaborador->areas as $area)
                                                <li class="overflowing-skipt" style='font-size: 0.9rem;'>{{$area['nombre']}} @if($area['tipo'] === 1)(Apoyo) @endif</li>
                                            @endforeach
                                            </ol>

                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Actividades favoritas:</p>
                                            <ol class="custom-list">
                                                @foreach($colaborador->actividadesFavoritas as $actividades)
                                                    <li style='font-size: 0.9rem;' class="overflowing-skipt">{{$actividades}}</li>
                                                @endforeach
                                            </ol>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Carrera:</p>
                                            <p class="overflowing-skipt" style='font-size: 0.9rem;'>{{$colaborador->candidato->carrera->nombre}}</p>
                                        </div>
                                        <div class="form-group">
                                            <p style='font-weight: bold; font-size: 1rem; margin: 0px; ' class="m-t-none m-b">Estado:</p>
                                            <p style='font-size: 0.9rem;'>
                                                @if ($colaborador->estado == 1)
                                                <span style="color: green"><strong>Activo</strong></span>

                                                @else
                                                <span style="color: #F00"><strong>Inactivo</strong></span>
                                                @endif
                                            </p></div>
                                        <div>
                                            {{-- editar colaborador--}}
                                            <x-uiverse.tooltip nameTool="Editar">
                                                <a data-toggle="modal" id="editButton{{ $colaborador->id }}"
                                                    class="btn btn-sm btn-primary float-right m-t-n-xs fa fa-edit btn-success"
                                                    onclick="abrirModalEdicion({{$colaborador->id}});"
                                                    style="font-size: 20px; width: 60px;"
                                                    {{-- href="#modal-form-update{{$colaborador->id}}" --}}
                                                    href="javascript:void(0)"></a>
                                            </x-uiverse.tooltip>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-center text-danger">
                                        <h2><strong> Colaborador </strong></h2>
                                        <a style="color: black;"><strong>{{$colaborador->id}}</strong></a>
                                        <div class="custom-file w-200 h-300 " style="padding: 20px 0px;">
                                            <img src="{{asset('storage/candidatos/'.$colaborador->candidato->icono)}}"
                                                class="img-lg  max-min-h-w-200 img-cover">

                                        </div>

                                        <div class="botones-colabs">
                                            {{-- Redirección a computadora --}}
                                            <form id="getComputadoraColab{{$colaborador->id}}"
                                                action="{{route('colaboradores.getComputadora', $colaborador->id)}}">
                                            </form>
                                            <x-uiverse.tooltip nameTool="Maquinas">
                                            <a href="#" class="btn btn-primary btn-success fa fa-desktop"
                                                style="width: 110px; font-size: 18px;"
                                                onclick="document.getElementById('getComputadoraColab{{$colaborador->id}}').submit();">
                                            </a>
                                            </x-uiverse.tooltip>

                                            {{-- Redirección a préstamo --}}
                                            <form id="getPrestamoColab{{$colaborador->id}}" action="{{route('colaboradores.getPrestamo', $colaborador->id)}}">
                                            </form>
                                            <x-uiverse.tooltip nameTool="Prestamos">
                                            <a data-toggle="modal" class="btn btn-primary btn-success fa fa-dropbox"
                                                style="width: 110px; font-size: 18px;" href="#" onclick="document.getElementById('getPrestamoColab{{$colaborador->id}}').submit();"></a>
                                            </x-uiverse.tooltip>

                                            {{-- Redirección a librería --}}
                                            <form id="getLibroColab{{ $colaborador->id }}" action="{{ route('libro.colabLibro', $colaborador->id) }}">
                                            </form>
                                            <x-uiverse.tooltip nameTool="Libreria">
                                                <a
                                                    href="javascript:void(0);"
                                                    class="btn btn-primary btn-success fa fa-book"
                                                    style="width: 110px; font-size: 18px;"
                                                    onclick="document.getElementById('getLibroColab{{$colaborador->id}}').submit();"
                                                >
                                                </a>
                                            </x-uiverse.tooltip>
                                        </div>

                                        <div class="mt-2">
                                            <button class="btn btn-danger" onclick="confirmDespedir({{$colaborador->id}})">Despedir</button>
                                        </div>

                                        <div class="mt-2">
                                            <button
                                                class="btn btn-warning"
                                                onclick="activeEdit({{ $colaborador->id }})"
                                                {{ $colaborador->editable === 1 ? 'disabled' : '' }}
                                            >
                                                Activar Edición
                                            </button>
                                        </div>

                                        <div class="mt-2">
                                            @if(!$colaborador->hasUser)
                                                <form action="{{ route('colaboradoresEmail.store', $colaborador->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        Crear usuario
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    /* .product-box {
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                    }

                    .product-name h2 {
                        font-size: 1.5rem;
                        margin: 0;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .product-desc h5 {
                        font-size: 1rem;
                        margin: 0;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    } */

                    .overflowing-text{
                        margin: 0;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .overflowing-skipt{
                        word-wrap: break-word; /* Permite que las palabras largas se rompan y continúen en la siguiente línea */
                        overflow-wrap: break-word; /* Asegura que las palabras largas se rompan en navegadores más modernos */
                        white-space: normal;/* Permite el salto de línea normal */
                    }
                    ol.custom-list {
                        list-style-position: inside; /* Esto coloca los números dentro del contenedor, evitando el sangrado */
                        padding-left: 0; /* Elimina el relleno a la izquierda del <ol> */
                        margin-left: 0; /* Elimina el margen a la izquierda del <ol> */
                        /* width: 100%; */
                    }

                    ol.custom-list li {
                        margin: 0; /* Elimina el margen de los elementos <li> */
                        padding-left: 0; /* Opcional: Agrega un poco de espacio a la izquierda para el número, si es necesario */
                        /* width: 100%; */
                    }

                    .overflowing-skipt {
    white-space: nowrap; /* Evita que el texto se divida en varias líneas */
    overflow: hidden; /* Oculta cualquier desbordamiento */
    text-overflow: ellipsis; /* Agrega "..." cuando el texto es muy largo */
    max-width: 100%; /* Asegura que se ajuste al contenedor */
    display: inline-block; /* Evita comportamiento inesperado con listas */
}


                    .select2-selection__choice__remove {
                        paddin: 2em;
                    }

                    /* .text-center {
                        text-align: center;
                    }

                    .text-left {
                        text-align: left;
                    }

                    .small {
                        font-size: 0.875rem;
                    }

                    .product-desc {
                        flex: 1;
                    }

                    .product-box img {
                        max-width: 100%;
                        height: auto;
                    }

                    .ibox-content {
                        padding: 1rem;
                        box-sizing: border-box;
                    }

                    .btn {
                        margin: 0.5rem;
                    } */
                </style>
                {{-- mostrar colaboradores --}}
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">

                    <div
                         class="ibox">
                        <div class="ibox-content product-box"
                        @if($colaborador->estadoJefe)
                            style="box-shadow: 3px 10px 25px{{ $colaborador->estadoJefe['color'] }};"
                        @endif

                        @if($colaborador->candidato->ciclo_de_estudiante == 6)
                            style="box-shadow: 3px 5px 20px rgba(3, 125, 36);"
                        @endif

                        @if ($colaborador->estadoJefe && $colaborador->candidato->ciclo_de_estudiante == 6)
                        style="box-shadow: 3px 10px 25px{{ $colaborador->estadoJefe['color'] }};"
                        @endif>
                            <div class="text-center rounded-circle">
                                <img src="{{asset('storage/candidatos/'.$colaborador->candidato->icono)}}"
                                    class="rounded-circle max-min-h-w-200 p-a-10 img-cover">
                            </div>
                            <div class="product-desc">
                                @if($colaborador->estado != 2)
                                <span class="product-price btn-Default" style="background-color: transparent;">
                                    <form method="POST" action="{{ route('colaboradores.activarInactivar', ["colaborador_id"=> $colaborador->id]) }}">
                                        @csrf
                                        @isset($pageData->currentURL)
                                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                        @endisset


                                    </form>
                                    <button type="button" class="btn btn-{{ $colaborador->estado ? 'outline-success' : 'danger' }} btn-primary dim btn-xs" onclick="confirmState({{ $colaborador->id }})">
                                        <span>{{ $colaborador->estado ? 'Activo' : 'Inactivo' }}</span>
                                    </button>
                                </span>
                                <div title="{{$colaborador->status['message']}}" style="position: absolute; font-size: 14px; font-weight: 600; height: 35px; width: 35px; top: -27px; left: 5; border-radius: 100%">
                                    <button style="border-radius: 100%; background: {{$colaborador->status['color']}}" class="btn w-100 h-100"></button>
                                </div>
                                @else
                                <h3 class="text-danger font-weight-bold">Ex Colaborador</h3>
                                @endif

                                {{-- <p>{{$colaborador->status['message']}}</p> --}}

                                <div href="#" class="product-name">
                                    <h2 class="overflowing-text" @if($colaborador->estadoJefe)
                                        style="color: {{ $colaborador->estadoJefe['color'] }};
                                        font-weight: bold;"
                                    @endif>{{$colaborador->candidato->nombre." ".$colaborador->candidato->apellido}}
                                    @if($colaborador->estadoJefe)
                                    <span style="font-weight: normal; font-size: 0.7em; color: {{ $colaborador->estadoJefe['color'] }}; margin-left: 5px; background-color: #f0f0f0; padding: 2px 6px; border-radius: 5px;">
                                        ({{ $colaborador->estadoJefe['message'] }})
                                    </span>
                                @endif</h2>

                                </div>

                                <small class="text-muted text-left">
                                    <h3 class="text-dark" >Área(s):</h3>
                                </small>
                                <div class="small m-t-xs text-left">
                                    <h5 class="overflowing-text">
                                    @foreach($colaborador->areas as $index => $area)

                                    @if($index > 0) | @endif {{$area['nombre']}} @if($area['tipo'] === 1) <span style="color: #007">(Apoyo)</span>@endif
                                    @endforeach
                                    </h5>
                                </div>
                                <small class="text-muted text-left">
                                    <h3 class="text-dark">Ciclo:</h3>
                                </small>
                                <div class="small m-t-xs text-left">
                                    <h5 class="overflowing-text">
                                        {{$colaborador->candidato->ciclo_de_estudiante ?? 'Sin ciclo'}}°
                                    </h5>
                                </div>
                                <small class="text-muted text-left">
                                    @if(!empty($colaborador->candidato->dni))
                                        <h3 class="text-dark">DNI:</h3>
                                    @elseif(!empty($colaborador->candidato->carnet_extranjeria))
                                        <h3 class="text-dark">Carnet Extranjero:</h3>
                                    @endif
                                </small>

                                @if(!empty($colaborador->candidato->dni))
                                    <div class="small m-t-xs text-left">
                                        <h5 class="overflowing-text">{{$colaborador->candidato->dni ?? 'Sin DNI'}}</h5>
                                    </div>
                                @elseif(!empty($colaborador->candidato->carnet_extranjeria))
                                    <div class="small m-t-xs text-left">
                                        <h5 class="overflowing-text">{{$colaborador->candidato->carnet_extranjeria ?? 'Sin carnet extranjero'}}</h5>
                                    </div>
                                @endif

                                <small class="text-muted text-left">
                                    <h3 class="text-dark">ID Senati:</h3>
                                </small>

                                <div class="small m-t-xs text-left">
                                    <h5 class="overflowing-text">{{$colaborador->candidato->id_senati ?? 'Sin ID'}}</h5>
                                </div>
                                <small class="text-muted text-left">
                                    <h3 class="text-dark">Distrito:</h3>
                                </small>

                                <div class="small m-t-xs text-left">
                                    <h5 class="overflowing-text">{{$colaborador->candidato->distrito ? $colaborador->candidato->distrito->nombre : 'No asignado'}}</h5>
                                </div>

                                <small class="text-muted text-left">
                                    <h3 class="text-dark">Correo:</h3>
                                </small>
                                <div class="small m-t-xs text-left">
                                    <h5 class="overflowing-text">{{$colaborador->candidato->correo ?? 'Sin correo'}}</h5>
                                </div>

                                <small class="text-muted text-left">
                                    <h3 class="text-dark">Celular:</h3>
                                </small>
                                <div class="small m-t-xs text-left">
                                    <h5 class="overflowing-text">{{$colaborador->candidato->celular ?? 'Sin celular'}}</h5>
                                </div>

                                <div class="m-t text-righ">

                                    <a href="#" data-toggle="model"> <i></i> </a>
                                    <form id="horario-clase-{{$colaborador->id}}" role="form" method="GET"
                                        action="{{route('colaboradores.horarioClase', $colaborador->id)}}">
                                    </form>
                                    <div class="ibox-content">
                                        @if($colaborador->estado != 2)
                                        <div class="text-center d-flex justify-content-center align-items-center" style="gap:3em;">
                                            {{-- Botón calendario colaborador --}}
                                            <x-uiverse.tooltip nameTool="Horario">
                                                <button data-toggle="modal" class="btn btn-primary fa fa-clock-o"
                                                    style="font-size: 20px;"
                                                    onclick="document.getElementById('horario-clase-{{$colaborador->id}}').submit();">
                                                </button>
                                            </x-uiverse.tooltip>

                                            {{-- Botón ver colaborador --}}
                                            <x-uiverse.tooltip nameTool="Ver">
                                                <button data-toggle="modal" class="btn btn-primary btn-success fa fa-eye"
                                                    style="font-size: 20px;"
                                                    href="#modal-form-view{{$colaborador->id}}">
                                                </button>
                                            </x-uiverse.tooltip>

                                            {{-- btn pagos colab --}}
                                            <x-uiverse.tooltip nameTool="Pagos">
                                                <button data-toggle="modal" class="btn btn-primary btn-success fa fa-coins"
                                                    style="font-size: 20px;"
                                                    href="#modal-form-gasto{{$colaborador->id}}">
                                                </button>
                                            </x-uiverse.tooltip>
                                        </div>
                                        <style>
                                            /* Quitar los controles de incremento y decremento en los navegadores */
                                            input[type="number"]::-webkit-outer-spin-button,
                                            input[type="number"]::-webkit-inner-spin-button {
                                                -webkit-appearance: none;
                                                margin: 0;
                                            }

                                            input[type="number"] {
                                                -moz-appearance: textfield; /* Para Firefox */
                                            }
                                        </style>

                                        {{-- MODAL GASTO --}}
                                        <div id="modal-form-gasto{{ $colaborador->id }}" class="modal fade" aria-hidden="true">
                                            <div class="modal-dialog modal-custom">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <style>
                                                            #modal-form-gasto{{ $colaborador->id }} .modal-body {
                                                                max-height: 80vh;
                                                                overflow-y: auto;
                                                            }
                                                            #modal-form-gasto{{ $colaborador->id }} .modal-body::-webkit-scrollbar {
                                                                width: 8px;
                                                                background: #f1f1f1;
                                                                border-radius: 4px;
                                                            }
                                                        </style>
                                                        <form action="{{ route('colaboradores.pagos', $colaborador->id) }}" method="POST">
                                                            @csrf
                                                            @method('POST')

                                                            <input type="hidden" name="gastos_eliminados[{{ $colaborador->id }}]" id="eliminar-gastos-{{ $colaborador->id }}">

                                                            <h2 class="name-colab">{{ $colaborador->candidato->nombre . " " . $colaborador->candidato->apellido}}</h2>

                                                            <div class="row d-flex justify-center pagos-content">
                                                                <div class="row btn-agregar-content">
                                                                    <button type="button" class="btn btn-primary btn-pago-colab" id="add-more-{{ $colaborador->id }}">+</button>
                                                                    <span>Agregar pagos</span>
                                                                </div>

                                                                <div class="row d-flex justify-center pagos-content" id="gastos-container-{{ $colaborador->id }}">

                                                                    @foreach ($colaborador->pago_colaborador as $gasto)
                                                                        <div class="input-group mb-2 gasto-item" data-id="{{ $gasto->id }}">

                                                                            <input type="hidden" name="gasto_id[]" value="{{ $gasto->id }}">

                                                                           <div class="descripcion-content">
                                                                                <label >Descripcion</label>
                                                                                <input type="text" name="descripcion[{{ $colaborador->id }}][]" value="{{ $gasto->descripcion }}" class="form-control">
                                                                           </div>

                                                                            <div class="monto-content">
                                                                                <label >Monto</label>
                                                                                <input type="number" name="monto[{{ $colaborador->id }}][]" value="{{ $gasto->monto }}" class="form-control">
                                                                            </div>

                                                                            <button type="button" class="btn btn-danger btn-sm delete-btn btn-pagos" data-id="{{ $gasto->id }}">X</button>

                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <button type="submit" class="btn btn-primary mt-4">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- modal update -->
                                        <div id="modal-form-update{{$colaborador->id}}" class="modal fade"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-custom">
                                                <div style="min-width: 760px" class="modal-content">
                                                    <div class="modal-body">
                                                        <form role="form" method="POST"
                                                            action="{{ route('colaboradores.update', $colaborador->id) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            {{-- Campo oculto para identificar el tipo de formulario --}}
                                                            <input type="hidden" name="form_type" value="edit">
                                                            <input type="hidden" name="colaborador_id" value="{{ $colaborador->id }}">
                                                            <style>
                                                                #modal-form-update{{$colaborador->id}} .modal-dialog {
                                                                    max-height: 95vh; /* Limita la altura máxima del diálogo */
                                                                    overflow-x: hidden;
                                                                    overflow-y: auto;
                                                                    scrollbar-width: thin;
                                                                    scrollbar-color: grid;
                                                                }

                                                                #modal-form-update{{$colaborador->id}} .modal-dialog::-webkit-scrollbar {
                                                                    width: 8px;
                                                                }

                                                                #modal-form-update{{$colaborador->id}} .modal-dialog::-webkit-scrollbar-track {
                                                                    background: #f5f5f5;
                                                                }

                                                                #modal-form-update{{$colaborador->id}} .modal-dialog::-webkit-scrollbar-thumb {
                                                                    background-color: #1ab394;
                                                                    border-radius: 6px;
                                                                    border: 2px solid #f5f5f5;
                                                                }
                                                            </style>
                                                            <style>
                                                                #modal-form-view{{$colaborador->id}} .modal-body{
                                                                    max-height: 85vh;
                                                                    overflow-y: auto;
                                                                }
                                                            </style>    
                                                            <style>
                                                                .form-group {
                                                                    margin-bottom: 0rem;
                                                                }
                                                            </style>
                                                            @isset($pageData->currentURL)
                                                            <input type="hidden" name="currentURL"
                                                                value="{{ $pageData->currentURL }}">
                                                            @endisset
                                                            <div class="row">
                                                                <div class="col-sm-4 b-r">
                                                                    <h3 class="m-t-none m-b">Información Personal
                                                                    </h3>
                                                                    
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Nombres:</h5>
                                                                        </label><input type="text" placeholder="....."
                                                                            class="form-control" name="nombre"
                                                                            id="nombre"
                                                                            value="{{ $colaborador->candidato->nombre }}">
                                                                            @error('nombre'.$colaborador->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Apellidos:</h5>
                                                                        </label><input type="text" placeholder="....."
                                                                            class="form-control" name="apellido"
                                                                            id="apellido"
                                                                            value="{{ $colaborador->candidato->apellido }}">
                                                                            @error('apellido'.$colaborador->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Dirección:</h5>
                                                                        </label><input type="text" placeholder="....."
                                                                            class="form-control" name="direccion"
                                                                            id="direccion"
                                                                            value="{{  $colaborador->candidato->direccion }}">
                                                                            @error('direccion'.$colaborador->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>
                                                                            <h5 class="m-t-none">Distrito:</h5>
                                                                        </label>
                                                                        <select class="form-control select2-distrito" name="distrito_id" id="distrito_id">
                                                                            <option value="">Seleccione un distrito</option>
                                                                            @foreach($distritos as $distrito)
                                                                                <option value="{{ $distrito->id }}"
                                                                                    {{ isset($colaborador->candidato->distrito_id) && $colaborador->candidato->distrito_id == $distrito->id ? 'selected' : '' }}>
                                                                                    {{ $distrito->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>


                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Institución - Sede:
                                                                            </h5>
                                                                        </label>
                                                                        <select class="form-control" name="sede_id">
                                                                            @foreach ($sedes as $sede)
                                                                            <option value="{{ $sede->id }}" @if($sede->
                                                                                id == $colaborador->candidato->sede_id)
                                                                                selected @endif>
                                                                                {{ $sede->nombre }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Ciclo:</h5>
                                                                        </label>
                                                                        <select name="ciclo_de_estudiante"
                                                                            class="form-control">
                                                                            @for($i = 4; $i <= 10; $i++) <option
                                                                                @if($i==$colaborador->
                                                                                candidato->ciclo_de_estudiante) selected
                                                                                @endif >{{$i}}</option>
                                                                                @endfor

                                                                        </select>

                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Correo:</h5>
                                                                        </label><input type="text" placeholder="....."
                                                                            class="form-control" name="correo"
                                                                            id="correo"
                                                                            value="{{ $colaborador->candidato->correo }}">
                                                                            @error('correo'.$colaborador->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <h5 class="m-t-none">Actividades favoritas:</h5>
                                                                        </label>
                                                                        <select name="actividades_id[]" multiple
                                                                            class="form-control multiple_actividades_select">
                                                                            @foreach ($Allactividades as $actividad)
                                                                            <option value="{{ $actividad->id }}"
                                                                                @foreach($colaborador->actividadesFavoritas as $actividadNombre)
                                                                                @if($actividad->nombre ==
                                                                                $actividadNombre)
                                                                                selected
                                                                                @endif
                                                                                @endforeach
                                                                                >
                                                                                {{ $actividad->nombre }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-4 b-r">
                                                                    <h3 class="m-t-none m-b">.</h3>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Fecha de Nacimiento:
                                                                            </h5>
                                                                        </label><input type="date" placeholder="....."
                                                                            class="form-control" name="fecha_nacimiento"
                                                                            id="fecha_nacimiento"
                                                                            value="{{ $colaborador->candidato->fecha_nacimiento }}">
                                                                    </div>
                                                                    <div class="form-group" id="content-dni-carnet-colaborador-{{ $colaborador->id }}">

                                                                        @if(!empty($colaborador->candidato->dni))
                                                                            <label id="label-change-dni-carnet-colaborador-{{ $colaborador->id }}">
                                                                                <h5 class="m-t-none">DNI:</h5>
                                                                            </label>
                                                                            <div class="position-relative">
                                                                                <input type="number" placeholder="....." class="form-control" name="dni"
                                                                                    id="input-dni-carnet-colaborador-{{ $colaborador->id }}"
                                                                                    value="{{ $colaborador->candidato->dni }}"
                                                                                    autocomplete="off"
                                                                                    oninput="limitDNI(this)">
                                                                                <span id="dni-counter-colaborador-{{ $colaborador->id }}"
                                                                                    class="position-absolute"
                                                                                    style="right: 50px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">
                                                                                    {{ strlen($colaborador->candidato->dni) }}/8
                                                                                </span>
                                                                                <button class="btn btn-success btn-sm position-absolute"
                                                                                        type="button"
                                                                                        id="btn-change-colaborador-{{ $colaborador->id }}"
                                                                                        onclick="changeToDniCarnetColaborador({{ $colaborador->id }})"
                                                                                        style="right: 10px; top: 50%; transform: translateY(-50%);">
                                                                                    <i class='bx bx-undo'></i>
                                                                                </button>
                                                                            </div>
                                                                            @error('dni'.$colaborador->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror

                                                                        @elseif(!empty($colaborador->candidato->carnet_extranjeria))
                                                                            <label id="label-change-dni-carnet-colaborador-{{ $colaborador->id }}">
                                                                                <h5 class="m-t-none">Carnet Extranjería:</h5>
                                                                            </label>
                                                                            <div class="position-relative">
                                                                                <input type="text" placeholder="....." class="form-control" name="carnet_extranjeria"
                                                                                    id="input-dni-carnet-colaborador-{{ $colaborador->id }}"
                                                                                    value="{{ $colaborador->candidato->carnet_extranjeria }}"
                                                                                    autocomplete="off">
                                                                                <button class="btn btn-success btn-sm position-absolute"
                                                                                        type="button"
                                                                                        id="btn-change-colaborador-{{ $colaborador->id }}"
                                                                                        onclick="changeToDniCarnetColaborador({{ $colaborador->id }})"
                                                                                        style="right: 10px; top: 50%; transform: translateY(-50%);">
                                                                                    <i class='bx bx-undo'></i>
                                                                                </button>
                                                                            </div>
                                                                            @error('carnet_extranjeria'.$colaborador->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror

                                                                        @else
                                                                            <label id="label-change-dni-carnet-colaborador-{{ $colaborador->id }}">
                                                                                <h5 class="m-t-none">DNI:</h5>
                                                                            </label>
                                                                            <div class="position-relative">
                                                                                <input type="number" placeholder="....." class="form-control" name="dni"
                                                                                    id="input-dni-carnet-colaborador-{{ $colaborador->id }}"
                                                                                    autocomplete="off"
                                                                                    oninput="limitDNI(this)">
                                                                                <span id="dni-counter-colaborador-{{ $colaborador->id }}"
                                                                                    class="position-absolute"
                                                                                    style="right: 50px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">
                                                                                    0/8
                                                                                </span>
                                                                                <button class="btn btn-success btn-sm position-absolute"
                                                                                        type="button"
                                                                                        id="btn-change-colaborador-{{ $colaborador->id }}"
                                                                                        onclick="changeToDniCarnetColaborador({{ $colaborador->id }})"
                                                                                        style="right: 10px; top: 50%; transform: translateY(-50%);">
                                                                                    <i class='bx bx-undo'></i>
                                                                                </button>
                                                                            </div>
                                                                            @error('dni'.$colaborador->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        @endif

                                                                    </div>

                                                                    <div class="form-group">

                                                                        <label>
                                                                            <h5 class="m-t-none">ID Senati:</h5>
                                                                        </label>

                                                                       <div class="position-relative">

                                                                        <input type="number" placeholder="....."
                                                                        class="form-control" name="id_senati"
                                                                        value="{{$colaborador->candidato->id_senati}}"></input>

                                                                        @error('id_senati'.$colaborador->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                       </div>
                                                                    </div>

                                                                    <div class="form-group">

                                                                        <label>
                                                                            <h5 class="m-t-none">Celular:</h5>
                                                                        </label>

                                                                       <div class="position-relative">

                                                                        <input type="number" placeholder="....."
                                                                        class="form-control" name="celular"
                                                                        id="cel-update-{{ $colaborador->id }}"
                                                                        value="{{ $colaborador->candidato->celular }}" oninput="limitCel(this)"></input>
                                                                        <span id="cel-counter-update-{{ $colaborador->id }}" class="position-absolute" style="right: 10px; top: 40%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">0/9</span>
                                                                        @error('celular'.$colaborador->id)
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                       </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>
                                                                            <h5 class="m-t-none">Área(s):</h5>
                                                                        </label>
                                                                        <select name="areas_id[]" multiple
                                                                            class="form-control multiple_areas_select">
                                                                            @foreach ($areas as $key => $area)
                                                                            <option value="{{ $area->id }}"
                                                                                @foreach($colaborador->areas as $areaNombre)
                                                                                    @if($areaNombre['tipo'] === 0)
                                                                                        @if($area->especializacion ==
                                                                                        $areaNombre['nombre'])
                                                                                        selected
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                                >
                                                                                {{ $area->especializacion }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <h5 class="m-t-none">Área(s) Apoyo:</h5>
                                                                        </label>
                                                                        <select name="areas_apoyo_id[]" multiple
                                                                            class="form-control multiple_apoyo_select">
                                                                            @foreach ($areas as $key => $area)
                                                                            <option value="{{ $area->id }}"
                                                                                @foreach($colaborador->areas as $areaNombre)
                                                                                    @if($areaNombre['tipo'] === 1)
                                                                                        @if($area->especializacion ==
                                                                                        $areaNombre['nombre'])
                                                                                        selected
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                                >
                                                                                {{ $area->especializacion }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Carrera:</h5>
                                                                        </label>
                                                                        <select class="form-control" name="carrera_id">
                                                                            @foreach ($carreras as $carrera)
                                                                            <option value="{{ $carrera->id }}"
                                                                                @if($carrera->id == old('carrera_id',
                                                                                $colaborador->candidato->carrera_id))
                                                                                selected @endif>
                                                                                {{ $carrera->nombre }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h5 class="m-t-none">Especialista de Seguimiento:</h5>
                                                                        </label>
                                                                        <select class="form-control" name="especialista_id">
                                                                            <option value="0" @if($colaborador->especialista_id == null) selected @endif>Sin Especialista</option>
                                                                            @foreach ($especialistas as $especialista)
                                                                            <option value="{{ $especialista->id }}"
                                                                                @if($especialista->id == $colaborador->especialista_id) selected @endif>
                                                                                {{ $especialista->nombres }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="mt-4 text-center">
                                                                        <button
                                                                            class="btn btn-sm btn-primary float-left m-t-n-xs btn-success"
                                                                            type="submit">
                                                                            <strong>Guardar</strong>
                                                                        </button>

                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-4 text-center text-danger">
                                                                    <h2><strong> Colaborador </strong></h2>
                                                                    <a style="color: black;"><strong>ID: {{
                                                                            $colaborador->id }}</strong></a>
                                                                    <div class="custom-file w-200 h-300 "
                                                                        style="padding: 20px 0px;">


                                                                        <input type="file" class="form-control-file"
                                                                            id="icono-{{ $colaborador->candidato->id }}"
                                                                            name="icono"
                                                                            value="{{ old('icono', $colaborador->candidato->icono) }}"
                                                                            style="display: none;">
                                                                        <button type="button" class="btn btn-link"
                                                                            id="icon-upload-{{ $colaborador->candidato->id }}">
                                                                            <img src="{{ asset('storage/candidatos/' . $colaborador->candidato->icono) }}"
                                                                                class="img-lg w-200 max-min-h-w-200 img-cover">

                                                                        </button>
                                                                        @error('icono'.$colaborador->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                        <script>
                                                                            document.getElementById('icon-upload-{{ $colaborador->candidato->id }}').addEventListener('click', function() {
                                                                                document.getElementById('icono-{{ $colaborador->candidato->id }}').click();
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                    {{-- tooltip btns colab --}}
                                                                    <div style="display: flex; gap:2px; flex-direction: column">
                                                                        <x-uiverse.tooltip nameTool="Maquinas">
                                                                            <a href="#"
                                                                            class="btn btn-primary btn-success fa fa-desktop"
                                                                            style="width: 100px; font-size: 18px;"
                                                                            onclick="document.getElementById('getComputadoraColab{{$colaborador->id}}').submit();">
                                                                            </a>
                                                                        </x-uiverse.tooltip>

                                                                        <x-uiverse.tooltip nameTool="Prestamos">
                                                                            <a data-toggle="modal"
                                                                            class="btn btn-primary btn-success fa fa-dropbox"
                                                                            style="width: 100px; font-size: 18px;"
                                                                            href=""
                                                                            onclick="document.getElementById('getLibroColab{{$colaborador->id}}').submit();"></a>
                                                                        </x-uiverse.tooltip>


                                                                        <x-uiverse.tooltip nameTool="Libreria">
                                                                            <a
                                                                                href="javascript:void(0);"
                                                                                class="btn btn-primary btn-success fa fa-book"
                                                                                style="width: 100px; font-size: 18px;"
                                                                                onclick="document.getElementById('getLibroColab{{$colaborador->id}}').submit();"
                                                                            >
                                                                            </a>
                                                                        </x-uiverse.tooltip>

                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </form>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center d-flex justify-content-center gap-10">
                                            {{-- <form role="form" method="POST" action="{{route('colaboradores.recontratarColaborador', $colaborador->id)}}">
                                                @csrf
                                                @method('PUT')
                                                @isset($pageData->currentURL)
                                                <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                                @endisset

                                            </form> --}}
                                            <button class="btn btn-success" type="submit" onclick="confirmRecontratar({{ $colaborador->id }}, '{{ $pageData->currentURL }}')">
                                                Re Contratar
                                            </button>
                                            {{-- delete --}}
                                                <button class="btn btn-danger" type="button" onclick="confirmDelete({{ $colaborador->id }}, '{{ $pageData->currentURL }}')">
                                                    Eliminar
                                                </button>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


            @if($hasPagination === true)
            <div class="row mb-5 mb-md-4">
                <div
                    class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-10 my-3">
                    @if($pageData->lastPage > 2 && $pageData->currentPage !== 1)
                    <a href="{{ $colaboradores->url(1) }}" class="btn btn-outline-dark rounded-5">
                        <i class="fa fa-arrow-circle-left"></i> Primero
                    </a>
                    @endif
                    @if($pageData->currentPage > 1)
                    <a href="{{$pageData->previousPageUrl}}" class="btn btn-outline-dark rounded-5">
                        <i class="fa fa-arrow-circle-left"></i> Anterior
                    </a>
                    @endif
                </div>
                <div
                    class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end align-items-center gap-10">
                    @if($pageData->currentPage < $pageData->lastPage)
                        <a href="{{ $pageData->nextPageUrl }}" class="btn btn-outline-dark rounded-5">
                            Siguiente <i class="fa fa-arrow-circle-right"></i>
                        </a>
                        @endif
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== $pageData->lastPage)
                        <a href="{{ $pageData->lastPageUrl }}" class="btn btn-outline-dark rounded-5">
                            Último <i class="fa fa-arrow-circle-right"></i>
                        </a>
                        @endif
                </div>
            </div>
            @endif
            <style>
                .swal2-container {
                    position: fixed;
                    z-index: 9999999999999;
                }

                .select2-selection__choice {
                background-color: #f1f1f1 !important;
                border: 1px solid #aaa !important;
                border-radius: 4px !important;
                font-size: 12px !important;
                padding-left: 2em !important;
                }

                .select2-selection__choice__display {
                word-break: normal !important;
                white-space: normal !important;
                overflow: visible !important;
                }

                .select2-container--default .select2-selection--multiple {
                border: 1px solid #ced4da !important;
                }
            </style>

        </div>




        @include('components.inspinia.footer-inspinia')
    </div>



    </div>
    <!-- Script para manejar los errores globalmente -->
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                console.error("Error: {{ $error }}");
            @endforeach
        @endif
    </script>

    {{-- MODAL SCRIPT --}}
    @if ($errors->any())
        <script>
            console.log(@json($errors->all())); // Muestra todos los errores en la consola
            document.addEventListener('DOMContentLoaded', function() {
                @if (old('form_type') == 'edit' && old('colaborador_id'))
                $('#modal-form-update' + {{ old('colaborador_id') }}).modal('show');
                @endif
            });
        </script>
    @endif

    <style>
        .select2-container.select2-container--default.select2-container--open {
            z-index: 9999 !important;
            width: 100% !important;
        }


        .select2-container {
            display: inline !important;
        }


    </style>

    <script src="{{ asset('js/asistencia/colaboradores.js') }}"></script>

    <script>
$(document).ready(function() {
    // Desactivar el comportamiento por defecto de Bootstrap para Escape
    $.fn.modal.Constructor.prototype.escape = function() {};

    // Manejar Escape manualmente
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27) { // Escape key
            var $visibleModals = $('.modal.show');
            if ($visibleModals.length > 0) {
                // Obtener el modal que está más arriba
                var $topModal = $visibleModals.last();

                // Cerrar solo ese modal
                $topModal.modal('hide');

                // Prevenir propagación
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }
    });

    // Limpiar backdrops cuando se cierra cualquier modal
    $('.modal').on('hidden.bs.modal', function () {
        setTimeout(function() {
            var visibleModals = $('.modal.show').length;
            var backdrops = $('.modal-backdrop').length;

            if (backdrops > visibleModals) {
                $('.modal-backdrop').slice(visibleModals).remove();
            }

            if (visibleModals === 0) {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            }
        }, 50);
    });
});

function abrirModalEdicion(colaboradorId) {
    $('#modal-form-view' + colaboradorId).modal('hide');

    $('#modal-form-view' + colaboradorId).one('hidden.bs.modal', function() {
        setTimeout(function() {
            $('#modal-form-update' + colaboradorId).modal('show');
        }, 100);
    });
}
</script>

    <script>
        function confirmState(id) {
            Swal.fire({
                title: "¿Deseas cambiar el estado del colaborador?",
                showCancelButton: true,
                confirmButtonText: "Confirmar",
                cancelButtonText: "Cancelar",
            }).then((result) => {
                if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';
                    // form.action = `/colaboradores/activar-inactivar/${id}`;

                    let routeTemplate = "<?php echo route('colaboradores.activarInactivar', ':id'); ?>";
                    form.action = routeTemplate.replace(':id', id);

                    form.innerHTML = `
                        @csrf @method("PUT")
                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                        `;

                    document.body.appendChild(form);
                    form.submit();
                } else {

                    Swal.fire({
                        title: "Acción cancelada",
                        text: "El colaborador no fue cambiado de estado",
                        icon: "info",
                        customClass: {
                            content: 'swal-content'
                        }
                    });

                    const style = document.createElement('style');
                    style.innerHTML = `
                        .swal2-html-container {
                            color: #FFFFFF;
                        }
                    `;
                    document.head.appendChild(style);
                }
            });
        }
    </script>

    <script>
        function confirmEditAll() {
        Swal.fire({
            title: "¿Deseas activar la edición para todos los colaboradores?",
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {

                let form = document.createElement('form');
                form.method = 'POST';

                let routeTemplate = "<?php echo route('colaboradores.editAll'); ?>";
                form.action = routeTemplate;

                form.innerHTML = `
                    @csrf @method("PUT")
                `;

                document.body.appendChild(form);
                form.submit();

            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "No se activó la edición",
                    icon: "info",
                    customClass: {
                        content: 'swal-content'
                    }
                });

                const style = document.createElement('style');
                style.innerHTML = `
                    .swal2-html-container {
                        color: #FFFFFF;
                    }
                `;
                document.head.appendChild(style);
            }
        });
    }
        function activeEdit(id) {
            Swal.fire({
                    title: "¿Deseas activar la edición?",
                    showCancelButton: true,
                    confirmButtonText: "Activar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';

                    let routeTemplate = "<?php echo route('colaboradores.editState', ':id'); ?>";


                    form.action = routeTemplate.replace(':id', id);
                    form.innerHTML = `@csrf @method("PUT")
                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                    `;

                    document.body.appendChild(form);
                    form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "La edición no fue activada",
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
                    // console.log(result);
                    });

        }

        function confirmDespedir(id) {
            Swal.fire({
                    title: "¿Deseas despedir a este colaborador?",
                    showCancelButton: true,
                    confirmButtonText: "Despedir",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';

                    let routeTemplate = "<?php echo route('colaboradores.despedirColaborador', ':id'); ?>";


                    form.action = routeTemplate.replace(':id', id);
                    form.innerHTML = `@csrf @method("PUT")
                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                        `;


                    document.body.appendChild(form);
                    form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "El colaborador no fue despedido",
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
                    // console.log(result);
                    });

        }


        function changeToDniCarnetColaborador(colaboradorId) {
            const contentDiv = document.getElementById('content-dni-carnet-colaborador-' + colaboradorId);
            const currentLabel = document.getElementById('label-change-dni-carnet-colaborador-' + colaboradorId).querySelector('h5').textContent;

            if (currentLabel === 'DNI:') {
                // Cambiar a carnet_extranjeria
                contentDiv.innerHTML = `
                    <input type="hidden" name="dni" value="">
                    <label id="label-change-dni-carnet-colaborador-${colaboradorId}">
                        <h5 class="m-t-none">Carnet Extranjería:</h5>
                    </label>
                    <div class="position-relative">
                        <input type="text" placeholder="....." class="form-control" name="carnet_extranjeria"
                            id="input-dni-carnet-colaborador-${colaboradorId}"
                            autocomplete="off" value="">
                        <button class="btn btn-success btn-sm position-absolute"
                                type="button"
                                id="btn-change-colaborador-${colaboradorId}"
                                onclick="changeToDniCarnetColaborador(${colaboradorId})"
                                style="right: 10px; top: 50%; transform: translateY(-50%);">
                            <i class='bx bx-undo'></i>
                        </button>
                    </div>
                `;
            } else {
                // Cambiar a DNI
                contentDiv.innerHTML = `
                    <input type="hidden" name="carnet_extranjeria" value="">
                    <label id="label-change-dni-carnet-colaborador-${colaboradorId}">
                        <h5 class="m-t-none">DNI:</h5>
                    </label>
                    <div class="position-relative">
                        <input type="number" placeholder="....." class="form-control" name="dni"
                            id="input-dni-carnet-colaborador-${colaboradorId}"
                            autocomplete="off"
                            oninput="limitDNI(this)" value="">
                        <span id="dni-counter-colaborador-${colaboradorId}"
                            class="position-absolute"
                            style="right: 50px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">
                            0/8
                        </span>
                        <button class="btn btn-success btn-sm position-absolute"
                                type="button"
                                id="btn-change-colaborador-${colaboradorId}"
                                onclick="changeToDniCarnetColaborador(${colaboradorId})"
                                style="right: 10px; top: 50%; transform: translateY(-50%);">
                            <i class='bx bx-undo'></i>
                        </button>
                    </div>
                `;
            }
        }


        function  confirmRecontratar(id, currentURL){
            Swal.fire({
                    title: "¿Deseas re contratar a este colaborador?",
                    showCancelButton: true,
                    confirmButtonText: "Re contratar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';
                    // form.action = `/colaboradores/recontratarColaborador/${id}`;

                    let routeTemplate = "<?php echo route('colaboradores.recontratarColaborador', ':id'); ?>";
                    form.action = routeTemplate.replace(':id', id);

                    form.innerHTML = `@csrf @method("PUT")
                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                    `;

                    if (currentURL != null) {
                        let inputHidden = document.createElement('input');
                        inputHidden.type = 'hidden';
                        inputHidden.name = 'currentURL';
                        inputHidden.value = currentURL;
                        form.appendChild(inputHidden);
                    }

                    document.body.appendChild(form);
                    form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "El colaborador no fue eliminado",
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


        function confirmDelete(id, currentURL) {
            Swal.fire({
                    title: "¿Deseas eliminar este registro? Se eliminará todo lo relacionado a este colaborador",
                    showCancelButton: true,
                    confirmButtonText: "Eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';
                    // form.action = `/colaboradores/${id}`;

                    let routeTemplate = "<?php echo route('colaboradores.destroy', ':id'); ?>";
                    form.action = routeTemplate.replace(':id', id);

                    form.innerHTML = `@csrf @method("DELETE")
                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                        `;

                    if (currentURL != null) {
                        let inputHidden = document.createElement('input');
                        inputHidden.type = 'hidden';
                        inputHidden.name = 'currentURL';
                        inputHidden.value = currentURL;
                        form.appendChild(inputHidden);
                    }

                    document.body.appendChild(form);
                    form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "El colaborador no fue eliminado",
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
         function prepareSearchActionURL(event) {
            // preventDefault();

            let busqueda = document.getElementById('searchInput').value;

            if(busqueda.trim().length > 0){
                // console.log(busqueda);

                let actionUrl = `{{ url('colaboradores/search/${busqueda}') }}`;
                // console.log(actionUrl);
                document.querySelector('#searchColaboradores').action = actionUrl;

                return true;
            } else{
                event.preventDefault();
                return false;
            }

        }


    const colaboradoresFiltrarBaseUrl = '{{ route("colaboradores.filtrar", ["estados" => "PLACEHOLDER_ESTADOS","areas" => "PLACEHOLDER_AREAS","carreras" => "PLACEHOLDER_CARRERAS","instituciones" => "PLACEHOLDER_INSTITUCIONES","ciclos" => "PLACEHOLDER_CICLOS","sedes" => "PLACEHOLDER_SEDES","computadoras" => "PLACEHOLDER_COMPUTADORAS"]) }}';

    function prepareFilterActionURL() {
        let estados = Array.from(document.querySelectorAll('.estado-checkbox:checked')).map(cb => cb.value);
        let areas = Array.from(document.querySelectorAll('.area-checkbox:checked')).map(cb => cb.value);
        let carreras = Array.from(document.querySelectorAll('.carrera-checkbox:checked')).map(cb => cb.value);
        let instituciones = Array.from(document.querySelectorAll('.institucion-checkbox:checked')).map(cb => cb.value);
        let ciclos = Array.from(document.querySelectorAll('.ciclo-checkbox:checked')).map(cb => cb.value);
        let sedes = Array.from(document.querySelectorAll('.sede-checkbox:checked')).map(cb => cb.value);
        let computadoras = Array.from(document.querySelectorAll('.computadora-checkbox:checked')).map(cb => cb.value);

        estados = estados.length ? estados.join(',') : '1';
        areas = areas.length ? areas.join(',') : '0';
        carreras = carreras.length ? carreras.join(',') : '0';
        instituciones = instituciones.length ? instituciones.join(',') : '0';
        ciclos = ciclos.length ? ciclos.join(',') : '0';
        sedes = sedes.length ? sedes.join(',') : '0';
        computadoras = computadoras.length ? computadoras.join(',') : '0';

        if(estados != null && areas != null && carreras != null && instituciones != null && ciclos != null && sedes != null && computadoras != null){
             let actionUrl = colaboradoresFiltrarBaseUrl
            .replace('PLACEHOLDER_ESTADOS', estados)
            .replace('PLACEHOLDER_AREAS', areas)
            .replace('PLACEHOLDER_CARRERAS', carreras)
            .replace('PLACEHOLDER_INSTITUCIONES', instituciones)
            .replace('PLACEHOLDER_CICLOS', ciclos)
            .replace('PLACEHOLDER_SEDES', sedes)
            .replace('PLACEHOLDER_COMPUTADORAS', computadoras);

        document.querySelector('#filtrarColaboradores').action = actionUrl;
        return true;
        }
    }

    function updateSelectAll(checkboxGroup, selectAllId) {
        const selectAllCheckbox = document.getElementById(selectAllId);
        const checkboxes = document.querySelectorAll(checkboxGroup);
        selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
    }

    // Event listeners para los "select all" - SOLO UNA VEZ CADA UNO
    document.getElementById('select-all-estados').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.estado-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-areas').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.area-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-carreras').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.carrera-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-instituciones').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.institucion-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-ciclos').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.ciclo-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-sedes').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.sede-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('select-all-computadoras').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.computadora-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Event listeners para actualizar los "select all" cuando cambian los checkboxes individuales
    document.querySelectorAll('.estado-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.estado-checkbox', 'select-all-estados');
        });
    });

    document.querySelectorAll('.area-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.area-checkbox', 'select-all-areas');
        });
    });

    document.querySelectorAll('.carrera-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.carrera-checkbox', 'select-all-carreras');
        });
    });

    document.querySelectorAll('.institucion-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.institucion-checkbox', 'select-all-instituciones');
        });
    });

    document.querySelectorAll('.ciclo-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.ciclo-checkbox', 'select-all-ciclos');
        });
    });

    document.querySelectorAll('.sede-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.sede-checkbox', 'select-all-sedes');
        });
    });

    document.querySelectorAll('.computadora-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectAll('.computadora-checkbox', 'select-all-computadoras');
        });
    });
    </script>
</body>

</html>