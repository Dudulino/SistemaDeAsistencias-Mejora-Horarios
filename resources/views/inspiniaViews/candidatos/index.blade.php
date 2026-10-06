<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inspina|Candidatos</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-3">
                <h2>Candidatos</h2>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a>Personal</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Candidatos</strong>
                    </li>

                </ol>

            </div>
            <div class="col-lg-7 flex-centered">
                <div class="flex-centered spc-per-90">
                    <form id="searchCandidatos" role="form" method="GET" action="" enctype="multipart/form-data" onsubmit="return prepareSearchActionURL(event)"
                        class="flex-centered gap-20 spc-per-100">
                        <input id="searchInput" class="form-control wdt-per-80" type="search"
                            placeholder="Buscar Candidato..." aria-label="Search" required autocomplete="off">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="py-3">
                    <!-- {{-- abrir modal agregar --}} -->
                    <button class="btn btn-success dim float-right" href="#modal-form-add" data-toggle="modal"
                        type="button">Agregar</button>
                    <button data-toggle="modal" class="btn btn-primary dim float-right" href="#modal-filtrar"> Filtrar </button>
                    
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
                                <form id="filtrarCandidatos" role="form" method="GET" action="" enctype="multipart/form-data" onsubmit="return prepareFilterActionURL()">
                                    <h2 class="m-t-none m-b font-bold text-center">Filtrar Candidatos</h2>

                                    <div class="accordion" id="accordionExampleCandidatos">
                                        <!-- Estados -->
                                        <div class="card">
                                            <div class="card-header" id="headingEstadosCandidatos">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseEstadosCandidatos" aria-expanded="true" aria-controls="collapseEstadosCandidatos">
                                                        Estados
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseEstadosCandidatos" class="collapse show" aria-labelledby="headingEstadosCandidatos" data-parent="#accordionExampleCandidatos">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="select-all-estados"><span> Seleccionar todos</span>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-candidatos-1" value="1">
                                                        <span for="checkbox-estados-candidatos-1">Pendiente</span>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-candidatos-0" value="0">
                                                        <span for="checkbox-estados-candidatos-0">Colaborador</span>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-candidatos-2" value="2">
                                                        <span for="checkbox-estados-candidatos-2">Rechazado</span>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input estado-checkbox" id="checkbox-estados-candidatos-3" value="3">
                                                        <span for="checkbox-estados-candidatos-3">Ex colaborador</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Carreras -->
                                        <div class="card">
                                            <div class="card-header" id="headingCarrerasCandidatos">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseCarrerasCandidatos" aria-expanded="false" aria-controls="collapseCarrerasCandidatos">
                                                        Carreras
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseCarrerasCandidatos" class="collapse" aria-labelledby="headingCarrerasCandidatos" data-parent="#accordionExampleCandidatos">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="select-all-carreras"><span> Seleccionar todos</span>
                                                    </div>
                                                    @foreach($carrerasAll as $index => $carrera)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input carrera-checkbox" id="checkbox-carreras-candidatos-{{ $index }}" value="{{ $carrera->id }}">
                                                        <span for="checkbox-carreras-candidatos-{{ $index }}">{{ $carrera->nombre }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>



                                        <!-- Ciclos -->
                                            <div class="card">
                                                <div class="card-header" id="headingCiclosCandidatos">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#collapseCiclosCandidatos" aria-expanded="false" aria-controls="collapseCiclosCandidatos">
                                                            Ciclos
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapseCiclosCandidatos" class="collapse" aria-labelledby="headingCiclosCandidatos"
                                                    data-parent="#accordionExampleCandidatos">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <input type="checkbox" id="select-all-ciclos"><span> Seleccionar todos</span>
                                                        </div>
                                                        @foreach($ciclosAll as $index => $ciclo)
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input ciclo-checkbox"
                                                            id="checkbox-ciclo-candidatos-{{ $ciclo }}" value="{{ $ciclo }}">
                                                            <span for="checkbox-ciclo-candidatos-{{ $ciclo }}">Ciclo {{ $ciclo }}</span>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                        <!-- Instituciones -->
                                        <div class="card">
                                            <div class="card-header" id="headingInstitucionesCandidatos">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseInstitucionesCandidatos" aria-expanded="false" aria-controls="collapseInstitucionesCandidatos">
                                                        Instituciones
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapseInstitucionesCandidatos" class="collapse" aria-labelledby="headingInstitucionesCandidatos" data-parent="#accordionExampleCandidatos">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input type="checkbox" id="select-all-instituciones"><span> Seleccionar todos</span>
                                                    </div>
                                                    @foreach($institucionesAll as $index => $institucion)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input institucion-checkbox" id="checkbox-instituciones-candidatos-{{ $index }}" value="{{ $institucion->id }}">
                                                        <span for="checkbox-instituciones-candidatos-{{ $index }}">{{ $institucion->nombre }}</span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        {{-- sedes --}}
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
                {{-- MODAL STORE --}}
                <div id="modal-form-add" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <style>
                                    #modal-form-add .modal-body {
                                        max-height: 80vh;
                                        overflow-y: auto;
                                    }
                                    #modal-form-add .modal-body::-webkit-scrollbar {
                                        width: 8px;
                                        background: #f1f1f1;
                                        border-radius: 4px;
                                    }
                                </style>
                                
                                <form role="form" method="POST" action="{{ route('candidatos.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="form_type" value="create">

                                    @isset($pageData->currentURL)
                                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                    @endisset
                                    <div class="row">
                                        <div class="col-sm-6 b-r">
                                            <h3 class="m-t-none m-b">Ingrese los Datos</h3>
                                            <div class="form-group"><label>Nombre</label>
                                                <input type="text" placeholder="Ingrese un nombre" autocomplete="off" class="form-control" name="nombre" value="{{ old('nombre') }}">
                                                @error('nombre')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group"><label>Apellido</label> <input type="text" placeholder="Ingrese apellido" class="form-control" name="apellido" value="{{ old('apellido')}}"
                                                    autocomplete="off">
                                                @error('apellido')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group" id="content-dni-carnet">
                                                <label id="label-change-dni-carnet">DNI</label>
                                                <button class="btn btn-success btn-sm" type="button" id="btn-change" onclick="changeToDniCarnet()">
                                                    <i class='bx bx-undo'></i>
                                                </button>
                                                <div class="position-relative">
                                                    <input type="number" placeholder="Ingrese dni" id="input-dni-carnet" class="form-control" name="dni">
                                                    {{-- <span id="dni-counter-store" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">0/8</span> --}}
                                                </div>
                                                @error('dni')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
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

                                            <div class="form-group">
                                                <label>Dirección</label>
                                                <input type="text"
                                                    placeholder="Ingrese dirección" class="form-control"
                                                    name="direccion" value="{{ old('direccion')}}" autocomplete="off">

                                                    @error('direccion')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Distrito</label>
                                                <select class="form-control select2-distrito" name="distrito_id" id="distrito_id">
                                                    <option value="">Seleccione un distrito</option>
                                                    @foreach($distritos as $distrito)
                                                        <option value="{{ $distrito->id }}">
                                                            {{ $distrito->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>ID Senati</label>
                                                <input type="text"
                                                    placeholder="Ingrese su id" class="form-control"
                                                    name="id_senati" value="{{ old('id_senati')}}" autocomplete="off">

                                                    @error('id_senati')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                            <div class="form-group"><label>Fecha de Nacimiento</label> <input
                                                    type="date" class="form-control" name="fecha_nacimiento" value="{{ old('fecha_nacimiento')}}" autocomplete="off">
                                            </div>
                                            <div class="form-group"><label>Ciclo de Estudiante</label>
                                                <select name="ciclo_de_estudiante" class="form-control" >
                                                    @for($i = 4; $i <= 10; $i++)
                                                        <option value="{{ $i }}"
                                                            @if($i == old('ciclo_de_estudiante')) selected @endif>
                                                            {{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('ciclo_de_estudiante')
                                                        <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>


                                        </div>
                                        <div class="col-sm-6">
                                            <h4>Subir Icono</h4>
                                            <input type="file" class="form-control-file" id="icono" name="icono" value="{{ old('icono')}}"
                                                style="display: none;">
                                            <button type="button" class="btn btn-link" id="icon-upload">
                                                <i class="fa fa-cloud-download big-icon"></i>
                                            </button>
                                            @error('icono')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group"><label>Institucion - Sede</label>
                                                <select class="form-control" name="sede_id" >
                                                    @foreach($sedes as $sede)
                                                        <option value="{{ $sede->id }}"
                                                            @if($sede->id == old('sede_id')) selected @endif>
                                                            {{ $sede->nombre }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                @error('sede_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group"><label>Carrera</label>
                                                <select class="form-control" name="carrera_id" >
                                                    @foreach($carreras as $carrera)
                                                        <option value="{{ $carrera->id }}"
                                                            @if($carrera->id == old('carrera_id')) selected @endif>
                                                            {{ $carrera->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('carrera_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>


                                            <div class="form-group"><label>Correo</label> <input type="email"
                                                placeholder="correo@gmail.com" class="form-control" name="correo" value="{{old('correo')}}" autocomplete="off">
                                                @error('correo')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>




                                            <div class="form-group">
                                                <label>Celular</label>
                                               <div class="position-relative">
                                                    <input type="number" id="cel-store"
                                                    placeholder="Ingrese celular" class="form-control" name="celular" oninput="limitCel(this)">
                                                    <span id="cel-counter-store" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">0/9</span>
                                                    @error('celular')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                               </div>
                                            </div>

                                        </div>

                                        <div>
                                            <button class="btn btn-primary btn-sm m-t-n-xs float-right" type="submit" ><i
                                                    class="fa fa-check"></i>&nbsp;Confirmar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="wrapper wrapper-content animated fadeInRight">
            @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Advertencia!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>¡Éxito!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
            @if(session('error'))
            <div id="alert-error" class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert" style="position: relative;">
                <div style="flex-grow: 1;">
                    <strong>Error:</strong> {{ session('error') }}
                </div>
                <button onclick="deleteAlertError()" type="button" class="btn btn-outline-dark btn-xs" style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            @endif

            <div class="row">
                @foreach ($candidatos as $index => $candidato)
                {{-- MODAL SHOW --}}
                <div id="modal-form-view{{$candidato->id}}" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <style>
                                        #modal-form-view{{$candidato->id}} .modal-body{
                                            max-height: 85vh;
                                            overflow-y: auto;
                                        }
                                    </style>
                                    <div class="col-sm-6 b-r">
                                        <h3 class="m-t-none m-b">Información Personal </h3>


                                        <form role="form">
                                            <style>
                                                .form-group {
                                                    margin-bottom: 0rem;
                                                }
                                            </style>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Nombres:</h5>
                                                </label><label for="">{{$candidato->nombre ?? 'Sin nombre'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Apellidos:</h5>
                                                </label><label for="">{{$candidato->apellido ?? 'Sin apellido'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Dirección:</h5>
                                                </label><label for="">{{$candidato->direccion ?? 'Sin dirección'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Distrito:</h5>
                                                </label><label for="">{{$candidato->distrito ? $candidato->distrito->nombre : 'No asignado'}}</label>

                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Institución - Sede:</h5>
                                                </label><label for="">{{$candidato->sede->nombre ?? 'Sin Institucion - Sede'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Ciclo:</h5>
                                                </label><label for="">{{$candidato->ciclo_de_estudiante  ?? 'Sin ciclo'}}°</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Correo:</h5>
                                                </label><label for="">{{$candidato->correo  ?? 'Sin correo'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">fecha de
                                                        Nacimiento:</h5>
                                                </label><label for="">{{$candidato->fecha_nacimiento  ?? 'Sin fecha de nacimiento'}}</label>
                                            </div>
                                            @if(!empty($candidato->dni))
                                                <div class="form-group"><label>
                                                        <h5 class="m-t-none m-b">DNI:</h5>
                                                    </label><label for="">{{$candidato->dni  ?? 'Sin DNI'}}</label>
                                                </div>
                                            @elseif(!empty($candidato->carnet_extranjeria))
                                                <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Carnet Extranjería:</h5>
                                                    </label><label for="">{{$candidato->carnet_extranjeria  ?? 'Sin carnet extranjero'}}</label>
                                                </div>
                                            @elseif(!empty($candidato->dni) && !empty($candidato->carnet_extranjeria))
                                                <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Documento:</h5>
                                                    </label><label for="">Sin documento de identificación</label>
                                                </div>
                                            @endif

                                            <div class="form-group"><label>
                                                <h5 class="m-t-none m-b">ID Senati:</h5>
                                                </label><label for="">{{$candidato->id_senati  ?? 'Sin ID'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Celular:</h5>
                                                </label><label for="">{{$candidato->celular  ?? 'Sin celular'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Carrera:</h5>
                                                </label><label for="">{{$candidato->carrera->nombre  ?? 'Sin carrera'}}</label>
                                            </div>
                                            <div class="form-group"><label>
                                                    <h5 class="m-t-none m-b">Estado: </h5>
                                                </label><label for="">
                                                    @if ($candidato->estado == 1)
                                                    <span style="color: green"><strong>Pendiente</strong></span>
                                                    @elseif($candidato->estado == 0)
                                                    <span style="color: gold"><strong>Colaborador</strong></span>
                                                    @else
                                                    <span style="color: #F00"><strong>Rechazado</strong></span>
                                                    @endif
                                                </label></div>
                                            <div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-sm-6 text-center text-danger">
                                        <h2><strong> Candidato </strong></h2>
                                        <a style="color: black;"><strong>{{$candidato->id}}</strong></a>
                                        <div class="custom-file w-200 h-300 " style="padding: 20px 0px;">
                                            <img src="{{asset('storage/candidatos/'.$candidato->icono)}}"
                                                class="img-lg  max-min-h-w-200 img-cover">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="ibox ">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-sm-6 b-r">
                                    <div class="text-center rounded-circle">
                                        <img src="{{asset('storage/candidatos/'.$candidato->icono)}}"
                                            class="rounded-circle max-min-h-w-200 p-a-25 img-cover">
                                    </div>
                                    <p class="text-center">
                                        {{ $candidato->nombre." ".$candidato->apellido}}
                                    </p>
                                    <p class="text-center">
                                    @if ($candidato->estado == 1)
                                    <div class="d-flex gap-10">
                                        <form class="text-center" method="GET"
                                            action="{{ route('candidatos.form', $candidato->id) }}">
                                            <button class="btn btn-primary" type="submit">
                                                Agregar Colaborador
                                            </button>
                                        </form>
                                        {{-- <form class="text-center" method="POST"
                                            action="{{ route('candidatos.rechazarCandidato', $candidato->id) }}">
                                            @csrf
                                            @isset($pageData->currentURL)
                                            <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                            @endisset

                                        </form> --}}
                                        <button class="btn btn-danger" type="submit" onclick="confirmRechazar({{ $candidato->id }}, '{{ $pageData->currentURL }}')">
                                            Rechazar
                                        </button>
                                    </div>

                                    @elseif($candidato->estado == 0)
                                    <div class="text-center">
                                        <h3 style="color: gold" class="font-bold">Colaborador</h3>
                                    </div>
                                    @elseif($candidato->estado == 2)
                                    <div class="d-flex justify-content-center gap-10">
                                        {{-- <form class="text-center" method="POST"
                                            action="{{ route('candidatos.reconsiderarCandidato', $candidato->id) }}">
                                            @csrf
                                            @isset($pageData->currentURL)
                                            <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                            @endisset

                                        </form> --}}
                                        <button class="btn btn-success" type="submit" onclick="confirmReconsiderar({{ $candidato->id }}, '{{ $pageData->currentURL }}')">
                                            Reconsiderar
                                        </button>
                                        <button class="btn btn-danger" type="button" onclick="confirmDelete({{ $candidato->id }}, '{{ $pageData->currentURL }}')">
                                            Eliminar
                                        </button>
                                    </div>
                                    @elseif($candidato->estado == 3)
                                    <div class="text-center">
                                        <h3 class="text-danger font-bold">Ex Colaborador</h3>
                                    </div>
                                    @endif

                                    </p>
                                </div>
                                <div class="col-sm-6">

                                    <dl class="row mb-0">
                                        <div class="col-sm-6 text-sm-left">
                                            <dt>Carrera:</dt>
                                            <dd class="sm-2"> {{$candidato->carrera->nombre ?? 'Sin carrera'}} </dd>
                                        </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        @if(!empty($candidato->dni))
                                            <div class="col-sm-6 text-sm-left">
                                                <dt>DNI:</dt>
                                                <dd class="sm-2">{{$candidato->dni ?? 'Sin DNI'}}</dd>
                                            </div>
                                        @elseif(!empty($candidato->carnet_extranjeria))
                                            <div class="col-sm-6 text-sm-left">
                                                <dt>Carnet Extranjería:</dt>
                                                <dd class="sm-2">{{$candidato->carnet_extranjeria ?? 'Sin carnet extranjero'}}</dd>
                                            </div>
                                        @elseif(!empty($candidato->dni) && !empty($candidato->carnet_extranjeria))
                                            <div class="col-sm-6 text-sm-left">
                                                <dt>Sin documento:</dt>
                                                <dd class="sm-2">Sin documento de identificación</dd>
                                            </div>
                                        @endif
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-6 text-sm-left">
                                            <dt>Correo</dt>
                                            <dd class="sm-2">{{$candidato->correo ?? 'Sin correo'}}</dd>
                                        </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-6 text-sm-left">
                                            <dt>Celular</dt>
                                            <dd class="sm-2">{{$candidato->celular ?? 'Sin celular'}}</dd>
                                        </div>
                                    </dl>
                                    <dl class="row mb-0">
                                        <div class="col-sm-6 text-sm-left">
                                            <dt>Distrito</dt>
                                            <dd class="sm-2">{{$candidato->distrito ? $candidato->distrito->nombre : 'No asignado'}}</dd>
                                        </div>
                                    </dl>

                                    <div>
                                        <div class="d-flex justify-content-center align-items-center" style="display: flex; gap: 2px">
                                            {{-- botón ver colaborador --}}
                                            <x-uiverse.tooltip nameTool="Ver">
                                                <button class="btn btn-success float-right mx-2" type="button"
                                                    href="#modal-form-view{{$candidato->id}}" data-toggle="modal"><i
                                                        style="font-size: 20px" class="fa fa-eye"></i></button>
                                            </x-uiverse.tooltip>

                                            {{-- botón editar colaborador --}}
                                            <x-uiverse.tooltip nameTool="Editar">
                                                <button id="editButton{{ $candidato->id }}" class="btn btn-info float-right mx-2" type="button"
                                                href="#modal-form{{$candidato->id}}" data-toggle="modal"><i
                                                    style="font-size: 20px" class="fa fa-paste"></i></button>
                                            </x-uiverse.tooltip>
                                        </div>

                                        {{-- MODAL UPDATE --}}
                                        <div id="modal-form{{$candidato->id}}" class="modal fade" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <form role="form" method="POST"
                                                            action="{{ route('candidatos.update', $candidato->id) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            @isset($pageData->currentURL)
                                                            <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                                            @endisset

                                                            <input type="hidden" name="form_type" value="edit">
                                                            <input type="hidden" name="candidato_id" value="{{ $candidato->id }}">
                                                            <style>
                                                                #modal-form{{$candidato->id}} .modal-dialog {
                                                                    max-height: 90vh; /* Limita la altura máxima del diálogo */
                                                                    overflow-x: hidden;
                                                                    overflow-y: auto;
                                                                    scrollbar-width: thin;
                                                                    scrollbar-color: grid;
                                                                }

                                                                #modal-form{{$candidato->id}} .modal-dialog::-webkit-scrollbar {
                                                                    width: 8px;
                                                                }

                                                                #modal-form{{$candidato->id}} .modal-dialog::-webkit-scrollbar-track {
                                                                    background: #f5f5f5;
                                                                }

                                                                #modal-form{{$candidato->id}} .modal-dialog::-webkit-scrollbar-thumb {
                                                                    background-color: #1ab394;
                                                                    border-radius: 6px;
                                                                    border: 2px solid #f5f5f5;
                                                                }
                                                            </style>
                                                            <div class="row">
                                                                <div class="col-sm-6 b-r">
                                                                    <h3 class="m-t-none m-b">Ingrese los Datos</h3>
                                                                    <div class="form-group">
                                                                        <label>Nombre</label>
                                                                        <input type="text" placeholder="....."
                                                                            class="form-control" name="nombre"
                                                                            id="nombre"
                                                                            value="{{ $candidato->nombre }}">
                                                                            @error('nombre'.$candidato->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror

                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Apellido</label>
                                                                        <input type="text" placeholder="....."
                                                                            class="form-control" name="apellido"
                                                                            id="apellido"
                                                                            value="{{ $candidato->apellido }}">
                                                                            @error('apellido'.$candidato->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="form-group" id="content-dni-carnet-update-{{ $candidato->id }}">
                                                                        @if(!empty($candidato->dni))
                                                                            <label id="label-change-dni-carnet-update-{{ $candidato->id }}">DNI</label>
                                                                            <button class="btn btn-success btn-sm" type="button" id="btn-change-update-{{ $candidato->id }}" onclick="changeToDniCarnetUpdate({{ $candidato->id }})">
                                                                                <i class='bx bx-undo'></i>
                                                                            </button>
                                                                            <div class="position-relative">
                                                                                <input type="number" placeholder="Ingrese dni" id="input-dni-carnet-update-{{ $candidato->id }}" class="form-control" name="dni" value="{{ $candidato->dni }}" autocomplete="off" oninput="limitDNI(this)">
                                                                                <span id="dni-counter-update-{{ $candidato->id }}" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">{{ strlen($candidato->dni) }}/8</span>
                                                                            </div>
                                                                            @error('dni.' . $candidato->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        @elseif(!empty($candidato->carnet_extranjeria))
                                                                            <label id="label-change-dni-carnet-update-{{ $candidato->id }}">Carnet Extranjería</label>
                                                                            <button class="btn btn-success btn-sm" type="button" id="btn-change-update-{{ $candidato->id }}" onclick="changeToDniCarnetUpdate({{ $candidato->id }})">
                                                                                <i class='bx bx-undo'></i>
                                                                            </button>
                                                                            <div class="position-relative">
                                                                                <input type="text" placeholder="Ingrese carnet de extranjería" id="input-dni-carnet-update-{{ $candidato->id }}" class="form-control" name="carnet_extranjeria" autocomplete="off" value="{{ $candidato->carnet_extranjeria }}">
                                                                            </div>
                                                                            @error('carnet_extranjeria.' . $candidato->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        @else
                                                                            <label id="label-change-dni-carnet-update-{{ $candidato->id }}">DNI</label>
                                                                            <button class="btn btn-success btn-sm" type="button" id="btn-change-update-{{ $candidato->id }}" onclick="changeToDniCarnetUpdate({{ $candidato->id }})">
                                                                                <i class='bx bx-undo'></i>
                                                                            </button>
                                                                            <div class="position-relative">
                                                                                <input type="number" placeholder="Ingrese dni" id="input-dni-carnet-update-{{ $candidato->id }}" class="form-control" name="dni" autocomplete="off" oninput="limitDNI(this)">
                                                                                <span id="dni-counter-update-{{ $candidato->id }}" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">0/8</span>
                                                                            </div>
                                                                            @error('dni.' . $candidato->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        @endif
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>ID Senati</label>
                                                                        <div class="position-relative">
                                                                            <!-- Campo ID senati -->
                                                                            <input type="number"  placeholder="Ingrese ID senati" class="form-control" name="id_senati" value="{{ $candidato->id_senati ?? 'No tiene' }}" autocomplete="off" >
                                                                            @error('id_senati'.$candidato->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        @error('dni.' . $candidato->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="form-group"><label>Dirección</label>
                                                                        <input type="text" placeholder="....."
                                                                            class="form-control" name="direccion"
                                                                            id="direccion"
                                                                            value="{{ $candidato->direccion }}">
                                                                            @error('direccion'.$candidato->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Distrito</label>
                                                                        <select class="form-control select2-distrito" name="distrito_id" id="distrito_id">
                                                                            <option value="">Seleccione un distrito</option>
                                                                            @foreach($distritos as $distrito)
                                                                                <option value="{{ $distrito->id }}"
                                                                                    {{ isset($candidato->distrito_id) && $candidato->distrito_id == $distrito->id ? 'selected' : '' }}>
                                                                                    {{ $distrito->nombre }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group"><label>Fecha de
                                                                            Nacimiento</label>
                                                                        <input type="date" placeholder="....."
                                                                            class="form-control" name="fecha_nacimiento"
                                                                            id="fecha_nacimiento"
                                                                            value="{{ $candidato->fecha_nacimiento }}">

                                                                    </div>
                                                                    <div class="form-group"><label>Ciclo de
                                                                            Estudiante</label>
                                                                            <select name="ciclo_de_estudiante" id="ciclo_de_estudiante" class="form-control" >
                                                                                @for($i = 4; $i <= 10; $i++)
                                                                                    <option value="{{ $i }}"
                                                                                        @if($i ==  $candidato->ciclo_de_estudiante) selected @endif>
                                                                                        {{ $i }}
                                                                                    </option>
                                                                                @endfor
                                                                            </select>
                                                                            @error('ciclo_de_estudiante')
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <h4>Subir Ícono</h4>
                                                                    <input type="file" class="form-control-file"
                                                                        id="icono-{{ $candidato->id }}" name="icono"
                                                                        value="{{ old('icono', $candidato->icono) }}"
                                                                        style="display: none;">
                                                                    <button type="button" class="btn btn-link"
                                                                        id="icon-upload-{{ $candidato->id }}">
                                                                        <i class="fa fa-cloud-download big-icon"></i>
                                                                    </button>
                                                                    @error('icono'.$candidato->id)
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                    <script>
                                                                        document.getElementById('icon-upload-{{ $candidato->id }}').addEventListener('click', function() {
                                                                        document.getElementById('icono-{{ $candidato->id }}').click();
                                                                    });
                                                                    </script>
                                                                    <div class="form-group">
                                                                        <label>Institución - Sede</label>
                                                                        <select class="form-control" name="sede_id">
                                                                            @foreach($sedes as $sede)
                                                                            <option value="{{ $sede->id }}" @if($sede->
                                                                                id ==

                                                                                $candidato->sede_id) selected
                                                                                @endif>{{ $sede->nombre }}
                                                                            </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('sede_id')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group"><label>Carrera</label>
                                                                        <select class="form-control" name="carrera_id">
                                                                            @foreach($carreras as $carrera)
                                                                            <option value="{{ $carrera->id }}"
                                                                                @if($carrera->id ==
                                                                                $candidato->carrera_id) selected
                                                                                @endif >{{ $carrera->nombre }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('carrera_id')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group"><label>Correo</label>
                                                                        <input type="text" placeholder="....."
                                                                            class="form-control" name="correo"
                                                                            id="correo"
                                                                            value="{{ $candidato->correo }}">
                                                                        @error('correo'.$candidato->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="form-group">
                                                                      <div class="position-relative">
                                                                        <label>Celular</label>
                                                                        <input type="number" placeholder="....."
                                                                            class="form-control" name="celular"
                                                                            id="cel-update-{{ $candidato->id }}"
                                                                            oninput="limitCel(this)" value="{{ $candidato->celular }}">
                                                                             <span id="cel-counter-update-{{ $candidato->id }}" class="position-absolute" style="right: 10px; top: 70%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">0/9</span>
                                                                        @error('celular'.$candidato->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                      </div>
                                                                    </div>
                                                                    <div class="mt-4">
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
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-10 my-3">
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== 1)
                            <a href="{{ $candidatos->url(1) }}" class="btn btn-outline-dark rounded-5">
                                <i class="fa fa-arrow-circle-left"></i> Primero
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
                                Último <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @include('components.inspinia.footer-inspinia')

    </div>
    </div>
    <style>
        .select2-container.select2-container--default.select2-container--open {
            z-index: 9999 !important;
            width: 100% !important;
        }

        .select2-container {
            display: inline !important;
        }


    </style>
    @if ($errors->any())
        <script>
            // Reabrir el modal de creación si el error proviene del formulario de creación
            console.log(@json($errors->all()));
            @if (old('form_type') == 'create')
                $('#modal-form-add').modal('show');
            @endif

            // Reabrir el modal de edición si el error proviene del formulario de edición
            @if (old('form_type') == 'edit' && old('candidato_id'))
                $('#modal-form' + {{ old('candidato_id') }}).modal('show');
            @endif
        </script>
    @endif



<script src="{{ asset('js/asistencia/candidatos.js') }}"></script>

<script>
     function prepareSearchActionURL(event) {
            let busqueda = document.getElementById('searchInput').value;

            if(busqueda.trim().length > 0) {
                let actionUrl = `{{ url('candidatos/search/${busqueda}') }}`;
                console.log(actionUrl);
                document.querySelector('#searchCandidatos').action = actionUrl;

                return true;
            } else{
                event.preventDefault();
                return false;
            }
        }

        function prepareFilterActionURL() {
            let estados = Array.from(document.querySelectorAll('.estado-checkbox:checked')).map(cb => cb.value);
            let carreras = Array.from(document.querySelectorAll('.carrera-checkbox:checked')).map(cb => cb.value);
            let instituciones = Array.from(document.querySelectorAll('.institucion-checkbox:checked')).map(cb => cb.value);
            let ciclos = Array.from(document.querySelectorAll('.ciclo-checkbox:checked')).map(cb => cb.value);
            let sedes = Array.from(document.querySelectorAll('.sede-checkbox:checked')).map(cb => cb.value);

            estados = estados.length ? estados.join(',') : '1';
            carreras = carreras.length ? carreras.join(',') : '0';
            instituciones = instituciones.length ? instituciones.join(',') : '0';
            ciclos = ciclos.length ? ciclos.join(',') : '0';
            sedes = sedes.length ? sedes.join(',') : '0';



            if(estados != null && carreras != null && instituciones != null && ciclos != null, sedes != null) {
                let actionUrl = `{{ url('candidatos/filtrar/estados=${estados}/carreras=${carreras}/instituciones=${instituciones}/ciclos=${ciclos}/sedes=${sedes}') }}`;
                console.log(actionUrl);
                document.querySelector('#filtrarCandidatos').action = actionUrl;

                return true;
            }
        }
</script>


    <script>
        $(document).ready(function() {
            $('.select2-distrito').select2({
                placeholder: "Buscar distrito...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>

    </script>
    <script>
        const hiddenFileInput = document.getElementById('icono');
        const iconUploadButton = document.getElementById('icon-upload');

        iconUploadButton.addEventListener('click', function() {
            hiddenFileInput.click();
        });
    </script>

    <script>



        function confirmReconsiderar(id, currentURL) {
                Swal.fire({
                    title: "¿Deseas reconsiderar este candidato?",
                    showCancelButton: true,
                    confirmButtonText: "Reconsiderar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/candidato/reconsiderarCandidato/${id}`;

                    form.innerHTML = '@csrf @method("POST")';

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
                            text: "El candidato no fue reconsiderado",
                            icon: "info",
                            customClass: {
                                content: 'swal-content'
                            }
                        });

                        const style = document.createElement('style');
                        style.innerHTML = `
                            .swal2-html-container{
                                color: #FFFFFF;  // Cambia el color del texto del contenido
                            }
                        `;
                        document.head.appendChild(style);
                    }
                    });
            }


            function confirmRechazar(id, currentURL) {
                Swal.fire({
                    title: "¿Deseas rechazar este candidato?",
                    showCancelButton: true,
                    confirmButtonText: "Rechazar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                    // Crear el formulario y enviarlo
                    let form = document.createElement('form');
                    form.method = 'POST';
                    let routeTemplate = "<?php echo route('candidatos.rechazarCandidato', ':id'); ?>";
                    form.action = routeTemplate.replace(':id', id);

                    // Agregar el CSRF token y el método HTTP necesario
                    form.innerHTML = `@csrf @method("POST")
                     <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                    `;

                    // Agregar el parámetro currentURL, si está disponible
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
                            text: "El candidato no fue rechazado",
                            icon: "info",
                            customClass: {
                                content: 'swal-content'  // Asignamos una clase personalizada al contenido
                            }
                        });
                        // Luego, en tu CSS, puedes cambiar el color del contenido
                        const style = document.createElement('style');
                        style.innerHTML = `
                            .swal2-html-container{
                                color: #FFFFFF;  // Cambia el color del texto del contenido
                            }
                        `;
                        document.head.appendChild(style);
                                    }
                    });
            }


        function confirmDelete(id, currentURL) {

            Swal.fire({
                title: "¿Deseas eliminar este registro? Esta acción es permanente",
                showCancelButton: true,
                confirmButtonText: "Despedir",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/candidatos/${id}`;

                form.innerHTML = '@csrf @method("DELETE")';

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
                        text: "El candidato no fue despedido",
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

            // alertify.confirm("¿Deseas eliminar este registro? Esta acción es permanente", function(e) {
            //     if (e) {
            //         let form = document.createElement('form')

            //         form.method = 'POST'
            //         form.action = `/candidatos/${id}`
            //         form.innerHTML = '@csrf @method('DELETE')'

            //         if(currentURL != null){
            //             let inputHidden = document.createElement('input');
            //             inputHidden.type = 'hidden';
            //             inputHidden.name = 'currentURL';
            //             inputHidden.value = currentURL;
            //             form.appendChild(inputHidden)
            //         }

            //         document.body.appendChild(form)
            //         form.submit()
            //     } else {
            //         return false
            //     }
            // });
        }



    </script>
    <script>

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function changeToDniCarnet() {
            const contentDiv = document.getElementById('content-dni-carnet');
            const currentLabel = document.getElementById('label-change-dni-carnet').textContent;

            if (currentLabel === 'DNI') {
                // cambiarlo a carnet_extranjeria
                contentDiv.innerHTML = `
                    <label id="label-change-dni-carnet">Carnet Extranjería</label>
                    <button class="btn btn-success btn-sm" type="button" id="btn-change" onclick="changeToDniCarnet()">
                        <i class='bx bx-undo'></i>
                    </button>
                    <div class="position-relative">
                        <input type="text" placeholder="Ingrese carnet de extranjería" id="input-dni-carnet" class="form-control" name="carnet_extranjeria">
                    </div>
                `;
            } else {
                // cambiarlo a DNI
                contentDiv.innerHTML = `
                    <label id="label-change-dni-carnet">DNI</label>
                    <button class="btn btn-success btn-sm" type="button" id="btn-change" onclick="changeToDniCarnet()">
                        <i class='bx bx-undo'></i>
                    </button>
                    <div class="position-relative">
                        <input type="number" placeholder="Ingrese dni" id="input-dni-carnet" class="form-control" name="dni">
                    </div>
                `;
            }
        }

        function changeToDniCarnetUpdate(candidatoId) {
            const contentDiv = document.getElementById('content-dni-carnet-update-' + candidatoId);
            const currentLabel = document.getElementById('label-change-dni-carnet-update-' + candidatoId).textContent;
            const currentInput = document.getElementById('input-dni-carnet-update-' + candidatoId);

            const currentValue = currentInput ? currentInput.value : '';

            if (currentLabel === 'DNI') {
                contentDiv.innerHTML = `
                    <input type="hidden" name="dni" value="">
                    <label id="label-change-dni-carnet-update-${candidatoId}">Carnet Extranjería</label>
                    <button class="btn btn-success btn-sm" type="button" id="btn-change-update-${candidatoId}" onclick="changeToDniCarnetUpdate(${candidatoId})">
                        <i class='bx bx-undo'></i>
                    </button>
                    <div class="position-relative">
                        <input type="text" placeholder="Ingrese carnet de extranjería" id="input-dni-carnet-update-${candidatoId}" class="form-control" name="carnet_extranjeria" autocomplete="off" value="">
                    </div>
                `;
            } else {
                contentDiv.innerHTML = `
                    <input type="hidden" name="carnet_extranjeria" value="">
                    <label id="label-change-dni-carnet-update-${candidatoId}">DNI</label>
                    <button class="btn btn-success btn-sm" type="button" id="btn-change-update-${candidatoId}" onclick="changeToDniCarnetUpdate(${candidatoId})">
                        <i class='bx bx-undo'></i>
                    </button>
                    <div class="position-relative">
                        <input type="number" placeholder="Ingrese dni" id="input-dni-carnet-update-${candidatoId}" class="form-control" name="dni" autocomplete="off" oninput="limitDNI(this)" value="">
                        <span id="dni-counter-update-${candidatoId}" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); font-size: 0.9rem; color: gray;">0/8</span>
                    </div>
                `;
            }
        }
    </script>
</body>

</html>
