<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objetos;
use Illuminate\Support\Facades\DB;
use Exception;

class ObjetoController extends Controller
{
    public function index(){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $objetos = Objetos::paginate(12);

        $pageData = FunctionHelperController::getPageData($objetos);
        $hasPagination = true;
        //return $objetos;

        return view('inspiniaViews.objetos.index', [
            "objetos" => $objetos,
            "pageData" => $pageData,
            "hasPagination" => $hasPagination,
        ]);
    }

    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            // $request->validate([
            //     'nombre' => 'required|string|min:1|max:100',
            //     'descripcion' => 'required|string|min:1|max:300',
            // ]);

            $errors = [];

            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->nombre) > 100) {
                    $errors['nombre'] = "Excede los 100 caracteres";
                }
            }

            // validacion descripcion
            if(!isset($request->descripcion)){
                $errors['descripcion'] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->descripcion) > 100){
                    $errors['descripcion'] = "Exceder los 100 caracteres";
                }
            }

            // redireccion con errores
            if(!empty($errors)){
                return redirect()->route('objeto.index')->withErrors($errors)->withInput();
            }

            Objetos::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion
            ]);

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('objeto.index');
            }
        } catch(Exception $e){
            DB::rollBack();
                if($request->currentURL) {
                    return redirect($request->currentURL);
                } else {
                    return redirect()->route('objeto.index');
                }
        }

    }

    public function update(Request $request, $objeto_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            // $request->validate([
            //     'nombre' => 'sometimes|string|min:1|max:100',
            //     'descripcion' => 'sometimes|string|min:1|max:300',
            //     'estado' => 'sometimes|boolean'
            // ]);

            $objeto = Objetos::findOrFail($objeto_id);

            $errors = [];

            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'.$objeto_id] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->nombre) > 100) {
                    $errors['nombre'.$objeto_id] = "Excede los 100 caracteres";
                }
            }

            // validacion descripcion
            if(!isset($request->descripcion)){
                $errors['descripcion'.$objeto_id] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->descripcion) > 100){
                    $errors['descripcion'.$objeto_id] = "Excede los 100 caracteres";
                }
            }

            // redireccion con errores
            if(!empty($errors)){
                return redirect()->route('objeto.index')->withErrors($errors)->withInput();
            }

            $objeto->update($request->all());

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('objeto.index');
            }
        } catch(Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('objeto.index');
            }
        }
    }

    public function destroy($objeto_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $objeto = Objetos::findOrFail($objeto_id);

        $objeto->delete();

        return redirect()->route('objeto.index');
    }

    public function activarInactivar(Request $request, $objeto_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $objeto = Objetos::findOrFail($objeto_id);

            $objeto->estado = !$objeto->estado;

            $objeto->save();

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('objeto.index');
            }
        } catch(Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('objeto.index');
            }
        }

    }
}
