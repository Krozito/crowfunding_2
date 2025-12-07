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

    <div class="rounded-3xl border border-white/10 bg-zinc-900/80 shadow-[0_24px_60px_rgba(0,0,0,0.45)] p-6 space-y-4">
        @if ($proveedores->isEmpty())
            <p class="text-sm text-zinc-300">Aún no hay proveedores registrados para este proyecto.</p>
        @else
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($proveedores as $prov)
                    @php
                        $pagosProveedor = $pagos[$prov->id] ?? collect();
                        $previewAdjunto = $pagosProveedor->first()?->adjuntos[0] ?? null;
                        $previewImg = $previewAdjunto ? asset('storage/'.$previewAdjunto) : 'https://images.unsplash.com/photo-1471879832106-c7ab9e0cee23?auto=format&fit=crop&w=700&q=80';
                    @endphp
                    <article class="rounded-2xl border border-white/10 bg-white/5 overflow-hidden text-sm text-white shadow-[0_22px_55px_rgba(0,0,0,0.45)] flex flex-col">
                        <div class="relative h-32 w-full overflow-hidden">
                            <img src="{{ $previewImg }}" alt="Comprobante" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                            <div class="absolute top-2 left-2 inline-flex items-center rounded-full bg-black/50 px-3 py-1 text-[11px] font-semibold text-white border border-white/15">
                                {{ $prov->especialidad ?? 'Proveedor' }}
                            </div>
                        </div>

                        <div class="p-4 space-y-3 flex-1 flex flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-400 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                        </svg>
                                        Proveedor
                                    </p>
                                    <h3 class="text-lg font-semibold">{{ $prov->nombre_proveedor ?? 'Proveedor' }}</h3>
                                    <p class="text-[11px] text-zinc-400">{{ $prov->info_contacto ?? 'Contacto no disponible' }}</p>
                                </div>
                            </div>

                            @if ($pagosProveedor->isNotEmpty())
                                <div class="rounded-xl border border-white/10 bg-black/30 p-3 space-y-2">
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-zinc-400">Pagos al proveedor</p>
                                    @foreach ($pagosProveedor as $pago)
                                        <div class="rounded-lg border border-white/10 bg-white/5 p-3 text-[12px] space-y-1">
                                            <p class="font-semibold text-white">{{ $pago->concepto ?? 'Pago' }}</p>
                                            <p class="text-emerald-200 font-semibold">USD {{ number_format($pago->monto ?? 0, 2) }}</p>
                                            <p class="text-zinc-400">Fecha: {{ optional($pago->fecha_pago)->format('d/m/Y') ?? 'N/D' }}</p>
                                            @if (!empty($pago->adjuntos))
                                                <div class="flex flex-wrap gap-2 pt-1">
                                                    @foreach ($pago->adjuntos as $idx => $archivo)
                                                        <a href="{{ asset('storage/'.$archivo) }}" target="_blank" class="inline-flex items-center gap-1 rounded-md border border-white/10 bg-white/10 px-2 py-1 text-[11px] hover:border-indigo-400/60">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                                            </svg>
                                                            Factura {{ $idx + 1 }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="rounded-xl border border-white/10 bg-black/20 p-3 text-[12px] text-zinc-300">
                                    Sin pagos registrados para este proveedor.
                                </div>
                            @endif

                            @if ($prov->historiales?->count())
                                <details class="rounded-xl border border-white/10 bg-black/30 p-3">
                                    <summary class="text-xs font-semibold text-indigo-200 cursor-pointer flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ver historial
                                    </summary>
                                    <div class="mt-2 space-y-2 text-[11px] text-zinc-300">
                                        @foreach ($prov->historiales as $hist)
                                            <div class="rounded-lg border border-white/10 bg-white/5 p-2">
                                                <p class="font-semibold text-white">{{ $hist->titulo ?? 'Actualización' }}</p>
                                                <p>{{ $hist->descripcion ?? 'Sin descripción' }}</p>
                                                <p class="text-zinc-500">{{ optional($hist->created_at)->format('d/m/Y') }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
