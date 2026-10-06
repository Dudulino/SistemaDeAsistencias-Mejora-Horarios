<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReunionesProgramadas extends Model
{
    use HasFactory;
    protected $fillable = [
        'fecha',
        'hora_inicial',
        'hora_final',
        'disponibilidad',
        'url',
        'direccion',
        'descripcion',
        'estado'
    ];

    public function integrantes_reunion(){
        return $this->hasMany(IntegrantesReuniones::class, 'reunion_programada_id', 'id');
    }
}
