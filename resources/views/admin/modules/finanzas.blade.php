@extends('admin.layouts.panel')

@section('title', 'Finanzas')
@section('active', 'finanzas')

@section('content')
    @php
        $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
    @endphp

    <div class="space-y-8">
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

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Total recaudado</p>
                    <p class="mt-1 text-3xl font-bold text-white">US$ {{ number_format($stats['recaudado'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Fondos retenidos (escrow)</p>
                    <p class="mt-1 text-3xl font-bold text-white">US$ {{ number_format($stats['retenido'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Fondos liberados</p>
                    <p class="mt-1 text-3xl font-bold text-white">US$ {{ number_format($stats['liberado'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Gasto reportado</p>
                    <p class="mt-1 text-3xl font-bold text-white">US$ {{ number_format($stats['gastado'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Pendiente de liberar</p>
                    <p class="mt-1 text-3xl font-bold text-white">US$ {{ number_format($stats['pendiente'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Disponible</p>
                    <p class="mt-1 text-3xl font-bold text-white">US$ {{ number_format($stats['disponible'] ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-2">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Detalle recaudado</p>
                    <p class="text-sm text-zinc-400">Aportes de colaboradores según proyectos y campañas activas.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-2">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Liberaciones</p>
                    <p class="text-sm text-zinc-400">Incluye liberado/aprobado/pagado/gastado en solicitudes.</p>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Exportes rápidos</p>
                    <h3 class="text-lg font-semibold text-white">Reportes en Excel</h3>
                    <p class="text-sm text-zinc-400">Descarga la información financiera consolidada.</p>
                </div>
                <div class="flex flex-col gap-2 text-sm text-zinc-300">
                    <a href="{{ route('admin.finanzas.export.retenidos') }}" class="{{ $btnSolid }} justify-start text-left">Descargar fondos retenidos (Excel)</a>
                    <a href="{{ route('admin.finanzas.export.liberados') }}" class="{{ $btnSolid }} justify-start text-left">Descargar fondos liberados (Excel)</a>
                    <a href="{{ route('admin.finanzas.export.recaudacion.mensual') }}" class="{{ $btnSolid }} justify-start text-left">Recaudacion por mes/año (Excel)</a>
                    <a href="{{ route('admin.finanzas.export.recaudacion.categoria') }}" class="{{ $btnSolid }} justify-start text-left">Reporte por categoria (Excel)</a>
                </div>
                <p class="mt-2 text-[11px] text-zinc-500">Los reportes se generan como hojas Excel.</p>
            </div>
        </section>
    </div>
@endsection
