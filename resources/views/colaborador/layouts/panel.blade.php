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
    @php
        // Rutas con las que vamos a comparar
        $navItems = [
            [
                'route' => 'colaborador.dashboard',
                'label' => 'Inicio',
                'href'  => route('colaborador.dashboard'),
            ],
            [
                'route' => 'colaborador.proyectos',
                'label' => 'Proyectos que apoyas',
                'href'  => route('colaborador.proyectos'),
            ],
            [
                'route' => 'colaborador.aportaciones',
                'label' => 'Aportaciones',
                'href'  => route('colaborador.aportaciones'),
            ],
            [
                'route' => 'colaborador.reportes',
                'label' => 'Reportes',
                'href'  => route('colaborador.reportes'),
            ],
        ];
    @endphp

    <div class="min-h-screen flex bg-zinc-950">
        {{-- SIDEBAR --}}
        <aside class="w-72 border-r border-zinc-900 bg-black/40 backdrop-blur">
            <div class="px-6 py-5 border-b border-zinc-900 flex items-center gap-2">
                <img src="/images/brand/mark.png" alt="CrowdUp" class="h-7 w-7" />
                <div class="flex flex-col leading-tight">
                    <span class="text-sm font-semibold">CrowdUp Colaborador</span>
                </div>
            </div>

            <nav class="px-4 py-6 space-y-8 text-sm">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-500 mb-3">
                        Navegación
                    </p>

                    <div class="space-y-1">
                        @foreach($navItems as $item)
                            @php
                                $isActive = request()->routeIs($item['route']);
                            @endphp

                            <a href="{{ $item['href'] }}"
                               class="group flex items-center justify-between px-3 py-2 rounded-xl
                                      text-xs font-medium transition
                                      {{ $isActive
                                            ? 'bg-zinc-800/70 text-zinc-50'
                                            : 'text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100' }}">
                                <span>{{ $item['label'] }}</span>

                                {{-- Burbuja verde SOLO cuando está activa --}}
                                <span class="h-2.5 w-2.5 rounded-full
                                             {{ $isActive ? 'bg-emerald-400 shadow-[0_0_0_4px_rgba(16,185,129,0.25)]' : 'bg-zinc-700/0' }}">
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-500 mb-3">
                        Cuenta
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center justify-between px-3 py-2 rounded-xl
                              text-xs font-medium text-zinc-400
                              hover:bg-zinc-900 hover:text-zinc-100 transition">
                        <span>Mi perfil</span>
                    </a>
                </div>
            </nav>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="flex-1 flex flex-col">
            {{-- CABECERA SUPERIOR --}}
            <header class="border-b border-zinc-900 bg-black/40 backdrop-blur">
                <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3 text-xs text-zinc-400">
                        <a href="{{ route('dashboard') }}" class="hover:text-zinc-100 transition">
                            ← Volver al panel
                        </a>
                        <span class="text-zinc-600">/</span>
                        <span class="text-zinc-300">@yield('title', 'Panel de Colaborador')</span>
                    </div>

                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-zinc-400">
                            {{ auth()->user()->name ?? 'Usuario' }}
                            · <span class="uppercase tracking-[0.16em] text-[11px]">Colaborador</span>
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 rounded-full text-xs font-medium
                                           bg-zinc-100 text-zinc-900 hover:bg-white transition">
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- CONTENIDO DE CADA PÁGINA --}}
            <main class="flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
