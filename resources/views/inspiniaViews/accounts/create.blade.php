<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Incluir la librería Choices.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <title>Crear Cuenta</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading d-flex align-items-center ">
            <div class="col-lg-10">
                <h2>CREAR CUENTA</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('accounts.index')}}">Cuentas</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Crear Cuenta</strong>
                    </li>
                </ol>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            @error('colaborador_id')
            <div id="alert-colaborador_id-error"
                class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert"
                style="position: relative;">
                <div style="flex-grow: 1;">
                    <strong>Error:</strong> {{ $message }}
                </div>
                <button onclick="deleteAlert('alert-colaborador_id-error')" type="button"
                    class="btn btn-outline-dark btn-xs" style="position: absolute; top: 10px; right: 10px;"
                    data-bs-dismiss="alert" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            @enderror
            @error('areas_id')
            <div id="alert-areas_id-error"
                class="alert alert-danger alert-dismissible fade show d-flex align-items-start" role="alert"
                style="position: relative;">
                <div style="flex-grow: 1;">
                    <strong>Error:</strong> {{ $message }}
                </div>
                <button onclick="deleteAlert('alert-areas_id-error')" type="button" class="btn btn-outline-dark btn-xs"
                    style="position: absolute; top: 10px; right: 10px;" data-bs-dismiss="alert" aria-label="Close"><i
                        class="fa fa-close"></i></button>
            </div>
            @enderror
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
            <div class="ibox">
                <div class="ibox-content d-flex flex-column gap-5">
                    <div class="d-flex justify-content-between">
                        <h2>Agregar Nuevo Usuario</h2>
                        <button id="btnModalColaboradores" type="button" href="#modalColaboradores"
                            class="btn btn-secondary btn-sm text-white d-flex align-items-center" data-toggle="modal">Crear a base de colaborador</button>
                    </div>
                    <div>
                        <form method="POST" action="{{route('accounts.store')}}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Tipo de Usuario</label>
                                        <select onchange="handleTypeChange()" class="form-control" name="type"
                                            id="selectUserType">
                                            <option value="1">Administrador</option>
                                        </select>
                                        @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Correo</label>
                                        <input onchange="verifyCorrectInputs()" type="email" id="email" name="email"
                                            class="form-control" required />
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group">
                                        <label>Contraseña</label>
                                        <div class="input-container">
                                            <input class="form-control input-password" onchange="verifyCorrectInputs()"
                                                id="password" name="password" type="password" required />
                                            <button class="toggle-button"
                                                onclick="toogleInput('password', 'password-icon')" type="button"><i
                                                    class="fa fa-eye" id="password-icon"></i></button>
                                        </div>
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}
                                    <div class="form-group" id="areasJefe">

                                    </div>
                                    <div class="form-group" id="colabInputCont">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input onchange="verifyCorrectInputs()" name="name" id="name" type="text"
                                            class="form-control" required />
                                        @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Apellido</label>
                                        <input onchange="verifyCorrectInputs()" name="apellido" id="apellido"
                                            type="text" class="form-control" required />
                                        @error('apellido')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group">
                                        <label>Confirmar Contraseña</label>
                                        <div class="input-container">
                                            <input class="form-control input-password" onchange="verifySamePassword()"
                                                id="confirm_password" name="confirm_password" type="password"
                                                required />
                                            <button class="toggle-button"
                                                onclick="toogleInput('confirm_password', 'confirm_password-icon')"
                                                type="button"><i id="confirm_password-icon"
                                                    class="fa fa-eye"></i></button>
                                        </div>
                                        @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <span id="errorMessage" class="text-danger" hidden></span>
                                    </div> --}}
                                    <div class="form-group d-flex justify-content-end">
                                        <button class="btn btn-primary" id="submitButton" disabled>
                                            Asignar rol administrador</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="modal fade" id="modalColaboradores" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalColaboradoresLabel">Selecciona a un colaborador</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body d-flex flex-column gap-20">
                                <div class="form-group">
                                    <label>Buscar colaborador:</label>
                                    <select class="form-control" id="colaboradorSelectedId">
                                        <option value="" disabled selected>Busca un colaborador</option>
                                        @foreach($colaboradores as $colaborador)
                                            <option value="{{$colaborador->id}}"
                                                    data-email="{{$colaborador->candidato->correo ?? ''}}"
                                                    data-nombre="{{$colaborador->candidato->nombre}}"
                                                    data-apellido="{{$colaborador->candidato->apellido}}">
                                                {{$colaborador->candidato->nombre}} {{$colaborador->candidato->apellido}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button onclick="handleColabSelect()" class="btn btn-info" id="btnSelectColab" disabled>
                                        Seleccionar
                                    </button>
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

</body>
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



<script>
    const deleteAlert = (id) => {
        let alertError = document.getElementById(id);
        if (alertError) {
            alertError.remove();
        }
    }

    let cacheEmail = '';
    let cacheNombre = '';
    let cacheApellido = ''
    let cacheColabId = null;
    let cacheAreas = [];

    const handleColabSelect = () => {
        const colaboradorSelect = document.getElementById('colaboradorSelectedId');
        const selectedColaboradorId = colaboradorSelect.value;

        if (!selectedColaboradorId) {
            alert('Por favor selecciona un colaborador');
            return;
        }

        const selectedOption = colaboradorSelect.options[colaboradorSelect.selectedIndex];

        const nombre = selectedOption.dataset.nombre || '';
        const apellido = selectedOption.dataset.apellido || '';
        const email = selectedOption.dataset.email || '';

        document.getElementById('email').value = email;
        document.getElementById('name').value = nombre;
        document.getElementById('apellido').value = apellido;

        document.getElementById('email').removeAttribute('readonly');
        document.getElementById('name').removeAttribute('readonly');
        document.getElementById('apellido').removeAttribute('readonly');

        cacheEmail = email;
        cacheNombre = nombre;
        cacheApellido = apellido;
        cacheColabId = selectedColaboradorId;

        // Mantener como administrador
        const selectUserType = document.getElementById('selectUserType');
        selectUserType.value = '1';

        handleTypeChange();

        $('#modalColaboradores').modal('hide');

        verifyCorrectInputs();

        setTimeout(() => {
            if (window.colaboradorChoices) {
                window.colaboradorChoices.setChoiceByValue('');
            }
            document.getElementById('btnSelectColab').disabled = true;
        }, 500);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('colaboradorSelectedId');
        const btnSelect = document.getElementById('btnSelectColab');

        window.colaboradorChoices = new Choices(selectElement, {
            searchEnabled: true,
            itemSelectText: '',
            noResultsText: 'No se encontraron colaboradores',
            placeholder: true,
            placeholderValue: 'Busca un colaborador'
        });

        selectElement.addEventListener('change', function(event) {
            const selectedValue = event.target.value;
            btnSelect.disabled = !selectedValue;
        });

        $('#modalColaboradores').on('show.bs.modal', function () {
            if (window.colaboradorChoices) {
                window.colaboradorChoices.setChoiceByValue('');
            }
            btnSelect.disabled = true;
        });

        verifyCorrectInputs();
    });

    const handleTypeChange = () => {
        const selectType = document.getElementById('selectUserType');
        const btnColabs = document.getElementById('btnModalColaboradores');
        const selectedValue = selectType.value;

        let email = document.getElementById('email');
        let name = document.getElementById('name');
        let apellido = document.getElementById('apellido');

        if(selectedValue == 1){
            btnColabs.disabled = false;

            if(cacheEmail || cacheNombre || cacheApellido) {
                email.value = cacheEmail;
                name.value = cacheNombre;
                apellido.value = cacheApellido;
            }

            if(typeof renderAreas === 'function') {
                renderAreas();
            }

            if(cacheAreas.length > 0){
                let selectAreas = document.getElementById('selectAreas');
                if(selectAreas) {
                    Array.from(selectAreas.options).forEach(option => {
                        option.selected = false;
                    });

                    Array.from(selectAreas.options).forEach(option => {
                        cacheAreas.forEach(areaJefe => {
                            if(areaJefe.id == option.value) option.selected = true;
                        });
                    });

                    if(typeof $ !== 'undefined' && $('.multiple_areas_select').length) {
                        $('.multiple_areas_select').trigger('change');
                    }
                }
            }

            if(cacheColabId != null && typeof renderColabInput === 'function') {
                renderColabInput(cacheColabId);
            }
        }

        verifyCorrectInputs();
    }

    const verifyCorrectInputs = () => {
        const submitButton = document.getElementById('submitButton');
        const email = document.getElementById('email').value;
        const name = document.getElementById('name').value;
        const apellido = document.getElementById('apellido').value;
        const selectAreas = document.getElementById('selectAreas');

        console.log('Verificando inputs:', {
            email: email,
            name: name,
            apellido: apellido
        });

        if(email != '' && name != '' && apellido != '') {
            if(selectAreas != null){
                // verificar que no esté vacío
                const selectedOptions = Array.from(selectAreas.selectedOptions)
                if(selectedOptions.length > 0) {
                    submitButton.disabled = false
                } else{
                    submitButton.disabled = true
                }
            } else{
                submitButton.disabled = false
            }
        } else{
            submitButton.disabled = true
        }
    }
</script>
</html>
