<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cumpleaños</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        #calendar {
            max-width: 90%;
            margin: 30px auto;
        }

        /* Tooltip personalizado */
        .fc-event-tooltip {
            background-color: #333;
            color: #fff;
            padding: 3px;
            border-radius: 3px;
            font-size: 10px;
        }

        .fc-event-tooltip span {
            width: 100%;
        }

        /* Leyenda de colores */
        .legend {
            margin: 20px auto;
            max-width: 90%;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }

        .legend-green {
            background-color: #28a745;
        }

        .legend-yellow {
            background-color: #ffc107;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Cumpleaños</strong>
                    </li>
                </ol>
            </div>
        </div>

        <main>
            <!-- Leyenda de colores -->
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color legend-green"></div>
                    <span>Colaboradores Activos</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color legend-yellow"></div>
                    <span>Colaboradores Inactivos</span>
                </div>
            </div>

            <div id="calendar"></div>
        </main>

    </div>

</body>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const calendarEl = document.getElementById("calendar");

            // Obtener los eventos desde PHP
            const events = @json($events); // Convertir los datos de PHP a JSON

            // Inicializar el calendario
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: "dayGridMonth", // Vista mensual
                locale: "es", // Idioma español
                headerToolbar: {
                    left: "prev,next today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay",
                },
                events: events, // Usar los eventos pasados desde PHP
                eventContent: function (info) {
                    // Personalizar el contenido del evento
                    return { html: `<div class="birthday-event"><span>${info.event.title}</span></div>` };
                },

            });

            calendar.render();
        });
    </script>
</body>
</html>
