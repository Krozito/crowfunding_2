<?php

namespace Tests\Feature;

use App\Models\Aportacion;
use App\Models\Proyecto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModulesTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        $adminRole = Role::create(['nombre_rol' => 'ADMIN']);
        $admin = User::factory()->create([
            'nombre_completo' => 'Admin Test',
            'estado_verificacion' => true,
        ]);
        $admin->roles()->attach($adminRole->id);

        return $admin;
    }

    private function makeProject(User $creator, string $title = 'Proyecto Demo'): Proyecto
    {
        return Proyecto::create([
            'creador_id' => $creator->id,
            'titulo' => $title,
            'descripcion_proyecto' => 'Descripcion corta',
            'meta_financiacion' => 10000,
            'monto_recaudado' => 0,
            'estado' => 'publicado',
            'modelo_financiamiento' => 'todo-o-nada',
            'categoria' => 'Impacto',
            'ubicacion_geografica' => 'Ciudad Demo',
        ]);
    }

    public function test_admin_can_see_projects_list(): void
    {
        $admin = $this->makeAdmin();
        $creator = User::factory()->create();
        $this->makeProject($creator, 'Proyecto Alfa');
        $this->makeProject($creator, 'Proyecto Beta');

        $response = $this->actingAs($admin)->get(route('admin.proyectos'));

        $response->assertOk()
            ->assertSee('Proyecto Alfa')
            ->assertSee('Proyecto Beta')
            ->assertSee('Vision general');
    }

    public function test_admin_can_view_project_detail_with_stats(): void
    {
        $admin = $this->makeAdmin();
        $creator = User::factory()->create();
        $colab = User::factory()->create(['nombre_completo' => 'Colaborador Uno']);

        $proyecto = $this->makeProject($creator, 'Proyecto Fondo');

        Aportacion::create([
            'colaborador_id' => $colab->id,
            'proyecto_id' => $proyecto->id,
            'monto' => 150.50,
            'estado_pago' => 'pagado',
        ]);
        Aportacion::create([
            'colaborador_id' => $colab->id,
            'proyecto_id' => $proyecto->id,
            'monto' => 200,
            'estado_pago' => 'pagado',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.proyectos.show', $proyecto));

        $response->assertOk()
            ->assertSee('Proyecto Fondo')
            ->assertSee('Colaborador Uno')
            ->assertSee('US$ 350.50'); // total recaudado en la vista
    }

    public function test_admin_can_view_user_profile_with_contributions_and_projects(): void
    {
        $admin = $this->makeAdmin();
        $creator = User::factory()->create([
            'nombre_completo' => 'Creador Perfil',
            'estado_verificacion' => true,
        ]);

        // Proyectos creados por el usuario
        $proyectoPropio = $this->makeProject($creator, 'Proyecto Propio');
        $proyectoApoyado = $this->makeProject($creator, 'Proyecto Apoyado');

        // Aporta a otro proyecto
        $otroProyecto = $this->makeProject(User::factory()->create(), 'Proyecto Externo');
        Aportacion::create([
            'colaborador_id' => $creator->id,
            'proyecto_id' => $otroProyecto->id,
            'monto' => 99.99,
            'estado_pago' => 'pagado',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.show', $creator));

        $response->assertOk()
            ->assertSee('Creador Perfil')
            ->assertSee('Proyecto Propio')
            ->assertSee('Proyecto Externo')
            ->assertSee('US$ 99.99');
    }
}
