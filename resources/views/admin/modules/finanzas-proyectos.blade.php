<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fondos por proyecto | CrowdUp Admin</title>
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
                <a href="{{ route('admin.finanzas') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a finanzas
                </a>
                <h1 class="text-lg font-semibold text-white">Fondos retenidos por proyecto</h1>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-300">Monitor</span>
            </div>
        </div>
    </header>

    @php
        $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
    @endphp
    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6 space-y-8">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'finanzas'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Custodia</p>
                    <h2 class="text-2xl font-bold text-white">Fondos retenidos y liberados</h2>
                    <p class="text-sm text-zinc-400">Detecta proyectos sin desembolsos o con posibles irregularidades.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-zinc-200">Proyectos: {{ $filas->count() }}</span>
                    <a href="{{ route('admin.finanzas.solicitudes') }}" class="{{ $btnSolid }}">Solicitudes</a>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @forelse ($filas as $fila)
                    @php
                        $p = $fila['proyecto'];
                        $pend = $fila['pendiente'];
                        $alerta = $pend > 0 && $fila['liberado'] == 0;
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-5 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-lg font-semibold text-white">{{ $p->titulo }}</p>
                                <p class="text-xs text-zinc-400">Creador: {{ $p->creador->nombre_completo ?? $p->creador->name ?? 'N/D' }}</p>
                                <p class="text-[11px] text-zinc-500">Estado: {{ strtoupper($p->estado ?? 'pendiente') }}</p>
                            </div>
                            @if ($alerta)
                                <span class="rounded-full bg-amber-500/15 px-3 py-1 text-[11px] font-semibold text-amber-100">Pendiente sin liberar</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-zinc-200">
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                <p class="text-[11px] text-zinc-500">Recaudado</p>
                                <p class="font-semibold">US$ {{ number_format($fila['recaudado'], 2) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                <p class="text-[11px] text-zinc-500">Retenido</p>
                                <p class="font-semibold text-amber-100">US$ {{ number_format($fila['retenido'], 2) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                <p class="text-[11px] text-zinc-500">Liberado</p>
                                <p class="font-semibold text-emerald-100">US$ {{ number_format($fila['liberado'], 2) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                <p class="text-[11px] text-zinc-500">Pendiente</p>
                                <p class="font-semibold text-indigo-100">US$ {{ number_format($fila['pendiente'], 2) }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <a href="{{ route('admin.proyectos.show', $p) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]">Ver proyecto</a>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-zinc-400">No hay proyectos cargados.</p>
                @endforelse
            </div>
        </section>
            </div>
        </div>
    </main>
</body>
</html>








