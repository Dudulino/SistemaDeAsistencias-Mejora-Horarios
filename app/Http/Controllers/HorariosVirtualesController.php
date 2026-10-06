<?php

namespace App\Http\Controllers;

use App\Models\Horarios_Virtuales;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHorarios_VirtualesRequest;
use App\Http\Requests\UpdateHorarios_VirtualesRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class HorariosVirtualesController extends Controller
{
    //NOT USING YET
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $horarios_virtuales = Horarios_Virtuales::all();

        return view('horarios_Virtuales.index', compact('horarios_virtuales'));
    }


    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $request->validate([
            'hora_inicial' => 'required|string|min:1|max:100',
            'hora_final' => 'required|string|min:1|max:255',
            'dia' =>  'required|string|min:1|max:7'
        ]);

        Horarios_Virtuales::create([
            'hora_inicial' => $request->hora_inicial,
            'hora_final' => $request->hora_final,
            'dia' => $request->dia
        ]);


        return redirect()->route('horarios_Virtuales.index');

    }


    public function create(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {
            //Existencia
            if (!$request->hora_inicial) {
                return response()->json(["resp" => "Ingrese el hora inicial"]);
            }
            if (!$request->hora_final) {
                return response()->json(["resp" => "Ingrese el hora final"]);
            }
            if (!$request->dia) {
                return response()->json(["resp" => "Ingrese el dia"]);
            }

            //Tipo de dato
            if (!is_string($request->hora_inicial)) {
                return response()->json(["resp" => "La hora inicial debe ser una cadena de texto"]);
            }
            if (!is_string($request->hora_final)) {
                return response()->json(["resp" => "La hora final debe ser una cadena de texto"]);
            }
            if (!is_string($request->dia)) {
                return response()->json(["resp" => "El dia debe ser una cadena de texto"]);
            }

            Horarios_Virtuales::create([
                "hora_inicial" => $request->hora_inicial,
                "hora_final" => $request->hora_final,
                "dia" => $request->dia
            ]);

            DB::commit();
            return response()->json(["resp" => "Horario virtual creado"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e]);
        }
    }


    public function show($horario_virtual_id)
    {$access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        try {
            $horario = Horarios_Virtuales::find($horario_virtual_id);

            if ($horario == null) {
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }

            return response()->json(["data"=>$horario]);
        } catch (Exception $e) {
            return response()->json(["data" => $e]);
        }
    }


    public function update(Request $request, $horario_virtual_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $request->validate([
            'hora_inicial' => 'required|string|min:1|max:100',
            'hora_final' => 'required|string|min:1|max:255',
            'dia' =>  'required|string|min:1|max:7'
        ]);

        $horarios_virtuales = Horarios_Virtuales::findOrFail($horario_virtual_id);

        $horarios_virtuales->update($request->all());

        return redirect()->route('horarios_virtuales.index');
    }


    public function destroy($horario_virtual_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $horarios_virtuales = Horarios_Virtuales::findOrFail($horario_virtual_id);

        $horarios_virtuales->delete();

        return redirect()->route('horarios_virtuales.index');
    }
}
