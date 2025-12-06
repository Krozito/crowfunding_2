@extends('auditor.layouts.panel')

@section('title', 'Desembolsos en revision')
@section('active', 'desembolsos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 3</p>
            <h1 class="text-2xl font-bold text-white">Solicitudes de desembolso</h1>
            <p class="text-sm text-zinc-400">Revisar hitos previos, documentos y liberar fondos.</p>
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

    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <form method="GET" class="grid gap-3 md:grid-cols-[1fr_220px_auto] items-end">
            <div>
                <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Buscar</label>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Proyecto"
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
                <a href="{{ route('auditor.desembolsos') }}" class="admin-btn admin-btn-ghost text-xs">Limpiar</a>
            </div>
        </form>

        @forelse ($solicitudes as $item)
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">ID {{ $item->id }} - {{ $item->estado }}</p>
                    <p class="text-lg font-semibold text-white">{{ $item->proyecto->titulo ?? 'Proyecto' }} - {{ $item->hito ?? 'Hito' }}</p>
                    <p class="text-sm text-zinc-400">Solicita ${{ number_format($item->monto_solicitado, 0, ',', '.') }} • {{ $item->created_at?->format('Y-m-d') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('auditor.desembolsos.show', $item) }}" class="admin-btn admin-btn-primary text-xs">Ver detalle</a>
                </div>
            </div>
        @empty
            <p class="text-sm text-zinc-500">No hay solicitudes registradas.</p>
        @endforelse

        <div class="mt-4">
            {{ $solicitudes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $solicitudes->links() : '' }}
        </div>
    </div>
@endsection
