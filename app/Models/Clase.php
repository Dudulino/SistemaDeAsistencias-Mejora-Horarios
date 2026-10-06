<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    use HasFactory;

    protected $fillable = [
        'hora_inicial',
        'hora_final',
        'dia',
        'curso_id'
    ];

    public function curso(){
        return $this->belongsTo(Cursos::class, 'curso_id', 'id');
    }
}
