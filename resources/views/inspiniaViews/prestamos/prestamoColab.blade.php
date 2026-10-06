<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Préstamos</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/switchery/switchery.css" rel="stylesheet">

    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">



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
                <h2>Préstamos</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('colaboradores.index')}}">Colaborador</a>
                    </li>
                    <li class="breadcrumb-item">
                        <strong>Préstamos</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">
                <div class="ibox-content">
                    <div class="text-center">
                    <a data-toggle="modal"  class="btn btn-success"  href="#modal-form-add" > Agregar <i class="fa fa-long-arrow-right"></i></a>
                    </div>
                    <div id="modal-form-add" class="modal fade" aria-hidden="true">
                        <div class="modal-dialog modal-custom">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-sm-6">
                                            {{-- Envío formulario --}}
                                            <form role="form" action="{{ route('prestamo.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="colaborador_id" value="{{ $colaborador->id }}">

                                                <div class="form-group">
                                                    <label for="fecha_prestamo">Fecha de Préstamo</label>
                                                    <input type="date" placeholder="....." class="form-control" name="fecha_prestamo" id="fecha_prestamo" value="{{ old('fecha_prestamo') }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label for="objeto_id">Objeto</label>
                                                    <select name="objeto_id" id="objeto_id" class="form-control" required>
                                                        <option value="" disabled selected>Seleccione un objeto</option>
                                                        @foreach($objetos as $objeto)
                                                            <option value="{{ $objeto->id }}">{{ $objeto->nombre }}</option>
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
                                    @foreach ($prestamos as $prestamo)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card p-3 shadow-sm">
                                                <h4 class="text-center mb-3">
                                                    ID: {{ $prestamo->id }}
                                                </h4>

                                                @if ($prestamo->estado == 1)
                                                    <form role="form" method="POST" action="{{ route('prestamo.inactive', $prestamo->id) }}">
                                                        @csrf
                                                        @method('put')

                                                        <input type="hidden" name="colaborador_id" value="{{ $prestamo->colaborador_id }}">
                                                        <div class="text-center mb-3"> 
                                                            <label><h4 class="m-t-none m-b-2 font-bold">Herramienta:</h4></label>
                                                            <label class="font-bold">{{ $prestamo->objeto->nombre }}</label>
                                                        </div>

                                                        <div class="text-center mb-3">
                                                            <label><h5 class="m-t-none m-b-2">Fecha de Inscripción:</h5></label>
                                                            <label>{{ $prestamo->fecha_prestamo }}</label>
                                                        </div>

                                                        <div class="text-center mb-3">
                                                            <button type="submit" class="btn btn-primary btn-success" style="font-size: 16px;">
                                                                Devolver
                                                            </button>
                                                        </div>
                                                    </form>

                                                @elseif($prestamo->estado == 0)
                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Herramienta:</h4></label>
                                                        <label class="font-bold">{{ $prestamo->objeto->nombre }}</label>
                                                    </div>

                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Fecha de Inscripción:</h4></label>
                                                        <label class="font-bold">{{ $prestamo->fecha_prestamo }}</label>
                                                    </div>

                                                    <div class="text-center mb-3">
                                                        <label><h4 class="m-t-none m-b-2 font-bold">Fecha de devolución:</h4></label>
                                                        <label class="font-bold">{{ $prestamo->fecha_devolucion }}</label>
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


<!--- Cambiar Imagen-->
<script>

var currentWeek = 1;

        function toggleCheck(cell) {
            if (cell.textContent === '✔️') {
                cell.textContent = '❌';
            } else {
                cell.textContent = '✔️';
            }
        }



    document.getElementById('inputGroupFile').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            };
            reader.readAsDataURL(file);
            document.querySelector('.custom-file-label').innerHTML = file.name;
        }
    });
</script>





<!-- Mainly scripts -->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>
<script src="js/plugins/switchery/switchery.js"></script>


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

        var elem_5 = document.querySelector('.js-switch_5');
        var switchery_5 = new Switchery(elem_5, { color: '#1AB394' });
            switchery_5.disable();
            var elem_6 = document.querySelector('.js-switch_6');
        var switchery_6 = new Switchery(elem_6, { color: '#1AB394' });
            switchery_6.disable();
            var elem_7 = document.querySelector('.js-switch_7');
        var switchery_7 = new Switchery(elem_7, { color: 'red' });
            switchery_7.disable();


        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, { color: '#1AB394' });
















</script>

</body>

</html>
