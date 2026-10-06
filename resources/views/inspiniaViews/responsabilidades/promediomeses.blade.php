<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA | PROMEDIO MESES</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Promedio Meses</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('responsabilidades.index')}}">Responsabilidades</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Terminado</strong>
                    </li>
                </ol>
            </div>
        </div>

        <div class="wrapper wrapper-content">
            <div class="row animated fadeInDown">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-content">
                            <h1 class="titulo">PROMEDIO MESES</h1>
                            <br>
                            <div class="container">
                                <table>
                                    <thead>
                                        <tr class="m1">
                                            <th class="hm">Meses:</th>
                                            <th class="fecha" colspan="{{ count($responsabilidadesMeses)-2 }}">
                                                |
                                                @foreach($selectedMonths as $key => $mes)
                                                {{strtoupper($mes)}}
                                                |
                                                @endforeach
                                            </th>
                                            <th class="hm">Total Semanas: {{$totalSemanas}} </th>
                                            <th class="fecha" colspan="2">Del: {{$firstWeek->fecha_lunes}} Al:
                                                {{$lastWeek->fecha_lunes}}</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr class="m1">
                                            <th class="hm">Área:</th>
                                            <th colspan="{{ count($responsabilidadesMeses)+1 }}" id="Area">{{$area->especializacion}}</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr class="m1">
                                            <th class="hm">Responsabilidades / Colaboradores</th>
                                            @foreach($responsabilidadesMeses as $responsabilidad)
                                            <th colspan="1" id="colum">{{$responsabilidad['nombre']}}</th>
                                            @endforeach
                                            <th colspan="1" id="total">Total:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($colaboradoresMeses as $key => $colab)
                                        <tr class="celdas">
                                            <th id="name" rowspan="1">{{$colab['nombre']}}</th>
                                            @foreach($colab['promedio'] as $resp)
                                            <td>{{$resp}}</td>
                                            @endforeach

                                            <td>{{$colab['total']}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <style>
                                .container {

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
                                }

                                th,
                                td {
                                    border: 1px solid black;
                                    padding: 5px;
                                    color: black;
                                    height: 50px;
                                    width: 200px;
                                }

                                th {
                                    text-align: center;
                                    min-width: 100px;


                                }

                                .m1 {
                                    background-color: #4e7bbf;
                                    color: white;
                                    height: 40px;

                                }

                                .hm {
                                    background-color: #4e7bbf;
                                    width: 200px;
                                    color: white;
                                }

                                .titulo {
                                    font-weight: 600;
                                }

                                td {
                                    text-align: center;
                                }

                                #Area {
                                    color: black;
                                    background-color: #86eb95;
                                }

                                #colum {
                                    color: black;
                                    background-color: #a6d6f4;
                                }

                                #total {
                                    color: black;
                                    background-color: #dbad2c;
                                }

                                #name {
                                    color: black;
                                    background-color: #f3f3f3;
                                }

                                .celdas {

                                    height: 10px;
                                }
                            </style>
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