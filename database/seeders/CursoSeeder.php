<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cursos;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cursos::create([
            "nombre" => "curso1",
            "categoria" => "Diseño Grafico",
            "duracion" => "3 semanas"
        ]);
        Cursos::create([
            "nombre" => "curso2",
            "categoria" => "Videojuegos",
            "duracion" => "4 semanas"
        ]);
        Cursos::create([
            "nombre" => "curso3",
            "categoria" => "Programacion",
            "duracion" => "3 semanas"
        ]);
    }
}
