@extends('creator.layouts.panel')

@section('title', 'Avances y actualizaciones')
@section('active', 'avances')

@section('content')
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
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                <h2 class="text-2xl font-bold text-white">Selecciona el proyecto para gestionar sus avances</h2>
                <p class="text-sm text-zinc-400">Elige un proyecto y publica o edita actualizaciones con adjuntos e hitos.</p>
            </div>
            <div class="text-xs text-zinc-300">
            </div>
        </div>

        <form method="GET" action="{{ route('creador.avances') }}" class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-end">
            <div>
                <label class="text-xs text-zinc-400">Proyecto</label>
                <select name="proyecto" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                    @forelse ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" @selected($selectedProjectId == $proyecto->id)>{{ $proyecto->titulo }}</option>
                    @empty
                        <option value="">Sin proyectos disponibles</option>
                    @endforelse
                </select>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                Gestionar proyecto
            </button>
        </form>
    </section>

    @if ($proyectos->isEmpty())
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 text-sm text-zinc-300 shadow-2xl">
            No tienes proyectos creados aun. <a class="text-indigo-300 underline" href="{{ route('creador.proyectos') }}">Crea un proyecto</a> para comenzar a publicar avances.
        </section>
    @else
        <div class="grid gap-6 lg:grid-cols-[1.05fr,1.15fr]">
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Nuevo avance</p>
                        <h3 class="text-lg font-bold text-white">Publicar actualizacion</h3>
                        <p class="text-sm text-zinc-400">Agrega texto, marca si es un hito e incluye archivos.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('creador.proyectos.avances', $selectedProjectId) }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-sm text-zinc-300">Titulo *</label>
                        <input required name="titulo" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. Entregamos el primer lote a los backers">
                    </div>
                    <div>
                        <label class="text-sm text-zinc-300">Contenido</label>
                        <textarea name="contenido" rows="4" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Comparte avances, bloqueos o proximos pasos..."></textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-2 text-sm text-zinc-200">
                            <input type="checkbox" name="es_hito" value="1" class="h-4 w-4 rounded border-white/20 bg-zinc-900 text-indigo-500 focus:ring-indigo-400">
                            Marcar como hito cumplido
                        </label>
                    </div>
                    <div>
                        <label class="text-sm text-zinc-300">Adjuntos (imagenes, videos o docs)</label>
                        <input type="file" name="adjuntos[]" multiple class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30">
                        <p class="mt-1 text-xs text-zinc-500">Tamano maximo por archivo: 8MB.</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                            Publicar avance
                        </button>
                    </div>
                </form>
            </section>

            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-emerald-500/10 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Historial</p>
                        <h3 class="text-lg font-bold text-white">Actualizaciones del proyecto</h3>
                        <p class="text-sm text-zinc-400">Edita o elimina sin tener que desplazarte demasiado.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($actualizaciones as $actualizacion)
                        @php
                            $badge = $actualizacion->es_hito
                                ? ['label' => 'Hito cumplido', 'classes' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30']
                                : ['label' => 'Actualizacion', 'classes' => 'bg-indigo-500/15 text-indigo-100 border border-indigo-400/30'];
                        @endphp
                        <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <p class="text-sm font-semibold text-white">{{ $actualizacion->titulo }}</p>
                                    <p class="text-xs text-zinc-400">{{ $actualizacion->fecha_publicacion?->format('d/m/Y H:i') }}</p>
                                    <p class="text-sm text-zinc-200 whitespace-pre-line">{{ $actualizacion->contenido ?? 'Sin descripcion' }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge['classes'] }}">{{ $badge['label'] }}</span>
                            </div>

                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                                <p class="text-[11px] text-zinc-500">Adjuntos</p>
                                @if (!empty($actualizacion->adjuntos))
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @foreach ($actualizacion->adjuntos as $idx => $archivo)
                                            <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1 hover:border-indigo-400/60">
                                                Archivo {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-1 text-white">Sin adjuntos</p>
                                @endif
                            </div>

                            <details class="overflow-hidden rounded-xl border border-white/10 bg-zinc-900/60">
                                <summary class="flex cursor-pointer items-center justify-between px-4 py-3 text-xs font-semibold text-white rounded-xl bg-emerald-600/80 hover:bg-emerald-600 border border-emerald-500/40">
                                    Editar avance
                                    <span class="text-[11px] text-zinc-400">Click para abrir</span>
                                </summary>
                                <div class="border-t border-white/10 px-4 py-4 space-y-3">
                                    <form method="POST" action="{{ route('creador.proyectos.avances.update', [$actualizacion->proyecto_id, $actualizacion->id]) }}" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        @method('PATCH')
                                        <div>
                                            <label class="text-sm text-zinc-300">Titulo *</label>
                                            <input required name="titulo" value="{{ $actualizacion->titulo }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                                        </div>
                                        <div>
                                            <label class="text-sm text-zinc-300">Contenido</label>
                                            <textarea name="contenido" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">{{ $actualizacion->contenido }}</textarea>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <label class="inline-flex items-center gap-2 text-sm text-zinc-200">
                                                <input type="checkbox" name="es_hito" value="1" @checked($actualizacion->es_hito) class="h-4 w-4 rounded border-white/20 bg-zinc-900 text-indigo-500 focus:ring-indigo-400">
                                                Marcar como hito cumplido
                                            </label>
                                        </div>
                                        <div>
                                            <label class="text-sm text-zinc-300">Reemplazar adjuntos</label>
                                            <input type="file" name="adjuntos[]" multiple class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30">
                                            <p class="mt-1 text-[11px] text-zinc-500">Si adjuntas nuevos archivos, sustituiremos los actuales.</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2 pt-2">
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                                Guardar cambios
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('creador.proyectos.avances.delete', [$actualizacion->proyecto_id, $actualizacion->id]) }}" onsubmit="return confirm('?Eliminar este avance?');" class="pt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-400">Aun no hay avances publicados para este proyecto.</p>
                    @endforelse
                </div>
            </section>
        </div>
    @endif
@endsection
