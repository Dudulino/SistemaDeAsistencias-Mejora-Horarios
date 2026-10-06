<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function candidato(){
        return $this->hasMany(Candidatos::class, 'carrera_id', 'id');
    }

}
