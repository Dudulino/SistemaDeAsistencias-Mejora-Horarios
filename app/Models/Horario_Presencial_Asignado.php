<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario_Presencial_Asignado extends Model
{
    use HasFactory;

    protected $fillable = [
        'horario_presencial_id',
        'area_id'
    ];

    public function horario_presencial(){
        return $this->belongsTo(Horarios_Presenciales::class, 'horario_presencial_id', 'id');
    }
    public function area(){
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
