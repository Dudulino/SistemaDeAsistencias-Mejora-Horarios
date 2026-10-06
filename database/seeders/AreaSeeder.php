<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create([
            "especializacion" => "Videojuegos",
            "descripcion" => "Programadores de videojuegos",
            "color_hex" => "#aa0303",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        Area::create([
            "especializacion" => "Análisis y Videojuegos",
            "descripcion" => "Análisis 3",
            "color_hex" => "#ffc8aa",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        Area::create([
            "especializacion" => "Wordpress",
            "descripcion" => "Wordpress UI/UX/UML",
            "color_hex" => "#e6cff2",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        Area::create([
            "especializacion" => "Front-End 1",
            "descripcion" => "Implementación Front-End",
            "color_hex" => "#5a3286",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);

        Area::create([
            "especializacion" => "Análisis 1",
            "descripcion" => "Análisis y diseño",
            "color_hex" => "#094a97",
            "salon_id" => 2,
            "icono" => "Default.png"
        ]);
        Area::create([
            "especializacion" => "Front-End 2",
            "descripcion" => "Implementación Front-End",
            "color_hex" => "#ff00dd",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        Area::create([
            "especializacion" => "Análisis 4",
            "descripcion" => "Análisis y diseño 4",
            "color_hex" => "#ffcfc9",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        Area::create([
            "especializacion" => "IA",
            "descripcion" => "Inteligencia Articial",
            "color_hex" => "#693200",
            "salon_id" => 2,
            "icono" => "Default.png"
        ]);

        Area::create([
            "especializacion" => "Programación Web",
            "descripcion" => "Implementación (programación)",
            "color_hex" => "#11734b",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        
        Area::create([
            "especializacion" => "APIS",
            "descripcion" => "Creación APIS",
            "color_hex" => "#ffe5a0",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);
        
        Area::create([
            "especializacion" => "Rubi",
            "descripcion" => "Lenguaje específico",
            "color_hex" => "#ad232d",
            "salon_id" => 2,
            "icono" => "Default.png"
        ]);

        Area::create([
            "especializacion" => "Android",
            "descripcion" => "Programación Android y Análisis",
            "color_hex" => "#16a70c",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);

        Area::create([
            "especializacion" => "Análisis 2",
            "descripcion" => "Análisis y diseño 2",
            "color_hex" => "#13c9bd",
            "salon_id" => 2,
            "icono" => "Default.png"
        ]);

        Area::create([
            "especializacion" => "Blender 3D",
            "descripcion" => "Diseño de personajes 3D",
            "color_hex" => "#c07a2a",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);

        Area::create([
            "especializacion" => "Capacitación",
            "descripcion" => "La oveja negra",
            "color_hex" => "#616161",
            "salon_id" => 1,
            "icono" => "Default.png"
        ]);

    }
}
