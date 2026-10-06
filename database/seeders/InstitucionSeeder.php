<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\Institucion;

class InstitucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Institucion::create([
            'nombre' => 'Senati'
        ]);
        Institucion::create([
            'nombre' => 'UPC'
        ]);
        Institucion::create([
            'nombre' => 'UNI'
        ]);
    }
}
