<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProyectoDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Asegura un creador para asociar proyectos demo
        $creador = User::firstOrCreate(
            ['email' => 'creador-demo@app.test'],
            [
                'name' => 'Creador Demo',
                'nombre_completo' => 'Creador Demo',
                'password' => Hash::make('secret'),
                'estado_verificacion' => true,
                'indice_confianza' => 90,
            ]
        );

        $projects = [
            [
                'titulo' => 'Purificador solar para escuelas rurales',
                'descripcion_proyecto' => 'Llevamos agua segura a 10 comunidades con sistemas solares portatiles.',
                'meta_financiacion' => 25000,
                'monto_recaudado' => 18200,
                'fecha_limite' => Carbon::now()->addDays(45),
                'modelo_financiamiento' => 'todo-o-nada',
                'categoria' => 'Impacto social',
                'ubicacion_geografica' => 'Cusco, Peru',
            ],
            [
                'titulo' => 'Documental: Voces de la Amazonia',
                'descripcion_proyecto' => 'Historias que necesitan ser escuchadas para proteger la selva.',
                'meta_financiacion' => 20000,
                'monto_recaudado' => 9050,
                'fecha_limite' => Carbon::now()->addDays(30),
                'modelo_financiamiento' => 'flexible',
                'categoria' => 'Cultura',
                'ubicacion_geografica' => 'Manaus, Brasil',
            ],
            [
                'titulo' => 'Kit de protesis impresas en 3D',
                'descripcion_proyecto' => 'Acceso asequible a protesis personalizadas mediante impresion 3D.',
                'meta_financiacion' => 30000,
                'monto_recaudado' => 26740,
                'fecha_limite' => Carbon::now()->addDays(60),
                'modelo_financiamiento' => 'todo-o-nada',
                'categoria' => 'Salud',
                'ubicacion_geografica' => 'Bogota, Colombia',
            ],
        ];

        foreach ($projects as $project) {
            DB::table('proyectos')->updateOrInsert(
                ['titulo' => $project['titulo']],
                array_merge($project, [
                    'creador_id' => $creador->id,
                    'estado' => 'publicado',
                    'cronograma' => null,
                    'presupuesto' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}
