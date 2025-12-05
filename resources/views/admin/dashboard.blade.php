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
            <nav class="hidden gap-8 text-sm text-zinc-300 md:flex">
                <a href="#overview" class="hover:text-white">Overview</a>
                <a href="#roles" class="hover:text-white">Roles</a>
                <a href="#roadmap" class="hover:text-white">Roadmap</a>
            </nav>
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

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
            <aside class="space-y-6 lg:sticky lg:top-24">
                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 shadow-xl">
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Panel</p>
                        <p class="text-sm text-zinc-200">Mapa rapido de secciones</p>
                    </div>
                    <nav class="divide-y divide-white/5">
                        <a href="#overview" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">O</span>
                                Vision general
                            </span>
                            <span class="text-xs text-zinc-500 group-hover:text-indigo-200">Resumen</span>
                        </a>
                        <a href="#roles" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">R</span>
                                Distribucion de roles
                            </span>
                            <span class="text-xs text-zinc-500 group-hover:text-indigo-200">Usuarios</span>
                        </a>
                        <a href="#roadmap" class="group flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">H</span>
                                Hoja de ruta
                            </span>
                            <span class="text-xs text-zinc-500 group-hover:text-indigo-200">Proximos</span>
                        </a>
                    </nav>
                </div>

                <div class="rounded-2xl border border-white/10 bg-zinc-900/70 shadow-xl">
                    <div class="px-4 py-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Modulos</p>
                        <p class="text-sm text-zinc-200">Accesos laterales</p>
                    </div>
                    <nav class="divide-y divide-white/5">
                        <a href="{{ route('admin.roles') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">R</span>
                                Roles y usuarios
                            </span>
                            <span class="text-xs text-indigo-200">Ir</span>
                        </a>
                        <a href="{{ route('admin.proyectos') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">P</span>
                                Proyectos
                            </span>
                            <span class="text-xs text-indigo-200">Ir</span>
                        </a>
                        <a href="{{ route('admin.auditorias') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">A</span>
                                Auditorias y cumplimiento
                            </span>
                            <span class="text-xs text-indigo-200">Ir</span>
                        </a>
                        <a href="{{ route('admin.finanzas') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">F</span>
                                Finanzas
                            </span>
                            <span class="text-xs text-indigo-200">Ir</span>
                        </a>
                        <a href="{{ route('admin.proveedores') }}" class="flex items-center justify-between px-4 py-3 text-sm text-zinc-200 hover:bg-white/5 hover:text-white">
                            <span class="flex items-center gap-2">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-200">V</span>
                                Proveedores
                            </span>
                            <span class="text-xs text-indigo-200">Ir</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="space-y-10">
                <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 shadow-2xl">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.25),_transparent_45%)]"></div>
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Panel estrategico</p>
                            <h1 class="mt-3 text-4xl font-black text-white">Administracion centralizada</h1>
                            <p class="mt-4 max-w-2xl text-lg text-white/80">
                                Accede a modulos especializados: roles, proyectos, auditorias, finanzas, proveedores y reportes. Disenado para control granular y escalabilidad.
                            </p>
                        </div>
                        <div class="grid gap-4 rounded-2xl bg-white/10 p-6 text-white backdrop-blur">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-white/70">Usuarios totales</p>
                                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                            </div>
                            <div class="border-t border-white/10 pt-4">
                                <p class="text-xs uppercase tracking-[0.3em] text-white/70">Identidad verificada</p>
                                <p class="text-3xl font-bold">{{ $verifiedUsers }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="roles" class="grid gap-6 lg:grid-cols-3">
                    @foreach ($roleStats as $roleStat)
                        <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-zinc-400">{{ $roleStat->nombre_rol }}</p>
                            <p class="mt-3 text-4xl font-black text-white">{{ $roleStat->users_count }}</p>
                            <p class="mt-2 text-sm text-zinc-400">Usuarios activos con este rol</p>
                        </article>
                    @endforeach
                </section>

                <section id="roadmap">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Hoja de ruta</p>
                            <h3 class="mt-2 text-2xl font-bold text-white">Proximas capacidades para administracion</h3>
                        </div>
                    </div>
                    <div class="mt-6 grid gap-6 md:grid-cols-3">
                        <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-indigo-300">Prioridad 1</p>
                            <h4 class="mt-2 text-lg font-semibold text-white">Moderacion de usuarios</h4>
                            <p class="mt-3 text-sm text-zinc-400">
                                KYC mejorado, seguimiento de verificaciones (HU1, HU3) y control granular de permisos (HU2).
                            </p>
                        </article>
                        <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-fuchsia-300">Prioridad 2</p>
                            <h4 class="mt-2 text-lg font-semibold text-white">Supervision financiera</h4>
                            <p class="mt-3 text-sm text-zinc-400">
                                Metricas de proyectos, auditorias y liberacion de fondos (epicas 4, 5 y 8).
                            </p>
                        </article>
                        <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-300">Prioridad 3</p>
                            <h4 class="mt-2 text-lg font-semibold text-white">Cumplimiento &amp; reportes</h4>
                            <p class="mt-3 text-sm text-zinc-400">
                                Herramientas de AML/KYC, reportes fiscales y monitoreo de proveedores (epicas 9 y 10).
                            </p>
                        </article>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
