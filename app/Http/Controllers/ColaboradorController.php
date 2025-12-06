<?php

namespace App\Http\Controllers;

use App\Models\Aportacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ColaboradorController extends Controller
{
    /**
     * Panel principal del colaborador.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Métricas simples de ejemplo
        $totalAportado      = Aportacion::where('colaborador_id', $user->id)->sum('monto');
        $proyectosApoyados  = Aportacion::where('colaborador_id', $user->id)
                                ->distinct('proyecto_id')
                                ->count('proyecto_id');
        $totalAportaciones  = Aportacion::where('colaborador_id', $user->id)->count();

        return view('colaborador.dashboard', compact(
            'user',
            'totalAportado',
            'proyectosApoyados',
            'totalAportaciones'
        ));
    }

    /**
     * Proyectos que está apoyando este colaborador.
     */
    public function proyectos(): View
    {
        $user = Auth::user();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $user->id)
            ->get();

        return view('colaborador.proyectos', compact('aportaciones'));
    }

    /**
     * Historial de aportaciones.
     */
    public function aportaciones(): View
    {
        $user = Auth::user();

        $aportaciones = Aportacion::with('proyecto')
            ->where('colaborador_id', $user->id)
            ->latest()
            ->get();

        return view('colaborador.aportaciones', compact('aportaciones'));
    }

    /**
     * Reportes / resumen financiero personal.
     */
    public function reportes(): View
    {
        $user = Auth::user();

        $totalAportado      = Aportacion::where('colaborador_id', $user->id)->sum('monto');
        $totalAportaciones  = Aportacion::where('colaborador_id', $user->id)->count();

        return view('colaborador.reportes', compact(
            'user',
            'totalAportado',
            'totalAportaciones'
        ));
    }
}
