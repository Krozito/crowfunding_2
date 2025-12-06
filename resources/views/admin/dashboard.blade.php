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
                <section id="overview" class="relative overflow-hidden rounded-[22px] admin-hero px-8 py-10 shadow-2xl ring-1 ring-white/15">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.2),_transparent_45%)] blur-[2px]"></div>
                    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Panel estrategico</p>
                            <h1 class="mt-2 text-3xl font-extrabold tracking-wide text-white">Administracion centralizada</h1>
                            <p class="mt-3 max-w-2xl text-base text-white/75">
                                Accede a modulos especializados: roles, proyectos, auditorias, finanzas y proveedores. Disenado para control granular y escalabilidad.
                            </p>
                        </div>
                        <div class="grid gap-3 rounded-2xl bg-white/10 p-6 text-white backdrop-blur-sm shadow-inner">
                            <div class="flex items-center gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.35em] text-white/70">Usuarios totales</p>
                                    <p class="text-4xl font-extrabold">{{ $totalUsers }}</p>
                                </div>
                            </div>
                            <div class="border-t border-white/10 pt-3 flex items-center gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.35em] text-white/70">Identidad verificada</p>
                                    <p class="text-4xl font-extrabold">{{ $verifiedUsers }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="roles" class="grid gap-4 lg:grid-cols-4">
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
                        <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6 shadow-xl transition hover:scale-[1.01] hover:border-white/20 {{ 'border-t-4 ' . $meta['border'] }}">
                            <p class="flex items-center gap-2 text-sm font-semibold uppercase tracking-[0.35em] text-zinc-300">{{ $roleStat->nombre_rol }}</p>
                            <p class="mt-3 text-5xl font-extrabold text-white leading-tight">{{ $roleStat->users_count }}</p>
                            <p class="mt-1 text-sm text-zinc-400">Usuarios activos con este rol</p>
                        </article>
                    @endforeach
                </section>

            </div>
        </div>
    </main>
</body>
</html>



