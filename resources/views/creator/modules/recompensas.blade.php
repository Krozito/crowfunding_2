@extends('creator.layouts.panel')

@section('title', 'Recompensas')
@section('active', 'recompensas')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/15 space-y-4">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm text-zinc-400">Administra niveles: crea o edita, ordena/pausa y revisa como se ven.</p>
                    <h2 class="text-2xl font-bold text-white">Panel de recompensas</h2>
                </div>
                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                    <a href="{{ route('creador.recompensas.gestion', ['proyecto' => $selectedProjectId]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-500">
                        gestion recompensas
                    </a>
                    <a href="{{ route('creador.recompensas.preview', ['proyecto' => $selectedProjectId]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-500">
                        Abrir previsualizacion
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('creador.recompensas') }}" class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-center">
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
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                Ver recompensas
            </button>
        </form>

            <div class="grid gap-4 lg:grid-cols-[260px,1fr]">
                <div class="space-y-2">
                    @forelse ($niveles as $nivel)
                        @php
                            $estadoStyles = $nivel['estado'] === 'activo'
                                ? ['bg' => 'bg-emerald-500/15 text-emerald-200 border border-emerald-500/25', 'label' => 'Activo']
                                : ['bg' => 'bg-amber-400/15 text-amber-100 border border-amber-400/25', 'label' => 'Pausado'];
                        @endphp
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white ring-1 ring-indigo-500/10">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="font-semibold">{{ $nivel['titulo'] }}</p>
                                    <p class="text-xs text-zinc-400">Desde USD {{ number_format($nivel['monto'], 2) }}</p>
                                    <p class="text-[11px] text-indigo-200">Proyecto: {{ $nivel['proyecto'] }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $estadoStyles['bg'] }} {{ $estadoStyles['border'] ?? '' }}">{{ $estadoStyles['label'] }}</span>
                            </div>
                            <p class="mt-1 text-xs text-zinc-400 line-clamp-2">{{ $nivel['descripcion'] }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">No hay recompensas cargadas para este proyecto.</p>
                    @endforelse
                </div>

                <div class="rounded-2xl border border-indigo-500/30 bg-white/5 p-5 shadow-inner">
                    @if ($preview)
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-indigo-200">Desde USD {{ number_format($preview['monto'], 2) }}</p>
                                <h4 class="mt-1 text-xl font-bold text-white">{{ $preview['titulo'] }}</h4>
                                <p class="text-[11px] text-indigo-200">Proyecto: {{ $preview['proyecto'] }}</p>
                                <p class="mt-1 text-sm text-zinc-300">{{ $preview['descripcion'] }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-[11px] font-semibold text-emerald-200">{{ $preview['disponibles'] !== null ? $preview['disponibles'] : 'N/D' }} disponibles</span>
                        </div>
                        <div class="mt-3">
                            <p class="text-xs text-zinc-400">Beneficios</p>
                            <ul class="mt-2 space-y-1 text-sm text-white">
                                @foreach ($preview['beneficios'] as $beneficio)
                                    <li class="flex items-center gap-2"><span class="text-indigo-300">-</span> {{ $beneficio }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2 text-xs text-indigo-100">
                            <span class="rounded-full bg-white/10 px-3 py-1">Entrega: {{ $preview['entrega'] }}</span>
                            <span class="rounded-full bg-white/10 px-3 py-1">Orden: {{ $preview['orden'] }}</span>
                            <span class="rounded-full bg-white/10 px-3 py-1">{{ $preview['estado'] === 'activo' ? 'Disponible' : 'Pausado' }}</span>
                        </div>
                        <button class="mt-5 w-full rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                            Asi lo ve el colaborador: Aportar USD {{ number_format($preview['monto'], 2) }}
                        </button>
                    @else
                        <p class="text-sm text-zinc-400">Selecciona un proyecto con recompensas para ver la previsualizacion.</p>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection
