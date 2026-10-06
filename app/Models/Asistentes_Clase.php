<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistentes_Clase extends Model
{
    use HasFactory;

    protected $fillable = [
        'colaborador_id',
        'clase_id',
        'asistio'
    ];

    public function colaborador(){
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }
}
