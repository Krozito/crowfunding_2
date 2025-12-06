@extends('creator.layouts.panel')

@section('title', 'Nuevo proyecto')
@section('active', 'proyectos')
@section('back_url', route('creador.proyectos'))
@section('back_label', 'Volver a proyectos')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-300">Publicar</p>
                    <h2 class="text-2xl font-bold text-white">Crea un nuevo proyecto</h2>
                    <p class="text-sm text-zinc-300">Define la informacion base: meta, modelo, ubicacion, fechas y portada.</p>
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

            <form method="POST" action="{{ route('creador.proyectos.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-4 md:grid-cols-2">
                @csrf
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Titulo</label>
                    <input name="titulo" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Nombre de tu proyecto">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Descripcion</label>
                    <textarea name="descripcion_proyecto" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Describe el problema, la solucion y el impacto"></textarea>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Meta de financiacion (USD)</label>
                    <input type="number" step="0.01" min="0" name="meta_financiacion" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="10000">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Modelo de financiamiento</label>
                    <input name="modelo_financiamiento" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="todo-o-nada / flexible">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Categoria</label>
                    <input name="categoria" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Impacto social, salud, tecnologia...">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Ubicacion</label>
                    <input name="ubicacion_geografica" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ciudad, pais">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Fecha limite</label>
                    <input type="date" name="fecha_limite" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="md:col-span-1">
                    <label class="text-sm text-zinc-300">Cronograma (JSON)</label>
                    <textarea name="cronograma" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder='[{"hito":"Fase 1","fecha":"2025-01-10"}]'></textarea>
                </div>
                <div class="md:col-span-1">
                    <label class="text-sm text-zinc-300">Presupuesto (JSON)</label>
                    <textarea name="presupuesto" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder='[{"concepto":"Materiales","monto":3000}]'></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Imagen de portada</label>
                    <input type="file" name="portada" accept="image/*" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                    <p class="mt-1 text-xs text-zinc-500">JPG o PNG, max 2MB.</p>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                        Publicar en borrador
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection
