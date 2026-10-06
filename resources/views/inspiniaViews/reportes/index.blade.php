<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>REPORTES</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Reportes</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Reportes</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-content">
                            <div class="table-responsive table-scroll">
                                
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <th colspan="1">ID</th>
                                            <th colspan="1">Apellidos</th>
                                            <th colspan="1">Nombres</th>
                                            <th colspan="1">Áreas</th>
                                            <th colspan="1">Carrera</th>
                                            <th colspan="1">Instituto - Sede</th>
                                            <th colspan="1">Celular</th>
                                            <th colspan="1">Email</th>
                                            <th colspan="1">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($colaboradores as $colaborador)
                                            <tr>
                                                <th colspan="1" class="text-center">{{ $loop->index + 1 }}</th>
                                                <th colspan="1">{{$colaborador->candidato->apellido}}</th>
                                                <th colspan="1">{{$colaborador->candidato->nombre}}</th>
                                                <th colspan="1">@foreach($colaborador->areas as $index => $area)@if($area['tipo'] == 0) @if($index > 0) - @endif {{$area['nombre']}}@endif @endforeach</th>
                                                <th colspan="1">{{$colaborador->candidato->carrera->nombre}}</th>
                                                <th colspan="1">{{$colaborador->candidato->sede->nombre}}</th>
                                                <th colspan="1">{{$colaborador->candidato->celular}}</th>
                                                <th colspan="1">{{$colaborador->candidato->correo}}</th>
                                                <th colspan="1">@if($colaborador->estado === 1)
                                                    Activado
                                                    @elseif($colaborador->estado === 0)
                                                    Inactivo
                                                @endif</th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .table-scroll {
                overflow-x: auto;
                white-space: nowrap;
            }

            .table {
                width: 100%;
            }
        </style>






        @include('components.inspinia.footer-inspinia')
        </div>
    </div>
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

    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy', exportOptions: { columns: ':not(.oculto)' }},
                    { extend: 'csv', exportOptions: { columns: ':not(.oculto)' }},
                    { extend: 'excel', title: 'COLABORADORES', exportOptions: { columns: ':not(.oculto)' }},
                    { extend: 'pdf',
                    title: 'COLABORADORES',
                    exportOptions: { columns: ':not(.oculto)' },
                    customize: function(doc) {
                        doc.pageOrientation = 'landscape';
                        // tamaño fuente
                        doc.defaultStyle.fontSize = 8;

                        // Ajustar el ancho de las columnas para ocupar todo el espacio disponible
                        var columnCount = doc.content[1].table.body[0].length;
                        var columnWidths = [];
                        if (columnCount <= 9) {
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
                        doc.pageMargins = [20, 20, 20, 20];

                        doc.content[1].table.body.forEach(function(row) {
                            row.forEach(function(cell) {
                                if (cell.text) {
                                    // Reducir el margen de las celdas
                                    cell.margin = [0, 2, 0, 2]; // Margen: [izquierda, arriba, derecha, abajo]

                                    // Establecer la altura de las celdas para que solo ocupe lo necesario
                                    cell.alignment = 'left'; // Alinear el texto a la izquierda
                                }
                            });
                        });
                        // doc.content[1].table.body.forEach(function(row) {
                        //     row.forEach(function(cell) {
                        //         cell.style = {
                        //             fontSize: 8, // Reducir el tamaño de la fuente de las celdas
                        //             margin: [0, 2, 0, 2] // Reducir los márgenes
                        //         };
                        //     });
                        // });
                    }},
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
