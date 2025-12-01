<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboradroController;
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
    ->name('admin.proyectos.show');

Route::get('/admin/auditorias', [\App\Http\Controllers\AdminController::class, 'auditorias'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.auditorias');

Route::get('/admin/finanzas', [\App\Http\Controllers\AdminController::class, 'finanzas'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.finanzas');

Route::get('/admin/proveedores', [\App\Http\Controllers\AdminController::class, 'proveedores'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.proveedores');

Route::get('/admin/reportes', [\App\Http\Controllers\AdminController::class, 'reportes'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.reportes');

// Panel de AUDITOR
Route::get('/auditor', [\App\Http\Controllers\AuditorController::class, 'index'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.dashboard');

// Panel de CREADOR
Route::get('/creator', [\App\Http\Controllers\CreatorController::class, 'index'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.dashboard');

Route::get('/creator/proyectos', [\App\Http\Controllers\CreatorController::class, 'proyectos'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proyectos');

Route::get('/creator/recompensas', [\App\Http\Controllers\CreatorController::class, 'recompensas'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.recompensas');

Route::get('/creator/avances', [\App\Http\Controllers\CreatorController::class, 'avances'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.avances');

Route::get('/creator/fondos', [\App\Http\Controllers\CreatorController::class, 'fondos'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.fondos');

Route::get('/creator/proveedores', [\App\Http\Controllers\CreatorController::class, 'proveedores'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.proveedores');

Route::get('/creator/perfil', [\App\Http\Controllers\CreatorController::class, 'perfil'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.perfil');

Route::get('/creator/reportes', [\App\Http\Controllers\CreatorController::class, 'reportes'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.reportes');

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

Route::patch('/creator/perfil', [\App\Http\Controllers\CreatorController::class, 'updatePerfil'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.perfil.update');

// Panel de COLABORADOR
Route::get('/colaborador', [\App\Http\Controllers\ColaboradorController::class, 'index'])
    ->middleware(['auth','role:COLABORADOR'])
    ->name('colaborador.dashboard');

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
