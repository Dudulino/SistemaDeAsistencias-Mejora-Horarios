<?php

namespace Database\Seeders;

use App\Models\TipoTransacciones;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTransaccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoTransacciones::create([
            'descripcion' => 'Depósito',
            'es_ingreso' => true
        ]);

        TipoTransacciones::create([
            'descripcion' => 'Caja',
            'es_ingreso' => false
        ]);

        TipoTransacciones::create([
            'descripcion' => 'Colaborador',
            'es_ingreso' => false
        ]);
    }
}
