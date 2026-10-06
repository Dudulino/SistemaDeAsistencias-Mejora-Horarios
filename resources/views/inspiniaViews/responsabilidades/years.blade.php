<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INSPINIA | RESPONSABILIDADES MESES</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Años</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('responsabilidades.index')}}">Responsabilidades</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Años</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2 text-center">
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                @foreach ($Years as $year)
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="ibox">
                        <div class="ibox-content product-box">
                            <div class="product-desc text-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="#" class="product-name">{{ $year }}</a>
                                </div>
                                <div class="m-t text-righ">
                                    <button class="btn btn-primary btn-circle" type="button">
                                    </button>
                                    <a class="" href="">Activo</a>
                                </div>
                                <div class="m-t text-righ">
                                    <form method="GET"
                                        action="{{ route('responsabilidades.meses', ['year' => $year, 'area_id' => $area_id]) }}">
                                        <button class="btn btn-success" type="submit"><i class="fa fa-paste"></i>
                                            Meses</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @include('components.inspinia.footer-inspinia')
    </div>

    <script>
        function updateSelectedMonths() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        const selectedMonths = Array.from(checkboxes).map(cb => cb.value);
        document.getElementById('selected-months').value = JSON.stringify(selectedMonths);

        const button = document.getElementById('btn-getMonthsProm');
        if (selectedMonths.length > 0) {
            button.disabled = false;
            button.classList.remove('disabled');
        } else {
            button.disabled = true;
            button.classList.add('disabled');
        }
        }

        function submitAverageForm() {
            updateSelectedMonths();
            document.getElementById('average-form').submit();
        }
    </script>

</body>

</html>