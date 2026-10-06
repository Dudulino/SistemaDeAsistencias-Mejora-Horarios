<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'especializacion',
        'descripcion',
        'color_hex',
        'estado',
        'salon_id',
        'icono',
    ];


    public function colaborador_por_area(){
        return $this->hasMany(Colaboradores_por_Area::class, 'area_id', 'id');
    }

    public function salon(){
        return $this->belongsTo(Salones::class,'salon_id','id');
    }

    public function area_semana_desactivacion(){
        return $this->hasMany(AreaSemanaDesactivacion::class, 'area_id', 'id');
    }

    public function ultima_desactivacion() {
        return $this->hasOne(AreaSemanaDesactivacion::class, 'area_id', 'id')->latest('created_at');
    }

    public function proyectos() {
        return $this->hasMany(Proyecto::class, 'area_id', 'id');
    }
}
