<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamos_objetos_por_colaborador extends Model
{
    use HasFactory;

    protected $fillable = [
        'colaborador_id',
        'objeto_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'estado'
    ];

    public function colaborador(){
        return $this->belongsTo(Colaboradores::class, 'colaborador_id', 'id');
    }
    public function objeto(){
        return $this->belongsTo(Objetos::class, 'objeto_id', 'id');
    }
}
