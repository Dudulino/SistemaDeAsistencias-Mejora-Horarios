<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Programas;

class ProgramaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Programas::create([
            "nombre" => "StarUML",
            "descripcion" => "Creación de Diagramas",
            "icono" => "staruml.svg"
        ]);
        Programas::create([
            "nombre" => "Adobe Illustrator",
            "descripcion" => "Editor de gráficos",
            "icono" => "illustrator.svg"
        ]);
        Programas::create([
            "nombre" => "Adobe Photoshop",
            "descripcion" => "Editor de fotografías",
            "icono" => "photoshop.svg"
        ]);
        
    }
}
