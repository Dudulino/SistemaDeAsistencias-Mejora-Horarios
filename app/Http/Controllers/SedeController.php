<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sede;
use App\Models\Institucion;
use Illuminate\Support\Facades\DB;
use Exception;

class SedeController extends Controller
{
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $sedes = Sede::with('institucion')->orderBy('nombre', 'asc')->paginate(12);
        $instituciones = Institucion::where('estado', 1)->orderBy('nombre', 'asc')->get();

        $pageData = FunctionHelperController::getPageData($sedes);
        $hasPagination = true;

        return view('inspiniaViews.sedes.index', [
            "sedes" => $sedes,
            "instituciones" => $instituciones,
            "pageData" => $pageData,
            "hasPagination" => $hasPagination,
        ]);
    }

    public function store(Request $request){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            // $request->validate([
            //     'nombre' => 'required|string|max:100',
            //     'institucion_id' => 'required|integer|exists:institucions,id',
            // ]);

            $errors = [];
            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'] = "Este campo es obligatorio";
            }else{
                if(strlen($request -> nombre) > 100){
                    $errors['nombre'] = "El nombre no se puede exceder de los 100 caracteres";
                }
            }

            if(!empty($errors)){
                return redirect()->route('sedes.index')->withErrors($errors)->withInput();
            }

            $institucion = Institucion::findOrFail($request->institucion_id);
            if($institucion){
                Sede::create([
                    'nombre' => $institucion->nombre." - ".$request->nombre,
                    'institucion_id' => $request->institucion_id,
                ]);
            }
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('sedes.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('sedes.index');
            }
        }
    }

    public function update(Request $request, $sede_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            // $request->validate([
            //     'nombre' => 'required|string|max:100',
            //     'institucion_id' => 'required|integer|exists:institucions,id',
            // ]);

            $sede = Sede::findOrFail($sede_id);
            $errors = [];
            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'.$sede_id] = "Este campo es obligatorio";
            }else{
                if(strlen($request -> nombre.$sede_id) > 100){
                    $errors['nombre'.$sede_id] = "Exceden los 100 caracteres";
                }
            }

            if(!empty($errors)){
                return redirect()->route('sedes.index')->withErrors($errors)->withInput();
            }

            if($sede){
                $institucion = Institucion::findOrFail($request->institucion_id);
                if($institucion){
                    $sede->update([
                        'nombre' => $request->nombre,
                        'institucion_id' => $request->institucion_id,
                    ]);
                }
            }
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('sedes.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('sedes.index');
            }
        }
    }

    public function activarInactivar(Request $request, $sede_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $sede = Sede::findOrFail($sede_id);
            if($sede){
                $sede->update([
                    'estado' => !$sede->estado,
                ]);
            }
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('sedes.index');
            }
        } catch(Exception $e){
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('sedes.index');
            }
        }
    }

}
