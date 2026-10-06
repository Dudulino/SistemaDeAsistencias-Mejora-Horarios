<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA| Programas</title>
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
                <h2>Gestión Programas</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('ajustes.index')}}">Ajustes</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Programas</strong>
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
                                <form role="form" method="POST" action="{{ route('programas.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="form_type" value="create">
                                    <div class="row">
                                        <div class="col-sm-6 b-r">
                                            <h3 class="m-t-none m-b">Ingrese los Datos</h3>
                                            <div class="form-group"><label>Programa</label> <input type="text"
                                                    placeholder="....." class="form-control" name="nombre">
                                                    @error('nombre')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>
                                            <div class="form-group"><label>Descripción</label> <input type="text"
                                                    placeholder="....." class="form-control" name="descripcion">
                                                    @error('descripcion')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <h4>Subir Ícono</h4>
                                            <input type="file" class="form-control-file" id="icono" name="icono"
                                                style="display: none;">
                                                @error('icono')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            <!-- Icono que simula el clic en el botón de subir archivos -->
                                            <button type="button" class="btn btn-link" id="icon-upload">
                                                <i class="fa fa-cloud-download big-icon"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <a href="areas"
                                                class="btn btn-white btn-sm m-t-n-xs float-left">Cancelar</a>
                                            <button class="btn btn-primary btn-sm m-t-n-xs float-right" type="submit"><i
                                                    class="fa fa-check"></i>&nbsp;Confirmar</button>
                                        </div>
                                    </div>
                                </form>
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
                    <table class="table table-striped table-bordered table-hover dataTables-example">
                        <thead>
                            <tr>
                                <th class="col-lg-1">ID</th>
                                <th class="col-lg-4">Programa</th>
                                <th class="col-lg-5">Descripción</th>
                                <th class="col-lg-1 child-center">Estado</th>
                                <th class="col-lg-1 child-center oculto">Imagen</th>
                                <th class="col-lg-1 child-center oculto">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programas as $programa)
                            <tr>
                                <td>{{ $programa->id }}</td>
                                <td>{{ $programa->nombre }}</td>
                                <td>{{ $programa->descripcion }}</td>
                                <td class="child-center">
                                    <form method="POST"
                                        action="{{ route('programas.activarInactivar', $programa->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $programa->estado ? 'outline-success' : 'danger' }} btn-primary dim">
                                            <span>{{ $programa->estado ? 'Activado' : 'Inactivo' }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td class="child-center oculto"><img src="{{ asset('storage/programas/' . $programa->icono) }}"
                                        style="height: 50px; width: 50px; border-radius: 10px" class="img-cover" alt="">
                                </td>
                                <td class="child-center oculto"><button class="btn btn-info" type="button"
                                        href="#modal-form{{ $programa->id }}" data-toggle="modal"><i
                                            class="fa fa-paste"></i></button></td>
                                <div id="modal-form{{ $programa->id }}" class="modal fade" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <form role="form" method="POST"
                                                    action="{{ route('programas.update', $programa->id) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="form_type" value="edit">
                                                    <input type="hidden" name="programa_id" value="{{ $programa->id }}">
                                                    <div class="row">
                                                        <div class="col-sm-6 b-r">
                                                            <h3 class="m-t-none m-b">Editar</h3>
                                                            <label class="col-form-label">Programas</label>
                                                            <div class="form-group"><label>Nombre</label>
                                                                <input type="text" placeholder="....."
                                                                    class="form-control" name="nombre" id="nombre"
                                                                    value="{{ $programa->nombre }}">
                                                                    @error('nombre'.$programa->id)
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                            </div>
                                                            <div class="form-group"><label>Descripción</label>
                                                                <input type="text" placeholder="....."
                                                                    class="form-control" name="descripcion"
                                                                    id="descripcion"
                                                                    value="{{ $programa->descripcion }}">
                                                                    @error('descripcion'.$programa->id)
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                            </div>

                                                        </div>
                                                        <div class="col-sm-6 b-r">
                                                            <h4>Subir Ícono</h4>
                                                            <input type="file" class="form-control-file"
                                                                id="icono-{{ $programa->id }}" name="icono"
                                                                value="{{ $programa->icono }}"
                                                                style="display: none;">
                                                                @error('icono'.$programa->id)
                                                                    <span class="text-danger">{{ $message }}</span>
                                                                @enderror
                                                            <button type="button" class="btn btn-link"
                                                                id="icon-upload-{{ $programa->id }}">
                                                                <i class="fa fa-cloud-download big-icon"></i>
                                                            </button>
                                                            <script>
                                                                document.getElementById('icon-upload-{{ $programa->id }}').addEventListener('click', function() {
                                                                        document.getElementById('icono-{{ $programa->id }}').click();
                                                                    });
                                                            </script>
                                                            <button class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                                type="submit"><i
                                                                    class="fa fa-check"></i>&nbsp;Confirmar</button>
                                                        </div>
                                                    </div>
                                                </form>

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
            @if ($errors->any())
            <script>
                // Reabrir el modal de creación si el error proviene del formulario de creación
                console.log(@json($errors->all()));
                @if (old('form_type') == 'create')
                    $('#modal-form-add').modal('show');
                @endif
        
                // Reabrir el modal de edición si el error proviene del formulario de edición
                @if (old('form_type') == 'edit' && old('programa_id'))
                    $('#modal-form' + {{ old('programa_id') }}).modal('show');
                @endif
            </script>
        @endif
        </div>

        @include('components.inspinia.footer-inspinia')

    </div>
    </div>

    <script>
        const hiddenFileInput = document.getElementById('icono');
        const iconUploadButton = document.getElementById('icon-upload');

        iconUploadButton.addEventListener('click', function() {
            hiddenFileInput.click();
        });

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
                        { extend: 'excel', title: 'PROGRAMAS', exportOptions: { columns: ':not(.oculto)' }},
                        { extend: 'pdf',
                        title: 'PROGRAMAS',
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
