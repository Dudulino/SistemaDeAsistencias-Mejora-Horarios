<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InformeSemanal extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'nota_semanal',
        'estado',
        'informe_url',
        'semana_id',
        'area_id',
        'dia',
        'hora',
    ];

    // Relación con el modelo Semana
    public function semana()
    {
    return $this->belongsTo(Semanas::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
