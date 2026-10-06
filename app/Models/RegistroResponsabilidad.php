<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroResponsabilidad extends Model
{
    use HasFactory;
    protected $fillable = [
        'responsabilidad_id',
        'estado',
        'fecha'
    ];


    public function registros()
    {
        return $this->hasMany(RegistroResponsabilidad::class, 'responsabilidad_id', 'id');
    }
}
