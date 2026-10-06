<?php

namespace App\Http\Controllers;

class AjusteController extends Controller
{
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        return view('inspiniaViews.ajustes.index');
    }

// gggg
}
