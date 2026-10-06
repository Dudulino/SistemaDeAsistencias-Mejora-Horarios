<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = 'libros';

    protected $fillable = [
        'titulo',
        'autor',
        'estado'
    ];

    public function colaboradores() {
        return $this->belongsToMany(Colaboradores::class, 'colaborador_libro', 'libro_id', 'colaborador_id');
    }
    
}