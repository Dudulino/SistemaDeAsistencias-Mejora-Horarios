<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro_Mantenimiento extends Model
{
    use HasFactory;

    protected $fillable = [
        'computadora_id',
        'fecha',
        'registro_incidencia',
        'estado'
    ];

    public function computadora(){
        return $this->belongsTo(Computadora_colaborador::class, 'computadora_id', 'id');
    }

}
