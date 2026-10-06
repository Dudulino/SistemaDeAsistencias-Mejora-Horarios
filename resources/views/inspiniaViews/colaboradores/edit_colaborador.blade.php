<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/colaboradores/edit-colab.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <title>Editar Colaborador</title>
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
                        <strong>Colaboradores</strong>
                    </li>
                </ol>
            </div>
        </div>


        <main class="main-colaborador-edit">
            <section class="section-colaborador">
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Advertencia!</strong> {{ session('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('colaboradorEdit.update', $candidato->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    @php
                        // Verifica si el campo editable de la tabla colaboradores es 0 (no editable)
                        $isEditable = $colaborador->editable == 1;
                    @endphp

                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $candidato->nombre) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellidos</label>
                        <input type="text" id="apellido" name="apellido" class="form-control" value="{{ old('apellido', $candidato->apellido) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('apellido')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="{{ old('correo', $candidato->correo) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('correo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="dni">DNI</label>
                        <input type="text" id="dni" name="dni" class="form-control" value="{{ old('dni', $candidato->dni) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('dni')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_senati">ID Senati</label>
                        <input type="number" id="id_senati" name="id_senati" class="form-control" value="{{ old('id_senati', $candidato->id_senati) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('id_senati')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular</label>
                        <input type="number" id="celular" name="celular" class="form-control" value="{{ old('celular', $candidato->celular) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('celular')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input
                            type="date"
                            class="form-control" name="fecha_nacimiento"
                            id="fecha_nacimiento"
                            value="{{ old('fecha_nacimiento', $candidato->fecha_nacimiento) }}"
                            {{ !$isEditable ? 'disabled' : '' }}
                        >
                    </div>

                    <div class="form-group">
                        <label>
                            <h5 class="m-t-none">Distrito:</h5>
                        </label>
                        <select class="form-control select2-distrito" name="distrito_id" id="distrito_id">
                            <option value="">Seleccione un distrito</option>
                            @foreach($distritos as $distrito)
                                <option value="{{ $distrito->id }}" 
                                    {{ isset($colaborador->candidato->distrito_id) && $colaborador->candidato->distrito_id == $distrito->id ? 'selected' : '' }}>
                                    {{ $distrito->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" class="form-control" value="{{ old('direccion', $candidato->direccion) }}" {{ !$isEditable ? 'disabled' : '' }}>
                        @error('direccion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sede_id">Sede</label>
                        <select id="sede_id" name="sede_id" class="form-control" {{ !$isEditable ? 'disabled' : '' }}>
                            @foreach ($sedes as $sede)
                                <option value="{{ $sede->id }}" {{ old('sede_id', $candidato->sede_id) == $sede->id ? 'selected' : '' }}>
                                    {{ $sede->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="carrera_id">Carrera</label>
                        <select id="carrera_id" name="carrera_id" class="form-control" {{ !$isEditable ? 'disabled' : '' }}>
                            @foreach ($carreras as $carrera)
                                <option value="{{ $carrera->id }}" {{ old('carrera_id', $candidato->carrera_id) == $carrera->id ? 'selected' : '' }}>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <h5 class="m-t-none">Ciclo:</h5>
                        </label>
                        <select name="ciclo_de_estudiante" class="form-control" {{ !$isEditable ? 'disabled' : '' }}>
                            @for($i = 4; $i <= 10; $i++)
                                <option value="{{ $i }}"
                                    {{ old('ciclo_de_estudiante', $candidato->ciclo_de_estudiante) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="especialista_id">Especialista</label>
                        <select id="especialista_id" name="especialista_id" class="form-control" {{ !$isEditable ? 'disabled' : '' }}>
                            <option value="" {{ old('especialista_id', optional($colaborador->especialista)->id) == '' ? 'selected' : '' }}>
                                Sin especialista
                            </option>
                            @foreach ($especialistas as $especialista)
                                <option value="{{ $especialista->id }}" {{ old('especialista_id', optional($colaborador->especialista)->id) == $especialista->id ? 'selected' : '' }}>
                                    {{ $especialista->nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label>
                            <h5 class="m-t-none">Actividades favoritas:</h5>
                        </label>
                        <select name="actividades_id[]" multiple class="form-control multiple_actividades_select">
                            @foreach ($actividades as $actividad)
                                <option value="{{ $actividad->id }}"
                                    @if(in_array($actividad->id, old('actividades_id', $colaborador->actividades->pluck('id')->toArray() ?? [])))
                                        selected
                                    @endif
                                >
                                    {{ $actividad->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    @if($isEditable)
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    @endif
                </form>
            </section>

        </main>

    </div>

    <script>
        $(document).ready(function() {
            $('.multiple_actividades_select').select2();
        });
    </script>

    {{-- script para select2 --}}
    <script>
        $(document).ready(function() {
            $('.select2-distrito').select2({
                placeholder: "Buscar distrito...",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>
