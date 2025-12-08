@extends('colaborador.layouts.panel')

@section('title', 'Proveedores del proyecto')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos.resumen', $proyecto))
@section('back_label', 'Volver al resumen')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-6">
    <header class="space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Proveedores</p>
        <h1 class="text-2xl font-bold text-white">Proveedores asociados</h1>
        <p class="text-sm text-zinc-400">Listado de proveedores para el proyecto {{ $proyecto->titulo }}.</p>
    </header>

    <form method="GET" action="{{ route('colaborador.proyectos.proveedores', $proyecto) }}" class="rounded-3xl border border-white/15 bg-[#030712] p-4 grid gap-3 sm:grid-cols-[2fr,1fr,auto] sm:items-center shadow-[0_20px_50px_rgba(0,0,0,0.5)] relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-0.5 bg-sky-500/70"></div>
        <input type="text" name="q" value="{{ $search }}" placeholder="Buscar por nombre, especialidad o contacto"
               class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500">
        <select name="promedio" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-sky-500 focus:ring-sky-500">
            <option value="" style="background-color:#030712;color:#fff;">Todos los promedios</option>
            <option value="4" @selected($promedio === '4') style="background-color:#030712;color:#fff;">4.0 o mas</option>
            <option value="3" @selected($promedio === '3') style="background-color:#030712;color:#fff;">3.0 a 3.9</option>
            <option value="2" @selected($promedio === '2') style="background-color:#030712;color:#fff;">0 a 2.9</option>
            <option value="sin" @selected($promedio === 'sin') style="background-color:#030712;color:#fff;">Sin calificacion</option>
        </select>
        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
            Filtrar
        </button>
    </form>

    <div class="rounded-3xl border border-white/15 bg-[#0b1020] shadow-[0_24px_60px_rgba(0,0,0,0.45)] p-6 space-y-4">
        @if ($proveedores->isEmpty())
            <p class="text-sm text-zinc-300">Aun no hay proveedores registrados para este proyecto.</p>
        @else
            <div class="grid gap-6 grid-cols-1">
                @foreach ($proveedores as $prov)
                    @php
                        $pagosProveedor = $pagos[$prov->id] ?? collect();
                        $totalProveedor = $pagosProveedor->sum('monto');
                        $calificacionPromedio = $prov->historiales->avg('calificacion');
                        $filledStars = (int) floor($calificacionPromedio ?? 0);
                        $ultimoPago = $pagosProveedor->max('fecha_pago');
                        $ultimoPagoFecha = $ultimoPago
                            ? optional($ultimoPago instanceof \Illuminate\Support\Carbon ? $ultimoPago : \Illuminate\Support\Carbon::parse($ultimoPago))->format('d/m/Y')
                            : null;
                    @endphp
                    <article class="rounded-2xl border border-white/15 bg-[#0b1020] text-sm text-white shadow-[0_18px_40px_rgba(0,0,0,0.4)] flex flex-col">
                        <div class="flex flex-wrap items-center gap-4 lg:gap-6 lg:flex-nowrap lg:justify-start border-b border-white/10 bg-white/5 px-4 py-3">
                            <div class="space-y-1">
                                <p class="text-[10px] uppercase tracking-[0.14em] text-zinc-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                    </svg>
                                    Proveedor
                                </p>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-semibold">{{ $prov->nombre_proveedor ?? 'Proveedor' }}</h3>
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-semibold bg-sky-500/15 text-sky-200 border border-sky-400/40">
                                        <span class="h-1.5 w-1.5 rounded-full bg-current opacity-80"></span>
                                        Proveedor activo
                                    </span>
                                </div>
                                <div class="flex flex-wrap gap-2 text-[12px] text-zinc-300">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-white/5 px-2.5 py-1 border border-white/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-3-3h-2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20H4v-2a3 3 0 013-3h2" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4a4 4 0 116 0M5 8a4 4 0 104 4H5a4 4 0 01-4-4z" />
                                        </svg>
                                        Especialidad: {{ $prov->especialidad ?? 'No especificada' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-white/5 px-2.5 py-1 border border-white/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.105-1.789L9 1l5 2.829L19.895 3.83A2 2 0 0121 5.618v9.764a2 2 0 01-1.105 1.789L14 20l-5-2.829z" />
                                        </svg>
                                        Tipo: {{ $prov->tipo ?? 'No especificado' }}
                                    </span>
                                </div>
                                <p class="text-xs text-zinc-500">Contacto: {{ $prov->info_contacto ?? 'No disponible' }}</p>
                            </div>
                            <div class="text-right space-y-3 lg:ml-auto">
                                <div class="rounded-xl border border-white/15 bg-white/5 px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-[0.18em] text-sky-200">Promedio</p>
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="flex items-center gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i <= $filledStars ? 'text-amber-300' : 'text-zinc-600' }}" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-base font-semibold text-white">{{ $calificacionPromedio ? number_format($calificacionPromedio, 1) : 'N/D' }}</p>
                                    </div>
                                </div>
                                <div class="rounded-xl border border-white/15 bg-white/5 px-3 py-2">
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-sky-200">Total pagado</p>
                                    <p class="text-2xl font-black text-sky-100">USD {{ number_format($totalProveedor, 2) }}</p>
                                    <p class="text-[11px] text-zinc-400">En {{ $pagosProveedor->count() }} pagos</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap items-center gap-2 text-sm text-white">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-sky-500/10 px-3 py-1.5 text-sky-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 17l-5 3 1.9-5.9L4 9.5l6-.3L12 4l2 5.2 6 .3-4.9 4.6L17 20z" />
                                    </svg>
                                    Promedio: {{ $calificacionPromedio ? number_format($calificacionPromedio, 1) : 'N/D' }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-lg bg-white/10 px-3 py-1.5 text-zinc-200">
                                    Pagos: {{ $pagosProveedor->count() }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-lg bg-white/10 px-3 py-1.5 text-zinc-200">
                                    Ultimo pago: {{ $ultimoPagoFecha ?? 'Sin pagos' }}
                                </span>
                            </div>
                            <a href="{{ route('colaborador.proyectos.proveedores.show', [$proyecto, $prov]) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-[12px] font-semibold text-white hover:bg-sky-700 transition-colors">
                                Ver historial
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
