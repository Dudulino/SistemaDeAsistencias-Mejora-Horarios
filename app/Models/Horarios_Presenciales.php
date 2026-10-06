<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horarios_Presenciales extends Model
{
    use HasFactory;

    protected $fillable= [
        'hora_inicial',
        'hora_final',
        'dia'
    ];

    public function horario_presencial_asignado(){
        return $this->belongsTo(Horario_Presencial_Asignado::class, 'horario_presencial_id');
    }
}


