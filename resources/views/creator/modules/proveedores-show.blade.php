@extends('creator.layouts.panel')

@section('title', 'Historial de proveedor')
@section('active', 'proveedores')
@section('back_url', route('creador.proveedores'))
@section('back_label', 'Volver a proveedores')

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

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Proveedor</p>
                    <h2 class="text-2xl font-bold text-white">{{ $proveedor->nombre_proveedor }}</h2>
                    <p class="text-sm text-zinc-400">Especialidad: {{ $proveedor->especialidad ?? 'Sin especialidad' }}</p>
                    <p class="text-xs text-zinc-500">Proyecto: {{ $proveedor->proyecto->titulo ?? 'Sin vincular' }}</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('creador.proveedores') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                        Ver listado
                    </a>
                    <a href="{{ route('creador.proveedores.edit', $proveedor) }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                        Editar proveedor
                    </a>
                </div>
            </div>
            @php
                $avg = $proveedor->calificacion_promedio;
                $avgCard = $avg === null
                    ? ['border' => 'border-white/10', 'bg' => 'bg-zinc-900/60', 'text' => 'text-zinc-200', 'label' => 'N/D']
                    : ($avg < 5
                        ? ['border' => 'border-red-500/30', 'bg' => 'bg-red-500/10', 'text' => 'text-red-100', 'label' => number_format($avg, 1) . '/10']
                        : ($avg == 5
                            ? ['border' => 'border-amber-400/30', 'bg' => 'bg-amber-400/10', 'text' => 'text-amber-100', 'label' => number_format($avg, 1) . '/10']
                            : ['border' => 'border-emerald-500/30', 'bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-100', 'label' => number_format($avg, 1) . '/10']));
            @endphp
            <div class="mt-4 grid gap-3 text-sm text-zinc-300 md:grid-cols-4">
                <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                    <p class="text-xs text-zinc-500">Contacto</p>
                    <p class="font-medium text-white">{{ $proveedor->info_contacto ?? 'N/D' }}</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                    <p class="text-xs text-zinc-500">Creado</p>
                    <p class="font-medium text-white">{{ $proveedor->created_at?->format('d/m/Y') }}</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2">
                    <p class="text-xs text-zinc-500">ID</p>
                    <p class="font-medium text-white">#{{ $proveedor->id }}</p>
                </div>
                <div class="rounded-xl px-3 py-2 {{ $avgCard['bg'] }} {{ $avgCard['border'] }}">
                    <p class="text-xs text-white/70">Promedio calificacion</p>
                    <p class="text-lg font-semibold {{ $avgCard['text'] }}">{{ $avgCard['label'] }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-2">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Historial</p>
                <h3 class="text-lg font-semibold text-white">Compras y entregas</h3>
                <p class="text-sm text-zinc-400">Revisa el historial y registra nuevas compras sin tener que hacer scroll.</p>
            </div>

            <details id="form-compra" class="overflow-hidden rounded-2xl border border-white/10 bg-white/5 ring-1 ring-indigo-500/10">
                <summary class="flex cursor-pointer items-center justify-between gap-2 px-5 py-4 text-sm font-semibold text-white rounded-xl bg-emerald-600/80 hover:bg-emerald-600 border border-emerald-500/40">
                    Registrar nueva compra
                    <span class="text-[11px] text-zinc-400">Click para abrir / cerrar</span>
                </summary>
                <div class="border-t border-white/10 bg-zinc-900/60 px-5 py-5">
                    <form method="POST" action="{{ route('creador.proveedores.historial.store', $proveedor) }}" class="grid gap-3 md:grid-cols-2">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="text-sm text-zinc-300">Concepto</label>
                            <input name="concepto" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Compra de materiales, servicio de logistica...">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Monto (USD)</label>
                            <input type="number" step="0.01" min="0" name="monto" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="500">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Fecha de entrega</label>
                            <input type="date" name="fecha_entrega" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Calificacion (1-10)</label>
                            <input type="number" name="calificacion" min="1" max="10" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="8">
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                                Guardar en historial
                            </button>
                        </div>
                    </form>
                </div>
            </details>

            <div class="space-y-3">
                @forelse ($proveedor->historiales as $item)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 flex flex-col gap-2 ring-1 ring-indigo-500/10">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-white">{{ $item->concepto }}</p>
                            @if($item->calificacion)
                                @php
                                    $calColor = $item->calificacion < 5
                                        ? 'bg-red-500/15 text-red-200'
                                        : ($item->calificacion == 5
                                            ? 'bg-amber-400/20 text-amber-100'
                                            : 'bg-emerald-500/15 text-emerald-200');
                                @endphp
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $calColor }}">Calificacion: {{ $item->calificacion }}/10</span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-400">Monto: ${{ number_format($item->monto, 2, ',', '.') }}</p>
                        <p class="text-xs text-zinc-400">Entrega: {{ $item->fecha_entrega?->format('d/m/Y') ?? 'Sin fecha' }}</p>
                        <p class="text-xs text-zinc-500">Registrado {{ $item->created_at?->format('d/m/Y H:i') }}</p>
                    </div>
                @empty
                    <p class="text-sm text-zinc-400">Aun no hay registros de historial para este proveedor.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
