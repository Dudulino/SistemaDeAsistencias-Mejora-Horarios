<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropuestaHorario extends Model
{
    use HasFactory;

    protected $table = 'propuestas_horario';

    protected $fillable = [
        'generado_por', 'estado', 'factible', 'puntaje_total', 'pendientes', 'resumen'
    ];

    protected $casts = [
        'factible' => 'boolean',
        'pendientes' => 'array',
        'resumen' => 'array',
    ];

    public function detalles()
    {
        return $this->hasMany(PropuestaHorarioDetalle::class, 'propuesta_id');
    }

    public function generador()
    {
        return $this->belongsTo(User::class, 'generado_por');
    }
}
