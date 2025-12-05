<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Crear nivel de recompensa | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-20 top-0 h-64 w-64 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.recompensas') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver a recompensas
                </a>
                <h1 class="text-lg font-semibold text-white">Crear / editar nivel</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/15 space-y-4">
            <div>
                <h2 class="text-2xl font-bold text-white">Nuevo nivel de recompensa</h2>
                <p class="text-sm text-zinc-400">Define monto, descripcion, beneficios y disponibilidad limitada.</p>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('creador.recompensas.store') }}" class="grid gap-4">
                @csrf
                <div>
                    <label class="text-xs text-zinc-400">Proyecto asociado</label>
                    <select name="proyecto_id" required class="mt-1 w-full appearance-none rounded-xl border border-white/15 bg-zinc-900/80 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        @forelse ($proyectos as $proyecto)
                            <option value="{{ $proyecto->id }}" @selected(old('proyecto_id') == $proyecto->id)>{{ $proyecto->titulo }}</option>
                        @empty
                            <option value="">Sin proyectos disponibles</option>
                        @endforelse
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-xs text-zinc-400">Nombre del nivel</label>
                        <input name="titulo" value="{{ old('titulo') }}" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Early bird, Kit, VIP...">
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Monto minimo (USD)</label>
                        <input name="monto_minimo_aportacion" type="number" min="0" step="1" value="{{ old('monto_minimo_aportacion') }}" required class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="25">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-zinc-400">Descripcion</label>
                    <textarea name="descripcion" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Que incluye este nivel y por que es atractivo">{{ old('descripcion') }}</textarea>
                </div>

                <div>
                    <label class="text-xs text-zinc-400">Beneficios (separados por coma)</label>
                    <input class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Sticker digital, Agradecimiento publico, Acceso a AMA">
                </div>

                <div class="grid gap-3 sm:grid-cols-[1fr,auto] sm:items-end">
                    <div>
                        <label class="text-xs text-zinc-400">Disponibilidad (unidades)</label>
                        <input name="disponibilidad" type="number" min="0" class="mt-1 w-full rounded-lg border border-white/10 bg-zinc-900 px-3 py-2 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Ej: 50 unidades" value="{{ old('disponibilidad') }}">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                            Publicar recompensa
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
