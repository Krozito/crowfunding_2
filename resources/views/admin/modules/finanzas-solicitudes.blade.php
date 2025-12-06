<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Solicitudes de fondos | CrowdUp Admin</title>
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
                <a href="{{ route('admin.finanzas') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a finanzas
                </a>
                <h1 class="text-lg font-semibold text-white">Solicitudes globales</h1>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-300">Flujos</span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'finanzas'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
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

                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Solicitudes</p>
                            <h2 class="text-2xl font-bold text-white">Perspectiva global</h2>
                            <p class="text-sm text-zinc-400">Admin observa estado, montos y puede tomar acciones manuales de respaldo.</p>
                        </div>
                        @php
                            $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
                        @endphp
                        <div class="flex flex-wrap gap-2 text-xs text-zinc-300">
                            <a href="{{ route('admin.finanzas.proyectos') }}" class="{{ $btnSolid }}">Fondos por proyecto</a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.finanzas.solicitudes') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,auto] sm:items-end">
                        <div>
                            <label class="text-xs text-zinc-400">Proyecto</label>
                            <input type="text" name="q" value="{{ $q }}" placeholder="Titulo de proyecto"
                                   class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label class="text-xs text-zinc-400">Estado</label>
                            <select name="estado" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                                <option value="">Todos</option>
                                <option value="pendiente" @selected($estado === 'pendiente')>Pendiente</option>
                                <option value="aprobado" @selected($estado === 'aprobado')>Aprobado</option>
                                <option value="liberado" @selected($estado === 'liberado')>Liberado</option>
                                <option value="pagado" @selected($estado === 'pagado')>Pagado</option>
                                <option value="rechazado" @selected($estado === 'rechazado')>Rechazado</option>
                                <option value="pausado" @selected($estado === 'pausado')>Pausado</option>
                                <option value="gastado" @selected($estado === 'gastado')>Gastado</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="{{ $btnSolid }}">
                                Filtrar
                            </button>
                            <a href="{{ route('admin.finanzas.solicitudes') }}" class="admin-btn admin-btn-ghost">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    <div class="divide-y divide-white/5">
                        @forelse ($solicitudes as $solicitud)
                            @php
                                $estadoStyles = [
                                    'pendiente' => 'bg-amber-500/15 text-amber-100 border border-amber-400/30',
                                    'aprobado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                    'liberado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                    'pagado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                    'rechazado' => 'bg-red-500/15 text-red-100 border border-red-400/30',
                                    'pausado' => 'bg-red-500/15 text-red-100 border border-red-400/30',
                                    'gastado' => 'bg-sky-500/15 text-sky-100 border border-sky-400/30',
                                ];
                                $badge = $estadoStyles[$solicitud->estado] ?? 'bg-white/10 text-white border border-white/20';
                            @endphp
                            <article class="py-5">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $solicitud->proyecto->titulo ?? 'Proyecto' }}</p>
                                        <p class="text-xs text-zinc-400">Creador: {{ $solicitud->proyecto->creador->nombre_completo ?? $solicitud->proyecto->creador->name ?? 'N/D' }}</p>
                                        <p class="text-xs text-zinc-400">Hito: {{ $solicitud->hito ?? 'N/D' }}</p>
                                        <p class="text-sm text-zinc-200">Monto: USD {{ number_format($solicitud->monto_solicitado, 2) }}</p>
                                        <p class="text-[11px] text-zinc-500">Estado admin: {{ $solicitud->estado_admin ?? 'N/D' }}</p>
                                        @if($solicitud->justificacion_admin)
                                            <p class="text-xs text-zinc-300">Justificacion: {{ $solicitud->justificacion_admin }}</p>
                                        @endif
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge }}">{{ ucfirst($solicitud->estado) }}</span>
                                </div>

                                <div class="mt-3 grid gap-2 text-xs text-zinc-300 sm:grid-cols-[1fr,auto] sm:items-center">
                                    <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                                        <p class="text-[11px] text-zinc-500">Adjuntos</p>
                                        @if (!empty($solicitud->adjuntos))
                                            <div class="mt-1 flex flex-wrap gap-2">
                                                @foreach ($solicitud->adjuntos as $idx => $archivo)
                                                    <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1 hover:border-indigo-400/60">
                                                        Archivo {{ is_string($idx) ? ucfirst(str_replace('_',' ', $idx)) : $idx + 1 }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="mt-1 text-white">Sin adjuntos</p>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('admin.finanzas.solicitudes.update', $solicitud) }}" class="flex flex-wrap gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="justificacion_admin" value="">
                                        <button name="accion" value="liberar" class="{{ $btnSolid }} text-xs">Liberar manual</button>
                                        <button name="accion" value="pausar" class="admin-btn admin-btn-ghost text-xs">Pausar</button>
                                        <button name="accion" value="reintentar" class="admin-btn admin-btn-ghost text-xs">Reintentar</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <p class="py-8 text-sm text-zinc-400 text-center">No hay solicitudes con este filtro.</p>
                        @endforelse
                    </div>

                    <div class="border-t border-white/5 px-6 py-4 text-right text-xs text-zinc-400">
                        {{ $solicitudes->links() }}
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>








