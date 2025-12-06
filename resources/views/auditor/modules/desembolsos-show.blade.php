@extends('auditor.layouts.panel')

@section('title', 'Detalle de desembolso')
@section('active', 'desembolsos')
@section('back_url', route('auditor.desembolsos'))
@section('back_label', 'Volver a desembolsos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        <div class="relative overflow-hidden rounded-[22px] bg-gradient-to-r from-purple-800/30 via-purple-700/25 to-fuchsia-600/25 border border-white/10 px-6 py-6 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_45%)] blur-[1px]"></div>
            <div class="relative flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Solicitud de desembolso</p>
                    <h1 class="mt-1 text-2xl font-extrabold text-white">#{{ $solicitud->id }} — {{ $solicitud->hito ?? 'Hito' }}</h1>
                    <p class="text-sm text-white/80">Proyecto: {{ $solicitud->proyecto->titulo ?? 'Proyecto' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.3em] text-white">
                        Estado: {{ ucfirst($solicitud->estado) }}
                    </span>
                    @if($solicitud->created_at)
                        <span class="text-xs text-white/70">Creada: {{ $solicitud->created_at->format('Y-m-d H:i') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Monto solicitado</p>
                <p class="text-3xl font-extrabold text-emerald-200">${{ number_format($solicitud->monto_solicitado, 0, ',', '.') }}</p>
                <p class="text-xs text-zinc-400">Fecha estimada: {{ $solicitud->fecha_estimada?->format('Y-m-d') ?? 'N/D' }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Estado</p>
                <p class="text-lg font-semibold text-white">{{ ucfirst($solicitud->estado) }}</p>
                <p class="text-xs text-zinc-400">Proyecto ID: {{ $solicitud->proyecto_id }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proveedores</p>
                <div class="flex flex-wrap gap-2">
                    @forelse ($solicitud->proveedores ?? [] as $prov)
                        <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs text-white">{{ $prov }}</span>
                    @empty
                        <p class="text-xs text-zinc-400">N/D</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Descripción</p>
            <p class="text-sm text-zinc-300 leading-relaxed">{{ $solicitud->descripcion ?? 'Sin descripción' }}</p>
        </div>

        @if ($adjuntos->isNotEmpty())
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Adjuntos</p>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($adjuntos as $file)
                        <a href="{{ $file['url'] }}" target="_blank" class="group relative block overflow-hidden rounded-xl border border-white/10 bg-white/5">
                            <img src="{{ $file['url'] }}" alt="Adjunto" class="h-48 w-full object-cover transition duration-200 group-hover:scale-[1.02] group-hover:opacity-90" onerror="this.style.display='none'">
                            <div class="absolute bottom-2 left-2 right-2 text-xs text-white/90 truncate">{{ basename($file['path']) }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('auditor.desembolsos.estado', $solicitud) }}" method="POST" class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3 admin-accent-card">
            @csrf
            @method('PATCH')
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Acciones</p>
            <label class="text-xs uppercase tracking-[0.2em] text-zinc-500">Nota (requerida para rechazar)</label>
            <textarea name="nota" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none" placeholder="Describe hallazgos o razón de rechazo">{{ old('nota', $solicitud->justificacion_admin) }}</textarea>
            <input type="hidden" name="accion" value="aprobar" id="accion-desembolso">
            <div class="flex flex-wrap gap-2">
                <button type="submit" onclick="document.getElementById('accion-desembolso').value='aprobar'" class="admin-btn admin-btn-primary text-xs">Aprobar</button>
                <button type="submit" onclick="document.getElementById('accion-desembolso').value='rechazar'" class="admin-btn admin-btn-ghost text-xs border-red-400/60 text-red-200">Rechazar</button>
            </div>
            <p class="text-xs text-zinc-500">Estado actual: {{ $solicitud->estado }}</p>
        </form>
    </div>
@endsection
