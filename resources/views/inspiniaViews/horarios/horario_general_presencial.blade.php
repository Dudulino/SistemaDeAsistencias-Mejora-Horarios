<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.print.css') }}" rel='stylesheet' media='print'>
    <title>INSPINIA | HORARIO GENERAL</title>

    {{-- <style>
    .fc-event {
    position: absolute !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    margin-top: 2px;
}
    </style>

    <style>
    .fc-time-grid .fc-event {
    position: absolute !important;
    left: 0 !important;
    right: 0 !important;
    margin: 2px 0 !important;
    padding: 4px !important;
    font-size: 13px !important;
    width: auto !important;
    white-space: normal !important;
}

    .fc-time-grid .fc-event {
    position: absolute !important;
    left: 2% !important;
    right: 2% !important;
    width: 100% !important;
    }

    .fc-toolbar, .fc-day-header {
        display: none !important;
    }
    .fc-time-grid .fc-event {
        position: inherit !important;
        left: auto !important;
        right: auto !important;
        margin-top: 1px !important;
        padding: 12.5px 0px  !important;
        font-size: 11px !important;
        white-space: nowrap !important;
        width: 100% !important;
    }
    .fc-timegrid-event {
        width: 100% !important;
        left: 0 !important;
        right: 0 !important;
        padding: 20px 0 !important;
    }

    </style> --}}

</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Horarios Generales</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Horario General</strong>
                    </li>
                </ol>
            </div>
        </div>

        {{-- btn descargar excel --}}
        <div id="button-container" style="text-align: center; margin-top: 20px; display: flex; justify-content: end;">

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


                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

<style>
    /* Ocultar elementos no necesarios */
    .fc-toolbar, .fc-day-header {
        display: none !important;
    }

    .fc-time-grid .fc-event {
        position: inherit !important;
        left: auto !important;
        right: auto !important;
        margin: 0 !important; /* sin márgenes que corten el alto */
        padding: 3px !important;
        font-size: 11px !important;
        width: 100% !important;
        height: 100% !important; /* ocupa toda la celda de la hora */
        box-sizing: border-box;
    }

    /* Ajustar el contenido interno del evento */
    .fc-time-grid-event > .fc-content {
        height: 100% !important;
        padding: 5px;
        box-sizing: border-box;
    }
