@extends('creator.layouts.panel')

@section('title', 'Proyectos')
@section('active', 'proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-300">Campanas</p>
                    <h2 class="mt-1 text-2xl font-bold text-white">Tus proyectos</h2>
                    <p class="text-sm text-zinc-300">Revisa lo que has creado, publica nuevos y actualiza los existentes.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('creador.proyectos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                        Publicar nuevo proyecto
                    </a>
                    
                </div>
            </div>
            <form method="GET" action="{{ route('creador.proyectos') }}" class="mt-4 grid gap-3 sm:grid-cols-[1.2fr,0.6fr,auto] sm:items-end">
                <div>
                    <label class="text-xs text-zinc-300">Buscar</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Titulo, descripcion, categoria" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-xs text-zinc-300">Estado</label>
                    <select name="estado" class="mt-1 w-full rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Todos</option>
                        <option value="borrador" @selected(($estado ?? '') === 'borrador')>Borrador</option>
                        <option value="publicado" @selected(($estado ?? '') === 'publicado')>Publicado</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                        Filtrar
                    </button>
                    <a href="{{ route('creador.proyectos') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white hover:border-indigo-400/60">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        <section id="project-list" class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Listado</p>
                    <h3 class="text-lg font-semibold text-white">Borradores y publicados</h3>
                </div>
                <a href="{{ route('creador.proyectos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                    + Nuevo
                </a>
            </div>

            <div class="grid gap-4">
                @forelse ($proyectos as $proyecto)
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-white">{{ $proyecto->titulo }}</p>
                                <p class="text-xs text-zinc-400">Estado: {{ strtoupper($proyecto->estado ?? 'borrador') }}</p>
                                <p class="text-[11px] text-zinc-500">Modelo: {{ $proyecto->modelo_financiamiento ?? 'N/D' }} · Categoria: {{ $proyecto->categoria ?? 'N/D' }}</p>
                                <p class="text-[11px] text-zinc-500">Meta: ${{ number_format($proyecto->meta_financiacion, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('creador.proyectos.edit', $proyecto) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                    Actualizar
                                </a>
                            </div>
                        </div>
                        @if($proyecto->imagen_portada)
                            <div class="mt-3">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada) }}" alt="Portada" class="h-32 w-full rounded-xl object-cover">
                            </div>
                        @endif
                        <p class="mt-2 text-xs text-zinc-400 line-clamp-2">{{ $proyecto->descripcion_proyecto ?: 'Sin descripcion' }}</p>
                    </article>
                @empty
                    <p class="text-sm text-zinc-400">Aun no tienes proyectos. Crea el primero con “Publicar nuevo proyecto”.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
