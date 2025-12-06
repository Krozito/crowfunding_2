@extends('creator.layouts.panel')

@section('title', 'Reportes y comprobantes')
@section('active', 'reportes')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proyectos</p>
                    <h2 class="text-2xl font-bold text-white">Reportes de pagos a proveedores</h2>
                    <p class="text-sm text-zinc-400">Sube facturas, comprobantes y vincula cada pago a un desembolso aprobado y proveedor.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                    <a href="{{ route('creador.fondos.historial', ['proyecto' => $selectedProjectId]) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-500">
                        Ver desembolsos
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('creador.reportes') }}" class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-end">
                <div>
                    <label class="text-xs text-zinc-400">Proyecto</label>
                    <select name="proyecto" class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        @forelse ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" @selected($selectedProjectId == $proyecto->id)>{{ $proyecto->titulo }}</option>
                        @empty
                            <option value="">Sin proyectos disponibles</option>
                        @endforelse
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                    Ver reportes
                </button>
            </form>
        </section>

        @if ($proyectos->isEmpty())
            <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 text-sm text-zinc-300 shadow-2xl">
                No tienes proyectos creados aun. <a class="text-indigo-300 underline" href="{{ route('creador.proyectos') }}">Crea un proyecto</a> para subir facturas y comprobantes.
            </section>
        @else
            <div class="grid gap-6 lg:grid-cols-[1.05fr,0.95fr]">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Resumen</p>
                            <h3 class="text-lg font-bold text-white">Compras y gastos reportados</h3>
                            <p class="text-sm text-zinc-400">Pagos asociados a desembolsos aprobados.</p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Total pagado</p>
                            <p class="text-2xl font-bold text-white">USD {{ number_format($resumen['totalPagado'], 2) }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Pagos con adjuntos</p>
                            <p class="text-xl font-bold text-emerald-100">{{ $resumen['pagosConAdjuntos'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="text-[11px] text-zinc-400">Comprobantes pendientes</p>
                            <p class="text-xl font-bold text-amber-100">{{ max($resumen['pagosProveedor'] - $resumen['pagosConAdjuntos'], 0) }}</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-zinc-900/60 p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-white">Pagos registrados</p>
                            <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Total: {{ $pagos->count() }}</span>
                        </div>
                        <div class="space-y-3">
                            @forelse ($pagos as $pago)
                                @php
                                    $estado = ucfirst($pago->solicitud->estado ?? 'pendiente');
                                    $badge = match($pago->solicitud->estado ?? '') {
                                        'aprobado', 'liberado', 'pagado', 'gastado' => 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30',
                                        'rechazado' => 'bg-red-500/15 text-red-100 border border-red-400/30',
                                        default => 'bg-amber-500/15 text-amber-100 border border-amber-400/30',
                                    };
                                @endphp
                                <article class="rounded-2xl border border-white/10 bg-white/5 p-4 shadow-inner ring-1 ring-indigo-500/10 space-y-2">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-semibold text-white">{{ $pago->concepto ?? 'Pago a proveedor' }}</p>
                                            <p class="text-xs text-zinc-400">{{ $pago->fecha_pago?->format('d/m/Y H:i') }} - Proveedor: {{ $pago->proveedor->nombre_proveedor ?? 'N/D' }}</p>
                                            <p class="text-sm text-zinc-200">Monto: USD {{ number_format($pago->monto, 2) }} - Hito: {{ $pago->solicitud->hito ?? 'N/D' }}</p>
                                        </div>
                                        <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $badge }}">{{ $estado }}</span>
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
                                <p class="text-sm text-zinc-400">Sin pagos registrados para este proyecto.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-emerald-500/10 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Nuevo pago</p>
                            <h3 class="text-lg font-bold text-white">Subir factura y comprobantes</h3>
                            <p class="text-sm text-zinc-400">Asocia el pago a un desembolso aprobado y a un proveedor registrado.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('creador.reportes.pagos.store', $selectedProjectId) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-sm text-zinc-300">Desembolso aprobado *</label>
                            <select name="solicitud_id" required class="mt-1 w-full rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                                <option value="">Selecciona un desembolso</option>
                                @foreach ($solicitudes as $solicitud)
                                    <option value="{{ $solicitud->id }}" @selected(old('solicitud_id') == $solicitud->id)>{{ $solicitud->hito ?? 'Hito' }} - {{ ucfirst($solicitud->estado) }} - USD {{ number_format($solicitud->monto_solicitado, 2) }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-zinc-500 mt-1">Solo aparecen desembolsos aprobados/liberados.</p>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Proveedor *</label>
                            <select name="proveedor_id" required class="mt-1 w-full rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                                <option value="">Selecciona un proveedor</option>
                                @foreach ($proveedores as $prov)
                                    <option value="{{ $prov->id }}" @selected(old('proveedor_id') == $prov->id)>{{ $prov->nombre_proveedor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="text-sm text-zinc-300">Monto *</label>
                                <input type="number" name="monto" step="0.01" min="0.01" value="{{ old('monto') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. 450.00" required>
                            </div>
                            <div>
                                <label class="text-sm text-zinc-300">Fecha de pago</label>
                                <input type="date" name="fecha_pago" value="{{ old('fecha_pago') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Concepto</label>
                            <input name="concepto" value="{{ old('concepto') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej. Pago factura 001 a proveedor">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Adjuntar facturas y comprobantes</label>
                            <input type="file" name="adjuntos[]" multiple class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30">
                            <p class="mt-1 text-xs text-zinc-500">Maximo 8MB por archivo.</p>
                        </div>
                        <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                            Registrar pago
                        </button>
                    </div>
                </form>
                </section>
            </div>
        @endif
    </div>
@endsection
