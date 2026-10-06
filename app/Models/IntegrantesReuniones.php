<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntegrantesReuniones extends Model
{
    use HasFactory;

    protected $fillable = [
        'reunion_programada_id',
        'colaborador_id',
        'estado',
        'asistio'
    ];

    public function reunion_programada(){
        return $this->belongsTo(ReunionesProgramadas::class, 'reunion_programada_id', 'id');
    }

    public function colaborador(){
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }

}
