<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropuestaHorarioDetalle extends Model
{
    use HasFactory;

    protected $table = 'propuesta_horario_detalles';

    protected $fillable = [
        'propuesta_id', 'colaborador_id', 'configuracion_bloque_id', 'puntaje', 'explicacion'
    ];

    protected $casts = ['explicacion' => 'array'];

    public function propuesta()
    {
        return $this->belongsTo(PropuestaHorario::class, 'propuesta_id');
    }

    public function colaborador()
    {
        return $this->belongsTo(Colaboradores::class, 'colaborador_id');
    }

    public function bloque()
    {
        return $this->belongsTo(ConfiguracionBloqueHorario::class, 'configuracion_bloque_id');
    }
}
