<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $fillable = [
        'semana_id',
        'nro_pago',
        'nombres',
        'dni',
        'descripcion',
        'observaciones',
        'monto',
        'tipo_transaccion_id',
        'estado',
        'fecha',
        'anulado'
    ];


    public function transaccion_detalle() {
        return $this->hasMany(TransaccionDetalle::class, 'transaccion_id', 'id');
    }

    public function ingreso_egreso_transaccion() {
        return $this->hasMany(IngresoEgresoTransaccion::class, 'transaccion_id', 'id');
    }

    public function tipo_transaccion() {
        return $this->belongsTo(TipoTransacciones::class, 'tipo_transaccion_id', 'id');
    }

    public function saldo_transaccion() {
        return $this->hasMany(SaldoTransaccion::class, 'transaccion_id', 'id');
    }

    public function semana() {
        return $this->belongsTo(Semanas::class, 'semana_id', 'id');
    }
}
