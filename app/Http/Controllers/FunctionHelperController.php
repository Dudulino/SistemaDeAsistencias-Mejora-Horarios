<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Candidatos;
use App\Models\Colaboradores;
use App\Models\Colaboradores_por_Area;
use App\Models\ColaboradoresApoyoAreas;
use App\Models\Cumplio_Responsabilidad_Semanal;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Maquina_reservada;
use App\Models\Responsabilidades_semanales;
use App\Models\Semanas;
use App\Models\Transaccion;
use App\Models\User;
use App\Models\UsuarioAdministrador;
use App\Models\UsuarioColaborador;
use App\Models\UsuarioJefeArea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class FunctionHelperController extends Controller
{
    //USERS
    public static function getUserRol(){
        $user = auth()->user();
        $administrador = UsuarioAdministrador::where('user_id', $user->id)->where('estado', 1)->first();
        $isAdmin = false;
        if($administrador){$isAdmin = true;}
        
        $jefeArea = UsuarioJefeArea::where('user_id', $user->id)->where('estado', 1)->get();
        $isBoss = false;
        if($jefeArea->count() > 0){$isBoss = true;}

        $candidato = Candidatos::where('correo', $user->email)->first();
        $colaborador = $candidato ? Colaboradores::where('candidato_id', $candidato->id)->first() : null;
        $colabArea = $colaborador
        ? Colaboradores_por_Area::where('colaborador_id', $colaborador->id)
            ->where('jefe_area', 0)
            ->first()
        : null;
        $isColab = false;
        if($colaborador) {
            $isColab = true;
        }

        return [
            "user" => $user,
            "administrador" => $administrador,
            "Jefeareas" => $jefeArea,
            "colaboradores" => $colaborador,
            "colabsArea" => $colabArea,
            "isAdmin" => $isAdmin,
            "isBoss" => $isBoss,
            "isColab" => $isColab
        ];
    }

    public static function getUserRolById($user_id){
        $user = User::findOrFail($user_id);
        $administrador = UsuarioAdministrador::where('user_id', $user->id)->where('estado', 1)->first();
        $isAdmin = false;
        if($administrador){$isAdmin = true;}
        $jefeArea = UsuarioJefeArea::where('user_id', $user->id)->where('estado', 1)->get();
        $isBoss = false;
        if($jefeArea->count() > 0){$isBoss = true;}
        return [
            "user" => $user,
            "administrador" => $administrador,
            "Jefeareas" => $jefeArea,
            "isAdmin" => $isAdmin,
            "isBoss" => $isBoss,
        ];
    }

    public static function modifyColabByUser($user, $newData){
        //Encontrar colaborador / Candidato
        $colaborador = Candidatos::where('correo', $user->email)->where('estado', 0)->first();
        $user->update([
            'name'=> $newData['name'],
            'apellido'=> $newData['apellido'],
            'email' => $newData['email']
        ]);
        if($colaborador){
            $colaborador->update([
                'nombre' => $newData['name'],
                'apellido'=> $newData['apellido'],
                'correo' => $newData['email']
            ]);
        }
    }

    public static function modifyUserByColab($colab, $newData){
        $user = User::where('email', $colab->correo)->first();
        $colab->update([
            'nombre' => $newData['nombre'],
            'apellido'=> $newData['apellido'],
            'correo' => $newData['correo']
        ]);
        if($user){
            $user->update([
                'name'=> $newData['nombre'],
                'apellido'=> $newData['apellido'],
                'email' => $newData['correo']
            ]);
        }
    }



    public static function verifyAreaAccess($area_id){
        $userData = FunctionHelperController::getUserRol();
        if($userData['isAdmin']){
            return true;
        }else if($userData['isBoss']){
            $bossAreasId = $userData['Jefeareas']->pluck('area_id')->toArray();
            if(in_array($area_id, $bossAreasId)){
                return true;
            }else{
                return false;
            }
        } else{
            return false;
        }
    }

    public static function verifyAdminAccess(){
        $userData = FunctionHelperController::getUserRol();
        if($userData['isAdmin']){
            return true;
        } else{
            return false;
        }
    }

    public static function verifySuperAdmin($user_id){
        $SuperAdministrador = UsuarioAdministrador::where('user_id', $user_id)->where('super_admin', 1)->first();
        if($SuperAdministrador){
            return true;
        }else{
            return false;
        }

    }

    public static function getAreasJefe($user_id){
        $AreasJefe = UsuarioJefeArea::with('area')->where('user_id', $user_id)->where('estado', 1)->get()->pluck('area');

        return $AreasJefe;
    }


    //COLABORADORES
    public static function colaboradoresConArea($colaboradoresBase){
        $colaboradoresConArea = [];

        foreach ($colaboradoresBase as $colaborador) {
            $colaboradorArea = Colaboradores_por_Area::with('area')->where('colaborador_id', $colaborador->id)->where('estado', true)->get();
            $colaboradorApoyo = ColaboradoresApoyoAreas::with('area')->where('colaborador_id', $colaborador->id)->where('estado', true)->get();
            if (count($colaboradorArea)>0 || count($colaboradorApoyo)>0) {
                $areas = [];

                foreach($colaboradorArea as $colArea){
                    $areas[] = ["nombre" => $colArea->area->especializacion, "tipo" => 0];
                }
                foreach($colaboradorApoyo as $colApoyo){
                    $areas[] = ["nombre"=>$colApoyo->area->especializacion, "tipo" => 1];
                }
                $colaborador->areas = $areas;
            } else {
                $colaborador->areas = [["nombre" => 'Sin área asignada', "tipo" => 2]];
            }
            $colaboradoresConArea[] = $colaborador;
        }
        return $colaboradoresConArea;
    }

    public static function verifyEmailCandidatoConflict($email, $candidato_id = null){
        if($candidato_id != null){
            $candidato = Candidatos::where('correo', $email)->whereNot('id', $candidato_id)->first();
        } else{
            $candidato = Candidatos::where('correo', $email)->first();
        }
        if($candidato){
            return true; //Si hay conflicto
        }else{
            return false; //No hay conflicto
        }
    }

    public static function findUserCandidato($type, $email){
        switch($type){
            //Si type es 1, se busca al candidato de un usuario
            case 1:
                $candidato = Candidatos::where('correo', $email)->first();
                return $candidato;
            //Si type es 2, se busca el usuario de un candidato
            case 2:
                $user = User::where('email', $email)->first();
                return $user;
            //Sino se retorna null
            default:
                return null;
        }
    }
    //PAGINATION
    public static function getPageData($collection){
        $pageData = [
            'currentPage' => $collection->currentPage(),
            'currentURL' => $collection->url($collection->currentPage()),
            'lastPage' => $collection->lastPage(),
            'nextPageUrl' => $collection->nextPageUrl(),
            'previousPageUrl' => $collection->previousPageUrl(),
            'lastPageUrl' => $collection->url($collection->lastPage()),
        ];
        $pageData = json_decode(json_encode($pageData));
        return $pageData;
    }

    //TIME


    public static function findThisWeek(){
        $thisWeekMonday = Carbon::today()->startOfWeek()->toDateString();
        $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();

        return $thisSemana;
    }
    public static function findOrCreateNextWeek(){
        //Encontrar el dia actual
        $today = Carbon::now();
        $monday = $today;
        //Buscar el siguiente lunes
        while (!$today->isMonday()) {
            $today->addDay();
            $monday = $today;
        }
        //Buscar semana del siguiente lunes
        $semana = Semanas::where('fecha_lunes', $monday->toDateString())->first();
        if(!$semana){
            $semana = Semanas::create(['fecha_lunes' => $monday->toDateString()]);
        }
        //Retornar semana
        return $semana;
    }

    public static function getWeekFromToDisponible($semana_id){
        $disponible = true;
        $semana = Semanas::where('id', $semana_id)->first();

        $yesterday = Carbon::today()->subDay();
        $thisWeekMonday = $yesterday->copy()->startOfWeek()->toDateString();
        $thisSemana = Semanas::where('fecha_lunes', $thisWeekMonday)->first();

        if($semana->id >= $thisSemana->id) $disponible = false;

        $desde = Carbon::parse($semana->fecha_lunes)->format('d/m/Y');
        $hasta = Carbon::parse($semana->fecha_lunes);

        while(!$hasta->isSunday()){
            $hasta->addDay();
        }

        return [
            "desde" => $desde,
            "hasta" => $hasta->format('d/m/Y'),
            "disponible" => $disponible
        ];
    }

    public static function getSemanaByDay($date){
        //Se convierte la fecha a Carbon para poder manipularla mejor
        $date = Carbon::parse($date);
        //Se guarda la fecha en la semanaDate
        $semanaDate = $date;
        //Si la fecha es lunes, martes o miercoles
        if(($date->isMonday()) || ($date->isTuesday()) || ($date->isWednesday())){
            //la fecha de la semana será igual al inicio de la semana de la fecha
            $semanaDate = $date->copy()->startOfWeek();
        } else{
            //Si es otro día
            //Mientras la semanaDate no sea lunes
            while(!$semanaDate->isMonday()){
                //Se le agregará un dia mas hasta que llegue a ser lunes
                $semanaDate->addDay();
            }
        }
        // return $semanaDate->toDateString();
        //Se busca la semana donde la fecha_lunes sea igual a la fecha de la semanaDate
        $semana = Semanas::where('fecha_lunes', $semanaDate->toDateString())->first();
        //Se retorna la semana
        return $semana;
    }

    public static function getYears()
    {
        $TotalSemanas = Semanas::get();
        $Years = [];

        foreach ($TotalSemanas as $semana) {
            $semanaYear = date('Y', strtotime($semana->fecha_lunes));
            if (!in_array($semanaYear, $Years)) {
                $Years[] = $semanaYear;
            }
        }

        return $Years;
    }

    public static function getMonths()
    {
        $Meses = [
            "Enero" => ["nombre" => "Enero", "id" => "01"],
            "Febrero" => ["nombre" => "Febrero", "id" => "02"],
            "Marzo" => ["nombre" => "Marzo", "id" => "03"],
            "Abril" => ["nombre" => "Abril", "id" => "04"],
            "Mayo" => ["nombre" => "Mayo", "id" => "05"],
            "Junio" => ["nombre" => "Junio", "id" => "06"],
            "Julio" => ["nombre" => "Julio", "id" => "07"],
            "Agosto" => ["nombre" => "Agosto", "id" => "08"],
            "Septiembre" => ["nombre" => "Septiembre", "id" => "09"],
            "Octubre" => ["nombre" => "Octubre", "id" => "10"],
            "Noviembre" => ["nombre" => "Noviembre", "id" => "11"],
            "Diciembre" => ["nombre" => "Diciembre", "id" => "12"],
        ];

        return $Meses;
    }

    public static function getDays(){
        $days = [
            "Lunes",
            "Martes",
            "Miércoles",
            "Jueves",
            "Viernes",
            "Sábado",
            "Domingo"
        ];

        return $days;
    }

    /**
     * RETORNA LAS ÁREAS CONCURRENTES
     * REQUIERE DE UN ARRAY DENTRO DE ESTE EL OBJETO AREA QUE ES OBLIGATORIO
     * SE LE PUEDEN ESPECIFICAR SI SE QUIERE REGRESAR LAS ÁREAS INCLUYENDO ESTA Y SI SOLO RETORNAR ÁREAS ACTIVAS O NO
     * POR DEFECTO REGRESA LAS ÁREAS INCLUYENDO ESTA Y TODAS LAS AREAS INDEFERENTEMENTE DE SU ESTADO
     */
    public static function getAreasConcurrentes($array = ["area" => null, "WithThis" => true, "active" => false]){
        //Obtener el área
        // return $array;
        if($array['area'] && $array['area'] != null){
            $area = $array['area'];
            //Obtener los horarios id del área
            $horariosArea = Horario_Presencial_Asignado::where('area_id', $area->id)->get()->pluck('horario_presencial_id');
            //Buscar otras areas con el mismo horario
            $allAreasConcurrentesId = Horario_Presencial_Asignado::with('area')->whereIn('horario_presencial_id', $horariosArea)->get()->pluck('area_id');
            //Buscar todas las áreas con esos Id
            if(isset($array['WithThis'])){
                if($array['WithThis'] == true) {
                    //absolutamente todas las área concurrentes
                    $allAreasConcurrentes = Area::whereIn('id', $allAreasConcurrentesId)->get();
                } else{
                    //Todas las áreas concurrentes menos la que se está buscando
                    $allAreasConcurrentes = Area::whereIn('id', $allAreasConcurrentesId)->whereNot('id', $area->id)->get();
                }
            } else{
                $allAreasConcurrentes = Area::whereIn('id', $allAreasConcurrentesId)->get();
            }
            //Las areas tienen que ser del mismo salón para ser concurrentes
            $allAreasConcurrentesSalon = $allAreasConcurrentes->where('salon_id', $area->salon_id);
            //ver si se quiere que sean activas o no
            if(isset($array['active'])){
                if($array['active'] == true){
                    $allActiveAreasConcurrentes = $allAreasConcurrentesSalon->where('estado', 1);
                    return $allActiveAreasConcurrentes;
                } else{
                    return $allAreasConcurrentesSalon;
                }
            } else{
                return $allAreasConcurrentesSalon;
            }
        }
    }

    public static function destroySameMachines($area_id){
        DB::beginTransaction();
        try{
            $countDestroyed = 0;
            $area = Area::findOrFail($area_id);
            if($area){
                $areasConcurrentesWithoutThis = FunctionHelperController::getAreasConcurrentes(["area" => $area, "WithThis" => false, "active" => true]);
                //Encontrar los colaboradores de este area
                $colaboradoresArea = Colaboradores_por_Area::with('colaborador')->where('area_id', $area->id)->where('estado', 1)->get();
                //Encontrar las maquinas de esta area
                $maquinasReservadas = Maquina_reservada::whereIn('colaborador_area_id', $colaboradoresArea->pluck('id'))->get();

                //Encontrar los colaboradores de las áreas concurrentes
                $colaboradoresAreasConcurrentes = Colaboradores_por_Area::with('colaborador')->whereIn('area_id', $areasConcurrentesWithoutThis->pluck('id'))
                    ->where('estado', 1)->get();
                //Encontrar las máquinas de las áreas concurrentes
                $maquinasReservadasAreasConcurrentes = Maquina_reservada::whereIn('colaborador_area_id', $colaboradoresAreasConcurrentes->pluck('id'))->get();

                //Recorrer las maquinas de esta area
                foreach($maquinasReservadas as $maquinaReservada) {
                    //Recorrer las maquinas de las otras areas
                    foreach($maquinasReservadasAreasConcurrentes as $maquinaReservadaAreaConcurrente) {
                        //Verificar conflictos con las maquinas de otras areas
                        if($maquinaReservada->maquina_id === $maquinaReservadaAreaConcurrente->maquina_id){
                            //Si hay un choque, se elimina la maquina de esta area
                            $maquinaReservada->delete();
                            $countDestroyed++;
                        }
                        //Si no hay conflictos, no hacer nada
                    }
                }
            }
            DB::commit();
            return response()->json(["destroyed" => $countDestroyed]);
        } catch(Exception $e){
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public static function semanasColaboradorArea($colaborador_area_id) {
        $colaboradorArea = Colaboradores_por_Area::findOrFail($colaborador_area_id);
        //obtener sus semanas de inactividad
        $semanasInactivas = RegistroActividadController::getColabSemanasInactivas($colaborador_area_id);
        $semanasInactivasId = [];
        foreach($semanasInactivas as $semanaInactiva){
            $semanasInactivasId[] = $semanaInactiva['id'];
        }
        //obtener las semanas desde la de esta semana actual hasta la de la semana inicio exceptuando las semanas inactivas
        $semanaActual = FunctionHelperController::findThisWeek();
        $semanasTotales = Semanas::where('id', '>=', $colaboradorArea->semana_inicio_id)
            ->where('id', '<', $semanaActual->id)->whereNotIn('id', $semanasInactivasId)->get();

        //retornar las semanas y conteo
        return ["semanas" => $semanasTotales, "conteoSemanas" => $semanasTotales->count()];
    }

    public static function semanasColaborador($colaborador_id){
        //obtener primero colaborador Area
        $colaboradorArea = Colaboradores_por_Area::where('colaborador_id', $colaborador_id)->first();
        if($colaboradorArea){
            $semanaActual = FunctionHelperController::findThisWeek();
            $semanasTotales = Semanas::where('id', '>=', $colaboradorArea->semana_inicio_id)
            ->where('id', '<', $semanaActual->id)->get();

            return ["semanas" => $semanasTotales, "conteoSemanas" => $semanasTotales->count()];
        }

    }

    public static function promedioColaboradorArea($colaborador_area_id, $semanas){
        //recorrer sus semanas, por cada semana obtener su evaluacion de cada responsabilidad
        $notasTotales = [];
        $responsabilidades = [];
        // return $responsabilidades;
        foreach($semanas as $semana){
            //Obtener registros de evaluación de esa semana
            $registros = Cumplio_Responsabilidad_Semanal::with('responsabilidad_semanal')
                ->where('colaborador_area_id', $colaborador_area_id)->where('semana_id', $semana->id)->get();
            // return $registros;
            foreach($registros as $registro){
                $valor = $registro->cumplio ? 20 : 0;
                $responsabilidad = $registro->responsabilidad_semanal;
                //Agregar a Notas
                if(isset($notasTotales[$responsabilidad->nombre])){
                    //Si ya esta en el array se le suma el valor
                    $notasTotales[$responsabilidad->nombre] += $valor;
                } else{
                    //Si no esta en el array se agrega con el valor de la nota
                    $notasTotales[$responsabilidad->nombre] = $valor;
                }
                //Agregar a Responsabilidades
                if(isset($responsabilidades[$responsabilidad->nombre])){
                    //Si ya esta en el array se le agrega 1 a su conteo de semanas
                    $responsabilidades[$responsabilidad->nombre]['conteoSemanas']++;
                } else{
                    //Si no esta en el array se agrega con un conteo de 1
                    $responsabilidades[$responsabilidad->nombre] = [
                        "id" => $responsabilidad->id,
                        "nombre" => $responsabilidad->nombre,
                        "conteoSemanas" => 1,
                    ];
                }
            }
        }
        $promedioNotas = [];
        foreach(array_keys($notasTotales) as $notaTotal){
            $responsabilidad = $responsabilidades[$notaTotal];
            $promedioNotas[$notaTotal] = number_format($notasTotales[$notaTotal]/$responsabilidad['conteoSemanas'], 1);
        }
        // return count($responsabilidades);
        $responsabilidadesCount = count($responsabilidades) === 0 ?  false : count($responsabilidades);
        // return $responsabilidadesCount;
        if(!$responsabilidadesCount){
            $promedio = null;
        } else{
            $promedio = number_format(array_sum($promedioNotas)/$responsabilidadesCount, 1);
        }
        $data = [
            "notasTotales" => $notasTotales,
            "promedioNotas" => $promedioNotas,
            "promedio" => $promedio,
            "responsabilidades" => $responsabilidades
        ];
        return $data;
    }

    public static function promedioColaborador($colaborador_id, $semanas){
        // $colaborador = Colaboradores::findOrFail($colaborador_id);
        $colaboradorAreas = Colaboradores_por_Area::where('colaborador_id', $colaborador_id)->get();
        // almacenar data sumando de cada área
        $notasTotales = [];
        $promedioNotas = [];
        $promedio = 0;
        foreach($colaboradorAreas as $colabArea){
            //Data de cada colab area
            $dataColabArea = FunctionHelperController::promedioColaboradorArea($colabArea->id, $semanas);
            $notasTotalesColabArea = $dataColabArea['notasTotales'];
            if($dataColabArea['promedio'] == null){
                //Si su promedio es null, significa que aun no fue evaluado
                //Retirar este colaborador del arreglo para no agregar ni dividir
                $colaboradorAreas = $colaboradorAreas->where('id', '!=', $colabArea->id);
            } else{
                foreach(array_keys($notasTotalesColabArea) as $notaTotal){
                    if(isset($notasTotales[$notaTotal])){
                        //Si ya esta en el array se le suma el valor
                        $notasTotales[$notaTotal] += $notasTotalesColabArea[$notaTotal];
                    } else{
                        //Si no esta en el array se agrega con el valor de la nota
                        $notasTotales[$notaTotal] = $notasTotalesColabArea[$notaTotal];
                    }
                }

                $promedioNotasColabArea = $dataColabArea['promedioNotas'];
                foreach(array_keys($promedioNotasColabArea) as $promedioNota){
                    if(isset($promedioNotas[$promedioNota])){
                        //Si ya esta en el array se le suma el valor
                        $promedioNotas[$promedioNota] += $promedioNotasColabArea[$promedioNota];
                    } else{
                        //Si no esta en el array se agrega con el valor del promedio
                        $promedioNotas[$promedioNota] = $promedioNotasColabArea[$promedioNota];
                    }
                }

                //Sumar promedio de cada colab area para obtener el promedio total del colaborador
                $promedio += $dataColabArea['promedio'];
                // return $dataColabArea;
            }
        }
        // return $colaboradorAreas;

        //dividir todo entre el numero de colaboradoresArea
        $colaboradorAreasCount = $colaboradorAreas->count() === 0 ?  1 : $colaboradorAreas->count();

        foreach(array_keys($promedioNotas) as $promedioNota){
            $promedioNotas[$promedioNota] = number_format($promedioNotas[$promedioNota]/$colaboradorAreasCount, 1);
        }
        $promedio = number_format($promedio/$colaboradorAreasCount, 1);

        $data = [
            "notasTotales" => $notasTotales,
            "promedioNotas" => $promedioNotas,
            "promedio" => $promedio,
        ];

        return $data;
    }

    public static function getConteoMaximoSemanasEvaluadasColaborador($colaborador_id){
        $conteoSemanas = 0;
        $colaborador_areas = Colaboradores_por_Area::where("colaborador_id", $colaborador_id)->get();
        foreach($colaborador_areas as $colaborador_area){
            $conteoSemanasColabArea = Cumplio_Responsabilidad_Semanal::where("colaborador_area_id",$colaborador_area->id)->get()
                ->pluck('semana_id')->unique()->count();
            if($conteoSemanasColabArea > $conteoSemanas) $conteoSemanas = $conteoSemanasColabArea;
        }
        return $conteoSemanas;
    }

    public function funcionPruebas(){
        $semanas = FunctionHelperController::semanasColaborador(1);
        // $resultado = FunctionHelperController::findThisWeek();
        $resultado = FunctionHelperController::promedioColaborador(1, $semanas['semanas']);
        $colab1 = FunctionHelperController::promedioColaboradorArea(1, $semanas['semanas']);
        $colab2 = FunctionHelperController::promedioColaboradorArea(14, $semanas['semanas']);

        return $semanas;
        // return [$colab1, $colab2];
    }

    public static function generarNroPago() {
        $ultimoPago = Transaccion::max('nro_pago');
        return $ultimoPago ? $ultimoPago + 1 : 1;
    }
}
