<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA | ASISTENCIA</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Responsabilidades</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Responsabilidades - Semanas</strong>
                    </li>
                </ol>
            </div>

        </div>


        <style>
            .juntar {
                margin-bottom: 0px;
            }

            table {
                margin: 0 auto;
                width: 80%;
                border-collapse: collapse;
                background-color: #ffffff;
                margin-bottom: 20px;
            }

            tr {
                width: 100%;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 18px;
                text-align: center;
            }

            th {
                background-color: #4E7BBF;
                color: rgb(255, 255, 255);
                padding: 15px;
            }

            .semana {
                background-color: #4E7BBF;
                position: relative;
            }

            .semana button {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                background-color: antiquewhite;
            }

            .area {
                background-color: #78EA91;
                color: #000000;
            }

            .colabo {
                background-color: #eaeaeb;
                color: #000000;
            }

            .check,
            .x {
                cursor: pointer;
            }

            .respon {
                background-color: #A0D6F4;
                color: #000000;
                padding: 10px;
            }

            #semana2 {
                display: none;
            }

            .semanaactiva {
                display: block;
            }

            .regre {
                position: absolute;
                left: 10px;
                background-color: antiquewhite;
            }

            .disabled {
                pointer-events: none;
                opacity: 0.75;
                /* Cambia la opacidad para simular el efecto de deshabilitar */
            }
        </style>


        @foreach($semanasMes as $index => $semana)

        <style>
            .section-calificar {
                height: 100% !important;
                object-fit: cover;
            }
        </style>
        <section id="semana{{$index+1}}" style="display: none" class="section-calificar">
            <table class="juntar">
                <tr>
                    <th> {{$mes}} </th>
                    <th class="d-flex w-100">
                        <div style="width: 90%">
                            <h3 class="p-0 m-0">Semana: {{$index+1}}</h3>
                            <p class="p-0 m-0">Desde: {{$semana->desde}} Hasta: {{$semana->hasta}}</p>
                        </div>
                        <div style="width: 10%" class="d-flex justify-content-center align-items-center gap-20">
                            <button class="btn btn-circle btn-info border-dark" id="backButton{{$index+1}}" onclick="regresarSemana()">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                            <button class="btn btn-circle btn-info border-dark" id="nextButton{{$index+1}}" onclick="avanzarSemana()">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th> Área</th>
                    <th class="area d-flex h-100 align-content-center">
                        <div style="width: 90%;" class="p-0 m-0">
                            <h3 class="p-0 m-0">
                                {{$area->especializacion}}
                            </h3>
                            {{-- <p title="La evaluación de nuevas semanas se liberan cada domingo."
                                class="p-0 m-0 font-italic {{$semana->disponible ? 'text-success' : 'text-danger'}}">
                                {{
                                    $semana->disponible ? '(Esta semana está disponible para ser evaluada)'
                                    : '(Esta semana no puede ser evaluada aún)'
                                }}
                            </p> --}}
                            <p title="La evaluación de nuevas semanas se liberan cada domingo."
                                class="p-0 m-0 font-italic text-success">
                                Activado los domingos
                            </p>
                        </div>
                        <div style="width: 10%">
                            <a class="btn btn-primary btn-success btn-md float-right fa fa-file-o" style="font-size: 20px;"
                                data-toggle="modal" data-target="#modal-form-{{$index+1}}">
                            </a>
                        </div>
                    </th>
                </tr>
            </table>

            {{-- Modal para la semana actual --}}
            <div id="modal-form-{{$index+1}}" class="modal"">
                    <div class=" modal-dialog">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center pl-5 ">
                        <h3 class="modal-title">Informe de Semana {{$index+1}}</h3>
                        {{-- Botón para abrir el modal de creación --}}
                        <button id="openModalButton{{ $index+1 }}" class="btn btn-success dim float-right" type="button"
                            data-toggle="modal" data-target="#modal-form-add-{{ $index+1 }}"
                            onclick="abrirModalCreacion({{ $index+1 }});">
                            Agregar Informe
                        </button>
                    </div>
                    <div class="modal-body">
                        @forelse ($semana->informesSemanales as $informe)
                        <div class="informe-item d-flex justify-content-center align-items-center">
                            <div style="border-bottom: 1px solid #ccc" class="row w-100 pb-1 mb-4">
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <h3>{{ $informe->titulo }}</h6>

                                </div>
                                <div
                                    class="col-sm-12 col-md-4 col-lg-4 d-flex align-items-center justify-content-around">
                                    {{-- BOTON VER --}}
                                    <div>
                                        <button data-toggle="modal" id="viewButton{{ $informe->id }}"
                                            class="btn btn-sm btn-success float-right "
                                            onclick="abrirModalVista({{ $informe->id }}, {{ $index+1 }})"
                                            href="#modal-form-view-{{ $informe->id }}">
                                            <i style="font-size: 20px" class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                    {{-- BOTON EDITAR --}}
                                    <div>
                                        <button data-toggle="modal" id="editButton{{ $informe->id }}"
                                            class="btn btn-sm btn-info float-right"
                                            onclick="abrirModalEdicion({{ $informe->id }}, {{ $index+1 }})"
                                            href="#modal-form-update-{{ $informe->id }}">
                                            <i style="font-size: 20px" class="fa fa-paste"></i>
                                        </button>
                                    </div>
                                    {{-- BOTON ELIMINAR --}}
                                    <div>
                                        <button
                                            onclick="confirmDelete({{ $informe->id }}, {{ $index+1 }}, {{ $year }}, '{{ $mes }}', {{ $area->id }})"
                                            class="btn btn-danger btn-sm"
                                            type="button"
                                            title="Eliminar informe">
                                        <i style="font-size: 20px" class="fa fa-trash"></i>
                                    </button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        @empty
                        <p>No hay informes registrados para esta semana.</p>
                        @endforelse
                    </div>
                </div>
            </div>
    </div>
    @foreach ($semana->informesSemanales as $informe)
    {{-- MODAL VIEW --}}
    <div id="modal-form-view-{{ $informe->id }}" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detalles del Informe</h3>
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center mb-3">
                        <h4>Título:</h4>
                        <h2 class="font-bold text-success m-0">{{ $informe->titulo }}</h2>
                    </div>

                    <div class="d-flex flex-column justify-content-center mb-3">
                        <h4>Nota Semanal:</h4>
                        <p>{{ $informe->nota_semanal ? $informe->nota_semanal : 'No se ha escrito una nota.' }}</p>
                    </div>

                    <div class="d-flex flex-column justify-content-center mb-2">
                        <h4>Fecha de Entrega:</h4>

                        @if (!empty($informe->dia))
                            <p class="fw-bold">📅 {{ \Carbon\Carbon::parse($informe->dia)->format('d/m/Y') }}</p>
                        @else
                            <p class="fw-bold text-muted">📅 No especificado</p>
                        @endif

                        @if (!empty($informe->hora))
                            <p class="fw-bold">🕒 {{ \Carbon\Carbon::parse($informe->hora)->format('H:i') }}</p>
                        @else
                            <p class="fw-bold text-muted">🕒 No especificado</p>
                        @endif
                    </div>


                    <div class="d-flex flex-column justify-content-center mb-3">
                        <h4>Archivo:</h4>
                        @if($informe->informe_url)
                        <a class="btn btn-success" href="{{ asset('storage/informes/' . $informe->informe_url) }}"
                            target="_blank">Ver archivo <i class="fa fa-file"></i></a>
                        @else
                        <p>No hay archivo disponible.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPDATE --}}
    <div id="modal-form-update-{{ $informe->id }}" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Editar Informe</h3>
                </div>
                <div class="modal-body">
                    <form role="form" action="{{ route('InformeSemanal.update', $informe->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- obtención de la semana empezando desde semana 1 --}}
                        <input type="hidden" name='index' value="{{ $index+1 }}">

                        <input type="hidden" name="semana_id" value="{{ $informe->semana_id }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="mes" value="{{ $mes }}">
                        <input type="hidden" name="area_id" value="{{ $informe->area_id }}">
                        <input type="hidden" name="form_type" value="edit">
                        <input type="hidden" name="informe" value="{{ $informe->id }}">

                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" class="form-control" name="titulo" value="{{ $informe->titulo }}"
                                required>
                            @error('titulo'.$informe->id)
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nota_semanal">Nota Semanal</label>
                            <textarea class="form-control" name="nota_semanal"
                                rows="3">{{ $informe->nota_semanal }}</textarea>
                            @error('nota_semanal'.$informe->id)
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="informe_url">Archivo</label>
                            <input type="file" class="form-control" name="informe_url">
                            @if($informe->informe_url)
                            <p><a href="{{ asset('storage/informes/' . $informe->informe_url) }}" target="_blank">Ver
                                    archivo actual</a></p>
                            @endif
                            @error('informe_url'.$informe->id)
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{-- MODAL STORE --}}
    <div id="modal-form-add-{{ $index+1 }}" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Crear Informe - Semana {{ $index+1 }}</h3>
                </div>
                <div class="modal-body">
                    <form action="{{ route('InformeSemanal.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="form_type" value="create">
                        <input type="hidden" name="semana_id" value="{{ $semana->id }}">
                        <input type="hidden" name="year" value="{{ $year }}">
                        <input type="hidden" name="mes" value="{{ $mes }}">
                        <input type="hidden" name="area_id" value="{{ $area->id }}">

                        {{-- obtención de la semana a partir desde el 1 --}}
                        <input type="hidden" name="index" value="{{ $index+1 }}">


                        <div class="form-group">
                            <label for="titulo-{{ $index+1 }}">Título</label>
                            <input type="text" class="form-control" id="titulo-{{ $index+1 }}" name="titulo" required>
                            @error('titulo')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="nota_semanal-{{ $index+1 }}">Nota Semanal</label>
                            <textarea class="form-control" id="nota_semanal-{{ $index+1 }}" name="nota_semanal" rows="3"
                                value="nota_semanal" required></textarea>
                            @error('nota_semanal')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="informe_url-{{ $index+1 }}">Archivo</label>
                            <input type="file" class="form-control" id="informe_url-{{ $index+1 }}" name="informe_url"
                                required>
                            @error('informe_url')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @if($semana->cumplido == true)
    <table id="table-semana-cumplida{{$index+1}}" class="disabled">
        <form id="cumplioUpdate{{$index+1}}" role="form" method="POST"
            action="{{ route('responsabilidades.actualizar', ['semana_id' => $semana->id, 'area_id' => $area->id]) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="year" value="{{$year}}">
            <input type="hidden" name="mes" value="{{$mes}}">
            <thead>
                <tr>
                    <th> Colaboradores / Responsabilidades </th>
                    @foreach($semana->responsabilidades as $responsabilidad)
                    <td class="respon">{{$responsabilidad->nombre}}</td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($semana->colaboradores as $colaboradorArea)
                <tr>
                    <th class="colabo">
                        {{$colaboradorArea->colaborador->candidato->nombre}}
                        {{$colaboradorArea->colaborador->candidato->apellido}}
                        <input type="number" name="colaborador_area_id[]" value="{{$colaboradorArea->id}}"
                            style="display: none">
                        <input type="number" name="semana_id" value="{{$semana->id}}" style="display: none">
                    </th>
                    @foreach($semana->responsabilidades as $responsabilidad)

                    <?php
                                    $registro = $registros->where('colaborador_area_id', $colaboradorArea->id)
                                                        ->where('responsabilidad_id', $responsabilidad->id)
                                                        ->where('semana_id', $semana->id)
                                                        ->first();
                                ?>
                    <input type="number" name="responsabilidad_id[]" value="{{$responsabilidad->id}}"
                        style="display: none">
                    <td class="check" onclick="toggleCheck(this)">
                        <div>
                            <span>
                                @isset($registro->cumplio)
                                @if($registro->cumplio == true)
                                ✔️
                                @else
                                ❌
                                @endif
                                @endisset
                            </span>
                            @isset($registro->cumplio)
                            <input type="number" name="cumplio[]" value="{{ old('cumplio', $registro->cumplio) }}"
                                style="display: none">
                            @else
                            <input type="number" name="cumplio[]" style="display: none">
                            @endisset

                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </form>
    </table>
    <style>
        .btn-calificar {
            /* height: 100%; */
        }
    </style>
    <div class="text-center btn-calificar">
        <button onclick="habilitarEdicion({{$index+1}})" class="ladda-button btn btn-success mr-2">Editar</button>
        <a href="#" id="BtnGuardar{{$index+1}}" class="ladda-button btn btn-primary mr-2 disabled"
            onclick="document.getElementById('cumplioUpdate{{$index+1}}').submit();" disabled>Guardar
        </a>
        <a href="{{$comeBackUri}}" class="ladda-button btn btn-warning text-white" data-style="expand-left">Regresar</a>
    </div>
    <script>
        function habilitarEdicion(index) {
                        let tabla = document.getElementById(`table-semana-cumplida${index}`);
                        let botonGuardar = document.getElementById(`BtnGuardar${index}`);

                        tabla.classList.remove('disabled');
                        botonGuardar.classList.remove('disabled');
                    }
    </script>
    @else
    <table id="table-semana{{$index+1}}">
        <form id="cumplioStore{{$index+1}}" role="form" method="POST" action="{{ route('responsabilidades.store') }}">
            @csrf
            <input type="hidden" name="year" value="{{$year}}">
            <input type="hidden" name="mes" value="{{$mes}}">
            <input type="hidden" name="area_id" value="{{$area->id}}">
            <input type="hidden" name='index' value="{{ $index+1 }}">
            <thead>
                <tr>
                    <th> Colaboradores / Responsabilidades </th>
                    @foreach($semana->responsabilidades as $responsabilidad)
                    <td class="respon">{{$responsabilidad->nombre}}</td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($semana->colaboradores as $colaboradorArea)
                <tr>
                    <th class="colabo">
                        {{$colaboradorArea->colaborador->candidato->nombre}}
                        {{$colaboradorArea->colaborador->candidato->apellido}}
                        <input type="number" name="colaborador_area_id[]" value="{{$colaboradorArea->id}}"
                            style="display: none">
                        <input type="number" name="semana_id" value="{{$semana->id}}" style="display: none">
                    </th>
                    @foreach($semana->responsabilidades as $responsabilidad)
                    <input type="number" name="responsabilidad_id[]" value="{{$responsabilidad->id}}"
                        style="display: none">
                    <td class="check" onclick="toggleCheck(this)">
                        <div>
                            <span></span>
                            <input type="number" name="cumplio[]" value="0" style="display: none">
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </form>
    </table>
    <div class="text-center btn-calificar">
        <a href="#" class="ladda-button btn btn-primary mr-5"
            onclick="document.getElementById('cumplioStore{{$index+1}}').submit();">Guardar
        </a>
        <a href="{{$comeBackUri}}" class="ladda-button btn btn-warning text-white" data-style="expand-left">Regresar</a>
    </div>
    @endif



    </section>

    @endforeach

    {{-- Mostrar errores --}}
    @if (session('InformeError'))
    <div id="alert-error" class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert"
        style="position: fixed; bottom: 30px; right: 20px; max-width: 90%; min-width: 250px; z-index: 1050; border-radius: 15px;">
        <div style="flex-grow: 1;">
            <strong>Error(es) en la Semana {{ session('current_semana_id') }}</strong>
            <ul>
                @foreach (session('InformeError') as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button onclick="deleteAlert('alert-error')" type="button" class="btn btn-outline-dark btn-sm"
            style="position: absolute; top: 5px; right: 5px;" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
    </div>
    @endif

    {{-- Mostrar advertencias --}}
    @if (session('InformeWarning'))
    <div id="alert-warning" class="alert alert-warning alert-dismissible fade show d-flex align-items-start"
        role="alert"
        style="position: fixed; bottom: 30px; right: 20px; max-width: 90%; min-width: 250px; z-index: 1050; border-radius: 15px; background-color: #fff3cd; color: #856404;">
        <div style="flex-grow: 1;">
            <strong>Advertencia para la Semana {{ session('current_semana_id') }}</strong>
            @foreach (session('InformeWarning') as $warning)
            <p>{{ $warning }}</p>
            @endforeach
        </div>
        <button onclick="deleteAlert('alert-warning')" type="button" class="btn btn-outline-dark btn-sm"
            style="position: absolute; top: 5px; right: 5px;" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
    </div>
    @endif

    @if (session('EvaluacionWarning'))
    <div id="alert-evaluacion-warning" class="alert alert-warning alert-dismissible fade show d-flex align-items-start"
        role="alert"
        style="position: fixed; bottom: 30px; right: 20px; max-width: 90%; min-width: 250px; z-index: 1050; border-radius: 15px; background-color: #fff3cd; color: #856404;">
        <div style="flex-grow: 1;">
            <strong>Advertencia para la Semana {{ session('current_semana_id') }}</strong>
            <p>{{ session('EvaluacionWarning') }}</p>
        </div>
        <button onclick="deleteAlert('alert-evaluacion-warning')" type="button" class="btn btn-outline-dark btn-sm"
            style="position: absolute; top: 5px; right: 5px;" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
    </div>
    @endif


    {{-- MODAL ERRORES --}}
    <script>
        const deleteAlert = (id) => {
            let alertError = document.getElementById(id);
            if (alertError) {
                alertError.remove();
            } else{
                console.error(`Elemento con ID '${id}' no encontrado.`);
            }
        }
    </script>

    {{-- tarjetas validaciones errores y warnings --}}
    <script>
        const deleteAlert = (id) => {
                let alertError = document.getElementById(id);
                if (alertError) {
                    alertError.remove();
                } else{
                    console.error(`Elemento con ID '${id}' no encontrado.`);
                }
        }
    </script>

    <script>
        var currentWeek = 1;
            var totalWeeks = {{ count($semanasMes) }};
            $("#semana"+currentWeek).css("display", "unset");
            updateNavigationButtons();

                function toggleCheck(cell) {
                    var span = cell.querySelector('div span');
                    var input = cell.querySelector('div input');
                    if (span.textContent === '✔️') {
                        span.textContent = '❌';
                        input.setAttribute('value', '0');
                    } else {
                        span.textContent = '✔️';
                        input.setAttribute('value', '1');
                    }
                }
                function actualizarSemana(offset) {
                    currentWeek += offset;
                    var semanaTitulo = document.querySelector('.semana');
                    semanaTitulo.textContent = "Semana: " + currentWeek;

                    var checkCells = document.querySelectorAll('.check');
                    checkCells.forEach(function(cell) {
                        cell.textContent = '';
                    });
                    var backButton = document.getElementById('backButton');
                    var nextButton = document.getElementById('nextButton');

                    if (currentWeek === 1) {
                        backButton.style.display = 'none';
                        nextButton.style.display = 'inline-block';
                    } else if (currentWeek === 2) {
                        backButton.style.display = 'inline-block';
                        nextButton.style.display = 'none';
                    } else {
                        backButton.style.display = 'inline-block';
                        nextButton.style.display = 'none';
                    }
                }
                function regresarSemana() {
                    if (currentWeek > 1) {
                        $(`#semana${currentWeek}`).css("display", "none");
                        currentWeek--;
                        $(`#semana${currentWeek}`).css("display", "unset");
                        updateNavigationButtons();
                    }
                }

                function avanzarSemana() {
                    if (currentWeek < totalWeeks) {
                        $(`#semana${currentWeek}`).css("display", "none");
                        currentWeek++;
                        $(`#semana${currentWeek}`).css("display", "unset");
                        updateNavigationButtons();
                    }
                }

                function updateNavigationButtons() {
                    for (var i = 1; i <= totalWeeks; i++) {
                        var backButton = $(`#backButton${i}`);
                        var nextButton = $(`#nextButton${i}`);

                        backButton.show();
                        nextButton.show();

                        if (currentWeek === 1) {
                            backButton.prop('disabled', true);
                        } else {
                            backButton.prop('disabled', false);
                        }

                        if (currentWeek === totalWeeks) {
                            nextButton.prop('disabled', true);
                        } else {
                            nextButton.prop('disabled', false);
                        }
                    }
                }

    </script>





    {{-- scripts modals --}}
    <script>
        function ocultarTodosLosModales() {
                $('.modal').hide();
            }

            function showModal(modalId) {
                ocultarTodosLosModales();
                const modal = document.getElementById(modalId);
                if (modal) {
                    $(modal).show();
                }
            }

            function hideModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('show');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.parentNode.removeChild(backdrop);
                    }
                }
            }

            function abrirModalCreacion(index) {
                hideModal('modal-form-' + index);
                showModal('modal-form-add-' + index);
            }

            function abrirModalEdicion(id, index) {
                hideModal('modal-form-' + index);
                showModal('modal-form-update-' + id);
            }

            function abrirModalVista(id, index) {
                hideModal('modal-form-' + index);
                showModal('modal-form-view-' + id);
            }

            const forzarCerrado = (modalId) => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.parentNode.removeChild(backdrop);
                    }
                }
            }

            function confirmDelete(informeId, index, year, mes, area_id) {
                forzarCerrado('modal-form-' + index);
                alertify.confirm(
                    "Confirmación de eliminación",
                    "¿Estás seguro de que deseas eliminar este informe? Esta acción es permanente.",
                    function() {
                        let form = document.createElement('form');
                        form.method = 'POST';

                        let routeTemplate = "<?php echo route('InformeSemanal.destroy', ':id'); ?>";
                        form.action = routeTemplate.replace(':id', informeId);

                        let csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = "{{ csrf_token() }}";

                        let methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        form.appendChild(csrfToken);
                        form.appendChild(methodField);

                        let inputYear = document.createElement('input');
                        inputYear.type = 'hidden';
                        inputYear.name = 'year';
                        inputYear.value = year;
                        form.appendChild(inputYear);

                        let inputMes = document.createElement('input');
                        inputMes.type = 'hidden';
                        inputMes.name = 'mes';
                        inputMes.value = mes;
                        form.appendChild(inputMes);

                        let inputArea = document.createElement('input');
                        inputArea.type = 'hidden';
                        inputArea.name = 'area_id';
                        inputArea.value = area_id;
                        form.appendChild(inputArea);

                        document.body.appendChild(form);
                        form.submit();
                    },
                    function() {
                        console.log('Eliminación cancelada');
                    }
                ).set('labels', {ok:'Eliminar', cancel:'Cancelar'});
            }
    </script>





    <!--===PRUEBAS===-->

    @if(session('error'))
    <div id="alert-error" class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert"
        style="position: fixed; bottom: 25px; right: 10px; z-index: 1050;">
        <div style="flex-grow: 1;">
            <strong>Error:</strong> {{ session('error') }}
        </div>
        <button onclick="deleteAlert('alert-error')" type="button" class="btn btn-outline-dark btn-xs"
            style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
    </div>
    @endif

    @if(session('success'))
    <div id="alert-success" class="alert alert-success alert-dismissible fade show d-flex align-items-start"
        role="alert" style="position: fixed; bottom: 25px; right: 10px; z-index: 1050;">
        <div style="flex-grow: 1;">
            <strong>Éxito:</strong> {{ session('success') }}
        </div>
        <button onclick="deleteAlert('alert-success')" type="button" class="btn btn-outline-dark btn-xs"
            style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close">
            <i class="fa fa-close"></i>
        </button>
    </div>
    @endif


    </div>
    </div>


</body>

</html>
