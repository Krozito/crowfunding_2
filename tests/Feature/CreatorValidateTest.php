<?php

use App\Models\Proyecto;
use App\Models\User;

test('verified creator can create project draft', function () {
    test()->withoutMiddleware();

    $user = User::factory()->create([
        'estado_verificacion' => true,
    ]);

    $this->actingAs($user);

    $response = $this->post(route('creador.proyectos.store'), [
        'titulo' => 'Proyecto Demo',
        'descripcion_proyecto' => 'Descripción de prueba',
        'meta_financiacion' => 10000,
        'modelo_financiamiento_id' => null,
        'categoria_id' => null,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Proyecto creado en borrador.');

    $this->assertDatabaseHas('proyectos', [
        'titulo' => 'Proyecto Demo',
        'creador_id' => $user->id,
        'estado' => 'borrador',
    ]);
});

test('unverified creator cannot create project', function () {
    test()->withoutMiddleware();

    $user = User::factory()->create([
        'estado_verificacion' => false,
    ]);

    $this->actingAs($user);

    $response = $this->post(route('creador.proyectos.store'), [
        'titulo' => 'Proyecto No Permitido',
        'meta_financiacion' => 1000,
    ]);

    $response->assertStatus(403);
    $this->assertDatabaseMissing('proyectos', [
        'titulo' => 'Proyecto No Permitido',
    ]);
});
