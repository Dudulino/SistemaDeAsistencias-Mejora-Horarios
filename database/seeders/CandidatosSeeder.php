<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Candidatos;

class CandidatosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Candidatos::create([
            "nombre" => "Martha",
            "apellido" => "Lopez",
            "dni" => "01234567",
            "direccion" => "direccion ejemplo #001",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 5,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 1,
            "correo" => "01234567@gmail.com",
            "celular" => "987654321",
            "icono" => "candidato1.jpg",
        ]);

        Candidatos::create([
            "nombre" => "default",
            "apellido" => "001",
            "dni" => "11111111",
            "direccion" => "direccion ejemplo",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 2,
            "correo" => "default01@gmail.com",
            "celular" => "111111111",
            "icono" => "default.png",
        ]);

        Candidatos::create([
            "nombre" => "Marlo",
            "apellido" => "Zamaniego",
            "dni" => "73452363",
            "direccion" => "direccion ejemplo #002",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 2,
            "carrera_id" => 5,
            "correo" => "marlo@gmail.com",
            "celular" => "982342649",
            "icono" => "candidato2.jpg",
        ]);

        Candidatos::create([
            "nombre" => "default",
            "apellido" => "002",
            "dni" => "222222",
            "direccion" => "direccion ejemplo",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 2,
            "correo" => "default02@gmail.com",
            "celular" => "222222222",
            "icono" => "default.png",
        ]);

        Candidatos::create([
            "nombre" => "Jorge",
            "apellido" => "Gomez",
            "dni" => "04296422",
            "direccion" => "direccion ejemplo #001",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 5,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 3,
            "correo" => "jorge@gmail.com",
            "celular" => "932541352",
            "icono" => "candidato3.jpg",
        ]);

        Candidatos::create([
            "nombre" => "default",
            "apellido" => "003",
            "dni" => "33333333",
            "direccion" => "direccion ejemplo",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 2,
            "correo" => "default03@gmail.com",
            "celular" => "333333333",
            "icono" => "default.png",
        ]);

        Candidatos::create([
            "nombre" => "Karla",
            "apellido" => "Lopez",
            "dni" => "32284626",
            "direccion" => "direccion ejemplo #002",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 2,
            "carrera_id" => 4,
            "correo" => "karla@gmail.com",
            "celular" => "941285161",
            "icono" => "candidato4.jpg",
        ]);

        Candidatos::create([
            "nombre" => "default",
            "apellido" => "004",
            "dni" => "44444444",
            "direccion" => "direccion ejemplo",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 2,
            "correo" => "default04@gmail.com",
            "celular" => "444444444",
            "icono" => "default.png",
        ]);

        Candidatos::create([
            "nombre" => "Daniel",
            "apellido" => "Roman",
            "dni" => "34626422",
            "direccion" => "direccion ejemplo #001",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 5,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 1,
            "correo" => "Daniel@gmail.com",
            "celular" => "932893242",
            "icono" => "candidato5.jpg",
        ]);

        Candidatos::create([
            "nombre" => "default",
            "apellido" => "005",
            "dni" => "55555555",
            "direccion" => "direccion ejemplo",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 2,
            "correo" => "default05@gmail.com",
            "celular" => "555555555",
            "icono" => "default.png",
        ]);

        Candidatos::create([
            "nombre" => "Ana",
            "apellido" => "Cristina",
            "dni" => "53332146",
            "direccion" => "direccion ejemplo #002",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 2,
            "carrera_id" => 3,
            "correo" => "cristina@gmail.com",
            "celular" => "932424822",
            "icono" => "candidato6.jpg",
        ]);

        Candidatos::create([
            "nombre" => "default",
            "apellido" => "006",
            "dni" => "66666666",
            "direccion" => "direccion ejemplo",
            "fecha_nacimiento" => "2000-12-12",
            "ciclo_de_estudiante" => 4,
            "estado" => 0,
            "sede_id" => 1,
            "carrera_id" => 2,
            "correo" => "default06@gmail.com",
            "celular" => "666666666",
            "icono" => "default.png",
        ]);

    }
}
