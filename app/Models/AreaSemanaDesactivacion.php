<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaSemanaDesactivacion extends Model
{
    use HasFactory;

    protected $table = 'area_semana_desactivaciones';

    protected $fillable = [
        'area_id',
        'fecha_inicio',
        'fecha_fin',
        'desactivada'
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_fin'
    ];

    public function area() {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
