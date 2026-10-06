<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaboradores extends Model
{
    use HasFactory;

    protected $fillable = [
        'estado',
        'candidato_id',
        'editable',
        'especialista_id',
    ];


    public function candidato(){
        return $this->belongsTo(Candidatos::class, 'candidato_id', 'id');
    }

    public function colaborador_por_area(){
        return $this->hasMany(Colaboradores_por_Area::class, 'colaborador_id', 'id');
    }

    public function horario_de_clases(){
        return $this->hasMany(Horario_de_Clases::class, 'colaborador_id', 'id');
    }

    public function horario_virtual_colaborador(){
        return $this->hasMany(Horario_Virtual_Colaborador::class, 'colaborador_id', 'id');
    }

    public function especialista() {
        return $this->belongsTo(Especialista::class, 'especialista_id', 'id');
    }

    public function usuario_colaborador() {
        return $this->hasMany(UsuarioColaborador::class, 'colaborador_id', 'id');
    }

    public function actividades() {
        return $this->belongsToMany(Actividades::class, 'area_recreativas', 'colaborador_id', 'actividad_id');
    }

    public function pago_colaborador() {
        return $this->hasMany(PagoColaborador::class, 'colaborador_id', 'id');
    }

    public function libros() {
        return $this->belongsToMany(Libro::class, 'colaborador_libro', 'colaborador_id', 'libro_id');

    }

}
