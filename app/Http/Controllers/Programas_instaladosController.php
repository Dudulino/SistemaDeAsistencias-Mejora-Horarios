<?php

namespace App\Http\Controllers;

use App\Models\Programas_instalados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class Programas_instaladosController extends Controller
{

    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $programas_instalados = Programas_instalados::all();

        return view('programas_instalados.index', compact('programas_instalados'));

    }



    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'computadora_id' => 'required|integer|min:1|max:100',
            'programa_id' => 'required|integer|min:1|max:255'
        ]);
        
            
        Programas_instalados::create([
            "computadora_id" => $request->computadora_id,
            "programa_id" => $request->programa_id
        ]);

        return redirect()->route('programas_instalados.index');
    }

    public function selectProgramas(Request $request, $computadora_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            // return $request;
            $request->validate([
                "programas_id.*" => "required|integer",
                "colaborador_id" => "required|integer",
            ]);

            foreach($request->programas_id as $programa_id){
                $programa = Programas_instalados::where("computadora_id", $computadora_id)->where("programa_id", $programa_id)->first();
                if(!$programa){
                    Programas_instalados::create([
                        "computadora_id" => $computadora_id,
                        "programa_id" => $programa_id
                    ]);
                } else{
                    if($programa->estado === 0){
                        $programa->estado = 1;
                        $programa->save();
                    }
                }
            }

            $programasInactivos = Programas_instalados::where("computadora_id", $computadora_id)->whereNotIn("programa_id", $request->programas_id)->get();
            // return $programasInactivos;
            foreach($programasInactivos as $programaInactivo){
                $programaInactivo->estado = 0;
                $programaInactivo->save();

            }
            DB::commit();
            return redirect()->route('colaboradores.getComputadora', $request->colaborador_id);

        } catch(Exception $e) {
            DB::rollBack();
            // return response()->json(["error"=> $e]);
            return redirect()->route('colaboradores.getComputadora', $request->colaborador_id);
        }

    }
    public function inactivate($colaborador_id, $id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $registro = Programas_instalados::findOrFail($id);
            if($registro){
                $registro->estado = 0;
                $registro->save();
            }
            DB::commit();
            return redirect()->route('colaboradores.getComputadora', $colaborador_id);
        } catch(Exception $e){
            DB::rollBack();
            // return response()->json(["error" => $e]);
            return redirect()->route('colaboradores.getComputadora', $colaborador_id);
        }
    }


    public function show($programas_instalados_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        try{
            $programas_instalados = Programas_instalados::with([
                'computadora_colaborador' => function ($query) {
                    $query->select(
                        'id',
                        'colaborador_id',
                        'procesador',
                        'tarjeta_grafica',
                        'ram',
                        'almacenamiento',
                        'es_laptop',
                        'codigo_serie'
                    );
                },
                'programas' => function ($query) {
                    $query->select('id', 'nombre', 'descripcion', 'memoria_grafica', 'ram'); }
            ])->find($programas_instalados_id);

            if(!$programas_instalados){
                return response()->json(["resp"=> "No existe un registro con ese id"]);
            }

            return response()->json(["data" => $programas_instalados]);
        } catch (Exception $e) {
            return response()->json(["error"=> $e]);
        }

    }


    public function update(Request $request, $programas_instalados_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'computadora_id' => 'required|integer|min:1|max:100',
            'programa_id' => 'required|integer|min:1|max:255',
        ]);

        $programas_instalados = Programas_instalados::findOrFail($programas_instalados_id);
        
        $programas_instalados->update($request->all());

        return redirect()->route('programas_instalados.index');

    }


    public function destroy($programas_instalados_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $programas_instalados = Programas_instalados::findOrFail($programas_instalados_id);

        $programas_instalados->delete();

        return redirect()->route('programas_instalados.index');
    }
}
