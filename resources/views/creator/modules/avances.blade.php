@extends('creator.layouts.panel')

@section('title', 'Avances y actualizaciones')
@section('active', 'avances')

@section('content')
    @php
        $estadoBadge = fn($estado) => match($estado) {
            'borrador' => ['label' => 'Borrador', 'classes' => 'bg-amber-500/15 text-amber-200 border-amber-400/40'],
            'publicado' => ['label' => 'Publicado', 'classes' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40'],
            'finalizado' => ['label' => 'Finalizado', 'classes' => 'bg-sky-500/15 text-sky-200 border-sky-400/40'],
            default => ['label' => ucfirst($estado ?? 'N/D'), 'classes' => 'bg-zinc-500/15 text-zinc-200 border-white/10'],
        };
        $hasMultipleProjects = $proyectos->count() > 1;
    @endphp

    @if (session('status'))
        <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                <h2 class="text-2xl font-bold text-white">Gestiona avances y hitos</h2>
                <p class="text-sm text-zinc-400">Selecciona el proyecto y publica actualizaciones destacando los hitos relevantes.</p>
            </div>
            <div>
                @if ($hasMultipleProjects)
                    <form method="GET" action="{{ route('creador.avances') }}" class="flex flex-wrap items-center gap-2">
                        <label class="text-xs text-zinc-400 mr-2">Proyecto</label>
                        <select name="proyecto" class="rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            @foreach ($proyectos as $proyecto)
                                <option value="{{ $proyecto->id }}" @selected($selectedProjectId == $proyecto->id)>{{ $proyecto->titulo }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                            Aplicar
                        </button>
                    </form>
                @else
                    <p class="text-sm text-white/90">Proyecto activo: <span class="font-semibold">{{ $selectedProject->titulo ?? 'Proyecto' }}</span></p>
                    <a href="{{ route('creador.proyectos') }}" class="text-xs text-emerald-200 hover:text-white">Cambiar proyecto</a>
                @endif
            </div>
        </div>
        @if ($projectContext)
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Estado</p>
                    @php $badge = $estadoBadge($projectContext['estado']); @endphp
                    <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $badge['classes'] }}">
                        {{ $badge['label'] }}
                    </span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Recaudado vs meta</p>
                    <p class="text-lg font-semibold text-white">${{ number_format($projectContext['recaudado'],0,',','.') }} de ${{ number_format($projectContext['meta'],0,',','.') }}</p>
                    <div class="mt-2 h-2 rounded-full bg-white/10">
                        <div class="h-2 rounded-full bg-emerald-400" style="width: {{ min(100, $projectContext['progreso']) }}%;"></div>
                    </div>
                    <p class="text-[11px] text-zinc-500 mt-1">{{ $projectContext['progreso'] }}% completado</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                    <p class="text-[11px] uppercase text-zinc-400">Próximo hito</p>
                    <p class="text-sm text-white">{{ $projectContext['hito'] }}</p>
                </div>
            </div>
        @endif
    </section>

    @if ($proyectos->isEmpty())
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 text-sm text-zinc-300 shadow-2xl">
            No tienes proyectos creados aún. <a class="text-indigo-300 underline" href="{{ route('creador.proyectos') }}">Crea un proyecto</a> para comenzar a publicar avances.
        </section>
    @else
        <div class="grid gap-6 lg:grid-cols-[1.05fr,1.15fr]">
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Publicar actualización</p>
                    <h3 class="text-lg font-semibold text-white">Publica un nuevo avance</h3>
                    <p class="text-sm text-zinc-400">Comparte avances, marca hitos y añade evidencias.</p>
                </div>

                <form method="POST" action="{{ route('creador.proyectos.avances', $selectedProjectId) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-semibold text-white">Título *</label>
                        <input required name="titulo" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. Entregamos el primer lote a los backers">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-white">Contenido</label>
                        <textarea name="contenido" rows="4" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Comparte avances, bloqueos o próximos pasos..."></textarea>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-white">Adjuntos (arrastra o selecciona archivos)</label>
                        <input type="file" name="adjuntos[]" multiple class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30" data-file-preview="avance-nuevo">
                        <div class="mt-2 flex flex-wrap gap-2 text-xs text-zinc-300" data-file-list="avance-nuevo"></div>
                        <p class="mt-1 text-xs text-zinc-500">Máximo 8MB por archivo.</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/40 hover:bg-emerald-500">
                            Publicar avance
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-emerald-500/10 flex flex-col space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Historial</p>
                        <h3 class="text-lg font-bold text-white">Últimas actualizaciones</h3>
                    </div>
                    <div class="flex gap-2 text-xs text-zinc-300">
                        <span>Más recientes arriba</span>
                    </div>
                </div>

                <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                    @forelse ($actualizaciones as $actualizacion)
                        @php
                            $badge = $actualizacion->es_hito
                                ? ['label' => 'Hito cumplido', 'classes' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30']
                                : ['label' => 'Actualización', 'classes' => 'bg-indigo-500/15 text-indigo-100 border border-indigo-400/30'];
                            $adjuntosCount = count($actualizacion->adjuntos ?? []);
                        @endphp
                        <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        @if($actualizacion->es_hito)
                                            <span class="text-sm text-emerald-200">⭐</span>
                                        @endif
                                        <p class="text-sm font-semibold text-white">{{ $actualizacion->titulo }}</p>
                                    </div>
                                    <p class="text-xs text-zinc-400">{{ $actualizacion->fecha_publicacion?->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-zinc-300">{{ \Illuminate\Support\Str::limit($actualizacion->contenido ?? 'Sin descripción', 120) }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge['classes'] }}">{{ $badge['label'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-zinc-400">
                                <p class="{{ $adjuntosCount ? 'text-zinc-200' : 'text-zinc-500' }}">
                                    {{ $adjuntosCount ? "{$adjuntosCount} archivos adjuntos" : 'Sin adjuntos' }}
                                </p>
                                <a href="#gestion-{{ $actualizacion->id }}" class="text-xs text-emerald-300 hover:text-white">Ver / editar avance</a>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                                <p class="text-[11px] text-zinc-500 font-semibold">Adjuntos</p>
                                @if ($adjuntosCount)
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @foreach ($actualizacion->adjuntos as $idx => $archivo)
                                            <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1 text-[11px] font-semibold text-white hover:border-indigo-400/60">
                                                Archivo {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-1 text-xs text-zinc-500">Sin adjuntos</p>
                                @endif
                            </div>

                            <details id="gestion-{{ $actualizacion->id }}" class="overflow-hidden rounded-xl border border-white/10 bg-zinc-900/60">
                                <summary class="flex cursor-pointer items-center justify-between px-4 py-3 text-xs font-semibold text-white rounded-xl bg-emerald-600/80 hover:bg-emerald-600 border border-emerald-500/40">
                                    Editar avance
                                    <span class="text-[11px] text-zinc-400">Click para abrir</span>
                                </summary>
                                <div class="border-t border-white/10 px-4 py-4 space-y-3">
                                    <form method="POST" action="{{ route('creador.proyectos.avances.update', [$actualizacion->proyecto_id, $actualizacion->id]) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="text-sm text-zinc-300">Título *</label>
                                            <input required name="titulo" value="{{ $actualizacion->titulo }}" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                                        </div>
                                        <div>
                                            <label class="text-sm text-zinc-300">Contenido</label>
                                            <textarea name="contenido" rows="3" class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">{{ $actualizacion->contenido }}</textarea>
                                        </div>
                                        <div>
                                            <label class="inline-flex items-center gap-2 text-sm text-zinc-200">
                                                <input type="checkbox" name="es_hito" value="1" @checked($actualizacion->es_hito) class="h-4 w-4 rounded border-white/20 bg-zinc-900 text-indigo-500 focus:ring-indigo-400">
                                                Marcar como hito cumplido
                                            </label>
                                            <p class="text-[11px] text-zinc-500 ml-6">Los hitos destacados se muestran a colaboradores.</p>
                                        </div>
                                        <div>
                                            <label class="text-sm text-zinc-300">Reemplazar adjuntos</label>
                                            <input type="file" name="adjuntos[]" multiple class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30" data-file-preview="avance-{{ $actualizacion->id }}">
                                            <div class="mt-2 flex flex-wrap gap-2 text-xs text-zinc-300" data-file-list="avance-{{ $actualizacion->id }}"></div>
                                            <p class="mt-1 text-[11px] text-zinc-500">Si adjuntas nuevos archivos, sustituiremos los actuales.</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2 pt-2">
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                                Guardar cambios
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('creador.proyectos.avances.delete', [$actualizacion->proyecto_id, $actualizacion->id]) }}" onsubmit="return confirm('¿Eliminar este avance?');" class="pt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-500">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-400">Aún no hay avances publicados para este proyecto. Usa el panel izquierdo para publicar el primero.</p>
                    @endforelse
                </div>
            </section>
        </div>
    @endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type="file"][data-file-preview]').forEach(input => {
        const previewId = input.getAttribute('data-file-preview');
        const container = document.querySelector(`[data-file-list="${previewId}"]`);
        const updateList = () => {
            if (!container) return;
            container.innerHTML = '';
            const files = Array.from(input.files || []);
            if (!files.length) {
                container.textContent = 'Sin archivos seleccionados';
                container.classList.add('text-zinc-500');
                return;
            }
            container.classList.remove('text-zinc-500');
            files.forEach(file => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-white';
                badge.textContent = file.name;
                container.appendChild(badge);
            });
        };
        input.addEventListener('change', updateList);
        updateList();
    });
});
</script>
@endpush
