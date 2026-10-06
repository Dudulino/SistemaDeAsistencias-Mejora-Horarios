<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquina_reservada extends Model
{
    use HasFactory;

    protected $fillable = [
        'colaborador_area_id',
        'maquina_id'
    ];

    public function maquina(){
        return $this->belongsTo(Maquinas::class, 'maquina_id', 'id');
    }   

    public function colaborador_area(){
        return $this->belongsTo(Colaboradores_por_Area::class, 'colaborador_area_id', 'id');
    }
}