</style>

    <script>
        $(document).ready(function() {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

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

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            var horariosAreas = <?php echo json_encode($horarios_presenciales_Asignados); ?>;

            console.log(horariosAreas);
            var eventosHorarios = horariosAreas.map(function(horario) {
                var numeroDia;
                console.log(horario.horario_modificado);
                if(horario.horario_modificado.dia == "Lunes"){
                    numeroDia = 5;
                } else if(horario.horario_modificado.dia == "Martes"){
                    numeroDia = 6;
                } else if(horario.horario_modificado.dia == "Miércoles"){
                    numeroDia = 7;
                } else if(horario.horario_modificado.dia == "Jueves"){
                    numeroDia = 8;
                } else if(horario.horario_modificado.dia == "Viernes"){
                    numeroDia = 9;
                } else if(horario.horario_modificado.dia == "Sábado"){
                    numeroDia = 10;
                } else if(horario.horario_modificado.dia == "Domingo"){
                    numeroDia = 4;
                } else{
                    numeroDia = 4;
                }
                return {
                    title: horario.area.especializacion,
                    start: new Date(2024, 1, numeroDia, horario.horario_modificado.hora_inicial, 0),
                    end: new Date(2024, 1, numeroDia, horario.horario_modificado.hora_final, 0),
                    allDay: false,
                    color: horario.area.color_hex,
                    editable: false,
                    area_id: horario.area.id
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
                    editable: true
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
                eventOrder: 'start',
                eventDisplay: 'block',
                eventOverlap: false,
                slotEventOverlap: false,
                editable: true,
                droppable: true,
                allDaySlot: false,

                drop: function() {
                    if ($('#drop-remove').is(':checked')) {
                        $(this).remove();
                    }
                },
                events: eventos,
                eventRender: function(event, element) {
                var daysToShow = 4;
                var columnWidth = $('.fc-day-grid-container').width() / daysToShow;
                // element.css('width', columnWidth);

                // NUEVO: envolver contenido para girarlo
                element.wrapInner('<div class="event-wrapper"></div>');
},

                eventClick: function(calEvent, jsEvent, view) {

                    if (calEvent.area_id) {
                        window.open("{{ route('areas.getHorario', ':area_id') }}".replace(':area_id', calEvent.area_id), '_blank');
                    } else {
                        console.log('Clic en día: ' + calEvent.title);
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/exceljs@4.2.0/dist/exceljs.min.js"></script>


    ></script>



    <script>
    function exportToExcel() {
        var horariosAreas = <?php echo json_encode($horarios_presenciales_Asignados); ?>;

        // Crear una estructura para almacenar las áreas por hora (de 8 AM a 6 PM)
        var eventosAgrupados = {
            "8 AM": [],
            "9 AM": [],
            "10 AM": [],
            "11 AM": [],
            "12 PM": [],
            "1 PM": [],
            "2 PM": [],
            "3 PM": [],
            "4 PM": [],
            "5 PM": [],
            "6 PM": []
        };

        // Generar colores únicos para cada área
        var coloresAreas = {};
        var coloresUsados = [];
        function generarColorTransparente() {
            var r = Math.floor(Math.random() * 256);
            var g = Math.floor(Math.random() * 256);
            var b = Math.floor(Math.random() * 256);
            return { argb: `FF${r.toString(16).padStart(2, "0")}${g.toString(16).padStart(2, "0")}${b.toString(16).padStart(2, "0")}` }; // Color sólido
        }

        horariosAreas.forEach(function(horario) {
            var horaInicial = horario.horario_modificado.hora_inicial;
            var horaFinal = horario.horario_modificado.hora_final;
            var area = horario.area.especializacion;
            var dia = horario.horario_modificado.dia;

            for (var h = horaInicial; h < horaFinal; h++) {
                var hora = formatTime(h);
                if (eventosAgrupados[hora]) {
                    eventosAgrupados[hora].push({ area: area, dia: dia });

                    // Generar un color único si no existe para el área
                    if (!coloresAreas[area]) {
                        var nuevoColor = generarColorTransparente();
                        while (coloresUsados.includes(nuevoColor.argb)) {
                            nuevoColor = generarColorTransparente();
                        }
                        coloresAreas[area] = nuevoColor;
                        coloresUsados.push(nuevoColor.argb);
                    }
                }
            }
        });

        // Crear una nueva instancia de ExcelJS
        var wb = new ExcelJS.Workbook();
        var ws = wb.addWorksheet("Eventos");

        // Establecer los encabezados de columnas (de 8AM a 6PM)
        ws.columns = [
            { header: 'Hora/Área', key: 'hora', width: 15 },
            { header: 'Lunes', key: 'lunes', width: 30 },
            { header: 'Martes', key: 'martes', width: 30 },
            { header: 'Miércoles', key: 'miercoles', width: 30 },
            { header: 'Jueves', key: 'jueves', width: 30 },
            { header: 'Viernes', key: 'viernes', width: 30 },
            { header: 'Sábado', key: 'sabado', width: 30 },
            { header: 'Domingo', key: 'domingo', width: 30 }
        ];

        // Añadir las filas con los horarios y las áreas
        for (var hora in eventosAgrupados) {
            var areas = eventosAgrupados[hora];

            var dias = {
                "Lunes": [],
                "Martes": [],
                "Miércoles": [],
                "Jueves": [],
                "Viernes": [],
                "Sábado": [],
                "Domingo": []
            };

            areas.forEach(function(evento) {
                dias[evento.dia].push(evento.area);
            });

            var row = {
                hora: hora,
                lunes: dias["Lunes"].join("\n"),
                martes: dias["Martes"].join("\n"),
                miercoles: dias["Miércoles"].join("\n"),
                jueves: dias["Jueves"].join("\n"),
                viernes: dias["Viernes"].join("\n"),
                sabado: dias["Sábado"].join("\n"),
                domingo: dias["Domingo"].join("\n")
            };

            ws.addRow(row);
        }

        // Aplicar estilos, colores y bordes a las celdas
        ws.eachRow(function(row, rowNumber) {
            row.eachCell(function(cell, colNumber) {
                // Aplicar bordes negros
                cell.border = {
                    top: { style: 'thin', color: { argb: '000000' } },
                    left: { style: 'thin', color: { argb: '000000' } },
                    bottom: { style: 'thin', color: { argb: '000000' } },
                    right: { style: 'thin', color: { argb: '000000' } }
                };

                if (rowNumber === 1) {
                    // Estilo del encabezado
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: 'FFFF' } // Celeste transparente
                    };
                    cell.font = { bold: true };
                    cell.alignment = { vertical: 'middle', horizontal: 'center' };
                } else if (colNumber === 1) {
                    // Fondo blanco para la columna Hora/Área
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: 'FFFFFF' } // Blanco
                    };
                } else {
                    // Aplicar colores para las áreas
                    var valorCelda = cell.value;
                    if (valorCelda) {
                        var color = coloresAreas[valorCelda.split("\n")[0]]; // Primer área en la celda
                        if (color) {
                            cell.fill = {
                                type: 'pattern',
                                pattern: 'solid',
                                fgColor: color
                            };
                        }
                    }
                    cell.alignment = { vertical: 'middle', horizontal: 'center', wrapText: true };
                }
            });
        });

        // Descargar el archivo Excel
        wb.xlsx.writeBuffer().then(function(buffer) {
            var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = "horarios-areas.xlsx";
            link.click();
        });
    }

    // Función para convertir la hora en formato de 12 horas (AM/PM)
    function formatTime(hora) {
        var hora12 = hora % 12;
        if (hora12 === 0) hora12 = 12;
        var ampm = hora < 12 ? 'AM' : 'PM';
        return hora12 + " " + ampm;
    }

    // Crear el botón
    var button = document.createElement("button");
    button.innerHTML = "Descargar Excel";
    button.onclick = exportToExcel;

    // Estilos opcionales para el botón
    button.style.backgroundColor = "#4CAF50";  // Fondo verde
    button.style.color = "white";  // Texto blanco
    button.style.fontSize = "16px";  // Tamaño de fuente
    button.style.padding = "10px 20px";  // Relleno interno
    button.style.border = "none";  // Sin borde
    button.style.borderRadius = "5px";  // Bordes redondeados
    button.style.cursor = "pointer";  // Cambio del cursor cuando pasa por encima

    // Añadir el botón al contenedor con id "button-container"
    document.getElementById("button-container").appendChild(button);
</script>


</body>
</html>
