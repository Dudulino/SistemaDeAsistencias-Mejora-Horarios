<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCursosRequest;
use Illuminate\Http\Request;
use App\Models\Cursos;
use Illuminate\Support\Facades\DB;
use Exception;

class CursosController extends Controller
{
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $cursos = Cursos::paginate(12);

        $pageData = FunctionHelperController::getPageData($cursos);
        $hasPagination = true;

        return view('inspiniaViews.cursos.index', [
            'cursos' => $cursos,
            'pageData' => $pageData,
            'hasPagination' => $hasPagination
        ]);
    }


    public function store(StoreCursosRequest $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            /*
            Cursos::create([
                'nombre' => $request->nombre,
                'categoria' => $request->categoria,
                'duracion' => $request->duracion
            ]);
            */

            //pruebas
            $errors = [];
            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->nombre) > 100){
                    $errors['nombre'] = "El curso no se puede exceder de los 100 caracteres";
                }
            }


            if(!empty($errors)){
                return redirect()->route('cursos.index')->withErrors($errors)->withInput();
            }


            Cursos::create([
                'nombre' => $request->nombre,
                'categoria' => $request->categoria,
                'duracion' => $request->duracion
            ]);
            //

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('cursos.index');
            }
        } catch(Exception $e){
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('cursos.index');
            }
        }


    }
    public function update(Request $request, $curso_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            $curso = Cursos::findOrFail($curso_id);
            $errors = [];

            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'.$curso_id] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->nombre) > 100){
                    $errors['nombre'.$curso_id] = "Exceden de los 100 caracteres";
                }
            }

            // validacion categoria
            if(!isset($request->categoria)){
                $errors['categoria'.$curso_id] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->nombre) > 100){
                    $errors['categoria'.$curso_id] = "Excede los 100 caracteres";
                }
            }

            // validacion duracion
            if(!isset($request->duracion)){
                $errors['duracion'.$curso_id] = "Este campo es obligatorio.";
            }else{
                if(strlen($request->duracion > 15)){
                    $errors['duracion'.$curso_id] = "Excede los 15 caracteres";
                }
            }

            if(!empty($errors)){
                return redirect()->route('cursos.index')->withErrors($errors)->withInput();
            }
            $curso->fill([
                "nombre" => $request->nombre,
                "categoria" => $request->categoria,
                "duracion" => $request->duracion
            ])->save();

            DB::commit();

            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('cursos.index');
            }
        } catch(Exception $e){
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('cursos.index');
            }
        }

    }


    // public function destroy($curso_id)
    // {
    //     $curso = Cursos::findOrFail($curso_id);

    //     $curso->delete();

    //     return redirect()->route('cursos.index');

    // }

    public function activarInactivar(Request $request, $curso_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $curso = Cursos::findOrFail($curso_id);

            $curso->estado = !$curso->estado;

            $curso->save();

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('cursos.index');
            }
        } catch(Exception $e){
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('cursos.index');
            }
        }
    }
}
