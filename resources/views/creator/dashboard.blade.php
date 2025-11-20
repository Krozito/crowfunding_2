<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Creador | CrowdUp</title>
    <meta name="description" content="Gestiona proyectos, recompensas y actualizaciones de tu campaña en CrowdUp.">
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
                <span class="text-xl font-extrabold tracking-tight">Crowd<span class="text-indigo-400">Up</span> Creator</span>
            </a>
            <nav class="hidden gap-8 text-sm text-zinc-300 md:flex">
                <a href="#overview" class="hover:text-white">Overview</a>
                <a href="#modules" class="hover:text-white">Módulos</a>
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
        <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.25),_transparent_45%)]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Panel de creador</p>
                    <h1 class="mt-3 text-4xl font-black text-white">Lanza, gestiona y demuestra transparencia</h1>
                    <p class="mt-4 max-w-2xl text-lg text-white/80">
                        Administra tus campañas, define recompensas, comunica avances y controla desembolsos. Todo en un solo lugar.
                    </p>
                </div>
                <div class="grid gap-4 rounded-2xl bg-white/10 p-6 text-white backdrop-blur">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Proyectos activos</p>
                        <p class="text-3xl font-bold">{{ $metrics['proyectos'] }}</p>
                    </div>
                    <div class="border-t border-white/10 pt-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Monto recaudado</p>
                        <p class="text-3xl font-bold">${{ number_format($metrics['montoRecaudado'], 0, ',', '.') }}</p>
                    </div>
                    <div class="border-t border-white/10 pt-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Colaboradores</p>
                        <p class="text-3xl font-bold">{{ $metrics['colaboradores'] }}</p>
                    </div>
                    <div class="border-t border-white/10 pt-4">
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Avance</p>
                        <p class="text-3xl font-bold">{{ $metrics['avance'] }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="modules" class="mt-12">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Módulos clave</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Gestiona cada parte de tu campaña</h2>
                    <p class="mt-2 text-sm text-zinc-400">Accesos a proyectos, recompensas, avances y fondos.</p>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('creador.proyectos') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Proyectos</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Launch</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Configura la campaña, metas, cronograma y presupuesto.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>
                <a href="{{ route('creador.recompensas') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Recompensas</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Engage</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Define niveles, beneficios y entregas para colaboradores.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>
                <a href="{{ route('creador.avances') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Avances</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Trust</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Publica hitos, evidencias y comunicación transparente.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>
                <a href="{{ route('creador.fondos') }}" class="group rounded-2xl border border-white/10 bg-zinc-900/70 p-6 shadow-lg hover:border-indigo-400/60">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Fondos</h3>
                        <span class="rounded-full bg-indigo-600/20 px-3 py-1 text-xs font-semibold text-indigo-200">Escrow</span>
                    </div>
                    <p class="mt-3 text-sm text-zinc-400">Solicitud de desembolsos, pagos a proveedores y trazabilidad.</p>
                    <div class="mt-4 flex items-center gap-2 text-indigo-300 text-sm font-semibold">
                        Ir al módulo
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </a>
            </div>
        </section>

        <section id="playbook" class="mt-12">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Playbook</p>
                    <h3 class="mt-2 text-2xl font-bold text-white">Próximos pasos para creadores</h3>
                </div>
            </div>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-300">Preparación</p>
                    <h4 class="mt-2 text-lg font-semibold text-white">Identidad y perfil</h4>
                    <p class="mt-3 text-sm text-zinc-400">
                        Completa tu perfil, redes y credenciales para generar confianza (HU1, HU3).
                    </p>
                </article>
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-fuchsia-300">Lanzamiento</p>
                    <h4 class="mt-2 text-lg font-semibold text-white">Campaña y recompensas</h4>
                    <p class="mt-3 text-sm text-zinc-400">
                        Detalla objetivos, cronograma y niveles de recompensa (HU4-HU6).
                    </p>
                </article>
                    <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-300">Ejecución</p>
                    <h4 class="mt-2 text-lg font-semibold text-white">Transparencia financiera</h4>
                    <p class="mt-3 text-sm text-zinc-400">
                        Documenta hitos, gastos y desembolsos con evidencia (HU12-HU15).
                    </p>
                </article>
            </div>
        </section>
    </main>
</body>
</html>
