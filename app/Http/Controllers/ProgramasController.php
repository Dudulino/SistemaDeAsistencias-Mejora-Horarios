<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProgramasRequest;
use Exception;
use Illuminate\Http\Request;
use App\Models\Programas;
use App\Models\Area;
use Illuminate\Support\Facades\DB;

class ProgramasController extends Controller
{
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $programas = Programas::paginate(12);
        $pageData = FunctionHelperController::getPageData($programas);
        $hasPagination = true;

        return view("inspiniaViews.programas.index", [
            'programas' => $programas,
            'pageData' => $pageData,
            'hasPagination' => $hasPagination,
        ]);
    }

    public function store(StoreProgramasRequest $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            if ($request->hasFile('icono')) {
                $icono = $request->file('icono');
                $nombreIcono = time() . '.' . $icono->getClientOriginalExtension();
                $icono->move(public_path('storage/programas'), $nombreIcono);
            } else {
                $nombreIcono = 'default.png';
            }


            Programas::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'icono' => $nombreIcono
            ]);

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('programas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('programas.index');
            }
        }
    }


    public function show($programa_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $programa = Programas::find($programa_id);

        return response()->json(["data" => $programa]);
    }


    public function update(Request $request, $programa_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            // $request->validate([
            //     "nombre" => "required|string|min:1|max:255",
            //     "descripcion" => "sometimes|string|min:1|max:500",
            //     "icono" => "image"
            // ]);

            $programa = Programas::findOrFail($programa_id);
            $errors = [];

            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'.$programa_id] = "Este campo es obligatorio";
            }else{
                if(strlen($request-> nombre.$programa_id) > 100){
                    $errors['nombre'.$programa_id] = "Excede los 100 caracteres";
                }
            }

            // validacion descripcion
            if(!isset($request->descripcion)){
                $errors['descripcion'.$programa_id] = "Este campo es obligatorio.";
            }else{
                if(strlen($request-> descripcion.$programa_id) > 100){
                    $errors['descripcion'.$programa_id] = "Excede los 100 caracteres";
                }
            }

            // validacion icono
            if ($request->hasFile('icono')) {
                $extension = 'img';
                $extensionVal = $request->file('icono')->getClientOriginalExtension();
                if ($extensionVal !== $extension) {
                    $errors['icono' . $programa_id] = 'El icono debe ser un archivo de tipo: ' . $extension;
                }
            }

            if(!empty($errors)){
                return redirect()->route('programas.index')->withErrors($errors)->withInput();
            }

            $datosActualizar = $request->except(['icono']);

            if ($request->hasFile('icono')) {
                $rutaPublica = public_path('storage/programas');

                // Verificar si el icono actual no es el predeterminado
                if ($programa->icono && $programa->icono !== "default.png") {
                    // Eliminar el icono actual si no es el predeterminado
                    unlink($rutaPublica . '/' . $programa->icono);
                }

                $icono = $request->file('icono');
                $nombreIcono = time() . '.' . $icono->getClientOriginalExtension();

                $icono->move($rutaPublica, $nombreIcono);

                $datosActualizar['icono'] = $nombreIcono;
            }

            $programa->update($datosActualizar);
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('programas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('programas.index');
            }
        }
    }



    public function destroy($programa_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $programa = Programas::findOrFail($programa_id);

        $programa->delete();

        return redirect()->route('programas.index');
    }

    public function activarInactivar(Request $request, $programa_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $programa = Programas::findOrFail($programa_id);

            $programa->estado = !$programa->estado;

            $programa->save();
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('programas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('programas.index');
            }
        }
    }
}
