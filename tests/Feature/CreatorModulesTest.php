<?php

namespace Tests\Feature;

use App\Models\ActualizacionProyecto;
use App\Models\Proyecto;
use App\Models\Proveedor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\EnsureRole;
use Tests\TestCase;

class CreatorModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Simplifica las pruebas de formularios evitando CSRF/roles
        $this->withoutMiddleware();
    }

    private function makeCreator(): User
    {
        $role = Role::create(['nombre_rol' => 'CREADOR']);
        $user = User::factory()->create([
            'nombre_completo' => 'Creador Demo',
            'estado_verificacion' => false,
        ]);
        $user->roles()->attach($role->id);
        return $user;
    }

    public function test_creator_can_create_project_with_goal(): void
    {
        $creator = $this->makeCreator();

        $response = $this->actingAs($creator)->post(route('creador.proyectos.store'), [
            'titulo' => 'Proyecto Meta',
            'descripcion_proyecto' => 'Desc',
            'meta_financiacion' => 5000,
            'modelo_financiamiento' => 'todo-o-nada',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('proyectos', [
            'titulo' => 'Proyecto Meta',
            'creador_id' => $creator->id,
            'meta_financiacion' => 5000,
            'estado' => 'borrador',
        ]);
    }

    public function test_creator_can_publish_update_for_own_project(): void
    {
        $creator = $this->makeCreator();
        $project = Proyecto::create([
            'creador_id' => $creator->id,
            'titulo' => 'Proyecto Avance',
            'meta_financiacion' => 1000,
            'monto_recaudado' => 0,
            'estado' => 'publicado',
        ]);

        $response = $this->actingAs($creator)->post(route('creador.proyectos.avances', ['proyecto' => $project->id]), [
            'titulo' => 'Avance 1',
            'contenido' => 'Detalle del avance',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('actualizaciones_proyecto', [
            'proyecto_id' => $project->id,
            'titulo' => 'Avance 1',
        ]);
    }

    public function test_creator_can_link_supplier_to_project(): void
    {
        $creator = $this->makeCreator();
        $project = Proyecto::create([
            'creador_id' => $creator->id,
            'titulo' => 'Proyecto Proveedor',
            'meta_financiacion' => 2000,
            'monto_recaudado' => 0,
            'estado' => 'publicado',
        ]);

        $response = $this->actingAs($creator)->post(route('creador.proveedores.store'), [
            'nombre_proveedor' => 'Proveedor XYZ',
            'proyecto_id' => $project->id,
            'info_contacto' => 'contacto@test.com',
            'especialidad' => 'Logistica',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('proveedores', [
            'nombre_proveedor' => 'Proveedor XYZ',
            'proyecto_id' => $project->id,
            'creador_id' => $creator->id,
        ]);
    }

    public function test_creator_can_update_profile_and_verify(): void
    {
        $creator = $this->makeCreator();

        $response = $this->actingAs($creator)->patch(route('creador.perfil.update'), [
            'info_personal' => 'Bio corta',
            'redes_sociales' => ['twitter' => '@demo'],
            'estado_verificacion' => true,
        ]);

        $response->assertRedirect();
        $creator->refresh();

        $this->assertEquals('Bio corta', $creator->info_personal);
        $this->assertEquals('@demo', $creator->redes_sociales['twitter']);
        $this->assertTrue((bool) $creator->estado_verificacion);
    }
}
