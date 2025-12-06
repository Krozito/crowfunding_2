@extends('colaborador.layouts.panel')

@section('title', 'Panel de Colaborador')
@section('active', 'dashboard')

@section('content')
    <section class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600/30 via-sky-500/20 to-emerald-500/25 p-8 shadow-2xl ring-1 ring-indigo-500/10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.12),_transparent_45%)]"></div>
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-100">Transparencia</p>
                <h2 class="text-3xl font-bold text-white">Sigue el impacto de tus aportes</h2>
                <p class="text-sm text-indigo-100 max-w-2xl">
                    Revisa los proyectos que apoyas, el monto aportado y cómo se usan los fondos. Próximamente podrás ver más módulos desde este panel.
                </p>
                <div class="flex flex-wrap gap-2 text-xs text-white/80">
                    <span class="rounded-full bg-white/10 px-3 py-1">Trazabilidad</span>
                    <span class="rounded-full bg-white/10 px-3 py-1">Reportes claros</span>
                    <span class="rounded-full bg-white/10 px-3 py-1">Acceso seguro</span>
                </div>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 text-sm text-white">
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4 shadow-inner">
                    <p class="text-[11px] uppercase tracking-[0.3em] text-indigo-100">Total aportado</p>
                    <p class="mt-2 text-2xl font-bold">${{ number_format($metrics['totalAportado'] ?? 0, 2) }}</p>
                </div>
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4 shadow-inner">
                    <p class="text-[11px] uppercase tracking-[0.3em] text-indigo-100">Proyectos apoyados</p>
                    <p class="mt-2 text-2xl font-bold">{{ $metrics['numProyectos'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-white/15 bg-white/10 p-4 shadow-inner">
                    <p class="text-[11px] uppercase tracking-[0.3em] text-indigo-100">Aportaciones</p>
                    <p class="mt-2 text-2xl font-bold">{{ $metrics['numAportaciones'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                <h3 class="text-lg font-semibold text-white">Proyectos que estás apoyando</h3>
                <p class="text-sm text-zinc-400">Resumen rápido de tus aportes y estado de cada campaña.</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-zinc-950/50 p-4">
            @if(isset($proyectosAportados) && $proyectosAportados->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-zinc-400 border-b border-white/10">
                                <th class="py-3 pr-4">Proyecto</th>
                                <th class="py-3 pr-4">Meta</th>
                                <th class="py-3 pr-4">Recaudado</th>
                                <th class="py-3 pr-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-white">
                            @foreach($proyectosAportados as $proyecto)
                                <tr class="hover:bg-white/5">
                                    <td class="py-3 pr-4 font-semibold">{{ $proyecto->titulo ?? 'Proyecto' }}</td>
                                    <td class="py-3 pr-4">${{ number_format($proyecto->meta_financiacion ?? 0, 2) }}</td>
                                    <td class="py-3 pr-4">${{ number_format($proyecto->monto_recaudado ?? 0, 2) }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex items-center rounded-full bg-indigo-500/20 px-3 py-1 text-[11px] font-semibold text-indigo-100">
                                            {{ strtoupper($proyecto->estado ?? 'EN PROGRESO') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-zinc-300">Todavía no has realizado aportaciones. Explora proyectos y apoya tu primera campaña.</p>
            @endif
        </div>
    </section>
@endsection
