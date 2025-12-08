@extends('colaborador.layouts.panel')

@section('title', 'Resumen del proyecto')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-8">
    @php
        $hero = $proyecto->imagen_portada
            ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
            : null;
    @endphp
    <section class="rounded-3xl border border-white/15 bg-[#030712] shadow-[0_24px_60px_rgba(0,0,0,0.55)] overflow-hidden">
        <div class="relative">
            @if ($hero)
                <div class="absolute inset-0">
                    <img src="{{ $hero }}" alt="Imagen del proyecto {{ $proyecto->titulo }}" class="h-full w-full object-cover opacity-60">
                    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/70 to-zinc-900/70"></div>
                </div>
            @endif
            <div class="relative p-6 space-y-3 text-white">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="space-y-1">
                        <p class="text-xs uppercase tracking-[0.3em] text-sky-200">Proyecto</p>
                        <h1 class="text-2xl font-bold">{{ $proyecto->titulo }}</h1>
                        <p class="text-sm text-sky-100/80">Estado: <span class="font-semibold">{{ ucfirst($proyecto->estado ?? 'En progreso') }}</span></p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-black/30 px-4 py-3 text-sm text-indigo-100">
                        <p class="text-xs uppercase tracking-[0.2em] text-sky-200">Tu aporte acumulado</p>
                        <p class="text-2xl font-extrabold text-emerald-300">${{ number_format($aporteUsuario, 0, ',', '.') }}</p>
                        <a href="{{ route('colaborador.proyectos.aportar', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-[12px] font-bold uppercase tracking-wide text-white shadow-[0_10px_28px_rgba(59,130,246,0.35)] hover:bg-sky-700 transition-colors mt-2">
                            Apoyar ahora
                        </a>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 text-sm text-indigo-100/90">
                    <div class="rounded-xl border border-white/15 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Meta</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($proyecto->meta_financiacion ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Recaudado</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($proyecto->monto_recaudado ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Modelo</p>
                        <p class="text-lg font-semibold text-white">{{ $proyecto->modelo_financiamiento ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 bg-black/40 px-6 py-4 flex flex-wrap gap-2">
            <a href="{{ route('colaborador.proyectos.proveedores', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:border-sky-400/60">
                Ver proveedores
            </a>
        </div>
    </section>

    <section class="space-y-3">
        <h2 class="text-sm font-semibold text-zinc-100 tracking-wide uppercase">
            Hitos recientes
        </h2>
        @if ($proyecto->hitos->isEmpty())
            <div class="rounded-2xl border border-white/10 bg-black/40 p-4 text-xs text-zinc-400">
                Aún no hay hitos publicados para este proyecto.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($proyecto->hitos as $hito)
                    <article class="rounded-2xl border border-white/10 bg-black/40 p-4 space-y-1">
                        <h3 class="text-sm font-semibold text-zinc-50">{{ $hito->titulo ?? 'Actualización' }}</h3>
                        <p class="text-[11px] text-zinc-400">{{ optional($hito->created_at)->format('d/m/Y H:i') }}</p>
                        <p class="text-xs text-zinc-200">{{ $hito->descripcion ?? $hito->contenido }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
