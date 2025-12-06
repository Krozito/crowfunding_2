@extends('creator.layouts.panel')

@section('title', 'Editar proveedor')
@section('active', 'proveedores')
@section('back_url', route('creador.proveedores'))
@section('back_label', 'Volver a proveedores')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Editar proveedor</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Actualiza datos y vinculacion</h2>
            <p class="mt-2 text-sm text-zinc-400">Cambia especialidad, contacto o proyecto asociado.</p>

            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('creador.proveedores.update', $proveedor) }}" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Nombre del proveedor</label>
                    <input name="nombre_proveedor" value="{{ old('nombre_proveedor', $proveedor->nombre_proveedor) }}" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Especialidad</label>
                    <input name="especialidad" value="{{ old('especialidad', $proveedor->especialidad) }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Contacto</label>
                    <input name="info_contacto" value="{{ old('info_contacto', $proveedor->info_contacto) }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Proyecto asociado</label>
                    <select name="proyecto_id" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Sin vincular</option>
                        @foreach ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" @selected(old('proyecto_id', $proveedor->proyecto_id) == $proyecto->id)>{{ $proyecto->titulo }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
