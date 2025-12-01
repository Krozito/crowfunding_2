<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Proyecto;
use App\Models\Aportacion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $verifiedUsers = User::where('estado_verificacion', true)->count();
        $roleStats = Role::withCount('users')->orderBy('nombre_rol')->get();

        return view('admin.dashboard', [
            'totalUsers'    => $totalUsers,
            'verifiedUsers' => $verifiedUsers,
            'roleStats'     => $roleStats,
        ]);
    }

    public function roles(): View
    {
        $users = User::with('roles')->orderBy('name')->get();
        $roles = Role::orderBy('nombre_rol')->get();

        return view('admin.modules.roles', compact('users', 'roles'));
    }

    public function updateUserRoles(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'roles'   => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()
            ->route('admin.roles')
            ->with('status', "Roles del usuario {$user->name} actualizados.");
    }

    public function proyectos(): View
    {
        $proyectos = Proyecto::orderByDesc('created_at')->paginate(10);

        return view('admin.modules.proyectos', compact('proyectos'));
    }

    public function showProyecto(Proyecto $proyecto): View
    {
        $proyecto->load('creador');

        $aporteQuery = Aportacion::where('proyecto_id', $proyecto->id);

        $topInversionistas = (clone $aporteQuery)
            ->select('colaborador_id', DB::raw('SUM(monto) as total'), DB::raw('COUNT(*) as aportes'))
            ->whereNotNull('colaborador_id')
            ->groupBy('colaborador_id')
            ->orderByDesc('total')
            ->with('colaborador')
            ->take(5)
            ->get();

        $aportacionesRecientes = (clone $aporteQuery)
            ->with('colaborador')
            ->orderByDesc('fecha_aportacion')
            ->take(8)
            ->get();

        $stats = [
            'total_recaudado' => (clone $aporteQuery)->sum('monto'),
            'aportaciones' => (clone $aporteQuery)->count(),
            'colaboradores' => (clone $aporteQuery)->distinct('colaborador_id')->count('colaborador_id'),
        ];

        return view('admin.modules.proyectos-show', [
            'proyecto' => $proyecto,
            'topInversionistas' => $topInversionistas,
            'aportacionesRecientes' => $aportacionesRecientes,
            'stats' => $stats,
        ]);
    }

    public function auditorias(): View
    {
        return view('admin.modules.auditorias');
    }

    public function finanzas(): View
    {
        return view('admin.modules.finanzas');
    }

    public function proveedores(): View
    {
        return view('admin.modules.proveedores');
    }

    public function reportes(): View
    {
        return view('admin.modules.reportes');
    }
}
