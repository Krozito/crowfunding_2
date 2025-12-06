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
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_220px_auto] items-end">
            <div>
                <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Buscar</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Proveedor, proyecto o concepto"
                       class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Estado</label>
                <select name="estado" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                    <option value="">Todos</option>
                    @foreach ($estados as $opt)
                        <option value="{{ $opt }}" {{ ($estado ?? '') === $opt ? 'selected' : '' }}>
                            {{ ucfirst($opt) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="admin-btn admin-btn-primary text-xs">Filtrar</button>
                <a href="{{ route('auditor.comprobantes') }}" class="admin-btn admin-btn-ghost text-xs">Limpiar</a>
            </div>
        </form>

        <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl admin-accent-card">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4 text-xs uppercase tracking-[0.2em] text-zinc-400 font-semibold">
                <span>Comprobante</span>
                <span>Proyecto</span>
                <span>Monto</span>
                <span>Proveedor</span>
                <span>Fecha</span>
                <span>Hito/Estado</span>
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
                            <p class="text-xs text-zinc-400">Solicitud: {{ $pago->solicitud->estado ?? 'Estado' }}</p>
                            <p class="text-[11px] uppercase tracking-[0.2em] {{ ($pago->estado_auditoria ?? 'pendiente') === 'rechazado' ? 'text-red-300' : 'text-indigo-200' }}">Auditoria: {{ $pago->estado_auditoria ?? 'pendiente' }}</p>
                        </div>
                        <form action="{{ route('auditor.comprobantes.estado', $pago) }}" method="POST" class="flex flex-wrap gap-2 items-center">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="accion" value="observar">
                            <input type="text" name="nota" placeholder="Nota (para rechazar/observar)" class="w-full md:w-40 rounded-lg border border-white/10 bg-white/5 px-2 py-1 text-xs text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:outline-none">
                            <button type="submit" onclick="this.form.accion.value='aprobar'" class="admin-btn admin-btn-primary text-xs">Aprobar</button>
                            <button type="submit" onclick="this.form.accion.value='observar'" class="admin-btn admin-btn-ghost text-xs">Observar</button>
                            <button type="submit" onclick="this.form.accion.value='rechazar'" class="admin-btn admin-btn-ghost text-xs border-red-400/60 text-red-200">Rechazar</button>
                            <a href="{{ route('auditor.comprobantes.show', $pago) }}" class="admin-btn admin-btn-ghost text-xs">Ver detalle</a>
                        </form>
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
