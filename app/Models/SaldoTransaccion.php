<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoTransaccion extends Model
{
    use HasFactory;

    protected $table = 'saldo_transacciones';

    protected $fillable = [
        'fecha',
        'saldo_actual',
        'transaccion_id',
    ];

    public function transaccion() {
        return $this->belongsTo(Transaccion::class, 'transaccion_id', 'id');
    }
}
