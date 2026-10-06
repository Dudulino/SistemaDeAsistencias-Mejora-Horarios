<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computadora_colaborador extends Model
{
    use HasFactory;

    protected $fillable = [
        'colaborador_id',
        'procesador',
        'tarjeta_grafica',
        'memoria_grafica',
        'ram',
        'almacenamiento',
        'es_laptop',
        'codigo_serie',
        'estado',
        'estado_pc'
    ];

    public function colaborador(){
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }

    public function programas_instalados(){
        return $this->hasMany(Programas_instalados::class, 'computadora_id', 'id');
    }

}
