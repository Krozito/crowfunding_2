<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboradorController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

// Panel de ADMIN
Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'index'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.dashboard');

Route::get('/admin/roles', [\App\Http\Controllers\AdminController::class, 'roles'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.roles');

Route::get('/admin/users/{user}', [\App\Http\Controllers\AdminController::class, 'showUser'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.users.show');

Route::patch('/admin/users/{user}/roles', [\App\Http\Controllers\AdminController::class, 'updateUserRoles'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.users.roles');

Route::get('/admin/proyectos', [\App\Http\Controllers\AdminController::class, 'proyectos'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proyectos');

Route::get('/admin/proyectos/{proyecto}', [\App\Http\Controllers\AdminController::class, 'showProyecto'])
    ->middleware(['auth','role:ADMIN'])
    ->whereNumber('proyecto')
    ->name('admin.proyectos.show');
Route::get('/admin/proyectos/{proyecto}/gastos', [\App\Http\Controllers\AdminController::class, 'proyectoGastos'])
    ->middleware(['auth','role:ADMIN'])
    ->whereNumber('proyecto')
    ->name('admin.proyectos.gastos');

Route::get('/admin/proyectos/config', [\App\Http\Controllers\AdminController::class, 'proyectosConfig'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proyectos.config');
Route::post('/admin/proyectos/categorias', [\App\Http\Controllers\AdminController::class, 'storeCategoriaProyecto'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proyectos.categorias.store');
Route::delete('/admin/proyectos/categorias/{categoria}', [\App\Http\Controllers\AdminController::class, 'deleteCategoriaProyecto'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proyectos.categorias.destroy');
Route::post('/admin/proyectos/modelos', [\App\Http\Controllers\AdminController::class, 'storeModeloFinanciamiento'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proyectos.modelos.store');
Route::delete('/admin/proyectos/modelos/{modelo}', [\App\Http\Controllers\AdminController::class, 'deleteModeloFinanciamiento'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proyectos.modelos.destroy');

Route::get('/admin/auditorias', [\App\Http\Controllers\AdminController::class, 'auditorias'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.auditorias');

Route::get('/admin/finanzas', [\App\Http\Controllers\AdminController::class, 'finanzas'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas');
Route::get('/admin/finanzas/proyectos', [\App\Http\Controllers\AdminController::class, 'finanzasProyectos'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.proyectos');
Route::get('/admin/finanzas/solicitudes', [\App\Http\Controllers\AdminController::class, 'finanzasSolicitudes'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.solicitudes');
Route::patch('/admin/finanzas/solicitudes/{solicitud}', [\App\Http\Controllers\AdminController::class, 'updateSolicitudFondos'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.solicitudes.update');
Route::get('/admin/finanzas/export/retenidos', [\App\Http\Controllers\AdminController::class, 'exportFondosRetenidos'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.export.retenidos');
Route::get('/admin/finanzas/export/liberados', [\App\Http\Controllers\AdminController::class, 'exportFondosLiberados'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.export.liberados');
Route::get('/admin/finanzas/export/recaudacion-mensual', [\App\Http\Controllers\AdminController::class, 'exportRecaudacionMensual'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.export.recaudacion.mensual');
Route::get('/admin/finanzas/export/recaudacion-categoria', [\App\Http\Controllers\AdminController::class, 'exportRecaudacionCategoria'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas.export.recaudacion.categoria');

Route::get('/admin/proveedores', [\App\Http\Controllers\AdminController::class, 'proveedores'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proveedores');

Route::get('/admin/reportes', [\App\Http\Controllers\AdminController::class, 'reportes'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.reportes');
Route::get('/admin/verificaciones', [\App\Http\Controllers\AdminController::class, 'verificaciones'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.verificaciones');
Route::patch('/admin/verificaciones/{solicitud}', [\App\Http\Controllers\AdminController::class, 'updateVerificacion'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.verificaciones.update');
Route::get('/admin/verificaciones/{solicitud}/archivo/{tipo}', [\App\Http\Controllers\AdminController::class, 'verificacionAdjunto'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.verificaciones.adjunto');

// Panel de AUDITOR
Route::get('/auditor', [\App\Http\Controllers\AuditorController::class, 'index'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.dashboard');
Route::get('/auditor/comprobantes', [\App\Http\Controllers\AuditorController::class, 'comprobantes'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.comprobantes');
Route::get('/auditor/desembolsos', [\App\Http\Controllers\AuditorController::class, 'desembolsos'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.desembolsos');
Route::get('/auditor/reportes', [\App\Http\Controllers\AuditorController::class, 'reportes'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.reportes');
Route::get('/auditor/hitos', [\App\Http\Controllers\AuditorController::class, 'hitos'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.hitos');
Route::get('/auditor/proyectos', [\App\Http\Controllers\AuditorController::class, 'proyectos'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.proyectos');

// Panel de CREADOR
Route::get('/creator', [\App\Http\Controllers\CreatorController::class, 'index'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.dashboard');

Route::get('/creator/proyectos', [\App\Http\Controllers\CreatorController::class, 'proyectos'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos');
Route::get('/creator/proyectos/crear', [\App\Http\Controllers\CreatorController::class, 'proyectosCrear'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.create');
Route::get('/creator/proyectos/{proyecto}/editar', [\App\Http\Controllers\CreatorController::class, 'proyectosEditar'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.edit');

Route::get('/creator/recompensas', [\App\Http\Controllers\CreatorController::class, 'recompensas'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas');
Route::get('/creator/recompensas/crear', [\App\Http\Controllers\CreatorController::class, 'recompensasCrear'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.create');
Route::get('/creator/recompensas/gestion', [\App\Http\Controllers\CreatorController::class, 'recompensasGestionar'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.gestion');
Route::get('/creator/recompensas/preview', [\App\Http\Controllers\CreatorController::class, 'recompensasPreview'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.preview');
Route::post('/creator/recompensas', [\App\Http\Controllers\CreatorController::class, 'storeRecompensa'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.store');
Route::get('/creator/recompensas/{recompensa}/editar', [\App\Http\Controllers\CreatorController::class, 'recompensasEditar'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.edit');
Route::patch('/creator/recompensas/{recompensa}', [\App\Http\Controllers\CreatorController::class, 'updateRecompensa'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.update');
Route::patch('/creator/recompensas/{recompensa}/estado', [\App\Http\Controllers\CreatorController::class, 'toggleRecompensaEstado'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.estado');
Route::delete('/creator/recompensas/{recompensa}', [\App\Http\Controllers\CreatorController::class, 'eliminarRecompensa'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas.destroy');

Route::get('/creator/avances', [\App\Http\Controllers\CreatorController::class, 'avances'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.avances');
Route::patch('/creator/proyectos/{proyecto}/avances/{actualizacion}', [\App\Http\Controllers\CreatorController::class, 'updateAvance'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.avances.update');
Route::delete('/creator/proyectos/{proyecto}/avances/{actualizacion}', [\App\Http\Controllers\CreatorController::class, 'deleteAvance'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.avances.delete');

Route::get('/creator/fondos', [\App\Http\Controllers\CreatorController::class, 'fondos'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.fondos');
Route::get('/creator/fondos/historial', [\App\Http\Controllers\CreatorController::class, 'fondosHistorial'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.fondos.historial');
Route::post('/creator/proyectos/{proyecto}/fondos/solicitudes', [\App\Http\Controllers\CreatorController::class, 'storeSolicitudDesembolso'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.fondos.solicitudes.store');

Route::get('/creator/proveedores', [\App\Http\Controllers\CreatorController::class, 'proveedores'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores');

Route::get('/creator/proveedores/crear', [\App\Http\Controllers\CreatorController::class, 'crearProveedor'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.create');

Route::get('/creator/proveedores/{proveedor}/editar', [\App\Http\Controllers\CreatorController::class, 'editarProveedor'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.edit');

Route::get('/creator/proveedores/{proveedor}', [\App\Http\Controllers\CreatorController::class, 'showProveedor'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.show');

Route::get('/creator/perfil', [\App\Http\Controllers\CreatorController::class, 'perfil'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.perfil');
Route::get('/creator/perfil/verificacion', [\App\Http\Controllers\CreatorController::class, 'verificacion'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.perfil.verificacion.form');

Route::get('/creator/reportes', [\App\Http\Controllers\CreatorController::class, 'reportes'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.reportes');
Route::post('/creator/proyectos/{proyecto}/reportes/pagos', [\App\Http\Controllers\CreatorController::class, 'storePago'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.reportes.pagos.store');

// Acciones de creador (simples)
Route::post('/creator/proyectos', [\App\Http\Controllers\CreatorController::class, 'storeProyecto'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.store');

Route::patch('/creator/proyectos/{proyecto}', [\App\Http\Controllers\CreatorController::class, 'updateProyecto'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.update');

Route::post('/creator/proyectos/{proyecto}/avances', [\App\Http\Controllers\CreatorController::class, 'agregarAvance'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos.avances');

Route::post('/creator/proveedores', [\App\Http\Controllers\CreatorController::class, 'storeProveedor'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.store');

Route::patch('/creator/proveedores/{proveedor}', [\App\Http\Controllers\CreatorController::class, 'updateProveedor'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.update');

Route::delete('/creator/proveedores/{proveedor}', [\App\Http\Controllers\CreatorController::class, 'deleteProveedor'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.destroy');

Route::post('/creator/proveedores/{proveedor}/historial', [\App\Http\Controllers\CreatorController::class, 'storeProveedorHistorial'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores.historial.store');

Route::patch('/creator/perfil', [\App\Http\Controllers\CreatorController::class, 'updatePerfil'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.perfil.update');
Route::post('/creator/perfil/verificacion', [\App\Http\Controllers\CreatorController::class, 'solicitarVerificacion'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.perfil.verificacion');

// Panel de COLABORADOR
Route::middleware(['auth', 'role:COLABORADOR'])->group(function () {

    Route::get('/colaborador', [ColaboradorController::class, 'index'])
        ->name('colaborador.dashboard');

    Route::get('/colaborador/proyectos', [ColaboradorController::class, 'proyectos'])
        ->name('colaborador.proyectos');

    Route::get('/colaborador/aportaciones', [ColaboradorController::class, 'aportaciones'])
        ->name('colaborador.aportaciones');

    Route::get('/colaborador/reportes', [ColaboradorController::class, 'reportes'])
        ->name('colaborador.reportes');
});


// Dashboard general (fallback)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
