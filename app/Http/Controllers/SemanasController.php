<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semanas;
use Illuminate\Support\Facades\DB;
use Exception;

class SemanasController extends Controller
{
    //NOT USING
    public function index()
    {
        $semanas = Semanas::all();

        return view('semanas.index', compact('semanas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_lunes' => 'required|string|min:1|max:100',
        ]);
        
        Semanas::create([
            'fecha_lunes' => $request->fecha_lunes,
        ]);
        
        return redirect()->route('semanas.index');
    }


    public function storeOld(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!$request->fecha_lunes) {
                return response()->json(["resp" => "Ingrese la fecha"]);
            }

            /*if (!is_string($request->fecha_lunes)) {
                return response()->json(["resp" => "La fecha debe ser dateTime"]);
            }*/
            /*
            if (strlen($request->fecha_lunes) > 100) {
                return response()->json(["resp" => "La fehca es demasiada larga"]);
            }
            */

            Semanas::create([
                "fecha_lunes" => $request->fecha_lunes,
            ]);

            DB::commit();
            return response()->json(["resp" => "Registro creado"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e]);
        }
    }


    public function show($semana_id)
    {
        try {
            $semana = Semanas::find($semana_id);

            if ($semana == null) {
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }

            return response()->json(["data" => $semana]);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }
       
    }


    public function update(Request $request, $semana_id)
    {
        $request->validate([
            'fecha_lunes' => 'required|string|min:1|max:100',
        ]);

        $semanas = Semanas::findOrFail($semana_id);
        
        $semanas->update($request->all());

        return redirect()->route('semanas.index');
    }


    public function destroy($semana_id)
    {
        $semanas = Semanas::findOrFail($semana_id);

        $semanas->delete();

        return redirect()->route('semanas.index');
    }
}
