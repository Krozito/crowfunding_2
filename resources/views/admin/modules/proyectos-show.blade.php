<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalle de proyecto | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-32 top-0 h-72 w-72 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/15 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.proyectos') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a proyectos
                </a>
                <h1 class="text-lg font-semibold text-white">Detalle de proyecto</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600/20 via-zinc-900/80 to-zinc-900/70 p-8 shadow-2xl">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-200">Proyecto</p>
                    <h2 class="text-3xl font-bold text-white leading-tight">{{ $proyecto->titulo }}</h2>
                    <div class="flex items-center gap-3 text-sm text-zinc-300">
                        <span class="inline-flex items-center rounded-full bg-indigo-500/15 px-2.5 py-1 text-xs font-semibold text-indigo-100">
                            {{ strtoupper($proyecto->estado ?? 'pendiente') }}
                        </span>
                        <span>Creado {{ $proyecto->created_at?->format('d/m/Y') }}</span>
                        @if($proyecto->creador)
                            <span class="inline-flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Meta</p>
                        <p class="text-lg font-semibold text-white">US$ {{ number_format($proyecto->meta_financiacion, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Recaudado</p>
                        <p class="text-lg font-semibold text-white">US$ {{ number_format($stats['total_recaudado'] ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Aportes</p>
                        <p class="text-lg font-semibold text-white">{{ $stats['aportaciones'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Liberado</p>
                        <p class="text-lg font-semibold text-emerald-100">US$ {{ number_format($fondos['liberados'] ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Retenido</p>
                        <p class="text-lg font-semibold text-amber-100">US$ {{ number_format($fondos['retenidos'] ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>
            @if($proyecto->descripcion_proyecto)
                <p class="mt-4 text-sm text-zinc-200 leading-relaxed">{{ $proyecto->descripcion_proyecto }}</p>
            @endif
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Aportaciones recientes</p>
                        <h3 class="text-lg font-semibold text-white">Ultimos movimientos</h3>
                    </div>
                </div>
                <div class="mt-4 divide-y divide-white/5">
                    @forelse($aportacionesRecientes as $aporte)
                        <div class="flex items-center justify-between py-3">
                            <div class="space-y-1">
                                <p class="text-sm text-white">{{ $aporte->colaborador->nombre_completo ?? $aporte->colaborador->name ?? 'Colaborador' }}</p>
                                <p class="text-xs text-zinc-400">Fecha {{ $aporte->fecha_aportacion?->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-emerald-300">+ US$ {{ number_format($aporte->monto, 2) }}</p>
                                <p class="text-xs text-zinc-400">{{ strtoupper($aporte->estado_pago) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-sm text-zinc-400 text-center">Sin aportaciones registradas.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl space-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Top inversionistas</p>
                    <h3 class="text-lg font-semibold text-white">Mayores aportantes</h3>
                </div>
                <div class="space-y-3">
                    @forelse($topInversionistas as $i => $inv)
                        <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-white">
                                    #{{ $i + 1 }} {{ $inv->colaborador->nombre_completo ?? $inv->colaborador->name ?? 'Colaborador' }}
                                </p>
                                <p class="text-xs text-zinc-400">{{ $inv->aportes }} aportes</p>
                            </div>
                            <p class="text-sm font-semibold text-emerald-300">US$ {{ number_format($inv->total, 2) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">Sin inversionistas todavia.</p>
                    @endforelse
                </div>
            </div>
        </section>

    </main>
</body>
</html>
