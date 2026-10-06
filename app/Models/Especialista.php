<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialista extends Model
{
    use HasFactory;

    protected $table = 'especialistas';

    protected $fillable = [
        'nombres',
        'correo',
        'celular',
        'estado',
    ];

    public function colaboradores() {
        return $this->hasMany(Colaboradores::class, 'especialista_id', 'id');
    }
}
