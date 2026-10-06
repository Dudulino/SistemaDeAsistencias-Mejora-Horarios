<?php

namespace Database\Seeders;

use App\Models\UsuariosPasswords;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UsuariosPasswords::create([
            "user_id" => 1,
            "password" => 'P$$$JYP0824'
        ]);
        UsuariosPasswords::create([
            "user_id" => 2,
            "password" => '@G^e^Fet&VGTsUBqLekW'
        ]);
        UsuariosPasswords::create([
            "user_id" => 3,
            "password" => 'password'
        ]);
        UsuariosPasswords::create([
            "user_id" => 4,
            "password" => 'P$$$0824'
        ]);
        
    }
}
