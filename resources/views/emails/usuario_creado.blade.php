<body>
    <div class="logo-container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCrz_XKzKwCeiR3Te3FDc-zTIDtxgLMVE5CA&s"
            alt="J&P Perifericos Logo">
    </div>

    <div class="body-container">
        <div class="body-header">
            <h1>Hola</h1>
            <p>Estimado(a) <b>{{$usuario}}</b></p>
        </div>
        <div class="body-info">
            <div>
                <p>Le informamos que usted ha sido agregado a nuestro sistema como un usuario <span
                        class="text-bold">{{$tipo_usuario == 1 ? "Administrador" : "Colaborador"}}</span>.</p>
            </div>
            <div>
                <p>Sus credenciales son las siguentes:</p>
                <p><span class="text-bold">Email: </span>{{$email}}</p>
                <p><span class="text-bold">Contraseña: </span>{{$password}}</p>
            </div>
            <div>
                <p><span class="text-bold">Link al Sistema: </span><a href="http://servidorcorman.dyndns.org:290/SistemaDeAsistencias/public" target="BLANK">Ver Sistema</a></p>
            </div>
        </div>
    </div>
    <div class="footer">
        <hr>
        <p>J&P PERIFÉRICOS S.A.C</p>
    </div>

</body>


<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        flex-direction: column;
        gap: 15px;
        justify-content: center;
        align-items: center;

        width: 45%
    }

    .redirectButton {
        border: 2px solid #000;
        border-radius: 20px;
        text-decoration: none;
        color: #fff;
        font-weight: bold;
        padding: 10px 20px;
        background: #141892;
    }

    .redirectButton:hover {
        background: #2836d4;
    }

    .logo-container {
        width: 100%;
        text-align: center;
    }

    .body-container {
        display: flex;
        flex-direction: column;
        padding: 20px 10%;
        line-height: 20px;
        gap: 0px;
        padding-left: ;
    }

    .footer {
        text-align: center;
        width: 90%;
    }

    .footer>p {
        font-weight: bold;
    }

    .text-bold {
        font-weight: bold;
    }

    .text-center {
        text-align: center;
    }
</style>
