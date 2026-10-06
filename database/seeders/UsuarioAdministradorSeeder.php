<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UsuarioAdministrador;

class UsuarioAdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsuarioAdministrador::create([
            'user_id' => 1,
            'super_admin' => true,
        ]);

        UsuarioAdministrador::create([
            'user_id' => 2,
            'super_admin' => false,
        ]);
    }
}
