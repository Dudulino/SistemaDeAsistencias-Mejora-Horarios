<body>
    <div class="logo-container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCrz_XKzKwCeiR3Te3FDc-zTIDtxgLMVE5CA&s"
            alt="J&P Perifericos Logo">
    </div>
    <div class="body-container">
        <div class="body-header">
            <h1>Aviso Reunión</h1>
            
            <p>Estimado(a) <b>{{$colaborador->candidato->nombre.' '.$colaborador->candidato->apellido}}</b></p>
        </div>
        <div class="body-info">
            <p>Le informamos que fue removido de la reunión a la que fue agendado(a).</p>
            <p>Le avisamos que ya no es preciso que asista a la reunión del día {{ \Carbon\Carbon::parse($reunion->fecha)->format('d/m/Y') }} 
                de {{$reunion->hora_inicial}} a {{$reunion->hora_final}}
            </p>
            <p>Lamentamos el inconveniente causado.</p>
            <p>Gracias por su comprensión.</p>
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
    }

    .logo-container {
        width: 100%;
        text-align: center;
    }

    .body-container {
        padding-inline: 10%;
        display: flex;
        flex-direction: column;
        gap: 0px;
    }

    .footer {
        text-align: center;
    }

    .footer>p {
        font-weight: bold;
    }

    .text-bold{
        font-weight: bold;
    }
</style>