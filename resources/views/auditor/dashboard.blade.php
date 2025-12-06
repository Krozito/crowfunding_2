@extends('auditor.layouts.panel')

@section('title', 'Panel de Auditor')
@section('active', 'general')
@section('back_url', '')

@section('content')
    <div class="flex justify-end px-4 sm:px-6 lg:px-8 pt-6">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
            Salir
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <div class="space-y-10 px-4 sm:px-6 lg:px-8">
        <section id="overview" class="relative overflow-hidden rounded-[22px] admin-hero px-8 py-10 shadow-2xl ring-1 ring-white/15">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.22),_transparent_45%)] blur-[2px]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Vigilancia y cumplimiento</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-wide text-white">Panel de auditoria continua</h1>
                    <p class="mt-3 max-w-2xl text-base text-white/75">
                        Monitorea solicitudes, pagos y verificaciones con datos reales. Prioriza lo pendiente y actúa con evidencia.
                    </p>
                </div>
                <div class="grid gap-3 rounded-2xl bg-white/10 p-6 text-white backdrop-blur-sm shadow-inner">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/70">Solicitudes pendientes</p>
                            <p class="text-4xl font-extrabold">{{ $kpis['solicitudes_pendientes'] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="border-t border-white/10 pt-3 flex items-center gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/70">Pagos registrados</p>
                            <p class="text-4xl font-extrabold">{{ $kpis['pagos_registrados'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="general" class="space-y-4">
            <div class="flex flex-col gap-2">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Modulo 1</p>
                <h2 class="text-2xl font-bold text-white">Panel general del auditor</h2>
                <p class="text-sm text-zinc-400">KPI y colas operativas basadas en la base de datos.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Solicitudes pendientes</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $kpis['solicitudes_pendientes'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">Desembolsos por validar</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Solicitudes aprobadas</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $kpis['solicitudes_aprobadas'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">Incluye liberadas/pagadas</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Pagos registrados</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $kpis['pagos_registrados'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">Con proveedor asociado</p>
                </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase text-zinc-400">Verificaciones pendientes</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $kpis['kyc_pendientes'] ?? 0 }}</p>
                    <p class="text-xs text-zinc-500">Solicitudes de identidad</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Desembolsos</p>
                            <h3 class="text-lg font-semibold text-white">Pendientes por validar</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">{{ $solicitudesPendientes->count() }}</span>
                    </div>
                    <div class="space-y-3 text-sm text-zinc-300">
                        @forelse ($solicitudesPendientes as $item)
                            <div class="rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <p class="font-semibold text-white">{{ $item->proyecto->titulo ?? 'Proyecto' }} - ${{ number_format($item->monto_solicitado, 0, ',', '.') }}</p>
                                <p class="text-xs text-zinc-400">{{ $item->hito ?? 'Hito' }} • {{ $item->estado }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-500">No hay solicitudes pendientes.</p>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Pagos</p>
                            <h3 class="text-lg font-semibold text-white">Últimos registrados</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">{{ $pagosRecientes->count() }}</span>
                    </div>
                    <div class="space-y-3 text-sm text-zinc-300">
                        @forelse ($pagosRecientes as $pago)
                            <div class="rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <p class="font-semibold text-white">${{ number_format($pago->monto, 0, ',', '.') }} — {{ $pago->proveedor->nombre_proveedor ?? 'Proveedor' }}</p>
                                <p class="text-xs text-zinc-400">{{ optional($pago->solicitud->proyecto)->titulo ?? 'Proyecto' }} • {{ $pago->fecha_pago?->format('Y-m-d') }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-500">No hay pagos registrados.</p>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Verificaciones</p>
                            <h3 class="text-lg font-semibold text-white">Identidad pendientes</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">{{ $verificacionesPendientes->count() }}</span>
                    </div>
                    <div class="space-y-3 text-sm text-zinc-300">
                        @forelse ($verificacionesPendientes as $verif)
                            <div class="rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <p class="font-semibold text-white">{{ $verif->user->nombre_completo ?? $verif->user->name ?? 'Usuario' }}</p>
                                <p class="text-xs text-zinc-400">{{ $verif->estado }} • {{ $verif->created_at?->format('Y-m-d') }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-500">No hay verificaciones pendientes.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyectos</p>
                            <h3 class="text-lg font-semibold text-white">Recientes</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">{{ $proyectosActivos->count() }}</span>
                    </div>
                    <div class="space-y-3 text-sm text-zinc-300">
                        @forelse ($proyectosActivos as $proy)
                            <div class="rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <p class="font-semibold text-white">{{ $proy->titulo }}</p>
                                <p class="text-xs text-zinc-400">Estado: {{ $proy->estado ?? 'N/D' }} • {{ $proy->created_at?->format('Y-m-d') }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-zinc-500">Sin proyectos registrados.</p>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Solicitudes</p>
                            <h3 class="text-lg font-semibold text-white">Vista detallada</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">{{ ($kpis['solicitudes_pendientes'] ?? 0) + ($kpis['solicitudes_aprobadas'] ?? 0) }}</span>
                    </div>
                    <p class="text-sm text-zinc-400">Consulta todas las solicitudes en el modulo de desembolsos.</p>
                    <a href="{{ route('auditor.desembolsos') }}" class="admin-btn admin-btn-primary text-xs w-fit">Ir a desembolsos</a>
                </div>
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Pagos</p>
                            <h3 class="text-lg font-semibold text-white">Detalle de comprobantes</h3>
                        </div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-white">{{ $pagosRecientes->count() }}</span>
                    </div>
                    <p class="text-sm text-zinc-400">Revisa facturas y soporte directamente.</p>
                    <a href="{{ route('auditor.comprobantes') }}" class="admin-btn admin-btn-ghost text-xs w-fit">Ir a comprobantes</a>
                </div>
            </div>
        </section>
    </div>
@endsection
