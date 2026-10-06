<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistentes_Clase;
use Illuminate\Support\Facades\DB;
use Exception;

class Asistentes_ClaseController extends Controller
{
    //NOT USING YET
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $asistente_clase = Asistentes_Clase::all();

        return view('inspiniaViews.asistente_clase.index', compact('asistente_clase'));

    }


    public function create(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            if(!$request->colaborador_id){
                return response()->json(["resp" => "Ingrese colaborador"]);
            }

            if(!$request->clase_id){
                return response()->json(["resp" => "Ingrese clase"]);
            }

            if(!$request->asistio){
                return response()->json(["resp" => "Ingrese si asistio"]);
            }

            if(!is_integer($request->colaborador_id)){
                return response()->json(["resp" => "El id del colaborador debe ser un número entero"]);
            }

            if(!is_integer($request->clase_id)){
                return response()->json(["resp" => "El id de la clase debe ser un número entero"]);
            }

            if(!is_bool($request->asistio)){
                return response()->json(["resp" => "Asistio debe ser un booleano"]);
            }

            Asistentes_Clase::create([
                "colaborador_id" => $request->colaborador_id,
                "clase_id" => $request->clase_id,
                "asistio" => $request->asistio
            ]);
            DB::commit();
            return response()->json(["resp" => "Registro creado correctamente"]);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(["error" => $e]);
        }
    }

    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'colaborador_id' => 'required|integer|min:1|max:100',
            'clase_id' => 'required|integer|min:1|max:255',
            'asistio' => 'required|boolean|min:1|max:7'
        ]);

        Asistentes_Clase::create([
            'colaborador_id' => $request->colaborador_id,
            'clase_id' => $request->clase_id,
            'asistio' => $request->asistio
        ]);

        return redirect()->route('asistente_clase.index');

    }


    public function show($asistente_clase_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        try{
            $asistente_clase = Asistentes_Clase::with('colaboradores')->find($asistente_clase_id);

            if(!$asistente_clase){
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }

            return response()->json(["data" => $asistente_clase]);
        } catch(Exception $e){
            return response()->json(["error" => $e]);
        }

    }


    public function update(Request $request, $asistente_clase_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'colaborador_id' => 'required|integer|min:1|max:100',
            'clase_id' => 'required|integer|min:1|max:255',
            'asistio' => 'required|boolean|min:1|max:7'
        ]);

        $asistente_clase = Asistentes_Clase::findOrFail($asistente_clase_id);

        $asistente_clase->update($request->all());

        return redirect()->route('asistente_clase.index');

    }


    public function destroy($asistente_clase_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        try{
            $asistente_clase = Asistentes_Clase::findOrFail($asistente_clase_id);

            $asistente_clase->delete();

            DB::beginTransaction();
            return redirect()->route('asistente_clase.index');
        }catch(Exception $e){
            return redirect()->route('asistente_clase.index');
        }



    }
}
