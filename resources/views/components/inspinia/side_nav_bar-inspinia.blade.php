<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="{{ asset('css/plugins/switchery/switchery.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{asset('css/plugins/select2/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.0.1/dist/css/multi-select-tag.css">

    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alertify.min.css') }}">
    <link href="{{ asset('css/plugins/chartist/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/inspinia.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/fullcalendar/fullcalendar.print.css') }}" rel='stylesheet' media='print'>

</head>

@php
    use App\Http\Controllers\FunctionHelperController;
    $userData = FunctionHelperController::getUserRol();
    $user = $userData['user'];
    $rol = '';
    if($userData['isColab']) $rol = 'Colaborador';
    if($userData['isBoss']) $rol = 'Jefe de Área';
    if($userData['isAdmin']) $rol = 'Administrador';
@endphp


<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" class="rounded-circle img-lg" src="{{asset('img/image.png')}}" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{$userData['user']['name']}}</span>
                        <span class="text-muted text-xs block">{{$rol}}<b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a class="dropdown-item" href="{{route('perfil.index')}}">Perfil</a></li>
                        @if($userData['isAdmin'])
                        <li><a class="dropdown-item" href="{{route('accounts.index')}}">Administrar Cuentas</a></li>
                        @endif
                        @if($userData['isColab'])
                            <li><a class="dropdown-item" href="{{ route('colaboradorEdit.edit', session('colaborador_id')) }}">Editar datos</a></li>
                        @endif


                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item"
                            href="javascript:void(0);" onclick="confirmLogout();">Cerrar Sesión</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    SDA
                </div>
            </li>
            <li>
                <a href="{{route('dashboard')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Inicio</span></a>
            </li>
            @if($userData['isAdmin'])
                <li>
                    <a href="#"><i class="fa fa-clock-o"></i> <span class="nav-label">Horarios
                            Generales</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><a href="{{route('horarios.getHorarioGeneral')}}">Presencial</a></li>
                        <li><a href="{{ route('asignacionHorarios.index') }}"><i class="fa fa-magic"></i> Asignación automática</a></li>
                        <li><a href="{{route('reuniones.getAll')}}">Reuniones Áreas</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{route('reunionesProgramadas.allReu')}}"><i class="fa fa-video-camera"></i> <span class="nav-label">Reu.
                            Programadas</span></a>
                </li>
                <li>
                    <a href="{{route('reportes.index')}}"><i class="fa fa-book"></i> <span class="nav-label">Reportes</span></a>
                </li>
                <li id="personalCont">
                    <a href="#"><i class="fa fa-group"></i> <span class="nav-label">Personal</span><span
                            class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li id="candidatos">
                            <a href="{{route('candidatos.index')}}">Candidatos</a>
                        </li>
                        <li id="colaboradores">
                            <a href="{{route('colaboradores.index')}}">Colaboradores</a>
                        </li>
                        <li>
                            <a href="{{route('caja.index')}}">Caja Chica</a>
                        </li>
                    </ul>

                </li>
                <li id="areas">
                    <a href="{{route('areas.index')}}"><i class="fa fa-tags"></i> <span class="nav-label">Áreas</span></a>
                </li>
            @endif

            {{-- admin y jefe de area --}}
            @if($userData['isAdmin'] || $userData['isBoss'])
                <li>
                    <a href="{{route('responsabilidades.index')}}"><i class="fa fa-list-alt"></i> <span
                            class="nav-label">Responsabilidades</span></a>
                </li>
            @endif

            {{-- jefe de area --}}
            @if($userData['isBoss'])
                <li>
                    <a href="{{ route('proyectos.index') }}">
                        <i class="fa fa-folder-open"></i> Proyectos
                    </a>
                </li>
            @endif

            {{-- colaborador y jefe de area --}}
            @if($userData['isColab'] || $userData['isBoss'])
                <li>
                    <a href="{{ route('asignacionHorarios.index') }}"><i class="fa fa-calendar-check-o"></i> <span class="nav-label">Mi disponibilidad</span></a>
                </li>
                <li>
                    <a href="{{ route('bibliotecaColab.index') }}"><i class="fa fa-list-alt"></i> <span
                            class="nav-label">Biblioteca</span></a>
                </li>
                {{-- en proceso --}}
                {{-- <li>
                    <a href="#"><i class="fa fa-list-alt"></i> <span
                            class="nav-label">Horarios</span></a>
                </li> --}}
            @endif

            @if($userData['isAdmin'])
                <li id="maquinas">
                    <a href="{{route('maquinas.index')}}"><i class="fa fa-desktop"></i> <span class="nav-label">Máquinas</span></a>
                </li>
                <li id="salones">
                    <a href="{{route('salones.index')}}"><i class="fa fa-address-card-o"></i> <span class="nav-label">Salones</span></a>
                </li>
                <li id="ajustes">
                    <a href="{{route('ajustes.index')}}"><i class="fa fa-cog"></i> <span class="nav-label">Ajustes</span></a>
                </li>
            @endif

            <li>
                <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                    @csrf
                </form>

                <a href="javascript:void(0);" onclick="confirmLogout();">
                    <i class="fa fa-sign-out"></i>
                    <span class="nav-label">Cerrar Sesión</span>
                </a>

            </li>
        </ul>

    </div>
