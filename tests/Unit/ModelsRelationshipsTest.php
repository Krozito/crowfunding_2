<?php

use App\Models\User;
use App\Models\Role;
use App\Models\Proyecto;
use App\Models\Aportacion;
use App\Models\Proveedor;
use App\Models\Recompensa;
use App\Models\ActualizacionProyecto;

test('user roles relationship works', function () {
    $user = User::factory()->create();
    $role = Role::create(['nombre_rol' => 'ADMIN']);

    $user->roles()->attach($role);
    $user->refresh();

    expect($user->roles)->toHaveCount(1);
    expect($user->roles->first()->nombre_rol)->toBe('ADMIN');
});

test('project relationships with creator, contributions, providers, rewards and milestones work', function () {
    $creator = User::factory()->create();

    $proyecto = Proyecto::create([
        'creador_id' => $creator->id,
        'titulo' => 'Proyecto relaciones',
        'meta_financiacion' => 1000,
        'monto_recaudado' => 0,
        'estado' => 'borrador',
    ]);

    $aportante = User::factory()->create();
    Aportacion::create([
        'colaborador_id' => $aportante->id,
        'proyecto_id' => $proyecto->id,
        'monto' => 80,
        'fecha_aportacion' => now(),
        'estado_pago' => 'pagado',
        'id_transaccion_pago' => 'TX-REL-1',
    ]);

    Proveedor::create([
        'creador_id' => $creator->id,
        'proyecto_id' => $proyecto->id,
        'nombre_proveedor' => 'Proveedor Relacional',
        'info_contacto' => null,
        'especialidad' => null,
    ]);

    Recompensa::create([
        'proyecto_id' => $proyecto->id,
        'titulo' => 'Nivel básico',
        'descripcion' => 'Recompensa inicial',
        'monto_minimo_aportacion' => 10,
        'disponibilidad' => null,
    ]);

    ActualizacionProyecto::create([
        'proyecto_id' => $proyecto->id,
        'titulo' => 'Hito inicial',
        'contenido' => 'Descripción del hito',
        'fecha_publicacion' => now(),
        'es_hito' => true,
        'adjuntos' => [],
    ]);

    $proyecto->load('creador', 'aportaciones', 'proveedores', 'recompensas', 'hitos');

    expect($proyecto->creador->id)->toBe($creator->id);
    expect($proyecto->aportaciones)->toHaveCount(1);
    expect($proyecto->proveedores)->toHaveCount(1);
    expect($proyecto->recompensas)->toHaveCount(1);
    expect($proyecto->hitos->first()->es_hito)->toBeTrue();
});
