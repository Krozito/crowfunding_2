@extends('auditor.layouts.panel')

@section('title', 'Hitos de proyecto')
@section('active', 'hitos')
@section('back_url', route('auditor.hitos'))
@section('back_label', 'Volver a proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        <div class="relative overflow-hidden rounded-[22px] bg-gradient-to-r from-purple-800/30 via-purple-700/25 to-fuchsia-600/25 border border-white/10 px-6 py-6 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_45%)] blur-[1px]"></div>
            <div class="relative flex flex-col gap-2">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Proyecto</p>
                <h1 class="text-2xl font-extrabold text-white">{{ $proyecto->titulo }}</h1>
                <p class="text-sm text-white/80">Hitos y evidencias asociados</p>
            </div>
        </div>

        <div class="rounded-[22px] border border-white/10 bg-zinc-900/70 p-5 shadow-xl">
            <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto] items-end">
                <div>
                    <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Buscar</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Hito o contenido"
                           class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="admin-btn admin-btn-primary text-xs">Filtrar</button>
                    <a href="{{ route('auditor.hitos.proyecto', $proyecto) }}" class="admin-btn admin-btn-ghost text-xs">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="grid gap-4">
            @forelse ($hitos as $item)
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3 admin-accent-card">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">{{ $item->fecha_publicacion?->format('Y-m-d') ?? 'Sin fecha' }}</p>
                            <h3 class="text-xl font-semibold text-white">{{ $item->titulo }}</h3>
                        </div>
                        <span class="text-[11px] uppercase tracking-[0.22em] text-indigo-200">ID {{ $item->id }}</span>
                    </div>
                    <p class="text-sm text-zinc-400 leading-relaxed">{{ $item->contenido ?? 'Sin contenido' }}</p>
                </div>
            @empty
                <p class="text-sm text-zinc-500">No hay hitos para este proyecto.</p>
            @endforelse
        </div>

        <div>
            {{ $hitos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $hitos->links() : '' }}
        </div>
    </div>
@endsection
