<?php

namespace App\Http\Controllers;

use App\Models\UsuariosPasswords;
use Illuminate\Http\Request;

class UsuariosPasswordsController extends Controller
{
    public static function registrar($user_id, $password){
        UsuariosPasswords::create([
            "user_id" => $user_id,
            "password" => $password
        ]);
    }

    public static function showPassword($user_id){
        $usuario = UsuariosPasswords::where("user_id", $user_id)->get()->last();
        return $usuario?->password;
    }
}
