<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Roles y usuarios | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
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

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-zinc-900/85 via-zinc-900/70 to-indigo-950/40 shadow-2xl">
            <div class="border-b border-white/5 px-6 py-6 md:flex md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Gestion operativa</p>
                    <h2 class="text-2xl font-bold text-white">Asignacion de roles</h2>
                    <p class="text-sm text-zinc-400">Filtra, busca y asigna roles de manera rapida.</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-zinc-300 mt-3 md:mt-0">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600/20 text-indigo-200 font-semibold">{{ $users->total() }}</span>
                    usuarios encontrados
                </div>
            </div>

            @if (session('status'))
                <div class="mx-6 mt-6 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-6 py-4 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="px-6 pt-6">
                <form method="GET" action="{{ route('admin.roles') }}" class="grid gap-3 sm:grid-cols-[2fr,1fr,auto] sm:items-center">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Buscar por nombre o email"
                           class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">
                    <select name="role" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Todos los roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" @selected($roleFilter == $role->id)>{{ $role->nombre_rol }}</option>
                        @endforeach
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">
                            Filtrar
                        </button>
                        <a href="{{ route('admin.roles') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/5">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-6 divide-y divide-white/5">
                @forelse ($users as $user)
                    <div class="px-6 py-5">
                        <div class="grid gap-4 lg:grid-cols-[1.6fr,1fr] lg:items-start">
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
                                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-500/10 px-3 py-1 text-xs font-semibold text-yellow-300">
                                            <span class="h-2 w-2 rounded-full bg-yellow-300"></span> Revision pendiente
                                        </span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-2">
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

                            <form method="POST" action="{{ route('admin.users.roles', $user) }}" class="rounded-2xl border border-white/10 bg-zinc-950/50 p-5 shadow-inner">
                                @csrf
                                @method('PATCH')
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach ($roles as $role)
                                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-white/5 bg-white/5 px-3 py-2 text-sm text-white hover:border-indigo-400/60">
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
                    </div>
                @empty
                    <p class="px-8 py-10 text-center text-zinc-400">Aun no existen usuarios registrados.</p>
                @endforelse
            </div>

            <div class="border-t border-white/5 px-6 py-4 text-right text-xs text-zinc-400">
                {{ $users->links() }}
            </div>
        </section>
    </main>
</body>
</html>
