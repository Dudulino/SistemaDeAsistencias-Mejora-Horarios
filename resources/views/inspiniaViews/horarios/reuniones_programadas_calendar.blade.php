<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>INSPINIA | REUNIONES ÁREAS</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Reuniones Programadas</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Reuniones Programadas</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">

            @if(session('error'))
            <div id="alert-error" class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert" style="position: relative;">
                <div style="flex-grow: 1;">
                    <strong>Error:</strong> {{ session('error') }}
                </div>
                <button onclick="deleteAlertError()" type="button" class="btn btn-outline-dark btn-xs" style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
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
                                                        </style>
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div role="tabpanel" id="tab-2" class="tab-pane active">
                                <div class="panel-body">
                                    <div class="wrapper wrapper-content animated fadeInRight">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="ibox ">
                                                    <div class="ibox-content">
                                                        <div class="row m-0">
                                                            <div class="col-10">
                                                                <h1>Crear Reunión</h1>
                                                            </div>
                                                        </div>
                                                        <hr class="mt-0" />
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12">
                                                                <form id="formStore" action="{{ route('reunionesProgramadas.store') }}"
                                                                    role="form" method="POST">
                                                                    @csrf
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Seleccione Fecha</h3>
                                                                        <input type="date" class="form-control w-75" name="fecha" required>
                                                                        {{-- <select class="form-control w-75" name="dia" required>                                                                        </select> --}}
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Seleccione Hora Inicial</h3>
                                                                        <select class="form-control w-75" name="hora_inicial" required>
                                                                            @foreach($horas as $hora)
                                                                                <option>{{$hora}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Seleccione Hora Final</h3>
                                                                        <select class="form-control w-75" name="hora_final" required>
                                                                            @foreach($horas as $hora)
                                                                                <option>{{$hora}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Seleccione Disponibilidad</h3>
                                                                        <select id="selectDisponibilidad" onchange="onDisponibilityChange()" class="form-control w-75" name="disponibilidad" required>
                                                                            <option>Virtual</option>
                                                                            <option>Presencial</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Elija a los Integrantes</h3>
                                                                        <select class="form-control w-75 multiple_integrantes_select" multiple name="colaboradores_id[]" required style="display: none">
                                                                            @foreach ($colaboradores as $colaborador)
                                                                            <option value=" {{ $colaborador->id }} ">{{ $colaborador->candidato->nombre }} {{ $colaborador->candidato->apellido }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Url</h3>
                                                                        <input id="inputUrl" name="url" placeholder="..." type="text" class="form-control w-75" required>
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Dirección</h3>
                                                                        <input id="inputDireccion" name="direccion" placeholder="..." type="text" class="form-control w-75" disabled>
                                                                    </div>
                                                                    <div class="input-group d-flex flex-column gap-y-2 align-content-start">
                                                                        <h3>Descripción</h3>
                                                                        <input name="descripcion" placeholder="(Opcional)" type="text" class="form-control w-75">
                                                                    </div>
                                                                    <div class="input-group mt-4">
                                                                        <button class="btn btn-primary"
                                                                            type="submit">
                                                                            <i class="fa fa-check"></i>&nbsp;Guardar
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="col-md-6 col-sm-12" id="min-calendar">

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
            </div>
        </div>










        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

    <style>
        /* .select2-container.select2-container--default.select2-container--open {
            z-index: 9999 !important;
            width: 100% !important;
        }
        .select2-container {
            display: inline !important;
        } */
        .select2-container{
            width: 75% !important;
        }
    </style>

    <script>

        const onDisponibilityChange = () => {
            let selectDisponibilidad = document.getElementById('selectDisponibilidad');
            let inputUrl = document.getElementById('inputUrl');
            let inputDireccion = document.getElementById('inputDireccion');
            if(selectDisponibilidad.value === 'Virtual') {
                inputUrl.required = true;
                inputUrl.disabled = false;
                inputDireccion.required = false;
                inputDireccion.disabled = true;
                inputDireccion.value = '';
            } else if(selectDisponibilidad.value === 'Presencial'){
                inputDireccion.required = true;
                inputDireccion.disabled = false;
                inputUrl.required = false;
                inputUrl.disabled = true;
                inputUrl.value = '';
            }
        }

        //JQuery para select multiple de integrantes
        $(document).ready(function() {
            $('.multiple_integrantes_select').select2();
        });


    </script>


    {{-- traduccion en español --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js"></script>

    <script>
         const deleteAlertError = () => {
            let alertError = document.getElementById('alert-error');
            if (alertError) {
                alertError.remove();
            } else{
                console.error("Elemento con ID 'alert-error' no encontrado.");
            }
        }

        
        $(document).ready(function() {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green'
            });

            // initialize the external events
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

            // initialize the calendar
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var reunionesProgramadas = <?php echo json_encode($reunionesProgramadas); ?>;

            var eventosHorarios = reunionesProgramadas.map(function(reunion) {
                let hf = reunion.horario_modificado; //Horario formateado
                // console.log(hf);
                // console.log(m);

                return {
                    title: reunion.descripcion,
                    start: new Date(hf.year, hf.month, hf.day, hf.hora_inicial, 0),
                    end: new Date(hf.year, hf.month, hf.day, hf.hora_final, 0),
                    allDay: false,
                    // color: '#f00',
                    editable: false,
                    url: hf.url
                };
            });

            $('#calendar').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'agendaWeek,agendaDay'
                },
                editable: false,
                droppable: false,
                defaultView: 'agendaWeek', // inicializar en formato semanal
                timeFormat: 'h:mm A', // formato de tiempo en eventos
                columnHeaderFormat: 'ddd M/D', // formato para el encabezado de los días
                drop: function() {
                    if ($('#drop-remove').is(':checked')) {
                        $(this).remove();
                    }
                },
                events: [].concat(eventosHorarios)
            });

            $('#min-calendar').fullCalendar({
                locale: 'es',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'agendaDay'
                },
                editable: false,
                droppable: false,
                defaultView: 'agendaDay', // inicializar en formato semanal
                timeFormat: 'h:mm A', // formato de tiempo en eventos
                columnHeaderFormat: 'ddd M/D', // formato para el encabezado de los días

                events: eventosHorarios,
            });
        });
    </script>



</body>

</html>
