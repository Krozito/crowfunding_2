@extends('auditor.layouts.panel')

@section('title', 'Reportes sospechosos')
@section('active', 'sospechosos')

@section('content')
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 pt-6">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Modulo 4</p>
            <h1 class="text-2xl font-bold text-white">Reportes sospechosos de colaboradores</h1>
            <p class="text-sm text-zinc-400">Centralizar denuncias, cruzar con gastos y cerrar casos.</p>
        </div>
    </div>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 rounded-3xl border border-white/15 bg-[#030712] shadow-[0_24px_60px_rgba(0,0,0,0.55)] p-6 relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-sky-500/70"></div>

        <form method="GET" action="{{ route('auditor.reportes') }}" class="rounded-3xl border border-white/10 bg-white/5 p-4 grid gap-3 md:grid-cols-[1.5fr,1fr,auto] md:items-end shadow-[0_12px_30px_rgba(0,0,0,0.35)] relative overflow-hidden">
            <div class="absolute inset-x-0 top-0 h-0.5 bg-white/10"></div>
            <div>
                <p class="text-[11px] uppercase tracking-[0.3em] text-zinc-400">Buscar en reportes</p>
                <input
                    type="text"
                    name="q"
                    value="{{ $q ?? '' }}"
                    placeholder="Proyecto, colaborador o parte del motivo"
                    class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-zinc-500 focus:border-sky-500 focus:ring-sky-500"
                >
                <p class="text-xs text-zinc-400 mt-1">La búsqueda recorre títulos, nombres y descripciones de cada reporte.</p>
            </div>
            <div>
                <label class="text-xs text-zinc-400">Estado</label>
                <select
                    name="estado"
                    class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-sky-500 focus:ring-sky-500"
                >
                    <option value="">Todos</option>
                    @php
                        $estadosFiltro = collect(['pendiente', 'aprobado', 'rechazado'])
                            ->merge($estados ?? collect())
                            ->unique()
                            ->values();
                    @endphp
                    @foreach ($estadosFiltro as $opt)
                        <option value="{{ $opt }}" {{ ($estado ?? '') === $opt ? 'selected' : '' }}>
                            {{ ucfirst($opt) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700 w-full">
                    Filtrar
                </button>
                <a href="{{ route('auditor.reportes') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-zinc-200 hover:border-white/25">
                    Limpiar
                </a>
            </div>
        </form>

        <div class="h-px w-full bg-white/5"></div>

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-5">
            @forelse ($reportesColab as $item)
                @php
                    $estadoActual = $item->estado ?? 'pendiente';
                    $estadoClase = match ($estadoActual) {
                        'aprobado' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/40',
                        'rechazado' => 'bg-rose-500/15 text-rose-200 border-rose-400/40',
                        default => 'bg-amber-500/15 text-amber-200 border-amber-400/40',
                    };
                @endphp
                <article class="rounded-2xl border border-white/10 bg-[#0b1020] p-5 shadow-[0_10px_30px_rgba(0,0,0,0.35)] space-y-4">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div class="space-y-1">
                            <p class="text-xs uppercase tracking-[0.3em] text-sky-200">ID #{{ $item->id }} · {{ $item->proyecto->titulo ?? 'Proyecto' }}</p>
                            <p class="text-sm text-zinc-400">Colaborador: {{ $item->colaborador->nombre_completo ?? $item->colaborador->name ?? 'N/D' }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-1 text-right">
                            <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[11px] font-semibold {{ $estadoClase }}">
                                <span class="h-2 w-2 rounded-full bg-current opacity-80"></span>
                                {{ ucfirst($estadoActual) }}
                            </span>
                            <p class="text-[11px] text-zinc-400">Enviado {{ optional($item->created_at)->format('d/m/Y H:i') ?? 'N/D' }}</p>
                        </div>
                    </div>
                    <p class="text-base font-semibold text-white leading-relaxed">{{ $item->motivo }}</p>
                    @if (!empty($item->evidencias))
                        <div class="flex flex-wrap gap-2">
                            @foreach ($item->evidencias as $idx => $path)
                                <a href="{{ asset('storage/'.$path) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-1.5 text-[12px] font-semibold text-white hover:border-indigo-400/60">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6" />
                                    </svg>
                                    Evidencia {{ $idx + 1 }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('auditor.reportes.estado', $item) }}" class="space-y-3 border-t border-white/10 pt-3" data-auditor-report>
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="reporte_id" value="{{ $item->id }}">

                        <label class="text-xs uppercase tracking-[0.3em] text-zinc-500">Explicación del auditor</label>
                        <p class="text-[11px] text-zinc-400">Este comentario quedará registrado en el historial del caso. Obligatorio (mínimo 20 caracteres, máximo 500).</p>
                        <textarea
                            name="respuesta"
                            rows="4"
                            class="w-full rounded-xl border border-zinc-700 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-zinc-500 focus:border-sky-500 focus:ring-sky-500"
                            placeholder="Describe por qué apruebas o rechazas este reporte."
                            maxlength="500"
                        >{{ old('reporte_id') == $item->id ? old('respuesta') : ($item->respuesta ?? '') }}</textarea>
                        @if ($errors->has('respuesta') && old('reporte_id') == $item->id)
                            <p class="text-xs text-rose-300">{{ $errors->first('respuesta') }}</p>
                        @endif
                        <div class="flex justify-between text-[11px] text-zinc-400">
                            <span>Mínimo 20 caracteres requeridos.</span>
                            <span data-char-count class="font-mono">0 / 500</span>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit" name="accion" value="rechazar" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-4 py-2 text-sm font-semibold text-white hover:from-purple-500 hover:to-fuchsia-500 flex-1 opacity-60" disabled>
                                Rechazar
                            </button>
                            <button type="submit" name="accion" value="aprobar" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 to-fuchsia-600 px-4 py-2 text-sm font-semibold text-white hover:from-purple-500 hover:to-fuchsia-500 flex-1 opacity-60" disabled>
                                Marcar como aprobado
                            </button>
                        </div>
                    </form>
                </article>
            @empty
                <p class="text-sm text-zinc-500">No hay reportes de colaboradores registrados.</p>
            @endforelse
        </div>

        @if ($reportesColab instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-4">
                {{ $reportesColab->links() }}
            </div>
        @endif
    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const minChars = 20;
    document.querySelectorAll('[data-auditor-report]').forEach(form => {
        const textarea = form.querySelector('textarea[name="respuesta"]');
        const counter = form.querySelector('[data-char-count]');
        const buttons = Array.from(form.querySelectorAll('button[name="accion"]'));

        const updateState = () => {
            const length = textarea?.value.trim().length ?? 0;
            if (counter) {
                counter.textContent = `${Math.min(length, 500)} / 500`;
            }
            buttons.forEach(btn => {
                const meets = length >= minChars;
                btn.disabled = !meets;
                btn.classList.toggle('opacity-60', !meets);
            });
        };

        textarea?.addEventListener('input', updateState);
        updateState();
    });
});
</script>
@endpush
