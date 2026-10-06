<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Candidatos;
use App\Models\Colaboradores;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Hash;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function index(){

        $userData = FunctionHelperController::getUserRol();
        if($userData['isBoss']){
            $areasId = $userData['Jefeareas']->pluck('area_id');
            $areas = [];
            foreach($areasId as $areaId){
                $area = Area::findOrFail($areaId);
                $areas[] = $area;
            }
            $userData['areas'] = $areas;
        }
        // return $userData;
        return view('inspiniaViews.accounts.perfil', ["userData" => $userData]);
    }

    public function update(Request $request){
        DB::beginTransaction();
        try{
            $user = auth()->user();
            $userData = FunctionHelperController::getUserRol();
            $user_id = $user->id;
            //VALIDACIONES
            $errors = [];
            //Name(Requerido, String, Minimo 1)
            if (!isset($request->name)) {
                $errors['name'] = 'El nombre es un campo requerido';
            } else{
                if(!is_string($request->name)){
                    $errors['name'] = 'El nombre debe ser un texto';
                }else{
                    if(strlen($request->name) < 1){
                        $errors['name'] = 'El nombre debe ser mayor a 1 caracter';
                    }
                }
            }
            //Apellido(Requerido, String, Minimo 1)
            if (!isset($request->apellido)) {
                $errors['apellido'] = 'El apellido es un campo requerido';
            } else{
                if(!is_string($request->apellido)){
                    $errors['apellido'] = 'El apellido debe ser un texto';
                }else{
                    if(strlen($request->apellido) < 1){
                        $errors['apellido'] = 'El apellido debe ser mayor a 1 caracter';
                    }
                }
            }
            //Email(Requerido, String, Minimo 1)
            if (!isset($request->email)) {
                $errors['email'] = 'El email es un campo requerido';
            } else{
                if(!is_string($request->email)){
                    $errors['email'] = 'El email debe ser un texto';
                }else{
                    if(strlen($request->email) < 1){
                        $errors['email'] = 'El email debe ser mayor a 1 caracter';
                    }
                }

                $sameUserEmail = User::where('email', $request->email)->whereNot('id', $user_id)->first();
                if($sameUserEmail){
                    $errors['email'] = 'ya hay un usuario registrado con ese email';
                }
                $candidato = FunctionHelperController::findUserCandidato(1, $user->email);
                if($candidato != null){
                    $conflict = FunctionHelperController::verifyEmailCandidatoConflict($request->email, $candidato->id);
                    if($conflict){
                        $errors['email'] = 'ya hay un colaborador usuario registrado con ese email';
                    }
                }
                if($userData['isAdmin']){
                    $conflict = FunctionHelperController::verifyEmailCandidatoConflict($request->email);
                    if($conflict){
                        $errors['email'] = 'ya hay un colaborador registrado con ese email';
                    }
                }

            }
            if(!empty($errors)) {
                $errors['user'] = $user_id;
                // return $errors;
                return redirect()->route('perfil.index')->with('userError', $user_id)->withErrors($errors)->withInput();
            }

            // $candidato = FunctionHelperController::findUserCandidato(1, $user->email);
            FunctionHelperController::modifyColabByUser($user, $request);

            // Actualización de datos del candidato en la tabla 'candidatos'
        $colabEmail = Candidatos::where('correo', $user->email)->first();
        if ($colabEmail) {
            // Verifica si no hay conflictos con el correo antes de actualizarlo
            $colabEmail->update([
                "correo" => $request->email
            ]);
        }

            $user->update([
                "name" => $request->name,
                "apellido" => $request->apellido,
                "correo" => $request->correo
            ]);



            DB::commit();
            return redirect()->route('perfil.index');
        } catch(Exception $e){
            // return $e;
            DB::rollBack();
            return redirect()->route('perfil.index')->with('error', 'Ocurrió un error al realizar la acción.');
        }
    }

    public function updatePassword(Request $request){
        DB::beginTransaction();
        try{
            $user = auth()->user();
            //VALIDACIONES
            $errors = [];
            //Old password (Required, igual a la contraseña actual)
            if(!isset($request->old_password)){
                $errors['old_password'] = 'La contraseña actual es requerida.';
            }else{
                if (!Hash::check($request->old_password, $user->password)) {
                    $errors['old_password'] = 'La contraseña actual no coincide.';
                }
            }
            //New password (Required, min:8, max:100)
            if(!isset($request->new_password)){
                $errors['new_password'] = 'La nueva contraseña es requerida.';
            }else{
                if(strlen($request->new_password) < 8) {
                    $errors['new_password'] = 'La nueva contraseña debe tener al menos 8 caracter.';
                } else if (strlen($request->new_password) > 100) {
                    $errors['new_password'] = 'La nueva contraseña no debe tener más de 100 caracteres.';
                }
            }
            //Confirm password(Required, igual a la nueva contraseña)
            if(!isset($request->confirm_password)){
                $errors['confirm_password'] = 'La contraseña de confirmación es requerida.';
            }else{
                if(!($request->new_password == $request->confirm_password)){
                    $errors['confirm_password'] = 'Las contraseñas no coinciden.';
                }
            }

            if(!empty($errors)){
                return redirect()->route('perfil.index')->withErrors($errors)->withInput();
            }

            //Si la contraseña es diferente cambiarla
            if (!Hash::check($request->new_password, $user->password)) {
                $user->update([
                    "password" => Hash::make($request->new_password)
                ]);

                UsuariosPasswordsController::registrar($user->id, $request->new_password);
            }

            DB::commit();
            return redirect()->route('perfil.index')->with('success', 'Contraseña actualizada correctamente.');
        } catch(Exception $e){
            DB::rollBack();
            return redirect()->route('perfil.index')->with('error', 'Ocurrió un error al realizar la acción.');
        }
    }

}
