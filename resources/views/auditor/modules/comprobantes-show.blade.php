@extends('auditor.layouts.panel')

@section('title', 'Detalle de comprobante')
@section('active', 'comprobantes')
@section('back_url', route('auditor.comprobantes'))
@section('back_label', 'Volver a comprobantes')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Comprobante</p>
                <h1 class="text-2xl font-bold text-white">#{{ $pago->id }} — {{ $pago->concepto ?? 'Sin concepto' }}</h1>
                <p class="text-sm text-zinc-400">Fecha: {{ $pago->fecha_pago?->format('Y-m-d') ?? 'N/D' }}</p>
            </div>
            <span class="text-xs uppercase tracking-[0.3em] text-emerald-200">Estado auditoría: {{ $pago->estado_auditoria ?? 'pendiente' }}</span>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Monto</p>
                <p class="text-3xl font-extrabold text-emerald-200">${{ number_format($pago->monto, 0, ',', '.') }}</p>
                <p class="text-xs text-zinc-400">Proveedor: {{ $pago->proveedor->nombre_proveedor ?? 'N/D' }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyecto</p>
                <p class="text-lg font-semibold text-white">{{ optional($pago->solicitud->proyecto)->titulo ?? 'Proyecto' }}</p>
                <p class="text-xs text-zinc-400">Hito: {{ $pago->solicitud->hito ?? 'N/D' }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Estado de solicitud</p>
                <p class="text-lg font-semibold text-white">{{ $pago->solicitud->estado ?? 'N/D' }}</p>
                <p class="text-xs text-zinc-400">Solicitud ID: {{ $pago->solicitud->id ?? 'N/D' }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3 admin-accent-card">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Detalles</p>
            <p class="text-sm text-zinc-300">Concepto: {{ $pago->concepto ?? 'Sin concepto' }}</p>
            <p class="text-sm text-zinc-300">Proveedor contacto: {{ $pago->proveedor->info_contacto ?? 'N/D' }}</p>
        </div>

        @if ($adjuntos->isNotEmpty())
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Facturas / comprobantes adjuntos</p>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($adjuntos as $file)
                        <a href="{{ $file['url'] }}" target="_blank" class="group relative block overflow-hidden rounded-xl border border-white/10 bg-white/5">
                            <img src="{{ $file['url'] }}" alt="Comprobante" class="h-48 w-full object-cover transition duration-200 group-hover:scale-[1.02] group-hover:opacity-90" onerror="this.style.display='none'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                            <div class="absolute bottom-2 left-2 right-2 text-xs text-white/90 truncate">{{ basename($file['path']) }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('auditor.comprobantes.estado', $pago) }}" method="POST" class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-4 admin-accent-card">
            @csrf
            @method('PATCH')
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Acciones de auditoría</p>
            <label class="text-xs uppercase tracking-[0.2em] text-zinc-500">Nota (opcional, requerida para rechazar)</label>
            <textarea name="nota" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none" placeholder="Describe hallazgos o la razón de rechazo">{{ old('nota', $pago->nota_auditoria) }}</textarea>
            <input type="hidden" name="accion" value="aprobar" id="accion-input">
            <div class="flex flex-wrap gap-2">
                <button type="submit" onclick="document.getElementById('accion-input').value='aprobar'" class="admin-btn admin-btn-primary text-xs">Aprobar</button>
                <button type="submit" onclick="document.getElementById('accion-input').value='rechazar'" class="admin-btn admin-btn-ghost text-xs border-red-400/60 text-red-200">Rechazar</button>
            </div>
            <p class="text-xs text-zinc-500">Estado actual: {{ $pago->estado_auditoria ?? 'pendiente' }}</p>
        </form>
    </div>
@endsection
