<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cumplio_Responsabilidad_Semanal extends Model
{
    use HasFactory;

    protected $fillable = [
        'colaborador_area_id',
        'responsabilidad_id',
        'semana_id',
        'cumplio'
    ];
    
    public function colaborador_por_area(){
        return $this->belongsTo(Colaboradores_por_Area::class, 'colaborador_id', 'id');
    }
    public function responsabilidad_semanal(){
        return $this->belongsTo(Responsabilidades_semanales::class, 'responsabilidad_id', 'id');
    }
    public function semana(){
        return $this->belongsTo(Semanas::class, 'semana_id', 'id');
    }
}
