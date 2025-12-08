@extends('auditor.layouts.panel')

@section('title', 'Auditoria de proyectos')
@section('active', 'proyectos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 6</p>
            <h1 class="text-2xl font-bold text-white">Auditoria de proyectos</h1>
            <p class="text-sm text-zinc-400">Revisa publicaciones, estados y toma acciones.</p>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="rounded-[22px] border border-white/10 bg-zinc-950/80 p-5 shadow-xl backdrop-blur">
            <form method="GET" class="grid gap-3 md:grid-cols-[1fr_200px_auto] items-end">
                <div>
                    <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Buscar</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Proyecto"
                           class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Estado</label>
                    <select name="estado" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                        <option value="">Todos</option>
                        @foreach ($estadosPublicacion as $opt)
                            <option value="{{ $opt }}" {{ ($estado ?? '') === $opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="admin-btn admin-btn-primary text-xs">Filtrar</button>
                    <a href="{{ route('auditor.proyectos') }}" class="admin-btn admin-btn-ghost text-xs">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            @forelse ($proyectos as $item)
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 admin-accent-card space-y-2">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-white">{{ $item->titulo }}</p>
                        <span class="text-xs uppercase tracking-[0.22em] text-amber-100">{{ $item->estado ?? 'pendiente' }}</span>
                    </div>
                    <p class="text-sm text-zinc-400">Meta: ${{ number_format($item->meta_financiacion, 0, ',', '.') }} • Recaudado: ${{ number_format($item->monto_recaudado ?? 0, 0, ',', '.') }}</p>
                    <p class="text-sm text-white">Categoria: {{ $item->categoria ?? 'Sin categoria' }}</p>
                    <p class="text-xs text-zinc-400">Creado: {{ $item->created_at?->format('Y-m-d') }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="{{ route('auditor.proyectos.show', $item) }}" class="admin-btn admin-btn-primary text-xs">Inspeccionar</a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-zinc-500">No hay proyectos registrados.</p>
            @endforelse
        </div>

        <div>
            {{ $proyectos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $proyectos->links() : '' }}
        </div>
    </div>
@endsection
