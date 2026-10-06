<?php

namespace Database\Seeders;

use App\Models\Distrito;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistritosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nombres = [
                ['nombre' => 'Ancón'],
                ['nombre' => 'Ate'],
                ['nombre' => 'Barranco'],
                ['nombre' => 'Breña'],
                ['nombre' => 'Carabayllo'],
                ['nombre' => 'Cercado de Lima'],
                ['nombre' => 'Chaclacayo'],
                ['nombre' => 'Chorrillos'],
                ['nombre' => 'Cieneguilla'],
                ['nombre' => 'Comas'],
                ['nombre' => 'El Agustino'],
                ['nombre' => 'Independencia'],
                ['nombre' => 'Jesús María'],
                ['nombre' => 'La Molina'],
                ['nombre' => 'La Victoria'],
                ['nombre' => 'Lince'],
                ['nombre' => 'Los Olivos'],
                ['nombre' => 'Lurigancho-Chosica'],
                ['nombre' => 'Lurín'],
                ['nombre' => 'Magdalena del Mar'],
                ['nombre' => 'Miraflores'],
                ['nombre' => 'Pachacámac'],
                ['nombre' => 'Pucusana'],
                ['nombre' => 'Pueblo Libre'],
                ['nombre' => 'Puente Piedra'],
                ['nombre' => 'Punta Hermosa'],
                ['nombre' => 'Punta Negra'],
                ['nombre' => 'Rímac'],
                ['nombre' => 'San Bartolo'],
                ['nombre' => 'San Borja'],
                ['nombre' => 'San Isidro'],
                ['nombre' => 'San Juan de Lurigancho'],
                ['nombre' => 'San Juan de Miraflores'],
                ['nombre' => 'San Luis'],
                ['nombre' => 'San Martín de Porres'],
                ['nombre' => 'San Miguel'],
                ['nombre' => 'Santa Anita'],
                ['nombre' => 'Santa María del Mar'],
                ['nombre' => 'Santa Rosa'],
                ['nombre' => 'Santiago de Surco'],
                ['nombre' => 'Surquillo'],
                ['nombre' => 'Villa El Salvador'],
                ['nombre' => 'Villa María del Triunfo'],
        ];

        Distrito::insert($nombres);
    }
}
