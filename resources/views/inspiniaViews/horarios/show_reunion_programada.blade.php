<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA | REUNION PROGRAMADA</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Reunión Programada</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">
                        <strong>Reunión Programada</strong>
                    </li>
                </ol>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content">
                            <h2><strong>Reunión</strong></h2>
                            <form method="POST" action="{{route('reunionesProgramadas.update', $reunion->id)}}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fecha</label>
                                            <input type="date"
                                                value="{{ $reunion->fecha }}"
                                                class="form-control" name="fecha" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Hora Inicial</label>
                                            <select name="hora_inicial" class="form-control" required>
                                                @foreach($horas as $hora)
                                                    <option @if($hora == $reunion->hora_inicial) selected @endif>{{$hora}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Hora Final</label>
                                            <select name="hora_final" class="form-control" required>
                                                @foreach($horas as $hora)
                                                    <option @if($hora == $reunion->hora_final) selected @endif>{{$hora}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <input type="text" name="descripcion"
                                                placeholder="(Opcional)"
                                                value="{{ $reunion->descripcion }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Disponibilidad</label>
                                            <select class="form-control" id="selectDisponibilidad" name="disponibilidad" onchange="onDisponibilityChange()" required>
                                                <option @if($reunion->disponibilidad == 'Virtual') selected @endif >Virtual</option>
                                                <option @if($reunion->disponibilidad == 'Presencial') selected @endif >Presencial</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Url</label>
                                            <input type="text" name="url" id="inputUrl"
                                                placeholder="..."
                                                value="{{ $reunion->url }}"
                                                class="form-control" @if($reunion->disponibilidad == 'Virtual') required @else disabled @endif >
                                        </div>
                                        <div class="form-group">
                                            <label>Direccion</label>
                                            <input type="text" name="direccion" id="inputDireccion"
                                                placeholder="..."
                                                value="{{ $reunion->direccion }}"
                                                class="form-control" @if($reunion->disponibilidad == 'Presencial') required @else disabled @endif >
                                        </div>
                                        <div class="form-group d-flex flex-column">
                                            <label>Integrantes</label>
                                            <select class="form-control w-75 multiple_integrantes_select" multiple name="colaboradores_id[]" required style="display: none">
                                                @foreach ($colaboradores as $colaborador)
                                                <option @foreach($integrantes as  $integrante) @if($integrante->colaborador_id == $colaborador->id) selected @endif @endforeach value="{{ $colaborador->id }} ">{{ $colaborador->candidato->nombre }} {{ $colaborador->candidato->apellido }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group d-flex justify-content-end px-3">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="ibox-content">
                            <div class="table-responsive">
                                <h2 class="mx-3"><strong class="font-bold">INTEGRANTES REUNIÓN</strong></h2>
                                <hr>
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Correo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($integrantes as $index => $integrante)
                                        <tr class="gradeX">
                                            <td>{{ $index+1 }}</td>
                                            <td>{{ $integrante->colaborador->candidato->nombre }}</td>
                                            <td>{{ $integrante->colaborador->candidato->apellido }}</td>
                                            <td>{{ $integrante->colaborador->candidato->correo }}</td>
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










        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

</body>
<!-- Mainly scripts -->
{{-- <script src="{{asset('js/jquery-3.1.1.min.js')}}"></script> --}}
{{-- <script src="{{asset('js/popper.min.js')}}"></script> --}}
{{-- <script src="{{asset('js/bootstrap.js')}}"></script> --}}
<script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js')}}"></script>
<script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

<script src="{{asset('js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('js/plugins/dataTables/dataTables.bootstrap4.min.js')}}"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('js/inspinia.js')}}"></script>
<script src="{{asset('js/plugins/pace/pace.min.js')}}"></script>

<!-- Page-Level Scripts -->

<script>
    $(document).ready(function() {
            $('.multiple_integrantes_select').select2();
        });

    let reunion = <?php echo json_encode($reunion); ?>;

    const onDisponibilityChange = () => {
            let selectDisponibilidad = document.getElementById('selectDisponibilidad');
            let inputUrl = document.getElementById('inputUrl');
            let inputDireccion = document.getElementById('inputDireccion');
            if(selectDisponibilidad.value === 'Virtual') {
                inputUrl.required = true;
                inputUrl.disabled = false;
                inputUrl.value = reunion.url;
                inputDireccion.required = false;
                inputDireccion.disabled = true;
                inputDireccion.value = '';
            } else if(selectDisponibilidad.value === 'Presencial'){
                inputDireccion.required = true;
                inputDireccion.disabled = false;
                inputDireccion.value = reunion.direccion;
                inputUrl.required = false;
                inputUrl.disabled = true;
                inputUrl.value = '';
            }
        }
</script>
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy', exportOptions: { columns: ':not(.oculto)' }},
                { extend: 'csv', exportOptions: { columns: ':not(.oculto)' }},
                { extend: 'excel', title: 'INTEGRANTES', exportOptions: { columns: ':not(.oculto)' }},
                { extend: 'pdf',
                title: 'INTEGRANTES',
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

</html>
