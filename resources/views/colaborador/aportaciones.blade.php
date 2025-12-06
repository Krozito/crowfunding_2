<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs tracking-widest text-slate-400 uppercase">
                Módulo de aportaciones
            </p>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Historial de aportaciones
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="bg-slate-900/80 rounded-3xl border border-slate-800 p-6 shadow">
                @if($aportaciones->isEmpty())
                    <p class="text-sm text-slate-300">
                        Aún no has realizado ninguna aportación.
                    </p>
                @else
                    <ul class="divide-y divide-slate-800">
                        @foreach($aportaciones as $aporte)
                            <li class="py-4 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-slate-100">
                                        {{ optional($aporte->proyecto)->titulo ?? 'Proyecto eliminado' }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ $aporte->fecha_aportacion?->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-emerald-400">
                                        ${{ number_format($aporte->monto, 2) }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        Estado: {{ ucfirst($aporte->estado_pago) }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
