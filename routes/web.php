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

Route::patch('/admin/users/{user}/roles', [\App\Http\Controllers\AdminController::class, 'updateUserRoles'])
    ->middleware(['auth','role:ADMIN'])
    ->name('admin.users.roles');

// Panel de AUDITOR
Route::get('/auditor', [\App\Http\Controllers\AuditorController::class, 'index'])
    ->middleware(['auth','role:AUDITOR'])
    ->name('auditor.dashboard');

// Panel de CREADOR
Route::get('/creator', [\App\Http\Controllers\CreatorController::class, 'index'])
    ->middleware(['auth','role:CREADOR'])
    ->name('creador.dashboard');

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
