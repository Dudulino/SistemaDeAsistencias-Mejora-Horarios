<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA| Libros</title>
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
                <h2>Gestión Libros</h2>

                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>

                    <li class="breadcrumb-item active">
                        <strong>Libro</strong>
                    </li>
                </ol>
            </div>

            </div>
            {{-- TÉRMINO MODAL--}}



        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="ibox ">
                <div class="ibox-title">
                    <h3>Cantidad de libros: <strong>{{ $cantidadLib }}</strong></h3>
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
                                <th class="col-lg-1">ID</th>
                                <th class="col-lg-2">Titulo</th>
                                <th class="col-lg-2">Autor</th>
                                <th class="col-lg-1">Estado</th>
                                <th class="col-lg-1 oculto">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                           {{-- ARRAY OBJETOS --}}
                            @foreach ($librosDisponibles as $libro)
                            <tr>
                                <td>{{ $libro->id }}</td>
                                <td>{{ $libro->titulo }}</td>
                                <td>{{ $libro->autor }}</td>
                                <td>

                                        <button type="button" class="btn btn-{{ $libro->estado ? 'outline-success' : 'danger' }} btn-primary dim">
                                            <span>{{ $libro->estado ? 'Disponible' : 'No Disponible' }}</span>
                                        </button>
                                </td>
                                <td class="oculto">
                             

                                    <button class="btn btn-secondary" type="button" href="#modal-form-show{{ $libro->id }}" data-toggle="modal">
                                        <i
                                            class="fa fa-eye">
                                        </i>
                                    </button>
                                </td>
                                {{-- show libro --}}
                                <div id="modal-form-show{{ $libro->id }}" class="modal fade" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="row" style="display: flex; justify-content:center; align-items:center">
                                                    <div class="col-sm-11 b-r">
                                                        <h3 class="m-t-none m-b">Libro</h3>
                                                            <div class="form-group"><label>Título</label>
                                                                <input type="text"
                                                                    class="form-control" id="titulo"
                                                                    value="{{ $libro->titulo }}" readonly>
                                                            </div>

                                                            <div class="form-group"><label>Autor</label>
                                                                <input type="text"
                                                                    class="form-control" id="autor"
                                                                    value="{{ $libro->autor }}" readonly>
                                                            </div>

                                                            <div class="form-group">
                                                                @if ($libro->colaborador_actual)
                                                                    <div><label>Prestado a <strong>{{ $libro->colaborador_actual->candidato->nombre . " " . $libro->colaborador_actual->candidato->apellido }}</strong></label></div>
                                                                    @if ($libro->ultimoPrestamo)
                                                                       <div> <label>Fecha préstamo: <strong>{{ $libro->ultimoPrestamo->fecha_prestamo }}</strong></label></div>
                                                                    @else
                                                                        <div><label><strong>Sin fecha de préstamo</strong></label></div>
                                                                    @endif
                                                                @else
                                                                    Disponible
                                                                @endif
                                                            </div>
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
            @include('components.inspinia.footer-inspinia')
        </div>

        </div>

    </div>


    <script>
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
                        { extend: 'excel', title: 'Libros', exportOptions: { columns: ':not(.oculto)' }},
                        { extend: 'pdf',
                        title: 'Libros',
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
