<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Previsualizar recompensas | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-20 top-0 h-64 w-64 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.recompensas') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a recompensas
                </a>
                <h1 class="text-lg font-semibold text-white">Previsualizar recompensas</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/15 space-y-3">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white">Asi lo vera el colaborador</h2>
                    <p class="text-sm text-zinc-400">Selecciona un proyecto y un nivel para ver el detalle antes de publicar.</p>
                </div>
                <a href="{{ route('creador.recompensas.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-4 py-2 text-xs font-semibold text-white hover:border-indigo-400/60 hover:text-indigo-100">
                    Crear nuevo nivel
                </a>
            </div>

            <form method="GET" action="{{ route('creador.recompensas.preview') }}" class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-center">
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
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white hover:border-indigo-400/60">
                    Ver recompensas
                </button>
            </form>

            <div class="grid gap-4 md:grid-cols-[260px,1fr]">
                <div class="space-y-2">
                    @forelse ($niveles as $nivel)
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-white ring-1 ring-indigo-500/10">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <p class="font-semibold">{{ $nivel['titulo'] }}</p>
                                    <p class="text-xs text-zinc-400">Desde USD {{ number_format($nivel['monto'], 2) }}</p>
                                    <p class="text-[11px] text-indigo-200">Proyecto: {{ $nivel['proyecto'] }}</p>
                                </div>
                                <span class="text-[11px] text-zinc-400">Orden {{ $nivel['orden'] }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">No hay recompensas para este proyecto.</p>
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
                        <p class="text-sm text-zinc-400">Selecciona un proyecto con recompensas para previsualizar.</p>
                    @endif
                </div>
            </div>
        </section>
    </main>
</body>
</html>
