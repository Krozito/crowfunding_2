<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Finanzas | CrowdUp Admin</title>
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
                <h1 class="text-lg font-semibold text-white">Finanzas globales</h1>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            @php
                $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
            @endphp
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'finanzas'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4 admin-accent-card">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Estado general</p>
                            <h2 class="text-2xl font-bold text-white">Flujo global de la plataforma</h2>
                            <p class="text-sm text-zinc-400">Supervisa recaudado, escrow, liberado, gastado y pendiente.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <a href="{{ route('admin.finanzas.proyectos') }}" class="{{ $btnSolid }}">Fondos por proyecto</a>
                            <a href="{{ route('admin.finanzas.solicitudes') }}" class="{{ $btnSolid }}">Solicitudes</a>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-6">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Total recaudado</p>
                            <p class="text-2xl font-bold text-white">USD {{ number_format($stats['recaudado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Retenido en custodia</p>
                            <p class="text-xl font-bold text-amber-100">USD {{ number_format($stats['retenido'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Liberado a creadores</p>
                            <p class="text-xl font-bold text-emerald-100">USD {{ number_format($stats['liberado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Gastado y validado</p>
                            <p class="text-xl font-bold text-sky-100">USD {{ number_format($stats['gastado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Pendiente de liberar</p>
                            <p class="text-xl font-bold text-indigo-100">USD {{ number_format($stats['pendiente'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Fondos disponibles</p>
                            <p class="text-2xl font-bold text-emerald-200">USD {{ number_format($stats['disponible'], 2) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-zinc-300">
                        <p class="text-sm font-semibold text-white">Reportes globales</p>
                        <div class="mt-2 grid gap-2 sm:grid-cols-2 lg:grid-cols-4 text-xs">
                            <a href="{{ route('admin.finanzas.export.retenidos') }}" class="{{ $btnSolid }} justify-start text-left">Descargar fondos retenidos (Excel)</a>
                            <a href="{{ route('admin.finanzas.export.liberados') }}" class="{{ $btnSolid }} justify-start text-left">Descargar fondos liberados (Excel)</a>
                            <a href="{{ route('admin.finanzas.export.recaudacion.mensual') }}" class="{{ $btnSolid }} justify-start text-left">Recaudacion por mes/año (Excel)</a>
                            <a href="{{ route('admin.finanzas.export.recaudacion.categoria') }}" class="{{ $btnSolid }} justify-start text-left">Reporte por categoria (Excel)</a>
                        </div>
                        <p class="mt-2 text-[11px] text-zinc-500">Los reportes se generan como hojas Excel.</p>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>








