<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA| Carreras</title>
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
                <h2>Gestión Carreras</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('ajustes.index')}}">Ajustes</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Carrera</strong>
                    </li>
                </ol>
            </div>
            {{-- INICIO MODAL --}}
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

                                        <!--
                                                                <p>Sign in today for more expirience.</p>
                                                            -->

                                        <form role="form" method="POST" action="{{ route('carreras.store') }}">
                                            @csrf
                                            <input type="hidden" name="form_type" value="create">
                                            <div class="form-group"><label>Carrera</label> <input type="text"
                                                    placeholder="Ingrese un nombre" name="nombre" class="form-control">
                                            </div>
                                            @error('nombre')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
            {{-- TÉRMINO MODAL --}}

        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>Tabla</h5>
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
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th class="col-lg-1">ID</th>
                                    <th class="col-lg-5">Carrera</th>
                                    <th class="col-lg-1">Estado</th>
                                    <th class="col-lg-1 oculto">Editar</th>
                                    {{-- <th class="col-lg-1">Borrar</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carreras as $carrera)
                                <tr>
                                    <td>{{ $carrera->id }}</td>
                                    <td>{{ $carrera->nombre }}</td>
                                    <td><form method="POST" action="{{ route('carreras.activarInactivar', $carrera->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-{{ $carrera->estado ? 'outline-success' : 'danger' }} btn-primary dim">
                                            <span>{{ $carrera->estado ? 'Activado' : 'Inactivo' }}</span>
                                        </button>
                                    </form></td>
                                    <td><button class="btn btn-info oculto" type="button" href="#modal-form{{ $carrera->id }}" data-toggle="modal"><i
                                                class="fa fa-paste"></i></button></td>
                                    <div id="modal-form{{ $carrera->id }}" class="modal fade" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="row" style="display: flex; justify-content:center; align-items:center">
                                                        <div class="col-sm-11 b-r">
                                                            <h3 class="m-t-none m-b">Editar</h3>

                                                            <!--
                                                                <p>Sign in today for more expirience.</p>
                                                            -->

                                                            <form role="form" method="POST"
                                                                action="{{ route('carreras.update', $carrera->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="form_type" value="edit">
                                                                <input type="hidden" name="carrera_id" value="{{ $carrera->id }}">
                                                                
                                                                <label class="col-form-label">Carrera</label>
                                                                <div class="form-group"><label>Nombre</label>
                                                                    <input type="text" placeholder="....."
                                                                        class="form-control" name="nombre" id="nombre"
                                                                        value="{{ $carrera->nombre }}"/>
                                                                </div>
                                                                @error('nombre'.$carrera->id)
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
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
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
            @if (old('form_type') == 'edit' && old('carrera_id'))
                $('#modal-form' + {{ old('carrera_id') }}).modal('show');
            @endif
        </script>
    @endif
        @include('components.inspinia.footer-inspinia')

    </div>
    </div>

    <script>
        
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
                        { extend: 'excel', title: 'CARRERAS', exportOptions: { columns: ':not(.oculto)' }},
                        { extend: 'pdf',
                        title: 'CARRERAS',
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

    </script>

</body>

</html>
