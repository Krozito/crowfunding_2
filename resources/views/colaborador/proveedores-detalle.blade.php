@extends('colaborador.layouts.panel')

@section('title', 'Historial del proveedor')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos.proveedores', $proyecto))
@section('back_label', 'Volver a proveedores')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-6">
    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Historial de proveedor</p>
        <h1 class="text-2xl font-bold text-white">{{ $proveedor->nombre_proveedor }}</h1>
        <p class="text-sm text-zinc-400">Proyecto: {{ $proyecto->titulo }}</p>
        <p class="text-xs text-zinc-500">Especialidad: {{ $proveedor->especialidad ?? 'Sin especialidad' }} - Contacto: {{ $proveedor->info_contacto ?? 'No disponible' }}</p>
    </header>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)]">
            <p class="text-[11px] uppercase tracking-[0.2em] text-sky-200">Calificacion promedio</p>
            <p class="mt-2 flex items-center gap-2 text-xl font-semibold text-amber-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17l-5 3 1.9-5.9L4 9.5l6-.3L12 4l2 5.2 6 .3-4.9 4.6L17 20z" />
                </svg>
                {{ $calificacionPromedio ? number_format($calificacionPromedio, 1) : 'N/D' }}
            </p>
            <p class="text-xs text-zinc-500">Basada en {{ $proveedor->historiales->count() }} registros.</p>
        </div>

        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)]">
            <p class="text-[11px] uppercase tracking-[0.2em] text-sky-200">Total pagado</p>
            <p class="mt-2 text-2xl font-bold text-sky-100">USD {{ number_format($totalProveedor, 2) }}</p>
            <p class="text-xs text-zinc-500">Pagos liquidados a este proveedor.</p>
        </div>

        <div class="rounded-2xl border border-white/15 bg-[#0b1020] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)]">
            <p class="text-[11px] uppercase tracking-[0.2em] text-sky-200">Pagos registrados</p>
            <p class="mt-2 text-2xl font-bold text-white">{{ $pagos->count() }}</p>
            <p class="text-xs text-zinc-500">Incluye facturas adjuntas.</p>
        </div>
    </div>

    <div class="rounded-3xl border border-white/15 bg-[#0b1020] shadow-[0_24px_60px_rgba(0,0,0,0.45)] p-6 space-y-4">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-sky-200">Pagos y facturas</p>
            <p class="text-sm text-zinc-300">Detalle de cada pago realizado a este proveedor.</p>
        </div>

        @forelse ($pagos as $pago)
            <article class="rounded-2xl border border-white/15 bg-white/5 p-4 space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-white">{{ $pago->concepto ?? 'Pago' }}</p>
                        <p class="text-xs text-zinc-400">{{ $pago->fecha_pago?->format('d/m/Y') ?? 'Fecha no disponible' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-sky-100">USD {{ number_format($pago->monto ?? 0, 2) }}</p>
                        <p class="text-xs text-zinc-400 flex items-center justify-end gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17l-5 3 1.9-5.9L4 9.5l6-.3L12 4l2 5.2 6 .3-4.9 4.6L17 20z" />
                            </svg>
                            Calificacion: {{ $pago->calificacion_pago ? number_format($pago->calificacion_pago, 1) : 'N/D' }}
                        </p>
                    </div>
                </div>

                @if (!empty($pago->adjuntos))
                    <div class="flex flex-wrap gap-2">
                        @foreach ($pago->adjuntos as $idx => $archivo)
                            <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/15 bg-white/10 px-3 py-2 text-[12px] font-semibold text-white hover:border-sky-400/60">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                </svg>
                                Factura {{ $idx + 1 }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-zinc-400">Sin facturas adjuntas.</p>
                @endif
            </article>
        @empty
            <p class="text-sm text-zinc-300">Aun no hay pagos registrados para este proveedor.</p>
        @endforelse
    </div>
</div>
@endsection
