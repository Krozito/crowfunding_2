@extends('colaborador.layouts.panel')

@section('title', 'Aportar al proyecto')
@section('active', 'proyectos')
@section('back_url', route('colaborador.proyectos.show', $proyecto))
@section('back_label', 'Volver al detalle')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-8">
    <div class="rounded-2xl border border-white/10 bg-zinc-950/80 shadow-[0_24px_60px_rgba(0,0,0,0.45)] overflow-hidden">
        <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] uppercase tracking-[0.24em] text-indigo-200">Aportar al proyecto</p>
                        <h1 class="text-2xl font-bold text-white">{{ $proyecto->titulo }}</h1>
                        <p class="text-xs text-zinc-400">Por {{ $proyecto->creador->nombre_completo ?? $proyecto->creador->name ?? 'Creador' }}</p>
                    </div>
                    @if($proyecto->categoria)
                        <span class="inline-flex items-center rounded-full bg-indigo-500/20 px-3 py-1 text-[11px] font-semibold text-indigo-100 border border-indigo-400/30">
                            {{ ucfirst($proyecto->categoria) }}
                        </span>
                    @endif
                </div>

                @if ($errors->any())
                    <div class="rounded-xl border border-red-500/40 bg-red-500/10 p-3 text-sm text-red-100">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('colaborador.proyectos.aportar.store', $proyecto) }}" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs text-zinc-400">Monto a aportar (USD)</label>
                        <input type="number" name="monto" min="1" step="0.01" value="{{ old('monto', 25) }}"
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-emerald-400 focus:ring-emerald-400"
                               required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs text-zinc-400">Seleccionar recompensa (opcional)</label>
                        <select name="recompensa_id"
                                class="w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            <option value="">Sin recompensa</option>
                            @foreach ($proyecto->recompensas as $recompensa)
                                <option value="{{ $recompensa->id }}" @selected(old('recompensa_id') == $recompensa->id)>
                                    Desde ${{ number_format($recompensa->monto_minimo ?? $recompensa->monto_minimo_aportacion ?? 0, 0, ',', '.') }} - {{ $recompensa->titulo ?? 'Recompensa' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs text-zinc-400">Mensaje para el creador (opcional)</label>
                        <textarea name="mensaje" rows="3" class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-zinc-500 focus:border-indigo-400 focus:ring-indigo-400">{{ old('mensaje') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <p class="text-xs text-zinc-400">Metodo de pago</p>
                        <div class="grid gap-2 sm:grid-cols-3">
                            <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white cursor-pointer">
                                <input type="radio" name="metodo" value="tarjeta" class="text-emerald-400 focus:ring-emerald-400" checked>
                                Tarjeta
                            </label>
                            <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white cursor-pointer">
                                <input type="radio" name="metodo" value="transferencia" class="text-emerald-400 focus:ring-emerald-400">
                                Transferencia
                            </label>
                            <label class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white cursor-pointer">
                                <input type="radio" name="metodo" value="wallet" class="text-emerald-400 focus:ring-emerald-400">
                                Wallet
                            </label>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-5 py-3 text-sm font-bold uppercase tracking-wide text-emerald-950 shadow-[0_12px_35px_rgba(16,185,129,0.35)] hover:bg-emerald-400 transition-colors">
                            Confirmar aporte
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="border-t border-white/5 bg-black/40 lg:border-l lg:border-t-0 p-6 space-y-4">
                <div class="rounded-xl border border-white/10 bg-zinc-900/70 p-4 space-y-2">
                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Resumen</p>
                    <p class="text-sm text-zinc-300">{{ \Illuminate\Support\Str::limit($proyecto->descripcion_proyecto, 120) }}</p>
                    <div class="flex items-center justify-between text-sm text-indigo-50/90">
                        <span>Meta</span>
                        <span class="font-semibold text-white">${{ number_format($proyecto->meta_financiacion ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-indigo-50/90">
                        <span>Recaudado</span>
                        <span class="font-semibold text-white">${{ number_format($proyecto->monto_recaudado ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="rounded-xl border border-emerald-400/20 bg-emerald-500/10 p-4 text-sm text-emerald-100">
                    Tu aporte acerca este proyecto a su meta. Gracias por impulsar ideas con impacto.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
