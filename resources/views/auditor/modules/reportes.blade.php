@extends('auditor.layouts.panel')

@section('title', 'Reportes sospechosos')
@section('active', 'sospechosos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 4</p>
            <h1 class="text-2xl font-bold text-white">Reportes sospechosos de colaboradores</h1>
            <p class="text-sm text-zinc-400">Centralizar denuncias, cruzar con gastos y cerrar casos.</p>
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

    <div class="px-4 sm:px-6 lg:px-8 space-y-3">
        @forelse ($reportesColab as $item)
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">{{ $item['id'] ?? 'ID' }} - {{ $item['estado'] ?? '' }}</p>
                        <p class="text-lg font-semibold text-white">{{ $item['proyecto'] ?? 'Proyecto' }}</p>
                        <p class="text-sm text-zinc-400">Gasto {{ $item['gasto'] ?? '' }} - {{ $item['comentario'] ?? '' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button class="admin-btn admin-btn-primary text-xs">Marcar investigado</button>
                        <button class="admin-btn admin-btn-ghost text-xs">Marcar valido</button>
                        <button class="admin-btn admin-btn-ghost text-xs border-red-400/60 text-red-200">Sospechoso</button>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-xs text-zinc-400">
                    <span class="inline-flex h-2 w-2 rounded-full bg-amber-300"></span>
                    Si es real se rechaza el gasto, si es falso se cierra el reporte.
                </div>
            </div>
        @empty
            <p class="text-sm text-zinc-500">No hay reportes de colaboradores registrados.</p>
        @endforelse
    </div>
@endsection
