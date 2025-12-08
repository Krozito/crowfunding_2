<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyectos | CrowdUp Admin</title>
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
                <h1 class="text-lg font-semibold text-white">Supervision de proyectos</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6 relative">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'proyectos'])
            </aside>

            @php
                $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
                $badgeEstado = 'rounded-full px-3 py-1 text-[11px] font-semibold border';
                $estadoColors = [
                    'publicado' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40',
                    'en_revision' => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                    'pendiente' => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                    'borrador' => 'bg-zinc-500/15 text-zinc-200 border-zinc-400/40',
                    'rechazado' => 'bg-red-500/15 text-red-200 border-red-400/40',
                    'pausado' => 'bg-sky-500/15 text-sky-200 border-sky-400/40',
                ];
            @endphp
            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 shadow-2xl ring-1 ring-indigo-500/10 admin-accent-card">
                    <div class="border-b border-white/5 px-6 py-6 space-y-4">
                        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Monitor</p>
                                <h2 class="mt-1 text-2xl font-bold text-white">Supervision de proyectos</h2>
                                <p class="mt-2 text-sm text-zinc-400">
                                    Publica, valida y revisa proyectos activos. Selecciona un proyecto para ver sus detalles.
                                </p>
                                <p class="mt-2 text-xs text-zinc-500">
                                    Mostrando {{ $proyectos->count() }} de {{ $proyectos->total() }} proyectos • En revisión: {{ $estadoResumen['en_revision'] ?? 0 }}, Publicados: {{ $estadoResumen['publicado'] ?? 0 }}, Pausados: {{ $estadoResumen['pausado'] ?? 0 }}
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                                <a href="{{ route('admin.proyectos.config') }}" class="admin-btn admin-btn-ghost">
                                    Gestionar catalogos
                                </a>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('admin.proyectos') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,1fr,1fr,auto] sm:items-end">
                            <div>
                                <label class="text-xs text-zinc-400">Busqueda</label>
                                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Titulo, categoria o ubicacion"
                                       class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                            <div>
                                <label class="text-xs text-zinc-400">Estado</label>
                                <select name="estado" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                                    <option value="">Todos</option>
                                    @foreach (['borrador','en_revision','pendiente','publicado','pausado','rechazado'] as $opt)
                                        <option value="{{ $opt }}" @selected(($estado ?? '') === $opt)>{{ ucfirst(str_replace('_',' ', $opt)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-zinc-400">Categoria</label>
                                <select name="categoria" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                                    <option value="">Todas</option>
                                    @foreach ($categorias as $cat)
                                        <option value="{{ $cat }}" @selected(($categoria ?? '') === $cat)>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-zinc-400">Modelo de financiamiento</label>
                                <select name="modelo" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                                    <option value="">Todos</option>
                                    @foreach ($modelos as $modeloItem)
                                        <option value="{{ $modeloItem }}" @selected(($modelo ?? '') === $modeloItem)>{{ $modeloItem }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="{{ $btnSolid }}">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.proyectos') }}" class="admin-btn admin-btn-ghost">
                                    Limpiar
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="px-6 pb-6">
                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            @forelse ($proyectos as $proyecto)
                                @php
                                    $estadoKey = strtolower($proyecto->estado ?? 'pendiente');
                                    $badge = $badgeEstado . ' ' . ($estadoColors[$estadoKey] ?? 'bg-zinc-500/15 text-zinc-200 border-zinc-400/40');
                                    $recaudado = max($recaudadoPorProyecto[$proyecto->id] ?? 0, $proyecto->monto_recaudado ?? 0);
                                    $avance = $proyecto->meta_financiacion > 0 ? round(($recaudado / $proyecto->meta_financiacion) * 100) : 0;
                                    $reportes = $reportesPorProyecto[$proyecto->id] ?? 0;
                                @endphp
                                <article class="flex h-full flex-col rounded-2xl border border-white/10 bg-white/5 p-5 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="space-y-1">
                                            <p class="text-lg font-semibold text-white">{{ $proyecto->titulo }}</p>
                                            <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-400">
                                                <span>Creador: {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'N/D' }}</span>
                                                @if(optional($proyecto->creador)->estado_verificacion)
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-200"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> KYC verificado</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-2.5 py-1 text-[11px] font-semibold text-amber-200"><span class="h-2 w-2 rounded-full bg-amber-300"></span> KYC pendiente</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="{{ $badge }}">{{ strtoupper(str_replace('_',' ', $proyecto->estado ?? 'pendiente')) }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3 text-sm text-zinc-200 flex-1">
                                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                            <p class="text-[11px] text-zinc-500">Categoria</p>
                                            <p class="font-semibold">{{ $proyecto->categoria ?? 'N/D' }}</p>
                                            <p class="text-[11px] text-zinc-500">Ubicacion: {{ $proyecto->ubicacion_geografica ?? 'N/D' }}</p>
                                        </div>
                                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                            <p class="text-[11px] text-zinc-500">Modelo</p>
                                            <p class="font-semibold">{{ $proyecto->modelo_financiamiento ?? 'N/D' }}</p>
                                            <p class="text-[11px] text-zinc-500">Creado: {{ $proyecto->created_at?->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                            <p class="text-[11px] text-zinc-500">Meta</p>
                                            <p class="font-semibold">US$ {{ number_format($proyecto->meta_financiacion, 2) }}</p>
                                            <p class="text-[11px] text-zinc-500">Recaudado: US$ {{ number_format($recaudado, 2) }}</p>
                                        </div>
                                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full">
                                            <p class="text-[11px] text-zinc-500">Avance</p>
                                            <p class="font-semibold">{{ $avance }}%</p>
                                            <p class="text-[11px] text-zinc-500">Fecha límite: {{ optional($proyecto->fecha_limite)->format('d/m/Y') ?? 'Sin fecha' }}</p>
                                        </div>
                                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 h-full col-span-2">
                                            <div class="flex items-center justify-between text-[12px] text-zinc-400">
                                                <span>Reportes sospechosos</span>
                                                <span class="font-semibold text-amber-200">{{ $reportes }}</span>
                                            </div>
                                            <div class="mt-2 h-2 w-full rounded-full bg-white/10">
                                                <div class="h-full rounded-full bg-indigo-400" style="width: {{ min($avance,100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2 text-xs font-semibold justify-end">
                                        <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="{{ $btnSolid }}">
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
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>





