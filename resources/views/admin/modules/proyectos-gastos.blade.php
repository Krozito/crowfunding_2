<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gastos de proyecto | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-emerald-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.proyectos.show', $proyecto) }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al proyecto
                </a>
                <h1 class="text-lg font-semibold text-white">Gastos y comprobantes</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/75 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyecto</p>
                    <h2 class="text-2xl font-bold text-white">{{ $proyecto->titulo }}</h2>
                    <p class="text-sm text-zinc-400">Creador: {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'N/D' }}</p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs text-zinc-300">
                    <span class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">Pagos: {{ $totales['pagos'] }}</span>
                    <span class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">Monto: USD {{ number_format($totales['monto'], 2) }}</span>
                    <span class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">Con comprobantes: {{ $totales['conAdjuntos'] }}</span>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Pagos</p>
                            <h3 class="text-lg font-semibold text-white">Listado</h3>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Total: {{ $pagos->count() }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse ($pagos as $pago)
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-2">
                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $pago->concepto ?? 'Pago a proveedor' }}</p>
                                        <p class="text-xs text-zinc-400">{{ $pago->fecha_pago?->format('d/m/Y H:i') }} - Proveedor: {{ $pago->proveedor->nombre_proveedor ?? 'N/D' }}</p>
                                        <p class="text-sm text-zinc-200">Monto: USD {{ number_format($pago->monto, 2) }} - Hito: {{ $pago->solicitud->hito ?? 'N/D' }}</p>
                                    </div>
                                    <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">{{ ucfirst($pago->solicitud->estado ?? 'pendiente') }}</span>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                                    <p class="text-[11px] text-zinc-500">Adjuntos</p>
                                    @if (!empty($pago->adjuntos))
                                        <div class="mt-1 flex flex-wrap gap-2">
                                            @foreach ($pago->adjuntos as $idx => $archivo)
                                                <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1 hover:border-indigo-400/60">
                                                    Archivo {{ $idx + 1 }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="mt-1 text-white">Sin adjuntos</p>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <p class="text-sm text-zinc-400">No hay pagos registrados.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Por proveedor</p>
                            <h3 class="text-lg font-semibold text-white">Resumen</h3>
                        </div>
                        <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">{{ $reporteProveedores->count() }} proveedores</span>
                    </div>

                    <div class="space-y-2">
                        @forelse ($reporteProveedores as $provId => $data)
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-1">
                                <p class="text-sm font-semibold text-white">{{ $data['proveedor'] }}</p>
                                <p class="text-xs text-zinc-400">Pagos: {{ $data['pagos'] }} · Con comprobantes: {{ $data['conAdjuntos'] }}</p>
                                <p class="text-lg font-bold text-emerald-100">USD {{ number_format($data['total'], 2) }}</p>
                            </article>
                        @empty
                            <p class="text-sm text-zinc-400">Sin pagos por proveedor.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>
    </main>
</body>
</html>
