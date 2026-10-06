<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index(){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $colaboradoresEmpresa = Colaboradores::whereNot('estado', 2)->get();
        $colaboradores = FunctionHelperController::colaboradoresConArea($colaboradoresEmpresa);
        return view('inspiniaViews.reportes.index', [
            "colaboradores" => $colaboradores
        ]);
    }
}
