@extends('admin.layouts.panel')

@section('title', 'Proyectos')
@section('active', 'proyectos')

@section('content')
    @php
        $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
    @endphp

    <div class="space-y-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 shadow-2xl ring-1 ring-indigo-500/10 admin-accent-card">
            <div class="border-b border-white/5 px-6 py-6 space-y-4">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Monitor</p>
                        <h2 class="mt-1 text-2xl font-bold text-white">Supervision de proyectos</h2>
                        <p class="mt-2 text-sm text-zinc-400">
                            Publica, valida y revisa proyectos activos. Selecciona un proyecto para ver sus detalles.
                        </p>
                        <p class="mt-2 text-xs text-zinc-500">
                            Mostrando {{ $proyectos->count() }} de {{ $proyectos->total() }} proyectos - En revisión: {{ $estadoResumen['en_revision'] ?? 0 }}, Publicados: {{ $estadoResumen['publicado'] ?? 0 }}, Pausados: {{ $estadoResumen['pausado'] ?? 0 }}
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                        <a href="{{ route('admin.proyectos.config') }}" class="admin-btn admin-btn-ghost">
                            Gestionar catalogos
                        </a>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.proyectos') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,1fr,1fr,auto] sm:items-end">
                    <div>
                        <label class="text-xs text-zinc-400">Busqueda</label>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Titulo, categoria o ubicacion"
                               class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Estado</label>
                        <select name="estado" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todos</option>
                            <option value="borrador" @selected($estado === 'borrador')>Borrador</option>
                            <option value="pendiente" @selected($estado === 'pendiente')>Pendiente</option>
                            <option value="en_revision" @selected($estado === 'en_revision')>En revisión</option>
                            <option value="publicado" @selected($estado === 'publicado')>Publicado</option>
                            <option value="pausado" @selected($estado === 'pausado')>Pausado</option>
                            <option value="rechazado" @selected($estado === 'rechazado')>Rechazado</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Categoria</label>
                        <select name="categoria" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todas</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat }}" @selected($categoria === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Modelo</label>
                        <select name="modelo" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todos</option>
                            @foreach ($modelos as $model)
                                <option value="{{ $model }}" @selected($modelo === $model)>{{ $model }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="{{ $btnSolid }}">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.proyectos') }}" class="admin-btn admin-btn-ghost">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="divide-y divide-white/5">
                <div class="grid grid-cols-6 gap-3 px-4 py-3 text-[11px] font-semibold uppercase tracking-wide text-zinc-300">
                    <span>Proyecto</span>
                    <span>Categoria</span>
                    <span>Modelo</span>
                    <span>Estado</span>
                    <span>Recaudado</span>
                    <span class="text-right">Acciones</span>
                </div>
                @forelse ($proyectos as $proyecto)
                    @php
                        $estadoClase = match ($proyecto->estado) {
                            'publicado' => 'text-emerald-200',
                            'pendiente', 'en_revision' => 'text-amber-200',
                            'pausado', 'rechazado' => 'text-rose-200',
                            default => 'text-zinc-300',
                        };
                    @endphp
                    <div class="grid grid-cols-6 gap-3 px-4 py-3 text-sm text-white items-center">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ $proyecto->titulo }}</span>
                            <span class="text-xs text-zinc-400">Creador: {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'N/D' }}</span>
                        </div>
                        <span class="text-zinc-300">{{ $proyecto->categoria ?? 'N/D' }}</span>
                        <span class="text-zinc-300">{{ $proyecto->modelo_financiamiento ?? 'N/D' }}</span>
                        <span class="{{ $estadoClase }}">{{ ucfirst(str_replace('_', ' ', $proyecto->estado ?? 'N/D')) }}</span>
                        <span class="text-zinc-200">US$ {{ number_format($recaudadoPorProyecto[$proyecto->id] ?? 0, 2) }}</span>
                        <div class="text-right flex flex-wrap justify-end gap-2 text-xs">
                            <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="{{ $btnSolid }}">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-zinc-400">
                        No hay proyectos cargados aun.
                    </p>
                @endforelse
            </div>

            <div class="border-t border-white/5 px-4 py-3 text-right text-xs text-zinc-400">
                {{ $proyectos->links() }}
            </div>
        </section>
    </div>
@endsection
