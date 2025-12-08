<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Roles y usuarios | CrowdUp Admin</title>
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
                <h1 class="text-lg font-semibold text-white">Roles y usuarios</h1>
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

            @php
                $btnSolid = 'inline-flex items-center gap-2 rounded-xl bg-[#4f46e5] px-4 py-2.5 text-sm font-semibold text-white border border-[#4f46e5] hover:bg-[#4338ca]';
            @endphp
            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/75 shadow-2xl ring-1 ring-indigo-500/10 admin-accent-card">
            <div class="border-b border-white/5 px-6 py-6 space-y-4">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Gestion de accesos y roles</p>
                        <h2 class="text-2xl font-bold text-white">Usuarios, verificacion y rol exclusivo</h2>
                        <p class="text-sm text-zinc-400">Un usuario solo puede tener un rol activo. Usa filtros para encontrar pendientes y asignar con rapidez.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs text-zinc-300">
                        <a href="{{ route('admin.verificaciones') }}" class="admin-btn admin-btn-ghost">
                            Solicitudes de verificacion
                        </a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-6 py-4 text-sm text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.roles') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,1fr,auto] sm:items-end">
                    <div>
                        <label class="text-xs text-zinc-400">Busqueda</label>
                        <input type="text" name="q" value="{{ $search }}" placeholder="Buscar por nombre o email"
                               class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Rol</label>
                        <select name="role" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todos los roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected($roleFilter == $role->id)>{{ $role->nombre_rol }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Estado de verificacion</label>
                        <select name="verificacion" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                            <option value="">Todos</option>
                            <option value="verificado" @selected(($verificationFilter ?? '') === 'verificado')>Verificados</option>
                            <option value="pendiente" @selected(($verificationFilter ?? '') === 'pendiente')>Pendientes</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="{{ $btnSolid }}">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.roles') }}" class="admin-btn admin-btn-ghost">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="divide-y divide-white/5">
                @forelse ($users as $user)
                    @php
                        $rolActual = $user->roles->first();
                        $rolName = $rolActual->nombre_rol ?? 'Sin rol asignado';
                        $roleColors = [
                            'ADMIN' => 'bg-sky-500/15 text-sky-200 border-sky-400/40',
                            'AUDITOR' => 'bg-purple-500/15 text-purple-200 border-purple-400/40',
                            'CREADOR' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40',
                            'COLABORADOR' => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                        ];
                        $badgeClass = $roleColors[strtoupper($rolName)] ?? 'bg-amber-500/10 text-amber-200 border-amber-300/40';
                    @endphp
                    <div class="px-6 py-4">
                        <div class="grid gap-3 lg:grid-cols-[2fr,1.2fr] lg:items-center rounded-2xl border border-white/5 bg-white/5 px-4 py-4">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-3">
                                    <div>
                                        <a href="{{ route('admin.users.show', $user) }}" class="text-lg font-semibold text-indigo-200 hover:text-white">
                                            {{ $user->nombre_completo ?? $user->name }}
                                        </a>
                                        <p class="text-sm text-zinc-400">{{ $user->email }}</p>
                                    </div>
                                    @if($user->estado_verificacion)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">
                                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span> Verificado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 px-3 py-1 text-xs font-semibold text-amber-200">
                                            <span class="h-2 w-2 rounded-full bg-amber-300"></span> Pendiente
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        Rol actual: {{ $rolName }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-3 text-xs text-zinc-400">
                                    <span>Registrado: {{ optional($user->created_at)->format('Y-m-d') ?? '-' }}</span>
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-200 underline">Ver detalle</a>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.users.roles', $user) }}" class="flex flex-col gap-2 rounded-xl border border-white/10 bg-zinc-950/70 px-4 py-3 shadow-inner ring-1 ring-indigo-500/10">
                                @csrf
                                @method('PATCH')
                                <label class="text-xs text-zinc-400">Selecciona un rol (exclusivo)</label>
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <select name="role_id" class="w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-white/40 focus:ring-white/20">
                                        <option value="">Sin rol</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}" @selected(optional($rolActual)->id === $role->id)>{{ $role->nombre_rol }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="{{ $btnSolid }} justify-center sm:w-28 w-full">
                                        Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="px-8 py-10 text-center text-zinc-400">Aun no existen usuarios registrados.</p>
                @endforelse
            </div>

            <div class="border-t border-white/5 px-6 py-4 text-right text-xs text-zinc-400">
                {{ $users->links() }}
            </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>







