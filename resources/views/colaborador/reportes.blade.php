<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs tracking-widest text-slate-400 uppercase">
                Módulo de reportes
            </p>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Reportes financieros personales
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div class="bg-slate-900/80 rounded-3xl border border-slate-800 p-6 shadow">
                    <p class="text-xs uppercase text-slate-400 mb-1">
                        Total aportado
                    </p>
                    <p class="text-3xl font-bold text-white">
                        ${{ number_format($totalAportado, 2) }}
                    </p>
                </div>

                <div class="bg-slate-900/80 rounded-3xl border border-slate-800 p-6 shadow">
                    <p class="text-xs uppercase text-slate-400 mb-1">
                        Número de aportaciones
                    </p>
                    <p class="text-3xl font-bold text-white">
                        {{ $totalAportaciones }}
                    </p>
                </div>

                <div class="bg-slate-900/80 rounded-3xl border border-slate-800 p-6 shadow">
                    <p class="text-xs uppercase text-slate-400 mb-1">
                        Colaborador
                    </p>
                    <p class="text-xl font-semibold text-white">
                        {{ $user->name }}
                    </p>
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
