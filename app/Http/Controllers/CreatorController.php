<?php

namespace App\Http\Controllers;

use App\Models\ActualizacionProyecto;
use App\Models\Proveedor;
use App\Models\Proyecto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CreatorController extends Controller
{
    public function index(): View
    {
        // Metricas de ejemplo (sustituir por consultas reales cuando existan modelos)
        $metrics = [
            'proyectos'      => 0,
            'montoRecaudado' => 0,
            'colaboradores'  => 0,
            'avance'         => '0%',
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

    public function proveedores(): View
    {
        return view('creator.modules.proveedores');
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
