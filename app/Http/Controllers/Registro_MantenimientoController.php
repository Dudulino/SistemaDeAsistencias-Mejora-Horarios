<?php

namespace App\Http\Controllers;

use App\Models\Computadora_colaborador;
use Illuminate\Http\Request;
use App\Models\Registro_Mantenimiento;
use Illuminate\Support\Facades\DB;
use Exception;

class Registro_MantenimientoController extends Controller
{
    public function index()
    {
        $registro_mantenimiento = Registro_Mantenimiento::all();

        return view('inspiniaViews.registro_mantenimiento.index', compact('registro_mantenimiento'));

    }

    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            // return $request;
            $request->validate([
                'computadora_id' => 'required|integer|min:1|max:100',
                'colaborador_id' => 'required|integer|min:1|max:100',
                'fecha' => 'required|date',
                'registro_incidencia' => 'required|string|min:1|max:255'
            ]);
    
            Registro_Mantenimiento::create([
                "computadora_id" => $request->computadora_id,
                "fecha" => $request->fecha,
                "registro_incidencia" => $request->registro_incidencia
    
            ]);
            DB::commit();
            return redirect()->route('colaboradores.getComputadora', $request->colaborador_id);
        } catch(Exception $e) {
            DB::rollBack();
            // return response()->json(["error"=> $e]);
            return redirect()->route('colaboradores.getComputadora', $request->colaborador_id);
        }

    }

    public function inactivar($colaborador_id, $registro_Mantenimiento_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $registro_Mantenimiento = Registro_Mantenimiento::findOrFail($registro_Mantenimiento_id);
            if(($registro_Mantenimiento) && ($registro_Mantenimiento->estado === 1) ){
                $registro_Mantenimiento->estado = 0;
            }
            $registro_Mantenimiento->save();
            DB::commit();
            return redirect()->route('colaboradores.getComputadora', $colaborador_id);
        } catch(Exception $e) {
            DB::rollBack();
            return redirect()->route('colaboradores.getComputadora', $colaborador_id);
        }
    }




    public function update(Request $request, $registro_mantenimiento_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'horario_presencial_id' => 'required|string|min:1|max:100',
            'fecha' => 'required|datetime|min:1|max:255',
            'registro_incidencia' => 'required|datetime|min:1|max:255'
        ]);

        $registro_Mantenimiento = Registro_Mantenimiento::findOrFail($registro_mantenimiento_id);

        $registro_Mantenimiento->update($request->all());

        return redirect()->route('inspiniaViews.registro_Mantenimiento.index');

    }


    public function destroy($registro_mantenimiento_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $registro_Mantenimiento = Registro_Mantenimiento::findOrFail($registro_mantenimiento_id);

        $registro_Mantenimiento->delete();

        return redirect()->route('inspiniaViews.registro_Mantenimiento.index');

    }
}
