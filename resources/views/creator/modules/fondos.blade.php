<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fondos | CrowdUp Creador</title>
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
                <a href="{{ route('creador.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al panel
                </a>
                <h1 class="text-lg font-semibold text-white">Fondos y desembolsos</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                    <h2 class="text-2xl font-bold text-white">Centro financiero de cada proyecto</h2>
                    <p class="text-sm text-zinc-400">Visualiza recaudado, escrow, liberado, gastado y solicita desembolsos guiados.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                    <span class="rounded-full bg-white/5 px-3 py-1">Total proyectos: {{ $proyectos->count() }}</span>
                    <a href="{{ route('creador.fondos.historial', ['proyecto' => $selectedProjectId]) }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2 text-white hover:border-indigo-400/60">
                        Ver historial completo
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('creador.fondos') }}" class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-end">
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
                    Ver fondos
                </button>
            </form>
        </section>

        @if ($proyectos->isEmpty())
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 text-sm text-zinc-300 shadow-2xl">
                No tienes proyectos creados aun. <a class="text-indigo-300 underline" href="{{ route('creador.proyectos') }}">Crea un proyecto</a> para gestionar fondos.
            </section>
        @else
            <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Dashboard financiero</p>
                            <h3 class="text-lg font-bold text-white">Estado del proyecto</h3>
                            <p class="text-sm text-zinc-400">Revisa lo retenido en escrow, liberado, gastado y lo disponible para solicitar.</p>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Proyecto #{{ $selectedProjectId }}</span>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Total recaudado</p>
                            <p class="text-2xl font-bold text-white">USD {{ number_format($finanzas['recaudado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Fondos retenidos (escrow)</p>
                            <p class="text-xl font-bold text-amber-100">USD {{ number_format($finanzas['retenido'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Fondos liberados</p>
                            <p class="text-xl font-bold text-emerald-100">USD {{ number_format($finanzas['liberado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Fondos gastados</p>
                            <p class="text-xl font-bold text-rose-100">USD {{ number_format($finanzas['gastado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Solicitudes pendientes</p>
                            <p class="text-xl font-bold text-indigo-100">USD {{ number_format($finanzas['pendiente'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Fondos disponibles para solicitar</p>
                            <p class="text-2xl font-bold text-emerald-200">USD {{ number_format($finanzas['disponible'], 2) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4">
                        <p class="text-sm font-semibold text-white">Estado de hitos financieros</p>
                        <p class="text-xs text-zinc-400">Consulta cada solicitud y su hito asociado.</p>
                        <div class="mt-3 space-y-2">
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
                                <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white">
                                    <div>
                                        <p class="font-semibold">{{ $solicitud->hito }}</p>
                                        <p class="text-[12px] text-zinc-400">Monto: USD {{ number_format($solicitud->monto_solicitado, 2) }} - Fecha estimada: {{ $solicitud->fecha_estimada?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge }}">{{ ucfirst($solicitud->estado) }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-zinc-400">Sin solicitudes registradas para este proyecto.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-emerald-500/10 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Solicitar desembolso</p>
                            <h3 class="text-lg font-bold text-white">Crea una solicitud</h3>
                            <p class="text-sm text-zinc-400">Valida monto, hito y adjunta documentacion inicial.</p>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Disponible: USD {{ number_format($finanzas['disponible'], 2) }}</span>
                    </div>

                    <form method="POST" action="{{ route('creador.fondos.solicitudes.store', $selectedProjectId) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-sm text-zinc-300">Monto solicitado *</label>
                            <input type="number" name="monto_solicitado" step="0.01" min="0" value="{{ old('monto_solicitado') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. 1500.00" required>
                            <p class="text-[11px] text-zinc-500 mt-1">No puede superar los fondos disponibles.</p>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Hito financiero *</label>
                            <input name="hito" value="{{ old('hito') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. Entrega fase beta" required>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Descripcion del uso</label>
                            <textarea name="descripcion" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Explica en que usaras el desembolso">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="text-sm text-zinc-300">Proveedores involucrados</label>
                                <input name="proveedores" value="{{ old('proveedores') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. Proveedor A, Proveedor B">
                                <p class="text-[11px] text-zinc-500 mt-1">Separados por coma.</p>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-300">Fecha estimada</label>
                                <input type="date" name="fecha_estimada" value="{{ old('fecha_estimada') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Adjuntar documentacion (cotizaciones, contratos)</label>
                            <input type="file" name="adjuntos[]" multiple class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30">
                            <p class="mt-1 text-xs text-zinc-500">Maximo 8MB por archivo.</p>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/20 px-4 py-2.5 text-sm font-semibold text-emerald-50 hover:border-emerald-400/60">
                                Enviar solicitud
                            </button>
                        </div>
                    </form>
                </section>
            </div>

        @endif
    </main>
</body>
</html>
