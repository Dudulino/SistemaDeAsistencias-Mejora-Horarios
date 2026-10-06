<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTransacciones extends Model
{
    use HasFactory;

    protected $table = 'tipo_transacciones';

    protected $fillable = [
        'descripcion',
        'es_ingreso'
    ];

    public function transaccion() {
        return $this->hasMany(Transaccion::class, 'tipo_transaccion_id', 'id');
    }
}
