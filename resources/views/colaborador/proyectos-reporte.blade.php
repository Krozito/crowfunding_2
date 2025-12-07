@extends('colaborador.layouts.panel')

@section('title', 'Reporte de pagos')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos.resumen', $proyecto))
@section('back_label', 'Volver al resumen')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-6">
    <header class="space-y-1">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Pagos</p>
        <h1 class="text-2xl font-bold text-white">Reporte de pagos del proyecto</h1>
        <p class="text-sm text-zinc-400">Detalles de aportes registrados para {{ $proyecto->titulo }}.</p>
    </header>

    <div class="rounded-3xl border border-white/10 bg-zinc-900/70 shadow-xl p-6 space-y-4">
        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Total aportado</p>
                <p class="text-xl font-bold text-white">${{ number_format($total, 2) }}</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Aportes</p>
                <p class="text-xl font-bold text-white">{{ $aportaciones->count() }}</p>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Último aporte</p>
                <p class="text-sm font-semibold text-white">
                    {{ optional($aportaciones->max('fecha_aportacion'))?->format('d/m/Y H:i') ?? 'N/D' }}
                </p>
            </div>
        </div>

        @if($aportaciones->isEmpty())
            <p class="text-sm text-zinc-300">No hay aportaciones registradas.</p>
        @else
            <div class="space-y-3">
                @foreach($aportaciones as $aporte)
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white flex flex-col gap-2">
                        <div class="flex items-center justify-between text-xs text-zinc-400">
                            <span>ID: {{ $aporte->id }}</span>
                            <span>{{ optional($aporte->fecha_aportacion)->format('d/m/Y H:i') ?? $aporte->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="font-semibold">${{ number_format($aporte->monto, 2) }}</p>
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-3 py-1 text-[11px] font-semibold text-emerald-200 border border-emerald-400/40">
                                {{ strtoupper($aporte->estado_pago ?? 'PAGADO') }}
                            </span>
                        </div>
                        <p class="text-xs text-zinc-300">Transacción: {{ $aporte->id_transaccion_pago ?? '-' }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
