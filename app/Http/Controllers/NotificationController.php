<?php

namespace App\Http\Controllers;

use App\Models\Colaboradores;
use App\Models\Horario_Presencial_Asignado;
use App\Models\Horarios_Presenciales;
use App\Models\ReunionesProgramadas;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;


class NotificationController extends Controller
{

    public function index(){
        $notifications = [];

        $userData = FunctionHelperController::getUserRol();

        $today = Carbon::now()->format('Y-m-d');
        $dia_today = Carbon::now()->format('l');
        //traducir el día a español
        $dias = [
            "Monday" => "Lunes",
            "Tuesday" => "Martes",
            "Wednesday" => "Miércoles",
            "Thursday" => "Jueves",
            "Friday" => "Viernes",
            "Saturday" => "Sábado",
            "Sunday" => "Domingo"
        ];

        $dia_español = $dias[$dia_today];

        if($userData['isAdmin']){

            $BirthDayColaboradores = [];

            $numberDayToday = date('d', strtotime($today));
            $numberMonthToday = date('m', strtotime($today));
            $colaboradoresEmpresa = Colaboradores::whereNot("estado", 2)->get();
            foreach($colaboradoresEmpresa as $colab){
                $colabDay = date('d', strtotime($colab->candidato->fecha_nacimiento));
                $colabMonth = date('m', strtotime($colab->candidato->fecha_nacimiento));
                if($colabMonth === $numberMonthToday){
                    if($colabDay === $numberDayToday){
                        $BirthDayColaboradores[] = $colab;
                    }
                }
            }

            // return $BirthDayColaboradores;

            $reunionesProgramadasToday = ReunionesProgramadas::where('fecha', $today)->get();
            $horariosToday = Horarios_Presenciales::where('dia', $dia_español)->get();
            $horariosAreasToday = Horario_Presencial_Asignado::with('area', 'horario_presencial')->whereIn('horario_presencial_id', $horariosToday->pluck('id'))->get();
            $areasToday = [];
            foreach($horariosAreasToday as $horario){
                // Verificar si el área está activa antes de agregarla a las notificaciones
                if ($horario->area->estado == 1) {
                    // Si el area ya esta en el array areasToday, entonces modificar
                    if(isset($areasToday[$horario->area_id])){
                        // Modificar hoy_inicial y hora_final según corresponda
                        if($areasToday[$horario->area_id]['horario_id'] < $horario->horario_presencial_id){
                            // Quedarse con el horario_id más grande
                            $areasToday[$horario->area_id]['horario_id'] = $horario->horario_presencial_id;
                            $areasToday[$horario->area_id]['hora_final'] = $horario->horario_presencial->hora_final;
                        } else if($areasToday[$horario->area_id]['horario_id'] > $horario->horario_presencial_id){
                            $areasToday[$horario->area_id]['hora_inicial'] = $horario->horario_presencial->hora_inicial;
                        }
                    } else {
                        $areasToday[$horario->area_id] = [
                            'area_id' => $horario->area_id,
                            'area' => $horario->area->especializacion,
                            'horario_id' => $horario->horario_presencial_id,
                            'hora_inicial' => $horario->horario_presencial->hora_inicial,
                            'hora_final' => $horario->horario_presencial->hora_final,
                        ];
                    }
                }
            }


            foreach($reunionesProgramadasToday as $reunion){
                $notificacion = [
                    "icon" => "fa fa-video-camera",
                    "message" => "Reunión ".$reunion->disponibilidad." para el día de hoy de ".$reunion->hora_inicial." a ".$reunion->hora_final,
                    "url" => route('reunionesProgramadas.show', $reunion->id),
                ];
                $notifications[] = $notificacion;
            }

            foreach($areasToday as $area){
                $notificacion = [
                    "icon" => "fa fa-clock-o",
                    "message" => "Área ".$area['area']." asiste presencialmente el día de hoy de ".$area['hora_inicial']." a ".$area['hora_final'],
                    "url" => route('areas.getHorario', $area['area_id']),
                ];
                $notifications[] = $notificacion;
            }

            foreach($BirthDayColaboradores as $colab){
                $notificacion = [
                    "icon" => "fa fa-birthday-cake",
                    "message" => $colab->candidato->nombre." ".$colab->candidato->apellido." cumple años el día de hoy.",
                    "url" => "#"
                ];
                $notifications[] = $notificacion;
            }
        }

        return response()->json(["notifications" => $notifications]);
    }


    public function login(Request $request)
    {
        try {
            if (! Auth::attempt($request->only('email', 'password'))) {
                return response()->json(["message" => "Unauthorized"], 401);
            }
            $url = route('regenerateSession', ["email" => $request->email, "password" => $request->password]);

            $user = User::where('email', $request->email)->firstOrFail();

            return response()->json([
                'status' => 200,
                'message' => 'Inicio de sesión exitoso.',
                'user' => $user,
                'url' => $url
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                "status" => "500",
                "message" => "Ha ocurrido un error",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function regenerateSession(Request $request, $email, $password){

        // Crear una copia de $request y agregar los datos de email y password
        $newRequest = $request->duplicate(); // Clona el objeto Request original
        $newRequest->merge([
            'email' => $email,
            'password' => $password
        ]);
        // $request->email = $email;
        // $request->password = $password;
        // return $newRequest;
        if (! Auth::attempt($newRequest->only('email', 'password'))) {
            return response()->json(["message" => "Unauthorized"], 401);
        }

        $newRequest->session()->regenerate();
        return redirect()->intended(RouteServiceProvider::HOME);

    }

}
