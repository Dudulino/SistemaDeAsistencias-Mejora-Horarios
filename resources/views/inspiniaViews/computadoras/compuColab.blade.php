<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA | Computadora y Programas</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Gestión Programas</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('colaboradores.index')}}">Colaboradores</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Computadora y Programas</strong>
                    </li>
                </ol>
            </div>

        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                @if(!$hasComputer)
                <div class="col-md-12">
                    <div class="ibox">
                        <div class="ibox-content product-box">
                            <div class="col-lg-12">
                                <div class="ibox ">
                                    <div class="ibox-content">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <h1>No hay computadora creada</h1>
                                            </div>
                                            <div class="col-sm-3">
                                                <button class="btn btn-primary" href="#ModalStoreComputer"
                                                    data-toggle="modal" type="button">Crear Computadora</button>
                                            </div>
                                            <div id="ModalStoreComputer" class="modal fade" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <form method="POST"
                                                                action="{{route('computadora.storeComputadoraColab')}}">
                                                                @csrf
                                                                <input type="number" name="colaborador_id"
                                                                    value="{{$colaborador->id}}" hidden />
                                                                <div class="row">
                                                                    <div class="col-sm-6 b-r">
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Tipo:</dt>
                                                                                <select class="form-control m-b"
                                                                                    name="es_laptop" required>
                                                                                    <option value="0"> Computadora
                                                                                    </option>
                                                                                    <option value="1"> Laptop</option>
                                                                                </select>
                                                                            </div>
                                                                        </dl>
                                                                        <p class="text-center">
                                                                            <a href=""><i
                                                                                    class="fa fa-desktop big-icon "></i></a>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Código de Serie:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="codigo_serie" required />
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Procesador:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="procesador" required />
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Tarjeta Gráfica:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="tarjeta_grafica" required />
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Memoria Gráfica:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="memoria_grafica" required />
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Ram:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="ram" required />
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Almacenamiento:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="almacenamiento" required />
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Estado de la PC:</dt>
                                                                                <select name="estado_pc" class="form-control" required>
                                                                                    <option value="">Seleccione el estado</option>
                                                                                    <option value="2">Apto</option>
                                                                                    <option value="1">Medio</option>
                                                                                    <option value="0">Mal</option>
                                                                                </select>
                                                                            </div>
                                                                        </dl>

                                                                        <div><br><br>
                                                                            <div>
                                                                                <button
                                                                                    class="btn btn-sm btn-primary float-left m-t-n-xs btn-success"
                                                                                    type="submit"><strong>
                                                                                        Guardar</strong></button>
                                                                                <div>
                                                                                    <a class="btn btn-sm btn-primary float-right m-t-n-xs  btn-success"
                                                                                        href=""><strong>Descartar</strong></a>
                                                                                </div>
                                                                            </div>


                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            @if(session('success'))
                                                                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                                                    {{ session('success') }}
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            @endif

                                                            @if(session('error'))
                                                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                                                    {{ session('error') }}
                                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            @endif

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
                </div>
                @else
                <div class="col-md-12">
                    <div class="ibox">
                        <div class="ibox-content product-box">
                            <a class="product-name " style="margin-left: 20px;">
                                <h1>Computadora</h1>
                            </a>
                            <div class="col-lg-12">
                                <div class="ibox ">
                                    <div class="ibox-content">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-3 b-r">
                                                <dl class="row mb-0">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Tipo:</dt>
                                                        <dd class="sm-2">{{$computerColab->es_laptop === 1 ?
                                                            'Laptop' :
                                                            'Computadora'}}
                                                        </dd>
                                                    </div>
                                                </dl>
                                                <p class="text-center">
                                                    <a href=""><i class="fa fa-desktop big-icon "></i></a>
                                                </p>

                                                <div class="text-center mt-4">
                                                    <form role="form" method="POST"
                                                        action="{{route('computadora.activarInactivar', ["colaborador_id"=> $colaborador->id, "computadora_id" => $computerColab->id])}}">
                                                        @method('put')
                                                        @csrf
                                                        <button
                                                            class="btn {{$computerColab->estado? 'btn-primary' : 'btn-danger'}} btn-md"
                                                            type="submit"><strong>{{$computerColab->estado? 'Activo' :
                                                                'Inactivo'}}</strong></button>
                                                    </form>
                                                </div>


                                                {{-- <div class="form-group"><label></label>
                                                    <input type="checkbox" class="js-switch_4" checked />
                                                    <a style="color: black;">Activo</a>

                                                </div> --}}

                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-3 b-r">
                                                <dl class="row mb-3">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Código de Serie:</dt>
                                                        <dd class="sm-2">{{$computerColab->codigo_serie}}</dd>
                                                    </div>
                                                </dl>
                                                <dl class="row mb-4">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Procesador:</dt>
                                                        <dd class="sm-2">{{$computerColab->procesador}}</dd>
                                                    </div>
                                                </dl>
                                                <dl class="row mb-4">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Tarjeta Gráfica:</dt>
                                                        <dd class="sm-2">{{$computerColab->tarjeta_grafica}}</dd>
                                                    </div>
                                                </dl>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-3 b-r">
                                                <dl class="row mb-3">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Memoria Gráfica:</dt>
                                                        <dd class="sm-2">{{$computerColab->memoria_grafica}}</dd>
                                                    </div>
                                                </dl>
                                                <dl class="row mb-4">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Ram:</dt>
                                                        <dd class="sm-2">{{$computerColab->ram}}</dd>
                                                    </div>
                                                </dl>
                                                <dl class="row mb-4">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Almacenamiento:</dt>
                                                        <dd class="sm-2">{{$computerColab->almacenamiento}}</dd>
                                                    </div>
                                                </dl>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-3 b-r">
                                                <dl class="row mb-3">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Último Mantenimiento</dt>
                                                        <dd class="sm-2">{{($ultimaIncidencia) ?
                                                            $ultimaIncidencia->fecha : 'No hay
                                                            mantenimientos'}}</dd>
                                                    </div>
                                                </dl>
                                                <dl class="row mb-4">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Incidencias:</dt>
                                                        <a data-toggle="modal"
                                                            class="btn btn-primary btn-success fa fa-eye"
                                                            style="font-size: 20px;" href="#modalIncidencias"></a>
                                                    </div>
                                                </dl>
                                                <dl class="row mb-4">
                                                    <div class="col-sm-6 text-sm-left">
                                                        <dt>Estado PC:</dt>
                                                        <dd class="sm-2">
                                                            @if($computerColab->estado_pc === null)
                                                                No tiene estado
                                                            @elseif($computerColab->estado_pc === 0)
                                                                Mal
                                                            @elseif($computerColab->estado_pc === 1)
                                                                Medio
                                                            @elseif($computerColab->estado_pc === 2)
                                                                Apto
                                                            @endif
                                                        </dd>
                                                    </div>
                                                </dl>

                                            </div>
                                        </div>
                                        <div class="text-right">
                                            {{-- <a data-toggle="modal" class="btn btn-primary btn-danger fa fa-trash"
                                                style="font-size: 20px;" href=""></a> --}}

                                            <a data-toggle="modal" class="btn btn-primary btn-success fa fa-edit"
                                                style="font-size: 20px;" href="#modalComputerUpdate"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modalIncidencias" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="ibox">
                                            <div class="ibox-content product-box">
                                                <a class="product-name text-center">
                                                    <h1><strong>Incidencias</strong></h1>
                                                </a>
                                                <a data-toggle="modal"
                                                    class="btn btn-primary btn-success fa fa-plus-circle float-right"
                                                    onclick="hideModal('modalIncidencias'); showModal('modalIncidenciasStore');"
                                                    style="font-size: 20px;" href="#modalIncidenciasStore"></a>
                                                <div class="col-lg-14">
                                                    <div class="ibox ">
                                                        <div class="ibox-content">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    @if($incidencias->count() > 0)
                                                                    <div class="row mb-2">
                                                                        <div class="col-sm-3 text-sm-left">
                                                                            <dt><strong>FECHA:</strong></dt>
                                                                        </div>
                                                                        <div class="col-sm-7 text-sm-left">
                                                                            <dt><strong>DESCRIPCIÓN:</strong></dt>
                                                                        </div>
                                                                        <div class="col-sm-2 text-sm-left">
                                                                            <dt><strong>OP:</strong></dt>
                                                                        </div>
                                                                    </div>
                                                                    <hr>

                                                                    @foreach($incidencias as $key => $incidencia)
                                                                    <form
                                                                        id="formIncidenciaInactivate-{{$incidencia->id}}"
                                                                        method="POST"
                                                                        action="{{route('computadora.mantenimientoInactivar', [$colaborador->id, $incidencia->id])}}">
                                                                        @method('put')
                                                                        @csrf
                                                                    </form>
                                                                    <div class="row">
                                                                        <div class="col-sm-3 text-sm-left">
                                                                            <dt>{{$incidencia->fecha}}</dt>
                                                                        </div>
                                                                        <div class="col-sm-7 text-sm-left">
                                                                            <dt>{{$incidencia->registro_incidencia}}
                                                                            </dt>
                                                                        </div>
                                                                        <div class="col-sm-2 text-sm-left">
                                                                            <button
                                                                                class="btn btn-primary btn-danger fa fa-trash"
                                                                                style="font-size: 20px;" type="button"
                                                                                onclick="getElementById('formIncidenciaInactivate-{{$incidencia->id}}').submit()"></button>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    @endforeach
                                                                    @else
                                                                    <h2>No hay incidencias registradas</h2>
                                                                    @endif
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
                        </div>
                    </div>
                </div>
                <div id="modalIncidenciasStore" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="ibox">
                                            <div class="ibox-content product-box">
                                                <a class="product-name text-center">
                                                    <h1><strong>Incidencias</strong></h1>
                                                </a>

                                                <div class="col-lg-14">
                                                    <div class="ibox ">
                                                        <div class="ibox-content">
                                                            <div class="row">

                                                                <div class="col-sm-12">
                                                                    <form role="form" method="POST"
                                                                        action="{{route('computadora.mantenimientoStore')}}">
                                                                        @csrf
                                                                        <input type="hidden" name="computadora_id"
                                                                            value="{{$computerColab->id}}" />
                                                                        <input type="hidden" name="colaborador_id"
                                                                            value="{{$colaborador->id}}" />
                                                                        <div class="row">
                                                                            <div class="col-sm-4 text-sm-left">
                                                                                <dt>Fecha:</dt>
                                                                            </div>
                                                                            <div class="col-sm-6 text-sm-left">
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="date" name="fecha"
                                                                                    class="form-control" required />
                                                                            </div>

                                                                        </div>
                                                                        <br>
                                                                        <div class="row">

                                                                            <div class="col-sm-4 text-sm-left">
                                                                                <dt>Descripción:</dt>
                                                                            </div>
                                                                            <div class="col-sm-6 text-sm-left">
                                                                                <input style="padding: 1px 8px;"
                                                                                    name="registro_incidencia"
                                                                                    type="text" class="form-control"
                                                                                    required />
                                                                            </div>

                                                                        </div>
                                                                        <div class="row mt-2">
                                                                            <div class="col-12 flex-centered m-3">
                                                                                <button
                                                                                    class="btn btn-primary btn-success float-right"
                                                                                    style="font-size: 14px;"
                                                                                    type="submit">
                                                                                    Agregar
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="modalComputerUpdate" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="ibox">
                                            <div class="ibox-content product-box">
                                                <a class="product-name">
                                                    <h1>Computadora</h1>
                                                </a>
                                                <div class="col-lg-12">
                                                    <div class="ibox ">
                                                        <div class="ibox-content">
                                                            <form method="POST"
                                                                action="{{route('computadora.updateComputadoraColab', $computerColab->id)}}">
                                                                @method('put')
                                                                @csrf
                                                                <input type="hidden" name="colaborador_id"
                                                                    value="{{$colaborador->id}}" />
                                                                <div class="row">
                                                                    <div class="col-sm-6 b-r">
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Tipo:</dt>
                                                                                <select class="form-control m-b"
                                                                                    name="es_laptop" required>
                                                                                    <option value="0"
                                                                                        @if($computerColab->
                                                                                        es_laptop === 0) selected
                                                                                        @endif> Computadora</option>
                                                                                    <option value="1"
                                                                                        @if($computerColab->
                                                                                        es_laptop === 1) selected
                                                                                        @endif> Laptop</option>

                                                                                </select>
                                                                            </div>
                                                                        </dl>
                                                                        <p class="text-center">
                                                                            <a href=""><i
                                                                                    class="fa fa-desktop big-icon "></i></a>
                                                                        </p>
                                                                        <div class="form-group"><label></label>
                                                                            <input type="checkbox" name="estado"
                                                                                class="js-switch"
                                                                                @if($computerColab->estado)checked
                                                                            @endif
                                                                            />
                                                                            <span
                                                                                style="color: {{$computerColab->estado ? '#0d0' : '#d00'}};">
                                                                                <strong>{{$computerColab->estado ?
                                                                                    'Activo' : 'Inactivo'}}</strong>
                                                                            </span>

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Código de Serie:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="codigo_serie"
                                                                                    value="{{$computerColab->codigo_serie}}"
                                                                                    required></input>
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Procesador:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="procesador"
                                                                                    value="{{$computerColab->procesador}}"
                                                                                    required></input>
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Tarjeta Gráfica:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="tarjeta_grafica"
                                                                                    value="{{$computerColab->tarjeta_grafica}}"
                                                                                    required></input>
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Memoria Gráfica:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="memoria_grafica"
                                                                                    value="{{$computerColab->memoria_grafica}}"
                                                                                    required></input>
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Ram:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    value="{{$computerColab->ram}}"
                                                                                    name="ram" required></input>
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Almacenamiento:</dt>
                                                                                <input style="padding: 1px 8px;"
                                                                                    type="text" class="form-control"
                                                                                    name="almacenamiento"
                                                                                    value="{{$computerColab->almacenamiento}}"
                                                                                    required></input>
                                                                            </div>
                                                                        </dl>
                                                                        <dl class="row mb-0">
                                                                            <div class="col-sm-12 text-sm-left">
                                                                                <dt>Estado de la PC:</dt>
                                                                                <select name="estado_pc" class="form-control" required>
                                                                                    <option value="">Seleccione el estado</option>
                                                                                    <option value="2" @if($computerColab->estado_pc == 2) selected @endif>Apto</option>
                                                                                    <option value="1" @if($computerColab->estado_pc == 1) selected @endif>Medio</option>
                                                                                    <option value="0" @if($computerColab->estado_pc == 0) selected @endif>Mal</option>
                                                                                </select>
                                                                            </div>
                                                                        </dl>


                                                                        <div><br><br>
                                                                            <div>
                                                                                <button
                                                                                    class="btn btn-sm btn-primary float-left m-t-n-xs btn-success"
                                                                                    type="submit"><strong>
                                                                                        Guardar</strong></button>
                                                                                <div>
                                                                                    <a class="btn btn-sm btn-primary float-right m-t-n-xs  btn-success"
                                                                                        href=""><strong>
                                                                                            Descartar</strong></a>
                                                                                </div>
                                                                            </div>


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
                </div>

                <div class="col-md-12">
                    <div class="ibox">
                        <div class="ibox-content product-box">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 flex-centered">
                                    <a class="product-name">
                                        <h1>Programas</h1>
                                    </a>
                                </div>
                                <div class="col-sm-12 col-md-8">
                                    <form method="POST"
                                        action="{{route('computadora.selectProgramas', $computerColab->id)}}"
                                        class="col-12 flex-centered gap-10">
                                        @csrf
                                        <input type="hidden" name="colaborador_id" value="{{$colaborador->id}}" />
                                        <span class="font-bold">Programas Seleccionados: </span>
                                        <select name="programas_id[]" multiple required
                                            class="form-control multiple_programas_select">
                                            @foreach ($programas as $key => $programa)
                                            <option value="{{ $programa->id }}"
                                                @foreach($programasInstalados as $programaInstalado)
                                                @if($programaInstalado->programa_id ===
                                                $programa->id)
                                                selected
                                                @endif
                                                @endforeach
                                                >
                                                {{ $programa->nombre }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-success">Guardar</button>
                                    </form>

                                </div>
                            </div>
                            <div class="row">
                                @foreach($programasInstalados as $key => $programaInstalado)
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                                    <div class="ibox">
                                        <div class="ibox-content product-box">
                                            <div class="row">
                                                <div style="display: flex; flex-direction: column;"
                                                    class="col-sm-12 col-md-6 b-r">
                                                    <h4 class="text-center">
                                                        ID: {{$key+1}}
                                                    </h4>
                                                    <p class="text-center">
                                                        <img src="{{asset('storage/programas/'.$programaInstalado->programa->icono)}}"
                                                            width="100px" height="100px" alt=""></img>
                                                    </p>

                                                    <div class="text-center">
                                                        <button type="button"
                                                            onclick="confirmProgramaInactivate({{$colaborador->id}}, {{$programaInstalado->id}})"
                                                            data-toggle="modal"
                                                            class="btn btn-danger fa fa-trash mb-2 px-3"
                                                            style="font-size: 20px;"></button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6 text-center">
                                                    <dl class="row mb-12">
                                                        <div class="col-sm-12">
                                                            <h3>Programa:</h3>
                                                            <dd class="sm-2">{{$programaInstalado->programa->nombre}}
                                                            </dd>
                                                        </div>
                                                    </dl>
                                                    <dl class="row mb-12">
                                                        <div class="col-sm-12">
                                                            <h3>Descripción:</h3>
                                                            <dd class="sm-2">
                                                                {{$programaInstalado->programa->descripcion}}</dd>
                                                        </div>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @endif


            </div>
        </div>











        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

</body>

<script>
    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');

            // Remover el Backdrop
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.parentNode.removeChild(backdrop);
            }
        }
    }

    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            // modal.style.display = 'block';
        }
    }

    $(document).ready(function() {
            $('.multiple_programas_select').select2();
        });

    function confirmProgramaInactivate(colab_id, id) {
        alertify.confirm("¿Deseas eliminar este registro?", function(e) {
            if (e) {
                let form = document.createElement('form')
                form.method = 'POST'
                form.action = `/computadora/programasInstalados/Inactivate/${colab_id}/${id}`
                form.innerHTML = '@csrf @method('PUT')'
                document.body.appendChild(form)
                form.submit()
            } else {
                return false
            }
        });
    }

</script>

</html>
