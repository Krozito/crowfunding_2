@extends('auditor.layouts.panel')

@section('title', 'Auditoria de proyectos')
@section('active', 'proyectos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 6</p>
            <h1 class="text-2xl font-bold text-white">Auditoria de proyectos</h1>
            <p class="text-sm text-zinc-400">Linea de tiempo con gastos, desembolsos, documentos y decisiones.</p>
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
        <div class="grid gap-4 md:grid-cols-3">
            @forelse ($proyectos as $item)
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 admin-accent-card">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-white">{{ $item->titulo }}</p>
                        <span class="text-xs uppercase tracking-[0.22em] text-amber-100">{{ $item->estado ?? 'N/D' }}</span>
                    </div>
                    <p class="text-sm text-zinc-400">Meta: ${{ number_format($item->meta_financiacion, 0, ',', '.') }} • Recaudado: ${{ number_format($item->monto_recaudado ?? 0, 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-white">Categoria: {{ $item->categoria ?? 'Sin categoria' }}</p>
                    <p class="text-xs text-zinc-400">Creado: {{ $item->created_at?->format('Y-m-d') }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <button class="admin-btn admin-btn-primary text-xs">Marcar en riesgo</button>
                        <button class="admin-btn admin-btn-ghost text-xs">Congelar fondos</button>
                        <button class="admin-btn admin-btn-ghost text-xs">Ver timeline</button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-zinc-500">No hay proyectos registrados.</p>
            @endforelse
        </div>
    </div>
@endsection
