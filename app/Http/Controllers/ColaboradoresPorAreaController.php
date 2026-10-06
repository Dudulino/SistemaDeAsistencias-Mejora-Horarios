<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores_por_Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RegistroActividad;
use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ColaboradoresPorAreaController extends Controller
{
    //NOT USING
    public function index()
    {
        $colaboradores_por_area = Colaboradores_por_Area::all();

        return view('colaboradores_por_area.index', compact('colaboradores_por_area'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'colaborador_id' => 'required|integer|min:1|max:100',
            'area_id' => 'required|integer|min:1|max:255',
            'semana_inicio_id' =>  'required|integer|min:1|max:7'
        ]);

        Colaboradores_por_Area::create([
            'colaborador_id' => $request->colaborador_id,
            'area_id' => $request->area_id,
            'semana_inicio_id' => $request->semana_inicio_id
        ]);


        return redirect()->route('colaboradores_por_area.index');
    }


    public function show($colaborador_por_area_id)
    {
        try{
            $colaborador_por_area = Colaboradores_por_Area::/*with([
                'colaboradores' => function($query){$query->select('id', 'candidato_id');},
                'area' => function($query){$query->select('id', 'especializacion', 'color_hex');}])
                ->*/find($colaborador_por_area_id);

            if(!$colaborador_por_area){
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }

            return response()->json(["data" => $colaborador_por_area]);
        } catch(Exception $e){
            return response()->json(["resp" => $e]);
        }
    }


    public function update(Request $request, $colaborador_por_area_id)
    {
        $request->validate([
            'colaborador_id' => 'required|integer|min:1|max:100',
            'area_id' => 'required|integer|min:1|max:255',
            'semana_inicio_id' =>  'required|integer|min:1|max:7'
        ]);

        $colaborador_por_area = Colaboradores_por_Area::findOrFail($colaborador_por_area_id);

        $colaborador_por_area->update($request->all());

        return redirect()->route('colaboradores_por_area.index');
    }


    public function destroy($colaborador_por_area_id)
    {
        $colaborador_por_area = Colaboradores_por_Area::findOrFail($colaborador_por_area_id);

        $colaborador_por_area->delete();

        return redirect()->route('colaboradores_por_area.index');
    }



}
