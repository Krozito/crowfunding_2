<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->get();
        $roles = Role::orderBy('nombre_rol')->get();

        $roleStats = Role::withCount('users')->orderBy('nombre_rol')->get();
        $totalUsers = $users->count();
        $verifiedUsers = $users->where('estado_verificacion', true)->count();

        return view('admin.dashboard', compact(
            'users',
            'roles',
            'roleStats',
            'totalUsers',
            'verifiedUsers'
        ));
    }

    public function updateUserRoles(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', "Roles del usuario {$user->name} actualizados.");
    }
}
