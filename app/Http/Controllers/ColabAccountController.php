<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Exception;

class ColabAccountController extends Controller
{
    public function index() {
        try {

            $librosDisponibles = Libro::where('estado', 1)->get();
            $cantidadLib = count($librosDisponibles);


            return view('inspiniaViews.colaboradores.libros-disponibles', [
                'librosDisponibles' => $librosDisponibles,
                'cantidadLib' => $cantidadLib
            ]);

        } catch (Exception $e) {

            return redirect('dashboard')->with('error', 'Ocurrió un error al acceder a la página redireccionada. Si el error persiste comuniquese con su equipo de soporte.');

        }
    }
}
