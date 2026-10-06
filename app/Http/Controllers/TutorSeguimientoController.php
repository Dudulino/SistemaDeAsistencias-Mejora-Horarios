<?php

namespace App\Http\Controllers;

use App\Models\Especialista;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TutorSeguimientoController extends Controller
{
    public function index(){
        $especialistas = Especialista::get();

        return view('inspiniaViews.especialistas.index', [
            'especialistas' => $especialistas
        ]);
    }


    public function store(Request $request) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {
            $errors = [];

            // campo nombres
            if(!isset($request->nombres)) {
                $errors['nombres'] = 'Campo obligatorio';
            }else if (strlen($request->nombres) > 350) {
                $errors['nombres'] = 'Exceso de caracteres';
            }


            // campo correo
            if(!isset($request->correo)) {
                $errors['correo'] = 'Campo obligatorio';
            }else if(Especialista::where('correo', $request->correo)->exists()){
                $errors['correo'] = 'Correo en uso';
            }

            // campo celular
            if(!isset($request->celular)){
                $errors['celular'] = 'Campo obligatorio';
            }else if(strlen($request->celular) != 9){
                $errors['celular'] = 'Solo 9 dígitos';
            }else if(Especialista::where('celular', $request->celular)->exists()){
                $errors['celular'] = 'Celular en uso';
            }

            if(!empty($errors)) {
                return redirect()->route('especialista.index')->withErrors($errors)->withInput();
            }

            Especialista::create([
                'nombres' => $request->nombres,
                'correo' => $request->correo,
                'celular' => $request->celular,
            ]);

            DB::commit();
            return redirect()->route('especialista.index');
            // return $request;

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('especialista.index');
        }
    }

    public function update(Request $request, $especialista_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {
            $especialista = Especialista::findOrFail($especialista_id);

             // campo nombres
             if(!isset($request->nombres)) {
                $errors['nombres'.$especialista_id] = 'Campo obligatorio';
            }else if (strlen($request->nombres) > 350) {
                $errors['nombres'.$especialista_id] = 'Exceso de caracteres';
            }


            // campo correo
            if(!isset($request->correo)) {
                $errors['correo'.$especialista_id] = 'Campo obligatorio';
            }else {
                $especialistas = Especialista::where('correo', $request->correo)->get();
                foreach($especialistas as $especialista){
                    if($especialista->id != $especialista_id) {
                        $errors['correo'.$especialista_id] = 'Correo en uso';
                        break;
                    }
                }
            }

            // campo celular
            if(!isset($request->celular)){
                $errors['celular'.$especialista_id] = 'Campo obligatorio';
            }else if(strlen($request->celular) != 9){
                $errors['celular'.$especialista_id] = 'Solo 9 dígitos';
            }else {
                $especialistas = Especialista::where('celular', $request->celular)->get();
                foreach($especialistas as $especialista){
                    if($especialista->id != $especialista_id) {
                        $errors['celular'.$especialista_id] = 'Celular en uso';
                        break;
                    }
                }
            }

            if(!empty($errors)) {
                return redirect()->route('especialista.index')->withErrors($errors)->withInput();
            }

            $especialista->update([
                'nombres' => $request->nombres,
                'correo' => $request->correo,
                'celular' => $request->celular,
            ]);

            DB::commit();
            return redirect()->route('especialista.index');
            // return $request;
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->route('especialista.index');
        }
    }

    public function changeState(Request $request, $especialista_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            $especialista = Especialista::findOrFail($especialista_id);

            $especialista->update([
                'estado' => !$especialista->estado,
            ]);

            DB::commit();
            return redirect()->route('especialista.index');
            // return $request;
        } catch (Exception $e){
            DB::rollBack();
            return redirect()->route('especialista.index');
        }
    }

    public function show() {

    }
}
