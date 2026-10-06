<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Colaboradores;
use App\Models\Colaboradores_por_Area;
use App\Models\Objetos;
use App\Models\Prestamos_objetos_por_colaborador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;

class PrestamoObjetoColaboradorController extends Controller
{
    public function getColaboradorObjetos($colaborador_id)
{
    $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
    // busqueda colaborador
    $colaborador = Colaboradores::with('candidato')->findOrFail($colaborador_id);

    // busqueda colaboradores_por_area
    $colaboradorPorAreas = Colaboradores_por_Area::where('colaborador_id', $colaborador_id)->first();

    // obtener area del colaborador
    $areas = $colaborador->colaborador_por_area->map(function ($colaboradorPorArea) {
        return $colaboradorPorArea->area;
    });

    // prestamo asociado al colaborador
    $prestamos = Prestamos_objetos_por_colaborador::where('colaborador_id', $colaborador_id)->get();

    // objectos activos
    $objetos = Objetos::where('estado', 1)->orderBy('nombre', 'asc')->get();

    return view('inspiniaViews.prestamos.prestamoColab', [
        'colaborador' => $colaborador,
        'areas' => $areas,
        'prestamos' => $prestamos,
        'objetos' => $objetos
    ]);
}


    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            Prestamos_objetos_por_colaborador::create([
                'colaborador_id' => $request->colaborador_id,
                'objeto_id' => $request->objeto_id,
                'fecha_prestamo' => $request->fecha_prestamo
            ]);


            DB::commit();
                return redirect()->route('colaboradores.getPrestamo', $request->colaborador_id);

        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('colaboradores.getPrestamo', $request->colaborador_id);
        }
    }



    public function inactivate(Request $request, $id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            $prestamo = Prestamos_objetos_por_colaborador::findOrFail($id);

            $fechaDevolucion = Carbon::now()->format('Y-m-d');

            $prestamo->update([
                'estado' => false,
                'fecha_devolucion' => $fechaDevolucion
            ]);
            DB::commit();

            // Redirigir a la ruta correcta con el colaborador_id del préstamo
            return redirect()->route('colaboradores.getPrestamo', $prestamo->colaborador_id);

        } catch (Exception $e) {
            DB::rollBack();
            // Redirigir a la ruta correcta con el colaborador_id del préstamo en caso de error
            return redirect()->route('colaboradores.getPrestamo', $prestamo->colaborador_id);
        }
    }

}
