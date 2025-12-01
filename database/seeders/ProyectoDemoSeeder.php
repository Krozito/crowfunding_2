<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Proyecto;
use App\Models\Aportacion;
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

        $colaboradorRole = Role::firstOrCreate(['nombre_rol' => 'COLABORADOR']);
        $colaboradores = collect([
            ['email' => 'ana.colab@app.test', 'name' => 'Ana Colab'],
            ['email' => 'leo.colab@app.test', 'name' => 'Leo Colab'],
            ['email' => 'sara.colab@app.test', 'name' => 'Sara Colab'],
        ])->map(function ($data) use ($colaboradorRole) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'nombre_completo' => $data['name'],
                    'password' => Hash::make('secret'),
                    'estado_verificacion' => true,
                    'indice_confianza' => 70,
                ]
            );
            $user->roles()->syncWithoutDetaching([$colaboradorRole->id]);
            return $user;
        });

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

        $proyectosInsertados = collect();

        foreach ($projects as $project) {
            $id = DB::table('proyectos')->updateOrInsert(
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

            $proyectosInsertados->push(
                Proyecto::where('titulo', $project['titulo'])->first()
            );
        }

        // Aportaciones demo para que el panel tenga datos
        foreach ($proyectosInsertados as $proyecto) {
            $muestras = [
                ['user' => $colaboradores[0], 'monto' => 120.50],
                ['user' => $colaboradores[1], 'monto' => 250.00],
                ['user' => $colaboradores[2], 'monto' => 75.25],
                ['user' => $colaboradores[1], 'monto' => 420.00],
                ['user' => $colaboradores[0], 'monto' => 180.00],
            ];

            foreach ($muestras as $m) {
                Aportacion::create([
                    'colaborador_id' => $m['user']->id,
                    'proyecto_id' => $proyecto->id,
                    'monto' => $m['monto'],
                    'fecha_aportacion' => Carbon::now()->subDays(rand(1, 20)),
                    'estado_pago' => 'pagado',
                ]);
            }
        }
    }
}
