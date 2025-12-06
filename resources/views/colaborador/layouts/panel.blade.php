<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Panel Colaborador') | CrowdUp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden bg-zinc-950">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/30 blur-2xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-emerald-500/25 blur-2xl"></div>
    </div>

    @php
        $backUrl = trim($__env->yieldContent('back_url', route('colaborador.dashboard')));
        $backLabel = trim($__env->yieldContent('back_label', 'Volver al panel'));
    @endphp
    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="/images/brand/mark.png" alt="CrowdUp" class="h-8 w-8" />
                    <span class="text-xl font-extrabold tracking-tight">Crowd<span class="text-indigo-400">Up</span> Colaborador</span>
                </a>
                @if ($backUrl)
                    <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                        <span aria-hidden="true">&larr;</span> {{ $backLabel }}
                    </a>
                @endif
                <h1 class="text-lg font-semibold text-white">@yield('title', 'Panel colaborador')</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">COLABORADOR</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-xs font-semibold text-white hover:border-indigo-400/60 hover:bg-white/10">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6 space-y-8">
        <div class="grid gap-0 lg:grid-cols-[260px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('colaborador.partials.modules', ['active' => trim($__env->yieldContent('active')) ?: 'dashboard'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                @yield('content')
            </div>
        </div>
    </main>
</body>
</html>
