<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidatos extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'direccion',
        'fecha_nacimiento',
        'ciclo_de_estudiante',
        'estado',
        'sede_id',
        'carrera_id',
        'correo',
        'celular',
        'icono',
        'id_senati',
        'distrito_id',
        'carnet_extranjeria'
    ];

    public function sede(){
        return $this->belongsTo(Sede::class, 'sede_id', 'id');
    }

    public function carrera(){
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id');
    }

    public function colaborador(){
        return $this->hasOne(Colaboradores::class, 'candidato_id', 'id');
    }

    public static function searchByName($search = ''){
        $candidatosPorNombre = Candidatos::with('sede', 'carrera')
        ->where(DB::raw("CONCAT(nombre, ' ', apellido)"), 'like', '%' . $search . '%')
        ->get();

        return $candidatosPorNombre;
    }

    public static function searchByDni($search = ''){
        $candidatosPorDni = Candidatos::with('sede', 'carrera')
        ->where(DB::raw("dni"), 'like', '%' . $search . '%')
        ->get();

        return $candidatosPorDni;
    }

    public function distrito() {
        return $this->belongsTo(Distrito::class, 'distrito_id', 'id');
    }

}
