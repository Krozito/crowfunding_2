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

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-6 relative">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-zinc-900/80 via-zinc-900/70 to-indigo-950/40 p-8 shadow-2xl">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Monitor</p>
                    <h2 class="mt-1 text-2xl font-bold text-white">Supervision de proyectos</h2>
                    <p class="mt-2 text-sm text-zinc-400">
                        Publica, valida y revisa proyectos activos. Selecciona un proyecto para ver sus detalles.
                    </p>
                </div>
                <div class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-xs text-zinc-300">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600/20 text-indigo-200 font-bold">{{ $proyectos->total() }}</span>
                    proyectos listados
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-white/5 text-zinc-300 uppercase text-xs tracking-wide">
                            <tr>
                                <th class="px-4 py-3 text-left">Titulo</th>
                                <th class="px-4 py-3 text-left">Estado</th>
                                <th class="px-4 py-3 text-left">Categoria</th>
                                <th class="px-4 py-3 text-left">Meta</th>
                                <th class="px-4 py-3 text-left">Recaudado</th>
                                <th class="px-4 py-3 text-left">Limite</th>
                                <th class="px-4 py-3 text-left">Ubicacion</th>
                                <th class="px-4 py-3 text-left">Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($proyectos as $proyecto)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-4 py-3 font-semibold text-white">{{ $proyecto->titulo }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-full bg-indigo-500/15 px-2.5 py-1 text-xs font-semibold text-indigo-200">
                                            {{ strtoupper($proyecto->estado ?? 'pendiente') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-zinc-300">{{ $proyecto->categoria ?? 'N/D' }}</td>
                                    <td class="px-4 py-3 text-zinc-300">US$ {{ number_format($proyecto->meta_financiacion, 2) }}</td>
                                    <td class="px-4 py-3 text-zinc-300">US$ {{ number_format($proyecto->monto_recaudado, 2) }}</td>
                                    <td class="px-4 py-3 text-zinc-300">
                                        {{ optional($proyecto->fecha_limite)->format('d/m/Y') ?? 'Sin fecha' }}
                                    </td>
                                    <td class="px-4 py-3 text-zinc-300">{{ $proyecto->ubicacion_geografica ?? 'N/D' }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="inline-flex items-center gap-1 text-indigo-200 hover:text-white text-xs font-semibold">
                                            Ver detalle <span aria-hidden="true">→</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-zinc-400">
                                        No hay proyectos cargados aun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-white/5 px-4 py-3 text-right text-xs text-zinc-400">
                    {{ $proyectos->links() }}
                </div>
            </div>
        </section>
    </main>
</body>
</html>
