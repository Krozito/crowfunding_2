<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historial de solicitudes | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-24 top-0 h-64 w-64 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.fondos') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a fondos
                </a>
                <h1 class="text-lg font-semibold text-white">Historial de solicitudes</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                    <h2 class="text-2xl font-bold text-white">Solicitudes de desembolso</h2>
                    <p class="text-sm text-zinc-400">Consulta todas las solicitudes con sus hitos, estados y adjuntos.</p>
                </div>
                <div class="text-xs text-zinc-300">
                    <span class="rounded-full bg-white/5 px-3 py-1">Total proyectos: {{ $proyectos->count() }}</span>
                </div>
            </div>

            <form method="GET" action="{{ route('creador.fondos.historial') }}" class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-end">
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
                    Ver historial
                </button>
            </form>
        </section>

        @if ($proyectos->isEmpty())
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 text-sm text-zinc-300 shadow-2xl">
                No tienes proyectos creados aun. <a class="text-indigo-300 underline" href="{{ route('creador.proyectos') }}">Crea un proyecto</a> para gestionar solicitudes.
            </section>
        @else
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Historial</p>
                        <h3 class="text-lg font-bold text-white">Solicitudes del proyecto</h3>
                        <p class="text-sm text-zinc-400">Incluye monto, hito, estado, fecha y adjuntos enviados.</p>
                    </div>
                    <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Total: {{ $solicitudes->count() }}</span>
                </div>

                <div class="space-y-3">
                    @forelse ($solicitudes as $solicitud)
                        @php
                            $map = [
                                'pendiente' => 'bg-amber-500/15 text-amber-100 border border-amber-400/30',
                                'aprobado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                'liberado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                'pagado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                'rechazado' => 'bg-red-500/15 text-red-100 border border-red-400/30',
                                'gastado' => 'bg-sky-500/15 text-sky-100 border border-sky-400/30',
                            ];
                            $badge = $map[$solicitud->estado] ?? 'bg-white/10 text-white border border-white/20';
                        @endphp
                        <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-2">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $solicitud->hito ?? 'Hito no asignado' }}</p>
                                    <p class="text-xs text-zinc-400">{{ $solicitud->created_at?->format('d/m/Y H:i') }} - Fecha estimada: {{ $solicitud->fecha_estimada?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                    <p class="text-sm text-zinc-200">Monto: USD {{ number_format($solicitud->monto_solicitado, 2) }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge }}">{{ ucfirst($solicitud->estado) }}</span>
                            </div>
                            <p class="text-sm text-zinc-300">{{ $solicitud->descripcion ?? 'Sin descripcion' }}</p>
                            <div class="text-xs text-zinc-400">
                                <p class="font-semibold text-white">Proveedores:</p>
                                <p>{{ !empty($solicitud->proveedores) ? implode(', ', $solicitud->proveedores) : 'Sin proveedores especificados' }}</p>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                                <p class="text-[11px] text-zinc-500">Adjuntos</p>
                                @if (!empty($solicitud->adjuntos))
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @foreach ($solicitud->adjuntos as $idx => $archivo)
                                            <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1 hover:border-indigo-400/60">
                                                Archivo {{ $idx + 1 }}
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-1 text-white">Sin adjuntos</p>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="text-sm text-zinc-400">No hay solicitudes registradas para este proyecto.</p>
                    @endforelse
                </div>
            </section>
        @endif
    </main>
</body>
</html>
