<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Config Proyectos | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden bg-zinc-950">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/30 blur-2xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/25 blur-2xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al dashboard
                </a>
                <h1 class="text-lg font-semibold text-white">Config. Categorias y Modelos</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'proyectos'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-6 admin-accent-card">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                            <h2 class="text-2xl font-bold text-white">Gestionar catalogos</h2>
                            <p class="text-sm text-zinc-400">Define categorias y modelos de financiamiento para que los creadores los seleccionen.</p>
                        </div>
                        @if (session('status'))
                            <span class="rounded-full bg-indigo-500/15 px-3 py-1 text-[11px] font-semibold text-indigo-200">{{ session('status') }}</span>
                        @endif
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <article class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-inner ring-1 ring-indigo-500/10 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Catalogo</p>
                                    <h3 class="text-lg font-semibold text-white">Categorias</h3>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.proyectos.categorias.store') }}" class="flex gap-2">
                                @csrf
                                <input name="nombre" required maxlength="120" class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-2.5 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="Nueva categoria">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    Agregar
                                </button>
                            </form>
                            <div class="space-y-2 text-sm text-zinc-300 max-h-72 overflow-y-auto">
                                @forelse ($categorias as $categoria)
                                    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-zinc-900/70 px-3 py-2">
                                        <span>{{ $categoria->nombre }}</span>
                                        <form method="POST" action="{{ route('admin.proyectos.categorias.destroy', $categoria) }}" onsubmit="return confirm('¿Eliminar categoria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-500">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-xs text-zinc-400">Sin categorias registradas.</p>
                                @endforelse
                            </div>
                        </article>

                        <article class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-inner ring-1 ring-indigo-500/10 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Catalogo</p>
                                    <h3 class="text-lg font-semibold text-white">Modelos de financiamiento</h3>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.proyectos.modelos.store') }}" class="flex gap-2">
                                @csrf
                                <input name="nombre" required maxlength="120" class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-2.5 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="Nuevo modelo">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                    Agregar
                                </button>
                            </form>
                            <div class="space-y-2 text-sm text-zinc-300 max-h-72 overflow-y-auto">
                                @forelse ($modelos as $modelo)
                                    <div class="flex items-center justify-between rounded-xl border border-white/10 bg-zinc-900/70 px-3 py-2">
                                        <span>{{ $modelo->nombre }}</span>
                                        <form method="POST" action="{{ route('admin.proyectos.modelos.destroy', $modelo) }}" onsubmit="return confirm('¿Eliminar modelo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-500">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-xs text-zinc-400">Sin modelos registrados.</p>
                                @endforelse
                            </div>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
