<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reuniones_Areas extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'dia',
        'hora_inicial',
        'hora_final',
        'disponibilidad'
    ];


    public function area(){
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

}
