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

    <div class="px-4 sm:px-6 lg:px-8 grid gap-4 md:grid-cols-3">
        @forelse ($hitos as $item)
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyecto ID {{ $item->proyecto_id }}</p>
                <h3 class="text-lg font-semibold text-white">{{ $item->titulo }}</h3>
                <p class="text-sm text-zinc-400">{{ $item->fecha_publicacion?->format('Y-m-d') ?? 'Sin fecha' }}</p>
                <div class="flex flex-wrap gap-2 pt-1">
                    <button class="admin-btn admin-btn-primary text-xs">Validar</button>
                    <button class="admin-btn admin-btn-ghost text-xs">Observar</button>
                    <button class="admin-btn admin-btn-ghost text-xs border-red-400/60 text-red-200">Rechazar</button>
                </div>
            </div>
        @empty
            <p class="text-sm text-zinc-500">No hay hitos registrados.</p>
        @endforelse
    </div>
@endsection
