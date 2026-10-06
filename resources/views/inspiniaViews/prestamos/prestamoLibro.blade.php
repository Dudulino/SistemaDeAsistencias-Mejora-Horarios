<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>INSPINIA | Préstamos</title>

</head>

<body>

<div id="wrapper">
    @include('components.inspinia.side_nav_bar-inspinia')

    <!-- <div id="page-wrapper" class="gray-bg">
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                </div>
            </form> -->

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Librería</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('colaboradores.index')}}">Colaborador</a>
                    </li>
                    <li class="breadcrumb-item">
                        <strong>Préstamos de libros</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
                <div class="ibox-content">
                    <div class="text-center">
                    <a data-toggle="modal"  class="btn btn-success"  href="#modal-form-add" > Agregar <i class="fa fa-long-arrow-right"></i></a>
                    </div>


                    <style>
                        .modal {
                            z-index: 1050;
                        }

                        .select2-container {
                            z-index: 9999 !important; /
                        }

                        .customModal {
                            width: 100% !important;
                        }
                    </style>
                    {{-- modalStore prestamo --}}
                    <div id="modal-form-add" class="modal fade" aria-hidden="true">
                        <div class="modal-dialog modal-custom modal-lg">
                            <div class="modal-content">
                                <div class="modal-body customModal">
                                    <div class="row d-flex justify-content-center customModal">
                                        <div class="col-sm-6 customModal">
                                            {{-- Envío formulario --}}
                                            <form role="form" action="{{ route('libroPrestamo.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="colaborador_id" value="{{ $colaborador->id }}">

                                                <div class="form-group">
                                                    <label for="fecha_prestamo">Fecha de Préstamo</label>
                                                    <input type="date" placeholder="....." class="form-control" name="fecha_prestamo" id="fecha_prestamo" value="{{ \Carbon\Carbon::now()->toDateString() }}" readonly>
                                                </div>

                                                {{-- <div class="form-group">
                                                    <label for="libro_id">Libro</label>
                                                    <select name="libro_id select2-libro" id="libro_id" class="form-control" required>
                                                        <option value="" selected>Seleccione un libro</option>
                                                        @foreach($libros as $libro)
                                                            <option value="{{ $libro->id }}">{{ $libro->titulo }} - {{ $libro->autor }}</option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="form-group">
                                                    <div>
                                                        <label>Libros</label>
                                                    </div>
                                                    <select class="form-control select2-libro" name="libro_id" id="libro_id">
                                                        <option value="">Seleccione un libro</option>
                                                        @foreach($libros as $libro)
                                                            <option value="{{ $libro->id }}">
                                                                {{ $libro->titulo }} - {{ $libro->autor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-12 text-center">
                                                    <button class="btn btn-success btn-sm m-t-n-xs" type="submit"><i class="fa fa-check" ></i>&nbsp;Agregar</button>
                                                </div>
                                            </form>

                                        </div><br>

                                    </div>

                            </div>
                            </div>
                        </div>
                </div>
                </div>

            </div>
        </div>


        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-md-12">
                    <div class="ibox">
                        <div class="ibox-content product-box">

                                    <div class="col-lg-12">
                                        <div class="ibox ">
                                            <div class="ibox-content">
                                                <div class="row">
                                                    <div class="col-sm-3 b-r">

                                                        <p class="text-center">
                                                            <a href=""><i class="fa fa-user big-icon "></i></a>
                                                        </p>
                                                    </div>
                                                {{-- datos del colaborador correspondiente --}}
                                                    <div class="col-sm-4">
                                                        <form role="form">
                                                            <dl class="row mb-3">
                                                                <div class="col-sm-12 text-sm-left row">
                                                                    <dt class="col-sm-6">Colaborador:</dt>
                                                                    <dd class="col-sm-6 sm-2">{{$colaborador->candidato->nombre}}</dd>
                                                                </div>
                                                            </dl>
                                                            <dl class="row mb-3">
                                                                <div class="col-sm-12 text-sm-left row">
                                                                    <dt class="col-sm-6">Área:</dt>
                                                                    <dd class="col-sm-6" sm-2>
                                                                        @forelse ($areas as $area)
                                                                            <div>{{ $area->especializacion }}</div>
                                                                        @empty
                                                                            No asignado
                                                                        @endforelse
                                                                    </dd>
                                                                </div>
                                                            </dl>
                                                            <dl class="row mb-3">
                                                                <div class="col-sm-12 text-sm-left row">
                                                                    <dt class="col-sm-6">DNI:</dt>
                                                                    <dd class="col-sm-6 sm-2">{{$colaborador->candidato->dni}}</dd>
                                                                </div>
                                                            </dl>

                                                            <div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form role="form">
                                                            <dl class="row mb-3">
                                                                <div class="col-sm-12 text-sm-left row">
                                                                    <dt class="col-sm-6">Salón:</dt>
                                                                    <dd class="col-sm-6 sm-2">{{ $area->salon->id }}</dd>
                                                                </div>
                                                            </dl>
                                                            <dl class="row mb-3">
                                                                <div class="col-sm-12 text-sm-left row">
                                                                    <dt class="col-sm-6">Correo:</dt>
                                                                    <dd class="col-sm-6 sm-2">{{$colaborador->candidato->correo}}</dd>
                                                                </div>
                                                            </dl>
                                                            <dl class="row mb-3">
                                                                <div class="col-sm-12 text-sm-left row">
                                                                    <dt class="col-sm-6">Celular:</dt>
                                                                    <dd class="col-sm-6 sm-2">{{$colaborador->candidato->celular}}</dd>
                                                                </div>
                                                            </dl>

                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    </div>
                </div>
                </div>
                {{-- vista de prestamos al colaborador correspondiente --}}
                <div class="col-md-12">
                    <div class="ibox">
                        <div class="ibox-content product-box">
                            <div class="modal-body">
                                <div class="row">
                                    @foreach ($prestamoLibros as $libroColab)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card p-3 shadow-sm">
                                                <h4 class="text-center mb-3">
                                                    ID: {{ $libroColab->id }}
                                                </h4>

                                                @if ($libroColab->devuelto == 0)
                                                    <form role="form" method="POST" action="{{ route('libroPrestamo.devolver', $libroColab->id) }}">
                                                        @csrf
                                                        @method('put')

                                                        <input type="hidden" name="colaborador_id" value="{{ $libroColab->colaborador_id }}">

                                                        <div class="text-center mb-3">
                                                            <label><h4 class="m-t-none m-b-2 font-bold">Libro:</h4></label>
                                                            <label class="font-bold">{{ $libroColab->libro->titulo }}</label>
                                                        </div>

                                                        <div class="text-center mb-3">
                                                            <label><h4 class="m-t-none m-b-2 font-bold">Autor:</h4></label>
                                                            <label class="font-bold">{{ $libroColab->libro->autor }}</label>
                                                        </div>

                                                        <div class="text-center mb-3">
                                                            <label><h5 class="m-t-none m-b-2">Fecha de Préstamo:</h5></label>
                                                            <label>{{ $libroColab->fecha_prestamo }}</label>
                                                        </div>

                                                        <div class="text-center mb-3">
                                                            <button type="submit" class="btn btn-primary btn-success" style="font-size: 16px;">
                                                                Devolver
                                                            </button>
                                                        </div>
                                                    </form>

                                                @elseif($libroColab->devuelto == 1)
                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Libro:</h4></label>
                                                        <label class="font-bold">{{ $libroColab->libro->titulo }}</label>
                                                    </div>

                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Autor:</h4></label>
                                                        <label class="font-bold">{{ $libroColab->libro->autor }}</label>
                                                    </div>

                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Fecha de Préstamo:</h4></label>
                                                        <label class="font-bold">{{ $libroColab->fecha_prestamo }}</label>
                                                    </div>

                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Fecha de devolución:</h4></label>
                                                        <label class="font-bold">{{ $libroColab->fecha_devolucion }}</label>
                                                    </div>

                                                    <div class="text-center">
                                                        <label class="font-bold text-success">Devuelto</label>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        $('.select2-libro').select2({
            placeholder: "Buscar libro...",
            allowClear: true,
            width: '100%'
        });
    });
</script>

<script>

function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('show');
    modal.style.display = 'none';
  }
}

function showModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('show');
  }
}


</script>

</body>

</html>
