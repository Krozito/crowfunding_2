@extends('creator.layouts.panel')

@section('title', 'Nuevo proveedor')
@section('active', 'proveedores')
@section('back_url', route('creador.proveedores'))
@section('back_label', 'Volver a proveedores')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Nuevo proveedor</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Vincular proveedor a tus proyectos</h2>
            <p class="mt-2 text-sm text-zinc-400">Registra los datos y asocialo a un proyecto si aplica.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('creador.proveedores.store') }}" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Nombre del proveedor</label>
                    <input name="nombre_proveedor" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej: Acme Logistica">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Especialidad</label>
                    <input name="especialidad" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Logistica, materiales, tech...">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Contacto</label>
                    <input name="info_contacto" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="correo@proveedor.com / +57 300...">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Proyecto asociado (opcional)</label>
                    <select name="proyecto_id" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                        <option value="">Sin vincular</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}">{{ $proyecto->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                        Guardar proveedor
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
