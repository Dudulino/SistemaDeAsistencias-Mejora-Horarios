<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Colaboradores;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // UserSeeder::class,
            // CarreraSeeder::class, //Reemplazar con los de sql
            // InstitucionSeeder::class, // Reemplazar con los de sql
            // SalonesSeeder::class,
            // AreaSeeder::class, // Reemplazar con los de sql
            // MaquinasSeeder::class, // Reemplazar con los de sql
            // HorariosPresencialesSeeder::class, // Reemplazar con los de sql
            // CursoSeeder::class,
            // ProgramaSeeder::class,
            // SemanaSeeder::class,
            // ResponsabilidadSemanalSeeder::class, // Reemplazar con los de sql
            // SedeSeeder::class,
            // CandidatosSeeder::class, // Reemplazar con los de sql
            // HorarioPresencialAsignadoSeeder::class, // Reemplazar con los de sql
            // ColaboradoresSeeder::class, // Reemplazar con los de sql
            // ColaboradoresPorAreaSeeder::class, // Reemplazar con los de sql
            // HorarioDeClasesSeeder::class, // Reemplazar con los de sql
            // UsuarioAdministradorSeeder::class,
            // UsuarioJefeAreaSeeder::class,
            // UsuariosPasswordSeeder::class
            // TipoTransaccionSeeder::class
            DistritosSeeder::class
        ]);
            \App\Models\User::factory(10)->create();

            \App\Models\User::factory()->create([
               'name' => 'Test User',
               'email' => 'test@example.com',
           ]);
    }
}
