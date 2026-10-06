<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA| Sedes</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Proyectos</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Proyectos</strong>
                    </li>
                </ol>
            </div>

            {{-- Modal store --}}
            <div class="col-lg-2">
                <button class="btn btn-success dim float-right" href="#modal-form-add" data-toggle="modal"
                    type="button">Agregar</button>
                <div id="modal-form-add" class="modal fade" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row" style="display: flex; justify-content:center; align-items:center">
                                    <div class="col-sm-11">
                                        <h3 class="m-t-none m-b">Ingrese los Datos</h3>
                                        <form role="form" method="POST" action="{{ route('proyectos.store') }}">
                                            @csrf
                                            @method('POST')
                                            <div class="form-group">
                                                <label>Nombre: </label>
                                                <input type="text" placeholder="Ingrese un nombre" name="nombre" autocomplete="off"
                                                    class="form-control">
                                                @error('nombre')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                           <div class="form-group">
                                                <label>Descripción: </label>
                                                <textarea placeholder="Ingrese una descripción" name="descripcion" autocomplete="off"
                                                    class="form-control"></textarea>
                                                @error('descripcion')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha Inicio: </label>
                                                <input type="date" name="fecha_inicio" autocomplete="off"
                                                    class="form-control">
                                                @error('fecha_inicio')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Fecha Fin: </label>
                                                <input type="date" name="fecha_fin" autocomplete="off"
                                                    class="form-control">
                                                @error('fecha_fin')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
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
                                                    oninput="document.getElementById('progressBar').style.width = this.value + '%';
                                                            document.getElementById('progressBar').textContent = this.value + '%';">

                                                <div class="progress mt-2">
                                                    <div id="progressBar"
                                                        class="progress-bar bg-info"
                                                        style="width: 0%; color: black;">
                                                        0%
                                                    </div>
                                                </div>

                                                @error('porcentaje')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <button class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                    type="submit"><i class="fa fa-check"></i>&nbsp;Confirmar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--Término Modal--}}

        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Tabla Proyectos</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#" class="dropdown-item">Config option 1</a>
                            </li>
                            <li><a href="#" class="dropdown-item">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th class="col-lg-1">#</th>
                                <th class="col-lg-2">Proyecto</th>
                                <th class="col-lg-3">Descripción</th>
                                <th class="col-lg-2">Fecha Inicio</th>
                                <th class="col-lg-2">Fecha Fin</th>
                                <th class="col-lg-4">Porcentaje</th>
                                <th class="col-lg-1">Estado</th>
                                <th class="col-lg-1">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proyectos as $proyecto)
                                <tr>
                                    <td class="text-center">{{ $proyecto->id }}</td>
                                    <td>{{ $proyecto->nombre }}</td>
                                    <td>{{ $proyecto->descripcion }}</td>
                                    <td>{{ $proyecto->fecha_inicio ?? '' }}</td>
                                    <td>{{ $proyecto->fecha_fin ?? '' }}</td>
                                    <td>{{ $proyecto->porcentaje }}%</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('proyectos.changeState', $proyecto->id) }}">
                                            @csrf
                                            @method('PATCH')
                                                <button type="submit"
                                                    class="btn btn-{{ $proyecto->estado ? 'outline-success' : 'danger' }} btn-primary dim">
                                                    <span>{{ $proyecto->estado ? 'Activado' : 'Inactivo'}}</span>
                                                </button>
                                        </form>
                                    </td>

                                    <td class="d-flex justify-content-center align-items-center g-2 text-center oculto">
                                        <button
                                            class="btn btn-info" type="button"
                                            href="#modal-form{{ $proyecto->id }}"
                                            data-toggle="modal"><i
                                            class="fa fa-paste"></i>
                                        </button>
                                        <button
                                            class="btn btn-primary" type="button"
                                            href="#modal-view{{ $proyecto->id }}"
                                            data-toggle="modal"><i class="fa fa-eye"></i> 
                                            <style>
                                                .btn-primary {
                                                    margin: 5px;
                                                }
                                            </style>
                                        </button>
                                    </td>
                                    {{-- modal actualizar --}}
                                    <div id="modal-form{{ $proyecto->id }}" class="modal fade" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="row"
                                                        style="display: flex; justify-content:center; align-items:center">
                                                        <div class="col-sm-11 b-r">
                                                            <h3 class="m-t-none m-b">Editar</h3>
                                                            <form role="form" method="POST"
                                                                action="{{ route('proyectos.actualizar', $proyecto->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-group">
                                                                    <label>Nombre: </label>
                                                                    <input type="text" placeholder="Ingrese un nombre" name="nombre" autocomplete="off"
                                                                        class="form-control" value="{{ $proyecto->nombre ?? '' }}">
                                                                    @error('nombre')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Descripción: </label>
                                                                    <textarea placeholder="Ingrese una descripción" name="descripcion" autocomplete="off"
                                                                        class="form-control">{{ $proyecto->descripcion ?? '' }}</textarea>
                                                                    @error('descripcion')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Fecha Inicio: </label>
                                                                    <input type="date" name="fecha_inicio" autocomplete="off"
                                                                        class="form-control" value="{{ $proyecto->fecha_inicio ?? '' }}">
                                                                    @error('fecha_inicio')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Fecha Fin: </label>
                                                                    <input type="date" name="fecha_fin" autocomplete="off" value="{{ $proyecto->fecha_fin ?? '' }}"
                                                                        class="form-control">
                                                                    @error('fecha_fin')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
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
                                                                        oninput="document.getElementById('progressBar{{ $proyecto->id }}').style.width = this.value + '%';
                                                                                document.getElementById('progressBar{{ $proyecto->id }}').textContent = this.value + '%';">
                                                                    <div class="progress mt-2">
                                                                        <div id="progressBar{{ $proyecto->id }}"
                                                                            class="progress-bar bg-info"
                                                                            style="width: {{ $proyecto->porcentaje }}%;">                                                                            
                                                                            {{ $proyecto->porcentaje }}%
                                                                        </div>
                                                                    </div>
                                                                    @error('porcentaje')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div>
                                                                    <button
                                                                        class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                                        type="submit"><i
                                                                            class="fa fa-check"></i>&nbsp;Confirmar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Modal ver los datos del proyecto --}}
                                    <div class="modal "
                                        id="modal-view{{ $proyecto->id }}"
                                        tabindex="-1"
                                        aria-labelledby="modal-form{{ $proyecto->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modal-form{{ $proyecto->id }}">
                                                        Nombre: {{ $proyecto->nombre }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Descripción: {{ $proyecto->descripcion }}</p>
                                                    <p>Fecha Inicio: {{ $proyecto->fecha_inicio }}</p>      
                                                    <p>Fecha Fin: {{ $proyecto->fecha_fin }}</p>
                                                    <p>Porcentaje: {{ $proyecto->porcentaje }}%</p>
                                                    <p>Estado: {{ $proyecto->estado ?  'Activo' : 'Inactivo'}} </p>

                                                </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                @if (old('form_type') == 'edit' && old('sede_id'))
                    $('#modal-form' + {{ old('sede_id') }}).modal('show');
                @endif
            </script>
        @endif

        @include('components.inspinia.footer-inspinia')

    </div>
    </div>


        <!-- Mainly scripts -->
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.js"></script>
        <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
        <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

        <script src="js/plugins/dataTables/datatables.min.js"></script>
        <script src="js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

        <!-- Custom and plugin javascript -->
        <script src="js/inspinia.js"></script>
        <script src="js/plugins/pace/pace.min.js"></script>

        <!-- Page-Level Scripts -->
        <script>
            $(document).ready(function(){
                $('.dataTables-example').DataTable({
                    pageLength: 10,
                    responsive: true,
                    dom: '<"html5buttons"B>lTfgitp',
                    buttons: [
                        { extend: 'copy', exportOptions: { columns: ':not(.oculto)' }},
                        { extend: 'csv', exportOptions: { columns: ':not(.oculto)' }},
                        { extend: 'excel', title: 'SEDES', exportOptions: { columns: ':not(.oculto)' }},
                        { extend: 'pdf',
                        title: 'SEDES',
                        exportOptions: { columns: ':not(.oculto)' },
                        customize: function(doc) {
                            // tamaño fuente
                            doc.defaultStyle.fontSize = 10;

                            // Ajustar el ancho de las columnas para ocupar todo el espacio disponible
                            var columnCount = doc.content[1].table.body[0].length;
                            var columnWidths = [];
                            if (columnCount <= 4) {
                                columnWidths = Array(columnCount).fill('*');
                            } else {
                                columnWidths = Array(columnCount).fill('auto');
                            }
                            doc.content[1].table.widths = columnWidths;

                            // Estilo de la cabecera
                            doc.styles.tableHeader = {
                                fillColor: '#4682B4',
                                color: 'white',
                                alignment: 'center',
                                bold: true,
                                fontSize: 12
                            };

                            // Ajustar los márgenes de la página
                            doc.pageMargins = [20, 20, 20, 20]; }},
                        { extend: 'print',
                          customize: function (win){
                                $(win.document.body).addClass('white-bg');
                                $(win.document.body).css('font-size', '10px');
                                $(win.document.body).find('table')
                                    .addClass('compact');
                                $(win.document.body).find('thead th.oculto').css('display', 'none');
                                $(win.document.body).find('tbody td.oculto').css('display', 'none');
                          },
                          exportOptions: { columns: ':not(.oculto)' }
                        }
                    ],
                    language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                }
                });
            });
        </script>

</body>

</html>
