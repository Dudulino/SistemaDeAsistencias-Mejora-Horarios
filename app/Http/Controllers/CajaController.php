<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidatos;
use App\Models\Colaboradores;
use App\Models\IngresoEgresoTransaccion;
use App\Models\PagoColaborador;
use App\Models\SaldoTransaccion;
use App\Models\Semanas;
use App\Models\TipoTransacciones;
use App\Models\Transaccion;
use App\Models\TransaccionDetalle;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{

    public function index() {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }

        $thisWeekMonday = Carbon::now()->startOfWeek()->toDateString();
        $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();

        $cajaAbierta = Semanas::where('fecha_lunes', $thisWeekMonday)->value('caja_abierta');

        $colaboradores = collect();
        if ($cajaAbierta) {
            $colaboradores = Colaboradores::where('estado', 1)
                ->whereHas('pago_colaborador')
                ->with('candidato')
                ->get();
        }

        $pagoColab = PagoColaborador::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();

        $semanaActual = Semanas::where('fecha_lunes', '<=', Carbon::now()->toDateString())
        ->orderBy('fecha_lunes', 'desc')
        ->first();

        foreach ($colaboradores as $colaborador) {
            $colaborador->total_monto = $colaborador->pago_colaborador->sum('monto');

            if (!$semanaActual) {
                $colaborador->pagado = false;
                $colaborador->transaccion = null;
                $colaborador->transaccion_detalle = null;
                $colaborador->anulado = false;
                continue;
            }

            $transaccion = Transaccion::where('semana_id', $semanaActual->id)
                ->where('dni', $colaborador->candidato->dni)
                ->first();

            $transaccionDetalle = null;

            $transaccionAnulada = Transaccion::where('semana_id', $semanaActual->id)
                ->where('dni', $colaborador->candidato->dni)
                ->where('anulado', 1)
                ->first();

            if ($transaccion) {
                $transaccionDetalle = TransaccionDetalle::where('transaccion_id', $transaccion->id)->first();
            }

            $colaborador->pagado = $transaccion;
            $colaborador->transaccion = $transaccion;
            $colaborador->transaccion_detalle = $transaccionDetalle;
            $colaborador->anulado = $transaccionAnulada;
        }

        $tipoTransacciones = TipoTransacciones::where('es_ingreso', false)->get();

        $saldoActual = SaldoTransaccion::latest()->first();

        $depositos = Transaccion::where('tipo_transaccion_id', 1)
        ->where('semana_id', $thisSemana->id)
        ->where('estado', 1)
        ->with('tipo_transaccion')
        ->get();

        $cajas = Transaccion::where('tipo_transaccion_id', 2)
        ->where('semana_id', $thisSemana->id)
        ->where('estado', 1)
        ->with('tipo_transaccion')
        ->get();

        $añoActual = Carbon::now()->year;
        $semanasIds = Semanas::whereYear('fecha_lunes', $añoActual)->pluck('id');
        $ingresos = IngresoEgresoTransaccion::where('tipo', 'ingreso')
            ->whereIn('semana_id', $semanasIds)
            ->sum('monto');
        $egresos = IngresoEgresoTransaccion::where('tipo', 'egreso')
            ->whereIn('semana_id', $semanasIds)
            ->sum('monto');

        $users = User::get();
        foreach ($users as $user) {
        $candidato = Candidatos::where('correo', $user->email)->first();
        if ($candidato) {
            $user->dni = $candidato->dni;
        } else {
            $user->dni = 'No tiene DNI';
        }
    }
        return view('inspiniaViews.caja-chica.index', [
            'colaboradores' => $colaboradores,
            'pagoColab' => $pagoColab,
            'tipoTransacciones' => $tipoTransacciones,
            'saldoActual' => $saldoActual,
            'depositos' => $depositos,
            'cajas' => $cajas,
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'cajaAbierta' => $cajaAbierta,
            'users' => $users
        ]);
    }

    public function transaccionColab(Request $request, $colaborador_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }

        DB::beginTransaction();
        try {
            // dd($request->all());

            $colaborador = Colaboradores::findOrFail($colaborador_id);

            $atributosColab = $colaborador->candidato;

            $tipoColab = TipoTransacciones::where('descripcion', 'Colaborador')->first();

            $saldoActual = SaldoTransaccion::latest()->first()->saldo_actual ?? 0;

            if ($saldoActual < $request->total_monto) {
                DB::rollback();
                return redirect()->route('caja.index')->with('error', 'Saldo insuficiente para realizar el pago.');
            }

            $thisWeekMonday = Carbon::now()->startOfWeek()->toDateString();
            $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();
            if (!$thisSemana) {
                return redirect()->route('caja.index')->with('error', 'No se ha generado la semana actual en el sistema.');
            }

            if ($request->semana_id > $thisSemana->id) {
                return redirect()->route('caja.index')
                    ->with('error', 'No se pueden registrar transacciones en semanas futuras.');
            }

            $nro_pago = FunctionHelperController::generarNroPago();
            $transaccion = Transaccion::create([
                'semana_id' => $thisSemana->id,
                'nro_pago' => $nro_pago,
                'nombres' => $atributosColab->nombre ." ". $atributosColab->apellido,
                'dni' => $atributosColab->dni ?? null,
                'descripcion' => $request->descripcion,
                'observaciones' => $request->observaciones,
                'monto' => $request->total_monto,
                'tipo_transaccion_id' => $tipoColab->id,
                'estado' => 1,
                'fecha' => $request->fecha,
                'anulado' => 0
            ]);


            if ($request->hasFile('comprobante')) {
                $comprobanteImg = $request->file('comprobante');
                $nombreComprobante = time() . '.' . $comprobanteImg->getClientOriginalExtension();
                $comprobanteImg->move(public_path('storage/comprobantes-pagos'), $nombreComprobante);
            } else {
                $nombreComprobante = null;
            }

            TransaccionDetalle::create([
                'transaccion_id' => $transaccion->id,
                'metodo_pago' => $request->metodo_pago,
                'comprobante' => $nombreComprobante,
                'nro_operacion' => $request->nro_operacion
            ]);

            $nuevoSaldo = $saldoActual - $request->total_monto;
            SaldoTransaccion::create([
                'fecha' => Carbon::now(),
                'saldo_actual' => $nuevoSaldo,
                'transaccion_id' => $transaccion->id,
            ]);

            IngresoEgresoTransaccion::create([
                'transaccion_id' => $transaccion->id,
                'semana_id' => $thisSemana->id,
                'monto' => $request->total_monto,
                'tipo' => 'egreso',
                'fecha' => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('caja.index')->with('success', 'Pago éxitoso.');

        }catch (Exception $e) {

            // return $e;
            DB::rollback();
            return redirect()->route('caja.index')->with('error', 'Error al registrar el pago.');

        }
    }

    public function registroTransaccion(Request $request) {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente de nuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {

            $tipoTransaccion = TipoTransacciones::findOrFail($request->tipo_transaccion_id);

            $ultimoSaldo = SaldoTransaccion::latest()->first();
            $saldoActual = $ultimoSaldo ? $ultimoSaldo->saldo_actual : 0;

            if ($tipoTransaccion->es_ingreso == 0 && $saldoActual < $request->monto) {
                DB::rollback();
                return redirect()->route('caja.index')->with('error', 'Saldo insuficiente para realizar la transacción.');
            }

            $thisWeekMonday = Carbon::now()->startOfWeek()->toDateString();
            $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();
            if (!$thisSemana) {
                return redirect()->route('caja.index')->with('error', 'No se ha generado la semana actual en el sistema.');
            }
            if ($request->semana_id > $thisSemana->id) {
                return redirect()->route('caja.index')
                    ->with('error', 'No se pueden registrar transacciones en semanas futuras.');
            }

            $nro_pago = FunctionHelperController::generarNroPago();
            $transaccion = Transaccion::create([
                'semana_id' => $thisSemana->id,
                'nro_pago' => $nro_pago,
                'nombres' => $request->nombres,
                'dni' => $request->dni,
                'descripcion' => $request->descripcion,
                'monto' => $request->monto,
                'tipo_transaccion_id' => $tipoTransaccion->id,
                'estado' => 1,
                'fecha' => $request->fecha,
                'observaciones' => $request->observaciones,
                'anulado' => 0
            ]);

            if ($tipoTransaccion->es_ingreso == 1) {
                $saldoActual += $request->monto;
            } else {
                $saldoActual -= $request->monto;
            }

            $tipo = $tipoTransaccion->es_ingreso == 1 ? 'ingreso' : 'egreso';

            SaldoTransaccion::create([
                'fecha' => Carbon::now(),
                'saldo_actual' => $saldoActual,
                'transaccion_id' => $transaccion->id,
            ]);

            IngresoEgresoTransaccion::create([
                'transaccion_id' => $transaccion->id,
                'semana_id' => $thisSemana->id,
                'monto' => $request->monto,
                'tipo' => $tipo,
                'fecha' => Carbon::now(),
            ]);

            DB::commit();
            return redirect()->route('caja.index')->with('success', 'Transacción registrada exitosamente.');

        } catch (Exception $e) {

            // return $e;
            DB::rollback();
            return redirect()->route('caja.index')->with('error', 'Error al registrar la transacción.');

        }
    }

    public function abrirCaja() {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }
        $thisWeekMonday = Carbon::now()->startOfWeek()->toDateString();
        Semanas::where('fecha_lunes', $thisWeekMonday)->update(['caja_abierta' => 1]);
        return redirect()->back()->with('success', 'Caja abierta.');
    }

    public function cerrarCaja() {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }
        try {

            $thisWeekMonday = Carbon::now()->startOfWeek()->toDateString();
            $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();

            Semanas::where('fecha_lunes', $thisWeekMonday)->update(['caja_abierta' => 0]);

            Transaccion::where('semana_id', $thisSemana->id)
            ->update(['estado' => 0]);

            return redirect()->back()->with('success', 'Caja cerrada.');

        }catch(Exception $e) {

            return redirect()->route('error', 'Ocurrió un error inesperado.');

        }
    }

    public function filtrarFecha(Request $request) {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }

        DB::beginTransaction();
        try {

            $ingresosEgresos = IngresoEgresoTransaccion::query();

            if($request->has('fecha_inicio') && $request->has('fecha_fin')) {
                $ingresosEgresos->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
            }

            $ingresos = (clone $ingresosEgresos)->where('tipo', 'ingreso')->sum('monto');
            $egresos = (clone $ingresosEgresos)->where('tipo', 'egreso')->sum('monto');

            return response()->json([
                'ingresos' => $ingresos,
                'egresos' => $egresos
            ]);

        }catch(Exception $e) {

            return redirect()->route('caja.index')->with('error', 'Error al filtrar.');

        }
    }

    public function anularTransaccionColab(Request $request, $colaborador_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }

        DB::beginTransaction();
        try {
            $colaborador = Colaboradores::findOrFail($colaborador_id);

            $atributosColab = $colaborador->candidato;

            $tipoColab = TipoTransacciones::where('descripcion', 'Colaborador')->first();


            $thisWeekMonday = Carbon::now()->startOfWeek()->toDateString();
            $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();
            if (!$thisSemana) {
                return redirect()->route('caja.index')->with('error', 'No se ha generado la semana actual en el sistema.');
            }

            if ($request->semana_id > $thisSemana->id) {
                return redirect()->route('caja.index')
                    ->with('error', 'No se pueden registrar transacciones en semanas futuras.');
            }

            $fechaHoy = Carbon::now()->format('Y-m-d');
            Transaccion::create([
                'semana_id' => $thisSemana->id,
                'nro_pago' => null,
                'nombres' => $atributosColab->nombre ." ". $atributosColab->apellido,
                'dni' => $atributosColab->dni,
                'descripcion' => null,
                'observaciones' => null,
                'monto' => 0,
                'tipo_transaccion_id' => $tipoColab->id,
                'estado' => null,
                'fecha' => $fechaHoy,
                'anulado' => 1
            ]);


            DB::commit();
            return redirect()->route('caja.index')->with('success', 'Anulación exitosa.');

        }catch (Exception $e) {

            // return $e;
            DB::rollback();
            return redirect()->route('caja.index')->with('error', 'Error al registrar el pago.');

        }

    }

}
