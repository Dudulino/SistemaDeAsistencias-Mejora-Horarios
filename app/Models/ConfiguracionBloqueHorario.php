<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionBloqueHorario extends Model
{
    use HasFactory;

    protected $table = 'configuracion_bloques_horario';

    protected $fillable = [
        'horario_presencial_asignado_id', 'turno', 'cupo_min', 'cupo_max', 'modalidad', 'estado'
    ];

    protected $casts = ['estado' => 'boolean'];

    public function bloqueBase()
    {
        return $this->belongsTo(Horario_Presencial_Asignado::class, 'horario_presencial_asignado_id');
    }

    public function detallesPropuesta()
    {
        return $this->hasMany(PropuestaHorarioDetalle::class, 'configuracion_bloque_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionPracticante::class, 'configuracion_bloque_id');
    }
}
