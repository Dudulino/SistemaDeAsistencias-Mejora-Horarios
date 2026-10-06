<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\Salones;
use App\Models\Area;
use App\Models\Horarios_Presenciales;
use App\Models\Horario_Presencial_Asignado;
use App\Models\ConfiguracionBloqueHorario;
use App\Models\DisponibilidadPracticante;
use App\Models\Institucion;
use App\Models\Sede;
use App\Models\Carrera;
use App\Models\Distrito;
use App\Models\Candidatos;
use App\Models\Colaboradores;
use App\Models\Colaboradores_por_Area;
use App\Models\Semanas;

class DemoHorariosSeeder extends Seeder
{
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Datos base
        |--------------------------------------------------------------------------
        */

        $institucion = Institucion::firstOrCreate([
            'nombre' => 'Senati'
        ]);

        $sede = Sede::updateOrCreate(
            ['nombre' => 'Senati - Independencia'],
            [
                'institucion_id' => $institucion->id,
                'estado' => 1
            ]
        );

        $carrera = Carrera::updateOrCreate(
            ['nombre' => 'Ingeniería de Software con Inteligencia Artificial'],
            ['estado' => 1]
        );

        $distrito = Distrito::where('nombre', 'Independencia')->first();

        $semana = Semanas::firstOrCreate(
            [
                'fecha_lunes' => Carbon::now()
                    ->startOfWeek(Carbon::MONDAY)
                    ->toDateString()
            ],
            [
                'caja_abierta' => 0
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 2. Salones
        |--------------------------------------------------------------------------
        */

        $datosSalones = [
            [
                'nombre' => 'Sala de Desarrollo',
                'descripcion' => 'Ambiente destinado al trabajo presencial de los practicantes del área de software.'
            ],
            [
                'nombre' => 'Sala de Administración TI',
                'descripcion' => 'Ambiente destinado a actividades de soporte y administración de tecnologías de información.'
            ],
            [
                'nombre' => 'Sala de Infraestructura y Redes',
                'descripcion' => 'Ambiente destinado a actividades de configuración, soporte y mantenimiento de redes e infraestructura tecnológica.'
            ],
            [
                'nombre' => 'Sala de Capacitación',
                'descripcion' => 'Ambiente destinado a capacitaciones, reuniones técnicas y actividades formativas de los practicantes.'
            ],
        ];

        $salones = [];

        foreach ($datosSalones as $dato) {
            $salones[$dato['nombre']] = Salones::updateOrCreate(
                ['nombre' => $dato['nombre']],
                ['descripcion' => $dato['descripcion']]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Áreas
        |--------------------------------------------------------------------------
        */

        $datosAreas = [
            [
                'nombre' => 'Área de Software',
                'descripcion' => 'Área encargada del desarrollo, mantenimiento y soporte de soluciones de software de la empresa.',
                'color' => '#3A86FF',
                'salon' => 'Sala de Desarrollo',
            ],
            [
                'nombre' => 'Administración TI',
                'descripcion' => 'Área encargada de la gestión y soporte de los recursos tecnológicos y servicios de TI de la empresa.',
                'color' => '#00B894',
                'salon' => 'Sala de Administración TI',
            ],
            [
                'nombre' => 'Infraestructura y Redes',
                'descripcion' => 'Área encargada de la instalación, configuración, soporte y mantenimiento de redes e infraestructura tecnológica.',
                'color' => '#6C5CE7',
                'salon' => 'Sala de Infraestructura y Redes',
            ],
            [
                'nombre' => 'Capacitación y Soporte',
                'descripcion' => 'Área destinada a la capacitación técnica, orientación y soporte de los practicantes en actividades relacionadas con tecnologías de información.',
                'color' => '#F39C12',
                'salon' => 'Sala de Capacitación',
            ],
        ];

        $areas = [];

        foreach ($datosAreas as $dato) {
            $areas[$dato['nombre']] = Area::updateOrCreate(
                ['especializacion' => $dato['nombre']],
                [
                    'descripcion' => $dato['descripcion'],
                    'color_hex' => $dato['color'],
                    'estado' => 1,
                    'salon_id' => $salones[$dato['salon']]->id,
                    'icono' => 'Default.png',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Horarios y bloques
        |--------------------------------------------------------------------------
        */

        $bloques = [
            ['area' => 'Área de Software',          'dia' => 'Lunes',     'inicio' => '08:00', 'fin' => '12:00', 'turno' => 'Mañana'],
            ['area' => 'Área de Software',          'dia' => 'Miércoles', 'inicio' => '08:00', 'fin' => '12:00', 'turno' => 'Mañana'],

            ['area' => 'Administración TI',         'dia' => 'Lunes',     'inicio' => '14:00', 'fin' => '18:00', 'turno' => 'Tarde'],
            ['area' => 'Administración TI',         'dia' => 'Miércoles', 'inicio' => '14:00', 'fin' => '18:00', 'turno' => 'Tarde'],

            ['area' => 'Infraestructura y Redes',   'dia' => 'Martes',    'inicio' => '08:00', 'fin' => '12:00', 'turno' => 'Mañana'],
            ['area' => 'Infraestructura y Redes',   'dia' => 'Jueves',    'inicio' => '08:00', 'fin' => '12:00', 'turno' => 'Mañana'],

            ['area' => 'Capacitación y Soporte',    'dia' => 'Martes',    'inicio' => '14:00', 'fin' => '18:00', 'turno' => 'Tarde'],
            ['area' => 'Capacitación y Soporte',    'dia' => 'Jueves',    'inicio' => '14:00', 'fin' => '18:00', 'turno' => 'Tarde'],
        ];

        foreach ($bloques as $bloque) {

            $horario = Horarios_Presenciales::firstOrCreate([
                'dia' => $bloque['dia'],
                'hora_inicial' => $bloque['inicio'],
                'hora_final' => $bloque['fin'],
            ]);

            $asignado = Horario_Presencial_Asignado::firstOrCreate([
                'horario_presencial_id' => $horario->id,
                'area_id' => $areas[$bloque['area']]->id,
            ]);

            ConfiguracionBloqueHorario::updateOrCreate(
                [
                    'horario_presencial_asignado_id' => $asignado->id
                ],
                [
                    'turno' => $bloque['turno'],
                    'cupo_min' => 2,
                    'cupo_max' => 5,
                    'modalidad' => 'Presencial',
                    'estado' => 1,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Practicantes
        |--------------------------------------------------------------------------
        */

        $practicantes = [
            ['nombre' => 'Ana',     'apellido' => 'Torres',   'correo' => 'practicante01@demo.local', 'dni' => '70000001', 'celular' => '900000001', 'area' => 'Área de Software'],
            ['nombre' => 'Luis',    'apellido' => 'Mendoza',  'correo' => 'practicante02@demo.local', 'dni' => '70000002', 'celular' => '900000002', 'area' => 'Área de Software'],

            ['nombre' => 'Carlos',  'apellido' => 'Ramirez',  'correo' => 'practicante03@demo.local', 'dni' => '70000003', 'celular' => '900000003', 'area' => 'Administración TI'],
            ['nombre' => 'Maria',   'apellido' => 'Flores',   'correo' => 'practicante04@demo.local', 'dni' => '70000004', 'celular' => '900000004', 'area' => 'Administración TI'],

            ['nombre' => 'Diego',   'apellido' => 'Vargas',   'correo' => 'practicante05@demo.local', 'dni' => '70000005', 'celular' => '900000005', 'area' => 'Infraestructura y Redes'],
            ['nombre' => 'Valeria', 'apellido' => 'Rojas',    'correo' => 'practicante06@demo.local', 'dni' => '70000006', 'celular' => '900000006', 'area' => 'Infraestructura y Redes'],

            ['nombre' => 'Jorge',   'apellido' => 'Castro',   'correo' => 'practicante07@demo.local', 'dni' => '70000007', 'celular' => '900000007', 'area' => 'Capacitación y Soporte'],
            ['nombre' => 'Lucia',   'apellido' => 'Salazar',  'correo' => 'practicante08@demo.local', 'dni' => '70000008', 'celular' => '900000008', 'area' => 'Capacitación y Soporte'],
        ];

        foreach ($practicantes as $indice => $dato) {

            $candidato = Candidatos::updateOrCreate(
                ['correo' => $dato['correo']],
                [
                    'nombre' => $dato['nombre'],
                    'apellido' => $dato['apellido'],
                    'dni' => $dato['dni'],
                    'celular' => $dato['celular'],
                    'ciclo_de_estudiante' => 6,
                    'estado' => 0,
                    'sede_id' => $sede->id,
                    'carrera_id' => $carrera->id,
                    'distrito_id' => $distrito?->id,
                    'id_senati' => 'SENATI' . str_pad($indice + 1, 3, '0', STR_PAD_LEFT),
                    'icono' => 'Default.png',
                ]
            );

            $colaborador = Colaboradores::updateOrCreate(
                ['candidato_id' => $candidato->id],
                [
                    'estado' => 1,
                    'editable' => 1,
                ]
            );

            Colaboradores_por_Area::updateOrCreate(
                [
                    'colaborador_id' => $colaborador->id,
                    'area_id' => $areas[$dato['area']]->id,
                ],
                [
                    'semana_inicio_id' => $semana->id,
                    'estado' => 1,
                    'jefe_area' => 0,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Disponibilidad según los bloques de su área
            |--------------------------------------------------------------------------
            */

            foreach ($bloques as $bloque) {

                if ($bloque['area'] !== $dato['area']) {
                    continue;
                }

                DisponibilidadPracticante::updateOrCreate(
                    [
                        'colaborador_id' => $colaborador->id,
                        'dia' => $bloque['dia'],
                        'hora_inicial' => $bloque['inicio'],
                        'hora_final' => $bloque['fin'],
                    ],
                    [
                        'tipo' => 'disponible',
                        'origen' => 'practicas',
                        'modalidad' => 'Presencial',
                        'observacion' => 'Disponibilidad de demostración compatible con el área.',
                        'estado' => 1,
                    ]
                );
            }
        }

        $this->command->info('Demo de asignación automatizada creado correctamente.');
        $this->command->info('4 áreas - 4 salones - 8 practicantes - 16 disponibilidades - 8 bloques.');
    }
}