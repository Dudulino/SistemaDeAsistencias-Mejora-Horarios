<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAdministrador extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'estado',
        'super_admin'
    ];


    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
