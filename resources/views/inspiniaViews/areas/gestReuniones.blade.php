<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.print.css') }}" rel='stylesheet' media='print'>
    <title>INSPINIA | ÁREA REUNIONES</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Gestión Reunión</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('areas.index')}}">Áreas</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Reuniones Área</strong>
                    </li>
                </ol>
            </div>


        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            @if ($errors->any())
                    <div id="alert-error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Errores:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            <div class="row">


                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs" role="tablist">
                            <li><a class="nav-link active" data-toggle="tab" href="#tab-1"> Ver</a></li>
                            <li><a class="nav-link" data-toggle="tab" href="#tab-2">Gestionar</a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="wrapper wrapper-content">
                                        <div class="row animated fadeInDown">
                                            <div class="col-lg-12">
                                                <div class="ibox ">

                                                    <div class="ibox-content">
                                                        <style>
                                                            .fc-event {
                                                                text-align: right;
                                                                font-size: 15px;
                                                                display: flex;
                                                                align-items: center;
                                                                justify-content: center;

                                                            }

                                                            .fc-content {
                                                                text-align: center;
                                                            }

                                                            .fc-day-header {
                                                                display: none !important;
                                                            }
                                                        </style>
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <div class="wrapper wrapper-content animated fadeInRight">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <form id="storeReuniones" role="form" method="POST"
                                                            action="{{ route('areas.reunionCreate') }}">
                                                            @csrf
                                                            <h2><strong>REUNIONES</strong></h2>
                                                            <div class="col-lg-12">
                                                                <div class="ibox">
                                                                    <div class="ibox-title">
                                                                        <h5>Tabla</h5>
                                                                        <div class="ibox-tools">
                                                                            <button class="btn btn-primary"
                                                                                type="button" href="#modal-form-edit"
                                                                                onclick="agregarFila()"
                                                                                data-toggle="modal"><i
                                                                                    class="fa fa-plus-circle"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ibox-content">

                                                                        <table id="tablaHorarios" class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Dia</th>
                                                                                    <th>Hora Inicial</th>
                                                                                    <th>Hora Final</th>
                                                                                    <th>Disponibilidad</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody>
                                                                                @foreach($reuniones as $key =>
                                                                                $reunion)
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group row">
                                                                                            <label
                                                                                                class="col-form-label"></label>

                                                                                            <div class="col-sm-10">
                                                                                                <input
                                                                                                    class="form-control m-b"
                                                                                                    value="{{$reunion->dia}}"
                                                                                                    disabled>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group date">
                                                                                            <span
                                                                                                class="input-group-addon">
                                                                                                <i
                                                                                                    class="fa fa-calendar"></i></span>
                                                                                            <input type="time"
                                                                                                class="form-control"
                                                                                                value="{{$reunion->hora_inicial}}"
                                                                                                disabled>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group date">
                                                                                            <span
                                                                                                class="input-group-addon"><i
                                                                                                    class="fa fa-calendar"></i></span>
                                                                                            <input type="time"
                                                                                                class="form-control"
                                                                                                value="{{$reunion->hora_final}}"
                                                                                                disabled>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="form-group row">
                                                                                            <label
                                                                                                class="col-form-label"></label>

                                                                                            <div class="col-sm-10">
                                                                                                <input
                                                                                                    class="form-control m-b"
                                                                                                    value="{{$reunion->disponibilidad}}"
                                                                                                    disabled>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-danger float-right mx-2"
                                                                                            type="button"
                                                                                            onclick="confirmDelete({{ $reunion->id }})"><i
                                                                                                class="fa fa-trash-o"></i>
                                                                                        </button>
                                                                                        <button
                                                                                            class="btn btn-info float-right mx-2"
                                                                                            type="button"
                                                                                            href="#modal-form-update-{{$reunion->id}}"
                                                                                            data-toggle="modal"><i
                                                                                                class="fa fa-paste"></i>

                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group row">
                                                                                            <label
                                                                                                class="col-form-label"></label>

                                                                                            <div class="col-sm-10">
                                                                                                <select
                                                                                                    class="form-control m-b"
                                                                                                    name="reuniones[0][dia]">
                                                                                                    @foreach($dias as $key => $dia)
                                                                                                    <option>{{$dia}}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group date">
                                                                                            <span
                                                                                                class="input-group-addon">
                                                                                                <i
                                                                                                    class="fa fa-calendar"></i></span>
                                                                                            <select class="form-control"
                                                                                                name="reuniones[0][hora_inicial]"
                                                                                                id="">
                                                                                                @foreach($horas as $key
                                                                                                => $hora)
                                                                                                <option
                                                                                                    value="{{ $hora }}">
                                                                                                    {{ $hora }}</option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        @error('horarios.0.hora_inicial')
                                                                                            <div class="text-danger mt-2">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group date">
                                                                                            <span
                                                                                                class="input-group-addon"><i
                                                                                                    class="fa fa-calendar"></i></span>
                                                                                            <select class="form-control"
                                                                                                name="reuniones[0][hora_final]"
                                                                                                id="">
                                                                                                @foreach($horas as $key => $hora)
                                                                                                <option
                                                                                                    value="{{ $hora }}">
                                                                                                    {{ $hora }}</option>

                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                        @error('reuniones.0.hora_final')
                                                                                            <div class="text-danger mt-2">{{ $message }}</div>
                                                                                        @enderror
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="form-group row">
                                                                                            <div class="col-sm-10">
                                                                                                <select
                                                                                                    class="form-control m-b"
                                                                                                    name="reuniones[0][disponibilidad]">
                                                                                                    @foreach($disponibilidades as $disponibilidad)
                                                                                                    <option>{{$disponibilidad}}</option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-danger float-right"
                                                                                            type="button"
                                                                                            onclick="eliminarFila(this)">
                                                                                            <i class="fa fa-trash-o"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="area_id"
                                                                value="{{ $area->id }}">

                                                        </form>
                                                        <!---Div-->

                                                        </tbody>

                                                        <div class="text-center">
                                                            <button class="ladda-button btn btn-primary mr-5"
                                                                onclick="document.getElementById('storeReuniones').submit();"
                                                                data-style="expand-left">Guardar</button>
                                                            <a class="ladda-button btn btn-primary"
                                                                data-style="expand-left" href="">Cancelar</a>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                            @foreach($reuniones as $key =>
                                            $reunion)
                                            <div id="modal-form-update-{{$reunion->id}}" class="modal fade"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <form role="form" method="POST"
                                                                action="{{ route('areas.reunionUpdate', $reunion->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('put')
                                                                <div class="col-sm-12 b-r">
                                                                    <h3 class="m-t-none m-b">
                                                                        Ingrese los
                                                                        Datos</h3>
                                                                    <div class="form-group">
                                                                        <label>Día</label>
                                                                        <select class="form-control m-b" name="dia"
                                                                            value="{{ old('dia', $reunion->dia) }}"
                                                                            id="dia">
                                                                            @foreach($dias as $key => $dia)
                                                                            <option @if($dia === $reunion->dia) selected @endif>{{$dia}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Hora
                                                                            Inicial</label>
                                                                        <select class="form-control" name="hora_inicial"
                                                                            id="hora_inicial">
                                                                            @foreach($horas as $key => $hora)
                                                                            <option @if($hora === $reunion->hora_inicial) selected @endif">{{ $hora }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Hora Final</label>
                                                                        <select class="form-control" name="hora_final"
                                                                            id="hora_final">
                                                                            @foreach($horas as $key => $hora)
                                                                            <option @if($hora === $reunion->hora_final) selected @endif">{{ $hora }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <select class="form-control m-b" name="disponibilidad"
                                                                            value="{{ old('disponibilidad', $reunion->disponibilidad) }}"
                                                                            id="disponibilidad">
                                                                            @foreach($disponibilidades as $key => $disponibilidad)
                                                                            <option @if($disponibilidad === $reunion->disponibilidad) selected @endif>{{$disponibilidad}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    <div>
                                                                        <button
                                                                            class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                                            type="submit"><i class="fa fa-check"></i>&nbsp;Confirmar</button>
                                                                    </div>
                                                                </div>
                                                            </form>
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
                    </div>
                </div>
            </div>

        </div>
        @include('components.inspinia.footer-inspinia')

    </div>
    </div>
    <style>
        /*Ocultar la fecha del calendario*/
        .fc-toolbar {
            display: none;
        }
    </style>

<script>

    function construirSelectDia(name) {
        var select = '<select class="form-control" name="' + name + '">';
        for (var i = 0; i < dias.length; i++) {
            select += '<option value="' + dias[i] + '">' + dias[i] + '</option>';
        }

        select += '</select>';
        return select
    }

    function construirSelectDisponibilidad(name) {
        var select = '<select class="form-control" name="' + name + '">';
        for (var i = 0; i < disponibilidades.length; i++) {
            select += '<option value="' + disponibilidades[i] + '">' + disponibilidades[i] + '</option>';
        }

        select += '</select>';
        return select
    }

    function construirSelectHora(name) {
        var select = '<select class="form-control" name="' + name + '">';
        for (var i = 0; i < horas.length; i++) {
            select += '<option value="' + horas[i] + '">' + horas[i] + '</option>';
        }
        select += '</select>';
        return select;
    }

    function eliminarFila(boton) {
        var fila = boton.parentNode.parentNode;
        fila.parentNode.removeChild(fila);
    }

</script>

<script>
    function confirmDelete(id) {
        alertify.confirm("¿Deseas eliminar este registro?", function(e) {
            if (e) {
                let form = document.createElement('form')
                form.method = 'POST'
                form.action = "{{ route('areas.delete', ':id') }}".replace(':id', id)
                form.innerHTML = `@csrf @method('DELETE')`
                document.body.appendChild(form)
                form.submit()
            }
        });
    }


    var contadorFilas = 0;
    var horas = @json($horas);
    var dias = @json($dias);
    var disponibilidades = @json($disponibilidades);

    function agregarFila() {
        var tabla = document.getElementById("tablaHorarios").getElementsByTagName('tbody')[0];
        var nuevaFila = tabla.insertRow(tabla.rows.length);

        // Insertar celdas en la nueva fila
        var celdaDia = nuevaFila.insertCell(0);
        var celdaHoraInicial = nuevaFila.insertCell(1);
        var celdaHoraFinal = nuevaFila.insertCell(2);
        var celdaDisponibilidad = nuevaFila.insertCell(3);
        var celdaBotonEliminar = nuevaFila.insertCell(4);

        contadorFilas++;

        // Construir el select de horas iniciales y finales
        var selectDia = construirSelectDia('reuniones[' + contadorFilas + '][dia]');
        var selectDisponibilidad = construirSelectDisponibilidad('reuniones[' + contadorFilas + '][disponibilidad]');
        var selectHoraInicial = construirSelectHora('reuniones[' + contadorFilas + '][hora_inicial]');
        var selectHoraFinal = construirSelectHora('reuniones[' + contadorFilas + '][hora_final]');

        celdaDia.innerHTML = '<div class="form-group row"><label class="col-form-label"></label><div class="col-sm-10">'+selectDia+'</div></div>';
        celdaDisponibilidad.innerHTML = '<div class="form-group row"><label class="col-form-label"></label><div class="col-sm-10">'+selectDisponibilidad+'</div></div>';
        celdaHoraInicial.innerHTML = '<div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>' + selectHoraInicial + '</div>';
        celdaHoraFinal.innerHTML = '<div class="input-group date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>' + selectHoraFinal + '</div>';
        celdaBotonEliminar.innerHTML = '<button class="btn btn-danger float-right" type="button" onclick="eliminarFila(this)"><i class="fa fa-trash-o"></i></button>';
    }
</script>



    <script>
        $(document).ready(function() {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

            /* initialize the external events -----------------------------------------------------------------*/
            $('#external-events div.external-event').each(function() {
                $(this).data('event', {
                    title: $.trim($(this).text()),
                    stick: true
                });

                $(this).draggable({
                    zIndex: 1111999,
                    revert: true,
                    revertDuration: 0
                });
            });

            /* initialize the calendar -----------------------------------------------------------------*/
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var horariosFormateados = <?php echo json_encode($horariosFormateados); ?>;
            const area = <?php echo json_encode($area); ?>;

            var eventosHorarios = horariosFormateados.map(function(horario) {
                var numeroDia;
                if(horario.dia == "Lunes"){
                    numeroDia = 5;
                } else if(horario.dia == "Martes"){
                    numeroDia = 6;
                } else if(horario.dia == "Miércoles"){
                    numeroDia = 7;
                } else if(horario.dia == "Jueves"){
                    numeroDia = 8;
                } else if(horario.dia == "Viernes"){
                    numeroDia = 9;
                } else if(horario.dia == "Sábado"){
                    numeroDia = 10;
                } else if(horario.dia == "Domingo"){
                    numeroDia = 4;
                } else{
                    numeroDia = 4;
                }
                return {
                    title: horario.disponibilidad,
                    start: new Date(2024, 1, numeroDia, horario.hora_inicial, 0),
                    end: new Date(2024, 1, numeroDia, horario.hora_final, 0),
                    allDay: false,
                    color: area.color_hex,
                    editable: false
                };
            });


            var eventos = [{
                    title: 'Domingo',
                    start: new Date(2024, 1, 4, 0, 0),
                    end: new Date(2024, 1, 4, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                },
                {
                    title: 'Lunes',
                    start: new Date(2024, 1, 5, 9, 0),
                    end: new Date(2024, 1, 5, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                },
                {
                    title: 'Martes',
                    start: new Date(2024, 1, 6, 9, 0),
                    end: new Date(2024, 1, 6, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                },
                {
                    title: 'Miércoles',
                    start: new Date(2024, 1, 7, 9, 0),
                    end: new Date(2024, 1, 7, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                },
                {
                    title: 'Jueves',
                    start: new Date(2024, 1, 8, 9, 0),
                    end: new Date(2024, 1, 8, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                },
                {
                    title: 'Viernes',
                    start: new Date(2024, 1, 9, 9, 0),
                    end: new Date(2024, 1, 9, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                },
                {
                    title: 'Sábado',
                    start: new Date(2024, 1, 10, 9, 0),
                    end: new Date(2024, 1, 10, 13, 30),
                    allDay: true,
                    color: '#a0d6f4',
                    editable: false
                }
            ].concat(eventosHorarios);

            $('#calendar').fullCalendar({
                locale: 'es',
                defaultView: 'agendaWeek',
                weekNumbers: false,
                weekNumbersWithinDays: 7,
                viewRender: function(view, element) {
                    var startDate = moment('2024-02-04');
                    var endDate = moment(startDate).add(6, 'weeks');
                    if (view.end.isAfter(endDate)) {
                        $('#calendar').fullCalendar('gotoDate', startDate);
                    }
                },
                header: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                allDayText: 'Hora/Area',
                slotDuration: '00:30:00',
                slotLabelInterval: '01:00',
                minTime: '01:00:00',
                maxTime: '24:00:01',
                contentHeight: 'auto',
                eventOverlap: true,
                slotEventOverlap: false,
                editable: true,
                droppable: true,
                allDaySlot: true,
                drop: function() {
                    if ($('#drop-remove').is(':checked')) {
                        $(this).remove();
                    }
                },
                events: eventos,
                eventRender: function(event, element) {
                    var daysToShow = 4;
                    var columnWidth = $('.fc-day-grid-container').width() / daysToShow;
                    element.css('width', columnWidth);
                }
            });
        });
    </script>



</body>

</html>
