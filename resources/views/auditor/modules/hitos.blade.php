@extends('auditor.layouts.panel')

@section('title', 'Validacion de hitos')
@section('active', 'hitos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 5</p>
            <h1 class="text-2xl font-bold text-white">Hitos del proyecto</h1>
            <p class="text-sm text-zinc-400">Evidencia vs plan y gastos asociados por hito.</p>
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
        <div class="rounded-[22px] border border-white/10 bg-zinc-900/70 p-5 shadow-xl">
            <form method="GET" class="grid gap-3 md:grid-cols-[1fr_auto] items-end">
                <div>
                    <label class="block text-xs uppercase tracking-[0.3em] text-zinc-500">Buscar proyecto</label>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Nombre de proyecto"
                           class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:outline-none">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="admin-btn admin-btn-primary text-xs">Filtrar</button>
                    <a href="{{ route('auditor.hitos') }}" class="admin-btn admin-btn-ghost text-xs">Limpiar</a>
                </div>
            </form>
        </div>

        <div class="grid gap-4">
            @forelse ($proyectos as $proy)
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 space-y-3 admin-accent-card">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyecto</p>
                            <h3 class="text-xl font-semibold text-white">{{ $proy->titulo }}</h3>
                        </div>
                        <span class="text-[11px] uppercase tracking-[0.22em] text-indigo-200">Hitos: {{ $proy->hitos_count }}</span>
                    </div>
                    <p class="text-sm text-zinc-400">Verifica evidencia, fechas y documentos asociados a los hitos.</p>
                    <div class="flex flex-wrap gap-2 pt-1">
                        <a href="{{ route('auditor.hitos.proyecto', $proy) }}" class="admin-btn admin-btn-primary text-xs">Ver hitos</a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-zinc-500">No hay proyectos con hitos.</p>
            @endforelse
        </div>

        <div>
            {{ $proyectos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $proyectos->links() : '' }}
        </div>
    </div>
@endsection
