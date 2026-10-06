<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionPracticante extends Model
{
    use HasFactory;

    protected $table = 'asignaciones_practicantes';

    protected $fillable = [
        'colaborador_id', 'configuracion_bloque_id', 'propuesta_id', 'puntaje',
        'explicacion', 'estado', 'vigencia_desde', 'vigencia_hasta'
    ];

    protected $casts = [
        'explicacion' => 'array',
        'vigencia_desde' => 'date',
        'vigencia_hasta' => 'date',
    ];

    public function colaborador()
    {
        return $this->belongsTo(Colaboradores::class, 'colaborador_id');
    }

    public function bloque()
    {
        return $this->belongsTo(ConfiguracionBloqueHorario::class, 'configuracion_bloque_id');
    }

    public function propuesta()
    {
        return $this->belongsTo(PropuestaHorario::class, 'propuesta_id');
    }
}
