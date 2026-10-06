<?php

namespace App\Http\Controllers;

use App\Models\Institucion;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInstitucionRequest;
use App\Http\Requests\UpdateInstitucionRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class InstitucionController extends Controller
{

    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $institucion = Institucion::paginate(12);

        $pageData = FunctionHelperController::getPageData($institucion);
        $hasPagination = true;

        // return response()->json(['data' => $institucion]);
        return view('inspiniaViews.institucion.index', [
            'institucion' => $institucion,
            'pageData' => $pageData,
            'hasPagination' => $hasPagination
        ]);

    }
    public function getAll()
    {

        $instituciones = Institucion::get();

        return response()->json(['data' => $instituciones]);

    }

public function storeJSON(Request $request)
    {
        DB::beginTransaction();
        try{
            $request->validate([
                        'nombre' => 'required|string|min:1|max:100',
                    ]);

            institucion::create([
                'nombre' => $request->nombre,
            ]);

            DB::commit();
            return response()->json(["resp" => "Registro creado exitosamente"]);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(["msg" => "Ocurrió un error", "error" => $e->getMessage()]);
        }
    }

public function updateJSON(Request $request, $institucion_id)
    {
        DB::beginTransaction();
        try{
            $request->validate([
                'nombre' => 'sometimes|string|min:1|max:100',
                'estado' => 'sometimes|boolean'
            ]);

            $institucion = Institucion::findOrFail($institucion_id);

            $institucion->update($request->all());

            DB::commit();
            return response()->json(["resp" => "Registro actualizado exitosamente"]);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(["msg" => "Ocurrió un error", "error" => $e->getMessage()]);
        }

    }

public function activarInactivarJSON(Request $request,$institucion_id)
    {
        DB::beginTransaction();
        try{
            $institucion = Institucion::findOrFail($institucion_id);

            $institucion->estado = !$institucion->estado;

            $institucion->save();

            DB::commit();
            if($institucion->estado == true){
                return response()->json(["resp" => $institucion->nombre." Activado exitosamente"]);
            } else{
                return response()->json(["resp" => $institucion->nombre." Inactivado exitosamente"]);
            }
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json(["msg" => "Ocurrió un error", "error" => $e->getMessage()]);
        }
    }


    public function store(StoreInstitucionRequest $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            institucion::create([
                'nombre' => $request->nombre,
            ]);

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('institucion.index');
            }
        } catch(Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('institucion.index');
            }
        }
    }

    public function update(Request $request, $institucion_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            $institucion = Institucion::findOrFail($institucion_id);

            $errors = [];

            if(!isset($request->nombre)){
                $errors['nombre'.$institucion_id] = "Este campo es obligatorio";
            }else{
                if(strlen($request->nombre) > 100){
                    $errors['nombre'.$institucion_id] = "Exceden los 100 caracteres";
                }
            }

            if(!empty($errors)){
                return redirect()->route('institucion.index')->withErrors($errors)->withInput();
            }
            $institucion->update($request->all());

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('institucion.index');
            }
        } catch(Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('institucion.index');
            }
        }

    }


    public function destroy($institucion_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $institucion = Institucion::findOrFail($institucion_id);

        $institucion->delete();

        return redirect()->route('institucion.index');

    }

    public function activarInactivar(Request $request,$institucion_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $institucion = Institucion::findOrFail($institucion_id);

            $institucion->estado = !$institucion->estado;

            $institucion->save();

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('institucion.index');
            }
        } catch(Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('institucion.index');
            }
        }
    }

}
