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

    <div class="px-4 sm:px-6 lg:px-8 space-y-4">
        @forelse ($solicitudes as $item)
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-1">
                    <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">ID {{ $item->id }} - {{ $item->estado }}</p>
                    <p class="text-lg font-semibold text-white">{{ $item->proyecto->titulo ?? 'Proyecto' }} - {{ $item->hito ?? 'Hito' }}</p>
                    <p class="text-sm text-zinc-400">Solicita ${{ number_format($item->monto_solicitado, 0, ',', '.') }} • {{ $item->created_at?->format('Y-m-d') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button class="admin-btn admin-btn-primary text-xs">Aprobar</button>
                    <button class="admin-btn admin-btn-ghost text-xs">Rechazar</button>
                    <button class="admin-btn admin-btn-ghost text-xs">Pedir documentos</button>
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
