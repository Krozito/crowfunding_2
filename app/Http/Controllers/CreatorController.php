<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CreatorController extends Controller
{
    public function index(): View
    {
        // Métricas de ejemplo (sustituir por consultas reales cuando existan modelos)
        $metrics = [
            'proyectos'      => 0,
            'montoRecaudado' => 0,
            'colaboradores'  => 0,
            'avance'         => '—',
        ];

        return view('creator.dashboard', compact('metrics'));
    }

    public function proyectos(): View
    {
        return view('creator.modules.proyectos');
    }

    public function recompensas(): View
    {
        return view('creator.modules.recompensas');
    }

    public function avances(): View
    {
        return view('creator.modules.avances');
    }

    public function fondos(): View
    {
        return view('creator.modules.fondos');
    }
}
