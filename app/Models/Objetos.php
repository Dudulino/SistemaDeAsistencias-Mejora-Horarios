<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public function prestamo_objeto(){
        return $this->hasMany(Prestamos_objetos_por_colaborador::class, 'objeto_id', 'id');
    }
}
