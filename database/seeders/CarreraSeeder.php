<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Carrera;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Carrera::create([
            'nombre' => 'Desarrollo de Software'
        ]);
        Carrera::create([
            'nombre' => 'Ingenieria de Software con IA'
        ]);
        Carrera::create([
            'nombre' => 'Diseño Gráfico Digital'
        ]);
        Carrera::create([
            'nombre' => 'Administración'
        ]);
        Carrera::create([
            'nombre' => 'Soporte'
        ]);
    }
}
