<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.print.css') }}" rel='stylesheet' media='print'>
    <title>INSPINIA | HORARIO ÁREA</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>ÁREAS</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('areas.index')}}">Áreas</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Horario Área</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
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
                                                        <div class="row m-0">
                                                            <div class="col-10">
                                                                <h1>HORARIOS</h1>
                                                            </div>
                                                        </div>
                                                        <hr class="mt-0" />
                                                        @if($hasHorario)
                                                        <div style="display: flex; flex-direction: column; gap: 50px;"
                                                            class="">
                                                            @foreach($horarioAsignado as $key => $asignado)
                                                            <div class="pb-3 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-10">
                                                                        <h2>Actualizar Horario {{$key+1}} </h2>
                                                                    </div>
                                                                    <div class="col-2"><button class="btn btn-danger"
                                                                            onclick="confirmDelete({{$asignado->id}}, {{$area->id}})">Eliminar
                                                                            Registro</button></div>
                                                                </div>

                                                                <form
                                                                    action="{{ route('areas.horarioUpdate', $asignado->id) }}"
                                                                    role="form" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input value="{{ $area->id }}" name="area_id"
                                                                        type="hidden" />
                                                                    <h3 class="m-t-none m-b">Seleccione uno de los
                                                                        horarios
                                                                        disponibles:</h3>
                                                                    <div class="row">
                                                                        @foreach($horariosDisponibles as $horario)
                                                                        <div
                                                                            class="col-md-3 {{ $horario->using ? 'disabled' : '' }}">
                                                                            <div class="ibox">
                                                                                <div style="cursor: pointer"
                                                                                    class="ibox-content product-box"
                                                                                    onclick="toggleCheckbox(event, 'checkbox-update-{{$horario->id}}-{{$key}}', 'update-{{$key}}' )">
                                                                                    <div class="product-desc">
                                                                                        <div
                                                                                            style="display: flex; gap:60%">
                                                                                            <h1 class="product-name">{{
                                                                                                $horario->dia }}
                                                                                                @if($horario->using ===
                                                                                                true)
                                                                                                <span
                                                                                                    class="text-danger font-bold small">(En
                                                                                                    uso)
                                                                                                </span>
                                                                                                @if($horario->id ===
                                                                                                $asignado->horario_presencial_id)
                                                                                                <span
                                                                                                    class="text-success font-bold small">(Actual)

                                                                                                </span>

                                                                                                @endif


                                                                                                @endif
                                                                                            </h1>
                                                                                            <input type="checkbox"
                                                                                                value="{{ $horario->id }}"
                                                                                                name="horario_presencial_id"
                                                                                                class="horario-checkbox-update-{{$key}}"
                                                                                                id="checkbox-update-{{ $horario->id }}-{{$key}}"
                                                                                                onchange="updateSubmitButton('update-{{$key}}')" />
                                                                                        </div>
                                                                                        <div class="m-t-xs">Desde: {{
                                                                                            $horario->hora_inicial }}
                                                                                        </div>
                                                                                        <div class="m-t-xs">Hasta: {{
                                                                                            $horario->hora_final }}
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button
                                                                        class="btn btn-success btn-sm m-t-n-xs float-right"
                                                                        type="submit" id="submit-button-update-{{$key}}"
                                                                        disabled>
                                                                        <i class="fa fa-check"></i>&nbsp;Actualizar
                                                                    </button>
                                                                </form>
                                                            </div>

                                                            @endforeach
                                                        </div>


                                                        @endif
                                                        <form id="formStore" action="{{ route('areas.horarioCreate') }}"
                                                            role="form" method="POST">
                                                            @csrf
                                                            <h2>Asignar Horario</h2>
                                                            <input value="{{ $area->id }}" name="area_id"
                                                                type="hidden" />

                                                            <div id="storeFormContainer">
                                                                <div class="storeForm" data-form-index="0">
                                                                    <h3>NUEVO HORARIO:</h3>


                                                                    <h3 class="m-t-none m-b">Seleccione uno de los
                                                                        horarios disponibles:</h3>
                                                                    <div class="row">
                                                                        @foreach($horariosDisponibles as $index =>
                                                                        $horario)
                                                                        <div
                                                                            class="col-md-3 {{ $horario->using ? 'disabled' : '' }}">
                                                                            <div class="ibox">
                                                                                <div style="cursor: pointer"
                                                                                    class="ibox-content product-box"
                                                                                    onclick="toggleCheckbox(event, 'checkbox-store-{{$horario->id}}-0', 'store-0')">
                                                                                    <div class="product-desc">
                                                                                        <div
                                                                                            style="display: flex; gap:60%">
                                                                                            <h1 class="product-name">{{
                                                                                                $horario->dia }}
                                                                                                @if($horario->using ===
                                                                                                true)
                                                                                                <span
                                                                                                    class="text-danger font-bold small">(En
                                                                                                    uso)
                                                                                                </span>
                                                                                                @endif
                                                                                            </h1>
                                                                                            <input type="checkbox"
                                                                                                value="{{ $horario->id }}"
                                                                                                name="horario_presencial_id[]"
                                                                                                class="horario-checkbox-store-0"
                                                                                                id="checkbox-store-{{ $horario->id }}-0" />
                                                                                        </div>
                                                                                        <div class="m-t-xs">Desde: {{
                                                                                            $horario->hora_inicial }}
                                                                                        </div>
                                                                                        <div class="m-t-xs">Hasta: {{
                                                                                            $horario->hora_final }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" onclick="addNewForm()"
                                                                class="btn btn-secondary">Añadir nuevo horario</button>
                                                            <button type="button" onclick="removeForm(this)"
                                                                class="btn btn-danger btn-sm">Eliminar</button>
                                                            <button class="btn btn-primary btn-sm m-t-n-xs float-right"
                                                                type="submit" id="submit-store-button" disabled>
                                                                <i class="fa fa-check"></i>&nbsp;Enviar
                                                            </button>
                                                        </form>
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
        /*Ocultar la fecha del calendario*/
        .fc-toolbar {
            display: none;
        }
    </style>


<script>

    function toggleCheckbox(event, id, type) {
            if (event.target.tagName !== 'INPUT') {
                const checkbox = document.getElementById(id);
                checkbox.checked = !checkbox.checked;
                if (type.includes("update")) {
                    updateSubmitButton(type);
                } else if (type.includes("store")) {
                    updateStoreSubmitButton();
                }
            }
            uncheckOthers(id, type);
        }

        function uncheckOthers(id, type) {
            const checkboxes = document.querySelectorAll(`.horario-checkbox-${type}`);
            checkboxes.forEach(checkbox => {
                if (checkbox.id !== id) {
                    checkbox.checked = false;
                }
            });
        }

        function updateSubmitButton(type) {
            const checkboxes = document.querySelectorAll(`.horario-checkbox-${type}`);
            const submitButton = document.getElementById(`submit-button-${type}`);
            let isAnyChecked = false;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    isAnyChecked = true;
                }
            });
            submitButton.disabled = !isAnyChecked;
        }

        function updateStoreSubmitButton() {
            const formContainers = document.querySelectorAll('.storeForm');
            let allFormsValid = true;

            formContainers.forEach(container => {
                const checkboxes = container.querySelectorAll('input[type="checkbox"]');
                let isAnyChecked = false;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        isAnyChecked = true;
                    }
                });
                if (!isAnyChecked) {
                    allFormsValid = false;
                }
            });

            const submitButton = document.getElementById('submit-store-button');
            submitButton.disabled = !allFormsValid;
        }

        let formCounter = 1;

        function addNewForm() {
            const originalForm = document.querySelector('.storeForm');
            const newForm = originalForm.cloneNode(true);

            const formIndex = formCounter++;

            newForm.querySelectorAll('input[type="checkbox"]').forEach((checkbox, index) => {
                checkbox.checked = false;
                checkbox.classList.remove(`horario-checkbox-store-0`);
                checkbox.classList.add(`horario-checkbox-store-${formIndex}`);
                checkbox.id = checkbox.id.replace('-0', `-${formIndex}`);
            });

            newForm.querySelectorAll('.product-box').forEach((box, index) => {
                const horarioId = box.querySelector('input[type="checkbox"]').value;
                box.setAttribute('onclick', `toggleCheckbox(event, 'checkbox-store-${horarioId}-${formIndex}', 'store-${formIndex}')`);
            });

            newForm.setAttribute('data-form-index', formIndex);

            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'btn btn-danger btn-sm';
            deleteButton.textContent = 'Eliminar';
            deleteButton.setAttribute('onclick', 'removeForm(this)');
            newForm.prepend(deleteButton);

            document.querySelector('#storeFormContainer').appendChild(newForm);

            updateStoreSubmitButton();

        }

        function removeForm(button) {
            const formContainer = button.closest('.storeForm');
            formContainer.remove();
        }

</script>

</script>

<script>
     function confirmDelete(id, area_id) {
            Swal.fire({
                    title: "¿Deseas eliminar este horario?",
                    showCancelButton: true,
                    confirmButtonText: "Eliminar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {

                        let form = document.createElement('form');
                        form.method = 'POST';
                        // form.action = `/areas/horarioDelete/${area_id}/${id}`;

                        let routeTemplate = "<?php echo route('areas.horarioDelete', [':area_id', ':horario_presencial_asignado_id']); ?>";
                        form.action = routeTemplate.replace(':area_id', area_id).replace(':horario_presencial_asignado_id', id);

                        form.innerHTML = '@csrf @method("DELETE")';

                        document.body.appendChild(form);
                        form.submit();
                    } else {

                        Swal.fire({
                            title: "Acción cancelada",
                            text: "El horario no fue eliminado",
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
                    console.console.log(result);

                    });

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
                title: area.especializacion,
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
