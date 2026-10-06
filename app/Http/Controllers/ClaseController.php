<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clase;
use Illuminate\Support\Facades\DB;
use Exception;

class ClaseController extends Controller
{
    //NOT USING YET
    public function index()
    {
        $clase = Clase::all();

        return view('inspiniaViews.clase.index', compact('clase'));
    }

    
    public function create(Request $request)
    {
        DB::beginTransaction();
        try{
            if(!$request->hora_inicial){
                return response()->json(["resp" => "Ingrese hora inicial"]);
            }

            if(!$request->hora_final){
                return response()->json(["resp" => "Ingrese hora final"]);
            }

            if(!$request->dia){
                return response()->json(["resp" => "Ingrese dia"]);
            }

            if(!$request->curso_id){
                return response()->json(["resp" => "Ingrese curso"]);
            }

            if(!is_string($request->hora_inicial)){
                return response()->json(["resp" => "La hora inicial debe ser un texto"]);
            }

            if(!is_string($request->hora_final)){
                return response()->json(["resp" => "La hora final debe ser un texto"]);
            }

            if(!is_string($request->dia)){
                return response()->json(["resp" => "El dia debe ser un texto"]);
            }

            if(!is_integer($request->curso_id)){
                return response()->json(["resp" => "El curso debe ser un número entero"]);
            }

            Clase::create([
                "hora_inicial" => $request->hora_inicial,
                "hora_final" => $request->hora_final,
                "dia" => $request->dia,
                "curso_id" => $request->curso_id
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
        $request->validate([
            'hora_inicial' => 'required|string|min:1|max:100',
            'hora_final' => 'required|string|min:1|max:255',
            'dia' => 'required|string|min:1|max:7',
            'curso_id' => 'required|integer|min:1|max:7',

        ]);

        Clase::create([
            "hora_inicial" => $request->hora_inicial,
            "hora_final" => $request->hora_final,
            "dia" => $request->dia,
            "curso_id" => $request->curso_id
        ]);

        return redirect()->route('inspiniaViews.clase.index');
    }

    
    public function show($clase_id)
    {
        try {
            $clase = Clase::/*with([
                'horarios_virtuales' => function($query) {$query->select('id', 'hora_inicial', 'hora_final');},
                'colaboradores' => function($query) {$query->select('id', 'candidato_id');}])->*/find($clase_id);
            
            if (!$clase){
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }

            return response()->json(["data" => $clase]);
        } catch (Exception $e){
            return response()->json(["error" => $e]);
        }

    }

    
    public function update(Request $request, $clase_id)
    {
        $request->validate([
            'hora_inicial' => 'required|string|min:1|max:100',
            'hora_final' => 'required|string|min:1|max:255',
            'dia' => 'required|string|min:1|max:7',
            'curso_id' => 'required|integer|min:1|max:7',

        ]);

        $clase = Clase::findOrFail($clase_id);

        $clase->update($request->all());

        return redirect()->route('inspiniaViews.clase.index');

    }

    
    public function destroy($clase_id)
    {
        $clase = Clase::findOrFail($clase_id);

        $clase->delete();

        return redirect()->route('inspiniaViews.clase.index');
    }
}
