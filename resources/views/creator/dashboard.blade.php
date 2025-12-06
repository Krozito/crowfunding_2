@extends('creator.layouts.panel')

@section('title', 'Panel de Creador')
@section('active', 'dashboard')
@section('back_url', '')

@section('content')
    <div class="flex justify-end px-4 sm:px-6 lg:px-8 pt-6">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="inline-flex items-center rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">
            Salir
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <div class="space-y-10 px-4 sm:px-6 lg:px-8">
        <section id="overview" class="relative overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-800 to-teal-700 px-8 py-10 shadow-2xl">
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
@endsection
