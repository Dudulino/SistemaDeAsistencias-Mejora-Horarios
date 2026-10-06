<?php

namespace App\Http\Controllers;

use App\Models\Horarios_Presenciales;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHorarios_PresencialesRequest;
use App\Http\Requests\UpdateHorarios_PresencialesRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class HorariosPresencialesController extends Controller
{
    //NOT USING YET

    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $Horarios_Presenciales = Horarios_Presenciales::all();

        return view('Horarios_Presenciales.index', compact('Horarios_Presenciales'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'hora_inicial' => 'required|string|min:1|max:100',
            'hora_final' => 'required|string|min:1|max:255',
            'dia' =>  'required|string|min:1|max:7',
        ]);

        Horarios_Presenciales::create([
                "hora_inicial" => $request->hora_inicial,
                "hora_final" => $request->hora_final,
                "dia" => $request->dia
        ]);

    }


    public function show($horario_presencial_id)
    {
        try {
            $horario = Horarios_Presenciales::find($horario_presencial_id);

            if ($horario == null) {
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }

            return response()->json(["data" => $horario]);
        } catch (Exception $e) {
            return response()->json(["data" => $e]);
        }
    }


    public function update(Request $request, $horario_presencial_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        try{
            $request->validate([
                'hora_inicial' => 'required|string|min:1|max:100',
                'hora_final' => 'required|string|min:1|max:255',
                'dia' =>  'required|string|min:1|max:7',
            ]);

            $horarios_presenciales = horarios_presenciales::findOrFail($horario_presencial_id);

            $horarios_presenciales->update($request->all());

            DB::beginTransaction();
            return redirect()->route('horarios_presenciales.index');
        }catch(Exception $e)
        {
            return redirect()->route('horarios_presenciales.index');
        }



    }


    public function destroy($horario_presencial_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try{
            $horarios_presenciales = horarios_presenciales::findOrFail($horario_presencial_id);

            $horarios_presenciales->delete();

            DB::commit();
            return redirect()->route('horarios_presenciales.index');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->route('horarios_presenciales.index');
        }

    }
}
