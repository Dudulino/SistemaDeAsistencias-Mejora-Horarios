<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores;
use App\Models\ColaboradorLibro;
use App\Models\Libro;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibroController extends Controller
{
    public function index() {

        $libros = Libro::with('colaboradores.candidato')->get();

        $cantidadLib = $libros->count();

        $libroDisponible = Libro::where('estado', 1)->get();
        $librosDispo = $libroDisponible->count();


        foreach ($libros as $libro) {
            $ultimoPrestamo = ColaboradorLibro::where('libro_id', $libro->id)
                ->latest('id')
                ->first();

            $colaborador = ($ultimoPrestamo && !$ultimoPrestamo->devuelto)
                ? Colaboradores::find($ultimoPrestamo->colaborador_id)
                : null;

            $libro->setAttribute('colaborador_actual', $colaborador);
            $libro->setAttribute('ultimoPrestamo', $ultimoPrestamo);

        }

        return view('inspiniaViews.libros.index',[
            'libros' => $libros,
            'cantidadLib' => $cantidadLib,
            'librosDispo' => $librosDispo
        ]);
    }

    public function store(Request $request) {
        DB::beginTransaction();
        try {

            Libro::create([
                'titulo' => $request->titulo,
                'autor' => $request->autor,
                'estado' => 1
            ]);

            DB::commit();
            return redirect()->route('libro.index');

        }catch (Exception $e) {

            // return $e;
            DB::rollback();
            return redirect()->route('libro.index');

        }
    }

    public function update(Request $request, $libro_id) {
        DB::beginTransaction();
        try {

            $libro = Libro::findOrFail($libro_id);

            $libro->update([
                'titulo' => $request->titulo,
                'autor' => $request->autor
            ]);

            DB::commit();
            return redirect()->route('libro.index');

        }catch (Exception $e){

            DB::rollback();
            return redirect()->route('libro.index');

        }
    }

    // public function activeInactive(Request $request, $libro_id) {
    //     DB::beginTransaction();
    //     try {

    //         $libro = Libro::findOrFail($libro_id);

    //         $libro->estado = !$libro->estado;
    //         $libro->save();

    //         DB::commit();
    //         return redirect()->route('libro.index');

    //     }catch (Exception $e){

    //         DB::rollback();
    //         return redirect()->route('libro.index');

    //     }
    // }

}
