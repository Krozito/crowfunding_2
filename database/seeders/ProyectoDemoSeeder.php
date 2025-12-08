<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Proyecto;
use App\Models\Recompensa;
use App\Models\ActualizacionProyecto;
use App\Models\Aportacion;
use App\Models\ProyectoCategoria;
use Illuminate\Support\Facades\Hash;

class ProyectoDemoSeeder extends Seeder
{
    public function run(): void
    {
        $creador = $this->seedCreador();
        $colaborador = $this->seedColaborador();

        $categoria = ProyectoCategoria::firstOrCreate(['nombre' => 'Tecnologia']);

        $proyecto = Proyecto::firstOrCreate(
            ['titulo' => 'Proyecto Demo'],
            [
                'creador_id' => $creador->id,
                'descripcion_proyecto' => 'Proyecto de ejemplo para pruebas y demos.',
                'meta_financiacion' => 5000,
                'monto_recaudado' => 0,
                'fecha_limite' => now()->addMonth(),
                'estado' => 'publicado',
                'modelo_financiamiento' => 'donaciones',
                'categoria' => $categoria->nombre,
                'ubicacion_geografica' => 'Ciudad Demo',
            ]
        );

        $this->seedRecompensas($proyecto);
        $this->seedAvances($proyecto);
        $this->seedAportaciones($colaborador, $proyecto);
    }

    private function seedCreador(): User
    {
        $role = Role::firstOrCreate(['nombre_rol' => 'CREADOR']);

        $user = User::firstOrCreate(
            ['email' => 'creador@app.test'],
            [
                'name' => 'Creador Demo',
                'nombre_completo' => 'Creador Demo',
                'password' => Hash::make('secret'),
                'estado_verificacion' => true,
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);

        return $user;
    }

    private function seedColaborador(): User
    {
        $role = Role::firstOrCreate(['nombre_rol' => 'COLABORADOR']);

        $user = User::firstOrCreate(
            ['email' => 'colaborador@app.test'],
            [
                'name' => 'Colaborador Demo',
                'nombre_completo' => 'Colaborador Demo',
                'password' => Hash::make('secret'),
                'estado_verificacion' => true,
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);

        return $user;
    }

    private function seedRecompensas(Proyecto $proyecto): void
    {
        Recompensa::firstOrCreate([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Agradecimiento',
        ], [
            'descripcion' => 'Menci\u00f3n en la p\u00e1gina del proyecto.',
            'monto_minimo_aportacion' => 10,
            'disponibilidad' => null,
        ]);

        Recompensa::firstOrCreate([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Pack Early Adopter',
        ], [
            'descripcion' => 'Acceso anticipado y kit digital.',
            'monto_minimo_aportacion' => 50,
            'disponibilidad' => 100,
        ]);
    }

    private function seedAvances(Proyecto $proyecto): void
    {
        ActualizacionProyecto::firstOrCreate([
            'proyecto_id' => $proyecto->id,
            'titulo' => 'Lanzamiento de la campa\u00f1a',
        ], [
            'contenido' => 'Gracias por apoyar, aqu\u00ed iremos compartiendo avances.',
            'fecha_publicacion' => now(),
            'es_hito' => true,
            'adjuntos' => [],
        ]);
    }

    private function seedAportaciones(User $colaborador, Proyecto $proyecto): void
    {
        Aportacion::firstOrCreate([
            'colaborador_id' => $colaborador->id,
            'proyecto_id' => $proyecto->id,
            'id_transaccion_pago' => 'demo-' . uniqid(),
        ], [
            'monto' => 100,
            'fecha_aportacion' => now(),
            'estado_pago' => 'pagado',
        ]);

        $proyecto->update(['monto_recaudado' => $proyecto->monto_recaudado + 100]);
    }
}
