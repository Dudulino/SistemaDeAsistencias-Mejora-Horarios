<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Horario_de_Clases;

class HorarioDeClasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Horario_de_Clases::create([
            "colaborador_id" => 1,
            "hora_inicial" => "07:00",
            "hora_final" => "19:00",
            "dia" => "Lunes",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 2,
            "hora_inicial" => "07:00",
            "hora_final" => "12:00",
            "dia" => "Miércoles",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 2,
            "hora_inicial" => "07:00",
            "hora_final" => "17:00",
            "dia" => "Viernes",
            "justificacion" => "Trabajo"
        ]);

        Horario_de_Clases::create([
            "colaborador_id" => 3,
            "hora_inicial" => "07:00",
            "hora_final" => "19:00",
            "dia" => "Lunes",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 4,
            "hora_inicial" => "07:00",
            "hora_final" => "12:00",
            "dia" => "Miércoles",
            "justificacion" => "Clases"
        ]);

        Horario_de_Clases::create([
            "colaborador_id" => 5,
            "hora_inicial" => "07:00",
            "hora_final" => "17:00",
            "dia" => "Viernes",
            "justificacion" => "Trabajo"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 6,
            "hora_inicial" => "07:00",
            "hora_final" => "19:00",
            "dia" => "Lunes",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 7,
            "hora_inicial" => "07:00",
            "hora_final" => "12:00",
            "dia" => "Miércoles",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 8,
            "hora_inicial" => "07:00",
            "hora_final" => "17:00",
            "dia" => "Viernes",
            "justificacion" => "Trabajo"
        ]);

        Horario_de_Clases::create([
            "colaborador_id" => 9,
            "hora_inicial" => "07:00",
            "hora_final" => "17:00",
            "dia" => "Viernes",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 10,
            "hora_inicial" => "07:00",
            "hora_final" => "19:00",
            "dia" => "Lunes",
            "justificacion" => "Trabajo"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 11,
            "hora_inicial" => "07:00",
            "hora_final" => "12:00",
            "dia" => "Miércoles",
            "justificacion" => "Clases"
        ]);
        Horario_de_Clases::create([
            "colaborador_id" => 12,
            "hora_inicial" => "07:00",
            "hora_final" => "17:00",
            "dia" => "Viernes",
            "justificacion" => "Clases"
        ]);
    }
}
