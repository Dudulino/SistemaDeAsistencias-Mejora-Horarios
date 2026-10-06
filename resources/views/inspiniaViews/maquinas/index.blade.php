<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA | MÁQUINAS</title>

    
</head>


</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Máquinas</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Máquinas</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

                <div class="ibox-content">
                    <div class="text-center">
                        <a data-toggle="modal" class="btn btn-primary " href="#modal-form-add"> Agregar </a>
                    </div>
                    <div id="modal-form-add" class="modal fade" aria-hidden="true">
                        <form role="form" method="POST" action="{{ route('maquinas.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                            <input type="hidden" name="form_type" value="create">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-sm-6 b-r">
                                                <div class="form-group">
                                                    <label>
                                                        <h3 class="m-t-none m-b">Máquina</h3>
                                                    </label>
                                                    <input type="text" name="nombre" placeholder="Nombre Máquina..."
                                                        class="form-control"  />
                                                        @error('nombre')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>


                                                <div class="form-group"><label>
                                                        <h3 class="m-t-none m-b">Detalles Técnicos</h3>
                                                    </label><input type="text" name="detalles_tecnicos"
                                                        placeholder="Descripción" class="form-control"></div>
                                                        @error('detalles_tecnicos')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror

                                                <div
                                                    style="display:flex; justify-content: center; align-items: center; gap: 15px">

                                                    <button
                                                        class="btn btn-sm btn-primary float-right m-t-n-xs fa fa-check"
                                                        type="submit"
                                                        style="margin: 5x"><strong>Agregar</strong></button>
                                                </div>

                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group"><label>
                                                        <h3 class="m-t-none m-b">Número Identificador</h3>
                                                    </label>
                                                    <input type="number" name="num_identificador"
                                                        placeholder="Ingrese número" class="form-control">
                                                        @error('num_identificador')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                                <div class="form-group"><label>
                                                        <h3 class="m-t-none m-b">Salón Asignado</h3>
                                                    </label>
                                                    <select class="form-control" name="salon_id" required>
                                                        @foreach ($salones as $salon)
                                                        <option value="{{ $salon->id }}">{{ $salon->nombre }}
                                                        </option>
                                                        @endforeach
                                                    </select>

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
                @foreach ($maquinas as $index => $maquina)
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="ibox">
                        <div class="ibox-content product-box">

                            <div class="product-imitation">
                                <img src="{{ asset('img/pc.jpg') }}" class="rounded-circle" alt="">
                            </div>
                            <div class="product-desc">
                                <span class="product-price" style="background: transparent">
                                    <form method="POST" action="{{ route('maquinas.activarInactivar', $maquina->id) }}">
                                        @csrf
                                        <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                        <button type="submit"
                                            class="btn btn-{{ $maquina->estado ? 'outline-success' : 'danger' }} btn-primary dim btn-xs">
                                            <span>{{ $maquina->estado ? 'Activo' : 'Inactivo' }}</span>
                                        </button>
                                    </form>
                                </span>

                                <a href="#" class="product-name">
                                    <h2> {{ $maquina->nombre }}</h2>
                                </a>



                                <small class="text-muted text-center">
                                    <h3>Detalles Técnicos</h3>
                                </small>
                                <div class="small m-t-xs text-center">
                                    <h5>{{ $maquina->detalles_tecnicos }}</h5>
                                </div>
                                <small class="text-muted text-center">
                                    <h3>Número Identificador</h3>
                                </small>
                                <div class="small m-t-xs text-center">
                                    <h5>{{ $maquina->num_identificador }}</h5>
                                </div>
                                <small class="text-muted text-center">
                                    <h3>Salón Asignado</h3>
                                </small>
                                <div class="small m-t-xs text-center">
                                    <h5>{{ $maquina->salon->nombre }}</h5>
                                </div>
                                <div class="m-t text-righ">

                                    <div class="ibox-content">
                                        <div class="text-right">
                                            <x-uiverse.tooltip nameTool="Editar">
                                                <a data-toggle="modal" class="btn btn-primary fa fa-edit"
                                                    style="font-size: 20px; color: white;"
                                                    href="#modal-form{{ $maquina->id }}"></a>
                                            </x-uiverse.tooltip>
                                        </div>
                                        <div id="modal-form{{ $maquina->id }}" class="modal fade"
                                            aria-hidden="true">
                                            <form role="form" method="POST"
                                                action="{{ route('maquinas.update', $maquina->id) }}">
                                                @method('PUT')
                                                @csrf
                                                <input type="hidden" name="currentURL" value="{{ $pageData->currentURL }}">
                                                <input type="hidden" name="form_type" value="edit">
                                                <input type="hidden" name="maquina_id" value="{{ $maquina->id }}">

                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="row">

                                                                <div class="col-sm-6 b-r">
                                                                    <div class="form-group">
                                                                        <label>
                                                                            <h3 class="m-t-none m-b">Máquina</h3>
                                                                        </label>
                                                                        <input type="text" name="nombre"
                                                                            value="{{ $maquina->nombre }}"
                                                                            class="form-control" />
                                                                            @error('nombre'.$maquina->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>


                                                                    <div class="form-group"><label>
                                                                            <h3 class="m-t-none m-b">Detalles Técnicos
                                                                            </h3>
                                                                        </label><input type="text"
                                                                            name="detalles_tecnicos"
                                                                            value="{{ $maquina->detalles_tecnicos }}"
                                                                            class="form-control"></div>
                                                                            @error('detalles_tecnicos'.$maquina->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    <div
                                                                        style="display:flex; justify-content: center; align-items: center; gap: 15px">
                                                                        <button
                                                                            class="btn btn-sm btn-primary float-right m-t-n-xs fa fa-check"
                                                                            type="submit"
                                                                            style="margin: 5x"><strong>Aceptar</strong></button>
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group"><label>
                                                                            <h3 class="m-t-none m-b">Número
                                                                                Identificador</h3>
                                                                        </label>
                                                                        <input type="number" name="num_identificador"
                                                                            value="{{ $maquina->num_identificador }}"
                                                                            class="form-control">
                                                                            @error('num_identificador'.$maquina->id)
                                                                                <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                    </div>
                                                                    <div class="form-group"><label>
                                                                            <h3 class="m-t-none m-b">Salón Asignado
                                                                            </h3>
                                                                        </label>
                                                                        <select class="form-control" name="salon_id">
                                                                            @foreach ($salones as $salon)
                                                                            <option @if($salon->id === $maquina->salon_id) selected @endif value="{{ $salon->id }}">{{ $salon->nombre }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                                
                                                                </style>
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
                            <a href="{{ $maquinas->url(1) }}" class="btn btn-outline-dark rounded-5">
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
                @if (old('form_type') == 'edit' && old('maquina_id'))
                    $('#modal-form' + {{ old('maquina_id') }}).modal('show');
                @endif
            </script>
        @endif

        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maquina = document.getElementById('maquinas');
            if (maquina) {
                maquina.classList.add('active');
            } else {
                console.error("El elemento con el id 'maquinas' no se encontró en el DOM.");
            }
        });
    </script>

    <script>
        function confirmDelete(id) {
            alertify.confirm("¿Deseas eliminar este registro?", function(e) {
                if (e) {
                    let form = document.createElement('form')
                    form.method = 'POST'
                    form.action = `/maquinas/${id}`
                    form.innerHTML = '@csrf @method('DELETE')'
                    document.body.appendChild(form)
                    form.submit()
                } else {
                    return false
                }
            });
        }
    </script>

    <style>
        .modal form {
        pointer-events: none;
        }

        .modal .modal-dialog,
        .modal .modal-content,
        .modal .form-control,
        .modal button {
        pointer-events: all;
        }
    </style>

</body>

</html>
