<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Maquina_reservada;
use App\Models\Colaboradores_por_Area;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaquinaReservadaController extends Controller
{
    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        /*
        try {
            $maquinas_reservadas = Maquina_reservada::with([
                'horarios_presenciales' => function ($query) {
                    $query->select('id', 'horario_inicial', 'horario_final', 'dia'); },
                'maquinas' => function ($query) {
                    $query->select('id', 'nombre'); }
            ])->get();

            if (count($maquinas_reservadas) == 0) {
                return response()->json(["resp" => "No hay registros insertados"]);
            }

            return response()->json(["data" => $maquinas_reservadas, "conteo" => count($maquinas_reservadas)]);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }
        */
        $maquina_reservada = Maquina_reservada::all();

        return view('inspiniaViews.maquinareservada.index', compact('maquinareservada'));
    }

    public function asignarColaboradorOld(Request $request, $area_id, $maquina_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $request->validate([
                'colaborador_area_id' => 'required|integer|min:1|max:100',
            ]);
            //Encontramos el área
            $area = Area::findOrFail($area_id);
            //Encontramos los horarios de la área
            $horariosArea = Horario_Presencial_Asignado::where('area_id', $area->id)->get()->pluck('horario_presencial_id');
            //Buscar otras areas con el mismo horario
            $allAreasConcurrentes = Horario_Presencial_Asignado::with('area')->whereIn('horario_presencial_id', $horariosArea)->get()->pluck('area');
            //Filtrar solo las areas activas y obtener su id
            $allActiveAreasConcurrentesId = $allAreasConcurrentes->where('estado', 1)->pluck('id');
            //Encontrar al colaborador del request
            $colaborador_id = Colaboradores_por_Area::findOrFail($request->colaborador_area_id)->colaborador_id;
            //Buscar a los colaboradores concurrentes activos
            $sameColaboradorAreas = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $colaborador_id)->where('estado', 1)->get();
            //Buscar todos los colaboradores de esta área que estén activos
            $colaboradoresAreaId = Colaboradores_por_Area::where('area_id', $area_id)->where('estado', 1)->get()->pluck('id');
            //Obtener la primera máquina reservada que coincida con la máquina y los colaboradores del área
            $maquinaReservadaArea = Maquina_reservada::where('maquina_id', $maquina_id)->whereIn('colaborador_area_id', $colaboradoresAreaId)->first();
            //Si se encuentra la maquina reservada
            if($maquinaReservadaArea) {
                //Se verifica si el colaborador que se quiere asignar es el mismo que ya está asignado a la máquina
                if($maquinaReservadaArea->colaborador_area_id == $request->colaborador_area_id) {
                    //Si es el mismo colaborador, se recorre los colaboradores concurrentes
                    foreach($sameColaboradorAreas as $mismoColab){
                        //Se busca su maquina
                        $maquinaReservada = Maquina_reservada::where('colaborador_area_id', $mismoColab->id)->first();
                        //Se verifica si la encuentra
                        if($maquinaReservada){
                            //Si la encuentra y es diferente a la maquina que se quiere asignar
                            if($maquinaReservada->maquina_id != $maquina_id) {
                                //La elimina
                                $maquinaReservada->delete();
                                //Crea un nuevo registro
                                Maquina_reservada::create([
                                    'maquina_id' => $maquina_id,
                                    'colaborador_area_id' => $mismoColab->id,
                                ]);
                            } //Sino no hara nada
                        } else{
                            //Si no la encuentra la crea
                            Maquina_reservada::create([
                                'maquina_id' => $maquina_id,
                                'colaborador_area_id' => $mismoColab->id,
                            ]);
                        }
                    }
                } else{
                    //Si es un colaborador diferente
                    //Se encuentra al colaborador anterior de la máquina
                    $colaboradorAreaThisMaquinaOld = Colaboradores_por_Area::findOrFail($maquinaReservadaArea->colaborador_area_id);
                    //Se obtienen a sus concurrentes activos
                    $colaboradoresAreaConcurrentesOld = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $colaboradorAreaThisMaquinaOld->colaborador_id)->whereNot('id',$maquinaReservadaArea->colaborador_area_id)->where('estado', 1)->get();
                    //Se recorren los concurrentes del colaborador anterior de la máquina
                    foreach($colaboradoresAreaConcurrentesOld as $colaboradorAreaConcurrenteOld) {
                        //Se obtienen todas las máquinas reservadas por este colaborador concurrente
                        $maquinasReservadasOld = Maquina_reservada::where('maquina_id', $maquina_id)->where('colaborador_area_id', $colaboradorAreaConcurrenteOld->id)->get();
                        //Se recorren las máquinas reservadas por este colaborador concurrente
                        foreach($maquinasReservadasOld as $maquinaReservadaOld) {
                            //Se elimina la máquina reservada de este antiguo colaborador
                            $maquinaReservadaOld->delete();
                        }
                    }
                    //Ahora se actualiza la maquina reservada con el nuevo colaborador
                    $maquinaReservadaArea->update(["colaborador_area_id" => $request->colaborador_area_id]);
                    //Despues verificar si este colaborador que ocupará esta maquina tiene mas de un área y esta activo
                    $thisSameColaboradorAreas = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $colaborador_id)->whereNot('id', $maquinaReservadaArea->colaborador_area_id)->where('estado', 1)->get();
                    //Se recorre
                    foreach($thisSameColaboradorAreas as $thisSame){
                        //Se busca su maquina
                        $maquinaReservada = Maquina_reservada::where('colaborador_area_id', $thisSame->id)->first();
                        //Se verifica si la encuentra
                        if($maquinaReservada){
                            //Se verifica si la maquina encontrada es diferente a la maquina que se quiere asignar
                            if($maquinaReservada->maquina_id != $maquina_id){
                                //Si es diferente se elimina y se crea un nuevo registro con la maquina y colaborador actuales
                                $maquinaReservada->delete();
                                Maquina_reservada::create([
                                    'maquina_id' => $maquina_id,
                                    'colaborador_area_id' => $thisSame->id,
                                ]);
                            }//Sino no hará nada
                        } else{
                            //Si no encuentra la maquina se crea una nueva
                            Maquina_reservada::create([
                                'maquina_id' => $maquina_id,
                                'colaborador_area_id' => $thisSame->id,
                            ]);
                        }
                    }
                }
            } else{
                return "aqui?";
                //Si no se encuentra la maquina en el área, se recorre todos los colaboradores concurrentes del request
                foreach($sameColaboradorAreas as $sameColaboradorArea) {
                    //Se busca si este tiene una maquina
                    $maquinaReservada = Maquina_reservada::where('colaborador_area_id', $sameColaboradorArea->id)->first();
                    if($maquinaReservada) {
                        //Si la tiene se verifica si es diferente a la maquina que se quiere asignar
                        if($maquinaReservada->maquina_id != $maquina_id) {
                            //Si es diiferente se elimina y se crea un nuevo registro con la maquina y colaborador actuales
                            $maquinaReservada->delete();
                            Maquina_reservada::create([
                                'maquina_id' => $maquina_id,
                                'colaborador_area_id' => $sameColaboradorArea->id,
                            ]);
                        }
                    } else{
                        //Si no tiene maquina le crea una nueva
                        Maquina_reservada::create([
                            'maquina_id' => $maquina_id,
                            'colaborador_area_id' => $sameColaboradorArea->id,
                        ]);
                    }
                }
            }
            //Se realiza commit
            DB::commit();
            //Se redirige a la vista de maquinas de la area
            return redirect()->route('areas.getMaquinas', ['area_id' => $area_id]);
        } catch(Exception $e){
            //Si ocurre algún error se hace rollback
            DB::rollBack();
            // return $e;
            // return response()->json(["error" => $e->getMessage()], 500);

            //E igualmente se redirije a la vista de máquinas de la área
            return redirect()->route('areas.getMaquinas', ['area_id' => $area_id]);
        }
    }

    public function asignarColaborador(Request $request, $area_id, $maquina_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $request->validate([
                'colaborador_id' => 'required|integer|min:1|max:100',
            ]);
            //Encontramos el área
            $area = Area::findOrFail($area_id);
            //Encontramos los horarios de la área
            $horariosArea = Horario_Presencial_Asignado::where('area_id', $area->id)->get()->pluck('horario_presencial_id');
            //Buscar otras areas con el mismo horario
            $allAreasConcurrentes = Horario_Presencial_Asignado::with('area')->whereIn('horario_presencial_id', $horariosArea)->get()->pluck('area');
            //Filtrar solo las areas activas y obtener su id
            $allActiveAreasConcurrentesId = $allAreasConcurrentes->where('estado', 1)->pluck('id');
            $allColaboradoresHere = Colaboradores_por_Area::where('estado', 1)->whereIn('area_id', $allActiveAreasConcurrentesId)->get();
            //Encontrar al colaborador del request
            // $colaborador_id = Colaboradores_por_Area::findOrFail($request->colaborador_area_id)->colaborador_id; -----------------------
            $colaborador_id = $request->colaborador_id;
            //Buscar a los colaboradores concurrentes activos
            $sameColaboradorAreas = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $colaborador_id)->where('estado', 1)->get();
            //Buscar todos los colaboradores de esta área que estén activos
            $colaboradoresAreaId = Colaboradores_por_Area::where('area_id', $area_id)->where('estado', 1)->get()->pluck('id');
            //Obtener la primera máquina reservada que coincida con la máquina y los colaboradores del área
            $maquinaReservadaArea = Maquina_reservada::with('colaborador_area')->where('maquina_id', $maquina_id)->whereIn('colaborador_area_id', $allColaboradoresHere->pluck('id'))->first();
            //Si se encuentra la maquina reservada
            if($maquinaReservadaArea) {
                // return "found";
                //Se verifica si el colaborador que se quiere asignar es el mismo que ya está asignado a la máquina
                if($maquinaReservadaArea->colaborador_area->colaborador_id == $request->colaborador_id) {
                    // return "same";
                    //Si es el mismo colaborador, se recorre los colaboradores concurrentes
                    foreach($sameColaboradorAreas as $mismoColab){
                        //Se busca su maquina
                        $maquinaReservada = Maquina_reservada::where('colaborador_area_id', $mismoColab->id)->first();
                        //Se verifica si la encuentra
                        if($maquinaReservada){
                            //Si la encuentra y es diferente a la maquina que se quiere asignar
                            if($maquinaReservada->maquina_id != $maquina_id) {
                                //La elimina
                                $maquinaReservada->delete();
                                //Crea un nuevo registro
                                Maquina_reservada::create([
                                    'maquina_id' => $maquina_id,
                                    'colaborador_area_id' => $mismoColab->id,
                                ]);
                            } //Sino no hara nada
                        } else{
                            //Si no la encuentra la crea
                            Maquina_reservada::create([
                                'maquina_id' => $maquina_id,
                                'colaborador_area_id' => $mismoColab->id,
                            ]);
                        }
                    }
                } else{
                    // return "not same";
                    //Si es un colaborador diferente
                    //Se encuentra al colaborador anterior de la máquina
                    // $colaboradorAreaThisMaquinaOld = Colaboradores_por_Area::findOrFail($maquinaReservadaArea->colaborador_area_id); -------------------
                    $colaboradorAreaThisMaquinaOld = Colaboradores_por_Area::where('area_id', $area_id)->where('colaborador_id', $maquinaReservadaArea->colaborador_area->colaborador_id)->where('estado', 1)->first();
                    //Se obtienen a sus concurrentes activos
                    $colaboradoresAreaConcurrentesOld = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $colaboradorAreaThisMaquinaOld->colaborador_id)->where('estado', 1)->get();
                    //Se recorren los concurrentes del colaborador anterior de la máquina
                    foreach($colaboradoresAreaConcurrentesOld as $colaboradorAreaConcurrenteOld) {
                        //Se obtienen todas las máquinas reservadas por este colaborador concurrente
                        $maquinasReservadasOld = Maquina_reservada::where('maquina_id', $maquina_id)->where('colaborador_area_id', $colaboradorAreaConcurrenteOld->id)->get();
                        //Se recorren las máquinas reservadas por este colaborador concurrente
                        foreach($maquinasReservadasOld as $maquinaReservadaOld) {
                            //Se elimina la máquina reservada de este antiguo colaborador
                            $maquinaReservadaOld->delete();
                        }
                    }
                    //Ahora se actualiza la maquina reservada con el nuevo colaborador
                    // $maquinaReservadaArea->update(["colaborador_area_id" => $request->colaborador_area_id]); ------------------------------
                    //Despues verificar si este colaborador que ocupará esta maquina tiene mas de un área y esta activo
                    $thisSameColaboradorAreas = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $colaborador_id)->/*whereNot('id', $maquinaReservadaArea->colaborador_area_id)->*/where('estado', 1)->get();
                    // return $thisSameColaboradorAreas;
                    //Se recorre
                    foreach($thisSameColaboradorAreas as $thisSame){
                        //Se busca su maquina
                        $maquinaReservada = Maquina_reservada::where('colaborador_area_id', $thisSame->id)->first();
                        //Se verifica si la encuentra
                        if($maquinaReservada){
                            //Se verifica si la maquina encontrada es diferente a la maquina que se quiere asignar
                            if($maquinaReservada->maquina_id != $maquina_id){
                                //Si es diferente se elimina y se crea un nuevo registro con la maquina y colaborador actuales
                                $maquinaReservada->delete();
                                Maquina_reservada::create([
                                    'maquina_id' => $maquina_id,
                                    'colaborador_area_id' => $thisSame->id,
                                ]);
                            }//Sino no hará nada
                        } else{
                            //Si no encuentra la maquina se crea una nueva
                            Maquina_reservada::create([
                                'maquina_id' => $maquina_id,
                                'colaborador_area_id' => $thisSame->id,
                            ]);
                        }
                    }
                }
            } else{
                // return "not found";
                //Si no se encuentra la maquina en el área, se recorre todos los colaboradores concurrentes del request
                foreach($sameColaboradorAreas as $sameColaboradorArea) {
                    //Se busca si este tiene una maquina
                    $maquinaReservada = Maquina_reservada::where('colaborador_area_id', $sameColaboradorArea->id)->first();
                    if($maquinaReservada) {
                        //Si la tiene se verifica si es diferente a la maquina que se quiere asignar
                        if($maquinaReservada->maquina_id != $maquina_id) {
                            //Si es diiferente se elimina y se crea un nuevo registro con la maquina y colaborador actuales
                            $maquinaReservada->delete();
                            Maquina_reservada::create([
                                'maquina_id' => $maquina_id,
                                'colaborador_area_id' => $sameColaboradorArea->id,
                            ]);
                        }
                    } else{
                        //Si no tiene maquina le crea una nueva
                        Maquina_reservada::create([
                            'maquina_id' => $maquina_id,
                            'colaborador_area_id' => $sameColaboradorArea->id,
                        ]);
                    }
                }
            }
            //Se realiza commit
            DB::commit();
            //Se redirige a la vista de maquinas de la area
            return redirect()->route('areas.getMaquinas', ['area_id' => $area_id]);
        } catch(Exception $e){
            //Si ocurre algún error se hace rollback
            DB::rollBack();
            // return $e;
            // return response()->json(["error" => $e->getMessage()], 500);

            //E igualmente se redirije a la vista de máquinas de la área
            return redirect()->route('areas.getMaquinas', ['area_id' => $area_id]);
        }
    }
    public function liberarMaquina($area_id, $maquina_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            //Buscar la maquina reservada
            $maquinaReservadaArea = Maquina_reservada::with('colaborador_area')->findOrFail($maquina_id);
            //Verificar si se encuentra
            if($maquinaReservadaArea){
                //Encontramos los horarios de la área
                $horariosArea = Horario_Presencial_Asignado::where('area_id', $area_id)->get()->pluck('horario_presencial_id');
                //Buscar otras areas con el mismo horario
                $allAreasConcurrentes = Horario_Presencial_Asignado::with('area')->whereIn('horario_presencial_id', $horariosArea)->get()->pluck('area');
                //Filtrar solo las areas activas y obtener su id
                $allActiveAreasConcurrentesId = $allAreasConcurrentes->where('estado', 1)->pluck('id');
                //Obtener solo a los colaboradores de las áreas concurrentes que tengan el mismo colaborador_id y esten activos
                $sameColaboradorAreas = Colaboradores_por_Area::whereIn('area_id', $allActiveAreasConcurrentesId)->where('colaborador_id', $maquinaReservadaArea->colaborador_area->colaborador_id)->where('estado', 1)->get();
                //Recorrer los colaboradores concurrentes
                foreach($sameColaboradorAreas as $sameColaboradorArea) {
                    //Buscar si tienen una maquina reservada en el área actual
                    $maquinasReservadas = Maquina_reservada::where('colaborador_area_id', $sameColaboradorArea->id)->get();
                    //Recorrer las maquinas reservadas por el colaborador concurrente
                    foreach($maquinasReservadas as $maquinaReservada) {
                        //Eliminar la maquina reservada
                        $maquinaReservada->delete();
                    }
                }
            }
            //Realizar commit
            DB::commit();
            //Redirigir a la vista de máquinas del área
            return redirect()->route('areas.getMaquinas', ['area_id' => $area_id]);
        } catch(Exception $e){
            //Si ocurre algún error se hace rollback
            DB::rollBack();
            //Redirigir a la vista de máquinas del área
            return redirect()->route('areas.getMaquinas', ['area_id' => $area_id]);
        }
    }

    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'colaborador_area_id' => 'required|integer|min:1|max:100',
            'maquina_id' => 'required|integer|min:1|max:255'
        ]);

        Maquina_reservada::create([
            "colaborador_area_id" => $request->colaborador_area_id,
            "maquina_id" => $request->maquina_id
        ]);

        return redirect()->route('inspiniaViews.maquinareservada.index');

    }

    public function show($maquina_reservada_id)
    {
        try {
            $maquina_reservada = Maquina_reservada::with([
                'horarios_presenciales' => function ($query) {
                    $query->select('id', 'horario_inicial', 'horario_final', 'dia'); },
                'maquinas' => function ($query) {
                    $query->select('id', 'nombre'); }
            ])->find($maquina_reservada_id);

            if (!$maquina_reservada) {
                return response()->json(["resp" => "No existe un registro con ese id"]);
            }
            return response()->json(["data" => $maquina_reservada]);
        } catch (Exception $e) {
            return response()->json(["error" => $e]);
        }

    }

    public function update(Request $request, $maquina_reservada_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $request->validate([
            'horario_presencial_id' => 'required|string|min:1|max:100',
            'maquina_id' => 'required|string|min:1|max:255'
        ]);

        $maquina_reservada = Maquina_reservada::findOrFail($maquina_reservada_id);

        $maquina_reservada->update($request->all());

        return redirect()->route('inspiniaViews.maquinareservada.index');
    }

    public function destroy($maquina_reservada_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $maquina_reservada = Maquina_reservada::findOrFail($maquina_reservada_id);

        $maquina_reservada->delete();

        return redirect()->route('inspiniaViews.maquinareservada.index');

    }

}
