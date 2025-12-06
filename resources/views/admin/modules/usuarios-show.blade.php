<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil de usuario | CrowdUp Admin</title>
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
                <h1 class="text-lg font-semibold text-white">Perfil de usuario</h1>
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
                @include('admin.partials.modules', ['active' => 'roles'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600/20 via-zinc-900/80 to-zinc-900/70 p-8 shadow-2xl">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-200">Usuario</p>
                    <h2 class="text-3xl font-bold text-white leading-tight">{{ $user->nombre_completo ?? $user->name }}</h2>
                    <p class="text-sm text-zinc-300">{{ $user->email }}</p>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($user->estado_verificacion)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                                <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Verificado
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-semibold text-yellow-300">
                                <span class="h-2 w-2 rounded-full bg-yellow-300"></span> Revision pendiente
                            </span>
                        @endif
                        @foreach($user->roles as $role)
                            <span class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-white">{{ $role->nombre_rol }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Total aportado</p>
                        <p class="text-lg font-semibold text-white">US$ {{ number_format($stats['total_aportado'], 2) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Aportes</p>
                        <p class="text-lg font-semibold text-white">{{ $stats['aportaciones'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Proyectos apoyados</p>
                        <p class="text-lg font-semibold text-white">{{ $stats['proyectos_apoyados'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <p class="text-xs text-zinc-300 uppercase tracking-wide">Calificacion</p>
                        <p class="text-lg font-semibold text-white">{{ $calificacion ? number_format($calificacion, 1) . '/5' : 'N/D' }}</p>
                    </div>
                </div>
            </div>
            @if($user->info_personal)
                <p class="mt-4 text-sm text-zinc-200 leading-relaxed">{{ $user->info_personal }}</p>
            @endif
            @if($user->redes_sociales)
                <div class="mt-4 flex flex-wrap gap-2 text-xs text-indigo-200">
                    @foreach($user->redes_sociales as $k => $v)
                        <span class="inline-flex items-center gap-1 rounded-full border border-white/10 px-3 py-1">
                            <span class="font-semibold">{{ ucfirst($k) }}:</span> {{ $v }}
                        </span>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Aportaciones</p>
                        <h3 class="text-lg font-semibold text-white">Ultimos movimientos</h3>
                    </div>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($aportaciones as $aporte)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm text-white">{{ $aporte->proyecto->titulo ?? 'Proyecto eliminado' }}</p>
                                <p class="text-xs text-zinc-400">Fecha {{ $aporte->fecha_aportacion?->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-emerald-300">+ US$ {{ number_format($aporte->monto, 2) }}</p>
                                <p class="text-xs text-zinc-400">{{ strtoupper($aporte->estado_pago) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-zinc-400">Sin aportaciones registradas.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl space-y-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Top proyectos</p>
                    <h3 class="text-lg font-semibold text-white">Aportaciones destacadas</h3>
                </div>
                <div class="space-y-3">
                    @forelse($topProyectos as $tp)
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <p class="text-sm font-semibold text-white">{{ $tp['proyecto']->titulo ?? 'Proyecto eliminado' }}</p>
                            <p class="text-xs text-zinc-400">{{ $tp['aportes'] }} aportes</p>
                            <p class="text-sm font-semibold text-emerald-300 mt-1">US$ {{ number_format($tp['total'], 2) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-400">Sin datos de aportes.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos creados</p>
                    <h3 class="text-lg font-semibold text-white">Listado</h3>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                @forelse($user->proyectosCreados as $proyecto)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-white">{{ $proyecto->titulo }}</p>
                            <span class="rounded-full bg-indigo-500/15 px-2.5 py-1 text-xs font-semibold text-indigo-200">
                                {{ strtoupper($proyecto->estado ?? 'pendiente') }}
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-zinc-400">Creado {{ $proyecto->created_at?->format('d/m/Y') }}</p>
                        <p class="mt-1 text-sm text-zinc-300 line-clamp-2">{{ $proyecto->descripcion_proyecto }}</p>
                    </div>
                @empty
                    <p class="text-sm text-zinc-400">No tiene proyectos creados.</p>
                @endforelse
            </div>
        </section>
            </div>
        </div>
    </main>
</body>
</html>








