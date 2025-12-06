<x-guest-layout>
    <div class="min-h-screen bg-zinc-950 text-zinc-100 flex items-center justify-center px-4 py-10">
        <div class="relative isolate w-full max-w-5xl overflow-hidden rounded-3xl border border-white/10 bg-gradient-to-br from-indigo-900/70 via-zinc-900/85 to-zinc-950 shadow-2xl ring-1 ring-indigo-500/20">
            <div class="absolute -left-16 top-0 h-40 w-40 rounded-full bg-indigo-600/20 blur-3xl"></div>
            <div class="absolute right-4 bottom-0 h-32 w-32 rounded-full bg-emerald-500/15 blur-3xl"></div>

            <div class="relative grid gap-0 md:grid-cols-[1.05fr,0.95fr]">
                <div class="hidden md:flex flex-col justify-between border-r border-white/10 px-0 py-0 overflow-hidden">
                    <div class="relative flex-1">
                        <img src="/images/auth/pexels-davidmcbee-730547.jpg" alt="CrowdUp" class="h-full w-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-900/70 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-8 space-y-3">
                            
                            <h2 class="text-2xl font-bold text-white">Ingresa y gestiona</h2>
                            <p class="text-sm text-zinc-200">Accede a tus proyectos, avances, proveedores y fondos en un panel unificado.</p>
                            
                        </div>
                    </div>
                </div>

                <div class="px-6 py-8 sm:px-10 sm:py-12 space-y-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">Bienvenido</p>
                        <h1 class="mt-2 text-2xl font-bold text-white">Inicia sesión</h1>
                        <p class="text-sm text-zinc-400">Ingresa tus credenciales para continuar.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-sm text-zinc-300" for="email">Correo</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm text-zinc-300" for="password">Contraseña</label>
                            <input id="password" name="password" type="password" required autocomplete="current-password"
                                   class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div class="flex items-center justify-between text-sm text-zinc-300">
                            <label class="inline-flex items-center gap-2">
                                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-zinc-900 text-indigo-500 focus:ring-indigo-400">
                                Recordarme
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-indigo-300 hover:text-white" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>
                        <div class="space-y-3">
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                                Entrar
                            </button>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white hover:border-indigo-400/60">
                                    Crear cuenta
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
