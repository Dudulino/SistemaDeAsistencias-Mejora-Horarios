<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INSPINIA | SALONES</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Salones</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Salones</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

                <div class="ibox-content">
                    <div class="text-center">
                        <a data-toggle="modal" class="btn btn-primary " href="#modal-form-add"> Agregar </a>
                    </div>
                    <div id="modal-form-add" class="modal fade" aria-hidden="true">
                        <form role="form" method="POST" action="{{ route('salones.store') }}">
                            @csrf
                            <input type="hidden" name="form_type" value="create">
                            <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-sm-12 b-r">
                                                <div class="form-group" style="text-align: center"><label>
                                                        <h3 class="m-t-none m-b">Nombre</h3>
                                                    </label><input type="text" placeholder="Ingresa nombre"
                                                        class="form-control" name="nombre"></div>
                                                        @error('nombre')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror

                                                <div class="form-group" style="text-align: center"><label>
                                                        <h3 class="m-t-none m-b">Descripción</h3>
                                                    </label><input type="text" placeholder="Ingresa Descripcion"
                                                        class="form-control" name="descripcion"></div>
                                                        @error('descripcion')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                <div
                                                    style="display:flex; justify-content: center; align-items: center; gap: 15px">
                                                    <button class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                        type="submit"><i
                                                            class="fa fa-check"></i>&nbsp;Confirmar</button>
                                                </div>

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
        <div class="wrapper wrapper-content animated fadeInRight col-md-12">
            <div class="row">
                @foreach ($salones as $index => $salon)
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="ibox">
                        <div class="ibox-content product-box">

                            <div class="product-imitation">
                                <img src="{{ asset('img/salon.svg') }}" alt="">
                            </div>
                            <div class="product-desc">
                                <span class="product-price" style="background: transparent">
                                    <form method="POST" action="{{ route('salones.activarInactivar', $salon->id) }}">
                                        @csrf
                                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                        <button type="submit"
                                            class="btn btn-{{ $salon->estado ? 'outline-success' : 'danger' }} btn-primary dim btn-xs">
                                            <span>{{ $salon->estado ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </form>
                                </span>

                                <a href="#" class="product-name">
                                    <h2> {{ $salon->nombre }}</h2>
                                </a>



                                <small class="text-muted text-center">
                                    <h3><strong>Código</strong></h3>
                                </small>
                                <div class="small m-t-xs text-center">
                                    <h5>{{ $salon->id }}</h5>
                                </div>
                                <small class="text-muted text-center">
                                    <h3><strong>Descripción</strong></h3>
                                </small>
                                <div class="small m-t-xs text-center">
                                    <h5>{{ $salon->descripcion }}</h5>
                                </div>
                                <small class="text-muted text-center">
                                    <h3><strong>Cantidad De Máquinas</strong></h3>
                                </small>
                                <div class="small m-t-xs text-center">
                                    <h5>{{$salon->cant_maquinas}}</h5>
                                </div>
                                <div class="m-t text-righ">

                                    <a href="#" data-toggle="model"> <i></i> </a>
                                    <div class="ibox-content d-flex justify-content-center">
                                        <div class="text-right d-flex justify-content-center align-items-center" style="gap:2em;">
                                            {{-- <button class="btn btn-primary btn-danger fa fa-trash"
                                                style="font-size: 20px;" type="button"
                                                onclick="confirmDelete({{ $salon->id }})"></button> --}}
                                            <x-uiverse.tooltip nameTool="Editar">
                                                <a data-toggle="modal" class="btn btn-primary fa fa-edit"
                                                    style="font-size: 20px;" href="#modal-form{{ $salon->id }}"></a>
                                            </x-uiverse.tooltip>

                                            <x-uiverse.tooltip nameTool="Ver">
                                                <button type="button" class="btn btn-primary btn-success fa fa-eye"
                                                    data-toggle="modal" data-target="#modal-form-view-{{ $salon->id }}"
                                                    style="font-size: 20px;" data-salon-id="{{ $salon->id }}"></button>
                                            </x-uiverse.tooltip>
                                        </div>

                                        <style>
                                            .modal-custom {
                                                max-width: 80%;

                                            }
                                        </style>
                                        <div class="modal fade" id="modal-form-view-{{ $salon->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-custom">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <!-- se agregara un scroball -->
                                                         <style>
                                                            #modal-form-view-{{ $salon->id }} .modal-body{
                                                                max-height: 80vh;
                                                                overflow-y: auto;
                                                            }
                                                            #modal-form-view-{{ $salon->id }} .modal-body::-webkit-scrollbar {
                                                                width: 8px;
                                                                background: #f1f1f1;
                                                                border-radius: 4px;
                                                            }
                                                         </style>
                                                        <div class="row text-center">
                                                            <div class="product-name col-sm-4">
                                                                <h3>ID: {{ $salon->id }}</h3>
                                                            </div>
                                                            <div class="product-name col-sm-4">
                                                                <h1><strong>{{ $salon->nombre }}</strong></h1>
                                                            </div>
                                                            <div class="product-name col-sm-4">
                                                                <h3>Descripción: {{ $salon->descripcion }}</h3>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div id="" class="row">
                                                            <!-- Aquí se cargarán dinámicamente las máquinas -->
                                                            @foreach($salon->maquinas as $key => $maquina)
                                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                                <div class="ibox">
                                                                    <div class="ibox-content product-box">
                                                                        <div class="row">
                                                                            <div class="col-sm-12 col-md-6 b-r text-center">
                                                                                <div class="d-flex justify-center align-content-center flex-column py-2">
                                                                                    <h2 class="text-center">
                                                                                        {{$maquina->nombre}}
                                                                                    </h2>
                                                                                    <p class="text-center">
                                                                                        <img src="{{asset('img/pc.jpg')}}" class="rounded-circle h-150">
                                                                                    </p>
                                                                                    <form method="POST" action="{{route('maquinas.activarInactivar', $maquina->id)}}">
                                                                                        @csrf
                                                                                        @isset($pageData->currentURL)
                                                                                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                                                                        @endisset
                                                                                        <button class="btn {{$maquina->estado ? 'btn-primary' : 'btn-danger' }} btn-xs" type="submit">
                                                                                            <span>{{$maquina->estado ? 'Activo' : 'Inactivo'}}</span>
                                                                                        </button>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-12 col-md-6 py-3">
                                                                                <div class="text-md-left text-sm-center p-2 mb-2">
                                                                                    <div class="text-md-left text-sm-center">
                                                                                        <dt class="text-md-left text-sm-center">ID:</dt>
                                                                                        <dd class="sm-2 text-md-left text-sm-center">{{$maquina->id}}</dd>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="text-md-left text-sm-center p-2 mb-2">
                                                                                    <div class="text-md-left text-sm-center">
                                                                                        <dt class="text-md-left text-sm-center">Detalles Técnicos:</dt>
                                                                                        <dd class="sm-2 text-md-left text-sm-center">{{$maquina->detalles_tecnicos}}</dd>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="text-md-left text-sm-center p-2 mb-2">
                                                                                    <div class="text-md-left text-sm-center">
                                                                                        <dt class="text-md-left text-sm-center">Número Identificador:</dt>
                                                                                        <dd class="sm-2 text-md-left text-sm-center">{{$maquina->num_identificador}}</dd>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="text-md-left text-sm-center p-2 mb-2">
                                                                                    <div class="text-md-left text-sm-center">
                                                                                        <dt class="text-md-left text-sm-center">Salón Asignado:</dt>
                                                                                        <dd class="sm-2 text-md-left text-sm-center">{{$maquina->nombre}}</dd>
                                                                                    </div>
                                                                                </div>
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
                                        </div>
                                        <div id="modal-form{{ $salon->id }}" class="modal fade"
                                            aria-hidden="true">
                                            <form role="form" method="POST"
                                                action="{{ route('salones.update', $salon->id) }}">
                                                @method('PUT')
                                                @csrf
                                                <input type="hidden" name="form_type" value="edit">
                                                <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                                <input type="hidden" name="salon_id" value="{{ $salon->id }}">

                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-sm-12 b-r">
                                                                    <div class="form-group"><label>
                                                                            <h3 class="m-t-none m-b">Nombre</h3>
                                                                        </label><input type="text" name="nombre"
                                                                            class="form-control"
                                                                            value="{{ $salon->nombre }}">
                                                                            @error('nombre'.$salon->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>

                                                                    <div class="form-group"><label>
                                                                            <h3 class="m-t-none m-b">Descripción</h3>
                                                                        </label><input type="text" name="descripcion"
                                                                            class="form-control"
                                                                            value="{{ $salon->descripcion }}">
                                                                            @error('descripcion'.$salon->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>

                                                                    <div>
                                                                        <button
                                                                            class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                                            type="submit"><i
                                                                                class="fa fa-check"></i>&nbsp;Confirmar</button>

                                                                    </div>

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
                @endforeach
            </div>
            @if($hasPagination === true)
                <div class="row mb-5 mb-md-4">
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-10 my-3">
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== 1)
                            <a href="{{ $salones->url(1) }}" class="btn btn-outline-dark rounded-5">
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







        @if ($errors->any())
            <script>
                // Reabrir el modal de creación si el error proviene del formulario de creación
                console.log(@json($errors->all()));
                @if (old('form_type') == 'create')
                    $('#modal-form-add').modal('show');
                @endif

                // Reabrir el modal de edición si el error proviene del formulario de edición
                @if (old('form_type') == 'edit' && old('salon_id'))
                    $('#modal-form' + {{ old('salon_id') }}).modal('show');
                @endif
            </script>
        @endif




        @include('components.inspinia.footer-inspinia')
    </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salon = document.getElementById('salones');
            if (salon) {
                salon.classList.add('active');
            } else {
                console.error("El elemento con el id 'salones' no se encontró en el DOM.");
            }
        });
    </script>

   


</body>

</html>
