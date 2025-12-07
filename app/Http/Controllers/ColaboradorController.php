<?php

namespace App\Http\Controllers;

use App\Models\Aportacion;
use App\Models\Pago;
use App\Models\Proyecto;
use App\Models\ProyectoCategoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Http\Response;

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
    public function aportaciones(Request $request): View
    {
        $colaboradorId = Auth::id();

        $proyectoFiltro = $request->query('proyecto');
        $desde = $request->query('desde');
        $hasta = $request->query('hasta');

        $aportacionesQuery = Aportacion::with('proyecto')
            ->where('colaborador_id', $colaboradorId)
            ->orderByDesc('fecha_aportacion');

        if ($proyectoFiltro) {
            $aportacionesQuery->whereHas('proyecto', function ($q) use ($proyectoFiltro) {
                $q->where('titulo', 'like', "%{$proyectoFiltro}%");
            });
        }

        if ($desde) {
            $from = Carbon::parse($desde)->startOfDay();
            $aportacionesQuery->where('fecha_aportacion', '>=', $from);
        }

        if ($hasta) {
            $to = Carbon::parse($hasta)->endOfDay();
            $aportacionesQuery->where('fecha_aportacion', '<=', $to);
        }

        $aportaciones = $aportacionesQuery->get();
        $totalAportado = $aportaciones->sum('monto');
        $numAportes = $aportaciones->count();
        $proyectosApoyados = $aportaciones->pluck('proyecto_id')->filter()->unique()->count();
        $ultimaAportacion = $aportaciones->max('fecha_aportacion') ?? $aportaciones->max('created_at');

        return view('colaborador.aportaciones', compact(
            'aportaciones',
            'proyectoFiltro',
            'desde',
            'hasta',
            'totalAportado',
            'numAportes',
            'proyectosApoyados',
            'ultimaAportacion'
        ));
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

    public function resumenProyecto(Proyecto $proyecto): View
    {
        $colaboradorId = Auth::id();
        $proyecto->load(['creador', 'hitos']);

        $aporteUsuario = Aportacion::where('colaborador_id', $colaboradorId)
            ->where('proyecto_id', $proyecto->id)
            ->sum('monto');

        return view('colaborador.proyectos-resumen', compact('proyecto', 'aporteUsuario'));
    }

    public function proveedoresProyecto(Proyecto $proyecto): View
    {
        $proyecto->load(['creador', 'proveedores.historiales']);
        $proveedores = $proyecto->proveedores;

        $pagos = Pago::with('solicitud')
            ->whereHas('solicitud', fn($q) => $q->where('proyecto_id', $proyecto->id))
            ->whereIn('proveedor_id', $proveedores->pluck('id'))
            ->orderByDesc('fecha_pago')
            ->get()
            ->groupBy('proveedor_id');

        return view('colaborador.proyectos-proveedores', compact('proyecto', 'proveedores', 'pagos'));
    }

    public function reportePagosProyecto(Proyecto $proyecto): View
    {
        $proyecto->load('creador');
        $aportaciones = Aportacion::where('proyecto_id', $proyecto->id)->get();
        $total = $aportaciones->sum('monto');
        return view('colaborador.proyectos-reporte', compact('proyecto', 'aportaciones', 'total'));
    }

    /**
     * Vista para aportar a un proyecto
     */
    public function aportarProyecto(Proyecto $proyecto): View
    {
        $proyecto->load(['recompensas', 'creador']);
        return view('colaborador.proyectos-aportar', compact('proyecto'));
    }

    /**
     * Registrar aporte (simulado)
     */
    public function storeAportacion(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $validated = $request->validate([
            'monto' => ['required'],
            'recompensa_id' => ['nullable', 'exists:recompensas,id'],
            'mensaje' => ['nullable', 'string', 'max:500'],
        ]);

        // Normaliza el monto por si viene con comas o puntos de miles
        $monto = $validated['monto'];
        if (is_string($monto)) {
            $monto = str_replace([' ', ','], ['', '.'], $monto);
        }
        $monto = (float) $monto;

        if ($monto <= 0) {
            return back()->withErrors(['monto' => 'El monto debe ser mayor que cero.'])->withInput();
        }

        $aportacionId = null;

        DB::transaction(function () use ($proyecto, $monto, &$aportacionId) {
            $aportacion = Aportacion::create([
                'colaborador_id' => Auth::id(),
                'proyecto_id' => $proyecto->id,
                'monto' => $monto,
                'fecha_aportacion' => now(),
                'estado_pago' => 'pagado',
                'id_transaccion_pago' => null,
            ]);

            $proyecto->increment('monto_recaudado', $monto);

            $aportacionId = $aportacion->id;
        });

        Log::info('Aportacion creada por colaborador', [
            'aportacion_id' => $aportacionId,
            'colaborador_id' => Auth::id(),
            'proyecto_id' => $proyecto->id,
            'monto' => $monto,
        ]);

        return redirect()
            ->route('colaborador.proyectos.show', $proyecto)
            ->with('status', 'Aporte registrado (#'.$aportacionId.'). Procesaremos tu pago en breve.');
    }

    /**
     * Recibo PDF simple de una aportación
     */
    public function reciboAportacion(Aportacion $aporte): Response
    {
        if ($aporte->colaborador_id !== Auth::id()) {
            abort(403);
        }

        $aporte->load('proyecto');

        $escape = function ($text) {
            $text = (string) $text;
            return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        };

        $lineas = [
            'Recibo de aportacion',
            'Aporte ID: '.$aporte->id,
            'Proyecto: '.$escape($aporte->proyecto->titulo ?? 'N/D'),
            'Monto: $'.number_format($aporte->monto, 2, '.', ','),
            'Estado: '.strtoupper($aporte->estado_pago ?? 'N/D'),
            'Fecha: '.($aporte->fecha_aportacion?->format('d/m/Y H:i') ?? $aporte->created_at?->format('d/m/Y H:i') ?? ''),
            'Transaccion: '.($aporte->id_transaccion_pago ?? '-'),
        ];

        $y = 760;
        $streamParts = ["BT /F1 14 Tf 60 $y Td (".$escape('Recibo de aportacion').") Tj ET"];
        $y -= 28;
        foreach (array_slice($lineas, 1) as $linea) {
            $streamParts[] = "BT /F1 11 Tf 60 $y Td (".$escape($linea).") Tj ET";
            $y -= 18;
        }
        $stream = implode("\n", $streamParts);
        $len = strlen($stream);

        $objects = [];
        $objects[] = "1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n";
        $objects[] = "2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n";
        $objects[] = "3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n";
        $objects[] = "4 0 obj << /Length $len >> stream\n$stream\nendstream\nendobj\n";
        $objects[] = "5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\n";

        $pdf = "%PDF-1.4\n";
        $offsets = [0]; // xref object 0
        foreach ($objects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj;
        }

        $xrefPos = strlen($pdf);
        $pdf .= "xref\n0 ".count($offsets)."\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i < count($offsets); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= "trailer << /Size ".count($offsets)." /Root 1 0 R >>\nstartxref\n{$xrefPos}\n%%EOF";

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="recibo-aporte-'.$aporte->id.'.pdf"',
        ]);
    }
}
