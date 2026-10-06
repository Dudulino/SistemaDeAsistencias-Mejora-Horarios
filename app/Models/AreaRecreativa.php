<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaRecreativa extends Model
{
    use HasFactory;

    protected $fillable = [
        'colaborador_id',
        'actividad_id',
        'estado'
    ];

    public function colaborador(){
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }
    public function actividad(){
        return $this->belongsTo(Actividades::class, 'actividad_id', 'id');
    }

}
