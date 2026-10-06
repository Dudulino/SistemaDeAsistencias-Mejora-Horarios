<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.print.css') }}" rel='stylesheet' media='print'>
    <title>INSPINIA | REUNIONES ÁREAS</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Reuniones Horarios</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Reuniones Generales</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs" role="tablist">
                            <a data-toggle="tab" href="#tab-1"></a>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" id="tab-1" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="wrapper wrapper-content">
                                        <div class="row animated fadeInDown">
                                            <div class="col-lg-12">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                    <h1 class="titulo">Reuniones Generales - Áreas</h1>
                                                    <br>
                                                    <div class="container">
                                                        <div class="visible-scrollbar" style="height:30em; overflow:auto;">
                                                            <table>
                                                            <thead>
                                                                <tr class="m1">
                                                                    <th class="hm">Hora / Área</th>
                                                                    <th id="A1">Domingo</th>
                                                                    <th id="A2">Lunes</th>
                                                                    <th id="A3">Martes</th>
                                                                    <th id="A4">Miércoles</th>
                                                                    <th id="A5">Jueves</th>
                                                                    <th id="A6">Viernes</th>
                                                                    <th id="A7">Sábado</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="celdas">
                                                                    <th rowspan="9">8:30 am - 12:30 pm</th>
                                                                    <td id="c1"></td>
                                                                    <td id="c2"></td>
                                                                    <td id="c3"></td>
                                                                    <td id="c4"></td>
                                                                    <td id="c5"></td>
                                                                    <td id="c6"></td>
                                                                    <td id="c7"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c8"></td>
                                                                    <td id="c9"></td>
                                                                    <td id="c10"></td>
                                                                    <td id="c11"></td>
                                                                    <td id="c12"></td>
                                                                    <td id="c13"></td>
                                                                    <td id="c14"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c15"></td>
                                                                    <td id="c16"></td>
                                                                    <td id="c17"></td>
                                                                    <td id="c18"></td>
                                                                    <td id="c19"></td>
                                                                    <td id="c20"></td>
                                                                    <td id="c21"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c22"></td>
                                                                    <td id="c23"></td>
                                                                    <td id="c24"></td>
                                                                    <td id="c25"></td>
                                                                    <td id="c26"></td>
                                                                    <td id="c27"></td>
                                                                    <td id="c28"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c29"></td>
                                                                    <td id="c30"></td>
                                                                    <td id="c31"></td>
                                                                    <td id="c32"></td>
                                                                    <td id="c33"></td>
                                                                    <td id="c34"></td>
                                                                    <td id="c35"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c36"></td>
                                                                    <td id="c37"></td>
                                                                    <td id="c38"></td>
                                                                    <td id="c39"></td>
                                                                    <td id="c40"></td>
                                                                    <td id="c41"></td>
                                                                    <td id="c42"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td id="c43"></td>
                                                                    <td id="c44"></td>
                                                                    <td id="c45"></td>
                                                                    <td id="c46"></td>
                                                                    <td id="c47"></td>
                                                                    <td id="c48"></td>
                                                                    <td id="c49"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c50"></td>
                                                                    <td id="c51"></td>
                                                                    <td id="c52"></td>
                                                                    <td id="c53"></td>
                                                                    <td id="c54"></td>
                                                                    <td id="c55"></td>
                                                                    <td id="c56"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c57"></td>
                                                                    <td id="c58"></td>
                                                                    <td id="c59"></td>
                                                                    <td id="c60"></td>
                                                                    <td id="c61"></td>
                                                                    <td id="c62"></td>
                                                                    <td id="c63"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>12:30 pm - 2:00 pm</th>
                                                                    <td class="receso" colspan="7">RECESO</td>
                                                                </tr>
                                                                <tr class="celdas" >
                                                                    <th rowspan="6">2:00 pm - 9:00 pm</th>
                                                                    <td id="c64"></td>
                                                                    <td id="c65"></td>
                                                                    <td id="c66"></td>
                                                                    <td id="c67"></td>
                                                                    <td id="c68"></td>
                                                                    <td id="c69"></td>
                                                                    <td id="c70"></td>
                                                                </tr>

                                                                <tr class="celdas">
                                                                    <td id="c71"></td>
                                                                    <td id="c72"></td>
                                                                    <td id="c73"></td>
                                                                    <td id="c74"></td>
                                                                    <td id="c75"></td>
                                                                    <td id="c76"></td>
                                                                    <td id="c77"></td>
                                                                </tr>

                                                                <tr class="celdas">
                                                                    <td id="c78"></td>
                                                                    <td id="c79"></td>
                                                                    <td id="c80"></td>
                                                                    <td id="c81"></td>
                                                                    <td id="c82"></td>
                                                                    <td id="c83"></td>
                                                                    <td id="c84"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c85"></td>
                                                                    <td id="c86"></td>
                                                                    <td id="c87"></td>
                                                                    <td id="c88"></td>
                                                                    <td id="c89"></td>
                                                                    <td id="c90"></td>
                                                                    <td id="c91"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c92"></td>
                                                                    <td id="c93"></td>
                                                                    <td id="c94"></td>
                                                                    <td id="c95"></td>
                                                                    <td id="c96"></td>
                                                                    <td id="c97"></td>
                                                                    <td id="c98"></td>
                                                                </tr>
                                                                <tr class="celdas">
                                                                    <td id="c99"></td>
                                                                    <td id="c100"></td>
                                                                    <td id="c101"></td>
                                                                    <td id="c102"></td>
                                                                    <td id="c103"></td>
                                                                    <td id="c104"></td>
                                                                    <td id="c105"></td>
                                                                </tr>
                                                            </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <style>
                                                    .container {
                                                        max-width: 1400px; /* Ancho máximo para pantallas grandes */
                                                        width: 100%; /* Ocupa el ancho disponible */
                                                        margin: 0 auto;
                                                        font-family: sans-serif;
                                                    }

                                                    h1 {
                                                        text-align: center;
                                                    }

                                                    .fecha {
                                                        text-align: center;
                                                        color: white;
                                                    }

                                                    table {
                                                        width: 100%;
                                                        border-collapse: collapse;
                                                        table-layout: fixed; /* Ayuda a que las columnas se comporten mejor */
                                                    }

                                                    th, td {
                                                        border: 1px solid black;
                                                        padding: 5px;
                                                        color: black;
                                                        height: 40px;
                                                    }

                                                    th {
                                                        text-align: center;
                                                        min-width: 100px;


                                                    }
                                                    .m1{
                                                        background-color: #4e7bbf;
                                                        color: white;

                                                    }
                                                    .hm{
                                                        background-color: #4e7bbf;
                                                        width: 15%; /* Ancho relativo para la primera columna */
                                                        color: white;
                                                    }
                                                    .titulo{
                                                        font-weight: 600;
                                                    }
                                                    .receso{
                                                        background-color: #4e7bbf;
                                                        font-size: 25px;
                                                        font-weight: 600;
                                                        color: white;
                                                        height: 100px;
                                                    }
                                                    td {
                                                        text-align: center;
                                                    }
                                                    #A4{
                                                        background-color: #bbd5ff;

                                                    }
                                                    #A2{
                                                        background-color: #bbd5ff;
                                                    }
                                                    #A1{
                                                        background-color: #bbd5ff;
                                                    }
                                                    #A3{
                                                        background-color: #bbd5ff;
                                                    }

                                                    #A4{
                                                        background-color: #bbd5ff;
                                                    }

                                                    #A5{
                                                        background-color: #bbd5ff;
                                                    }

                                                    #A6{
                                                        background-color: #bbd5ff;
                                                    }

                                                    #A7{
                                                        background-color: #bbd5ff;
                                                    }
                                                    .celdas{
                                                        background-color: hsla(0, 0%, 100%, 0.884);
                                                        height: 40px;
                                                    }

                                                    .visible-scrollbar {
                                                        overflow: auto;
                                                        -webkit-overflow-scrolling: touch;
                                                    }
                                                    .visible-scrollbar::-webkit-scrollbar {
                                                        height: 10px;
                                                        width: 10px;
                                                    }
                                                    .visible-scrollbar::-webkit-scrollbar-track {
                                                        background: #f1f1f1;
                                                    }
                                                    .visible-scrollbar::-webkit-scrollbar-thumb {
                                                        background: #c1c1c1;
                                                        border-radius: 6px;
                                                    }
                                                    .visible-scrollbar {
                                                        scrollbar-width: auto;
                                                        scrollbar-color: #c1c1c1 #f1f1f1;
                                                    }

                                                    /* Responsive: permitir scroll horizontal en pantallas pequeñas */
                                                    @media (max-width: 1024px) {
                                                        .visible-scrollbar { max-width: 100%; overflow-x: auto; }
                                                        table { min-width: 900px; } /* Reducimos el ancho mínimo */
                                                        th, td { padding: 6px 8px; font-size: 13px; }
                                                        .hm { width: 140px; } /* Ancho fijo para la primera columna en tabletas */
                                                    }

                                                    @media (max-width: 640px) {
                                                        .visible-scrollbar { max-width: 100%; overflow-x: auto; }
                                                        table { min-width: 800px; } /* Un ancho mínimo aún menor para móviles */
                                                        th, td { padding: 4px 6px; font-size: 12px; }
                                                        .hm { width: 120px; } /* Ancho fijo en móviles */
                                                    }

                                                    </style>
                                                    <script>
                                                    // Script para mostrar reuniones generales en la tabla
                                                    $(function() {
                                                        var reunionesAreas = <?php echo json_encode($reuniones); ?>;
                                                        // Mapeo de días a columna (1=Domingo, 2=Lunes, ..., 7=Sábado)
                                                        var diasCol = {
                                                            'Domingo': 1, 'Lunes': 2, 'Martes': 3, 'Miércoles': 4,
                                                            'Jueves': 5, 'Viernes': 6, 'Sábado': 7
                                                        };
                                                        // Mapeo de bloques de horario a filas (ajustar si cambian los bloques)
                                                        var bloques = [
                                                            { nombre: '8:30 am - 12:30 pm', base: 0 }, // c1-c62
                                                            { nombre: '2:00 pm - 9:00 pm', base: 63 }   // c63-c105
                                                        ];
                                                        // Agrupar reuniones por día y bloque
                                                        var agrupadas = {};
                                                        if (Array.isArray(reunionesAreas)) {
                                                            reunionesAreas.forEach(function(r) {
                                                                var dia = r.horario_modificado.dia;
                                                                var hora = parseInt(r.horario_modificado.hora_inicial, 10);
                                                                var bloqueIdx = null;
                                                                if (hora >= 8 && hora < 13) bloqueIdx = 0;
                                                                else if (hora >= 14 && hora < 21) bloqueIdx = 1;
                                                                if (bloqueIdx !== null && diasCol[dia]) {
                                                                    var key = dia + '_' + bloqueIdx;
                                                                    if (!agrupadas[key]) agrupadas[key] = [];
                                                                    agrupadas[key].push(r);
                                                                }
                                                            });
                                                            // Pintar en la tabla
                                                            Object.keys(diasCol).forEach(function(dia) {
                                                                bloques.forEach(function(bloque, bloqueIdx) {
                                                                    var key = dia + '_' + bloqueIdx;
                                                                    var eventos = agrupadas[key] || [];
                                                                    eventos.forEach(function(evento, idx) {
                                                                        if (idx < 8) { 
                                                                            var cellId = bloque.base + (idx * 7) + diasCol[dia];                                                                            var $celda = $('#c' + cellId);
                                                                            if ($celda.length) {
                                                                                $celda.empty();
                                                                                var $areaDiv = $('<div></div>');
                                                                                $areaDiv.text(evento.area.especializacion);
                                                                                $areaDiv.css({
                                                                                    backgroundColor: evento.area.color_hex,
                                                                                    color: '#fff',
                                                                                    padding: '5px',
                                                                                    marginBottom: '2px',
                                                                                    borderRadius: '6px',
                                                                                    fontSize: '12px',
                                                                                    cursor: 'pointer',
                                                                                    whiteSpace: 'nowrap',
                                                                                    overflow: 'hidden',
                                                                                    textOverflow: 'ellipsis'
                                                                                });
                                                                                $areaDiv.on('click', function() {
                                                                                    // Mostrar modal con datos del evento
                                                                                    $('#modalAreaName').text(evento.area.especializacion);
                                                                                    $('#modalMeetingDay').text(evento.horario_modificado.dia);
                                                                                    $('#modalMeetingTime').text(evento.horario_modificado.hora_inicial + ':00 - ' + evento.horario_modificado.hora_final + ':00');
                                                                                    $('#modalMeetingAvailability').text(evento.disponibilidad ?? '');
                                                                                    $('#meetingModal').modal('show');
                                                                                });
                                                                                $celda.append($areaDiv);
                                                                            }
                                                                        }
                                                                    });
                                                                });
                                                            });
                                                        }
                                                    });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div id="meetingModal" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detalles de la Reunión</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Área:</strong> <span id="modalAreaName"></span></p>
                                            <p><strong>Día:</strong> <span id="modalMeetingDay"></span></p>
                                            <p><strong>Horario:</strong> <span id="modalMeetingTime"></span></p>
                                            <p><strong>Disponibilidad:</strong> <span id="modalMeetingAvailability"></span></p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
    <style>
        .fc-toolbar {
            display: none;
        }
    </style>

