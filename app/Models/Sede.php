<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'institucion_id',
        'estado'
    ];

    public function institucion(){
        return $this->belongsTo(Institucion::class, 'institucion_id', 'id');
    }

    public function candidatos(){
        return $this->hasMany(Candidatos::class, 'sede_id', 'id');
    }

}
