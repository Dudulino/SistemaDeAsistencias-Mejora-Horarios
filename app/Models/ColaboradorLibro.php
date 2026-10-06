<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColaboradorLibro extends Model
{
    use HasFactory;

    protected $table = 'colaborador_libro';

    protected $fillable = [
        'colaborador_id',
        'libro_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'devuelto'
    ];

    public function libro() {
        return $this->belongsTo(Libro::class, 'libro_id', 'id');
    }
    
}
