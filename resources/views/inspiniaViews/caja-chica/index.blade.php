<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/caja-chica/index.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 para confirmaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Editar Colaborador</title>
</head>

<body>
    <div id="wrapper">
        @include('components.inspinia.side_nav_bar-inspinia')
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-3">
                <h2>Caja chica</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span>Personal</span>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Caja Chica</strong>
                    </li>
                </ol>
            </div>
        </div>

        <main class="main-caja">
            <section class="section-caja m-3">
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Advertencia!</strong> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>¡Éxito!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </section>

            <div class="transaccion-content m-3">
                <div class="saldo-content col-2 d-flex" readonly>
                    <label class="saldo-text">Saldo Actual:</label>
                    <label class="saldo-text"> S/{{ $saldoActual->saldo_actual ?? 0 }} </label>
                </div>

                <form id="filtrar-form">
                    <div class="filter-fecha-caja-content">
                        <div>
                            <label for="fecha_inicio">Fecha Inicio:</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label for="fecha_fin">Fecha Fin:</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" required>
                        </div>

                        <div class="btn-filter-content">
                            <button type="submit" class="btn-filter-montos btn-success">Filtrar</button>
                        </div>
                    </div>
                </form>

                <div class="total-ingresos-egresos-content row">
                    <div class="text-center total-in-content">
                        <h3>Total de Ingresos: S/<span id="total-ingresos">0</span></h3>
                    </div>

                    <div class="text-center total-e-content">
                        <h3>Total de Egresos: S/<span id="total-egresos">0</span></h3>
                    </div>
                </div>

                <div class="btns-transaccion-content">
                    @if($cajaAbierta)
                        <button class="btn btn-danger" onclick="cerrarCaja()">Cerrar Caja</button>
                        <!-- CORREGIDO: Bootstrap 5 sintaxis -->
                        <button type="button" class="btn btn-primary btn-add-registro" data-bs-toggle="modal" data-bs-target="#transaccionModal">
                            Agregar
                        </button>
                    @else
                        <button onclick="abrirCaja()" class="btn btn-primary">Abrir Caja</button>
                    @endif
                </div> 
            </div>

            {{-- modal registro transaccion --}}
            <div class="modal fade" id="transaccionModal" tabindex="-1" aria-labelledby="transaccionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="transaccionModalLabel">Pagos Depósito-Caja</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <style>
                            #transaccionModal .modal-body {
                                max-height: 80vh;
                                overflow-y: auto;
                            }
                            #transaccionModal .modal-body::-webkit-scrollbar {
                                width: 8px;
                                background: #f1f1f1;
                                border-radius: 4px;
                            }
                        </style>
                        <form method="POST" action="{{ route('caja.registroTransaccion') }}">
                            @csrf
                            <div class="registro-content">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="fecha">Fecha:</label>
                                        <input type="date" class="form-control" name="fecha" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="nombres">Nombres:</label>
                                        <div class="d-flex">
                                            <input type="text" name="nombres" id="nombres" class="form-control">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuarios">
                                                ...
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="dni">DNI:</label>
                                        <input type="number" name="dni" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="descripcion">Descripción:</label>
                                        <input type="text" name="descripcion" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="tipo_transaccion">Tipo de Transacción:</label>
                                        <select name="tipo_transaccion_id" id="tipo_transaccion" class="form-control">
                                            <option value="selected">Seleccionar tipo</option>
                                            <option value="1">Depósito</option>
                                            <option value="2">Caja</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label" for="monto">Monto:</label>
                                        <div class="text-soles">
                                            <h3>S/</h3>
                                            <input type="number" name="monto" class="form-control" step="0.01" required>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label"><b>Observaciones:</b></label>
                                        <textarea class="form-control observaciones" name="observaciones"></textarea>
                                    </div>
                                </div>

                                <div class="btn-registro-content">
                                    <button type="submit" class="btn btn-primary">Registrar Transacción</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- modal seleccion usuario --}}
            <div class="modal fade" id="modalUsuarios" tabindex="-1" aria-labelledby="modalUsuariosLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalUsuariosLabel">Seleccionar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="buscarUsuario" class="form-control mb-3" placeholder="Buscar usuario...">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nombres</th>
                                        <th>Correo</th>
                                        <th>DNI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $index => $user)
                                        <tr class="seleccionar-usuario usuario-fila {{ $index < count($users) - 3 ? 'd-none' : '' }}"
                                            data-nombre="{{ $user->name }} {{ $user->apellido }}"
                                            data-dni="{{ $user->dni }}"
                                            data-filtro="{{ strtolower($user->name . ' ' . $user->apellido . ' ' . $user->email . ' ' . $user->dni) }}"
                                            style="cursor: pointer;">
                                            <td>{{ $user->name }} {{ $user->apellido }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->dni }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="noResultados" class="d-none text-center">
                                <p>No se encontraron usuarios</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" cellpadding="10" cellspacing="0" id="tabla-transacciones">
                <thead>
                    <tr>
                        <th>NRO. PAGO</th>
                        <th>FECHA</th>
                        <th>DNI</th>
                        <th>NOMBRES</th>
                        <th>TIPO</th>
                        <th>INGRESOS</th>
                        <th>EGRESOS</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($depositos as $deposito)
                        <tr>
                            <td>{{ str_pad($deposito->nro_pago, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $deposito->fecha}}</td>
                            <td>{{ $deposito->dni }}</td>
                            <td>{{ $deposito->nombres }}</td>
                            <td>{{ $deposito->tipo_transaccion->descripcion }}</td>
                            <td>{{ $deposito->monto}} </td>
                            <td>0</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" disabled>Pagado</button>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#verdepositoModal{{ $deposito->id }}"
                                >
                                    Ver
                                </button>
                            </td>
                        </tr>

                        {{-- modal ver transaccion deposito --}}
                        <div class="modal fade" id="verdepositoModal{{ $deposito->id }}" tabindex="-1" role="dialog" aria-labelledby="verdepositoModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="pagoModalLabel{{ $deposito->id }}">Pago-Depósito</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                        <div class="registro-content">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="fecha">Fecha:</label>
                                                    <input type="date" class="form-control" value="{{ $deposito->fecha ?? 'Pendiente' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="nombres">Nombres:</label>
                                                    <input type="text" class="form-control" value="{{ $deposito->nombres }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="dni">DNI:</label>
                                                    <input type="number" class="form-control" value="{{ $deposito->dni }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="descripcion">Descripción:</label>
                                                    <input type="text" class="form-control" value="{{ $deposito->descripcion }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="tipo_transaccion">Tipo de Transacción:</label>
                                                    <input type="text" class="form-control" value={{ $deposito->tipo_transaccion->descripcion }} readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label class="form-label" for="monto">Monto:</label>
                                                    <div class="text-soles">
                                                        <h3>S/</h3>
                                                        <input type="number" class="form-control" value="{{ $deposito->monto }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <label class="form-label"><b>Observaciones:</b></label>
                                                    <textarea class="form-control observaciones" readonly>{{ $deposito->observaciones}}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($cajas as $caja)
                        <tr>
                            <td>{{ str_pad($caja->nro_pago, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $caja->fecha ?? 'Pendiente' }}</td>
                            <td>{{ $caja->dni }}</td>
                            <td>{{ $caja->nombres }}</td>
                            <td>{{ $caja->tipo_transaccion->descripcion }}</td>
                            <td>0</td>
                            <td>{{ $caja->monto}}</td>
                            <td>
                                <button class="btn btn-secondary btn-sm" disabled>Pagado</button>
                                <button class="btn btn-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#vercajaModal{{ $caja->id }}"
                                >
                                    Ver
                                </button>
                            </td>
                        </tr>

                        {{-- ver transaccion caja --}}
                        <div class="modal fade" id="vercajaModal{{ $caja->id }}" tabindex="-1" role="dialog" aria-labelledby="vercajaModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="pagoModalLabel{{ $caja->id }}">Pago Caja</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                        <div class="registro-content">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label" for="fecha">Fecha:</label>
                                                    <input type="date" class="form-control" value="{{ $caja->fecha ?? 'Pendiente' }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="nombres">Nombres:</label>
                                                    <input type="text" class="form-control" value="{{ $caja->nombres }}" readonly>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label" for="dni">DNI:</label>
                                                    <input type="number" class="form-control" value="{{ $caja->dni }}" readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="descripcion">Descripción:</label>
                                                    <input type="text" class="form-control" value="{{ $caja->descripcion }}" readonly>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="tipo_transaccion">Tipo de Transacción:</label>
                                                    <input type="text" class="form-control" value={{ $caja->tipo_transaccion->descripcion }} readonly>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label class="form-label" for="monto">Monto:</label>
                                                    <div class="text-soles">
                                                        <h3>S/</h3>
                                                        <input type="number" class="form-control" value="{{ $caja->monto }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <label class="form-label"><b>Observaciones:</b></label>
                                                    <textarea class="form-control observaciones" readonly>{{ $caja->observaciones}}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                    @foreach($colaboradores as $colaborador)
                        <tr>
                            <td>
                                @isset($colaborador->transaccion)
                                    {{ str_pad($colaborador->transaccion->nro_pago, 4, '0', STR_PAD_LEFT) }}
                                @else
                                    N/A
                                @endisset
                            </td>
                            <td>
                                @if ($colaborador->transaccion && $colaborador->transaccion->fecha)
                                    {{ $colaborador->transaccion->fecha }}
                                @else
                                    Pendiente
                                @endif
                            </td>

                            <td>{{ $colaborador->candidato->dni ?? 'Sin DNI'}}</td>
                            <td>{{ $colaborador->candidato->nombre . " " . $colaborador->candidato->apellido }}</td>
                            <td>Colaborador</td>
                            <td>0</td>
                            <td>
                                @if ($colaborador->anulado)
                                    {{ $colaborador->transaccion->monto ?? 0 }}
                                @else
                                    {{ $colaborador->total_monto }}
                                @endif
                            </td>

                            <td>
                                @if($colaborador->anulado)
                                    <button class="btn btn-secondary btn-sm" disabled>Anulado</button>
                                @elseif($colaborador->pagado)
                                    <button class="btn btn-secondary btn-sm" disabled>Pagado</button>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#verpagoModal{{ $colaborador->id }}"
                                            data-descripcion="{{ implode(' - ', $colaborador->pago_colaborador->pluck('descripcion')->toArray()) }}">
                                        Ver
                                    </button>
                                @else
                                    <div class="btn-">
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#pagoModal{{ $colaborador->id }}"
                                            data-descripcion="{{ implode(' - ', $colaborador->pago_colaborador->pluck('descripcion')->toArray()) }}">
                                            Pagar
                                        </button>
                                            <button
                                                class="btn btn-danger btn-sm"
                                                type="submit"
                                                onclick="confirmAnular({{ $colaborador->id }})"
                                            >
                                                Anular
                                            </button>
                                   </div>
                                @endif
                            </td>
                        </tr>

                        {{-- modal ver transaccion colaborador --}}
                        <div class="modal fade" id="verpagoModal{{ $colaborador->id }}" tabindex="-1"
                            aria-labelledby="verpagoModalLabel{{ $colaborador->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="pagoModalLabel{{ $colaborador->id }}">Pago Colaborador</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">

                                            <div class="transaccion-colab-content">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Fecha:</b></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $colaborador->transaccion ? $colaborador->transaccion->fecha : 'Pendiente' }}"
                                                            readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Nombres:</b></label>
                                                        <input type="text" class="form-control" value="{{ $colaborador->candidato->nombre . ' ' . $colaborador->candidato->apellido }}" readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>DNI:</b></label>
                                                        <input type="number" class="form-control" value="{{ $colaborador->candidato->dni }}" readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label"><b>Descripción:</b></label>
                                                        <input class="form-control descripcion-pago" type="text" value="{{ implode(' - ', $colaborador->pago_colaborador->pluck('descripcion')->toArray()) }}" readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Tipo:</b></label>
                                                        <input class="form-control descripcion-pago" type="text" value="Colaborador" readonly>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Método de Pago:</b></label>
                                                        <input type="text" class="form-control" value="{{ $colaborador->transaccion_detalle->metodo_pago ?? '...'}}" readonly>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <label class="form-label"><b>Nro. Operación:</b></label>
                                                        <input type="text" class="form-control"
                                                        value="{{ $colaborador->transaccion_detalle ? $colaborador->transaccion_detalle->nro_operacion : '...' }}"
                                                        readonly>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label"><b>Monto:</b></label>
                                                        <div class="text-soles">
                                                            <h3>S/</h3>
                                                            <input type="text" class="form-control" value="{{ $colaborador->total_monto }}" readonly>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Comprobante:</b></label>
                                                        @if (!empty($colaborador->transaccion_detalle->comprobante))
                                                            <div class="comprobante d-flex">
                                                                <a href="{{ asset('storage/comprobantes-pagos/' . $colaborador->transaccion_detalle->comprobante) }}"
                                                                    class="btn btn-success"
                                                                    download="{{ $colaborador->transaccion_detalle->comprobante }}"
                                                                    style="color: black;">
                                                                     <strong>Descargar Comprobante</strong>
                                                                 </a>
                                                            </div>
                                                        @else
                                                            <p class="text-muted">No hay comprobante disponible</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-8">
                                                        <label class="form-label"><b>Observaciones:</b></label>
                                                        <textarea class="form-control observaciones" readonly>{{ $colaborador->transaccion ? $colaborador->transaccion->observaciones : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- modal transaccion colaborador --}}
                        <div class="modal fade" id="pagoModal{{ $colaborador->id }}" tabindex="-1"
                            aria-labelledby="pagoModalLabel{{ $colaborador->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="pagoModalLabel{{ $colaborador->id }}">Realizar Pago Colaborador</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('caja.transaccionColab', $colaborador->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="colaborador_id" value="{{ $colaborador->id }}">

                                            <div class="transaccion-colab-content">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Fecha:</b></label>
                                                        <input type="date" class="form-control" name="fecha" value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Nombres:</b></label>
                                                        <input type="text" class="form-control" name="nombre" value="{{ $colaborador->candidato->nombre . ' ' . $colaborador->candidato->apellido }}" readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>DNI:</b></label>
                                                        <input type="number" class="form-control" name="dni" value="{{ $colaborador->candidato->dni }}" readonly>
                                                    </div>
                                                </div>


                                                <div class="row mb-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label"><b>Descripción:</b></label>
                                                        <input class="form-control descripcion-pago" type="text" name="descripcion" value="{{ implode(' - ', $colaborador->pago_colaborador->pluck('descripcion')->toArray()) }}" readonly>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Tipo:</b></label>
                                                        <input class="form-control descripcion-pago" type="text" value="Colaborador" readonly>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Método de Pago:</b></label>
                                                        <div role="group" aria-label="Método de Pago" style="width: 100%;">
                                                           <div class="row col-12 metodo-pago-row">
                                                             <input type="radio" class="btn-check" name="metodo_pago" id="yape{{ $colaborador->id }}" value="Yape" required>
                                                            <label class="btn" for="yape{{ $colaborador->id }}">Yape</label>

                                                            <input type="radio" class="btn-check" name="metodo_pago" id="plin{{ $colaborador->id }}" value="Plin" required>
                                                            <label class="btn" for="plin{{ $colaborador->id }}">Plin</label>
                                                           </div>

                                                           <div class="row col-12 metodo-pago-row">
                                                             <input type="radio" class="btn-check" name="metodo_pago" id="transferencia{{ $colaborador->id }}" value="Transferencia" required>
                                                            <label class="btn" for="transferencia{{ $colaborador->id }}">Transferencia</label>

                                                            <input type="radio" class="btn-check" name="metodo_pago" id="efectivo{{ $colaborador->id }}" value="Efectivo" required>
                                                            <label class="btn" for="efectivo{{ $colaborador->id }}">Efectivo</label>
                                                           </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <label class="form-label"><b>Nro. Operación:</b></label>
                                                        <input type="number" class="form-control" name="nro_operacion">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label"><b>Monto:</b></label>
                                                        <div class="text-soles">
                                                            <h3>S/</h3>
                                                            <input type="text" class="form-control" name="total_monto" value="{{ $colaborador->total_monto }}" readonly>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label"><b>Comprobante:</b></label>
                                                        <input type="file" name="comprobante" class="form-control img-text">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label"><b>Observaciones:</b></label>
                                                        <textarea class="form-control observaciones" name="observaciones"></textarea>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-success">Confirmar Pago</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // JAVASCRIPT CORREGIDO - Gestión correcta de modales
    document.addEventListener('DOMContentLoaded', function() {

        // Filtro de fechas
        document.getElementById('filtrar-form').addEventListener('submit', function(event) {
            event.preventDefault();

            let fechaInicio = document.getElementById('fecha_inicio').value;
            let fechaFin = document.getElementById('fecha_fin').value;

            fetch("{{ route('caja.filtrarFecha') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    fecha_inicio: fechaInicio,
                    fecha_fin: fechaFin
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-ingresos').textContent = data.ingresos;
                document.getElementById('total-egresos').textContent = data.egresos;
            })
            .catch(error => {
                console.error("Error al filtrar:", error);
            });
        });

        // Gestión de apertura del modal de usuarios
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-bs-target="#modalUsuarios"]')) {
                // Ocultar el modal de transacción antes de abrir el de usuarios
                var transaccionModal = bootstrap.Modal.getInstance(document.getElementById('transaccionModal'));
                if (transaccionModal) {
                    transaccionModal.hide();
                }
            }
        });

        // Búsqueda de usuarios
        document.getElementById('buscarUsuario').addEventListener('input', function() {
            var value = this.value.toLowerCase();
            var hasResults = false;
            var filas = document.querySelectorAll('.usuario-fila');

            if (value === '') {
                filas.forEach(function(fila, index) {
                    if (index < filas.length - 3) {
                        fila.classList.add('d-none');
                    } else {
                        fila.classList.remove('d-none');
                    }
                });
                hasResults = true;
            } else {
                filas.forEach(function(fila) {
                    var filtro = fila.dataset.filtro;
                    if (filtro.includes(value)) {
                        fila.classList.remove('d-none');
                        hasResults = true;
                    } else {
                        fila.classList.add('d-none');
                    }
                });
            }

            var noResultados = document.getElementById('noResultados');
            if (hasResults) {
                if (noResultados) noResultados.classList.add('d-none');
            } else {
                if (noResultados) noResultados.classList.remove('d-none');
            }
        });

        // Selección de usuario
        document.addEventListener('click', function(e) {
            if (e.target.closest('.seleccionar-usuario')) {
                var fila = e.target.closest('.seleccionar-usuario');
                var nombre = fila.dataset.nombre;
                var dni = fila.dataset.dni;

                // Llenar los campos del formulario
                document.getElementById('nombres').value = nombre;
                document.querySelector('input[name="dni"]').value = dni;

                // Cerrar modal de usuarios
                var modalUsuarios = bootstrap.Modal.getInstance(document.getElementById('modalUsuarios'));
                if (modalUsuarios) {
                    modalUsuarios.hide();
                }

                // Esperar a que se cierre completamente el modal de usuarios y luego abrir el de transacción
                document.getElementById('modalUsuarios').addEventListener('hidden.bs.modal', function() {
                    var transaccionModal = new bootstrap.Modal(document.getElementById('transaccionModal'));
                    transaccionModal.show();
                }, { once: true }); // { once: true } para que solo se ejecute una vez
            }
        });

        // Limpiar búsqueda al cerrar modal de usuarios
        document.getElementById('modalUsuarios').addEventListener('hidden.bs.modal', function() {
            document.getElementById('buscarUsuario').value = '';
            var filas = document.querySelectorAll('.usuario-fila');
            filas.forEach(function(fila, index) {
                if (index < filas.length - 3) {
                    fila.classList.add('d-none');
                } else {
                    fila.classList.remove('d-none');
                }
            });
            var noResultados = document.getElementById('noResultados');
            if (noResultados) noResultados.classList.add('d-none');
        });

        // Manejar el caso cuando se cancela la selección de usuario (cerrar modal sin seleccionar)
        document.getElementById('modalUsuarios').addEventListener('hide.bs.modal', function(e) {
            // Si no se seleccionó usuario (campos vacíos), volver a mostrar modal de transacción
            setTimeout(function() {
                if (!document.getElementById('nombres').value) {
                    var transaccionModal = new bootstrap.Modal(document.getElementById('transaccionModal'));
                    transaccionModal.show();
                }
            }, 300); // Pequeño delay para evitar conflictos
        });
    });

    // Funciones de caja
    function cerrarCaja() {
        fetch("{{ route('caja.cerrar') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
            },
        }).then(() => location.reload());
    }

    function abrirCaja() {
        fetch("{{ route('caja.abrir') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
            },
        }).then(() => location.reload());
    }

    // Función de confirmación para anular
    function confirmAnular(id) {
        Swal.fire({
            title: "¿Deseas anular esta transacción?",
            showCancelButton: true,
            confirmButtonText: "Anular",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                let routeTemplate = "<?php echo route('caja.anularTransaccionColab', ':id'); ?>";
                form.action = routeTemplate.replace(':id', id);
                form.innerHTML = `@csrf`;
                document.body.appendChild(form);
                form.submit();
            } else {
                Swal.fire({
                    title: "Acción cancelada",
                    text: "La transacción no fue cancelada",
                    icon: "info"
                });
            }
        });
    }
</script>
</body>
</html>
