@extends('creator.layouts.panel')

@section('title', 'Actualizar proyecto')
@section('active', 'proyectos')
@section('back_url', route('creador.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-300">Editar</p>
                    <h2 class="text-2xl font-bold text-white">Actualiza tu proyecto</h2>
                    <p class="text-sm text-zinc-300">Ajusta meta, fechas, descripcion o portada. Recuerda guardar los cambios.</p>
                </div>
                <div class="text-xs text-zinc-200">
                    <span class="rounded-full bg-white/5 px-3 py-1">{{ strtoupper($proyecto->estado ?? 'borrador') }}</span>
                </div>
            </div>

            @if (session('status'))
                <div class="mt-4 rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mt-4 rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('creador.proyectos.update', $proyecto) }}" enctype="multipart/form-data" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                @method('PATCH')
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Titulo</label>
                    <input name="titulo" value="{{ $proyecto->titulo }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Descripcion</label>
                    <textarea name="descripcion_proyecto" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">{{ $proyecto->descripcion_proyecto }}</textarea>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Meta de financiacion (USD)</label>
                    <input type="number" step="0.01" min="0" name="meta_financiacion" value="{{ $proyecto->meta_financiacion }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Estado</label>
                    <input name="estado" value="{{ $proyecto->estado }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="borrador/publicado">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Modelo de financiamiento</label>
                    <select name="modelo_financiamiento_id" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                        <option value="">Selecciona un modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->id }}" @selected($proyecto->modelo_financiamiento === $modelo->nombre)>{{ $modelo->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Categoria</label>
                    <select name="categoria_id" class="mt-1 w-full rounded-xl border border-white/10 bg-zinc-900/80 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                        <option value="">Selecciona una categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected($proyecto->categoria === $categoria->nombre)>{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Ubicacion</label>
                    <input name="ubicacion_geografica" value="{{ $proyecto->ubicacion_geografica }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Fecha limite</label>
                    <input type="date" name="fecha_limite" value="{{ optional($proyecto->fecha_limite)->format('Y-m-d') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Reemplazar portada</label>
                    <input type="file" name="portada" accept="image/*" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                    @if($proyecto->imagen_portada)
                        <p class="mt-1 text-xs text-zinc-400">Actual: <span class="text-white">{{ $proyecto->imagen_portada }}</span></p>
                    @endif
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
