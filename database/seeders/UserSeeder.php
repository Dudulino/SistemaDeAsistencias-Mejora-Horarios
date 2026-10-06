<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\User;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Brisila",
            "apellido" => "Bedregal",
            "email" => "brisadmin@gmail.com",
            "password" => Hash::make('P$$$JYP0824')
        ]);

        User::create([
            "name" => "admin",
            "apellido" => "administrator",
            "email" => "admin@gmail.com",
            "password" => Hash::make('password')
        ]);

        User::create([
            "name" => "Cristopher",
            "apellido" => "De la Cruz",
            "email" => "cris@gmail.com",
            "password" => Hash::make('password')
        ]);
    }
}
