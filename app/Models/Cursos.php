<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'categoria',
        'duracion',
        'estado'
    ];

    public function clase(){
        return $this->hasMany(Clase::class, 'curso', 'id');
    } 
}
