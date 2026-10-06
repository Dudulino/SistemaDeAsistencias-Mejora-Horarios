<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoColaborador extends Model
{
    use HasFactory;

    protected $table = 'pagos_colaboradores';

    protected $fillable = [
        'colaborador_id',
        'descripcion',
        'monto'
    ];

    public function colaborador() {
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }
}
