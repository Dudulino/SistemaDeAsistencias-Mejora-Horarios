<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores;
use App\Models\Colaboradores_por_Area;
use App\Models\ColaboradorLibro;
use App\Models\Libro;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoLibroController extends Controller
{
    public function colabLibros($colaborador_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $colaborador = Colaboradores::with('candidato')->findOrFail($colaborador_id);

        $libros = Libro::where('estado', 1)->get();

        $areas = $colaborador->colaborador_por_area->map(function ($colaboradorPorArea) {
            return $colaboradorPorArea->area;
        });


        $prestamoLibros = ColaboradorLibro::with('libro')->where('colaborador_id', $colaborador->id)->get();

        return view('inspiniaViews.prestamos.prestamoLibro',[
            'colaborador' => $colaborador,
            'libros' => $libros,
            'prestamoLibros' => $prestamoLibros,
            'areas' => $areas
        ]);
    }

    public function store(Request $request) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {

            $colaborador = $request->colaborador_id;
            $libro = $request->libro_id;

            ColaboradorLibro::create([
                'colaborador_id' => $colaborador,
                'libro_id' => $libro,
                'fecha_prestamo' => $request->fecha_prestamo
            ]);

            Libro::where('id', $libro)->update([
                'estado' => 0
            ]);

            DB::commit();
            return redirect()->route('libro.colabLibro', $colaborador);

        }catch (Exception $e) {

            // return $e;
            DB::rollBack();
            return redirect()->route('libro.colabLibro', $colaborador);

        }
    }

    public function devolver($libro_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {

            $libroColab = ColaboradorLibro::findOrFail($libro_id);

            $fechaDevolucion = Carbon::now()->format('Y-m-d');
            $libroColab->update([
                'fecha_devolucion' => $fechaDevolucion,
                'devuelto' => 1
            ]);

            Libro::where('id', $libroColab->libro_id)->update([
                'estado' => 1
            ]);

            DB::commit();
            return redirect()->route('libro.colabLibro', $libroColab->colaborador_id);

        }catch (Exception $e) {

            return $e;

        }
    }
}
