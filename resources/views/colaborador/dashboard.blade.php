@extends('colaborador.layouts.panel')

@section('title', 'Panel de colaborador')
@section('active', 'general')

@section('content')
<div id="general" class="px-4 pt-6 pb-10 lg:px-8 space-y-8">

    {{-- Hero / resumen --}}
    <section class="admin-hero rounded-2xl p-5 sm:p-6 lg:p-6 text-white flex flex-col sm:flex-row sm:items-center gap-6">
        <div class="flex-1 space-y-3 max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100/80">
                Hola, {{ Auth::user()->nombre_completo ?? Auth::user()->name }}
            </p>
            <h1 class="text-2xl sm:text-3xl font-bold leading-tight">
                Tu aporte impulsa sueños.<br class="hidden sm:block" /> Encuentra el próximo proyecto que quieres hacer posible.
            </h1>
            <p class="text-sm text-indigo-50/90 max-w-2xl">
                Descubre iniciativas que pueden cambiar vidas y deja tu huella apoyando a quienes construyen el futuro.
            </p>
            <div class="inline-flex items-center gap-2 rounded-xl bg-black/20 px-3 py-2 text-[11px] font-semibold uppercase tracking-wide text-indigo-50 ring-1 ring-white/15">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 0V4m0 8v4m-5 0h10" />
                </svg>
                Impacto colaborador
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-[#0f172a]/80 p-4 sm:w-64 space-y-3 shadow-[0_12px_35px_rgba(15,23,42,0.6)]">
            <p class="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100/70">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Tu impacto
            </p>
            <div class="space-y-2 text-sm">
                <p class="flex items-center justify-between text-indigo-50/90">
                    <span>Proyectos apoyados</span>
                    <span class="font-semibold text-white text-base">{{ $metrics['numProyectos'] ?? 0 }}</span>
                </p>
                <p class="flex items-center justify-between text-indigo-50/90">
                    <span>Aportaciones</span>
                    <span class="font-semibold text-white text-base">{{ $metrics['numAportaciones'] ?? 0 }}</span>
                </p>
                <p class="flex items-center justify-between text-indigo-50/90">
                    <span>Total aportado</span>
                    <span class="font-semibold text-emerald-300 text-base">
                        ${{ number_format($metrics['totalAportado'] ?? 0, 0, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>
    </section>

    {{-- Grid de proyectos para explorar --}}
    <section class="space-y-4">
        <div class="flex items-center justify-between gap-2">
            <div>
                <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
                    Explorar proyectos
                </h2>
                <p class="text-xs text-zinc-400">
                    Todos los proyectos disponibles en la plataforma
                </p>
            </div>
        </div>

        <form method="GET" action="{{ route('colaborador.dashboard') }}" class="rounded-2xl border border-white/5 bg-black/30 p-4 grid gap-3 md:grid-cols-[1.3fr,1fr,auto] md:items-end">
            <div>
                <label class="text-xs text-zinc-400">Busqueda</label>
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Titulo, categoria o ubicacion"
                    class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400"
                >
            </div>
            <div>
                <label class="text-xs text-zinc-400">Categoria</label>
                <select
                    name="categoria"
                    class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400"
                >
                    <option value="">Todas</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat }}" @selected(($categoria ?? '') === $cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="admin-btn admin-btn-primary w-full justify-center">
                    Filtrar
                </button>
                <a href="{{ route('colaborador.dashboard') }}" class="admin-btn admin-btn-ghost">
                    Limpiar
                </a>
            </div>
        </form>

        @php
            $hayFiltros = filled($search) || filled($categoria);
        @endphp

        @if ($proyectosExplorar->isEmpty())
            <div class="admin-accent-card rounded-2xl border border-white/5 p-6 text-sm text-zinc-300">
                <p class="font-medium text-zinc-100 mb-1">
                    @if ($hayFiltros)
                        No encontramos proyectos con estos filtros.
                    @else
                        Aun no hay proyectos publicados.
                    @endif
                </p>
                <p class="text-xs text-zinc-400">
                    @if ($hayFiltros)
                        Ajusta la busqueda o cambia la categoria para ver mas resultados.
                    @else
                        Cuando existan proyectos activos, apareceran aqui para que puedas apoyarlos.
                    @endif
                </p>
            </div>
        @else
            <div class="grid gap-7 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($proyectosExplorar as $proyecto)
                    @php
                        $meta = $proyecto->meta_financiacion ?: 1;
                        $recaudado = $proyecto->monto_recaudado ?? 0;
                        $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
                        $yaAporto = $proyecto->aportaciones->count() > 0;
                        $fechaLimite = $proyecto->fecha_limite ?? null;
                        $estaPorTerminar = $fechaLimite ? $fechaLimite->isBefore(now()->addDays(5)) : false;
                        $badge = null;
                        if ($progreso >= 80) {
                            $badge = 'Popular';
                        } elseif ($progreso >= 60) {
                            $badge = 'Recomendado';
                        } elseif ($estaPorTerminar) {
                            $badge = 'Ultimos dias';
                        }
                        $categoriaClass = match (strtolower($proyecto->categoria ?? '')) {
                            'tecnologia' => 'bg-sky-500/15 text-sky-50 border border-sky-400/40',
                            'arte', 'cultura' => 'bg-pink-500/15 text-pink-50 border border-pink-400/40',
                            'social', 'impacto' => 'bg-emerald-500/15 text-emerald-50 border border-emerald-400/40',
                            default => 'bg-indigo-500/15 text-indigo-50 border border-indigo-400/40',
                        };
                        $imagenPortada = $proyecto->imagen_portada
                            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                            : 'data:image/svg+xml,%3Csvg xmlns%3D%22http%3A//www.w3.org/2000/svg%22 width%3D%22720%22 height%3D%22480%22 viewBox%3D%220 0 720 480%22%3E%3Cdefs%3E%3ClinearGradient id%3D%22g%22 x1%3D%220%25%22 y1%3D%22100%25%22 x2%3D%22100%25%22 y2%3D%220%25%22%3E%3Cstop offset%3D%220%25%22 stop-color%3D%22%230b1220%22/%3E%3Cstop offset%3D%2250%25%22 stop-color%3D%22%23132938%22/%3E%3Cstop offset%3D%22100%25%22 stop-color%3D%22%231c3650%22/%3E%3C/linearGradient%3E%3CradialGradient id%3D%22b%22 cx%3D%2270%25%22 cy%3D%2230%25%22 r%3D%2235%25%22%3E%3Cstop offset%3D%220%25%22 stop-color%3D%22%235eead4%22 stop-opacity%3D%220.6%22/%3E%3Cstop offset%3D%22100%25%22 stop-color%3D%22%235eead4%22 stop-opacity%3D%220%22/%3E%3C/radialGradient%3E%3C/defs%3E%3Crect width%3D%22720%22 height%3D%22480%22 fill%3D%22url(%23g)%22/%3E%3Crect width%3D%22720%22 height%3D%22480%22 fill%3D%22url(%23b)%22/%3E%3C/svg%3E';
                    @endphp

                    <article class="rounded-3xl border border-white/10 bg-zinc-950/75 p-4 shadow-[0_12px_45px_rgba(0,0,0,0.35)] ring-1 ring-indigo-500/10 flex flex-col gap-4">
                        <div class="relative overflow-hidden rounded-xl border border-white/10 bg-zinc-900/70 aspect-[4/3]">
                            <img src="{{ $imagenPortada }}" alt="Portada del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/35 to-transparent"></div>
                            <div class="absolute top-3 left-3 flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold {{ $categoriaClass }} backdrop-blur-sm bg-black/40 border-white/20">
                                    {{ ucfirst($proyecto->categoria ?? 'Proyecto') }}
                                </span>
                                @if ($badge)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/85 text-amber-950 px-3 py-1 text-[11px] font-extrabold shadow-lg shadow-amber-500/30">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        {{ $badge }}
                                    </span>
                                @endif
                            </div>
                            @if ($yaAporto)
                                <span class="absolute bottom-3 left-3 inline-flex items-center gap-1 rounded-full bg-emerald-500/90 text-emerald-950 px-3 py-1 text-[11px] font-semibold shadow-lg shadow-emerald-500/30">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-900"></span>
                                    Ya has aportado
                                </span>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-xl font-bold text-white leading-tight line-clamp-2">
                                {{ $proyecto->titulo }}
                            </h3>
                            <p class="text-[13px] text-zinc-300 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit(
                                    $proyecto->descripcion_corta ?? $proyecto->descripcion_proyecto,
                                    110
                                ) }}
                            </p>
                            <p class="text-[12px] text-zinc-500">
                                Por
                                <span class="text-zinc-200 font-medium">
                                    {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'Creador' }}
                                </span>
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-[12px] text-zinc-300">
                                <span class="font-semibold">Progreso</span>
                                <span class="text-base font-bold text-sky-200">{{ $progreso }}%</span>
                            </div>
                            <div class="h-2.5 w-full rounded-full bg-zinc-900/80 overflow-hidden ring-1 ring-white/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-emerald-400 shadow-[0_0_12px_rgba(56,189,248,0.45)]"
                                     style="width: {{ $progreso }}%;"></div>
                            </div>
                            <div class="flex items-start justify-between text-sm text-zinc-200">
                                <div class="space-y-1">
                                    <p class="text-[11px] uppercase tracking-wide text-zinc-500">Recaudado</p>
                                    <p class="font-semibold text-white text-base">${{ number_format($recaudado, 0, ',', '.') }}</p>
                                </div>
                                <div class="space-y-1 text-right">
                                    <p class="text-[11px] uppercase tracking-wide text-zinc-500">Meta</p>
                                    <p class="font-semibold text-white text-base">${{ number_format($meta, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex">
                            <a href="{{ route('colaborador.proyectos.show', $proyecto) }}"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-indigo-700 px-4 py-2.5 text-xs font-bold uppercase tracking-wide text-white shadow-[0_10px_24px_rgba(67,56,202,0.4)] hover:bg-indigo-600 transition-colors">
                                Ver detalles
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Paginacion --}}
            <div class="mt-4">
                {{ $proyectosExplorar->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
