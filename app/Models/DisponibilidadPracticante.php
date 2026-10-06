<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisponibilidadPracticante extends Model
{
    use HasFactory;

    protected $table = 'disponibilidad_practicantes';

    protected $fillable = [
        'colaborador_id', 'dia', 'hora_inicial', 'hora_final', 'tipo',
        'origen', 'modalidad', 'observacion', 'estado'
    ];

    protected $casts = ['estado' => 'boolean'];

    public function colaborador()
    {
        return $this->belongsTo(Colaboradores::class, 'colaborador_id');
    }
}
