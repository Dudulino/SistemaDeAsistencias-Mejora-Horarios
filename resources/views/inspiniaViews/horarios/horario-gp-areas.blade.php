<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | Horario - Áreas</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

<div id="wrapper">
    @include('components.inspinia.side_nav_bar-inspinia')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>Horario General</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}">Inicio</a>
                </li>
                <li class="breadcrumb-item active">
                    <strong>Horario General</strong>
                </li>
            </ol>
        </div>
    </div>

    {{-- btn descargar excel --}}
    <div id="button-container" style="text-align: center; margin-top: 20px; display: flex; justify-content: end;">

    </div>

    <div class="wrapper wrapper-content">
        <div class="row animated fadeInDown">
            <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <h1 class="titulo">Horario General Presencial - Áreas</h1>
                    <br>
                    <div class="container">
                        <div class="visible-scrollbar" style="height:32em; overflow:auto;">
                            <div class="tablecontainer">
                                <table>
                                    <tr class="m1">
                                        <th class="hm">Hora / Área</th>
                                        <th id="A1">Domingo</th>
                                        <th id="A2">Lunes</th>
                                        <th id="A3">Martes</th>
                                        <th id="A4">Miércoles</th>
                                        <th id="A5">Jueves</th>
                                        <th id="A6">Viernes</th>
                                        <th id="A7">Sábado</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="celdas">
                                            <th rowspan="4" >8:30 am - 12:30 pm</th>
                                            <td id="c1"></td>
                                            <td id="c2"></td>
                                            <td id="c3"></td>
                                            <td id="c4"></td>
                                            <td id="c5"></td>
                                            <td id="c6"></td>
                                            <td id="c7"></td>
                                        </tr>
                                        <tr class="celdas">
                                            <td id="c8"></td>
                                            <td id="c9"></td>
                                            <td id="c10"></td>
                                            <td id="c11"></td>
                                            <td id="c12"></td>
                                            <td id="c13"></td>
                                            <td id="c14"></td>
                                        </tr>
                                        <tr class="celdas">
                                            <td id="c15"></td>
                                            <td id="c16"></td>
                                            <td id="c17"></td>
                                            <td id="c18"></td>
                                            <td id="c19"> </td>
                                            <td id="c20"> </td>
                                            <td id="c21"> </td>
                                        </tr>
                                        <tr class="celdas">
                                            <td id="c22"></td>
                                            <td id="c23"></td>
                                            <td id="c24"></td>
                                            <td id="c25"></td>
                                            <td id="c26"> </td>
                                            <td id="c27"> </td>
                                            <td id="c28"> </td>
                                        </tr>
                                        <tr>
                                            <th>12:30 pm - 2:00 pm</th>
                                            <td class="receso" colspan="7">RECESO</td>
                                        </tr>
                                        <tr class="celdas">
                                            <th rowspan="4" >2:00 pm - 6:00 pm</th>
                                            <td id="c29" ></td>
                                            <td id="c30" ></td>
                                            <td id="c31" > </td>
                                            <td id="c32" > </td>
                                            <td id="c33"> </td>
                                            <td id="c34"> </td>
                                            <td id="c35"> </td>
                                        </tr>
                                        <tr class="celdas">
                                            <td id="c36" ></td>
                                            <td id="c37" ></td>
                                            <td id="c38" ></td>
                                            <td id="c39" ></td>
                                            <td id="c40"> </td>
                                            <td id="c41"> </td>
                                            <td id="c42"> </td>
                                        </tr>
                                        <tr class="celdas">
                                            <td id="c43" ></td>
                                            <td id="c44" ></td>
                                            <td id="c45" ></td>
                                            <td id="c46" ></td>
                                            <td id="c47"></td>
                                            <td id="c48"></td>
                                            <td id="c49"></td>
                                        </tr>
                                        <tr class="celdas">
                                            <td id="c50" ></td>
                                            <td id="c51" ></td>
                                            <td id="c52" ></td>
                                            <td id="c53" ></td>
                                            <td id="c54"></td>
                                            <td id="c55"> </td>
                                            <td id="c56"> </td>
                                        </tr>
                                    </tbody>
                                </table >
                            </div>
                        </div>
                    </div>
                    <style>
                    .container {
                        max-width: 1400px; /* Ancho máximo para pantallas grandes */
                        width: 100%;      /* Ocupa el ancho disponible */
                        margin: 0 auto;
                        font-family: sans-serif;
                    }

                    h1 {
                        text-align: center;
                    }

                    .fecha {
                        text-align: center;
                        color: white;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        table-layout: fixed; /* Ayuda a que las columnas se comporten mejor */
                    }

                    th, td {
                        border: 1px solid black;
                        padding: 5px;
                        color: black;
                        height: 40px;
                    }

                    th {
                        text-align: center;

                    }
                    .m1{
                        background-color: #4e7bbf;
                        color: white;

                    }
                    .hm{
                        background-color: #4e7bbf;
                        width: 15%; /* Ancho relativo para la primera columna */
                        color: white;
                    }
                    .titulo{
                        font-weight: 600;
                    }
                    .receso{
                        background-color: #4e7bbf;
                        font-size: 25px;
                        font-weight: 600;
                        color: white;
                        height: 100px;
                    }
                    td {
                        text-align: center;
                    }
                    #A4{
                        background-color: #bbd5ff;

                    }
                    #A2{
                        background-color: #bbd5ff;
                    }
                    #A1{
                        background-color: #bbd5ff;
                    }
                    #A3{
                        background-color: #bbd5ff;
                    }

                    #A4{
                        background-color: #bbd5ff;
                    }

                    #A5{
                        background-color: #bbd5ff;
                    }

                    #A6{
                        background-color: #bbd5ff;
                    }

                    #A7{
                        background-color: #bbd5ff;
                    }
                    .celdas{
                        background-color: hsla(0, 0%, 100%, 0.884);
                        height: 40px;
                    }

                    .visible-scrollbar {
                        overflow: auto;
                        -webkit-overflow-scrolling: touch;
                    }
                    .visible-scrollbar::-webkit-scrollbar {
                        height: 10px;
                        width: 10px;
                    }
                    .visible-scrollbar::-webkit-scrollbar-track {
                        background: #f1f1f1;
                    }
                    .visible-scrollbar::-webkit-scrollbar-thumb {
                        background: #c1c1c1;
                        border-radius: 6px;
                    }
                    .visible-scrollbar {
                        scrollbar-width: 10em;
                        scrollbar-color: #c1c1c1 #f1f1f1;
                    }

                    div.tablecontainer {
                        overflow-x: auto;
                    }

                     @media (max-width: 1024px) {
                        .visible-scrollbar { max-width: 100%; overflow-x: auto; }
                        table { min-width: 900px; }
                        th, td { padding: 6px 8px; font-size: 13px; }
                        .hm { width: 140px; }
                        .receso { font-size: 20px; height: 80px; }
                    }

                    @media (max-width: 640px) {
                        .visible-scrollbar { max-width: 100%; overflow-x: auto; }
                        table { min-width: 800px; }
                        th, td { padding: 4px 6px; font-size: 12px; }
                        .hm { width: 120px; }
                        .receso { font-size: 16px; height: 60px; }
                    }
                    </style>

            </div>
            </div>
        </div>
        </div>
    </div>
    <div class="footer">
        <div class="float-right">
            10GB of <strong>250GB</strong> Free.
        </div>
        <div>
            <strong>Copyright</strong> Example Company &copy; 2014-2018
        </div>
    </div>

