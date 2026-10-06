<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuariosPasswords extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'password'
    ];
}
