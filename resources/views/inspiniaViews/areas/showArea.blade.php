<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Área {{$area->especializacion}}</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        @php
        $colorArea = $area->color_hex;
        @endphp

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>ÁREA {{Str::upper($area->especializacion)}}</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Área {{$area->especializacion}}</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-9 h-100">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h2>Horarios Del Área</h2>
                        </div>
                        <div class="ibox-content">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 h-100">
                    <div class="ibox px-0">
                        <div class="ibox-title text-center px-0">
                            <h2>Colaboradores del Área</h2>
                        </div>
                        <div class="ibox-content pl-4 d-flex flex-column gap-20">
                            @foreach($colaboradores as $colaborador)
                            <div style="background:{{$colorArea}}; border-radius: 15px; "
                                class="colaboradorCont py-1 px-2 shadow" data-color="{{ $colorArea }}">
                                <h3>{{$colaborador->colaborador->candidato->nombre}}
                                    {{$colaborador->colaborador->candidato->apellido}}
                                </h3>
                            </div>
                            @endforeach
                            @if(count($colaboradores) === 0)
                            <div class="py-1 px-2">
                                <h3>El área no cuenta con colaboradores.</h3>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>








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

            .fc-toolbar {
                display: none;
            }
        </style>
        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

    <script>
        //Area
        const area = <?php echo json_encode($area); ?>;
        // Función para convertir un color hexadecimal a RGB
        function hexToRgb(hex) {
            // Eliminar el hash al inicio, si está presente
            hex = hex.replace(/^#/, '');

            // Convertir el hex a valores RGB
            let r = parseInt(hex.substring(0, 2), 16);
            let g = parseInt(hex.substring(2, 4), 16);
            let b = parseInt(hex.substring(4, 6), 16);

            return { r, g, b };
        }

        // Función para determinar si el color es oscuro o claro
        function isColorDark(hex) {
            let { r, g, b } = hexToRgb(hex);

            // Calcular la luminancia utilizando la fórmula estándar
            let luminance = 0.2126 * r + 0.7152 * g + 0.0722 * b;

            // Luminancia menor que 128 se considera oscura
            return luminance < 128;
        }

        // Determinar si el color es oscuro o claro
        var isDark = isColorDark(area.color_hex);
        var textColor = isDark ? '#FFFFFF' : '#000000'; // Blanco para colores oscuros, negro para colores claros

        //Contenedores
        document.querySelectorAll('.colaboradorCont').forEach(function(element) {
            element.style.color = textColor;
        });

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
                

                return {
                    title: area.especializacion,
                    start: new Date(2024, 1, numeroDia, horario.hora_inicial, 0),
                    end: new Date(2024, 1, numeroDia, horario.hora_final, 0),
                    allDay: false,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                };
            });
            
    
            var eventos = [{
                    title: 'Domingo',
                    start: new Date(2024, 1, 4, 0, 0),
                    end: new Date(2024, 1, 4, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                },
                {
                    title: 'Lunes',
                    start: new Date(2024, 1, 5, 9, 0),
                    end: new Date(2024, 1, 5, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                },
                {
                    title: 'Martes',
                    start: new Date(2024, 1, 6, 9, 0),
                    end: new Date(2024, 1, 6, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                },
                {
                    title: 'Miércoles',
                    start: new Date(2024, 1, 7, 9, 0),
                    end: new Date(2024, 1, 7, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                },
                {
                    title: 'Jueves',
                    start: new Date(2024, 1, 8, 9, 0),
                    end: new Date(2024, 1, 8, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                },
                {
                    title: 'Viernes',
                    start: new Date(2024, 1, 9, 9, 0),
                    end: new Date(2024, 1, 9, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
                    editable: false
                },
                {
                    title: 'Sabado',
                    start: new Date(2024, 1, 10, 9, 0),
                    end: new Date(2024, 1, 10, 13, 30),
                    allDay: true,
                    color: area.color_hex,
                    textColor: textColor,
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
                minTime: '08:00:00',
                maxTime: '18:00:01',
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