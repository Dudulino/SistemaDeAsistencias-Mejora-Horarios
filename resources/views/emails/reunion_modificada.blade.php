<body>
    <div class="logo-container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCrz_XKzKwCeiR3Te3FDc-zTIDtxgLMVE5CA&s"
            alt="J&P Perifericos Logo">
    </div>
    <div class="body-container">
        <div class="body-header">
            <h1>Reunión Actualizada</h1>
            
            <p>Estimado(a) <b>{{$colaborador->candidato->nombre.' '.$colaborador->candidato->apellido}}</b></p>
        </div>
        <div class="body-info">
            <p>Le informamos que la reunión a la que fue agendado(a) ha sido actualizada.</p>
            <p>Se le ha agendado para asistir a una reunión de disponibilidad {{$reunion->disponibilidad}}.</p>
            <p>La reunión se llevará a cabo en la fecha {{ \Carbon\Carbon::parse($reunion->fecha)->format('d/m/Y') }}</p>
            <p>A partir de las {{$reunion->hora_inicial}} horas hasta las {{$reunion->hora_final}} horas</p>
            @if($reunion->disponibilidad === 'Virtual')
                <p>La reunión se dará a cabo en el siguiente link:</p>
                <a target="blank" href="{{$reunion->url}}">{{$reunion->url}}</a>
            @elseif($reunion->disponibilidad === 'Presencial')
                <p>La reunión se dará a cabo en la siguiente dirección:</p>
                <p class="text-bold">{{$reunion->direccion}}</p>
            @endif
            <h2>¡¡No falte!!</h2>
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