<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaccionDetalle extends Model
{
    use HasFactory;

    protected $table = 'transaccion_detalle';

    protected $fillable = [
        'transaccion_id',
        'metodo_pago',
        'comprobante',
        'nro_operacion'
    ];

    public function transaccion() {
        return $this->belongsTo(Transaccion::class, 'transaccion_id', 'id');
    }
}