</div>
</div>

<!-- Mainly scripts -->
<!-- <script src="js/plugins/fullcalendar/moment.min.js"></script> -->
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="js/inspinia.js"></script>
<script src="js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI  -->
<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js"></script>

<!-- Full Calendar -->
<!-- <script src="js/plugins/fullcalendar/fullcalendar.min.js"></script> -->

<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. Obtener los datos desde la variable PHP
    const horariosAreas = <?php echo json_encode($horarios_presenciales_Asignados); ?>;

    // 2. Mapear los nombres de los días a un índice de columna
    const diasMap = {
        'Domingo': 0, 'Lunes': 1, 'Martes': 2, 'Miércoles': 3,
        'Jueves': 4, 'Viernes': 5, 'Sábado': 6
    };

    // 3. Agrupar los eventos por día y por bloque de tiempo (Mañana/Tarde)
    const eventosPorDiaBloque = {
        'Domingo': { manana: [], tarde: [] },
        'Lunes': { manana: [], tarde: [] },
        'Martes': { manana: [], tarde: [] },
        'Miércoles': { manana: [], tarde: [] },
        'Jueves': { manana: [], tarde: [] },
        'Viernes': { manana: [], tarde: [] },
        'Sábado': { manana: [], tarde: [] }
    };

    horariosAreas.forEach(evento => {
        const dia = evento.horario_modificado.dia;
        const horaInicial = parseInt(evento.horario_modificado.hora_inicial, 10);
        if (dia) {
            if (horaInicial >= 8 && horaInicial < 12) {
                eventosPorDiaBloque[dia].manana.push(evento);
            } else if (horaInicial >= 14 && horaInicial < 18) {
                eventosPorDiaBloque[dia].tarde.push(evento);
            }
        }
    });

    // 4. Iterar sobre cada día y bloque para colocar los eventos en las celdas
    Object.keys(eventosPorDiaBloque).forEach(dia => {
        const colIndex = diasMap[dia];

        // Llenar el bloque de la mañana (celdas c1 a c28)
        eventosPorDiaBloque[dia].manana.forEach((evento, index) => {
            if (index < 4) {
                const rowIndex = index;
                const cellId = (rowIndex * 7) + (colIndex + 1);
                const celda = document.getElementById(`c${cellId}`);
                if (celda) {
                    insertarAreaEnCelda(celda, evento);
                }
            }
        });

        // Llenar el bloque de la tarde (celdas c29 a c56)
        eventosPorDiaBloque[dia].tarde.forEach((evento, index) => {
            if (index < 4) {
                const rowIndex = index; // El índice de fila es relativo a su bloque (0-3)
                const cellId = 28 + (rowIndex * 7) + (colIndex + 1); // La segunda sección comienza después de c28
                const celda = document.getElementById(`c${cellId}`);
                if (celda) {
                    insertarAreaEnCelda(celda, evento);
                }
            }
        });
    });

    // Función para crear y añadir el elemento del área
    function insertarAreaEnCelda(celda, evento) {
        const nombreArea = evento.area.especializacion;
        const color = evento.area.color_hex;
        const areaId = evento.area.id;

        const areaDiv = document.createElement('div');
        areaDiv.textContent = nombreArea;
        areaDiv.style.backgroundColor = color;
        areaDiv.style.color = '#fff';

        areaDiv.style.padding = '5px';
        areaDiv.style.marginBottom = '2px';
        areaDiv.style.borderRadius = '6px';
        areaDiv.style.fontSize = '12px';
        areaDiv.style.cursor = 'pointer';
        areaDiv.style.whiteSpace = 'nowrap';
        areaDiv.style.overflow = 'hidden';
        areaDiv.style.textOverflow = 'ellipsis';

        areaDiv.addEventListener('click', function() {
            window.open(`{{ route('areas.getHorario', ':area_id') }}`.replace(':area_id', areaId), '_blank');
        });

        celda.appendChild(areaDiv);
    }
});
</script>

</body>


</html>
