<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horarios_Virtuales extends Model
{
    use HasFactory;

    protected $fillable = [
        'hora_inicial',
        'hora_final',
        'dia'
    ];


    public function horario_virtual_colaborador(){
        return $this->hasMany(Horario_Virtual_Colaborador::class, 'horario_virtual_id', 'id');
    }

}
