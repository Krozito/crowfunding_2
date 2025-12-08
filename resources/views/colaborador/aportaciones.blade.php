@extends('colaborador.layouts.panel')

@section('active', 'aportaciones')
@section('title', 'Tus aportaciones')

@section('content')
<section class="p-8 space-y-6">
    <header class="mb-4">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">
            Aportaciones
        </p>
        <h1 class="text-2xl font-bold text-white mt-2">
            Historial de aportaciones
        </h1>
        <p class="text-sm text-zinc-400 mt-1">
            Revisa el detalle de cada aporte que has realizado.
        </p>
    </header>

    <form method="GET" action="{{ route('colaborador.aportaciones') }}" class="rounded-3xl border border-white/15 bg-[#030712] p-4 grid gap-3 md:grid-cols-[1.2fr,1fr,1fr,auto] md:items-end shadow-[0_20px_50px_rgba(0,0,0,0.5)] relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-0.5 bg-sky-500/70"></div>
        <div>
            <label class="text-xs text-zinc-400">Proyecto</label>
            <input
                type="text"
                name="proyecto"
                value="{{ $proyectoFiltro }}"
                placeholder="Nombre del proyecto"
                class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500"
            >
        </div>
        <div>
            <label class="text-xs text-zinc-400">Desde</label>
            <input
                type="date"
                name="desde"
                value="{{ $desde }}"
                class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-sky-500 focus:ring-sky-500"
            >
        </div>
        <div>
            <label class="text-xs text-zinc-400">Hasta</label>
            <input
                type="date"
                name="hasta"
                value="{{ $hasta }}"
                class="mt-1 w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-sky-500 focus:ring-sky-500"
            >
        </div>
        <div class="flex gap-2">
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700 w-full">
                Filtrar
            </button>
            <a href="{{ route('colaborador.aportaciones') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm font-semibold text-gray-300 hover:border-white/25">
                Limpiar
            </a>
        </div>
    </form>

    {{-- Resumen general --}}
    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Total aportado</p>
            <p class="text-2xl font-bold text-white">${{ number_format($totalAportado ?? 0, 2) }}</p>
        </div>
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Aportes</p>
            <p class="text-2xl font-bold text-white">{{ $numAportes ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Proyectos apoyados</p>
            <p class="text-2xl font-bold text-white">{{ $proyectosApoyados ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Última aportación</p>
            <p class="text-sm font-semibold text-white">
                {{ optional($ultimaAportacion)->format('d/m/Y H:i') ?? 'N/D' }}
            </p>
        </div>
    </div>

    @if($aportaciones->count())
        <div class="space-y-4">
            @foreach($aportaciones as $aporte)
                @php
                    $proyectoTitulo = optional($aporte->proyecto)->titulo ?? 'Proyecto eliminado';
                    $estado = strtolower($aporte->estado_pago ?? 'pagado');
                    $estadoClase = match($estado) {
                        'pagado' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40',
                        'pendiente' => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                        'fallido' => 'bg-rose-500/15 text-rose-200 border-rose-400/40',
                        default => 'bg-sky-500/15 text-sky-200 border-sky-400/40',
                    };
                    $metodo = $aporte->metodo_pago ?? 'No especificado';
                    $borderAccent = match($estado) {
                        'pagado' => 'border-l-4 border-emerald-400/60',
                        'pendiente' => 'border-l-4 border-amber-400/60',
                        'fallido' => 'border-l-4 border-rose-400/60',
                        default => 'border-l-4 border-sky-400/60',
                    };
                @endphp
                <article class="rounded-2xl border border-white/15 bg-[#0b1020] shadow-[0_18px_40px_rgba(0,0,0,0.4)] overflow-hidden {{ $borderAccent }}">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-white/10 px-4 py-3 bg-white/5">
                        <div class="space-y-1">
                            <p class="text-xs uppercase tracking-[0.22em] text-zinc-400 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Proyecto
                            </p>
                            <h3 class="text-lg font-semibold text-white">{{ $proyectoTitulo }}</h3>
                            <p class="text-[11px] text-zinc-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-12 8h14a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ optional($aporte->fecha_aportacion)->format('d/m/Y') ?? $aporte->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-semibold border {{ $estadoClase }}">
                                <span class="h-2 w-2 rounded-full bg-current opacity-80"></span>
                                {{ strtoupper($aporte->estado_pago ?? 'PAGADO') }}
                            </span>
                            <a href="{{ route('colaborador.aportaciones.recibo', $aporte) }}" class="inline-flex items-center gap-1 rounded-md border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-semibold text-white hover:border-sky-400/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16h8m-8-4h8m-6-4h6M6 4h9a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                </svg>
                                Recibo
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-3 px-4 py-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="space-y-1">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-500">Monto aportado</p>
                            <p class="text-xl font-bold text-white">${{ number_format($aporte->monto, 2) }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-500">Metodo de pago</p>
                            <p class="text-sm text-zinc-200 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-5 4h6a4 4 0 004-4v-5a4 4 0 00-4-4H7a4 4 0 00-4 4v5a4 4 0 004 4z" />
                                </svg>
                                {{ $metodo }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-500">ID transaccion</p>
                            <p class="text-sm text-zinc-200">{{ $aporte->id_transaccion_pago ?? '-' }}</p>
                        </div>
                    </div>

                    <details class="border-t border-white/10 px-4 py-3">
                        <summary class="flex items-center gap-2 text-xs font-semibold text-sky-200 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Ver detalles
                        </summary>
                        <div class="mt-3 grid gap-2 text-xs text-zinc-300 sm:grid-cols-2">
                            <p>Creado: {{ $aporte->created_at?->format('d/m/Y H:i') }}</p>
                            <p>Actualizado: {{ $aporte->updated_at?->format('d/m/Y H:i') }}</p>
                            <p>Estado de pago: {{ ucfirst($aporte->estado_pago ?? 'pagado') }}</p>
                            <p>Proyecto ID: {{ $aporte->proyecto_id }}</p>
                        </div>
                    </details>
                </article>
            @endforeach
        </div>
    @else
        <p class="text-sm text-zinc-300">
            Aun no has realizado aportaciones.
        </p>
    @endif
</section>
@endsection
