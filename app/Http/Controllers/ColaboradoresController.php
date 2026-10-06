<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FunctionHelperController;
use App\Http\Requests\StoreColaboradoresRequest;
use App\Models\Actividades;
use App\Models\Area;
use App\Models\AreaRecreativa;
use App\Models\Asistentes_Clase;
use App\Models\Colaboradores;
use App\Models\Candidatos;
use App\Models\Especialista;
use App\Models\Colaboradores_por_Area;
use App\Models\ColaboradoresApoyoAreas;
use App\Models\Computadora_colaborador;
use App\Models\Cumplio_Responsabilidad_Semanal;
use App\Models\Horario_de_Clases;
use App\Models\Horarios_Presenciales;
use App\Models\PagoColaborador;
use App\Models\Distrito;
use App\Models\Institucion;
use App\Models\Carrera;
use App\Models\Maquina_reservada;
use App\Models\Prestamos_objetos_por_colaborador;
use App\Models\Programas;
use App\Http\Requests\UpdateColaboradoresRequest;
use App\Mail\UsuarioCreadoMailable;
use App\Models\ColabPassword;
use App\Models\ColaboradorLibro;
use App\Models\Horario_Presencial_Asignado;
use App\Models\IntegrantesReuniones;
use App\Models\Programas_instalados;
use App\Models\Registro_Mantenimiento;
use App\Models\RegistroActividad;
use App\Models\Sede;
use App\Models\ReunionesProgramadas;
use App\Models\Semanas;
use App\Models\User;
use App\Models\UsuarioJefeArea;
use App\Models\UsuarioColaborador;
use App\Models\UsuariosPasswords;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Str;

class ColaboradoresController extends Controller
{
    function getColaboradoresPromedioStatus($colaboradores){
        foreach($colaboradores as $colaborador){
            $colaborador->status = ["type" => "Sin Evaluar", "color" => "#888", "message" => "El colaborador no ha sido evaluado aún."];
            $semanas = FunctionHelperController::semanasColaborador($colaborador->id);
            $semanasEvaluadasCount = FunctionHelperController::getConteoMaximoSemanasEvaluadasColaborador($colaborador->id);
            if($semanasEvaluadasCount > 0){
                $promedioArray = FunctionHelperController::promedioColaborador($colaborador->id, $semanas['semanas']);
                $promedio = $promedioArray['promedio'];
                $colaborador->promedio = $promedio;
                if($promedio < 11){
                    $colaborador->status = ["type" => "reprobado", "color" => "#b00", "message" => "El colaborador tiene un promedio general reprobado. Promedio general: ".$promedio];
                } else if($promedio >= 11 && $promedio < 14) {
                    $colaborador->status = ["type" => "riesgo", "color" => "#fa0", "message" => "El colaborador tiene un promedio general en riesgo de reprobar. Promedio general: ".$promedio];
                } else{
                    $colaborador->status = ["type" => "aprobado", "color" => "#0b0", "message" => "El colaborador tiene un promedio general aprobado. Promedio general: ".$promedio];
                }
            }
        }
        return $colaboradores;
    }

    public function asignarColorJefesArea($colaboradores)
    {
        $colaboradoresConColor = [];
        // $correosJefesAreaActivos = UsuarioJefeArea::where('estado', 1)->with('user')->get()->pluck('user.email')->toArray();

        foreach ($colaboradores as $colaborador) {
            $jefeDeArea = Colaboradores_por_Area::where("colaborador_id", $colaborador->id)->where("estado", 1)->where("jefe_area", 1)->first();


            // $correoColaborador = $colaborador->candidato->correo;

            // if (in_array($correoColaborador, $correosJefesAreaActivos)) {
            //     $colaborador->estadoJefe = [
            //         'color' => '#264b90',
            //         'message' => 'Jefe de Área'
            //     ];

            //     $colaboradoresConColor[] = $colaborador;
            // }
            if ($jefeDeArea) {
                $colaborador->estadoJefe = [
                    'color' => '#264b90',
                    'message' => 'Jefe de Área'
                ];

                $colaboradoresConColor[] = $colaborador;
            }
        }
        return $colaboradoresConColor;
    }


    public function getHoursColab() {
        $horasAsignadas = Horario_Presencial_Asignado::with('horario_presencial', 'area')->get();
        $colaboradores_area = Colaboradores_por_Area::with('colaborador', 'area')->get();
        $colaboradoresHoras = [];

        foreach ($colaboradores_area as $asignacion) {
            $colaboradorId = $asignacion->colaborador_id;
            $colaborador = $asignacion->colaborador;

            if ($colaborador->estado == 0) {
                $colaboradoresHoras[$colaboradorId] = [
                    'colaborador_id' => $colaboradorId,
                    'horasPracticas' => 0
                ];
                continue;
            }

            if (!isset($colaboradoresHoras[$colaboradorId])) {
                $colaboradoresHoras[$colaboradorId] = [
                    'colaborador_id' => $colaboradorId,
                    'horasPracticas' => 0
                ];
            }

            foreach ($horasAsignadas as $horas) {
                if ($asignacion->area_id == $horas->area_id) {
                    $horaInicial = Carbon::createFromFormat('H:i', $horas->horario_presencial->hora_inicial);
                    $horaFinal = Carbon::createFromFormat('H:i', $horas->horario_presencial->hora_final);
                    $diferenciaHoras = $horaFinal->diffInHours($horaInicial);
                    $colaboradoresHoras[$colaboradorId]['horasPracticas'] += $diferenciaHoras;
                }
            }
        }

        return $colaboradoresHoras;
    }


    public function index()
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $countColaboradores = Colaboradores::with('candidato')->whereNot('estado', 2)->get()->count();
        $colaboradores = Colaboradores::with('candidato', 'especialista')->whereNot('estado', 2)->paginate(12);
        $distritos = Distrito::get();

        $colaboradoresCol = $this->asignarColorJefesArea($colaboradores);

        $colaboradores = $this->getColaboradoresPromedioStatus($colaboradores);

        $especialistas = Especialista::where('estado', 1)->get();

        $sedesAll = Sede::with('institucion')->orderBy('nombre', 'asc')->get();
        $institucionesAll = Institucion::orderBy('nombre', 'asc')->get();
        $carrerasAll = Carrera::orderBy('nombre', 'asc')->get();
        $areasAll = Area::orderBy('especializacion', 'asc')->get();
        $ciclosAll = [4,5,6,7,8,9,10];

        $sedes = $sedesAll->where('estado', 1);
        $instituciones = $institucionesAll->where('estado', 1);
        $carreras = $carrerasAll->where('estado', 1);
        $areas = $areasAll->where('estado', 1);

