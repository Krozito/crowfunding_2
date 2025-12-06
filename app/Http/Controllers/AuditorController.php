<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudDesembolso;
use App\Models\Pago;
use App\Models\VerificacionSolicitud;
use App\Models\Proyecto;
use App\Models\ActualizacionProyecto;

class AuditorController extends Controller
{
    public function index()
    {
        $kpis = [
            'solicitudes_pendientes' => SolicitudDesembolso::where('estado', 'pendiente')->count(),
            'solicitudes_aprobadas' => SolicitudDesembolso::whereIn('estado', ['aprobado', 'liberado', 'pagado', 'gastado'])->count(),
            'pagos_registrados' => Pago::count(),
            'kyc_pendientes' => VerificacionSolicitud::where('estado', 'pendiente')->count(),
        ];

        $solicitudesPendientes = SolicitudDesembolso::with('proyecto')
            ->where('estado', 'pendiente')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $pagosRecientes = Pago::with(['proveedor', 'solicitud.proyecto'])
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $verificacionesPendientes = VerificacionSolicitud::with('user')
            ->where('estado', 'pendiente')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $proyectosActivos = Proyecto::orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('auditor.dashboard', compact(
            'kpis',
            'solicitudesPendientes',
            'pagosRecientes',
            'verificacionesPendientes',
            'proyectosActivos'
        ));
    }

    public function comprobantes()
    {
        $pagos = Pago::with(['proveedor', 'solicitud.proyecto'])
            ->orderByDesc('fecha_pago')
            ->orderByDesc('id')
            ->paginate(15);

        return view('auditor.modules.comprobantes', compact('pagos'));
    }

    public function desembolsos()
    {
        $solicitudes = SolicitudDesembolso::with('proyecto')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('auditor.modules.desembolsos', compact('solicitudes'));
    }

    public function reportes()
    {
        // No hay modelo de reportes de colaboradores; se envía colección vacía para datos reales.
        $reportesColab = collect();
        return view('auditor.modules.reportes', compact('reportesColab'));
    }

    public function hitos()
    {
        $hitos = ActualizacionProyecto::where('es_hito', true)
            ->orderByDesc('fecha_publicacion')
            ->take(30)
            ->get();

        return view('auditor.modules.hitos', compact('hitos'));
    }

    public function proyectos()
    {
        $proyectos = Proyecto::orderByDesc('created_at')
            ->withCount('aportaciones')
            ->get();

        return view('auditor.modules.proyectos', compact('proyectos'));
    }
}
