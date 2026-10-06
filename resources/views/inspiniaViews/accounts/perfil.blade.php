<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perfil</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading d-flex align-items-center ">
            <div class="col-lg-10">
                <h2>Perfil</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Perfil</strong>
                    </li>
                </ol>
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
                    
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Datos del Usuario</h5>
                        </div>
                        <div class="ibox-content">
                            <form method="POST" action="{{route('perfil.update')}}">
                                @method('put')
                                @csrf
                                <div class="form-group row">
                                    <label for="firstName" class="col-sm-2 col-form-label">Nombre</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name" placeholder="Nombre" value="{{ $userData['user']['name'] }}" required>
                                        @error('name')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="lastName" class="col-sm-2 col-form-label">Apellido</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="apellido" placeholder="Apellido" value="{{ $userData['user']['apellido'] }}" required>
                                        @error('apellido')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $userData['user']['email'] }}">
                                        @error('email')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Actualizar Contraseña</h5>
                        </div>
                        <div class="ibox-content">
                            <form method="POST" action="{{route('perfil.updatePassword')}}">
                                @method('put')
                                @csrf
                                <div class="form-group row">
                                    <label for="currentPassword" class="col-sm-2 col-form-label">Contraseña Actual</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="old_password" placeholder="Contraseña Actual" required>
                                        @error('old_password')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="newPassword" class="col-sm-2 col-form-label">Nueva Contraseña</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nueva Contraseña" required>
                                        @error('new_password')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <span class="text-danger" id="new_password_error" hidden></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirmPassword" class="col-sm-2 col-form-label">Confirmar Contraseña</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmar Contraseña" required>
                                        @error('confirm_password')
                                        <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <span class="text-danger" id="confirm_error" hidden></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button id="btnUpdatePassword" type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        @include('components.inspinia.footer-inspinia')
        </div>
    </div>

</body>

</html>