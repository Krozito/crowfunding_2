@extends('colaborador.layouts.panel')

@section('title', 'Detalle del proyecto')
@section('active', 'proyectos')
@section('back_url', route('colaborador.dashboard'))
@section('back_label', 'Volver al panel')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-8">

    {{-- Header proyecto --}}
    @php
        $meta = $proyecto->meta_financiacion ?: 1;
        $recaudado = $proyecto->monto_recaudado ?? 0;
        $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
        $hero = $proyecto->imagen_portada
            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
            : 'https://images.unsplash.com/photo-1471879832106-c7ab9e0cee23?auto=format&fit=crop&w=1200&q=80';
    @endphp
    <section class="rounded-2xl border border-white/10 bg-zinc-950/80 shadow-[0_24px_60px_rgba(0,0,0,0.5)] overflow-hidden">
        {{-- Hero image --}}
        <div class="relative aspect-[16/9] w-full overflow-hidden">
            <img src="{{ $hero }}" alt="Imagen del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col gap-4 text-white">
                <div class="flex flex-wrap items-center gap-2">
                    @if ($proyecto->categoria)
                        <span class="inline-flex items-center rounded-full bg-indigo-500/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide">
                            {{ ucfirst($proyecto->categoria) }}
                        </span>
                    @endif
                    @if ($proyecto->modelo_financiamiento)
                        <span class="inline-flex items-center rounded-full bg-emerald-500/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide">
                            {{ ucfirst($proyecto->modelo_financiamiento) }}
                        </span>
                    @endif
                    @if ($proyecto->fecha_limite)
                        <span class="inline-flex items-center rounded-full bg-black/60 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide border border-white/10">
                            Fecha límite: {{ optional($proyecto->fecha_limite)->format('d/m/Y') }}
                        </span>
                    @endif
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold leading-tight max-w-3xl">
                    {{ $proyecto->titulo }}
                </h1>
                <p class="text-sm sm:text-base text-indigo-50/90 max-w-3xl">
                    {{ $proyecto->descripcion_proyecto }}
                </p>
                <p class="text-[12px] text-indigo-100/80">
                    Por
                    <span class="font-semibold text-white">
                        {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'Creador' }}
                    </span>
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('colaborador.proyectos.aportar', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-emerald-950 shadow-[0_12px_35px_rgba(16,185,129,0.35)] hover:bg-emerald-400 transition-colors">
                        Apoyar este proyecto
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Progreso y aporte --}}
        <div class="grid gap-4 p-6 lg:grid-cols-[2fr_1fr] bg-black/40">
            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm text-indigo-50/90">
                    <span class="font-semibold">Progreso del proyecto</span>
                    <span class="text-lg font-bold text-sky-200">
                        {{ $progreso }}%
                        @if ($progreso >= 100)
                            <span class="ml-2 inline-flex items-center rounded-full bg-emerald-500/20 px-2 py-0.5 text-[11px] font-semibold text-emerald-200 border border-emerald-400/40">
                                Proyecto financiado
                            </span>
                        @endif
                    </span>
                </div>
                <div class="h-3 w-full rounded-full bg-zinc-900/80 overflow-hidden ring-1 ring-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-emerald-400 shadow-[0_0_12px_rgba(56,189,248,0.35)]"
                         style="width: {{ $progreso }}%;"></div>
                </div>
                <div class="flex flex-wrap items-center gap-6 text-sm text-indigo-50/90">
                    <div class="space-y-0.5">
                        <p class="text-[11px] uppercase tracking-wide text-indigo-100/70">Recaudado</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($recaudado, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[11px] uppercase tracking-wide text-indigo-100/70">Meta</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($meta, 0, ',', '.') }}</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="text-[11px] uppercase tracking-wide text-indigo-100/70">Estado</p>
                        <p class="text-sm font-semibold text-emerald-200">{{ ucfirst($proyecto->estado ?? 'Activo') }}</p>
                    </div>
                </div>
            </div>

            <div id="apoyar" class="rounded-2xl border border-white/10 bg-zinc-900/80 p-4 space-y-3 shadow-[0_18px_40px_rgba(0,0,0,0.45)]">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100/70">
                    Tu aporte a este proyecto
                </p>
                <p class="text-3xl font-extrabold text-emerald-300">
                    ${{ number_format($aporteUsuario, 0, ',', '.') }}
                </p>
                <p class="text-xs text-indigo-50/80">
                    Puedes decidir aumentar tu aporte si este proyecto te inspira.
                </p>
                <a href="{{ route('colaborador.aportaciones') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-500 px-4 py-2.5 text-sm font-bold uppercase tracking-wide text-white shadow-[0_10px_28px_rgba(79,70,229,0.35)] hover:bg-indigo-400 transition-colors">
                    Revisar mis aportes
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Recompensas --}}
    <section class="space-y-3">
        <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
            Recompensas disponibles
        </h2>

        @if ($proyecto->recompensas->isEmpty())
            <div class="rounded-2xl border border-white/5 bg-black/40 p-4 text-xs text-zinc-400">
                Este proyecto no tiene recompensas configuradas por el momento.
            </div>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($proyecto->recompensas as $recompensa)
                    @php
                        $recompensaImg = $proyecto->imagen_portada
                            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                            : 'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?auto=format&fit=crop&w=600&q=80';
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-zinc-900/70 overflow-hidden shadow-[0_16px_40px_rgba(0,0,0,0.35)] flex flex-col">
                        <div class="h-28 w-full overflow-hidden">
                            <img src="{{ $recompensaImg }}" alt="Recompensa" class="h-full w-full object-cover">
                        </div>
                        <div class="p-4 space-y-2 text-sm text-zinc-200 flex-1 flex flex-col">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-indigo-200">
                                A partir de
                                <span class="text-emerald-300">
                                    ${{ number_format($recompensa->monto_minimo ?? $recompensa->monto_minimo_aportacion ?? 0, 0, ',', '.') }}
                                </span>
                            </p>
                            <h3 class="text-sm font-semibold text-white">
                                {{ $recompensa->titulo ?? 'Recompensa' }}
                            </h3>
                            <p class="text-xs text-zinc-300 flex-1">
                                {{ $recompensa->descripcion ?? 'Recompensa por tu apoyo a este proyecto.' }}
                            </p>
                            <div class="flex justify-end">
                                <a href="#apoyar" class="inline-flex items-center gap-2 rounded-md bg-emerald-500 px-3 py-2 text-[11px] font-bold uppercase tracking-wide text-emerald-950 hover:bg-emerald-400 transition-colors">
                                    Seleccionar recompensa
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Hitos / Actualizaciones --}}
    <section class="space-y-3">
        <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
            Hitos y actualizaciones
        </h2>

        @if ($proyecto->hitos->isEmpty())
            <div class="rounded-2xl border border-white/5 bg-black/40 p-4 text-xs text-zinc-400">
                El creador aún no ha publicado hitos o actualizaciones para este proyecto.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($proyecto->hitos as $hito)
                    <article class="rounded-2xl border border-white/5 bg-black/40 p-4 space-y-1">
                        <h3 class="text-sm font-semibold text-zinc-50">
                            {{ $hito->titulo ?? 'Actualización' }}
                        </h3>
                        <p class="text-[11px] text-zinc-400">
                            {{ optional($hito->created_at)->format('d/m/Y H:i') }}
                        </p>
                        <p class="text-xs text-zinc-200">
                            {{ $hito->descripcion ?? $hito->contenido }}
                        </p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
