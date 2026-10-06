<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Horario_Presencial_Asignado;

class HorarioPresencialAsignadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Horario_Presencial_Asignado::create([
            'horario_presencial_id' => 3,
            'area_id' => 1
        ]);
        Horario_Presencial_Asignado::create([
            'horario_presencial_id' => 8,
            'area_id' => 2
        ]);

        Horario_Presencial_Asignado::create([
            'horario_presencial_id' => 7,
            'area_id' => 3
        ]);
        Horario_Presencial_Asignado::create([
            'horario_presencial_id' => 1,
            'area_id' => 4
        ]);
    }
}
