<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroActividad extends Model
{
    use HasFactory;
    protected $fillable = [
        'colaborador_area_id',
        'estado',
        'fecha',
    ];
    public function colaborador_area(){
        return $this->belongsTo(Colaboradores_por_Area::class, 'colaborador_area_id', 'id');
    }
}
