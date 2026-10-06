<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colaboradores_por_Area;

class ColaboradoresPorAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Colaboradores_por_Area::create([
            "colaborador_id" => 1,
            "area_id" => 1,
            "semana_inicio_id" => 1
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 2,
            "area_id" => 1,
            "semana_inicio_id" => 2
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 3,
            "area_id" => 1,
            "semana_inicio_id" => 2
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 4,
            "area_id" => 1,
            "semana_inicio_id" => 2
        ]);


        Colaboradores_por_Area::create([
            "colaborador_id" => 5,
            "area_id" => 2,
            "semana_inicio_id" => 1
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 6,
            "area_id" => 2,
            "semana_inicio_id" => 1
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 7,
            "area_id" => 2,
            "semana_inicio_id" => 1
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 8,
            "area_id" => 2,
            "semana_inicio_id" => 2
        ]);


        Colaboradores_por_Area::create([
            "colaborador_id" => 9,
            "area_id" => 3,
            "semana_inicio_id" => 3
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 10,
            "area_id" => 3,
            "semana_inicio_id" => 3
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 11,
            "area_id" => 3,
            "semana_inicio_id" => 3
        ]);

        Colaboradores_por_Area::create([
            "colaborador_id" => 12,
            "area_id" => 3,
            "semana_inicio_id" => 5
        ]);


    }
}
