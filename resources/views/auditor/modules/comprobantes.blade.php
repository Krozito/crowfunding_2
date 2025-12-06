@extends('auditor.layouts.panel')

@section('title', 'Comprobantes de gasto')
@section('active', 'comprobantes')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 2</p>
            <h1 class="text-2xl font-bold text-white">Revision de comprobantes</h1>
            <p class="text-sm text-zinc-400">Validar facturas, tickets y contratos contra desembolsos e hitos.</p>
        </div>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
            Salir
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-8">
        <div class="flex flex-wrap gap-3">
            <button class="admin-btn admin-btn-primary text-xs">Pendientes</button>
            <button class="admin-btn admin-btn-ghost text-xs">Observados</button>
            <button class="admin-btn admin-btn-ghost text-xs">Rechazados</button>
            <button class="admin-btn admin-btn-ghost text-xs">Aprobados</button>
        </div>

        <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl admin-accent-card">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4 text-xs uppercase tracking-[0.2em] text-zinc-400 font-semibold">
                <span>Comprobante</span>
                <span>Proyecto</span>
                <span>Monto</span>
                <span>Proveedor</span>
                <span>Fecha</span>
                <span>Hito/Categoria</span>
                <span>Acciones</span>
            </div>
            <div class="mt-3 space-y-3 text-sm text-zinc-300">
                @forelse ($pagos as $pago)
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-center rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                        <div>
                            <p class="font-semibold text-white">#{{ $pago->id }}</p>
                            <p class="text-xs text-zinc-400">{{ $pago->concepto ?? 'Sin concepto' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-white">{{ optional($pago->solicitud->proyecto)->titulo ?? 'Proyecto' }}</p>
                            <p class="text-xs text-zinc-400">{{ $pago->solicitud->hito ?? 'Hito' }}</p>
                        </div>
                        <p class="font-semibold text-emerald-200">${{ number_format($pago->monto, 0, ',', '.') }}</p>
                        <p class="text-sm text-white">{{ $pago->proveedor->nombre_proveedor ?? 'Proveedor' }}</p>
                        <p class="text-xs text-zinc-400">{{ $pago->fecha_pago?->format('Y-m-d') ?? 'N/D' }}</p>
                        <div>
                            <p class="text-sm text-white">{{ $pago->solicitud->hito ?? 'Hito' }}</p>
                            <p class="text-xs text-zinc-400">{{ $pago->solicitud->estado ?? 'Estado' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button class="admin-btn admin-btn-primary text-xs">Aprobar</button>
                            <button class="admin-btn admin-btn-ghost text-xs">Observar</button>
                            <button class="admin-btn admin-btn-ghost text-xs border-red-400/60 text-red-200">Rechazar</button>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-zinc-500">No hay comprobantes registrados.</p>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $pagos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $pagos->links() : '' }}
            </div>
        </div>
    </div>
@endsection
