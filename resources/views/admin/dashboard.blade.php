<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Administracion | CrowdUp</title>
    <meta name="description" content="Administra roles, modulos clave y monitorea la operacion de CrowdUp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-32 top-0 h-80 w-80 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8" />
                <span class="text-xl font-extrabold tracking-tight">Crowd<span class="text-indigo-400">Up</span> Admin</span>
            </a>
            
            <div class="flex items-center gap-3">
                <div class="text-right text-xs leading-tight">
                    <p class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>
                    <p class="text-zinc-400 uppercase tracking-wide">ADMIN</p>
                </div>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
                    Salir
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'dashboard'])
            </aside>

            <div class="space-y-10 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section id="overview" class="relative overflow-hidden rounded-[18px] admin-hero px-6 py-7 shadow-xl ring-1 ring-white/10">
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-[0.32em] text-white/70">Panel global de la plataforma</p>
                            <h1 class="mt-1 text-3xl font-extrabold tracking-tight text-white">Control de riesgos, operacion y cumplimiento</h1>
                            <p class="mt-2 max-w-2xl text-sm text-white/80">Supervisa usuarios, proyectos, fondos y alertas en un solo lugar.</p>
                        </div>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 text-white">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-white/70">Usuarios</p>
                                <p class="mt-1 text-3xl font-extrabold">{{ $totalUsers }}</p>
                                <p class="text-xs text-white/80">Verificados: {{ $verifiedUsers }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 text-white">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-white/70">Proyectos activos</p>
                                <p class="mt-1 text-3xl font-extrabold">{{ $projects['publicados'] }}</p>
                                <p class="text-xs text-white/80">Totales: {{ $projects['total'] }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/15 bg-white/10 p-4 text-white">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-white/70">Recaudado global</p>
                                <p class="mt-1 text-3xl font-extrabold">${{ number_format($finanzas['recaudado'], 0) }}</p>
                                <p class="text-xs text-white/80">Liberado: ${{ number_format($finanzas['liberado'], 0) }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="kpis" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @php
                        $cards = [
                            ['label' => 'Total recaudado', 'value' => '$'.number_format($finanzas['recaudado'],0), 'hint' => 'Fondos recibidos', 'color' => 'from-cyan-400 to-indigo-500'],
                            ['label' => 'Fondos en escrow', 'value' => '$'.number_format($finanzas['escrow'],0), 'hint' => 'Pendientes de liberar', 'color' => 'from-amber-300 to-orange-500'],
                            ['label' => 'Fondos liberados', 'value' => '$'.number_format($finanzas['liberado'],0), 'hint' => 'Aprobados / pagados', 'color' => 'from-emerald-300 to-teal-500'],
                            ['label' => 'Fondos gastados validados', 'value' => '$'.number_format($finanzas['gastado'],0), 'hint' => 'Gastos registrados', 'color' => 'from-purple-300 to-violet-500'],
                            ['label' => 'Proyectos activos', 'value' => $projects['publicados'], 'hint' => 'Publicados', 'color' => 'from-sky-300 to-blue-500'],
                            ['label' => 'Proyectos con alerta', 'value' => $projects['riesgo'], 'hint' => 'En pausa / riesgo', 'color' => 'from-red-300 to-orange-500'],
                        ];
                    @endphp
                    @foreach ($cards as $card)
                        <article class="rounded-2xl border border-white/10 bg-gradient-to-br {{ $card['color'] }} p-[1px] shadow-lg">
                            <div class="h-full rounded-2xl bg-zinc-950/90 p-5">
                                <p class="text-[11px] uppercase tracking-[0.28em] text-white/70">{{ $card['label'] }}</p>
                                <p class="mt-2 text-3xl font-extrabold text-white leading-tight">{{ $card['value'] }}</p>
                                <p class="text-xs text-white/75">{{ $card['hint'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </section>

                <section class="grid gap-4 lg:grid-cols-3">
                    <article class="rounded-2xl border border-red-400/30 bg-red-500/10 p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] uppercase tracking-[0.28em] text-red-200">Alertas de riesgo</p>
                            <a href="{{ route('auditor.reportes') }}" class="text-xs text-red-100 underline">Ver</a>
                        </div>
                        <div class="mt-4 space-y-2 text-sm text-red-50">
                            <div class="flex justify-between"><span>Reportes sospechosos abiertos</span><span class="font-bold">{{ $riesgos['reportes'] }}</span></div>
                            <div class="flex justify-between"><span>Proyectos marcados en riesgo</span><span class="font-bold">{{ $riesgos['proyectos_riesgo'] }}</span></div>
                            <div class="flex justify-between"><span>Gastos observados / rechazados</span><span class="font-bold">{{ $riesgos['gastos_observados'] }}</span></div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-5 shadow-lg lg:col-span-2">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] uppercase tracking-[0.28em] text-zinc-300">Pendientes críticos</p>
                            <span class="text-xs text-zinc-400">Acciones rápidas</span>
                        </div>
                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl border border-white/10 bg-white/5 p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-white">Verificaciones de identidad</p>
                                    <p class="text-2xl font-bold text-white">{{ $pendientes['kyc'] }}</p>
                                </div>
                                <a class="text-xs font-semibold text-indigo-200 underline" href="{{ route('admin.verificaciones') }}">Revisar</a>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-white">Proyectos en revisión</p>
                                    <p class="text-2xl font-bold text-white">{{ $pendientes['proyectos'] }}</p>
                                </div>
                                <a class="text-xs font-semibold text-indigo-200 underline" href="{{ route('admin.proyectos') }}">Aprobar</a>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-white">Solicitudes de desembolso</p>
                                    <p class="text-2xl font-bold text-white">{{ $pendientes['desembolsos'] }}</p>
                                </div>
                                <a class="text-xs font-semibold text-indigo-200 underline" href="{{ route('admin.finanzas.solicitudes') }}">Ver</a>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 p-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-white">Reportes sospechosos</p>
                                    <p class="text-2xl font-bold text-white">{{ $pendientes['reportes'] }}</p>
                                </div>
                                <a class="text-xs font-semibold text-indigo-200 underline" href="{{ route('auditor.reportes') }}">Atender</a>
                            </div>
                        </div>
                    </article>
                </section>

                <section class="grid gap-4 lg:grid-cols-[2fr_1fr]">
                    <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] uppercase tracking-[0.28em] text-zinc-400">Actividad reciente</p>
                            <span class="text-xs text-zinc-500">Últimos eventos</span>
                        </div>
                        <div class="mt-4 space-y-3">
                            @forelse ($actividadReciente as $item)
                                <div class="flex items-center gap-3 rounded-lg border border-white/5 bg-white/5 px-4 py-3 text-sm text-white">
                                    <span class="inline-flex h-2.5 w-2.5 rounded-full bg-indigo-300"></span>
                                    <div class="flex-1">
                                        <p class="font-semibold capitalize">{{ $item['tipo'] ?? 'evento' }}</p>
                                        <p class="text-xs text-zinc-400">{{ optional($item['created_at'])->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-xs text-zinc-300">ID {{ $item['id'] ?? '-' }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-zinc-400">Sin actividad reciente.</p>
                            @endforelse
                        </div>
                    </article>
                    <article id="roles" class="rounded-2xl border border-white/10 bg-zinc-900/80 p-5 shadow-lg">
                        <p class="text-[11px] uppercase tracking-[0.28em] text-zinc-400">Distribución de roles</p>
                        <div class="mt-4 grid gap-3">
                            @php
                                $icons = [
                                    'ADMIN' => ['border' => 'border-sky-400'],
                                    'AUDITOR' => ['border' => 'border-purple-400'],
                                    'CREADOR' => ['border' => 'border-emerald-400'],
                                    'COLABORADOR' => ['border' => 'border-amber-300'],
                                ];
                            @endphp
                            @foreach ($roleStats as $roleStat)
                                @php
                                    $meta = $icons[strtoupper($roleStat->nombre_rol)] ?? ['border' => 'border-indigo-400'];
                                @endphp
                                <div class="flex items-center justify-between rounded-xl border border-white/10 bg-white/5 p-4 {{ 'border-l-4 ' . $meta['border'] }}">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $roleStat->nombre_rol }}</p>
                                        <p class="text-xs text-zinc-400">Usuarios activos</p>
                                    </div>
                                    <p class="text-2xl font-bold text-white">{{ $roleStat->users_count }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </section>

            </div>
        </div>
    </main>
</body>
</html>


