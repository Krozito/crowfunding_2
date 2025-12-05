<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Creador | CrowdUp</title>
    <meta name="description" content="Gestiona proyectos, recompensas y actualizaciones de tu campana en CrowdUp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-32 top-0 h-80 w-80 rounded-full bg-emerald-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-lime-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8" />
                <span class="text-xl font-extrabold tracking-tight">Crowd<span class="text-emerald-400">Up</span> Creator</span>
            </a>
            <nav class="hidden gap-8 text-sm text-zinc-300 md:flex">
                <a href="#overview" class="hover:text-white">Overview</a>
                <a href="#modules" class="hover:text-white">Modulos</a>
                <a href="#playbook" class="hover:text-white">Playbook</a>
            </nav>
            <div class="flex items-center gap-3">
                <div class="text-right text-xs leading-tight">
                    <p class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>
                    <p class="text-zinc-400 uppercase tracking-wide">CREADOR</p>
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

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[260px_1fr]">
            <aside class="space-y-4 lg:sticky lg:top-24">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 shadow-xl">
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Panel</p>
                        <p class="text-sm text-zinc-200">Navega tus secciones</p>
                    </div>
                    <nav class="divide-y divide-white/5">
                        <a href="#overview" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span>Vision general</span>
                            <span class="text-xs text-zinc-500 group-hover:text-emerald-200">Resumen</span>
                        </a>
                        <a href="#modules" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span>Modulos clave</span>
                            <span class="text-xs text-zinc-500 group-hover:text-emerald-200">Accesos</span>
                        </a>
                        <a href="#perfil" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span>Perfil &amp; verificacion</span>
                            <span class="text-xs text-zinc-500 group-hover:text-emerald-200">Confianza</span>
                        </a>
                        <a href="#playbook" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span>Playbook</span>
                            <span class="text-xs text-zinc-500 group-hover:text-emerald-200">Pasos</span>
                        </a>
                    </nav>
                </div>

                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 shadow-xl">
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Modulos</p>
                        <p class="text-sm text-zinc-200">Accesos directos</p>
                    </div>
                    <nav class="divide-y divide-white/5">
                        <a href="{{ route('creador.proyectos') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">P</span>
                                Proyectos
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                        <a href="{{ route('creador.recompensas') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">R</span>
                                Recompensas
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                        <a href="{{ route('creador.avances') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">A</span>
                                Avances
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                        <a href="{{ route('creador.fondos') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">F</span>
                                Fondos
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                        <a href="{{ route('creador.proveedores') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">V</span>
                                Proveedores
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                        <a href="{{ route('creador.reportes') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">R</span>
                                Reportes
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                        <a href="{{ route('creador.perfil') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-500/20 text-emerald-200">K</span>
                                Perfil &amp; verificacion
                            </span>
                            <span class="text-xs text-emerald-200">Ir</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="space-y-10">
                <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600 to-lime-600 px-8 py-10 shadow-2xl">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.25),_transparent_45%)]"></div>
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Panel de creador</p>
                            <h1 class="mt-3 text-4xl font-black text-white">Lanza, gestiona y demuestra transparencia</h1>
                            <p class="mt-4 max-w-2xl text-lg text-white/80">
                                Administra tus campanas, define recompensas, comunica avances y controla desembolsos. Todo en un solo lugar.
                            </p>
                        </div>
                        <div class="grid gap-3 text-white text-sm">
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-300"></span>
                                Proyectos activos: <span class="font-semibold">{{ $metrics['proyectos'] }}</span>
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur">
                                <span class="h-2.5 w-2.5 rounded-full bg-lime-300"></span>
                                Monto recaudado: <span class="font-semibold">${{ number_format($metrics['montoRecaudado'], 0, ',', '.') }}</span>
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-200"></span>
                                Colaboradores: <span class="font-semibold">{{ $metrics['colaboradores'] }}</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="modules">
                    <div class="flex flex-col gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Estado financiero</p>
                        <h2 class="text-2xl font-bold text-white">Financiamiento y salud del proyecto</h2>
                        <p class="text-sm text-zinc-400">Resumen de recaudacion, gastos y proyecciones.</p>
                    </div>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs uppercase text-zinc-400">Recaudado</p>
                            <p class="text-2xl font-bold text-emerald-200">${{ number_format($metrics['montoRecaudado'], 0, ',', '.') }}</p>
                            <p class="text-xs text-zinc-500">vs meta total: {{ $metrics['avance'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs uppercase text-zinc-400">Gastos reportados</p>
                            <p class="text-2xl font-bold text-lime-200">${{ number_format($metrics['gastos'] ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-zinc-500">Incluye proveedores y comprobantes</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-xs uppercase text-zinc-400">Proyecciones</p>
                            <p class="text-2xl font-bold text-white">En curso</p>
                            <p class="text-xs text-zinc-500">Flujo esperado y hitos proximos</p>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-6 lg:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Gastos y desembolsos</p>
                                    <h3 class="text-lg font-semibold text-white">Ultimos movimientos</h3>
                                </div>
                                <a href="{{ route('creador.fondos') }}" class="text-xs text-emerald-200 hover:text-white">Ver fondos →</a>
                            </div>
                            <div class="mt-4 space-y-3 text-sm text-zinc-300">
                                <p>No hay gastos registrados aun. Agrega desembolsos y comprobantes en el modulo de fondos.</p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-zinc-900/70 p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proyecciones</p>
                                    <h3 class="text-lg font-semibold text-white">Hitos proximos</h3>
                                </div>
                                <a href="{{ route('creador.avances') }}" class="text-xs text-emerald-200 hover:text-white">Ver avances →</a>
                            </div>
                            <div class="mt-4 space-y-3 text-sm text-zinc-300">
                                <p>Define tu cronograma y presupuesto en tus proyectos para ver las proyecciones aqui.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="perfil" class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Confianza</p>
                            <h3 class="text-lg font-semibold text-white">Completa tu perfil y verificacion</h3>
                            <p class="text-sm text-zinc-400">Sube documentos, redes y certificaciones para elevar tu indice de confianza.</p>
                        </div>
                        <a href="{{ route('creador.perfil') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                            Abrir perfil <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </section>

                <section id="playbook">
                    <div class="flex flex-col gap-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Playbook</p>
                        <h3 class="text-2xl font-bold text-white">Pasos sugeridos</h3>
                        <p class="text-sm text-zinc-400">Guia rapida para mantener tu campaña al dia.</p>
                    </div>
                    <div class="mt-5 space-y-3">
                        <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-zinc-900/70 p-4">
                            <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-200 font-semibold">1</span>
                            <div>
                                <p class="text-sm font-semibold text-white">Completa identidad y perfil</p>
                                <p class="text-xs text-zinc-400">Sube redes, credenciales y verifica tu cuenta (HU1, HU3).</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-zinc-900/70 p-4">
                            <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-lime-500/20 text-lime-200 font-semibold">2</span>
                            <div>
                                <p class="text-sm font-semibold text-white">Define campaña y recompensas</p>
                                <p class="text-xs text-zinc-400">Objetivos, cronograma y niveles de aporte (HU4-HU6).</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-white/10 bg-zinc-900/70 p-4">
                            <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-200 font-semibold">3</span>
                            <div>
                                <p class="text-sm font-semibold text-white">Transparencia financiera</p>
                                <p class="text-xs text-zinc-400">Publica avances, evidencias y controla desembolsos (HU12-HU15).</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
