<?php

namespace App\Http\Controllers;

use App\Models\Maquinas;
use App\Models\Salones;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMaquinasRequest;
use App\Http\Requests\UpdateMaquinasRequest;
use App\Models\Computadora_colaborador;
use App\Models\Maquina_reservada;
use Illuminate\Support\Facades\DB;
use Exception;

class MaquinasController extends Controller
{

    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $maquinas = Maquinas::orderBy('salon_id', 'asc')->paginate(12);

        $salones = Salones::where('estado', 1)->get();

        $pageData = FunctionHelperController::getPageData($maquinas);
        $hasPagination = true;

        return view('inspiniaViews.maquinas.index', [
            'maquinas' => $maquinas,
            'salones' => $salones,
            'pageData' => $pageData,
            'hasPagination' => $hasPagination,
        ]);
    }

    public function store(StoreMaquinasRequest $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{


            Maquinas::create([
                "nombre" => $request->nombre,
                "detalles_tecnicos" => $request->detalles_tecnicos,
                "num_identificador" => $request->num_identificador,
                "salon_id" => $request->salon_id
            ]);

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('maquinas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('maquinas.index');
            }
        }

    }
    public function update(Request $request, $maquina_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{

            
            $maquina = Maquinas::find($maquina_id);
            $errors = [];
            // validacion nombre
            if(!isset($request->nombre)){
                $errors['nombre'.$maquina_id] = "Este campo es obligatorio";
            }else{
                if(strlen($request->nombre) > 100){
                    $errors['nombre'.$maquina_id] = "Excede los 100 caracteres.";
                }
            }

            // validacion detalles_tecnicos
            if(!isset($request->detalles_tecnicos)){
                $errors['detalles_tecnicos'.$maquina_id] = "Este campo es obligatorio";
            }else{
                if(strlen($request->detalles_tecnicos) > 255){
                    $errors['detalles_tecnicos'.$maquina_id] = "Excede los 255 caracteres.";
                }
            }

            // validaciones num_identificador
            if(!isset($request->num_identificador)){
                $errors['num_identificador'.$maquina_id] = "Este campo es obligatorio";
            }

            if(!empty($errors)){
                return redirect()->route('maquinas.index')->withErrors($errors)->withInput();
            }
            $maquina->update($request->all());

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('maquinas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('maquinas.index');
            }
        }
    }

    public function destroy($maquina_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $maquina = Maquinas::findOrFail($maquina_id);

        $maquina->delete();

        return redirect()->route('maquinas.index');
    }

    public function activarInactivar(Request $request, $maquina_id )
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $maquina = Maquinas::findOrFail($maquina_id);

            $maquina->estado = !$maquina->estado;

            $maquina->save();

            if($maquina->estado==0){
                // Buscar las areas que no estan en el request y que estan asociadas al colaborador
                $compuinactivas = Maquina_reservada::where('maquina_id', $maquina_id)->get();
                // Por cada registro encontrado
                foreach ($compuinactivas as $compuinactiva) {
                    //Se inactiva su estado
                    $compuinactiva->delete();
                }
            }

            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('maquinas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('maquinas.index');
            }
        }
    }

}
