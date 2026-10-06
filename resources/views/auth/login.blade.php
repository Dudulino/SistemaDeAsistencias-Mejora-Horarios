<!-- Session Status -->
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Login</title>

    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('font-awesome/css/font-awesome.css')}}"> --}}

    {{-- <link rel="stylesheet" href="{{asset('css/animate.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    {{-- <link rel="stylesheet" href="{{asset('css/alertify.min.css')}}"> --}}
    {{-- <link href="{{asset('css/inspinia.css')}}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="{{asset('css/inspinia.css')}}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-center p-0">
        <div class="row w-100 h-100 shadow  overflow-hidden gray-cont">

            <div class="col-6 p-0">
                <img src="http://jypsac.dyndns.org:190/facturacion_20545122520/public/archivos/imagenes/leonosofts.jpg"  class="img-fluid h-100 w-100 img-background">
            </div>

            <div class="col-6 d-flex p-0 justify-content-center align-items-center">
                <form action="{{ route('login') }}" method="POST" id="form">
                    @csrf
                    <div id="form-body">
                        <div id="welcome-lines">
                            <h1 id="welcome-line-1" style="font-size: 4em; color: #fff;">J&P SAC</h1>
                            <div id="welcome-line-2">Sistema de Asistencia</div>
                        </div>
                            <div class="" id="input-area">
                                <div id="cuadro">
                                    <label><i class="fa fa-envelope"></i>
                                        CORREO
                                    </label>
                                    <div id="form-control">
                                        <input type="value" class="text-white" required="" name="email" autocomplete="off">
                                    </div>
                                </div>
                                <div id="cuadro">
                                    <label><i class="fa fa-lock"></i>
                                        CONTRASEÑA
                                    </label>
                                    <div id="form-control">
                                        <input type="password" class="text-white" required="" name="password" id="password">
                                        <span
                                            onclick="togglePassword()"
                                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer; color:white;">
                                            <i id="eye-icon" class="fa fa-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>

                            </div>

                        <div id="form-recuerdame" class="d-flex justify-content-center gap-4 align-items-center mb-2">
                            <span class="text-white">Recuérdame</span>
                            <label class="switch">
                                <input class="toggle" type="checkbox" checked="" name="remember">
                                <span class="slider"></span>
                                <span class="card-side"></span>
                            </label>
                        </div>

                        <div class="d-flex justify-content-center" id="submit-button-cvr ">
                            <button type="submit" class="button">
                                <p>Ingresar</p>
                              </button>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 text-center" id="error-message"
                                style="border-radius: 10px;">
                                @foreach ($errors->all() as $error)
                                    <p class="mb-0">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </form>
            </div>


        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
            function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }

    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.querySelector('input[name="password"]');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>

</html>
