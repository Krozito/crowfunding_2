@extends('colaborador.layouts.panel')

@section('active', 'proyectos')
@section('title', 'Proyectos que apoyas')

@section('content')
<section class="p-8 space-y-6">
    @php
        $totalProyectos = $proyectosAportados->count() ?? 0;
        $totalRecaudado = $proyectosAportados->sum('monto_recaudado') ?? 0;
    @endphp

    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyectos</p>
        <h1 class="text-2xl font-bold text-white">Proyectos que estás apoyando</h1>
        <p class="text-sm text-zinc-400">Resumen rápido de las campañas asociadas a tus aportes.</p>
    </header>

    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Proyectos</p>
            <p class="text-2xl font-bold text-white">{{ $totalProyectos }}</p>
        </div>
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Total recaudado</p>
            <p class="text-2xl font-bold text-white">${{ number_format($totalRecaudado, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Estado general</p>
            <p class="text-sm font-semibold text-emerald-200">Activo</p>
        </div>
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-1">
            <p class="text-[11px] uppercase tracking-[0.28em] text-sky-200">Explorar</p>
            <p class="text-sm text-zinc-300">Sigue apoyando nuevas ideas desde el panel.</p>
        </div>
    </div>

    @if(isset($proyectosAportados) && $proyectosAportados->count())
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($proyectosAportados as $proyecto)
                @php
                    $meta = $proyecto->meta_financiacion ?: 1;
                    $recaudado = $proyecto->monto_recaudado ?? 0;
                    $progreso = $meta > 0 ? min(100, round(($recaudado / $meta) * 100)) : 0;
                    $estado = strtoupper($proyecto->estado ?? 'EN PROGRESO');
                    $estadoClase = match($estado) {
                        'PAGADO', 'FINALIZADO', 'PUBLICADO' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40',
                        'PENDIENTE', 'EN PROGRESO' => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                        default => 'bg-sky-500/15 text-sky-200 border-sky-400/40',
                    };
                    $imagen = $proyecto->imagen_portada
                        ? \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada)
                        : 'https://images.unsplash.com/photo-1471879832106-c7ab9e0cee23?auto=format&fit=crop&w=800&q=80';
                @endphp
                <article class="rounded-3xl border border-white/15 bg-[#030712] shadow-[0_18px_45px_rgba(0,0,0,0.45)] overflow-hidden flex flex-col">
                    <div class="relative h-40 w-full overflow-hidden">
                        <img src="{{ $imagen }}" alt="Imagen del proyecto" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                        <div class="absolute top-3 left-3 inline-flex items-center rounded-full bg-black/40 px-3 py-1 text-[11px] font-semibold text-white border border-white/15">
                            {{ ucfirst($proyecto->categoria ?? 'Proyecto') }}
                        </div>
                        <div class="absolute top-3 right-3 inline-flex items-center rounded-full px-3 py-1 text-[11px] font-semibold border {{ $estadoClase }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
                            {{ $estado }}
                        </div>
                    </div>

                    <div class="p-5 space-y-3 flex-1 flex flex-col">
                        <div class="space-y-1">
                            <h3 class="text-lg font-semibold text-white line-clamp-2">{{ $proyecto->titulo ?? 'Proyecto sin título' }}</h3>
                            <p class="text-xs text-zinc-400 line-clamp-2">
                                {{ \Illuminate\Support\Str::limit($proyecto->descripcion_corta ?? $proyecto->descripcion_proyecto ?? 'Sin descripcion', 120) }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[12px] text-zinc-300">
                                <span>Progreso</span>
                                <span class="text-sm font-semibold text-sky-200">{{ $progreso }}%</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-zinc-900/80 overflow-hidden ring-1 ring-white/10">
                                <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-400 to-emerald-400" style="width: {{ $progreso }}%;"></div>
                            </div>
                            <div class="flex items-center justify-between text-[12px] text-zinc-300">
                                <span>Recaudado: <span class="text-white font-semibold">${{ number_format($recaudado, 0, ',', '.') }}</span></span>
                                <span>Meta: <span class="text-white font-semibold">${{ number_format($meta, 0, ',', '.') }}</span></span>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('colaborador.proyectos.resumen', $proyecto) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-xs font-bold uppercase tracking-wide text-white shadow-[0_10px_28px_rgba(59,130,246,0.35)] hover:bg-sky-700 transition-colors">
                                Revisar
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @else
        <div class="rounded-3xl border border-white/15 bg-[#0b1020] shadow-xl p-6 text-sm text-zinc-300">
            <p class="font-medium text-white mb-1">Aun no has realizado aportaciones.</p>
            <p class="text-xs text-zinc-400">Explora proyectos en el panel general y apoya tu primera campaña.</p>
        </div>
    @endif
</section>
@endsection
