<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario_virtual_colaborador extends Model
{
    use HasFactory;

    protected $fillable = [
        'horario_virtual_id',
        'semana_id',
        'area_id'
    ];

    public function horario_virtual(){
        return $this->belongsTo(Horarios_Virtuales::class, 'horario_virtual_id', 'id');
    }
    public function semana(){
        return $this->belongsTo(Semanas::class, 'semana_id', 'id');
    }
    public function area(){
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
