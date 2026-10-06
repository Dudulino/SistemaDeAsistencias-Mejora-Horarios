<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCambioHorario extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_cambio_horario';

    protected $fillable = [
        'asignacion_id', 'colaborador_id', 'tipo_cambio', 'motivo', 'estado',
        'nueva_asignacion_id', 'gestionado_por', 'respuesta'
    ];

    public function asignacion()
    {
        return $this->belongsTo(AsignacionPracticante::class, 'asignacion_id');
    }

    public function nuevaAsignacion()
    {
        return $this->belongsTo(AsignacionPracticante::class, 'nueva_asignacion_id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaboradores::class, 'colaborador_id');
    }
}
