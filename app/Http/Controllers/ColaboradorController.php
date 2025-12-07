<?php

namespace App\Http\Controllers;

use App\Models\Aportacion;
use App\Models\Proyecto;
use App\Models\ProyectoCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ColaboradorController extends Controller
{
    /**
     * Dashboard principal del colaborador
     * - Muestra métricas personales
     * - Muestra TODOS los proyectos para explorar y apoyar
     */
    public function index(Request $request): View
    {
        $colaboradorId = Auth::id();

        $search = $request->query('q');
        $categoria = $request->query('categoria');

        // Aportaciones del colaborador (para métricas)
        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        // Métricas personales
        $metrics = [
            'totalAportado'   => $aportaciones->sum('monto'),
            'numProyectos'    => $aportaciones->groupBy('proyecto_id')->count(),
            'numAportaciones' => $aportaciones->count(),
        ];

        // TODOS los proyectos para explorar (no solo los que ha apoyado)
        // Se cargan también las aportaciones del usuario actual para saber si ya ha apoyado o no (opcional)
        $proyectosExplorar = Proyecto::with([
                'creador',
                'aportaciones' => function ($q) use ($colaboradorId) {
                    $q->where('colaborador_id', $colaboradorId);
                },
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('titulo', 'like', "%{$search}%")
                        ->orWhere('descripcion_proyecto', 'like', "%{$search}%")
                        ->orWhere('categoria', 'like', "%{$search}%")
                        ->orWhere('ubicacion_geografica', 'like', "%{$search}%");
                });
            })
            ->when($categoria, fn($q) => $q->where('categoria', $categoria))
            ->orderByDesc('created_at')
            ->paginate(9) // puedes cambiar el 9 por lo que quieras
            ->appends($request->query());

        $categorias = ProyectoCategoria::orderBy('nombre')->pluck('nombre');

        return view('colaborador.dashboard', compact(
            'metrics',
            'proyectosExplorar',
            'search',
            'categoria',
            'categorias'
        ));
    }

    /**
     * Lista de proyectos apoyados (SOLO los que ha apoyado)
     */
    public function proyectos(): View
    {
        $colaboradorId = Auth::id();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        $proyectosAportados = $aportaciones
            ->pluck('proyecto')
            ->filter()
            ->unique('id')
            ->values();

        return view('colaborador.proyectos', compact('proyectosAportados'));
    }

    /**
     * Historial de aportaciones
     */
    public function aportaciones(): View
    {
        $colaboradorId = Auth::id();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->orderByDesc('fecha_aportacion')
            ->get();

        return view('colaborador.aportaciones', compact('aportaciones'));
    }

    /**
     * Reportes / resumen
     */
    public function reportes(): View
    {
        $colaboradorId = Auth::id();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->get();

        $totalAportado   = $aportaciones->sum('monto');
        $numProyectos    = $aportaciones->groupBy('proyecto_id')->count();
        $numAportaciones = $aportaciones->count();

        return view('colaborador.reportes', compact(
            'aportaciones',
            'totalAportado',
            'numProyectos',
            'numAportaciones'
        ));
    }

    /**
     * Detalle de un proyecto para el colaborador
     * - Puede ver cualquier proyecto, haya aportado o no
     */
    public function showProyecto(Proyecto $proyecto): View
    {
        $colaboradorId = Auth::id();

        // Cuánto ha aportado este colaborador a este proyecto (puede ser 0 si no ha aportado aún)
        $aporteUsuario = Aportacion::where('colaborador_id', $colaboradorId)
            ->where('proyecto_id', $proyecto->id)
            ->sum('monto');

        // Cargamos relaciones útiles
        $proyecto->load([
            'creador',
            'hitos',        // actualizaciones
            'recompensas',  // recompensas del proyecto
        ]);

        return view('colaborador.proyectos-show', compact('proyecto', 'aporteUsuario'));
    }
}
