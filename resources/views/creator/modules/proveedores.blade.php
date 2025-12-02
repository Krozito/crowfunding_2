<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proveedores | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-20 top-0 h-64 w-64 rounded-full bg-emerald-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-lime-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al panel
                </a>
                <h1 class="text-lg font-semibold text-white">Proveedores</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-emerald-500/10">
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
                       class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-emerald-400 focus:ring-emerald-400">
                <select name="proyecto" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                    <option value="">Todos los proyectos</option>
                    @foreach ($proyectos as $proyecto)
                        <option value="{{ $proyecto->id }}" @selected($proyectoFiltro == $proyecto->id)>{{ $proyecto->titulo }}</option>
                    @endforeach
                </select>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">
                    Filtrar
                </button>
            </form>

            <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-zinc-400">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 px-3 py-1">
                    Total: <strong class="text-white">{{ $totalProveedores }}</strong>
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 px-3 py-1">
                    Mostrando: <strong class="text-white">{{ $proveedores->total() }}</strong>
                </span>
            </div>

            <div class="mt-6 grid gap-4">
                @forelse ($proveedores as $prov)
                    <article class="rounded-2xl border border-white/10 bg-white/5 p-5 shadow-inner ring-1 ring-emerald-500/10">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-base font-semibold text-white">{{ $prov->nombre_proveedor }}</p>
                                <p class="text-xs text-zinc-400">Especialidad: {{ $prov->especialidad ?? 'Sin especialidad' }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-200">
                                Proyecto: {{ $prov->proyecto->titulo ?? 'Sin vincular' }}
                            </span>
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
                            <div class="md:col-span-3 flex justify-end">
                                <a href="{{ route('creador.proveedores.edit', $prov) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2 text-xs font-semibold text-white hover:border-emerald-400/60 hover:text-emerald-100">
                                    Editar proveedor
                                </a>
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
    </main>
</body>
</html>
