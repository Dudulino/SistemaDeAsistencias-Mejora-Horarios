<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario_de_Clases extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'colaborador_id',
        'hora_inicial',
        'hora_final',
        'dia',
        'justificacion'
    ];

    public function colaborador(){
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }

}
