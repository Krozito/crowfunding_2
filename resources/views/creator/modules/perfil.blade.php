<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Perfil y verificacion | CrowdUp Creador</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden">
        <div class="absolute -left-24 top-0 h-64 w-64 rounded-full bg-indigo-600/25 blur-3xl"></div>
        <div class="absolute right-0 top-24 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('creador.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    <span aria-hidden="true">&larr;</span> Volver al panel
                </a>
                <h1 class="text-lg font-semibold text-white">Perfil y verificacion</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">CREADOR</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">
        @if (session('status'))
            <div class="rounded-2xl border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="rounded-2xl border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Confianza</p>
                    <h2 class="text-2xl font-bold text-white">Perfil profesional y redes</h2>
                    <p class="text-sm text-zinc-400">Completa tu biografia, profesion, experiencia, enlaces y foto. Previsualiza tu perfil publico.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-xs text-zinc-300">
                    @php
                        $estado = Auth::user()->estado_verificacion ? 'Verificado' : 'Pendiente';
                        $badge = Auth::user()->estado_verificacion
                            ? 'bg-emerald-500/15 text-emerald-100 border border-emerald-400/30'
                            : 'bg-amber-500/15 text-amber-100 border border-amber-400/30';
                    @endphp
                    <span class="rounded-full px-3 py-1 font-semibold {{ $badge }}">{{ $estado }}</span>
                    <a href="{{ route('creador.perfil.verificacion.form') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-white hover:border-indigo-400/60">
                        Solicitar verificacion
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('creador.perfil.update') }}" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1.05fr,0.95fr]">
                @csrf
                @method('PATCH')

                <div class="space-y-4 rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-indigo-500/10">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-sm text-zinc-300">Nombre completo</label>
                            <input name="nombre_completo" value="{{ old('nombre_completo', Auth::user()->nombre_completo ?? Auth::user()->name) }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Profesion</label>
                            <input name="profesion" value="{{ old('profesion', Auth::user()->profesion) }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Disenador UX, Ingeniero de software...">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-zinc-300">Experiencia (resumen)</label>
                        <textarea name="experiencia" rows="2" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="5+ anos liderando proyectos, lanzamientos, certificaciones...">{{ old('experiencia', Auth::user()->experiencia) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm text-zinc-300">Biografia</label>
                        <textarea name="biografia" rows="3" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Cuenta tu historia, logros y que te motiva a crear...">{{ old('biografia', Auth::user()->biografia) }}</textarea>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-sm text-zinc-300">LinkedIn</label>
                            <input name="redes_sociales[linkedin]" value="{{ old('redes_sociales.linkedin', Auth::user()->redes_sociales['linkedin'] ?? '') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="https://www.linkedin.com/in/usuario">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Twitter / X</label>
                            <input name="redes_sociales[twitter]" value="{{ old('redes_sociales.twitter', Auth::user()->redes_sociales['twitter'] ?? '') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="https://x.com/usuario">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Portfolio / Web</label>
                            <input name="redes_sociales[web]" value="{{ old('redes_sociales.web', Auth::user()->redes_sociales['web'] ?? '') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="https://miportfolio.com">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">GitHub</label>
                            <input name="redes_sociales[github]" value="{{ old('redes_sociales.github', Auth::user()->redes_sociales['github'] ?? '') }}" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="https://github.com/usuario">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-zinc-300">Info adicional</label>
                        <textarea name="info_personal" rows="2" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Certificaciones, idiomas, ubicacion...">{{ old('info_personal', Auth::user()->info_personal) }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm text-zinc-300">Foto de perfil (JPG/PNG, max 2MB)</label>
                        <input type="file" name="foto_perfil" class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-500/20 file:px-4 file:py-2 file:text-indigo-100 hover:file:bg-indigo-500/30">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-500/30 bg-emerald-500/20 px-4 py-2.5 text-sm font-semibold text-emerald-50 hover:border-emerald-400/60">
                            Guardar perfil
                        </button>
                    </div>
                </div>

                <section class="space-y-3 rounded-3xl border border-white/10 bg-zinc-900/70 p-6 shadow-2xl ring-1 ring-emerald-500/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Vista previa</p>
                            <h3 class="text-lg font-bold text-white">Perfil publico</h3>
                            <p class="text-sm text-zinc-400">Asi veran tu perfil los colaboradores.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="h-14 w-14 overflow-hidden rounded-full border border-white/10 bg-zinc-800">
                                @if (Auth::user()->foto_perfil)
                                    <img src="{{ asset('storage/'.Auth::user()->foto_perfil) }}" alt="Foto de perfil" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs text-zinc-400">Sin foto</div>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>
                                <p class="text-xs text-zinc-400">{{ Auth::user()->profesion ?? 'Profesion no definida' }}</p>
                            </div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-sm text-zinc-200">
                                {{ Auth::user()->biografia ?? 'Comparte tu historia, logros y motivacion.' }}
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                            <p class="text-[11px] text-zinc-500">Experiencia</p>
                            <p class="text-sm text-white">{{ Auth::user()->experiencia ?? 'Agrega un resumen de tu experiencia.' }}</p>
                        </div>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold text-zinc-400">Redes y enlaces</p>
                            <div class="flex flex-wrap gap-2 text-xs">
                                @php $links = Auth::user()->redes_sociales ?? []; @endphp
                                @forelse ($links as $label => $url)
                                    @if ($url)
                                        <a href="{{ $url }}" target="_blank" class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-white hover:border-indigo-400/60">
                                            {{ ucfirst($label) }}
                                        </a>
                                    @endif
                                @empty
                                    <span class="text-zinc-400">Sin enlaces agregados.</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="rounded-xl border border-white/10 bg-zinc-900/60 px-3 py-2 text-xs text-zinc-300">
                            <p class="text-[11px] text-zinc-500">Info adicional</p>
                            <p class="text-sm text-white">{{ Auth::user()->info_personal ?? 'Completa certificaciones, ubicacion, idiomas.' }}</p>
                        </div>
                    </div>
                </section>
            </form>

        </section>
    </main>
</body>
</html>
