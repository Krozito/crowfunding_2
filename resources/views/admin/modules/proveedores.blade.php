@extends('admin.layouts.panel')

@section('title', 'Proveedores')
@section('active', 'proveedores')

@section('content')
    <div class="space-y-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4 admin-accent-card">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Network</p>
                    <h2 class="text-2xl font-bold text-white">Directorio y performance</h2>
                    <p class="text-sm text-zinc-400">Audita proveedores, rating y vinculacion a proyectos.</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs text-zinc-300"></div>
            </div>

            <form class="grid gap-3 md:grid-cols-[2fr,1fr,1fr,auto]" method="GET" action="{{ route('admin.proveedores') }}">
                <div>
                    <label class="text-xs text-zinc-400">Busqueda</label>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Nombre, especialidad o contacto"
                           class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-xs text-zinc-400">Proyecto</label>
                    <select name="proyecto" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                        <option value="">Todos</option>
                        @foreach ($proyectos as $proy)
                            <option value="{{ $proy->id }}" @selected($proyectoFiltro == $proy->id)>{{ $proy->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-zinc-400">Creador</label>
                    <select name="creador" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                        <option value="">Todos</option>
                        @foreach ($creadores as $cre)
                            <option value="{{ $cre->id }}" @selected($creadorFiltro == $cre->id)>{{ $cre->nombre_completo ?? $cre->name }}</option>
                        @endforeach
                    </select>
                </div>
                @php
                    $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
                @endphp
                <div class="flex gap-2">
                    <button type="submit" class="{{ $btnSolid }}">Filtrar</button>
                    <a href="{{ route('admin.proveedores') }}" class="admin-btn admin-btn-ghost">Limpiar</a>
                </div>
            </form>

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Total proveedores</p>
                    <p class="mt-1 text-2xl font-bold text-white">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Con proyecto asignado</p>
                    <p class="mt-1 text-2xl font-bold text-white">{{ $stats['conProyecto'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-xs uppercase tracking-[0.24em] text-white/70">Calificacion promedio</p>
                    <p class="mt-1 text-2xl font-bold text-white">{{ $stats['calificacionPromedio'] ?? 0 }}</p>
                </div>
            </div>

            <div class="divide-y divide-white/5">
                <div class="grid grid-cols-5 gap-3 px-4 py-3 text-[11px] font-semibold uppercase tracking-wide text-zinc-300">
                    <span>Proveedor</span>
                    <span>Proyecto</span>
                    <span>Calificacion</span>
                    <span>Especialidad</span>
                    <span class="text-right">Acciones</span>
                </div>

                @forelse($proveedores as $prov)
                    <div class="grid grid-cols-5 gap-3 px-4 py-3 text-sm text-white items-center">
                        <div class="space-y-1">
                            <p class="font-semibold">{{ $prov->nombre_proveedor }}</p>
                            <p class="text-xs text-zinc-400">{{ $prov->info_contacto }}</p>
                        </div>
                        <span class="text-zinc-300">{{ $prov->proyecto->titulo ?? 'Sin proyecto' }}</span>
                        <span class="text-zinc-200">{{ $prov->calificacion_promedio ?? 'N/D' }}</span>
                        <span class="text-zinc-300">{{ $prov->especialidad ?? 'N/D' }}</span>
                        <div class="text-right text-xs">
                            <a href="{{ route('admin.proveedores.show', $prov) }}" class="{{ $btnSolid }}">Ver</a>
                        </div>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-zinc-400">No hay proveedores registrados.</p>
                @endforelse
            </div>

            <div class="border-t border-white/5 pt-3 text-right text-xs text-zinc-400">
                {{ $proveedores->links() }}
            </div>
        </section>
    </div>
@endsection
