<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/responsabilidades/index.css') }}">
    <title>INSPINIA | RESPONSABILIDADES</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Responsabilidades</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Responsabilidades - Áreas</strong>
                    </li>
                </ol>
            </div>
        </div>

        @if(!empty($warning))
            <div class="alert alert-warning">
                <strong>Advertencia:</strong> {{ $warning }}
            </div>
        @endif



        <form method="GET" action="{{ route('buscar.responsabilidades') }}" class="d-flex form-content">
            <div class="search-container">
                <form method="GET" action="">
                    <input id="ls-buscar" class="search-input" type="text" name="buscar_responsabilidad"
                        placeholder="Buscar Responsabilidad..." autocomplete="off">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
                </form>
                {{-- <form>
                    <button id="limpiar" class="btn btn-outline-success my-2 my-sm-0" type="button">Limpiar</button>
                </form> --}}
            </div>
        </form>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                @foreach ($areas as $index => $area)
                    <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="ibox">
                            <div class="ibox-content product-box">
                                <div class="product-imitation" style="object-fit: cover; padding: 0px; height: 225px;" onclick="onClickArea('{{ $area->id }}')">
                                    <img src="{{ asset('storage/areas/' . $area->icono) }}" alt="" style="height: 100%; width: 100%; object-fit: cover"  class="img-cover">
                                </div>
                                <div class="product-desc">
                                    {{-- <button class="btn btn-outline btn-primary dim float-right"
                                        type="button"><span>ON</span></button> --}}
                                    <small class="text-muted">{{ $area->id }}</small>
                                    <a href="#" class="product-name">{{ $area->especializacion }}</a>
                                    <div class="small m-t-xs">
                                        {{ $area->descripcion }}
                                    </div>

                                    <div class="m-t text-righ flex-centered gap-20">
                                        @if ($countYears > 1)
                                            <form method="GET"
                                                action="{{ route('responsabilidades.years', $area->id) }}">
                                                <button class="btn btn-success" type="submit"><i
                                                        class="fa fa-paste"></i>
                                                    Años</button>
                                            </form>
                                        @endif
                                        <form method="GET"
                                            action="{{ route('responsabilidades.meses', ['year' => $currentYear, 'area_id' => $area->id]) }}">
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
            @if($hasPagination === true)
                <div class="row mb-5 mb-md-4">
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-start align-items-center gap-10 my-3">
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== 1)
                            <a href="{{ $areas->url(1) }}" class="btn btn-outline-dark rounded-5">
                                <i class="fa fa-arrow-circle-left"></i> Primero
                            </a>
                        @endif
                        @if($pageData->currentPage > 1)
                            <a href="{{$pageData->previousPageUrl}}" class="btn btn-outline-dark rounded-5">
                                <i class="fa fa-arrow-circle-left"></i> Anterior
                            </a>
                        @endif
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end align-items-center gap-10">
                        @if($pageData->currentPage < $pageData->lastPage)
                            <a href="{{ $pageData->nextPageUrl }}" class="btn btn-outline-dark rounded-5">
                                Siguiente <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endif
                        @if($pageData->lastPage > 2 && $pageData->currentPage !== $pageData->lastPage)
                            <a href="{{ $pageData->lastPageUrl }}" class="btn btn-outline-dark rounded-5">
                                Último <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </div>



        @include('components.inspinia.footer-inspinia')
    </div>
    </div>

<script>
    const buscadora = document.getElementById("ls-buscar");
    const btnLimpiar = document.getElementById("limpiar");

    function limpiarBuscador() {
        buscadora.value = "";
    }

    btnLimpiar.addEventListener("click", limpiarBuscador);
</script>
</body>

</html>