<script>
    $(document).ready(function() {

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
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
        var reunionesAreas = <?php echo json_encode($reuniones); ?>;

        var eventosHorarios = reunionesAreas.map(function(reunion) {
            var numeroDia;
            if (reunion.horario_modificado.dia === "Lunes") {
                numeroDia = 5;
            } else if (reunion.horario_modificado.dia === "Martes") {
                numeroDia = 6;
            } else if (reunion.horario_modificado.dia === "Miércoles") {
                numeroDia = 7;
            } else if (reunion.horario_modificado.dia === "Jueves") {
                numeroDia = 8;
            } else if (reunion.horario_modificado.dia === "Viernes") {
                numeroDia = 9;
            } else if (reunion.horario_modificado.dia === "Sábado") {
                numeroDia = 10;
            } else if (reunion.horario_modificado.dia === "Domingo") {
                numeroDia = 4;
            } else {
                numeroDia = 4;
            }
            var abreviado = reunion.area.especializacion.match(/[A-Z]/g);
            breviado = abreviado ? abreviado.join('.') : reunion.area.especializacion;
            return {
                // En el calendario, solo se mostrará este title
                title: abreviado,
                start: new Date(2024, 1, numeroDia, reunion.horario_modificado.hora_inicial, 0),
                end: new Date(2024, 1, numeroDia, reunion.horario_modificado.hora_final, 0),
                allDay: false,
                color: reunion.area.color_hex,
                editable: false,
                // Guardamos la información completa para usarla en el modal
                reunionData: reunion
            };
        });

        var eventos = [
            {
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
                title: 'Sabado',
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
            // IMPORTANTE: Deja el formato de hora vacío para no mostrar las horas
            timeFormat: '',
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
                var daysToShow = 7;
                var columnWidth = $('.fc-day-grid-container').width() / daysToShow;
                element.css('width', columnWidth);
            },
            eventClick: function(calEvent, jsEvent, view) {
                // Mostramos el modal con toda la información real
                if (calEvent.reunionData) {
                    var reunionDetallada = calEvent.reunionData;
                    $('#modalAreaName').text(reunionDetallada.area.nombre || reunionDetallada.area.especializacion);
                    $('#modalAreaSpecialization').text(reunionDetallada.area.especializacion);
                    $('#modalMeetingDay').text(reunionDetallada.horario_modificado.dia);
                    $('#modalMeetingTime').text(
                        reunionDetallada.horario_modificado.hora_inicial + ':00 - ' +
                        reunionDetallada.horario_modificado.hora_final + ':00'
                    );
                    $('#modalMeetingAvailability').text(reunionDetallada.disponibilidad);

                    $('#meetingModal').modal('show');
                }
            }
        });
    });
</script>





</body>

</html>
