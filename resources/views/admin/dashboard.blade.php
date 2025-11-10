<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Administración | CrowdUp</title>
    <meta name="description" content="Administra roles, operaciones y métricas clave de CrowdUp.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-32 top-0 h-80 w-80 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <!-- NAV -->
    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8" />
                <span class="text-xl font-extrabold tracking-tight">Crowd<span class="text-indigo-400">Up</span> Admin</span>
            </a>

            <nav class="hidden gap-8 text-sm text-zinc-300 md:flex">
                <a href="#overview" class="hover:text-white">Overview</a>
                <a href="#roles" class="hover:text-white">Roles &amp; Usuarios</a>
                <a href="#roadmap" class="hover:text-white">Backlog</a>
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
        <!-- Hero -->
        <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 shadow-2xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.25),_transparent_45%)]"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/70">Panel estratégico</p>
                    <h1 class="mt-3 text-4xl font-black text-white">Control completo del ecosistema</h1>
                    <p class="mt-4 max-w-2xl text-lg text-white/80">
                        Gestiona roles, vigila la salud operativa y prioriza próximas capacidades. Cada acción se centra en transparencia, seguridad y experiencia impecable.
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

        <!-- KPI cards -->
        <section class="mt-10 grid gap-6 lg:grid-cols-3">
            @foreach ($roleStats as $roleStat)
                <article class="rounded-2xl border border-white/10 bg-zinc-900/80 p-6 shadow-xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-zinc-400">{{ $roleStat->nombre_rol }}</p>
                    <p class="mt-3 text-4xl font-black text-white">{{ $roleStat->users_count }}</p>
                    <p class="mt-2 text-sm text-zinc-400">Usuarios activos con este rol</p>
                </article>
            @endforeach
        </section>

        @if (session('status'))
            <div class="mt-8 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-6 py-4 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <!-- Role management -->
        <section id="roles" class="mt-10 rounded-3xl border border-white/10 bg-zinc-900/60 shadow-2xl">
            <div class="border-b border-white/5 px-8 py-6">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Gestión operativa</p>
                <h2 class="mt-2 text-2xl font-bold text-white">Roles y accesos</h2>
                <p class="mt-2 text-sm text-zinc-400">
                    Asigna o revoca privilegios en tiempo real. Cada cambio queda registrado en el historial de auditoría.
                </p>
            </div>

            <div class="divide-y divide-white/5">
                @forelse ($users as $user)
                    <div class="grid gap-6 px-6 py-6 lg:grid-cols-[1.6fr,1fr] lg:items-center">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <div>
                                    <p class="text-lg font-semibold text-white">{{ $user->nombre_completo ?? $user->name }}</p>
                                    <p class="text-sm text-zinc-400">{{ $user->email }}</p>
                                </div>
                                @if($user->estado_verificacion)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Verificado
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-semibold text-yellow-300">
                                        <span class="h-2 w-2 rounded-full bg-yellow-300"></span> Revisión pendiente
                                    </span>
                                @endif
                            </div>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @forelse ($user->roles as $role)
                                    <span class="rounded-full border border-white/10 px-3 py-1 text-xs font-semibold text-white">
                                        {{ $role->nombre_rol }}
                                    </span>
                                @empty
                                    <span class="rounded-full border border-dashed border-white/20 px-3 py-1 text-xs text-zinc-400">
                                        Sin rol asignado
                                    </span>
                                @endforelse
                            </div>
                        </div>

                        <form method="POST" action="{{ route('admin.users.roles', $user) }}"
                              class="rounded-2xl border border-white/10 bg-zinc-950/40 p-5 shadow-inner">
                            @csrf
                            @method('PATCH')
                            <div class="grid gap-3">
                                @foreach ($roles as $role)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-white/5 bg-white/5 px-3 py-2 text-sm text-white hover:border-indigo-400/60">
                                        <input type="checkbox"
                                               name="roles[]"
                                               value="{{ $role->id }}"
                                               class="h-4 w-4 rounded border-white/30 bg-transparent text-indigo-500 focus:ring-indigo-400"
                                               @checked($user->roles->contains('id', $role->id))>
                                        <span>{{ $role->nombre_rol }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <button type="submit"
                                    class="mt-4 w-full rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                                Actualizar roles
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="px-8 py-10 text-center text-zinc-400">Aún no existen usuarios registrados.</p>
                @endforelse
            </div>
        </section>

        
    </main>
</body>
</html>
