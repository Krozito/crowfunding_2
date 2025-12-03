<?php

namespace App\Http\Controllers;

use App\Models\ActualizacionProyecto;
use App\Models\Aportacion;
use App\Models\Proveedor;
use App\Models\ProveedorHistorial;
use App\Models\Proyecto;
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

        $recaudado = Aportacion::whereHas('proyecto', fn($q) => $q->where('creador_id', $userId))
            ->sum('monto');

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

    public function reportes(): View
    {
        return view('creator.modules.reportes');
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
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'contenido' => ['nullable', 'string'],
        ]);

        ActualizacionProyecto::create([
            'proyecto_id' => $proyecto->id,
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'] ?? null,
            'fecha_publicacion' => now(),
        ]);

        return redirect()->back()->with('status', 'Avance publicado.');
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

    public function updatePerfil(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'info_personal' => ['nullable', 'string'],
            'redes_sociales' => ['nullable', 'array'],
            'redes_sociales.*' => ['nullable', 'string'],
            'estado_verificacion' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $user->info_personal = $validated['info_personal'] ?? $user->info_personal;
        $user->redes_sociales = $validated['redes_sociales'] ?? $user->redes_sociales;

        if (array_key_exists('estado_verificacion', $validated)) {
            $user->estado_verificacion = $validated['estado_verificacion'];
        }

        $user->save();

        return redirect()->back()->with('status', 'Perfil actualizado.');
    }

    private function decodeJson(?string $value): ?array
    {
        if (!$value) {
            return null;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : null;
    }
}
