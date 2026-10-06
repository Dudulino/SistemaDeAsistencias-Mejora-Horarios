<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colaboradores;

class ColaboradoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Colaboradores::create([
            "candidato_id" => 1,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 2,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 3,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 4,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 5,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 6,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 7,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 8,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 9,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 10,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 11,
            "estado" => true 
        ]);

        Colaboradores::create([
            "candidato_id" => 12,
            "estado" => true 
        ]);
    }
}
