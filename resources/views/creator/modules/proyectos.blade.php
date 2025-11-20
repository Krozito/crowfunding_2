<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyectos | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-20 top-0 h-64 w-64 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    ← Volver al panel
                </a>
                <h1 class="text-lg font-semibold text-white">Proyectos</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Campañas</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Publicación y configuración</h2>
            <p class="mt-2 text-sm text-zinc-400">
                Aquí administraremos creación, edición y validación previa al lanzamiento (HU4, HU7).
            </p>
            <div class="mt-6 rounded-2xl border border-dashed border-white/15 bg-white/5 p-6 text-sm text-zinc-300">
                Próximamente: listado de proyectos, estados (borrador, revisión, publicado), metas, cronograma y presupuesto.
            </div>
        </section>
    </main>
</body>
</html>
