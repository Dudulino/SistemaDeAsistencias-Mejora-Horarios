<?php

namespace App\Http\Controllers;

use App\Models\Candidatos;
use App\Models\Colaboradores;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function index()
    {
        $candidatos = Candidatos::where('estado', 0)->get();
        $birthdays = Colaboradores::whereIn('candidato_id', $candidatos->pluck('id'))->whereIn('estado', [0, 1])->get();
        // $birthdays = Colaboradores::whereIn('candidato_id', $candidatos->pluck('id'))->where('estado', 1)->get();

        $events = [];
        $hoy = Carbon::now()->format('m-d');

        foreach ($birthdays as $colaborador) {
            if ($colaborador->candidato && !empty($colaborador->candidato->fecha_nacimiento)) {
                $fechaNacimiento = Carbon::parse($colaborador->candidato->fecha_nacimiento);
                $fechaCumple = $fechaNacimiento->format('m-d');

                // Determinar el color según el estado del colaborador
                $backgroundColor = $colaborador->estado == 1 ? '#28a745' : '#ffc107'; // Verde para estado 1, amarillo para estado 0
                $textColor = $colaborador->estado == 1 ? '#fff' : '#000'; // Texto blanco para verde, negro para amarillo

                // Crear evento para el calendario
                $events[] = [
                    'title' => '🎉 ' . $colaborador->candidato->nombre . ' ' . $colaborador->candidato->apellido,
                    'start' => Carbon::now()->year . '-' . $fechaCumple,
                    'allDay' => true,
                    'description' => 'Cumpleaños de ' . $colaborador->candidato->nombre . ' ' . $colaborador->candidato->apellido,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $backgroundColor,
                    'textColor' => $textColor,
                ];
            }
        }

        return view('inspiniaViews.birthdays.index', compact('events'));
    }

    public function getCumpleanerosHoy()
    {
        $candidatosIds = Candidatos::where('estado', 0)->pluck('id');
        $hoy = Carbon::now()->format('m-d');
        $count = Colaboradores::whereIn('candidato_id', $candidatosIds)
            ->where('estado', 1)
            ->whereHas('candidato', function ($query) use ($hoy) {
                $query->whereRaw("DATE_FORMAT(fecha_nacimiento, '%m-%d') = ?", [$hoy]);
            })
            ->count();

        return response()->json(['count' => $count]);
    }
}
