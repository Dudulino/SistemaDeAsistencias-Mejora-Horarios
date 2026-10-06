<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoEgresoTransaccion extends Model
{
    use HasFactory;

    protected $table = 'ingresos_egresos_transacciones';

    protected $fillable = [
        'semana_id',
        'transaccion_id',
        'monto',
        'tipo',
        'fecha'
    ];

    public function transaccion() {
        return $this->belongsTo(Transaccion::class, 'transaccion_id', 'id');
    }

    public function semana() {
        return $this->belongsTo(Semanas::class, 'semana_id', 'id');
    }
}
