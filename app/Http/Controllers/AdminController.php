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

    public function roles(Request $request): View
    {
        $search = $request->query('q');
        $roleFilter = $request->query('role');

        $usersQuery = User::with('roles')->orderBy('name');

        if ($search) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nombre_completo', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $usersQuery->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('id', $roleFilter);
            });
        }

        $users = $usersQuery->paginate(12)->withQueryString();
        $roles = Role::orderBy('nombre_rol')->get();

        return view('admin.modules.roles', [
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'roleFilter' => $roleFilter,
        ]);
    }

    public function showUser(User $user): View
    {
        $user->load(['roles', 'proyectosCreados' => function ($q) {
            $q->orderByDesc('created_at');
        }]);

        $aportaciones = $user->aportaciones()->with('proyecto')->orderByDesc('fecha_aportacion')->get();

        $stats = [
            'total_aportado' => $aportaciones->sum('monto'),
            'aportaciones' => $aportaciones->count(),
            'proyectos_apoyados' => $aportaciones->pluck('proyecto_id')->unique()->count(),
        ];

        $topProyectos = $aportaciones
            ->groupBy('proyecto_id')
            ->map(function ($group) {
                return [
                    'proyecto' => $group->first()->proyecto,
                    'total' => $group->sum('monto'),
                    'aportes' => $group->count(),
                ];
            })
            ->sortByDesc('total')
            ->take(5);

        $calificacion = DB::table('calificaciones')
            ->where('colaborador_id', $user->id)
            ->avg('puntaje');

        return view('admin.modules.usuarios-show', [
            'user' => $user,
            'aportaciones' => $aportaciones,
            'stats' => $stats,
            'topProyectos' => $topProyectos,
            'calificacion' => $calificacion,
        ]);
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
