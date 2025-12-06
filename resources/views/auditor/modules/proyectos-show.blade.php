@extends('auditor.layouts.panel')

@section('title', 'Proyecto')
@section('active', 'proyectos')
@section('back_url', route('auditor.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        <div class="rounded-[22px] border border-white/10 bg-zinc-950/80 shadow-2xl overflow-hidden admin-accent-card">
            <div class="relative h-64 bg-gradient-to-r from-purple-800/40 via-purple-700/25 to-fuchsia-600/25">
                @if ($portadaUrl ?? false)
                    <img src="{{ $portadaUrl }}" alt="Portada proyecto" class="h-full w-full object-cover opacity-80">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/35 to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4 flex flex-col gap-1">
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Proyecto</p>
                    <h1 class="text-3xl font-extrabold text-white">{{ $proyecto->titulo }}</h1>
                    <p class="text-sm text-white/80">Estado: {{ $proyecto->estado ?? 'pendiente' }}</p>
                </div>
            </div>

            <div class="p-6 grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="space-y-4">
                    <p class="text-sm text-zinc-300 leading-relaxed">{{ $proyecto->descripcion_proyecto ?? 'Sin descripción' }}</p>
                    <div class="grid gap-3 md:grid-cols-3">
                        <div class="rounded-xl border border-white/10 bg-zinc-900/70 p-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-500">Meta</p>
                            <p class="text-xl font-bold text-white">${{ number_format($proyecto->meta_financiacion, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/70 p-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-500">Recaudado</p>
                            <p class="text-xl font-bold text-emerald-200">${{ number_format($proyecto->monto_recaudado ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/70 p-3">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-500">Categoría</p>
                            <p class="text-sm font-semibold text-white">{{ $proyecto->categoria ?? 'Sin categoría' }}</p>
                        </div>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-zinc-900/70 p-4 space-y-1">
                        <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-500">Ubicación</p>
                        <p class="text-sm text-white">{{ $proyecto->ubicacion_geografica ?? 'No especificada' }}</p>
                        <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-500 pt-2">Creado</p>
                        <p class="text-sm text-white">{{ $proyecto->created_at?->format('Y-m-d') }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="rounded-xl border border-white/10 bg-zinc-900/70 p-4">
                        <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-500">Acciones</p>
                        <form method="POST" action="{{ route('auditor.proyectos.publicacion', $proyecto) }}" class="flex flex-wrap gap-2 pt-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="accion" value="permitir" id="accion-publicacion">
                            <button type="submit" onclick="document.getElementById('accion-publicacion').value='permitir'" class="admin-btn admin-btn-primary text-xs w-full">Permitir publicación</button>
                            <button type="submit" onclick="document.getElementById('accion-publicacion').value='pausar'" class="admin-btn admin-btn-ghost text-xs w-full">Pausar publicación</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
