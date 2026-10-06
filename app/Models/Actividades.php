<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividades extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado'
    ];

    public function colaboradores() {
        return $this->belongsToMany(Colaboradores::class, 'area_recreativas', 'actividad_id', 'colaborador_id');
    }

}
