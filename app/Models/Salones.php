<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salones extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];


    public function maquina(){
        return $this->hasMany(Maquinas::class, 'salon_id', 'id');
    }
    

}