</nav>

<div id="page-wrapper" class="gray-bg">
    <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i>
                </a>

            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a href="{{ route('cumplecolabs.index') }}">
                        <i class='bx bxs-cake' style="font-size: 25px"></i>
                            <span id="notificationsCountBirthdayBContainer" class="label label-primary badge-cumple" ></span>

                    </a>

                    <style>
                        .badge-cumple {
position: absolute;
top: 13px; /* Ajusta la posición arriba */
right: 176px;
width: 5px
line-height: 1;
}
                    </style>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i style="font-size: 20px" class="fa fa-bell "></i>
                        <span id="notificationsCountContainer" class="label label-primary" hidden>8</span>

                    </a>
                    <ul id="notificationsContainer" class="dropdown-menu dropdown-alerts max-height-scrollable">

                    </ul>
                </li>


                <li>

                    <a href="javascript:void(0);" onclick="confirmLogout();">
                        <i class="fa fa-sign-out"></i>Cerrar Sesión
                    </a>

                </li>
            </ul>

        </nav>
    </div>
    {{-- @if($userData['isAdmin'])
        @include('components.chatbot.chatbot') //Agregar el componente de chatbot si es creado
    @endif --}}
    @if($user['estado'] == 0)
        <script>
            console.log('baneado');
            document.getElementById('logoutForm').submit();
        </script>
    @endif
    <label id="notificationRoute" hidden>{{route('notificaciones')}}</label>
    <label id="userToken" hidden>{{json_encode(session('api_token'))}}</label>
    <style>
        .max-height-scrollable {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>

<script>
function confirmLogout() {
    Swal.fire({
        title: "¿Estás seguro de que deseas cerrar sesión?",
        text: "Puedes iniciar sesión nuevamente en cualquier momento.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Cerrar sesión",
        cancelButtonText: "Cancelar",
        customClass: {
            popup: 'swal-center-popup',
            title: 'swal-center-title',
            content: 'swal2-center-html-container',
            confirmButton: 'swal-center-confirm-button',
            cancelButton: 'swal-center-cancel-button'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutForm').submit();
        } else {
            Swal.fire({
                title: "Acción cancelada",
                icon: "info",
                customClass: {
                    popup: 'swal-center-popup',
                    title: 'swal-center-title',
                    content: 'swal2-html-container'
                }
            });
        }
    });
}

</script>

    <!-- Mainly scripts -->
    <script src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('js/alertify.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

    <script src="js/plugins/dataTables/datatables.min.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="{{ asset('js/inspinia.js') }}"></script>
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}"></script>


    <!-- jQuery UI  -->
    <script class="jQuery UI" src="{{ asset('js/plugins/jquery-ui/jquery-ui.min.js') }}"></script>


    <!-- iCheck -->
    <script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>

    <!-- Full Calendar -->
    <script src="{{ asset('js/plugins/fullcalendar/moment.min.js') }}"></script>
    <script src="{{ asset('js/plugins/fullcalendar/fullcalendar.min.js') }}"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <!-- Select2 -->
    <script src="{{asset('js/plugins/select2/select2.full.min.js')}} "></script>

    <!-- Chartist -->
    <script src="{{asset('js/plugins/chartist/chartist.min.js')}}"></script>

    <!--
    <script class="Flot" src="{{ asset('js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.spline.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.symbol.js') }}"></script>
    <script src="{{ asset('js/plugins/flot/jquery.flot.time.js') }}"></script>


    <script class="Peity" src="{{ asset('js/plugins/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('js/demo/peity-demo.js') }}"></script>





    <script class="Jvectormap" src="{{ asset('js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>


    <script class="EayPIE" src="{{ asset('js/plugins/easypiechart/jquery.easypiechart.js') }}"></script>


    <script class="Sparkline" src="{{ asset('js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>


    <script class="Sparkline demo data" src="{{ asset('js/demo/sparkline-demo.js') }}"></script>
    -->

    <script src="{{asset('js/InspiniaViewsJS/notifications.js')}}"></script>
    <script src="{{ asset('js/InspiniaViewsJS/notificacionBirthday.js') }}"></script>