        $colabsActividades = AreaRecreativaController::getColabActividades($colaboradores->items());
        // return $colabsActividades;
        $colaboradoresConArea = FunctionHelperController::colaboradoresConArea($colabsActividades);
        $colaboradores->data = $colaboradoresConArea;
        // return $colaboradores;
        $pageData = FunctionHelperController::getPageData($colaboradores);
        $hasPagination = true;

        // horas practicadas de cada colaborador
        $horasTotales = $this->getHoursColab();
        foreach ($colaboradores->data as &$colaborador) {
            $horasPracticas = collect($horasTotales)
                ->firstWhere('colaborador_id', $colaborador->id)['horasPracticas'] ?? 0;
            $colaborador->horasPracticas = $horasPracticas;

            $correo = $colaborador->candidato->correo;
            $colaborador->hasUser = User::where('email', $correo)->exists();
        }


        // return $colaboradores;
        $Allactividades = Actividades::where('estado', 1)->get();
        return view('inspiniaViews.colaboradores.index', [
            'colaboradores' => $colaboradores,
            'countColaboradores' => $countColaboradores,
            'hasPagination' => $hasPagination,
            'pageData' => $pageData,
            'sedes' => $sedes,
            'especialistas' => $especialistas,
            'instituciones' => $instituciones,
            'carreras' => $carreras,
            'areas' => $areas,
            'ciclosAll' => $ciclosAll,
            'sedesAll' => $sedesAll,
            'institucionesAll' => $institucionesAll,
            'carrerasAll' => $carrerasAll,
            'areasAll' => $areasAll,
            'Allactividades' => $Allactividades,
            'horasTotales' => $horasTotales,
            'colaboradoresCol' => $colaboradoresCol,
            'distritos' => $distritos
        ]);
    }

    public function getComputadoraColaborador($colaborador_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $colaborador = Colaboradores::with('candidato')->findOrFail($colaborador_id);

        $computerColab = Computadora_colaborador::where('colaborador_id', $colaborador_id)->first();

        $hasComputer = false;
        $incidencias = [];
        $ultimaIncidencia = null;
        $programas = Programas::where('estado', 1)->orderBy('nombre', 'asc')->get();
        $programasInstalados = [];
        if($computerColab) {
            $hasComputer = true;
            $incidencias = Registro_Mantenimiento::where('computadora_id', $computerColab->id)->where('estado', true)->get();
            $ultimaIncidencia = Registro_Mantenimiento::where('computadora_id', $computerColab->id)->orderBy('fecha', 'desc')->first();
            $programasInstalados = Programas_instalados::with('programa')->where('computadora_id', $computerColab->id)->where('estado', 1)->get();
        }

        $procesadores = [
            'Core I8',
            'Core I5',
            'Core I3',
        ];

        $almacenamientos = [
            '8 GBytes',
            '7 GBytes',
            '6 GBytes',
        ];


        // return response()->json(["colaborador" => $colaborador, "computerColab" => $computerColab, "hasComputer" => $hasComputer, "incidencias" => $incidencias, "ultimaIncidencia" => $ultimaIncidencia, "programas" => $programas,"programasInstalados" => $programasInstalados]);

        return view('inspiniaViews.computadoras.compuColab', [
            'colaborador' => $colaborador,
            'computerColab' => $computerColab,
            'hasComputer' => $hasComputer,
            'incidencias' => $incidencias,
            'ultimaIncidencia' => $ultimaIncidencia,
            'programas' => $programas,
            'programasInstalados' => $programasInstalados,
            'procesadores' => $procesadores,
            'almacenamientos' => $almacenamientos,
        ]);
    }

    //FUNCTION getObjetoColabodaor

    public function filtrarColaboradores(string $estados = '0,1,2', string $areas = '', string $carreras = '', string $instituciones = '', string $ciclos = '', string $sedes = '', string $computadoras = ''){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        $distritos = Distrito::with('candidato')->get();
        // Validamos los request de los filtros que queremos aplicar
        $ciclos = $ciclos ? explode(',', $ciclos): [];
        $estados = explode(',', $estados);
        $areas = $areas ? explode(',', $areas) : [];
        $carreras = $carreras ? explode(',', $carreras) : [];
        $instituciones = $instituciones ? explode(',', $instituciones) : [];
        $sedes = $sedes ? explode(',', $sedes) : [];
        $computadoras = $computadoras ? explode(',', $computadoras) : [];

        $sedesAll = Sede::with('institucion')->orderBy('nombre', 'asc')->get();
        $institucionesAll = Institucion::orderBy('nombre', 'asc')->get();
        $carrerasAll = Carrera::orderBy('nombre', 'asc')->get();
        $areasAll = Area::orderBy('especializacion', 'asc')->get();

        $especialistas = Especialista::where('estado', 1)->get();

        $ciclosAll = [4,5,6,7,8,9,10];

        $sedesFiltradas = $sedesAll->where('estado', 1);
        $institucionesFiltradas = $institucionesAll->where('estado', 1);
        $carrerasFiltradas = $carrerasAll->where('estado', 1);
        $areasFiltradas = $areasAll->where('estado', 1);

        $requestCarreras = empty($carreras) ? $carrerasAll->pluck('id')->toArray() : $carreras;
        $requestInstituciones = empty($instituciones) ? $institucionesAll->pluck('id')->toArray() : $instituciones;
        $requestAreas = empty($areas) ? $areasAll->pluck('id')->toArray() : $areas;
        $estadoAreas = empty($areas) ? [1,0] : [1];
        $requestCiclos = empty($ciclos) ? $ciclosAll : $ciclos;

        // justsedes
        $requestSedes = empty($sedes) ? $sedesAll->pluck('id') : $sedes;

        // Obtenemos a los colaboradores filtrados por áreas
        $colaboradoresAreaId = Colaboradores_por_Area::with('colaborador')
            ->whereIn('area_id', $requestAreas)
            ->whereIn('estado', $estadoAreas)
            ->get()
            ->pluck('colaborador_id')->toArray();

        $colaboradoresApoyoArea = ColaboradoresApoyoAreas::whereIn('area_id', $requestAreas)
            ->whereIn('estado', $estadoAreas)
            ->get()
            ->pluck('colaborador_id')
            ->toArray();

        // Unimos los IDs de colaboradores de ambas tablas
        $colaboradoresAreaId = array_merge($colaboradoresAreaId, $colaboradoresApoyoArea);

        // Filtramos por los estados
        $colaboradoresArea = Colaboradores::whereIn('id', $colaboradoresAreaId)
            ->whereIn('estado', $estados)
            ->get();

        $colaboradoresCandidatoId = $colaboradoresArea->pluck('candidato_id');

        // Creamos un array unificado de sedes válidas
        $validSedesIds = [];

        // Si no hay filtros específicos, usamos todas las sedes
        if(empty($sedes) && empty($instituciones)) {
            $validSedesIds = $sedesAll->pluck('id')->toArray();
        } else {
            // Si hay instituciones, obtenemos sus sedes
            if(!empty($instituciones)) {
                $sedesInstitucionesId = Sede::whereIn('institucion_id', $requestInstituciones)->pluck('id')->toArray();
                $validSedesIds = array_merge($validSedesIds, $sedesInstitucionesId);
            }

            // Si hay sedes específicas, las añadimos
            if(!empty($sedes)) {
                $validSedesIds = array_merge($validSedesIds, $sedes);
            }

            // Eliminamos duplicados
            $validSedesIds = array_unique($validSedesIds);
        }

        // Filtramos los candidatos con un solo whereIn para sede_id
        $candidatosFiltradosId = Candidatos::whereIn('id', $colaboradoresCandidatoId)
            ->whereIn('carrera_id', $requestCarreras)
            ->whereIn('sede_id', $validSedesIds)
            ->whereIn('ciclo_de_estudiante', $requestCiclos)
            ->pluck('id');

        // Consulta base para los colaboradores
        $colaboradoresQuery = Colaboradores::with('candidato')
            ->whereIn('candidato_id', $candidatosFiltradosId);

        // Aplicamos filtro de computadoras si se especifica
        if (!empty($computadoras) && !in_array('0', $computadoras)) {
            $colaboradoresConComputadora = Computadora_colaborador::pluck('colaborador_id')->toArray();

            $tieneRegistradas = in_array('registradas', $computadoras);
            $tieneNoRegistradas = in_array('no_registradas', $computadoras);

            if ($tieneRegistradas && !$tieneNoRegistradas) {
                // Solo colaboradores CON computadora registrada
                $colaboradoresQuery = $colaboradoresQuery->whereIn('id', $colaboradoresConComputadora);
            } elseif (!$tieneRegistradas && $tieneNoRegistradas) {
                // Solo colaboradores SIN computadora registrada
                $colaboradoresQuery = $colaboradoresQuery->whereNotIn('id', $colaboradoresConComputadora);
            }
            // Si ambas están seleccionadas, no aplicamos filtro (mostrar todos)
        }

        // Aplicamos ordenamiento especial solo si estado es 2 y es el único estado
        if(count($estados) === 1 && $estados[0] === "2") {
            $colaboradoresQuery = $colaboradoresQuery->orderBy('updated_at', 'desc');
        }

        // Obtenemos el count antes de paginar
        $countColaboradores = $colaboradoresQuery->count();
        $colaboradores = $colaboradoresQuery->paginate(12);
        $colaboradoresCol = $this->asignarColorJefesArea($colaboradores);

        $colaboradores = $this->getColaboradoresPromedioStatus($colaboradores);
        $colabsActividades = AreaRecreativaController::getColabActividades($colaboradores->items());
        $colaboradoresConArea = FunctionHelperController::colaboradoresConArea($colabsActividades);
        $colaboradores->data = $colaboradoresConArea;
        $pageData = FunctionHelperController::getPageData($colaboradores);
        $hasPagination = true;
        $Allactividades = Actividades::where('estado', 1)->get();

        // horas practicadas de cada colaborador
        $horasTotales = $this->getHoursColab();
        foreach ($colaboradores->data as &$colaborador) {
            $horasPracticas = collect($horasTotales)
                ->firstWhere('colaborador_id', $colaborador->id)['horasPracticas'] ?? 0;
            $colaborador->horasPracticas = $horasPracticas;
            $correo = $colaborador->candidato->correo;
            $colaborador->hasUser = User::where('email', $correo)->exists();

            // Verificar si tiene computadora registrada
            $colaborador->tieneComputadora = Computadora_colaborador::where('colaborador_id', $colaborador->id)->exists();
        }

        return view('inspiniaViews.colaboradores.index', [
            'colaboradores' => $colaboradores,
            'countColaboradores' => $countColaboradores,
            'hasPagination' => $hasPagination,
            'pageData' => $pageData,
            'distritos' => $distritos,
            'especialistas' => $especialistas,
            'sedes' => $sedesFiltradas,
            'instituciones' => $institucionesFiltradas,
            'carreras' => $carrerasFiltradas,
            'areas' => $areasFiltradas,
            'sedesAll' => $sedesAll,
            'institucionesAll' => $institucionesAll,
            'carrerasAll' => $carrerasAll,
            'areasAll' => $areasAll,
            'Allactividades' => $Allactividades,
            'horasTotales' => $horasTotales,
            'ciclosAll' => $ciclosAll,
            'colaboradoresCol' => $colaboradoresCol
        ]);
    }

    public function store(StoreColaboradoresRequest $request)
    {
        // return $request;
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            // return $request;
            //Se busca al candidato por su id
            $candidato = Candidatos::findOrFail($request->candidato_id);
            if ($candidato->estado == 1) {
                $colaborador = Colaboradores::create(['candidato_id' => $request->candidato_id]);

                $semana = FunctionHelperController::findOrCreateNextWeek();

                 $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@_';
                 $randomPassword = substr(str_shuffle(str_repeat($characters, 12)), 0, 12);

                if (empty($candidato->correo)) {
                    return redirect()->back()->with('error', 'El correo no puede estar vacío.');
                }

                $user = User::create([
                     'name' => $candidato->nombre,
                     'apellido' => $candidato->apellido,
                     'email' => $candidato->correo,
                     'password' => Hash::make($randomPassword),
                 ]);

                 UsuarioColaborador::create([
                    'user_id' => $user->id
                ]);

                 // Registrar al usuario con la contraseña generada
                 UsuariosPasswordsController::registrar($user->id, $randomPassword);

                // Verificar que el candidato tiene un correo válido
                 if (filter_var($candidato->correo, FILTER_VALIDATE_EMAIL)) {
                     // Enviar email con las credenciales
                     Mail::to($candidato->correo)->send(
                         new UsuarioCreadoMailable(
                             $candidato->correo,
                             $randomPassword,
                             $candidato->nombre . " " . $candidato->apellido,
                             'Colaborador'
                         )
                     );
                 } else {
                     throw new Exception('El correo electrónico del candidato no es válido.');
                 }


                if($request->areas_id){
                    foreach($request->areas_id as $area_id){
                        Colaboradores_por_Area::create([
                            'colaborador_id' => $colaborador->id,
                            'area_id' => $area_id,
                            'semana_inicio_id' => $semana->id,
                        ]);
                    }
                }

                foreach ($request->horarios as $horario) {
                    Horario_de_Clases::create([
                        'colaborador_id' => $colaborador->id,
                        'hora_inicial' => $horario['hora_inicial'],
                        'hora_final' => $horario['hora_final'],
                        'dia' => $horario['dia'],
                        'justificacion' => $horario['justificacion'],
                    ]);
                }

                $candidato->estado = 0;
                $candidato->save();
            }

            DB::commit();
            //Se redirige a la vista de colaboradores
            return redirect()->route('colaboradores.index');
        } catch (Exception $e) {
            // return $e;
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al registrar al colaborador, si el problema persite, contacte a su equipo de soporte.');

        }

    }

    public function update(Request $request, $colaborador_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $returnRoute = route('colaboradores.index');

            if(isset($request->currentURL)) {
                $returnRoute = $request->currentURL;
            }

            //Encontrar al colaborador con su candidato por su id
            $colaborador = Colaboradores::with('candidato')->findOrFail($colaborador_id);
            //ERRORES
            $errors = [];

            // Nombre (Requerido, maximo 100 caracteres)
            if(!isset($request->nombre)){
                $errors['nombre'.$colaborador_id] = 'El nombre es un campo requerido.';
            } else{
                if(strlen($request->nombre) > 100){
                    $errors['nombre'.$colaborador_id] = 'El nombre no puede exceder los 100 caracteres.';
                }
            }

            //apellido
            if(!isset($request->apellido)){
                $errors['apellido'.$colaborador_id] = 'El apellido es un campo requerido';
            }else{
                if(strlen($request->apellido) > 100){
                    $errors['apellido'.$colaborador_id] = 'El apellido no puede exceder los 100 caracteres.';
                }
            }

            //dirección
            if(isset($request->direccion)){
                if (strlen($request->direccion) > 200) {
                    $errors['direccion'.$colaborador_id] = 'La dirección no puede exceder los 200 caracteres.';
                }
            }

            // Ícono
        if ($request->hasFile('icono')) {
            $extensiones = ['jpeg', 'png', 'jpg', 'svg', 'webp'];
            $extensionVal = $request->file('icono')->getClientOriginalExtension();

            if (!in_array($extensionVal, $extensiones)) {
                $errors['icono'.$colaborador_id] = 'El icono debe ser un archivo de tipo: ' . implode(', ', $extensiones);
            }
        }

        // Validación de DNI
            if (isset($request->dni)) {
                if(strlen($request->dni) !== 8){

                    // Verificar si el DNI está definido y tiene 8 caracteres
                    $errors['dni'.$colaborador_id] = 'El DNI debe contener 8 caracteres.';
                } else {
                    // Verificar si el DNI ya está en uso
                    $candidatos = Candidatos::where('dni', $request->dni)->get();
                    foreach ($candidatos as $cand) {
                        if ($cand->id != $colaborador->candidato_id) {
                            $errors['dni'.$colaborador_id] = 'El DNI ya está en uso.';
                            break;
                        }
                    }
                }
            }

            // validacion id_senati
            if(isset($request->id_senati)) {
                $candidatos = Candidatos::where('id_senati', $request->id_senati)->get();
                foreach($candidatos as $candidato){
                    if($candidato->id != $colaborador->candidato_id) {
                        $errors['id_senati'.$colaborador_id] = 'El ID ya está en uso.';
                        break;
                    }
                }
            }

            // Verificar correo
            if(isset($request->correo)){
                $candidatos = Candidatos::where('correo', $request->correo)->get();
                foreach($candidatos as $candidato){
                    if($candidato->id != $colaborador->candidato_id) {
                        $errors['correo'.$colaborador_id] = 'El correo ya está en uso.';
                        break;
                    }
                }
            }

             // Validación de Celular
            if (isset($request->celular) && strlen($request->celular) !== 9) {
                $errors['celular'.$colaborador_id] = 'El celular debe contener 9 números.';
            } else if (isset($request->celular)) {
                $candidatos = Candidatos::where('celular', $request->celular)->get();
                foreach ($candidatos as $cand) {
                    if ($cand->id != $colaborador->candidato_id) {
                        $errors['celular'.$colaborador_id] = 'El celular ya está en uso.';
                        break;
                    }
                }
            }


            // return $errors;
            // Si hay errores, redirigir con todos ellos
            if(!empty($errors)) {
                return redirect($returnRoute)->withErrors($errors)->withInput();
            }
            //Asignar el candidato a una variable
            $candidato = $colaborador->candidato;
            //Areas recibidas
            $areas_id = [];
            $areas_apoyo_id = [];
            if(isset($request->areas_id)){
                $areas_id = $request->areas_id;
            }
            if(isset($request->areas_apoyo_id)){
                $areas_apoyo_id = $request->areas_apoyo_id;
            }

            $usuarioAsociado = null;
            if($candidato->correo != null){
                $user = User::where('email', $candidato->correo)->first();
                if($user){
                    $usuarioAsociado = $user;
                    if(!isset($request->correo)){
                        return redirect($returnRoute)->with('error', 'Debe ingresar el correo para este registro que es un usuario.');
                    } else{
                        if(strlen($request->correo) < 5 || strlen($request->correo) > 100) {
                            return redirect($returnRoute)->with('error', 'El correo debe tener entre 5 y 100 caracteres.');
                        }
                    }
                }
            }
            if(isset($request->correo)){
                if($usuarioAsociado != null) {
                    $sameUser = User::where('email', $request->correo)->whereNot('id', $usuarioAsociado->id)->first();
                } else{
                    $sameUser = User::where('email', $request->correo)->first();
                }
                if($sameUser) {
                    return redirect($returnRoute)->with('error', 'El correo ingresado ya se encuentra registrado en un usuario del sistema.');
                }
            }

            if($usuarioAsociado != null){
                FunctionHelperController::modifyUserByColab($candidato, $request);
            }
            // return ["areas" => $areas_apoyo_id];
            //Encontrar siguiente semana(Lunes)
            $semana = FunctionHelperController::findOrCreateNextWeek();
            //Recorrer cada area enviado en el request
            foreach($areas_id as $area_id){
                //Buscar Si hay colaborador por area y colaborador
                $colaborador_por_area = Colaboradores_por_Area::where('colaborador_id', $colaborador->id)->where('area_id', $area_id)->first();
                //Si no se encuentra un colaborador con esta area
                if (!$colaborador_por_area) {
                    //Se crea un nuevo registro con esta area y colaborador
                    Colaboradores_por_Area::create([
                        'colaborador_id' => $colaborador->id,
                        'area_id' => $area_id,
                        'semana_inicio_id' => $semana->id,
                        'estado' => true,
                    ]);
                    //Si se encuentra un colaborador con esta area y esta inactivo
                } else if ($colaborador_por_area->estado == false) {
                    //Se actualiza el estado a activo
                    $colaborador_por_area->update(['estado' => true]);
                    //Crear registro de re activación
                    RegistroActividadController::crearRegistro($colaborador_por_area->id, true);
                }
            }
            foreach($areas_apoyo_id as $area_id){
                //Buscar Si hay colaborador por area y colaborador
                $colaborador_apoyo_area = ColaboradoresApoyoAreas::where('colaborador_id', $colaborador->id)->where('area_id', $area_id)->first();
                //Si no se encuentra un colaborador con esta area
                // return $colaborador_apoyo_area;
                if (!$colaborador_apoyo_area) {
                    //Se crea un nuevo registro con esta area y colaborador
                    ColaboradoresApoyoAreas::create([
                        'colaborador_id' => $colaborador->id,
                        'area_id' => $area_id,
                        'estado' => 1,
                    ]);
                    //Si se encuentra un colaborador con esta area y esta inactivo
                } else if ($colaborador_apoyo_area->estado == 0) {
                    //Se actualiza el estado a activo
                    // return 'here';
                    $colaborador_apoyo_area->update(['estado' => 1]);
                }
            }

            // Buscar las areas que no estan en el request y que estan asociadas al colaborador
            $areasInactivas = Colaboradores_por_Area::where('colaborador_id', $colaborador_id)->where('estado', 1)->whereNotIn('area_id', $areas_id)->get();
            $areasInactivasApoyo = ColaboradoresApoyoAreas::where('colaborador_id', $colaborador_id)->where('estado', 1)->whereNotIn('area_id', $areas_apoyo_id)->get();
            // Por cada registro encontrado
            foreach ($areasInactivas as $areaInactiva) {
                //Se inactiva su estado
                $areaInactiva->update(['estado' => false]);
                if($areaInactiva->jefe_area == 1){
                    $areaInactiva->update(['jefe_area' => 0]);
                }
                //Crear registro de inactivación
                RegistroActividadController::crearRegistro($areaInactiva->id, false);
                //Se busca si tiene computadoras
                $ColabMachines = Maquina_reservada::where('colaborador_area_id', $areaInactiva->id)->get();
                //Recorrer maquinas encontradas
                foreach($ColabMachines as $machine){
                    //Eliminar maquinas
                    $machine->delete();
                }
            }
            foreach ($areasInactivasApoyo as $areaInactivaApoyo) {
                //Se inactiva su estado
                $areaInactivaApoyo->update(['estado' => 0]);
            }
            if($request->actividades_id == null){
                $actividadesInactivas = AreaRecreativa::where('colaborador_id', $colaborador_id)->where('estado', 1)->get();
                foreach($actividadesInactivas as $actividadInactiva){
                    $actividadInactiva->update(['estado' => false]);
                }
            } else {
                //Lo mismo con las actividades
                foreach($request->actividades_id as $actividad_id){
                    $actividad_recreativa = AreaRecreativa::where('colaborador_id', $colaborador->id)->where('actividad_id', $actividad_id)->first();

                    if(!$actividad_recreativa){
                        AreaRecreativa::create([
                            'colaborador_id' => $colaborador->id,
                            'actividad_id' => $actividad_id,
                            'estado' => true
                        ]);
                    } else if($actividad_recreativa->estado == false) {
                        $actividad_recreativa->update(['estado' => true]);
                    }
                }
                //Lo mismo con las actividades, inactivarlas
                $actividadesInactivas = AreaRecreativa::where('colaborador_id', $colaborador_id)->where('estado', 1)->whereNotIn('actividad_id', $request->actividades_id)->get();
                foreach($actividadesInactivas as $actividadInactiva){
                    $actividadInactiva->update(['estado' => false]);
                }
            }
            //Se crea un array de datos a actualizar para el candidato, exceptuando el icono y area_id
            $datosActualizar = $request->except(['icono', 'areas_id', 'dni', 'carnet_extranjeria']);

            if ($request->filled('dni')) {
                $datosActualizar['dni'] = $request->dni;
                $datosActualizar['carnet_extranjeria'] = null;
            } elseif ($request->filled('carnet_extranjeria')) {
                $datosActualizar['carnet_extranjeria'] = $request->carnet_extranjeria;
                $datosActualizar['dni'] = null;
            }

            //Realizar la asignación y actualización del icono si se envía uno nuevo en la solicitud
            if ($request->hasFile('icono')) {
                $rutaPublica = public_path('storage/candidatos');
                if ($candidato->icono && $candidato->icono != 'default.png' && file_exists($rutaPublica . '/' . $candidato->icono)) {
                    unlink($rutaPublica . '/' . $candidato->icono);
                }

                $icono = $request->file('icono');
                $nombreIcono = time() . '.' . $icono->getClientOriginalExtension();

                $icono->move($rutaPublica, $nombreIcono);

                $datosActualizar['icono'] = $nombreIcono;
            }
            if ($candidato->wasChanged('email')) {
                $usuarioAsociado = User::where('email', $candidato->email)->first();

                if ($usuarioAsociado) {
                    $usuarioAsociado->update([
                        'email' => $candidato->email,
                    ]);
                }
            }
            //Se actualizan los datos del candidato
            $candidato->update($datosActualizar);

            $valorEspecialista = $request->especialista_id != 0 ? $request->especialista_id : null;
            $colaborador->update([
                "especialista_id" => $valorEspecialista
            ]);


            $nuevoCorreo = $request->correo;
            if ($candidato->correo !== $nuevoCorreo) {
                $candidato->update([
                    'correo' => $nuevoCorreo
                ]);
                $usuario = User::where('email', $nuevoCorreo)->first();
                if ($usuario) {
                    $usuario->update([
                        'email' => $nuevoCorreo
                    ]);
                }
            }

            DB::commit();

            //Se redirige a la vista
            return redirect($returnRoute);
        } catch(Exception $e){
            DB::rollBack();
            // return $e;
            if($request->currentURL) {
                return redirect($request->currentURL)->with('error', 'Ocurrió un error al actualizar, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            } else {
                return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al actualizar, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            }
        }
    }


    public function activarInactivar(Request $request, $colaborador_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $colaborador = Colaboradores::findOrFail($colaborador_id);

            $colaborador->estado = !$colaborador->estado;

            $colaborador->save();

            $colaboradoresAreaActivos = Colaboradores_por_Area::where('colaborador_id', $colaborador->id)->where('estado', 1)->get();
            if($colaborador->estado == 0){
                foreach($colaboradoresAreaActivos as $colabArea){
                    $colabArea->update(["jefe_area" => 0]);
                    //Crear registro de inactivación
                    RegistroActividadController::crearRegistro($colabArea->id, false);
                }
            } else {
                 foreach($colaboradoresAreaActivos as $colabArea){
                    //Crear registro de inactivación
                    RegistroActividadController::crearRegistro($colabArea->id, true);
                }
            }
            DB::commit();

            // return redirect()->route('colaboradores.index');
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('colaboradores.index');
            }
        } catch(Exception $e) {
            DB::rollBack();
            // return $e->getMessage();
            // return redirect()->route('colaboradores.index');
            if($request->currentURL) {
                return redirect($request->currentURL)->with('error', 'Ocurrió un error al actualizar el estado, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            } else {
                return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al actualizar el estado, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            }
        }
    }

        public function search(string $busqueda = ''){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        $colaboradoresTotales = Colaboradores::with([
            'candidato' => function ($query) {
                $query->select('id', 'nombre', 'apellido', 'dni', 'direccion', 'fecha_nacimiento', 'ciclo_de_estudiante', 'estado', 'sede_id', 'carrera_id', 'icono', 'correo', 'celular', 'distrito_id'); }
        ]);

        // buscar por nombre
        $idCandidatosPorNombre = Candidatos::searchByName($busqueda)->pluck('id');
        $colaboradoresPorNombre = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorNombre)->paginate(12);
        $countColaboradoresNombre = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorNombre)->get()->count();

        // buscar por DNI
        $idCandidatosPorDni = Candidatos::searchByDni($busqueda)->pluck('id');
        $colaboradoresPorDni = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorDni)->paginate(12);
        $countColaboradoresDni = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorDni)->get()->count();

        // buscar por carnet extranjeria
        $idCandidatosPorCarnet = Candidatos::where('carnet_extranjeria', 'LIKE', '%' . $busqueda . '%')->pluck('id');
        $colaboradoresPorCarnet = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorCarnet)->paginate(12);
        $countColaboradoresPorCarnet = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorCarnet)->get()->count();

        // buscar por correo
        $idCandidatosPorCorreo = Candidatos::where('correo', 'LIKE', '%' . $busqueda . '%')->pluck('id');
        $colaboradoresPorCorreo = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorCorreo)->paginate(12);
        $countColaboradoresCorreo = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorCorreo)->get()->count();

        // buscar por distrito
        $idCandidatosPorDistrito = Candidatos::whereHas('distrito', function($query) use ($busqueda) {
            $query->whereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
                nombre, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u'))
                LIKE ?", ['%' . strtolower(
                    str_replace(['á','é','í','ó','ú','Á','É','Í','Ó','Ú'],
                                ['a','e','i','o','u','a','e','i','o','u'],
                                $busqueda)
                ) . '%']);
        })->pluck('id');
        $colaboradoresPorDistrito = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorDistrito)->paginate(12);
        $countColaboradoresDistrito = Colaboradores::with('candidato')->whereIn('candidato_id', $idCandidatosPorDistrito)->get()->count();

        $distritos = Distrito::get();


        if ($colaboradoresPorDni->count() > 0) {
            $colaboradores = $colaboradoresPorDni;
            $countColaboradores = $countColaboradoresDni;
        } else if ($colaboradoresPorCarnet->count() > 0) {
            $colaboradores = $colaboradoresPorCarnet;
            $countColaboradores = $countColaboradoresPorCarnet;
        } elseif ($colaboradoresPorNombre->count() > 0) {
            $colaboradores = $colaboradoresPorNombre;
            $countColaboradores = $countColaboradoresNombre;
        } elseif ($colaboradoresPorCorreo->count() > 0){
            $colaboradores = $colaboradoresPorCorreo;
            $countColaboradores = $countColaboradoresCorreo;
        } else {
            $colaboradores = $colaboradoresPorDistrito;
            $countColaboradores = $countColaboradoresDistrito;
        }

        $especialistas = Especialista::where('estado', 1)->get();

        $ciclosAll = [4,5,6,7,8,9,10];
        $sedesAll = Sede::with('institucion')->orderBy('nombre', 'asc')->get();
        $institucionesAll = Institucion::orderBy('nombre', 'asc')->get();
        $carrerasAll = Carrera::orderBy('nombre', 'asc')->get();
        $areasAll = Area::orderBy('especializacion', 'asc')->get();

        $sedes = $sedesAll->where('estado', 1);
        $instituciones = $institucionesAll->where('estado', 1);
        $carreras = $carrerasAll->where('estado', 1);
        $areas = $areasAll->where('estado', 1);

        $colaboradoresCol = $this->asignarColorJefesArea($colaboradores);

        $colaboradores = $this->getColaboradoresPromedioStatus($colaboradores);
        $colabsActividades = AreaRecreativaController::getColabActividades($colaboradores->items());
        $colaboradoresConArea = FunctionHelperController::colaboradoresConArea($colabsActividades);
        $colaboradores->data = $colaboradoresConArea;
        $pageData = FunctionHelperController::getPageData($colaboradores);
        $hasPagination = true;

        // horas practicadas de cada colaborador
        $horasTotales = $this->getHoursColab();
        foreach ($colaboradores->data as &$colaborador) {
            $horasPracticas = collect($horasTotales)
                ->firstWhere('colaborador_id', $colaborador->id)['horasPracticas'] ?? 0;
            $colaborador->horasPracticas = $horasPracticas;
        }

        $Allactividades = Actividades::where('estado', 1)->get();
        return view('inspiniaViews.colaboradores.index', [
            'colaboradores' => $colaboradores,
            'countColaboradores' => $countColaboradores,
            'hasPagination' => $hasPagination,
            'pageData' => $pageData,
            'especialistas' => $especialistas,
            'sedes' => $sedes,
            'instituciones' => $instituciones,
            'carreras' => $carreras,
            'areas' => $areas,
            'ciclosAll' => $ciclosAll,
            'sedesAll' => $sedesAll,
            'institucionesAll' => $institucionesAll,
            'carrerasAll' => $carrerasAll,
            'areasAll' => $areasAll,
            'Allactividades' => $Allactividades,
            'horasTotales' =>  $horasTotales,
            'colaboradoresCol' => $colaboradoresCol,
            'distritos' => $distritos
        ]);
    }

    public function despedirColaborador(Request $request, $colaborador_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $colaborador = Colaboradores::findOrFail($colaborador_id);

            if($colaborador){
                //encontrar Candidato
                $candidato = Candidatos::findOrFail($colaborador->candidato_id);
                if($candidato){
                    //Estado 3 igual a ex trabajador en candidatos
                    $candidato->update(["estado" => 3]);
                }
                //encontrar ColaboradoresPorArea
                $colaboradorAreas = Colaboradores_por_Area::where('colaborador_id', $colaborador_id)->where('estado', 1)->get();
                foreach($colaboradorAreas as $colabArea){
                    $colabArea->update(["estado" => 0]); //inactivo del área
                    //Crear registro de inactivación
                    RegistroActividadController::crearRegistro($colabArea->id, false);
                }
                //Estado 2 es igual a ex trabajador
                $colaborador->update(["estado" => 2]);
                // si es jefe_area
                $user = User::where('email', $colaborador->candidato->correo)->first();
                if($user) {
                    UsuarioJefeArea::where('user_id', $user->id)->delete();
                }
            }
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('colaboradores.index');
            }

        } catch(Exception $e){
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL)->with('error', 'Ocurrió un error al despedir, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            } else {
                return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al despedir, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            }
        }
    }

    public function recontratarColaborador(Request $request, $colaborador_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            //encontrar Colaborador
            $colaborador = Colaboradores::findOrFail($colaborador_id);
            if($colaborador) {
                //encontrar Candidato
                $candidato = Candidatos::findOrFail($colaborador->candidato_id);
                if($candidato) {
                    $candidato->update(["estado" => 0]); //estado 0 igual a activo en candidatos
                }
                $colaborador->update(["estado" => 1]); //estado 1 igual a activo en colaboradores
            }
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL);
            } else {
                return redirect()->route('colaboradores.index');
            }
        } catch(Exception $e){
            DB::rollBack();
            if($request->currentURL) {
                return redirect($request->currentURL)->with('error', 'Ocurrió un error al recontratar, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            } else {
                return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al recontratar, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            }
        }
    }
    public function destroy(Request $request, $colaborador_id)
    {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }
        DB::beginTransaction();
        try{
            $colaborador = Colaboradores::findOrFail($colaborador_id);
            if($colaborador){
                //primero encontramos al candidato
                $candidato = Candidatos::findOrFail($colaborador->candidato_id);
                if($candidato) {
                    //despues a los colaboradores (suponiendo que por error tenga mas de uno)
                    $colaboradores = Colaboradores::where('candidato_id', $candidato->id)->get();
                    //despues a los colaboradores por area de todos los colaboradores encontrados
                    $colaboradores_por_area = Colaboradores_por_Area::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    //despues todos los horarios de clase de estos colaboradores
                    $horarios_de_clases = Horario_de_Clases::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    //ahora los registros de actividad de los colaboradores con area
                    $registros_actividad = RegistroActividad::whereIn('colaborador_area_id', $colaboradores_por_area->pluck('id'))->get();
                    //despues todos las maquinas reservadas de los colaboradores con area
                    $maquinas_reservadas = Maquina_reservada::whereIn('colaborador_area_id', $colaboradores_por_area->pluck('id'))->get();
                    //ahora todas las responsabilidades semanales cumplidas por todos los colaboradores con area}
                    $responsabilidades_semanales_cumplidas = Cumplio_Responsabilidad_Semanal::whereIn('colaborador_area_id', $colaboradores_por_area->pluck('id'))->get();
                    //despues todas las computadoras de todos los colaborades encontrados
                    $computadoras = Computadora_colaborador::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    //luego a todos los programas instalados de estas computadoras
                    $programas_instalados = Programas_instalados::whereIn('computadora_id', $computadoras->pluck('id'))->get();
                    //luego todos los registros de mantenimientos de las computadoras
                    $registros_mantenimientos = Registro_Mantenimiento::whereIn('computadora_id', $computadoras->pluck('id'))->get();
                    //ahora todos los prestamos de objetos del colaborador
                    $prestamos_objetos = Prestamos_objetos_por_colaborador::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    //finalmente todas las asistencias de clases (Aun no implementada pero puesta de igual manera para evitar errores)
                    $asistencias_clases = Asistentes_Clase::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    // areas_recreativas asociadas al colaborador
                    $colaborador_actividades = AreaRecreativa::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    // areas_apoyo
                    $colaborador_apoyo_areas = ColaboradoresApoyoAreas::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    //integrantes_reuniones
                    $integrantes_reuniones = IntegrantesReuniones::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    //reuniones_programadas
                    $reuniones_programadas = ReunionesProgramadas::whereIn('id', $colaboradores->pluck('id'))->get();
                    //pago_colaborador
                    $pago_colaborador = PagoColaborador::whereIn('colaborador_id', $colaboradores->pluck('id'))->get();
                    // colaborador_libro
                    $colaborador_libro = ColaboradorLibro::with('libro')->whereIn('colaborador_id', $colaboradores->pluck('id'))->get();

                    // si el colaborador no devolvió uno o varios libros
                    $noDevueltoColab = ColaboradorLibro::whereIn('colaborador_id', $colaboradores->pluck('id'))->where('devuelto', 0)->get();
                    if($noDevueltoColab->isNotEmpty()){
                        return redirect()->route('colaboradores.index')->with('warning', 'El colaborador debe devolver los libros que se le prestó.');
                    }
                    //ELIMINACIÓN EN CASCADA
                    //ahora procedemos a eliminarlos de los ultimos a los primeros
                    //asistencias_clase
                    if($asistencias_clases){
                        foreach($asistencias_clases as $asistencia_clase) {
                            $asistencia_clase->delete();
                        }
                    }
                    //prestamos_objetos
                    if($prestamos_objetos){
                        foreach($prestamos_objetos as $prestamo_objeto) {
                            $prestamo_objeto->delete();
                        }
                    }
                    //registros_mantenimientos
                    if($registros_mantenimientos){
                        foreach($registros_mantenimientos as $registro_mantenimiento) {
                            $registro_mantenimiento->delete();
                        }
                    }
                    //programas_instalados
                    if($programas_instalados){
                        foreach($programas_instalados as $programa_instalado) {
                            $programa_instalado->delete();
                        }
                    }
                    //computadoras
                    if($computadoras){
                        foreach($computadoras as $computadora) {
                            $computadora->delete();
                        }
                    }
                    //responsabilidades_Semanales_cumplidas
                    if($responsabilidades_semanales_cumplidas){
                        foreach($responsabilidades_semanales_cumplidas as $responsabilidad_semanal_cumplida) {
                            $responsabilidad_semanal_cumplida->delete();
                        }
                    }
                    //maquinas_reservadas
                    if($maquinas_reservadas){
                        foreach($maquinas_reservadas as $maquina_reservada) {
                            $maquina_reservada->delete();
                        }
                    }
                    //registros_actividad
                    if($registros_actividad){
                        foreach($registros_actividad as $registro_actividad) {
                            $registro_actividad->delete();
                        }
                    }
                    //horarios_de_clases
                    if($horarios_de_clases){
                        foreach($horarios_de_clases as $horario_de_clase) {
                            $horario_de_clase->delete();
                        }
                    }
                    // areas_recreativa
                    if($colaborador_actividades){
                        foreach($colaborador_actividades as $activ){
                            $activ->delete();
                        }
                    }
                    // apoyo_areas
                    if($colaborador_apoyo_areas){
                        foreach($colaborador_apoyo_areas as $apo){
                            $apo->delete();
                        }
                    }
                    //integrantes_reuniones
                    if($integrantes_reuniones){
                        foreach($integrantes_reuniones as $inte){
                            $inte->delete();
                        }
                    }
                    //reuniones_programadas
                    if($reuniones_programadas){
                        foreach($reuniones_programadas as $reu){
                            $reu->delete();
                        }
                    }
                    //colaboradores_por_area
                    if($colaboradores_por_area){
                        foreach($colaboradores_por_area as $colaborador_por_area) {
                            $colaborador_por_area->delete();
                        }
                    }

                    // pago_colaborador
                    if($pago_colaborador) {
                        foreach($pago_colaborador as $pago_colab) {
                            $pago_colab->delete();
                        }
                    }

                    // colaborador_libro
                    if($colaborador_libro) {
                        foreach($colaborador_libro as $colab_libro) {
                            $colab_libro->delete();
                        }
                    }
                    //colaboradores
                    if($colaboradores){
                        foreach($colaboradores as $colab) {
                            $colab->delete();
                        }
                    }

                    //candidato
                    $candidato->delete();

                    $user = User::where('email', $candidato->correo)->first();
                    if ($user) {
                        $usuario_colab = UsuarioColaborador::where('user_id', $user->id)->first();
                        if ($usuario_colab) {
                            $usuario_colab->delete();
                        }

                        $usuario_jefe_areas = UsuarioJefeArea::where('user_id', $user->id)->get();
                        foreach ($usuario_jefe_areas as $usuario_jefe_area) {
                            $usuario_jefe_area->delete();
                        }

                        $usuarioPasswords = UsuariosPasswords::where('user_id', $user->id)->get();
                        if ($usuarioPasswords) {
                            foreach ($usuarioPasswords as $usuarioPassword) {
                                $usuarioPassword->delete();
                            }
                        }

                        $user->delete();
                    }
            }
        }
            DB::commit();
            if($request->currentURL) {
                return redirect($request->currentURL)->with('success', 'Se eliminó al colaborador con éxito.');
            } else {
                return redirect()->route('colaboradores.index')->with('success', 'Se eliminó al colaborador con éxito.');
            }
        } catch(Exception $e){
            DB::rollBack();
            // return $e;
            if($request->currentURL) {
                return redirect($request->currentURL)->with('error', 'Ocurrió un error al eliminar, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            } else {
                return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al eliminar, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
            }
        }

    }



    public function colabEditState(Request $request, $colaborador_id) {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try{
            $colaborador = Colaboradores::findOrFail($colaborador_id);

            $colaborador->update(['editable' => 1]);

            DB::commit();
            return redirect()->route('colaboradores.index');
        }catch (Exception $e) {
            DB::rollBack();
            return redirect($request->currentURL)->with('error', 'Ocurrió un error al registrar el pago, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
        }
    }

    public function createEmailPassword($colaborador_id) {
        // Verificar acceso de administrador
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        try {
            $colaborador = Colaboradores::findOrFail($colaborador_id);
            $candidato = $colaborador->candidato;

            // dd($candidato);
            if (!$candidato || !$candidato->correo) {
                return redirect()->route('colaboradores.index')->with('error', 'El candidato no tiene un correo asignado.');
            }

            $existingUser = User::where('email', $candidato->correo)->first();
            if ($existingUser) {
                return redirect()->route('colaboradores.index', ['existUser' => $existingUser])->with('error', 'Este colaborador ya tiene una cuenta asociada.');
            }

            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@_';
            $randomPassword = substr(str_shuffle(str_repeat($characters, 12)), 0, 12);

            $user = User::create([
                'name' => $candidato->nombre,
                'apellido' => $candidato->apellido,
                'email' => $candidato->correo,  // Usar el correo del candidato
                'password' => Hash::make($randomPassword),
            ]);

            // Registrar la contraseña en la tabla de contraseñas
            UsuariosPasswordsController::registrar($user->id, $randomPassword);

            // Verificar que el correo sea válido y enviar el email con las credenciales
            if (filter_var($candidato->correo, FILTER_VALIDATE_EMAIL)) {
                Mail::to($candidato->correo)->send(
                    new UsuarioCreadoMailable(
                        $candidato->correo,
                        $randomPassword,
                        $candidato->nombre . " " . $candidato->apellido,
                        'Colaborador'
                    )
                );
            } else {
                throw new Exception('El correo electrónico del candidato no es válido.');
            }

                UsuarioColaborador::create([
                    'user_id' => $user->id
                ]);


            return redirect()->route('colaboradores.index')->with('success', 'El colaborador ha sido creado con éxito y se ha enviado un correo con las credenciales.');

        } catch (Exception $e) {
            return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al registrar el pago, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
        }
    }

    public function pagoColab(Request $request, $colaborador_id){
        $access = FunctionHelperController::verifyAdminAccess();
        if (!$access) {
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción.');
        }

        DB::beginTransaction();
        try {
            $colaborador = Colaboradores::where('estado', 1)->findOrFail($colaborador_id);

            if ($request->has("gastos_eliminados") && !empty($request->gastos_eliminados[$colaborador_id])) {
                $gastosEliminar = explode(",", $request->gastos_eliminados[$colaborador_id]);

                if (!empty($gastosEliminar)) {
                    PagoColaborador::whereIn("id", $gastosEliminar)->delete();
                }
            }

            if ($request->has("descripcion") && isset($request->descripcion[$colaborador_id])) {
                foreach ($request->descripcion[$colaborador_id] as $index => $descripcion) {
                    $monto = $request->monto[$colaborador_id][$index];
                    $gastoId = $request->input("gasto_id")[$index] ?? null;

                    if ($gastoId) {
                        $gasto = PagoColaborador::find($gastoId);
                        if ($gasto) {
                            $gasto->update([
                                'descripcion' => $descripcion,
                                'monto' => $monto,
                            ]);
                        }
                    } else {
                        PagoColaborador::create([
                            'colaborador_id' => $colaborador_id,
                            'descripcion' => $descripcion,
                            'monto' => $monto,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('colaboradores.index')->with('success', 'Pagos actualizados correctamente.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al registrar el pago, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');
        }
    }

    public function activeEditAll() {
        $access = FunctionHelperController::verifyAdminAccess();
        if(!$access){
            return redirect()->route('dashboard')->with('error', 'No tiene acceso para ejecutar esta acción. No lo intente denuevo o puede ser baneado.');
        }

        DB::beginTransaction();
        try {

            Colaboradores::query()->update(['editable' => 1]);
            DB::commit();
            return redirect()->route('colaboradores.index')->with('success', 'Se activó la edición para todos los colaboradores.');

        } catch (Exception $e) {

            // return $e;
            DB::rollback();
            return redirect()->route('colaboradores.index')->with('error', 'Ocurrió un error al registrar el pago, intente denuevo. Si este error persiste, contacte a su equipo de soporte.');

        }
    }
}
