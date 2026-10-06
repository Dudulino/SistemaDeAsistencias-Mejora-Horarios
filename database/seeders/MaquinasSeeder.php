<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Maquinas;

class MaquinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Maquinas::create([
            "nombre" => "Maquina N°1",
            "detalles_tecnicos" => "Detalle N°1",
            "num_identificador" => 1,
            "salon_id" => 1
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°2",
            "detalles_tecnicos" => "Detalle N°2",
            "num_identificador" => 2,
            "salon_id" => 1
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°3",
            "detalles_tecnicos" => "Detalle N°3",
            "num_identificador" => 3,
            "salon_id" => 1
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°4",
            "detalles_tecnicos" => "Detalle N°4",
            "num_identificador" => 4,
            "salon_id" => 2
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°5",
            "detalles_tecnicos" => "Detalle N°5",
            "num_identificador" => 5,
            "salon_id" => 2
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°6",
            "detalles_tecnicos" => "Detalle N°6",
            "num_identificador" => 6,
            "salon_id" => 2
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°7",
            "detalles_tecnicos" => "Detalle N°7",
            "num_identificador" => 7,
            "salon_id" => 2
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°8",
            "detalles_tecnicos" => "Detalle N°8",
            "num_identificador" => 8,
            "salon_id" => 3
        ]);
        Maquinas::create([
            "nombre" => "Maquina N°9",
            "detalles_tecnicos" => "Detalle N°9",
            "num_identificador" => 9,
            "salon_id" => 3
        ]);
    }
}
