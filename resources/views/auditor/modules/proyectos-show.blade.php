@extends('auditor.layouts.panel')

@section('title', 'Proyecto')
@section('active', 'proyectos')
@section('back_url', route('auditor.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 pt-6 space-y-6">
        <div class="relative overflow-hidden rounded-[22px] bg-gradient-to-r from-indigo-700/30 via-sky-700/20 to-emerald-600/25 border border-white/10 px-6 py-6 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_45%)] blur-[1px]"></div>
            <div class="relative flex flex-col gap-2">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Proyecto</p>
                <h1 class="text-2xl font-extrabold text-white">{{ $proyecto->titulo }}</h1>
                <p class="text-sm text-white/80">Estado: {{ $proyecto->estado ?? 'pendiente' }}</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Finanzas</p>
                <p class="text-sm text-zinc-300">Meta: ${{ number_format($proyecto->meta_financiacion, 0, ',', '.') }}</p>
                <p class="text-sm text-zinc-300">Recaudado: ${{ number_format($proyecto->monto_recaudado ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Categoria</p>
                <p class="text-sm text-zinc-300">{{ $proyecto->categoria ?? 'Sin categoria' }}</p>
                <p class="text-xs text-zinc-400">Creado: {{ $proyecto->created_at?->format('Y-m-d') }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Acciones</p>
                <form method="POST" action="{{ route('auditor.proyectos.publicacion', $proyecto) }}" class="flex flex-wrap gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="accion" value="permitir" id="accion-publicacion">
                    <button type="submit" onclick="document.getElementById('accion-publicacion').value='permitir'" class="admin-btn admin-btn-primary text-xs">Permitir publicación</button>
                    <button type="submit" onclick="document.getElementById('accion-publicacion').value='pausar'" class="admin-btn admin-btn-ghost text-xs">Pausar publicación</button>
                </form>
            </div>
        </div>
    </div>
@endsection
