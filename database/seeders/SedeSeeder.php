<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sede;

class SedeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sede::create([
            'nombre' => 'Senati - Independencia', 
            'institucion_id' => 1
        ]);

        Sede::create([
            'nombre' => 'UPC - Central', 
            'institucion_id' => 2
        ]);

        Sede::create([
            'nombre' => 'UNI - Central', 
            'institucion_id' => 3
        ]);
    }
}
