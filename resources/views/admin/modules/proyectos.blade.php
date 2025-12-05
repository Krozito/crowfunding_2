<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyectos | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-32 top-0 h-72 w-72 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/15 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al dashboard
                </a>
                <h1 class="text-lg font-semibold text-white">Modulo de proyectos</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8 relative">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Monitor</p>
                    <h2 class="mt-1 text-2xl font-bold text-white">Supervision de proyectos</h2>
                    <p class="mt-2 text-sm text-zinc-400">
                        Publica, valida y revisa proyectos activos. Selecciona un proyecto para ver sus detalles.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                    <span class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600/20 text-indigo-200 font-bold">{{ $proyectos->total() }}</span>
                        Proyectos listados
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <form method="GET" action="{{ route('admin.proyectos') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,auto] sm:items-end">
                    <div>
                        <label class="text-xs text-zinc-400">Busqueda</label>
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Titulo, categoria o ubicacion"
                               class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.proyectos') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/5">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @forelse ($proyectos as $proyecto)
                    @php
                        $estadoStyles = [
                            'aprobado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                            'pendiente' => 'bg-amber-500/15 text-amber-100 border border-amber-400/30',
                            'rechazado' => 'bg-red-500/15 text-red-100 border border-red-400/30',
                        ];
                        $badge = $estadoStyles[$proyecto->estado] ?? 'bg-white/10 text-white border border-white/20';
                    @endphp
                    <article class="flex h-full flex-col rounded-2xl border border-white/10 bg-white/5 p-5 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-lg font-semibold text-white">{{ $proyecto->titulo }}</p>
                                <p class="text-xs text-zinc-400">Creador: {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'N/D' }}</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge }}">{{ strtoupper($proyecto->estado ?? 'PENDIENTE') }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 text-sm text-zinc-200 flex-1">
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                <p class="text-[11px] text-zinc-500">Categoria</p>
                                <p class="font-semibold">{{ $proyecto->categoria ?? 'N/D' }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                <p class="text-[11px] text-zinc-500">Ubicacion</p>
                                <p class="font-semibold">{{ $proyecto->ubicacion_geografica ?? 'N/D' }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                <p class="text-[11px] text-zinc-500">Meta</p>
                                <p class="font-semibold">US$ {{ number_format($proyecto->meta_financiacion, 2) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                <p class="text-[11px] text-zinc-500">Recaudado</p>
                                <p class="font-semibold">US$ {{ number_format($proyecto->monto_recaudado, 2) }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                <p class="text-[11px] text-zinc-500">Fecha limite</p>
                                <p class="font-semibold">{{ optional($proyecto->fecha_limite)->format('d/m/Y') ?? 'Sin fecha' }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                <p class="text-[11px] text-zinc-500">Creado</p>
                                <p class="font-semibold">{{ $proyecto->created_at?->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-semibold">
                            <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/15 px-3 py-2 text-white hover:border-indigo-400/60">
                                Ver detalle
                            </a>
                        </div>
                    </article>
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
    </main>
</body>
</html>
