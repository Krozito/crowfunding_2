<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalle de proyecto | CrowdUp Admin</title>
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

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'proyectos'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600/20 via-zinc-900/80 to-zinc-900/70 p-6 shadow-2xl space-y-4">
                    <div class="grid gap-4 lg:grid-cols-[1.3fr,1fr]">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-200">Proyecto</p>
                            <h2 class="text-3xl font-bold text-white leading-tight">{{ $proyecto->titulo }}</h2>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-zinc-300">
                                <span class="inline-flex items-center rounded-full bg-emerald-500/15 px-2.5 py-1 text-xs font-semibold text-emerald-100">
                                    {{ strtoupper($proyecto->estado ?? 'pendiente') }}
                                </span>
                                <span>Creado {{ $proyecto->created_at?->format('d/m/Y') }}</span>
                                @if($proyecto->creador)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                        {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name }}
                                    </span>
                                    @if($proyecto->creador->estado_verificacion)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-200">
                                            <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Verificado KYC
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2.5 py-1 text-[11px] font-semibold text-amber-200">
                                            <span class="h-2 w-2 rounded-full bg-amber-300"></span> KYC pendiente
                                        </span>
                                    @endif
                                @endif
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm text-white/90">
                                <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                                    <p class="text-[11px] text-white/70">Categoría</p>
                                    <p class="font-semibold">{{ $proyecto->categoria ?? 'N/D' }}</p>
                                    <p class="text-[11px] text-white/60">Ubicación: {{ $proyecto->ubicacion_geografica ?? 'N/D' }}</p>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                                    <p class="text-[11px] text-white/70">Modelo</p>
                                    <p class="font-semibold">{{ $proyecto->modelo_financiamiento ?? 'N/D' }}</p>
                                    <p class="text-[11px] text-white/60">Fecha límite: {{ optional($proyecto->fecha_limite)->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                </div>
                            </div>
                            @if($proyecto->descripcion_proyecto)
                                <p class="text-sm text-white/80 leading-relaxed">{{ $proyecto->descripcion_proyecto }}</p>
                            @endif
                            <div class="flex flex-wrap gap-2 text-xs text-indigo-100">
                                <a href="{{ route('admin.proyectos.gastos', $proyecto) }}" class="admin-btn admin-btn-primary text-xs">Ver movimientos financieros</a>
                                <a href="{{ route('admin.proveedores') }}?proyecto={{ $proyecto->id }}" class="admin-btn admin-btn-ghost text-xs">Pagos a proveedores</a>
                                <a href="{{ route('auditor.reportes') }}?q={{ urlencode($proyecto->titulo) }}" class="admin-btn admin-btn-ghost text-xs">Reportes sospechosos</a>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @php
                                $kpis = [
                                    ['label' => 'Meta', 'value' => 'US$ '.number_format($proyecto->meta_financiacion, 2)],
                                    ['label' => 'Recaudado', 'value' => 'US$ '.number_format($stats['total_recaudado'] ?? 0, 2)],
                                    ['label' => '% Avance', 'value' => $proyecto->meta_financiacion > 0 ? round(($stats['total_recaudado'] ?? 0)/$proyecto->meta_financiacion*100).'%' : '0%'],
                                    ['label' => 'Fondos retenidos', 'value' => 'US$ '.number_format($fondos['retenidos'] ?? 0, 2)],
                                    ['label' => 'Fondos liberados', 'value' => 'US$ '.number_format($fondos['liberados'] ?? 0, 2)],
                                    ['label' => 'Fondos gastados', 'value' => 'US$ '.number_format($fondos['gastado'] ?? 0, 2)],
                                ];
                            @endphp
                            @foreach ($kpis as $kpi)
                                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-white">
                                    <p class="text-[11px] uppercase tracking-[0.3em] text-white/70">{{ $kpi['label'] }}</p>
                                    <p class="mt-2 text-2xl font-extrabold">{{ $kpi['value'] }}</p>
                                </div>
                            @endforeach
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-white sm:col-span-2">
                                <p class="text-[11px] uppercase tracking-[0.3em] text-white/70">Transparencia</p>
                                <p class="mt-2 text-2xl font-extrabold">{{ $riesgos['transparencia'] ?? 0 }}%</p>
                                <p class="text-xs text-white/80">Fondos con comprobantes/gastos</p>
                            </div>
                            <div class="rounded-2xl border border-amber-400/40 bg-amber-500/10 p-4 text-amber-100">
                                <p class="text-[11px] uppercase tracking-[0.3em]">Reportes sospechosos</p>
                                <p class="mt-2 text-2xl font-extrabold">{{ $riesgos['reportes_abiertos'] ?? 0 }}</p>
                                <p class="text-xs text-amber-100/90">Abiertos de {{ $riesgos['reportes_totales'] ?? 0 }} totales</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-3">
                    <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Movimientos recientes</p>
                                <h3 class="text-lg font-semibold text-white">Aportes, desembolsos y pagos</h3>
                                <p class="text-xs text-zinc-500">Últimos 5 registros</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <p class="text-[12px] uppercase tracking-[0.28em] text-zinc-500">Aportes</p>
                                <div class="mt-2 divide-y divide-white/5 rounded-xl border border-white/10 bg-white/5">
                                    @forelse($aportacionesRecientes as $aporte)
                                        <div class="flex items-center justify-between px-4 py-3">
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
                                        <p class="py-3 text-sm text-zinc-400 text-center">Sin aportaciones registradas.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <p class="text-[12px] uppercase tracking-[0.28em] text-zinc-500">Desembolsos</p>
                                <div class="mt-2 divide-y divide-white/5 rounded-xl border border-white/10 bg-white/5">
                                    @forelse($desembolsosRecientes as $sol)
                                        <div class="flex items-center justify-between px-4 py-3">
                                            <div class="space-y-1">
                                                <p class="text-sm text-white">{{ $sol->hito ?? 'Hito' }}</p>
                                                <p class="text-xs text-zinc-400">Estado {{ ucfirst($sol->estado) }} • {{ $sol->created_at?->format('d/m/Y') }}</p>
                                            </div>
                                            <p class="text-sm font-semibold text-amber-200">US$ {{ number_format($sol->monto_solicitado, 2) }}</p>
                                        </div>
                                    @empty
                                        <p class="py-3 text-sm text-zinc-400 text-center">Sin desembolsos registrados.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <p class="text-[12px] uppercase tracking-[0.28em] text-zinc-500">Pagos a proveedores</p>
                                <div class="mt-2 divide-y divide-white/5 rounded-xl border border-white/10 bg-white/5">
                                    @forelse($pagosRecientes as $pago)
                                        <div class="flex items-center justify-between px-4 py-3">
                                            <div class="space-y-1">
                                                <p class="text-sm text-white">{{ $pago->proveedor->nombre_proveedor ?? 'Proveedor' }}</p>
                                                <p class="text-xs text-zinc-400">Fecha {{ $pago->fecha_pago?->format('d/m/Y') }}</p>
                                            </div>
                            <div class="text-right">
                                                <p class="text-sm font-semibold text-emerald-200">US$ {{ number_format($pago->monto, 2) }}</p>
                                                <p class="text-xs text-zinc-400">{{ ucfirst($pago->estado_auditoria ?? 'pendiente') }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="py-3 text-sm text-zinc-400 text-center">Sin pagos registrados.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Riesgos y auditoría</p>
                                <h3 class="text-lg font-semibold text-white">Estado de cumplimiento</h3>
                            </div>
                            <a href="{{ route('admin.proyectos.gastos', $proyecto) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2 text-xs font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]">
                                Panel financiero
                            </a>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-white">Reportes sospechosos</p>
                                    <p class="text-xs text-zinc-400">Abiertos / Totales</p>
                                </div>
                                <p class="text-xl font-bold text-amber-200">{{ $riesgos['reportes_abiertos'] ?? 0 }} / {{ $riesgos['reportes_totales'] ?? 0 }}</p>
                            </div>
                            <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                <div>
                                    <p class="text-sm font-semibold text-white">Gastos observados/rechazados</p>
                                    <p class="text-xs text-zinc-400">Pagos con incidencia</p>
                                </div>
                                <p class="text-xl font-bold text-red-200">{{ $riesgos['pagos_observados'] ?? 0 }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3">
                                <p class="text-sm font-semibold text-white">Transparencia</p>
                                <p class="text-2xl font-bold text-emerald-200">{{ $riesgos['transparencia'] ?? 0 }}%</p>
                                <p class="text-xs text-zinc-400">Fondos con comprobantes/gastos</p>
                                <div class="mt-2 h-2 w-full rounded-full bg-white/10">
                                    <div class="h-full rounded-full bg-emerald-400" style="width: {{ min($riesgos['transparencia'] ?? 0, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>







