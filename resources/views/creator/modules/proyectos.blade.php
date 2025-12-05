<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proyectos | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-20 top-0 h-64 w-64 rounded-full bg-emerald-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-lime-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al panel
                </a>
                <h1 class="text-lg font-semibold text-white">Proyectos</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-300">Campanas</p>
            <h2 class="mt-2 text-2xl font-bold text-white">Publica tu proyecto</h2>
            <p class="mt-2 text-sm text-zinc-300">Incluye descripcion, meta, cronograma y presupuesto (HU4, HU7).</p>

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
                    <input name="titulo" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="Nombre de tu proyecto">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Descripcion</label>
                    <textarea name="descripcion_proyecto" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="Describe el problema, la solucion y el impacto"></textarea>
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Meta de financiacion (USD)</label>
                    <input type="number" step="0.01" min="0" name="meta_financiacion" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="10000">
                </div>
                    <div>
                    <label class="text-sm text-zinc-300">Modelo de financiamiento</label>
                    <input name="modelo_financiamiento" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="todo-o-nada / flexible">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Categoria</label>
                    <input name="categoria" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="Impacto social, salud, tecnologia...">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Ubicacion</label>
                    <input name="ubicacion_geografica" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="Ciudad, pais">
                </div>
                <div>
                    <label class="text-sm text-zinc-300">Fecha limite</label>
                    <input type="date" name="fecha_limite" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                </div>
                <div class="md:col-span-1">
                    <label class="text-sm text-zinc-300">Cronograma (JSON)</label>
                    <textarea name="cronograma" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder='[{"hito":"Fase 1","fecha":"2025-01-10"}]'></textarea>
                </div>
                <div class="md:col-span-1">
                    <label class="text-sm text-zinc-300">Presupuesto (JSON)</label>
                    <textarea name="presupuesto" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder='[{"concepto":"Materiales","monto":3000}]'></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-zinc-300">Imagen de portada</label>
                    <input type="file" name="portada" accept="image/*" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                    <p class="mt-1 text-xs text-zinc-500">JPG o PNG, max 2MB.</p>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                        Guardar borrador
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-500">Tus proyectos</p>
                    <h3 class="text-lg font-semibold text-white">Borradores y publicados</h3>
                </div>
            </div>

            <div class="grid gap-4">
                @forelse ($proyectos as $proyecto)
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $proyecto->titulo }}</p>
                                <p class="text-xs text-zinc-400">Estado: {{ strtoupper($proyecto->estado ?? 'borrador') }}</p>
                            </div>
                            <span class="rounded-full bg-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-200">
                                Meta: ${{ number_format($proyecto->meta_financiacion, 0, ',', '.') }}
                            </span>
                        </div>
                        @if($proyecto->imagen_portada)
                            <div class="mt-3">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($proyecto->imagen_portada) }}" alt="Portada" class="h-32 w-full rounded-xl object-cover">
                            </div>
                        @endif
                        <form method="POST" action="{{ route('creador.proyectos.update', $proyecto) }}" enctype="multipart/form-data" class="mt-3 grid gap-3 md:grid-cols-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="titulo" value="{{ $proyecto->titulo }}">
                            <div>
                                <label class="text-xs text-zinc-400">Meta (USD)</label>
                                <input type="number" step="0.01" min="0" name="meta_financiacion" value="{{ $proyecto->meta_financiacion }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                            </div>
                            <div>
                                <label class="text-xs text-zinc-400">Estado</label>
                                <input name="estado" value="{{ $proyecto->estado }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400" placeholder="borrador/publicado">
                            </div>
                            <div>
                                <label class="text-xs text-zinc-400">Modelo financiamiento</label>
                                <input name="modelo_financiamiento" value="{{ $proyecto->modelo_financiamiento }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                            </div>
                            <div>
                                <label class="text-xs text-zinc-400">Fecha limite</label>
                                <input type="date" name="fecha_limite" value="{{ optional($proyecto->fecha_limite)->format('Y-m-d') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-zinc-400">Descripcion</label>
                                <textarea name="descripcion_proyecto" rows="2" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">{{ $proyecto->descripcion_proyecto }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs text-zinc-400">Reemplazar portada</label>
                                <input type="file" name="portada" accept="image/*" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white focus:border-emerald-400 focus:ring-emerald-400">
                            </div>
                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-500">
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-sm text-zinc-400">Aun no tienes proyectos.</p>
                @endforelse
            </div>
        </section>
    </main>
</body>
</html>
