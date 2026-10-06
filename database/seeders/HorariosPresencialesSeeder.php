<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Horarios_Presenciales;

class HorariosPresencialesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //LUNES
        Horarios_Presenciales::create([
            'hora_inicial' => '8:00',
            'hora_final' => '12:00',
            'dia' => 'Lunes'
        ]);
        Horarios_Presenciales::create([
            'hora_inicial' => '14:00',
            'hora_final' => '18:00',
            'dia' => 'Lunes'
        ]);

        //MARTES
        Horarios_Presenciales::create([
            'hora_inicial' => '8:00',
            'hora_final' => '12:00',
            'dia' => 'Martes'
        ]);
        Horarios_Presenciales::create([
            'hora_inicial' => '14:00',
            'hora_final' => '18:00',
            'dia' => 'Martes'
        ]);

        //MIÉRCOLES
        Horarios_Presenciales::create([
            'hora_inicial' => '8:00',
            'hora_final' => '12:00',
            'dia' => 'Miércoles'
        ]);
        Horarios_Presenciales::create([
            'hora_inicial' => '14:00',
            'hora_final' => '18:00',
            'dia' => 'Miércoles'
        ]);

        //JUEVES
        Horarios_Presenciales::create([
            'hora_inicial' => '8:00',
            'hora_final' => '12:00',
            'dia' => 'Jueves'
        ]);
        Horarios_Presenciales::create([
            'hora_inicial' => '14:00',
            'hora_final' => '18:00',
            'dia' => 'Jueves'
        ]);

        //VIERNES
        Horarios_Presenciales::create([
            'hora_inicial' => '8:00',
            'hora_final' => '12:00',
            'dia' => 'Viernes'
        ]);
        Horarios_Presenciales::create([
            'hora_inicial' => '14:00',
            'hora_final' => '18:00',
            'dia' => 'Viernes'
        ]);

        //SABADO
        Horarios_Presenciales::create([
            'hora_inicial' => '8:00',
            'hora_final' => '12:00',
            'dia' => 'Sábado'
        ]);
        Horarios_Presenciales::create([
            'hora_inicial' => '14:00',
            'hora_final' => '18:00',
            'dia' => 'Sábado'
        ]);

    }
}
