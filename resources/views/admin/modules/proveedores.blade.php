<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proveedores | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden bg-zinc-950">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/30 blur-2xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/25 blur-2xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al dashboard
                </a>
                <h1 class="text-lg font-semibold text-white">Proveedores</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6 space-y-8">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'proveedores'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4 admin-accent-card">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Network</p>
                    <h2 class="text-2xl font-bold text-white">Directorio y performance</h2>
                    <p class="text-sm text-zinc-400">Audita proveedores, rating y vinculacion a proyectos.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs text-zinc-300"></div>
            </div>

            <form method="GET" action="{{ route('admin.proveedores') }}" class="grid gap-3 sm:grid-cols-[1.5fr,1fr,1fr,auto] sm:items-end">
                <div>
                    <label class="text-xs text-zinc-400">Buscar</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nombre, especialidad, contacto" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-xs text-zinc-400">Proyecto</label>
                    <select name="proyecto" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                        <option value="">Todos</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" @selected($proyectoFiltro == $proyecto->id)>{{ $proyecto->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    @php
                        $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
                    @endphp
                    <button type="submit" class="{{ $btnSolid }}">Filtrar</button>
                    <a href="{{ route('admin.proveedores') }}" class="admin-btn admin-btn-ghost">Limpiar</a>
                </div>
            </form>
        </section>

        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Listado</p>
                    <h3 class="text-lg font-semibold text-white">Proveedores registrados</h3>
                </div>
                <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Mostrando {{ $proveedores->total() }}</span>
            </div>

            <div class="grid gap-3 lg:grid-cols-2">
                @forelse ($proveedores as $prov)
                    @php
                        $cal = $prov->calificacion_promedio;
                        $color = $cal === null ? 'text-zinc-300' : ($cal < 5 ? 'text-red-200' : ($cal == 5 ? 'text-amber-200' : 'text-emerald-200'));
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-2">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $prov->nombre_proveedor }}</p>
                                <p class="text-xs text-zinc-400">Especialidad: {{ $prov->especialidad ?? 'N/D' }}</p>
                                <p class="text-xs text-zinc-500">Contacto: {{ $prov->info_contacto ?? 'N/D' }}</p>
                                <p class="text-[11px] text-zinc-500">Proyecto: {{ $prov->proyecto->titulo ?? 'Sin proyecto' }}</p>
                                <p class="text-[11px] text-zinc-500">Creador: {{ $prov->creador->nombre_completo ?? $prov->creador->name ?? 'N/D' }}</p>
                            </div>
                            <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold {{ $color }}">
                                {{ $cal !== null ? number_format($cal, 1) . '/10' : 'N/D' }}
                            </span>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-zinc-400">No hay proveedores con estos filtros.</p>
                @endforelse
            </div>

            <div class="border-t border-white/5 pt-3 text-right text-xs text-zinc-400">
                {{ $proveedores->links() }}
            </div>
        </section>
            </div>
        </div>
    </main>
</body>
</html>








