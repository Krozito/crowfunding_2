<?php

namespace App\Http\Controllers;

use App\Models\ActualizacionProyecto;
use App\Models\Aportacion;
use App\Models\Proveedor;
use App\Models\ProveedorHistorial;
use App\Models\Proyecto;
use App\Models\Recompensa;
use App\Models\SolicitudDesembolso;
use App\Models\Pago;
use App\Models\VerificacionSolicitud;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CreatorController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $proyectos = Proyecto::where('creador_id', $userId)->get();

        $recaudadoAportaciones = Aportacion::whereHas('proyecto', fn($q) => $q->where('creador_id', $userId))
            ->sum('monto');
        $recaudadoDeclarado = $proyectos->sum('monto_recaudado');
        $recaudado = max($recaudadoAportaciones, $recaudadoDeclarado);

        $metaTotal = $proyectos->sum('meta_financiacion');
        $avance = $metaTotal > 0 ? round(($recaudado / $metaTotal) * 100) . '%' : '0%';

        $colaboradores = Aportacion::whereHas('proyecto', fn($q) => $q->where('creador_id', $userId))
            ->distinct('colaborador_id')
            ->count('colaborador_id');

        $metrics = [
            'proyectos'      => $proyectos->count(),
            'montoRecaudado' => $recaudado,
            'colaboradores'  => $colaboradores,
            'avance'         => $avance,
            'metaTotal'      => $metaTotal,
            'gastos'         => 0, // sin modelo de gastos implementado
        ];

        return view('creator.dashboard', compact('metrics'));
    }

    public function proyectos(): View
    {
        $proyectos = Proyecto::where('creador_id', auth()->id())->latest()->get();

        return view('creator.modules.proyectos', compact('proyectos'));
    }

    public function recompensas(Request $request): View
    {
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;

        $niveles = $this->getRecompensasPorProyecto($proyectos, $selectedProjectId);
        $preview = $niveles->first();

        return view('creator.modules.recompensas', compact('niveles', 'preview', 'proyectos', 'selectedProjectId'));
    }

    public function recompensasCrear(): View
    {
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();

        return view('creator.modules.recompensas-create', compact('proyectos'));
    }

    public function recompensasEditar(Recompensa $recompensa): View
    {
        $this->authorizeRecompensa($recompensa, auth()->id());
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();

        return view('creator.modules.recompensas-edit', compact('recompensa', 'proyectos'));
    }

    public function recompensasGestionar(Request $request): View
    {
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;
        $estadoFiltro = $request->query('estado');

        $niveles = $this->getRecompensasPorProyecto($proyectos, $selectedProjectId, $estadoFiltro);

        return view('creator.modules.recompensas-gestion', compact('niveles', 'proyectos', 'selectedProjectId', 'estadoFiltro'));
    }

    public function recompensasPreview(Request $request): View
    {
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;

        $niveles = $this->getRecompensasPorProyecto($proyectos, $selectedProjectId);
        $preview = $niveles->first();

        return view('creator.modules.recompensas-preview', compact('preview', 'niveles', 'proyectos', 'selectedProjectId'));
    }

    public function storeRecompensa(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'monto_minimo_aportacion' => ['required', 'numeric', 'min:0'],
            'disponibilidad' => ['nullable', 'integer', 'min:0'],
        ]);

        $proyecto = Proyecto::find($validated['proyecto_id']);
        abort_unless($proyecto && $proyecto->creador_id === $request->user()->id, 403);

        Recompensa::create([
            'proyecto_id' => $validated['proyecto_id'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'monto_minimo_aportacion' => $validated['monto_minimo_aportacion'],
            'disponibilidad' => $validated['disponibilidad'] ?? null,
        ]);

        return redirect()->route('creador.recompensas', ['proyecto' => $validated['proyecto_id']])
            ->with('status', 'Recompensa publicada.');
    }

    public function updateRecompensa(Request $request, Recompensa $recompensa): RedirectResponse
    {
        $this->authorizeRecompensa($recompensa, $request->user()->id);

        $validated = $request->validate([
            'proyecto_id' => ['required', 'exists:proyectos,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'monto_minimo_aportacion' => ['required', 'numeric', 'min:0'],
            'disponibilidad' => ['nullable', 'integer', 'min:0'],
        ]);

        $proyecto = Proyecto::find($validated['proyecto_id']);
        abort_unless($proyecto && $proyecto->creador_id === $request->user()->id, 403);

        $recompensa->update([
            'proyecto_id' => $validated['proyecto_id'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'] ?? null,
            'monto_minimo_aportacion' => $validated['monto_minimo_aportacion'],
            'disponibilidad' => $validated['disponibilidad'] ?? null,
        ]);

        return redirect()->route('creador.recompensas', ['proyecto' => $validated['proyecto_id']])
            ->with('status', 'Recompensa actualizada.');
    }

    public function toggleRecompensaEstado(Request $request, Recompensa $recompensa): RedirectResponse
    {
        $this->authorizeRecompensa($recompensa, $request->user()->id);
        $nuevo = $this->descripcionEsPausada($recompensa->descripcion) ? 'activo' : 'pausado';
        $recompensa->descripcion = $this->aplicarEstadoEnDescripcion($recompensa->descripcion, $nuevo);
        $recompensa->save();

        return redirect()->back()->with('status', "Recompensa {$nuevo}.");
    }

    public function eliminarRecompensa(Request $request, Recompensa $recompensa): RedirectResponse
    {
        $this->authorizeRecompensa($recompensa, $request->user()->id);
        $recompensa->delete();

        return redirect()->back()->with('status', 'Recompensa eliminada.');
    }

    public function avances(Request $request): View
    {
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;

        $actualizaciones = collect();
        if ($selectedProjectId) {
            $actualizaciones = ActualizacionProyecto::where('proyecto_id', $selectedProjectId)
                ->orderByDesc('fecha_publicacion')
                ->orderByDesc('id')
                ->get();
        }

        return view('creator.modules.avances', compact('proyectos', 'selectedProjectId', 'actualizaciones'));
    }

    public function fondos(Request $request): View
    {
        $userId = auth()->id();
        $proyectos = Proyecto::where('creador_id', $userId)->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;

        $solicitudes = collect();
        $finanzas = [
            'recaudado' => 0,
            'retenido' => 0,
            'liberado' => 0,
            'gastado' => 0,
            'pendiente' => 0,
            'disponible' => 0,
        ];

        if ($selectedProjectId) {
            $this->authorizeProyectoId($selectedProjectId, $userId);
            $solicitudes = SolicitudDesembolso::where('proyecto_id', $selectedProjectId)
                ->orderByDesc('created_at')
                ->get();

            $finanzas = $this->calcularFinanzasProyecto($selectedProjectId);
        }

        return view('creator.modules.fondos', compact('proyectos', 'selectedProjectId', 'solicitudes', 'finanzas'));
    }

    public function fondosHistorial(Request $request): View
    {
        $userId = auth()->id();
        $proyectos = Proyecto::where('creador_id', $userId)->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;

        $solicitudes = collect();
        if ($selectedProjectId) {
            $this->authorizeProyectoId($selectedProjectId, $userId);
            $solicitudes = SolicitudDesembolso::where('proyecto_id', $selectedProjectId)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('creator.modules.fondos-historial', compact('proyectos', 'selectedProjectId', 'solicitudes'));
    }

    public function proveedores(Request $request): View
    {
        $userId = auth()->id();
        $proyectos = Proyecto::where('creador_id', $userId)->get();
        $search = $request->query('q');
        $proyectoFiltro = $request->query('proyecto');

        $proveedoresQuery = Proveedor::with('proyecto')
            ->withAvg('historiales as calificacion_promedio', 'calificacion')
            ->where('creador_id', $userId)
            ->latest();

        if ($search) {
            $proveedoresQuery->where(function ($q) use ($search) {
                $q->where('nombre_proveedor', 'like', "%{$search}%")
                  ->orWhere('especialidad', 'like', "%{$search}%")
                  ->orWhere('info_contacto', 'like', "%{$search}%");
            });
        }

        if ($proyectoFiltro) {
            $proveedoresQuery->where('proyecto_id', $proyectoFiltro);
        }

        $proveedores = $proveedoresQuery->paginate(10)->withQueryString();
        $totalProveedores = Proveedor::where('creador_id', $userId)->count();

        return view('creator.modules.proveedores', compact('proyectos', 'proveedores', 'search', 'proyectoFiltro', 'totalProveedores'));
    }

    public function crearProveedor(): View
    {
        $userId = auth()->id();
        $proyectos = Proyecto::where('creador_id', $userId)->get();

        return view('creator.modules.proveedores-create', compact('proyectos'));
    }

    public function editarProveedor(Proveedor $proveedor): View
    {
        abort_unless($proveedor->creador_id === auth()->id(), 403);
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();

        return view('creator.modules.proveedores-edit', compact('proveedor', 'proyectos'));
    }

    public function showProveedor(Proveedor $proveedor): View
    {
        abort_unless($proveedor->creador_id === auth()->id(), 403);
        $proveedor->load('proyecto', 'historiales');
        $proveedor->loadAvg('historiales as calificacion_promedio', 'calificacion');
        $proyectos = Proyecto::where('creador_id', auth()->id())->get();
        $proveedores = Proveedor::with('proyecto:id,titulo')
            ->withAvg('historiales as calificacion_promedio', 'calificacion')
            ->where('creador_id', auth()->id())
            ->latest()
            ->get(['id', 'nombre_proveedor', 'proyecto_id']);

        return view('creator.modules.proveedores-show', compact('proveedor', 'proyectos', 'proveedores'));
    }

    public function perfil(): View
    {
        return view('creator.modules.perfil');
    }

    public function reportes(Request $request): View
    {
        $userId = auth()->id();
        $proyectos = Proyecto::where('creador_id', $userId)->get();
        $selectedProjectId = $request->query('proyecto') ?? $proyectos->first()?->id;

        $pagos = collect();
        $solicitudes = collect();
        $proveedores = collect();
        $resumen = [
            'totalPagado' => 0,
            'pagosConAdjuntos' => 0,
            'pagosProveedor' => 0,
        ];

        if ($selectedProjectId) {
            $this->authorizeProyectoId($selectedProjectId, $userId);
            $pagos = Pago::with('proveedor', 'solicitud')
                ->whereHas('solicitud', fn($q) => $q->where('proyecto_id', $selectedProjectId))
                ->orderByDesc('fecha_pago')
                ->orderByDesc('id')
                ->get();

            $solicitudes = SolicitudDesembolso::where('proyecto_id', $selectedProjectId)
                ->whereIn('estado', ['aprobado', 'liberado', 'pagado', 'gastado'])
                ->orderByDesc('created_at')
                ->get();

            $proveedores = Proveedor::where('creador_id', $userId)
                ->where(function ($q) use ($selectedProjectId) {
                    $q->whereNull('proyecto_id')->orWhere('proyecto_id', $selectedProjectId);
                })
                ->orderBy('nombre_proveedor')
                ->get();

            $resumen['totalPagado'] = $pagos->sum('monto');
            $resumen['pagosConAdjuntos'] = $pagos->filter(fn($p) => !empty($p->adjuntos))->count();
            $resumen['pagosProveedor'] = $pagos->count();
        }

        return view('creator.modules.reportes', compact('proyectos', 'selectedProjectId', 'pagos', 'solicitudes', 'proveedores', 'resumen'));
    }

    public function storeProyecto(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion_proyecto' => ['nullable', 'string'],
            'meta_financiacion' => ['required', 'numeric', 'min:0'],
            'modelo_financiamiento' => ['nullable', 'string', 'max:32'],
            'categoria' => ['nullable', 'string', 'max:64'],
            'ubicacion_geografica' => ['nullable', 'string', 'max:120'],
            'fecha_limite' => ['nullable', 'date'],
            'cronograma' => ['nullable', 'string'],
            'presupuesto' => ['nullable', 'string'],
            'portada' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('portada')) {
            $path = $request->file('portada')->store('proyectos', 'public');
        }

        Proyecto::create([
            'titulo' => $validated['titulo'],
            'descripcion_proyecto' => $validated['descripcion_proyecto'] ?? null,
            'meta_financiacion' => $validated['meta_financiacion'],
            'modelo_financiamiento' => $validated['modelo_financiamiento'] ?? null,
            'categoria' => $validated['categoria'] ?? null,
            'ubicacion_geografica' => $validated['ubicacion_geografica'] ?? null,
            'fecha_limite' => $validated['fecha_limite'] ?? null,
            'cronograma' => $this->decodeJson($validated['cronograma'] ?? null),
            'presupuesto' => $this->decodeJson($validated['presupuesto'] ?? null),
            'creador_id' => $request->user()->id,
            'estado' => 'borrador',
            'monto_recaudado' => 0,
            'imagen_portada' => $path,
        ]);

        return redirect()->back()->with('status', 'Proyecto creado en borrador.');
    }

    public function updateProyecto(Request $request, Proyecto $proyecto): RedirectResponse
    {
        abort_unless($proyecto->creador_id === $request->user()->id, 403);

        $validated = $request->validate([
            'titulo' => ['nullable', 'string', 'max:255'],
            'descripcion_proyecto' => ['nullable', 'string'],
            'meta_financiacion' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['nullable', 'string', 'max:32'],
            'modelo_financiamiento' => ['nullable', 'string', 'max:32'],
            'categoria' => ['nullable', 'string', 'max:64'],
            'ubicacion_geografica' => ['nullable', 'string', 'max:120'],
            'fecha_limite' => ['nullable', 'date'],
            'cronograma' => ['nullable', 'string'],
            'presupuesto' => ['nullable', 'string'],
            'portada' => ['nullable', 'image', 'max:2048'],
        ]);

        $payload = $validated;
        if (array_key_exists('cronograma', $validated)) {
            $payload['cronograma'] = $this->decodeJson($validated['cronograma']);
        }
        if (array_key_exists('presupuesto', $validated)) {
            $payload['presupuesto'] = $this->decodeJson($validated['presupuesto']);
        }
        if ($request->hasFile('portada')) {
            if ($proyecto->imagen_portada) {
                Storage::disk('public')->delete($proyecto->imagen_portada);
            }
            $payload['imagen_portada'] = $request->file('portada')->store('proyectos', 'public');
        }

        $proyecto->update($payload);

        return redirect()->back()->with('status', 'Proyecto actualizado.');
    }

    public function agregarAvance(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $this->authorizeProyecto($proyecto, $request->user()->id);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'contenido' => ['nullable', 'string'],
            'es_hito' => ['nullable', 'boolean'],
            'adjuntos.*' => ['nullable', 'file', 'max:8192'],
        ]);

        $paths = $this->storeAdjuntos($request);

        ActualizacionProyecto::create([
            'proyecto_id' => $proyecto->id,
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'] ?? null,
            'fecha_publicacion' => now(),
            'es_hito' => (bool) ($validated['es_hito'] ?? false),
            'adjuntos' => $paths,
        ]);

        return redirect()->back()->with('status', 'Avance publicado.');
    }

    public function updateAvance(Request $request, Proyecto $proyecto, ActualizacionProyecto $actualizacion): RedirectResponse
    {
        $this->authorizeProyecto($proyecto, $request->user()->id);
        abort_unless($actualizacion->proyecto_id === $proyecto->id, 403);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'contenido' => ['nullable', 'string'],
            'es_hito' => ['nullable', 'boolean'],
            'adjuntos.*' => ['nullable', 'file', 'max:8192'],
        ]);

        $paths = $actualizacion->adjuntos ?? [];
        if ($request->hasFile('adjuntos')) {
            $this->deleteAdjuntos($paths);
            $paths = $this->storeAdjuntos($request);
        }

        $actualizacion->update([
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'] ?? null,
            'es_hito' => (bool) ($validated['es_hito'] ?? false),
            'adjuntos' => $paths,
        ]);

        return redirect()->back()->with('status', 'Avance actualizado.');
    }

    public function deleteAvance(Request $request, Proyecto $proyecto, ActualizacionProyecto $actualizacion): RedirectResponse
    {
        $this->authorizeProyecto($proyecto, $request->user()->id);
        abort_unless($actualizacion->proyecto_id === $proyecto->id, 403);

        $this->deleteAdjuntos($actualizacion->adjuntos ?? []);
        $actualizacion->delete();

        return redirect()->back()->with('status', 'Avance eliminado.');
    }

    public function storeProveedor(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_proveedor' => ['required', 'string', 'max:255'],
            'proyecto_id' => ['nullable', 'exists:proyectos,id'],
            'info_contacto' => ['nullable', 'string'],
            'especialidad' => ['nullable', 'string', 'max:120'],
        ]);

        if (!empty($validated['proyecto_id'])) {
            $proyecto = Proyecto::find($validated['proyecto_id']);
            abort_unless($proyecto && $proyecto->creador_id === $request->user()->id, 403);
        }

        Proveedor::create([
            'creador_id' => $request->user()->id,
            'proyecto_id' => $validated['proyecto_id'] ?? null,
            'nombre_proveedor' => $validated['nombre_proveedor'],
            'info_contacto' => $validated['info_contacto'] ?? null,
            'especialidad' => $validated['especialidad'] ?? null,
        ]);

        return redirect()->back()->with('status', 'Proveedor registrado.');
    }

    public function updateProveedor(Request $request, Proveedor $proveedor): RedirectResponse
    {
        abort_unless($proveedor->creador_id === $request->user()->id, 403);

        $validated = $request->validate([
            'nombre_proveedor' => ['required', 'string', 'max:255'],
            'proyecto_id' => ['nullable', 'exists:proyectos,id'],
            'info_contacto' => ['nullable', 'string'],
            'especialidad' => ['nullable', 'string', 'max:120'],
        ]);

        if (!empty($validated['proyecto_id'])) {
            $proyecto = Proyecto::find($validated['proyecto_id']);
            abort_unless($proyecto && $proyecto->creador_id === $request->user()->id, 403);
        }

        $proveedor->update([
            'nombre_proveedor' => $validated['nombre_proveedor'],
            'proyecto_id' => $validated['proyecto_id'] ?? null,
            'info_contacto' => $validated['info_contacto'] ?? null,
            'especialidad' => $validated['especialidad'] ?? null,
        ]);

        return redirect()->route('creador.proveedores')->with('status', 'Proveedor actualizado.');
    }

    public function storeProveedorHistorial(Request $request, Proveedor $proveedor): RedirectResponse
    {
        abort_unless($proveedor->creador_id === $request->user()->id, 403);

        $validated = $request->validate([
            'concepto' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0'],
            'fecha_entrega' => ['nullable', 'date'],
            'calificacion' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        ProveedorHistorial::create([
            'proveedor_id' => $proveedor->id,
            'concepto' => $validated['concepto'],
            'monto' => $validated['monto'],
            'fecha_entrega' => $validated['fecha_entrega'] ?? null,
            'calificacion' => $validated['calificacion'] ?? null,
        ]);

        return redirect()->back()->with('status', 'Historial registrado.');
    }

    public function storePago(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $this->authorizeProyecto($proyecto, $request->user()->id);

        $validated = $request->validate([
            'solicitud_id' => ['required', 'exists:solicitudes_desembolso,id'],
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha_pago' => ['nullable', 'date'],
            'concepto' => ['nullable', 'string'],
            'adjuntos.*' => ['nullable', 'file', 'max:8192'],
        ]);

        $solicitud = SolicitudDesembolso::find($validated['solicitud_id']);
        abort_unless($solicitud && $solicitud->proyecto_id === $proyecto->id, 403);

        $permitidos = ['aprobado', 'liberado', 'pagado', 'gastado'];
        if (!in_array($solicitud->estado, $permitidos, true)) {
            return redirect()->back()->withErrors(['solicitud_id' => 'Solo puedes asociar pagos a desembolsos aprobados o liberados.'])->withInput();
        }

        $proveedor = Proveedor::find($validated['proveedor_id']);
        abort_unless($proveedor && $proveedor->creador_id === $request->user()->id, 403);
        if ($proveedor->proyecto_id && $proveedor->proyecto_id !== $proyecto->id) {
            return redirect()->back()->withErrors(['proveedor_id' => 'El proveedor no pertenece a este proyecto.'])->withInput();
        }

        $paths = $this->storePagoAdjuntos($request);

        Pago::create([
            'solicitud_id' => $solicitud->id,
            'proveedor_id' => $proveedor->id,
            'monto' => $validated['monto'],
            'fecha_pago' => $validated['fecha_pago'] ?? now(),
            'concepto' => $validated['concepto'] ?? null,
            'adjuntos' => $paths,
        ]);

        return redirect()->route('creador.reportes', ['proyecto' => $proyecto->id])
            ->with('status', 'Pago registrado con evidencias.');
    }

    public function updatePerfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre_completo' => ['nullable', 'string', 'max:255'],
            'profesion' => ['nullable', 'string', 'max:120'],
            'experiencia' => ['nullable', 'string'],
            'biografia' => ['nullable', 'string'],
            'info_personal' => ['nullable', 'string'],
            'redes_sociales' => ['nullable', 'array'],
            'redes_sociales.*' => ['nullable', 'url'],
            'foto_perfil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();
        $user->nombre_completo = $validated['nombre_completo'] ?? $user->nombre_completo;
        $user->profesion = $validated['profesion'] ?? $user->profesion;
        $user->experiencia = $validated['experiencia'] ?? $user->experiencia;
        $user->biografia = $validated['biografia'] ?? $user->biografia;
        $user->info_personal = $validated['info_personal'] ?? $user->info_personal;
        $user->redes_sociales = $validated['redes_sociales'] ?? $user->redes_sociales;

        if ($request->hasFile('foto_perfil')) {
            if ($user->foto_perfil) {
                Storage::disk('public')->delete($user->foto_perfil);
            }
            $user->foto_perfil = $request->file('foto_perfil')->store('perfiles', 'public');
        }

        $user->save();

        return redirect()->back()->with('status', 'Perfil actualizado.');
    }

    public function solicitarVerificacion(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'documento_frontal' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'documento_reverso' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'selfie' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'nota' => ['nullable', 'string'],
        ]);

        $existe = VerificacionSolicitud::where('user_id', $user->id)
            ->where('estado', 'pendiente')
            ->exists();
        if ($existe) {
            return redirect()->back()->withErrors(['verificacion' => 'Ya tienes una solicitud de verificacion pendiente.'])->withInput();
        }

        $adjuntos = $this->storeKycAdjuntos($request);

        VerificacionSolicitud::create([
            'user_id' => $user->id,
            'estado' => 'pendiente',
            'nota' => $validated['nota'] ?? null,
            'adjuntos' => $adjuntos,
        ]);

        return redirect()->back()->with('status', 'Solicitud de verificacion enviada a administracion.');
    }

    public function verificacion(Request $request): View
    {
        $pendiente = VerificacionSolicitud::where('user_id', $request->user()->id)
            ->where('estado', 'pendiente')
            ->exists();

        return view('creator.modules.perfil-verificacion', compact('pendiente'));
    }

    public function storeSolicitudDesembolso(Request $request, Proyecto $proyecto): RedirectResponse
    {
        $this->authorizeProyecto($proyecto, $request->user()->id);

        $validated = $request->validate([
            'monto_solicitado' => ['required', 'numeric', 'min:1'],
            'hito' => ['required', 'string', 'max:160'],
            'descripcion' => ['nullable', 'string'],
            'proveedores' => ['nullable', 'string'],
            'fecha_estimada' => ['nullable', 'date'],
            'adjuntos.*' => ['nullable', 'file', 'max:8192'],
        ]);

        $existe = SolicitudDesembolso::where('proyecto_id', $proyecto->id)
            ->where('hito', $validated['hito'])
            ->where('estado', 'pendiente')
            ->exists();

        if ($existe) {
            return redirect()->back()->withErrors(['hito' => 'Ya existe una solicitud pendiente para este hito.'])->withInput();
        }

        $finanzas = $this->calcularFinanzasProyecto($proyecto->id);
        if ($validated['monto_solicitado'] > $finanzas['disponible']) {
            return redirect()->back()->withErrors(['monto_solicitado' => 'El monto solicitado excede los fondos disponibles.'])->withInput();
        }

        $proveedores = [];
        if (!empty($validated['proveedores'])) {
            $proveedores = collect(explode(',', $validated['proveedores']))
                ->map(fn($v) => trim($v))
                ->filter()
                ->values()
                ->all();
        }

        $paths = $this->storeDesembolsoAdjuntos($request);

        SolicitudDesembolso::create([
            'proyecto_id' => $proyecto->id,
            'monto_solicitado' => $validated['monto_solicitado'],
            'hito' => $validated['hito'],
            'descripcion' => $validated['descripcion'] ?? null,
            'proveedores' => $proveedores,
            'fecha_estimada' => $validated['fecha_estimada'] ?? null,
            'estado' => 'pendiente',
            'adjuntos' => $paths,
        ]);

        return redirect()->route('creador.fondos', ['proyecto' => $proyecto->id])
            ->with('status', 'Solicitud enviada.');
    }

    private function authorizeProyecto(Proyecto $proyecto, int $userId): void
    {
        abort_unless($proyecto->creador_id === $userId, 403);
    }

    private function authorizeProyectoId(int $proyectoId, int $userId): void
    {
        $proyecto = Proyecto::find($proyectoId);
        abort_unless($proyecto && $proyecto->creador_id === $userId, 403);
    }

    private function storeAdjuntos(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $paths[] = $file->store('actualizaciones', 'public');
            }
        }

        return $paths;
    }

    private function storeDesembolsoAdjuntos(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $paths[] = $file->store('desembolsos', 'public');
            }
        }

        return $paths;
    }

    private function storePagoAdjuntos(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('adjuntos')) {
            foreach ($request->file('adjuntos') as $file) {
                $paths[] = $file->store('pagos', 'public');
            }
        }

        return $paths;
    }

    private function storeKycAdjuntos(Request $request): array
    {
        $paths = [];
        if ($request->hasFile('documento_frontal')) {
            $paths['documento_frontal'] = $request->file('documento_frontal')->store('kyc', 'public');
        }
        if ($request->hasFile('documento_reverso')) {
            $paths['documento_reverso'] = $request->file('documento_reverso')->store('kyc', 'public');
        }
        if ($request->hasFile('selfie')) {
            $paths['selfie'] = $request->file('selfie')->store('kyc', 'public');
        }

        return $paths;
    }

    private function deleteAdjuntos(array $paths): void
    {
        foreach ($paths as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function calcularFinanzasProyecto(int $proyectoId): array
    {
        $recaudadoAportaciones = Aportacion::where('proyecto_id', $proyectoId)->sum('monto');
        $recaudadoProyecto = Proyecto::where('id', $proyectoId)->value('monto_recaudado') ?? 0;
        $recaudado = max($recaudadoAportaciones, $recaudadoProyecto);
        $solicitudes = SolicitudDesembolso::where('proyecto_id', $proyectoId)->get();

        $liberado = $solicitudes->whereIn('estado', ['liberado', 'aprobado', 'pagado'])->sum('monto_solicitado');
        $gastado = $solicitudes->where('estado', 'gastado')->sum('monto_solicitado');
        $pendiente = $solicitudes->where('estado', 'pendiente')->sum('monto_solicitado');

        $retenido = max($recaudado - $liberado, 0);
        $disponible = max($recaudado - $liberado - $pendiente, 0);

        return [
            'recaudado' => $recaudado,
            'retenido' => $retenido,
            'liberado' => $liberado,
            'gastado' => $gastado,
            'pendiente' => $pendiente,
            'disponible' => $disponible,
        ];
    }

    private function decodeJson(?string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function getRecompensasPorProyecto($proyectos, $selectedProjectId, $estadoFiltro = null)
    {
        $query = Recompensa::with('proyecto')
            ->whereHas('proyecto', fn($q) => $q->where('creador_id', auth()->id()));

        if ($selectedProjectId) {
            $query->where('proyecto_id', $selectedProjectId);
        }

        $registros = $query->orderBy('id')->get();

        return $registros->map(function (Recompensa $r) {
            return [
                'id' => $r->id,
                'titulo' => $r->titulo,
                'monto' => $r->monto_minimo_aportacion,
                'descripcion' => $r->descripcion ?? 'Sin descripcion',
                'beneficios' => [],
                'limite' => $r->disponibilidad,
                'disponibles' => $r->disponibilidad,
                'orden' => $r->id,
                'estado' => $this->descripcionEsPausada($r->descripcion) ? 'pausado' : 'activo',
                'entrega' => 'Pendiente',
                'proyecto_id' => $r->proyecto_id,
                'proyecto' => $r->proyecto->titulo ?? 'Proyecto',
            ];
        })->when($estadoFiltro, fn($c) => $c->where('estado', $estadoFiltro));
    }

    private function authorizeRecompensa(Recompensa $recompensa, int $userId): void
    {
        $recompensa->loadMissing('proyecto');
        abort_unless(optional($recompensa->proyecto)->creador_id === $userId, 403);
    }

    private function descripcionEsPausada(?string $descripcion): bool
    {
        return str_starts_with((string) $descripcion, '[PAUSADO]');
    }

    private function aplicarEstadoEnDescripcion(?string $descripcion, string $estado): string
    {
        $desc = (string) $descripcion;
        $limpia = ltrim($desc);

        if ($this->descripcionEsPausada($limpia)) {
            $limpia = preg_replace('/^\\[PAUSADO\\]\\s*/', '', $limpia) ?? '';
        }

        return $estado === 'pausado' ? '[PAUSADO] ' . $limpia : $limpia;
    }
}
