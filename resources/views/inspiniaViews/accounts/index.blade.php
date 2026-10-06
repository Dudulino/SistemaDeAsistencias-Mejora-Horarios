<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CUENTAS</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading d-flex align-items-center ">
            <div class="col-lg-10">
                <h2>CUENTAS</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Cuentas</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2 ">
                <form action="{{route('accounts.create')}}">
                    <button type="submit" class="btn btn-success dim float-right">Agregar</button>
                </form>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            @if(session('error'))
            <div id="alert-error" class="alert alert-danger alert-dismissible fade show d-flex align-items-start"
                role="alert" style="position: relative;">
                <div style="flex-grow: 1;">
                    <strong>Error:</strong> {{ session('error') }}
                </div>
                <button onclick="deleteAlert('alert-error')" type="button" class="btn btn-outline-dark btn-xs"
                    style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i
                        class="fa fa-close"></i></button>
            </div>
            @endif

            @if(session('success'))
            <div id="alert-success" class="alert alert-success alert-dismissible fade show d-flex align-items-start"
                role="alert" style="position: relative;">
                <div style="flex-grow: 1;">
                    <strong>Éxito:</strong> {{ session('success') }}
                </div>
                <button onclick="deleteAlert('alert-success')" type="button" class="btn btn-outline-dark btn-xs"
                    style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i
                        class="fa fa-close"></i></button>
            </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Email</th>
                                            <th>Password</th>
                                            <th>Rol</th>
                                            <th>Estado</th>
                                            <th class="oculto">Editar</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr class="gradeX">
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->apellido }}</td>
                                                <td>{{ $user->email }}</td>
                                                <style>
                                                    /* Cambiar el tamaño y el color del scrollbar */
                                                    .overflow-auto::-webkit-scrollbar {
                                                        height: 8px;
                                                        /* Altura del scrollbar horizontal */
                                                    }

                                                    /* Color y estilo del fondo del scrollbar */
                                                    .overflow-auto::-webkit-scrollbar-track {
                                                        background: #f1f1f1;
                                                        /* Fondo del track */
                                                        border-radius: 8px;
                                                        /* Bordes redondeados */
                                                    }

                                                    /* Color y estilo del "thumb" (barra de desplazamiento) */
                                                    .overflow-auto::-webkit-scrollbar-thumb {
                                                        background: #222;
                                                        border-radius: 18px;
                                                    }
                                                </style>
                                                <td class="d-flex justify-content-between overflow-auto"><span
                                                        class="overflow-auto"
                                                        style="max-width: 200px; white-space: nowrap; overflow-x: auto; "
                                                        id="showPassword-{{$user->id}}" hidden>{{ $user->clave
                                                        }}</span><span
                                                        id="hidePassword-{{$user->id}}">*************</span><button
                                                        class="border-0 btn-outline-dark rounded"
                                                        onclick="showHidePassword({{$user->id}})"><i id="iconEyeTable-{{$user->id}}"
                                                            class="fa fa-eye"></i></button></td>
                                                <td class="changeToJefe" onclick="btnJefeArea({{ $user->id }}, '{{ $user->rol }}')">{{ $user->rol }}</td>
                                                <style>
                                                    .changeToJefe {
                                                        cursor: pointer;
                                                    }
                                                </style>
                                                <td>
                                                    <form method="POST"
                                                        action="{{route('accounts.activarInactivar', $user->id)}}">
                                                        @method('put')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{$user->estado == 1 ? 'primary' : 'danger'}}">@if($user->estado
                                                            == 1) Activado @else Inactivo @endif</button>
                                                    </form>
                                                </td>
                                                <td class="oculto">
                                                    <a data-toggle="modal" href="#modal-form-update{{$user->id}}"
                                                        class="btn btn-sm btn-success text-white">Editar</a>
                                                </td>
                                                <div id="modal-form-update{{$user->id}}" class="modal fade"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-custom">
                                                        <div style="min-width: 750px" class="modal-content">
                                                            <div
                                                                class="modal-header d-flex flex-column align-items-center justify-content-center">
                                                                <h2 class="font-bold">Editar Usuario</h2>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form method="POST"
                                                                    action="{{route('accounts.update', $user->id)}}">
                                                                    @method('put')
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Nombres:</label>
                                                                        <input class='form-control' name="name" required
                                                                            value="{{$user->name}}" type="text">
                                                                        @error('name'.$user->id)
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                        @enderror
                                                                        <div class="form-group">
                                                                            <label>Apellidos:</label>
                                                                            <input class='form-control' name="apellido"
                                                                                required value="{{$user->apellido}}"
                                                                                type="text">
                                                                            @error('apellido'.$user->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Email:</label>
                                                                            <input class='form-control' name="email"
                                                                                required value="{{$user->email}}"
                                                                                type="email">
                                                                            @error('email'.$user->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <style>
                                                                                .input-container {
                                                                                    position: relative;
                                                                                }

                                                                                .input-password {
                                                                                    padding-right: 30px;
                                                                                }

                                                                                .toggle-button {
                                                                                    position: absolute;
                                                                                    top: 50%;
                                                                                    right: 10px;
                                                                                    transform: translateY(-50%);
                                                                                    background: none;
                                                                                    border: none;
                                                                                    cursor: pointer;
                                                                                    font-size: 16px;
                                                                                }
                                                                            </style>
                                                                            <label>Contraseña:</label>
                                                                            <div class="input-container">
                                                                                <input class="form-control input-password"
                                                                                    id="password-{{$user->id}}" name="password"
                                                                                    type="password" value="{{$user->clave}}"
                                                                                    required />
                                                                                <button class="toggle-button"
                                                                                    onclick="toogleInput('password-{{$user->id}}', 'password-icon-{{$user->id}}')"
                                                                                    type="button"><i class="fa fa-eye"
                                                                                        id="password-icon-{{$user->id}}"></i></button>
                                                                            </div>
                                                                            @error('password'.$user->id)
                                                                            <span class="text-danger">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>
                                                                        @if($user->rol === 'Jefe de Área')
                                                                            <div class="form-group">
                                                                                <label>Áreas:</label>
                                                                                <select required
                                                                                    class='form-control multiple_areas_select'
                                                                                    multiple name="areas_id[]">
                                                                                    @foreach($areas as $area)
                                                                                    <option value="{{$area->id}}"
                                                                                        @foreach($user->areas as $areaJefe)
                                                                                        @if($areaJefe->id === $area->id)
                                                                                        selected
                                                                                        @endif
                                                                                        @endforeach
                                                                                        >

                                                                                        {{$area->especializacion}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        @endif
                                                                        <div class="d-flex justify-content-end">
                                                                            <button type="submit"
                                                                                class="btn btn-success">Editar</button>
                                                                        </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </tr>

                                            {{-- modal JefeArea --}}
                                            <div class="modal fade" id="myModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Cambiar a jefe</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p id="modalContent{{ $user->id }}"></p>

                                                            <div class="user">
                                                                <p>Nombre: {{ $user->name }}</p>
                                                                <p>Rol: {{ $user->rol }}</p>

                                                                @if (isset($areasUsuarios[$user->id]))
                                                                    <p>Área: {{ $areasUsuarios[$user->id]->especializacion }}</p>
                                                                @else
                                                                    <p>No se encontró área asociada.</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let errores = @json($errors->all());
            if(errores.length > 0){
                const UserId = @json(session('userError'));
                document.addEventListener('DOMContentLoaded', function() {
                    $(`#modal-form-update${UserId}`).modal('show');
                });
            }
        </script>


        <style>
            .select2-container.select2-container--default.select2-container--open {
                z-index: 9999 !important;
                width: 100% !important;
            }


            .select2-container {
                display: inline !important;
            }
        </style>


        @include('components.inspinia.footer-inspinia')
    </div>
    </div>
    <script src={{ asset('js/asistencia/cuentas/index.js') }}>

    </script>

    <script>
        $(document).ready(function() {
            $('.multiple_areas_select').select2();
        });
    </script>

    <script>

        function btnJefeArea(id, rol) {

            if(rol === "Administrador"){
                return;
            }

            $('#myModal' + id).modal('show');
        }

    </script>


</body>

</html>
