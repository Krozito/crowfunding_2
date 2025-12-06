<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs tracking-widest text-slate-400 uppercase">
                Módulo de proyectos
            </p>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Proyectos que estás apoyando
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="bg-slate-900/80 rounded-3xl border border-slate-800 p-6 shadow">
                @if($aportaciones->isEmpty())
                    <p class="text-sm text-slate-300">
                        Todavía no has realizado aportaciones. Explora proyectos y apoya tu primera campaña.
                    </p>
                @else
                    <table class="min-w-full text-sm text-left text-slate-200">
                        <thead class="text-xs uppercase text-slate-400 border-b border-slate-700">
                        <tr>
                            <th class="py-3">Proyecto</th>
                            <th class="py-3">Monto aportado</th>
                            <th class="py-3">Fecha</th>
                            <th class="py-3">Estado de pago</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($aportaciones as $aporte)
                            <tr class="border-b border-slate-800">
                                <td class="py-3">
                                    {{ optional($aporte->proyecto)->titulo ?? 'Proyecto eliminado' }}
                                </td>
                                <td class="py-3">
                                    ${{ number_format($aporte->monto, 2) }}
                                </td>
                                <td class="py-3">
                                    {{ $aporte->fecha_aportacion?->format('d/m/Y') }}
                                </td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded-full text-xs bg-slate-800">
                                        {{ ucfirst($aporte->estado_pago) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
