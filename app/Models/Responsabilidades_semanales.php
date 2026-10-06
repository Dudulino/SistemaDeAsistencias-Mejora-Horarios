<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsabilidades_semanales extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'porcentaje_peso', 'estado'];

    public function responsabilidad_semanal(){
        return $this->hasMany(Cumplio_Responsabilidad_Semanal::class, 'responsabilidad_id', 'id');
    }

}
