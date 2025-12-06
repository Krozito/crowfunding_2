@extends('creator.layouts.panel')

@section('title', 'Proveedores')
@section('active', 'proveedores')

@section('content')
    <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Operaciones</p>
                <h2 class="text-2xl font-bold text-white">Directorio y contratos</h2>
                <p class="text-sm text-zinc-400">Filtra por nombre o proyecto y revisa fichas completas.</p>
            </div>
            <a href="{{ route('creador.proveedores.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                Agregar proveedor <span aria-hidden="true">+</span>
            </a>
        </div>

        <form method="GET" action="{{ route('creador.proveedores') }}" class="mt-6 grid gap-3 sm:grid-cols-[2fr,1fr,auto] sm:items-center">
            <input type="text" name="q" value="{{ $search }}" placeholder="Buscar por nombre, especialidad o contacto"
                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
            <select name="proyecto" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                <option value="">Todos los proyectos</option>
                @foreach ($proyectos as $proyecto)
                    <option value="{{ $proyecto->id }}" @selected($proyectoFiltro == $proyecto->id)>{{ $proyecto->titulo }}</option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                Filtrar
            </button>
        </form>

        <div class="mt-6 grid gap-4">
            @forelse ($proveedores as $prov)
                <article class="rounded-2xl border border-white/10 bg-white/5 p-5 shadow-inner ring-1 ring-indigo-500/10">
                    @php
                        $avg = $prov->calificacion_promedio;
                        $avgBadge = $avg === null
                            ? ['bg' => 'bg-zinc-500/15 text-zinc-200 border border-white/10', 'label' => 'Sin calificacion']
                            : ($avg < 5
                                ? ['bg' => 'bg-red-500/15 text-red-100 border border-red-500/20', 'label' => number_format($avg, 1) . '/10']
                                : ($avg == 5
                                    ? ['bg' => 'bg-amber-400/15 text-amber-100 border border-amber-400/25', 'label' => number_format($avg, 1) . '/10']
                                    : ['bg' => 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/30', 'label' => number_format($avg, 1) . '/10']));
                    @endphp
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-base font-semibold text-white">{{ $prov->nombre_proveedor }}</p>
                            <p class="text-xs text-zinc-400">Especialidad: {{ $prov->especialidad ?? 'Sin especialidad' }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-indigo-500/15 px-3 py-1 text-xs font-semibold text-indigo-100">
                                Proyecto: {{ $prov->proyecto->titulo ?? 'Sin vincular' }}
                            </span>
                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $avgBadge['bg'] }}">
                                Promedio: {{ $avgBadge['label'] }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 grid gap-3 text-sm text-zinc-300 md:grid-cols-3">
                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                            <p class="text-xs text-zinc-500">Contacto</p>
                            <p class="font-medium text-white">{{ $prov->info_contacto ?? 'N/D' }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                            <p class="text-xs text-zinc-500">Creado</p>
                            <p class="font-medium text-white">{{ $prov->created_at?->format('d/m/Y') }}</p>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                            <p class="text-xs text-zinc-500">ID</p>
                            <p class="font-medium text-white">#{{ $prov->id }}</p>
                        </div>
                        <div class="md:col-span-3 flex flex-wrap justify-end gap-2">
                            <a href="{{ route('creador.proveedores.show', $prov) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                Ver historial
                            </a>
                            <a href="{{ route('creador.proveedores.edit', $prov) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                Editar proveedor
                            </a>
                            <form method="POST" action="{{ route('creador.proveedores.destroy', $prov) }}" onsubmit="return confirm('¿Eliminar este proveedor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-sm text-zinc-400">No se encontraron proveedores con los filtros actuales.</p>
            @endforelse
        </div>

        <div class="mt-4 text-right text-xs text-zinc-400">
            {{ $proveedores->links() }}
        </div>
    </section>
@endsection
