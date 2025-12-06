<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Solicitudes de verificacion | CrowdUp Admin</title>
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
                <a href="{{ route('admin.roles') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a roles
                </a>
                <h1 class="text-lg font-semibold text-white">Solicitudes de verificacion</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    @php
        $btnSolid = 'inline-flex items-center justify-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
    @endphp
    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'verificaciones'])
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

                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4 admin-accent-card">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">KYC</p>
                    <h2 class="text-2xl font-bold text-white">Revision de solicitudes</h2>
                    <p class="text-sm text-zinc-400">Aprueba o rechaza solicitudes de verificacion con los adjuntos cargados.</p>
                </div>
                <form method="GET" action="{{ route('admin.verificaciones') }}" class="flex items-end gap-2 text-sm">
                    <div>
                        <label class="text-xs text-zinc-400">Estado</label>
                        <select name="estado" class="mt-1 rounded-xl border border-white/15 bg-zinc-900/80 px-3 py-2 text-white focus:border-indigo-400 focus:ring-indigo-400">
                            <option value="">Todos</option>
                            <option value="pendiente" @selected($estado === 'pendiente')>Pendiente</option>
                            <option value="aprobada" @selected($estado === 'aprobada')>Aprobada</option>
                            <option value="rechazada" @selected($estado === 'rechazada')>Rechazada</option>
                        </select>
                    </div>
                    <button type="submit" class="{{ $btnSolid }}">
                        Filtrar
                    </button>
                </form>
            </div>

            <div class="divide-y divide-white/5">
                @forelse ($solicitudes as $solicitud)
                    <article class="py-5">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $solicitud->user->nombre_completo ?? $solicitud->user->name }}</p>
                                <p class="text-xs text-zinc-400">{{ $solicitud->user->email }}</p>
                                <p class="text-xs text-zinc-400">Estado actual: <span class="font-semibold text-white">{{ ucfirst($solicitud->estado) }}</span></p>
                                @if ($solicitud->nota)
                                    <p class="text-xs text-zinc-300">Nota: {{ $solicitud->nota }}</p>
                                @endif
                                <div class="mt-2 flex flex-wrap gap-2 text-xs text-white">
                                    @php $adj = $solicitud->adjuntos ?? []; @endphp
                                    @if (!empty($adj['documento_frontal']))
                                        <a target="_blank" href="{{ route('admin.verificaciones.adjunto', [$solicitud, 'documento_frontal']) }}" class="admin-btn admin-btn-ghost text-xs">Frontal</a>
                                    @endif
                                    @if (!empty($adj['documento_reverso']))
                                        <a target="_blank" href="{{ route('admin.verificaciones.adjunto', [$solicitud, 'documento_reverso']) }}" class="admin-btn admin-btn-ghost text-xs">Reverso</a>
                                    @endif
                                    @if (!empty($adj['selfie']))
                                        <a target="_blank" href="{{ route('admin.verificaciones.adjunto', [$solicitud, 'selfie']) }}" class="admin-btn admin-btn-ghost text-xs">Selfie</a>
                                    @endif
                                    @if (empty($adj))
                                        <span class="text-zinc-400">Archivos no disponibles</span>
                                    @endif
                                </div>
                            </div>

                            @if ($solicitud->estado === 'pendiente')
                                <form method="POST" action="{{ route('admin.verificaciones.update', $solicitud) }}" class="w-full max-w-sm rounded-2xl border border-white/10 bg-zinc-950/70 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="text-xs text-zinc-400">Nota (opcional)</label>
                                        <textarea name="nota" rows="2" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">{{ old('nota') }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button name="accion" value="aprobar" class="{{ $btnSolid }} text-sm">Aprobar</button>
                                        <button name="accion" value="rechazar" class="{{ $btnSolid }} text-sm">Rechazar</button>
                                    </div>
                                </form>
                            @else
                                <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Solicitud cerrada</span>
                            @endif
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








