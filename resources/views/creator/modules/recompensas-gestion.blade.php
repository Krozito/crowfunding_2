@extends('creator.layouts.panel')

@section('title', 'Gestionar recompensas')
@section('active', 'recompensas')
@section('back_url', route('creador.recompensas'))
@section('back_label', 'Volver a recompensas')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/15 space-y-3">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Orden y estados</h2>
                    <p class="text-sm text-zinc-400">Sube/baja prioridad, pausa o elimina. Controla stock por proyecto.</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-zinc-300">
                    <a href="{{ route('creador.recompensas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-500">Crear nueva recompensa</a>
                </div>
            </div>

            <form method="GET" action="{{ route('creador.recompensas.gestion') }}" class="grid gap-3 sm:grid-cols-[1fr,1fr,auto] sm:items-end">
                <div>
                    <label class="text-xs text-zinc-400">Proyecto</label>
                    <select name="proyecto" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        @forelse ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" @selected($selectedProjectId == $proyecto->id)>{{ $proyecto->titulo }}</option>
                        @empty
                            <option value="">Sin proyectos disponibles</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="text-xs text-zinc-400">Estado</label>
                    <select name="estado" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Todos</option>
                        <option value="activo" @selected($estadoFiltro === 'activo')>Activo</option>
                        <option value="pausado" @selected($estadoFiltro === 'pausado')>Pausado</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                    Filtrar
                </button>
            </form>

            <div class="space-y-4">
                @forelse ($niveles as $nivel)
                    @php
                        $estadoStyles = $nivel['estado'] === 'activo'
                            ? ['bg' => 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/30', 'label' => 'Activo']
                            : ['bg' => 'bg-amber-400/15 text-amber-100 border border-amber-400/30', 'label' => 'Pausado'];
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-base font-semibold text-white">{{ $nivel['titulo'] }}</p>
                                <p class="text-xs text-zinc-400">{{ $nivel['descripcion'] }}</p>
                                <p class="text-[11px] text-indigo-200">Proyecto: {{ $nivel['proyecto'] }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full bg-indigo-500/15 px-3 py-1 text-xs font-semibold text-indigo-100">USD {{ number_format($nivel['monto'], 2) }}</span>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $estadoStyles['bg'] }} {{ $estadoStyles['label'] === 'Activo' ? 'border border-emerald-500/30' : 'border border-amber-400/30' }}">{{ $estadoStyles['label'] }}</span>
                                <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Orden: {{ $nivel['orden'] }}</span>
                            </div>
                        </div>

                        <div class="mt-3 grid gap-3 text-xs text-zinc-300 md:grid-cols-3">
                    <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                        <p class="text-[11px] text-zinc-500">Disponibles</p>
                        <p class="text-sm font-semibold text-white">{{ $nivel['disponibles'] !== null ? $nivel['disponibles'] : 'N/D' }}</p>
                    </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                <p class="text-[11px] text-zinc-500">Entrega estimada</p>
                                <p class="text-sm font-semibold text-white">{{ $nivel['entrega'] }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                <p class="text-[11px] text-zinc-500">Beneficios</p>
                                <p class="text-sm font-semibold text-white">{{ implode(', ', $nivel['beneficios']) }}</p>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold">
                            <a href="{{ route('creador.recompensas.edit', $nivel['id']) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-500">Editar</a>
                            <form method="POST" action="{{ route('creador.recompensas.estado', $nivel['id']) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-500">{{ $nivel['estado'] === 'activo' ? 'Pausar' : 'Reactivar' }}</button>
                            </form>
                            <form method="POST" action="{{ route('creador.recompensas.destroy', $nivel['id']) }}" onsubmit="return confirm('Eliminar esta recompensa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-500">Eliminar</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-zinc-400">No hay recompensas para este proyecto.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
