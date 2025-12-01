<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminSetupSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles base
        $adminRole = Role::firstOrCreate(['nombre_rol' => 'ADMIN']);
        $auditorRole = Role::firstOrCreate(['nombre_rol' => 'AUDITOR']);
        $creadorRole = Role::firstOrCreate(['nombre_rol' => 'CREADOR']);
        $colabRole = Role::firstOrCreate(['nombre_rol' => 'COLABORADOR']);

        // Usuario admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@app.test'],
            [
                'name' => 'Administrador',
                'nombre_completo' => 'Administrador',
                'password' => Hash::make('secret'), // cambialo en prod
                'estado_verificacion' => true,
                'indice_confianza' => 100,
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Usuarios demo por rol
        $this->createUserWithRole('auditor@app.test', 'Auditor Demo', $auditorRole);
        $this->createUserWithRole('creador@app.test', 'Creador Demo', $creadorRole);
        $this->createUserWithRole('colaborador@app.test', 'Colaborador Demo', $colabRole);
    }

    private function createUserWithRole(string $email, string $name, Role $role): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'nombre_completo' => $name,
                'password' => Hash::make('secret'),
                'estado_verificacion' => true,
                'indice_confianza' => 80,
            ]
        );
        $user->roles()->syncWithoutDetaching([$role->id]);
    }
}
