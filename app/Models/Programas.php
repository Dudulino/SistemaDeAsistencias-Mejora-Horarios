<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'icono',
        'memoria_grafica',
        'ram',
        'almacenamiento'
    ];

    public function programa_instalado(){
        return $this->hasMany(Programas_instalados::class, 'programa_id', 'id');
    }

}
