<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programas_instalados extends Model
{
    use HasFactory;

    protected $fillable = [
        'computadora_id',
        'programa_id',
        'estado'
    ];


    public function computadora_colaborador(){
        return $this->belongsTo(Computadora_colaborador::class, 'computadora_id', 'id');
    }

    public function programa(){
        return $this->belongsTo(Programas::class, 'programa_id', 'id');
    }

}
