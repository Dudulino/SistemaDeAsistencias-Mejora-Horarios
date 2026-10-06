<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Colaboradores_por_Area;
use App\Models\Horario_de_Clases;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Horarios_Presenciales;
use App\Models\Maquinas;
use App\Models\Maquina_reservada;
use App\Models\Candidatos;
use App\Models\Colaboradores;
use App\Models\Salones;
use Illuminate\Http\Request;
use App\Http\Requests\StoreareaRequest;
use App\Http\Requests\UpdateareaRequest;
use App\Models\User;
use App\Models\AreaSemanaDesactivacion;
use App\Models\Proyecto;
use App\Models\UsuarioJefeArea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AreaController extends Controller
{
    function getAreaWithIntegrantes($areas){
        foreach($areas as $area){
            $area->hasBoss = false;
            // $colaboradoresAreaCount = Colaboradores_por_Area::where('area_id', $area->id)->where('estado', 1)->count();
            $integrantesArea = Colaboradores_por_Area::where('area_id', $area->id)->where('estado', 1)->get();
            foreach($integrantesArea as $integrante){
                if($integrante->jefe_area){
                    $area->hasBoss = true;
                    break;
                }
            }
            $colaboradoresAreaCount = $integrantesArea->count();
            $area->integrantes = $integrantesArea;
            $area->count_colabs = $colaboradoresAreaCount;

            $area->proyectos = Proyecto::where('area_id', $area->id)->where('estado', 1)->orderBy('created_at', 'desc')->limit(3)->get();
            $area->count_proyectos = $area->proyectos->count();
        }
        return $areas;
    }
    /**
     * INDEX
     *
     * Se encarga de mostrar todos los registros de áreas en la vista index.blade.php de la carpeta areas.
     *
     * @response 200 vista index.blade.php con todas las áreas
     *
     */
    public function index(Request $request){
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $buscar = $request->buscar_area;
        $warning = null;
        if($buscar) {

            $resultado = $this->buscarAreas($buscar );

            $areas = $resultado['areas'];
            $warning = $resultado['warning'];

        } else {
            //Recuperar todos los registros en áreas
            $areas = Area::with(['salon', 'ultima_desactivacion'])->paginate(12);
        }
        // return auth()->user();

        $salones = Salones::where('estado', 1)->get();
        $pageData = FunctionHelperController::getPageData($areas);
        $hasPagination = true;
        // foreach ($areas as $area) {
        //     $colaboradoresAreaCount = Colaboradores_por_Area::where('area_id', $area->id)->where('estado', 1)->count();
        //     $area->count_colabs = $colaboradoresAreaCount;
        // }
        $areas = $this->getAreaWithIntegrantes($areas);
        $countAreas = Area::where('estado', 1)->count();
        $countColabs = Colaboradores::where('estado', 1)->count();
        // $desactivacionFechaArea = AreaSemanaDesactivacion::with('area')->orderBy('created_at', 'desc');

        // return response()->json(["areas" => $areas]);
        //Redirigir a la vista mandando las áreas

        return view('inspiniaViews.areas.index', [
            'areas' => $areas,
            'hasPagination' => $hasPagination,
            'pageData' => $pageData,
            'salones' => $salones,
            'countAreas' => $countAreas,
            'countColabs' => $countColabs,
            'warning' => $warning
            // 'desactivacionFechaArea' => $desactivacionFechaArea
        ]);
    }

    public function getFormHorarios($area_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        // Encontrar area
        $area = Area::findOrFail($area_id);
        // Encontrar el id de los colaboradores del área
        $colaboradoresAreaId = Colaboradores_por_Area::where('estado', true)->where('area_id', $area_id)->pluck('colaborador_id');
        // Encontrar los días de clase de esos colaboradores
        // $horariosColaboradores = Horario_de_Clases::whereIn('colaborador_id', $colaboradoresAreaId)->get();
        // return $horariosColaboradores;
        // Obtener todos los Horarios presenciales disponibles
        $horariosPresenciales = Horarios_Presenciales::all();
        // return $horariosPresenciales;
        // Array para las horas ocupadas de los colaboradores
        // $horasOcupadas = [];
        // Recorrer los horarios de los colaboradores
        // foreach ($horariosColaboradores as $horarioColab) {
        //     $dia = $horarioColab->dia;
        //     $horaInicial = strtotime($horarioColab->hora_inicial);
        //     $horaFinal = strtotime($horarioColab->hora_final);

        //     // Por cada hora en el rango, agregar la hora al array de horas ocupadas para ese día
        //     for ($hora = $horaInicial; $hora <= $horaFinal; $hora += 3600) {
        //         //Agregar key dia y dentro de cada uno las horas que están ocupados durante ese día
        //         $horasOcupadas[$dia][] = date('H', $hora);
        //     }
        // }
        // Array para los horarios disponibles
        $horariosDisponibles = [];
        // Recorrer todos los Horarios Presenciales
        foreach ($horariosPresenciales as $horarioPres) {
            $diaPres = $horarioPres->dia;
            $horaInicialPres = strtotime($horarioPres->hora_inicial);
            $horaFinalPres = strtotime($horarioPres->hora_final);
            $rangoHorasPres = [];

            // Rellenar el rango de horas del horario presencial
            for ($hora = $horaInicialPres; $hora <= $horaFinalPres; $hora += 3600) {
                $rangoHorasPres[] = date('H', $hora);

            }
            // return $rangoHorasPres;
            $disponible = true;

            // // Comprobar si alguna de las horas del horario presencial coincide con las horas ocupadas
            // if (isset($horasOcupadas[$diaPres])) {
            //     // error_log($diaPres);
            //     //Recorrer el rango de horas presenciales
            //     foreach ($rangoHorasPres as $hora) {
            //         //Si la hora está dentro de las horas ocupadas del día
            //         if (in_array($hora, $horasOcupadas[$diaPres])) {
            //             //Este horario no estará disponible
            //             $disponible = false;
            //             break;
            //         }
            //     }
            // }
            //Si disponible es true
            if ($disponible) {
                //Se agrega el horario disponible al array de horarios disponibles
                $horariosDisponibles[] = $horarioPres;
            }
        }
        //Verificar si el área tiene horario asignado
        $hasHorario = false;
        //Ver si el area tiene horarios asignados
        $horarioAsignado = Horario_Presencial_Asignado::with('horario_presencial')->where('area_id', $area_id)->get();
        //Array de horariosFormateados
        $horariosFormateados = [];
        //Si el area tiene horarios asignados
        if (count($horarioAsignado) > 0) {
            //Se marca hasHorario como true
            $hasHorario = true;
            //Se recorre el horarios asignado
            foreach ($horarioAsignado as $horario) {
                $horaInicial = (int) date('H', strtotime($horario->horario_presencial->hora_inicial));
                $horaFinal = (int) date('H', strtotime($horario->horario_presencial->hora_final));
                //Se formatean las horas y se guarda en el array
                $horariosFormateados[] = [
                    'hora_inicial' => $horaInicial,
                    'hora_final' => $horaFinal,
                    'dia' => $horario->horario_presencial->dia,
                ];
            }

        } else {
            // Si no tiene horarios asignados, se marca hasHorario como false
            $hasHorario = false;
        }
        // Recorrer los horarios disponibles
        foreach ($horariosDisponibles as $horario) {
            //En caso sea igual al horario asignado, darle un campo "using" true
            //Si hasHorario es true
            if ($hasHorario) {
                //Se recorre cada horario Asignado
                foreach ($horarioAsignado as $horarioAsig) {
                    //Si el horario asignado es igual al horario disponible
                    if ($horarioAsig->horario_presencial_id == $horario->id) {
                        // Se marca como en uso
                        $horario->using = true;
                        break;
                    } else {
                        //Sino se marca como false
                        $horario->using = false;
                    }
                }
            } else {
                //Si no tiene horario asignado, se marca como false
                $horario->using = false;
            }
        }

        // return response()->json(["area" => $area, "horariosDisponibles" => $horariosDisponibles, "horarioAsignado" => $horarioAsignado, "horariosFormateados" => $horariosFormateados, "hasHorario" => $hasHorario]);
        return view('inspiniaViews.areas.gestHorarios', [
            "area" => $area,
            "horariosDisponibles" => $horariosDisponibles,
            "horarioAsignado" => $horarioAsignado,
            "horariosFormateados" => $horariosFormateados,
            "hasHorario" => $hasHorario
        ]);


    }


    public function getMaquinasByArea($area_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $area = Area::findOrFail($area_id);
        //Datos esta area
        $horariosArea = Horario_Presencial_Asignado::where('area_id', $area->id)->get()->pluck('horario_presencial_id');
        //Buscar otras areas con el mismo horario
        $allAreasConcurrentes = Horario_Presencial_Asignado::with('area')->whereIn('horario_presencial_id', $horariosArea)
            ->whereNot('area_id', $area->id)->get()->pluck('area');

        $areasConcurrentesActivas = $allAreasConcurrentes->where('estado', 1);
        //Areas del mismo salon
        $salonAreasConcurrentesId = $areasConcurrentesActivas->where('salon_id', $area->salon_id)->pluck('id');
        $totalAreaConcurrentesId = Horario_Presencial_Asignado::with('area')->whereIn('horario_presencial_id', $horariosArea)->get()->pluck('area')->where('estado', 1)->where('salon_id', $area->salon_id)->pluck('id');

        $colaboradoresActivosID = Colaboradores::whereNot('estado', 2)->get()->pluck('id');
        $colaboradoresOtherAreas = Colaboradores_por_Area::with('colaborador')->where('estado', 1)->whereIn('colaborador_id', $colaboradoresActivosID)->whereIn('area_id', $salonAreasConcurrentesId)->get();

        $colaboradoresThisArea = Colaboradores_por_Area::with('colaborador')->where('estado', 1)->whereIn('colaborador_id', $colaboradoresActivosID)->where('area_id', $area->id)->get();
        $colaboradoresBaseThisArea = $colaboradoresThisArea->pluck('colaborador');
        // return $colaboradoresBaseThisAreaID->candidato;
        foreach ($colaboradoresThisArea as $colabHere) {
            foreach ($colaboradoresOtherAreas as $key => $colabThere) {
                if ($colabHere->colaborador_id == $colabThere->colaborador_id) {
                    //Añadir el colab there a la lista de colaboradores de esta area
                    // $colaboradoresThisArea->push($colabThere); //Ya no agregar para evitar duplicados, se modificará el asignador de maquinas para que al asignar o editar lo haga con todas los colaboradores de ese colaborador que tengan horarios concurrentes
                    //Quitar colabThere de su colección original
                    // $colaboradoresOtherAreas->forget($key);
                }

            }
        }
        // $colaboradoresThisArea = $colaboradoresThisArea->unique('colaborador_id');
        $colaboradoresOtherAreasId = $colaboradoresOtherAreas->pluck('id');
        $maquinasOtherAreas = Maquina_reservada::with('colaborador_area')->whereIn('colaborador_area_id', $colaboradoresOtherAreasId)->get();
        // return $maquinasOtherAreas;

        // return ["this" => $colaboradoresThisArea, "other" => $colaboradoresOtherAreas];
        $colaboradoresThisAreaId = $colaboradoresThisArea->pluck('id');
        $maquinasThisArea = Maquina_reservada::with('colaborador_area')->whereIn('colaborador_area_id', $colaboradoresThisAreaId)->get();


        // return $colaboradoresThisArea->pluck('id');
        //Todas las maquinas de este salon
        $maquinas = Maquinas::where('salon_id', $area->salon_id)->where('estado', 1)->get();
        // return $maquinas;

        foreach ($maquinas as $maquina) {
            $maquina->estaArea = false;
            $maquina->otraArea = false;
            $maquina->maquina_reservada_id = null;
            $maquina->colaborador = 'Sin asignar';
            $maquina->colaborador_id = null;
            $maquina->colaborador_base_id = null;
            foreach ($maquinasOtherAreas as $otherMaquina) {
                if ($maquina->id === $otherMaquina->maquina_id) {
                    $areaNombre = $otherMaquina->colaborador_area->area->especializacion;
                    $maquina->otraArea = true;
                    $maquina->colaborador = 'Asignada a otra area (' . $areaNombre . ')';
                    $maquina->colaborador_base_id = $otherMaquina->colaborador_area->colaborador_id;
                    $maquina->colaborador_id = $otherMaquina->colaborador_area_id;
                    $maquina->maquina_reservada_id = $otherMaquina->id;
                }
            }
            foreach ($maquinasThisArea as $thisMaquina) {
                if ($maquina->id === $thisMaquina->maquina_id) {
                    $maquina->estaArea = true;
                    $colaborador = Colaboradores::where('id', $thisMaquina->colaborador_area->colaborador_id)->first();
                    $candidato = $colaborador->candidato;
                    $maquina->colaborador = $candidato->nombre . " " . $candidato->apellido;
                    $maquina->colaborador_id = $thisMaquina->colaborador_area_id;
                    $maquina->colaborador_base_id = $colaborador->id;
                    $maquina->maquina_reservada_id = $thisMaquina->id;
                }
            }

            foreach ($colaboradoresBaseThisArea as $colabBase) {
                if ($maquina->colaborador_base_id === $colabBase->id) {
                    // return [$maquina->colaborador_base_id , $colabBase->id];
                    $maquina->estaArea = true;
                    $maquina->otraArea = false;
                    $candidato = $colabBase->candidato;
                    $maquina->colaborador = $candidato->nombre . " " . $candidato->apellido;
                }
            }


        }

        foreach ($colaboradoresBaseThisArea as $colaboradorArea) {
            $colaboradorArea->hasMaquina = false;
            $candidato = Candidatos::findOrFail($colaboradorArea->candidato_id);
            $colaboradorArea->nombre = $candidato->nombre . " " . $candidato->apellido;
            $allColabsAreasID = Colaboradores_por_Area::where('colaborador_id', $colaboradorArea->id)->whereIn('area_id', $totalAreaConcurrentesId)->where('estado', 1)->get()->pluck('id');

            // if($colaboradorArea->nombre == "Karla Lopez"){
            //     return $allColabsAreasID;

            // }
            $maquina = Maquina_reservada::whereIn('colaborador_area_id', $allColabsAreasID)->first();
            if ($maquina) {
                $colaboradorArea->hasMaquina = true;
            }

        }

        // return response()->json(['maquina' => $maquinas, 'colaboradoresArea' => $colaboradoresBaseThisArea, 'area' => $area]);
        return view('inspiniaViews.areas.maquinas', [
            'maquinas' => $maquinas,
            'area' => $area,
            'colaboradoresArea' => $colaboradoresBaseThisArea,
            'colaboradoresAreaBase' => $colaboradoresBaseThisArea,
        ]);
    }


    /**
     *  STORE
     *
     *  Se encarga de guardar los datos de un área en la base de datos. Con imagen definida o default.
     *
     *  @bodyParam especializacion string required Especialización del área.
     *  @bodyParam descripcion string required Descripción del área.
     *  @bodyParam color_hex string required Color hexadecimal del área.
     *  @bodyParam icono file Imagen del área. Ejemplo: "icono.png" (opcional)
     *
     *  @response 200 Ruta de redirección a la vista index.blade.php.
     *
     *  @response 400 {"message": "Debe ingresar la especialización"}
     *  @response 400 {"message": "Debe ingresar la descripción"}
     *  @response 400 {"message": "Debe ingresar el color"}
     *
     *
     */
    public function store(Request $request)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        //Se inicia la transacción
        DB::beginTransaction();
        try {

            $errors = [];

            // validacion especializacion
            if (!isset($request->especializacion)) {
                $errors['especializacion'] = "Este campo es obligatorio.";
            } else {
                if (strlen($request->especializacion) > 100) {
                    $errors['especializacion'] = "Excede los 100 caracteres.";
                }
            }

            // validacion descripcion
            if (!isset($request->descripcion)) {
                $errors['descripcion'] = "Este campo es obligatorio.";
            } else {
                if (strlen($request->descripcion) > 250) {
                    $errors['descripcion'] = "Excede los 250 caracteres.";
                }
            }

            // validacion color_hex
            if (!isset($request->color_hex)) {
                $errors['color_hex'] = "Este campo es obligatorio.";
            }

            if (!empty($errors)) {
                return redirect()->route('areas.index')->withErrors($errors)->withInput();
            }
            //Validar que los datos no esten vacios
            // if(!$request->especializacion) return response()->json(["message" => "Debe ingresar la especialización"]);
            // if(!$request->descripcion) return response()->json(["message" => "Debe ingresar la descripcion"]);
            // if(!$request->color_hex) return response()->json(["message" => "Debe ingresar el color"]);

            //Validar que la imagen haya sido ingresada
            if ($request->hasFile('icono')) {
                //Si se ingresó se guarda en la variable icono
                $icono = $request->file('icono');
                //Le asignamos un nombre único con la fecha y hora exacta actual y la extensión del archivo
                $nombreIcono = time() . '.' . $icono->getClientOriginalExtension();
                //Se mueve la imagen a la carpeta storage/areas con el nombre único que se le asignó anteriormente
                $icono->move(public_path('storage/areas'), $nombreIcono);
            } else {
                //Si no se ingresó se asigna el nombre de la imagen por defecto
                $nombreIcono = 'Default.png';
            }

            //Area::create($request->all());
            //Se crea un nuevo registro con  los datos requeridos y la imagen definida o default
            Area::create([
                'especializacion' => $request->especializacion,
                'descripcion' => $request->descripcion,
                'color_hex' => $request->color_hex,
                'salon_id' => $request->salon_id,
                'icono' => $nombreIcono
            ]);
            //Se confirma la transacción
            DB::commit();
            //return response()->json(["resp" => "Área creada correctamente"]);
            //Se redirige a la vista index.blade.php de la carpeta areas
            // if ($request->currentURL) {
            //     return redirect($request->currentURL);
            // } else {
            //     return redirect()->route('areas.index');
            // }
            return redirect()->route('areas.index');
        } catch (Exception $e) {
            //Si ocurre algún error
            //Se revierte la transacción
            DB::rollback();
            if ($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('areas.index');
            }
            //Se retorna un mensaje avisando que hubo un error con la información del error
            // return response()->json(["message" => "Error al crear el registro", "error" => $e->getMessage()]);
        }
    }

    /**
     *  UPDATE
     *
     *  Se encarga de actualizar los datos de un área en la base de datos.
     *
     *  @urlParam  area_id integer required ID del área a actualizar.
     *
     *  @bodyParam especializacion string Especialización del área.
     *  @bodyParam descripcion string Descripción del área.
     *  @bodyParam color_hex string Color hexadecimal del área.
     *  @bodyParam icono file Imagen del área.
     *
     *  @response 200 Ruta de redirección a la vista de áreas.
     *
     */

    public function update(Request $request, $area_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        //Se inicia la transacción
        DB::beginTransaction();
        try {

            $errors = [];

            // validacion especializacion
            if (!isset($request->especializacion)) {
                $errors['especializacion' . $area_id] = "Este campo es obligatorio.";
            } else {
                if (strlen($request->especializacion) > 100) {
                    $errors['especializacion' . $area_id] = "Excede los 100 caracteres.";
                }
            }

            // validacion descripcion
            if (!isset($request->descripcion)) {
                $errors['descripcion' . $area_id] = "Este campo es obligatorio.";
            } else {
                if (strlen($request->descripcion) > 250) {
                    $errors['descripcion' . $area_id] = "Excede los 250 caracteres.";
                }
            }

            // validacion descripcion
            if (!isset($request->color_hex)) {
                $errors['color_hex' . $area_id] = "Este campo es obligatorio.";
            }

            if (!empty($errors)) {
                return redirect()->route('areas.index')->withErrors($errors)->withInput();
            }


            //Se busca el área por el id ingresado como parámetro
            $area = Area::findOrFail($area_id);

            if ($request->has('jefe_area_id')) {
                $jefeArea = Colaboradores_por_Area::where('area_id', $area->id)->where('jefe_area', 1)->first();

                if ($jefeArea) {
                    $previousUser = User::where('email', $jefeArea->colaborador->candidato->correo)->first();
                    if ($previousUser) {
                        UsuarioJefeArea::where('user_id', $previousUser->id)
                            ->where('area_id', $area->id)
                            ->delete();
                    }

                    if (isset($request->jefe_area_id) && $request->jefe_area_id != 0) {
                        if ($request->jefe_area_id != $jefeArea->id) {
                            // 3. Reasignar el nuevo jefe
                            $newJefeArea = Colaboradores_por_Area::findOrFail($request->jefe_area_id);
                            if ($newJefeArea && $newJefeArea->estado == 1) {
                                $newJefeArea->update(["jefe_area" => 1]);

                                $candidato = $newJefeArea->colaborador->candidato;
                                if ($candidato && $candidato->correo) {
                                    $user = User::where('email', $candidato->correo)->first();
                                    if ($user) {
                                        UsuarioJefeArea::updateOrCreate(
                                            ['user_id' => $user->id, 'area_id' => $area->id],
                                            ['estado' => 1] // Activo
                                        );
                                    }
                                }
                            }
                            $jefeArea->update(["jefe_area" => 0]);
                        } else {
                            if ($previousUser) {
                                UsuarioJefeArea::updateOrCreate(
                                    ['user_id' => $previousUser->id, 'area_id' => $area->id],
                                    ['estado' => 1] // Activo
                                );
                            }
                        }
                    } else {
                        $jefeArea->update(["jefe_area" => 0]);
                    }
                } else {
                    if (isset($request->jefe_area_id) && $request->jefe_area_id != 0) {
                        $newJefeArea = Colaboradores_por_Area::findOrFail($request->jefe_area_id);
                        if ($newJefeArea && $newJefeArea->estado == 1) {
                            $newJefeArea->update(["jefe_area" => 1]);

                            $candidato = $newJefeArea->colaborador->candidato;
                            if ($candidato && $candidato->correo) {
                                $user = User::where('email', $candidato->correo)->first();
                                if ($user) {
                                    // Registrar al nuevo jefe en usuario_jefe_areas
                                    UsuarioJefeArea::updateOrCreate(
                                        ['user_id' => $user->id, 'area_id' => $area->id],
                                        ['estado' => 1] // Activo
                                    );
                                }
                            }
                        }
                    }
                }
            }

            //Se asignan los valores ingresados por el usuario a las variables correspondientes, si no se ingresó nada se asigna el valor actual de la base de datos
            $especializacion = !$request->especializacion ? $area->especializacion : $request->especializacion;
            $descripcion = !$request->descripcion ? $area->descripcion : $request->descripcion;
            $color_hex = !$request->color_hex ? $area->color_hex : $request->color_hex;
            $salon_id = !$request->salon_id ? $area->salon_id : $request->salon_id;
            //Se crea un array con los datos a actualizar
            $datosActualizar = [
                "especializacion" => $especializacion,
                "descripcion" => $descripcion,
                "color_hex" => $color_hex,
                "salon_id" => $salon_id
            ];
            //Se verifica si se ingresó la imagen
            if ($request->hasFile('icono')) {
                //Si existe buscamos la ruta publica
                $rutaPublica = public_path('storage/areas');
                //Si existe la imagen y no es la imagen por defecto, se elimina la imagen anterior
                if ($area->icono) {
                    if($area->icono != 'Default.png'){
                        unlink($rutaPublica . '/' . $area->icono);
                    }
                }
                //Se obtiene la imagen ingresada por el usuario
                $icono = $request->file('icono');
                //Se obtiene el nombre de la imagen ingresada por el usuario y se le agrega la extensión de la imagen
                $nombreIcono = time() . '.' . $icono->getClientOriginalExtension();
                //Se mueve la imagen a la ruta publica con el nombre de la imagen definido anteriormente
                $icono->move($rutaPublica, $nombreIcono);
                //Se agrega el nombre de la imagen al array de datos a actualizar
                $datosActualizar['icono'] = $nombreIcono;
            }
            //Se actualiza el área con los datos ingresados por el usuario
            $area->update($datosActualizar);
            //Se confirma la transacción
            DB::commit();
            //Se redirige a la vista index.blade.php de la carpeta areas
            if ($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('areas.index');
            }
        } catch (Exception $e) {
            // return $e;
            //Si ocurre algún error
            //Se revierte la transacción
            DB::rollBack();
            if ($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('areas.index');
            }
            //Se retorna un mensaje avisando que hubo un error con la información del error
            // return response()->json(["message" => "Hubo un error", "error" => $e->getMessage()]);
        }
    }

    public function activarInactivar(Request $request, $area_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try {
            $area = Area::findOrFail($area_id);

            // $area->estado = !$area->estado;

            // $area->save();
            $area->update(["estado" => !$area->estado]);

            if ($area->estado == 0) {
                $colaboradoresArea = Colaboradores_por_Area::where('estado', 1)->where('area_id', $area_id)->get();
                AreaController::removeJefeArea($area_id);
                // Por cada registro encontrado
                foreach ($colaboradoresArea as $colaboradorArea) {
                    //Se inactiva su estado
                    $colaboradorArea->update(['estado' => false]);
                    //Crear registro de inactivación
                    RegistroActividadController::crearRegistro($colaboradorArea->id, false);
                    //Se busca si tiene computadoras
                    $ColabMachines = Maquina_reservada::where('colaborador_area_id', $colaboradorArea->id)->get();
                    //Recorrer maquinas encontradas
                    foreach ($ColabMachines as $machine) {
                        //Eliminar maquinas
                        $machine->delete();
                    }
                }
            }


            if ($area->estado == 1) {
                // Buscar las areas que no estan en el request y que estan asociadas al colaborador
                $areasInactivas = Colaboradores_por_Area::where('colaborador_id', $area_id)->where('estado', 1)->where('area_id', $area_id)->get();
                // Por cada registro encontrado
                foreach ($areasInactivas as $areaInactiva) {
                    //Se inactiva su estado
                    $areaInactiva->update(['estado' => 0]);
                    //Crear registro de inactivación
                    RegistroActividadController::crearRegistro($areaInactiva->id, false);
                    //Se busca si tiene computadoras
                    $ColabMachines = Maquina_reservada::where('colaborador_area_id', $areaInactiva->id)->get();
                    //Recorrer maquinas encontradas
                    foreach ($ColabMachines as $machine) {
                        //Eliminar maquinas
                        $machine->delete();
                    }
                }
            }

            DB::commit();
            if ($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('areas.index');
            }
        } catch (Exception $e) {
            DB::rollBack();
            if ($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('areas.index');
            }
        }
    }

    public function showArea($area_id)
    {
        // $access = FunctionHelperController::verifyAreaAccess($area_id);

        // if (!$access) {
        //     return redirect()->route('dashboard')->with('error', 'No es un usuario con permisos para visualizar esa area. No lo intente denuevo o puede ser baneado.');
        // }

        $area = Area::findOrFail($area_id);
        if ($area) {
            //Encontrar horarios del área
            $horarioAsignado = Horario_Presencial_Asignado::with('horario_presencial')->where('area_id', $area_id)->get();
            //Array de horariosFormateados
            $horariosFormateados = [];
            //Se marca hasHorario como true
            //Se recorre el horarios asignado
            foreach ($horarioAsignado as $horario) {
                $horaInicial = (int) date('H', strtotime($horario->horario_presencial->hora_inicial));
                $horaFinal = (int) date('H', strtotime($horario->horario_presencial->hora_final));
                //Se formatean las horas y se guarda en el array
                $horariosFormateados[] = [
                    'hora_inicial' => $horaInicial,
                    'hora_final' => $horaFinal,
                    'dia' => $horario->horario_presencial->dia,
                ];
            }

            //Encontrar Colaboradores del área
            $colaboradoresArea = Colaboradores_por_Area::where('area_id', $area_id)->where('estado', 1)->get();
            // return [$area, $horariosFormateados, $colaboradoresArea];
            return view('inspiniaViews.areas.showArea', [
                "area" => $area,
                "horariosFormateados" => $horariosFormateados,
                "colaboradores" => $colaboradoresArea,
            ]);
        }

    }

    public static function removeJefeArea($area_id){
        //Buscar al jefe de area
        $jefe_area = Colaboradores_por_Area::where('area_id', $area_id)->where('jefe_area', 1)->first();
        //Si existe se le quita el puesto de jefe
        if($jefe_area){
            $jefe_area->update(["jefe_area" => 0]);
        }

    }

    // public function desactivarEvaluaciones(Request $request, $area_id) {
    //     $access = FunctionHelperController::verifyAdminAccess();
    //     if (!$access) {
    //         return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
    //     }

    //     $fecha_inicio = $request->fecha_inicio;
    //     $fecha_fin = $request->fecha_fin;

    //     $area = Area::findOrFail($area_id);

    //     DB::beginTransaction();
    //     try {

    //         AreaSemanaDesactivacion::create([
    //             'area_id' => $area_id,
    //             'fecha_inicio' => $fecha_inicio,
    //             'fecha_fin' => $fecha_fin
    //         ]);

    //         DB::commit();
    //         return redirect()->route('areas.index')->with('success','Evaluaciones desactivadas con éxito.');

    //     } catch (Exception $e) {

    //         DB::rollBack();
    //         return redirect()->route('areas.index')->with('error','Ocurrió un error. Vuélvelo a intentarlo más tarde.');

    //     }
    // }

    // public function updateDesactivacion(Request $request, $area_id) {

    //     $access = FunctionHelperController::verifyAdminAccess();
    //     if (!$access) {
    //         return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
    //     }

    //     $area = Area::findOrFail($area_id);

    //     $fecha_inicio = $request->fecha_inicio;
    //     $fecha_fin = $request->fecha_fin;

    //     try {

    //         $area->update([
    //             'fecha_inicio' => $fecha_inicio,
    //             'fecha_fin' => $fecha_fin
    //         ]);

    //         DB::commit();
    //         return redirect()->route('areas.index')->with('success','Evaluaciones desactivadas con éxito.');

    //     } catch (Exception $e) {

    //         DB::rollBack();
    //         return redirect()->route('areas.index')->with('error','Ocurrió un error. Vuélvelo a intentarlo más tarde.');

    //     }


    public function updateDesactivacion(Request $request, $area_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        DB::beginTransaction();
        try {

            // $desactivacion = AreaSemanaDesactivacion::where('area_id', $area_id)->first();

            AreaSemanaDesactivacion::create([
                'area_id' => $area_id,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin
            ]);

            DB::commit();
            return redirect()->route('areas.index')->with('success','Evaluaciones desactivadas hasta el: '.$fecha_fin);

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('areas.index')->with('error','Ocurrió un error. Vuélvelo a intentarlo más tarde.');
        }
    }

    public function buscarAreas($busqueda) {
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $consulta = Area::with(['salon', 'ultima_desactivacion'])
                    ->orderBy('especializacion', 'asc');

        if(!empty($busqueda)) {
            $consulta->where('especializacion', 'LIKE', '%' . $busqueda . '%');
        }

        $areas = $consulta->paginate(12);

        $warning = null;

        if($areas->isEmpty()) {
            $warning = 'No se encontraron áreas que coincidan con su búsqueda';
        }


        return [
            'areas' => $areas,
            'warning' => $warning
        ];
    }
    public function crearproyecto(Request $request) {
        try {
        //     // traer el objeto area
        //     $areaPerteneciente = $this->verifyUserJefeArea();

        //     $cantidadProyectos = Proyecto::where('area_id', $areaPerteneciente->id)->count();

        // if ($cantidadProyectos >= 3) {
        //     return redirect()->route('proyectos.index')
        //         ->with('warning', 'Ya has registrado el máximo de 3 proyectos permitidos.');
        // }

            // store del proyecto con dicha area
            DB::beginTransaction();
            Proyecto::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'porcentaje' => $request->porcentaje,
                'estado' => 1,
                'area_id' => $request->area_id,
                'user_id' => Auth::id(),

            ]);

            DB::commit();
            return redirect()->route('areas.index')->with('success', 'Proyecto creado correctamente');

        } catch (Exception $e) {

            DB::rollBack();
            //  return $e;
            return redirect()->route('areas.index')->with('error', 'Ocurrió un error, inténtelo más tarde o contacte con el equipo de soporte');

        }
    }

}




