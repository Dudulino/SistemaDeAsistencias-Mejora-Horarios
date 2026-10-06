<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Horario de Clases</title>
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.print.css') }}" rel='stylesheet' media='print'>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Horario de Clases</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('colaboradores.index')}}">Colaboradores</a>
                    </li>
                    <li class="breadcrumb-item">
                        <strong>Horario de Clases</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>



        <div class="wrapper wrapper-content animated fadeIn">
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
                            <li><a class="nav-link" data-toggle="tab" href="#tab-2">Agregar</a></li>
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
                                                        <form id="storeHorarios" role="form" method="POST"
                                                            action="{{ route('horarioClase.store') }}">
                                                            @csrf
                                                            <h2><strong>Información</strong></h2>
                                                            <div class="row">
                                                                <div class="col-sm-6">

                                                                    <div class="form-group"><label>Nombres</label>
                                                                        <input type="text"
                                                                            placeholder="Ingrese su nombre"
                                                                            value="{{ $colaborador->candidato->nombre }}"
                                                                            class="form-control" disabled>
                                                                    </div>
                                                                    <div class="form-group"><label>DNI</label>
                                                                        <input type="text"
                                                                            placeholder="Ingrese su nombre"
                                                                            value="{{ $colaborador->candidato->dni }}"
                                                                            class="form-control" disabled>
                                                                    </div>

                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group"><label>Apellidos</label>
                                                                        <input type="text"
                                                                            placeholder="Ingrese su nombre"
                                                                            value="{{ $colaborador->candidato->apellido }}"
                                                                            class="form-control" disabled>
                                                                    </div>
                                                                    <div class="form-group"><label>Ciclo</label>
                                                                        <input type="text"
                                                                            placeholder="Ingrese su nombre"
                                                                            value="{{ $colaborador->candidato->ciclo_de_estudiante}}"
                                                                            class="form-control" disabled>
                                                                    </div>
                                                                </div>



                                                            </div>
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
                                                                                    <th>Día</th>
                                                                                    <th>Hora Inicial</th>
                                                                                    <th>Hora Final</th>
                                                                                    <th>Justificacion</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody>
                                                                                @foreach($horariosDeClases as $key =>
                                                                                $horario)
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="form-group row">
                                                                                            <label
                                                                                                class="col-form-label"></label>

                                                                                            <div class="col-sm-10">
                                                                                                <input
                                                                                                    class="form-control m-b"
                                                                                                    value="{{$horario->dia}}"
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
                                                                                                value="{{$horario->hora_inicial}}"
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
                                                                                                value="{{$horario->hora_final}}"
                                                                                                disabled>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group">
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                value="{{$horario->justificacion}}"
                                                                                                disabled>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-danger float-right mx-2"
                                                                                            type="button"
                                                                                            onclick="confirmDelete({{ $horario->id }})"><i
                                                                                                class="fa fa-trash-o"></i>
                                                                                        </button>
                                                                                        <button
                                                                                            class="btn btn-info float-right mx-2"
                                                                                            type="button"
                                                                                            href="#modal-form-update-{{$horario->id}}"
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
                                                                                                    name="horarios[0][dia]">
                                                                                                    @foreach($days as $day)
                                                                                                        <option>{{$day}}</option>
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
                                                                                            <!--
                                                                                            <input type="time"
                                                                                                class="form-control"
                                                                                                name="horarios[0][hora_inicial]"
                                                                                                > -->
                                                                                            <select class="form-control"
                                                                                                name="horarios[0][hora_inicial]"
                                                                                                id="">
                                                                                                @foreach($horas as $key
                                                                                                => $hora)
                                                                                                <option
                                                                                                    value="{{ $hora }}">
                                                                                                    {{ $hora }}</option>

                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group date">
                                                                                            <span
                                                                                                class="input-group-addon"><i
                                                                                                    class="fa fa-calendar"></i></span>
                                                                                            <select class="form-control"
                                                                                                name="horarios[0][hora_final]"
                                                                                                id="">
                                                                                                @foreach($horas as $key
                                                                                                => $hora)
                                                                                                <option
                                                                                                    value="{{ $hora }}">
                                                                                                    {{ $hora }}</option>

                                                                                                @endforeach
                                                                                            </select>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="input-group">
                                                                                            <select class="form-control"
                                                                                                name="horarios[0][justificacion]">
                                                                                                <option>Clases</option>
                                                                                                <option>Trabajo</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button
                                                                                            class="btn btn-danger float-right"
                                                                                            type="button"
                                                                                            onclick="eliminarFila(this)"><i
                                                                                                class="fa fa-trash-o"></i></button>
                                                                                    </td>
                                                                                </tr>
                                                                                <input type="number"
                                                                                    class="form-control-file"
                                                                                    id="colaborador_id"
                                                                                    name="colaborador_id"
                                                                                    value="{{ $colaborador->id }}"
                                                                                    style="display: none;">
                                                        </form>
                                                        <!---Div-->

                                                        </tbody>

                                                        </table>
                                                        <div class="text-center">
                                                            <button class="ladda-button btn btn-primary mr-5"
                                                                onclick="document.getElementById('storeHorarios').submit();"
                                                                data-style="expand-left">Guardar</button>
                                                            <a class="ladda-button btn btn-primary"
                                                                data-style="expand-left" href="{{route('colaboradores.index')}}">Cancelar</a>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                            @foreach($horariosDeClases as $key =>
                                            $horario)
                                            <div id="modal-form-update-{{$horario->id}}" class="modal fade"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <form role="form" method="POST"
                                                                action="{{ route('horarioClase.update', $horario->id) }}"
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
                                                                            value="{{ old('dia', $horario->dia) }}"
                                                                            id="dia">
                                                                            @foreach($days as $day)
                                                                                <option @if($day === $horario->dia) selected @endif>{{$day}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Hora Inicial</label>
                                                                        <select class="form-control" name="hora_inicial" id="hora_inicial">
                                                                            @foreach($horas as $key => $hora)
                                                                            <option @if($horario->hora_inicial == $hora) selected @endif value="{{ $hora }}"> {{ $hora }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Hora Final</label>
                                                                        <select class="form-control" name="hora_final" id="hora_final">
                                                                            @foreach($horas as $key => $hora)
                                                                            <option @if($horario->hora_final == $hora) selected @endif value="{{ $hora }}">{{ $hora }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Justificacion</label>
                                                                        <select class="form-control" name="justificacion">
                                                                            <option @if($horario->justificacion == "Clases") selected @endif >Clases</option>
                                                                            <option @if($horario->justificacion == "Trabajo") selected @endif>Trabajo</option>
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button
                                                                            class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                                            type="submit"><i
                                                                                class="fa fa-check"></i>&nbsp;Confirmar</button>
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
    </div>
    </div>








    </div>
    <div class="footer">
        <div class="float-right">
            10GB of <strong>250GB</strong> Free.
        </div>
        <div>
            <strong>Copyright</strong> Example Company &copy; 2014-2018
        </div>
    </div>
    </div>
    </div>

    <style>
        .fc-toolbar {
            display: none;
        }
    </style>
    <script>
        function confirmDelete(id) {

            Swal.fire({
                    title: "¿Deseas eliminar este registro?",
                    showCancelButton: true,
                    confirmButtonText: "Eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = 'POST';
                    // form.action = `/horarioClase/${id}`;
                    let routeTemplate = "<?php echo route('horarioClase.destroy', ':id'); ?>";
                    form.action = routeTemplate.replace(':id', id);

                    form.innerHTML = `
                        @csrf @method("DELETE")`;
                    // form.innerHTML = '@csrf @method("DELETE")';

                    document.body.appendChild(form);
                    form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "No se eliminó el horario",
                            icon: "info",
                            customClass: {
                                content: 'swal-content'
                            }
                        });

                        const style = document.createElement('style');
                        style.innerHTML = `
                            .swal2-html-container{
                                color: #FFFFFF;
                            }
                        `;
                        document.head.appendChild(style);
                    }
                    });
            // alertify.confirm("¿Deseas eliminar este registro?", function(e) {
            //     if (e) {
            //         let form = document.createElement('form')
            //         form.method = 'POST'
            //         form.action = `/horarioClase/${id}`
            //         form.innerHTML = '@csrf @method('DELETE')'
            //         document.body.appendChild(form)
            //         form.submit()
            //     } else {
            //         return false
            //     }
            // });
        }


        var contadorFilas = 0;
        var horas = @json($horas);



    </script>


<script src="{{ asset('js/asistencia/colaboradores-horario-clase.js') }}">

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
                console.log(horario.hora_inicial);
                return {
                    title: horario.justificacion,
                    start: new Date(2024, 1, numeroDia, horario.hora_inicial, 0),
                    end: new Date(2024, 1, numeroDia, horario.hora_final, 0),
                    allDay: false,
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
                minTime: '07:00:00',
                maxTime: '22:00:00',
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
