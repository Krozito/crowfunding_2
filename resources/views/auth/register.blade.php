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
                            <div class="flex items-center gap-3">
                                
                            </div>
                            <h2 class="text-2xl font-bold text-white">Registro</h2>
                            <p class="text-sm text-zinc-200">Únete para publicar proyectos, gestionar avances, fondos y proveedores con transparencia.</p>
                            
                        </div>
                    </div>
                </div>

                <div class="px-6 py-8 sm:px-10 sm:py-12 space-y-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-indigo-200">Crea tu cuenta</p>
                        <h1 class="mt-2 text-2xl font-bold text-white">Regístrate</h1>
                        <p class="text-sm text-zinc-400">Completa tus datos para comenzar.</p>
                    </div>

                    @if ($errors->any())
                        <div class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="passwordChecker()">
                        @csrf
                        <div class="space-y-1">
                            <label class="text-sm text-zinc-300" for="name">Nombre</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name"
                                   class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm text-zinc-300" for="email">Correo</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                                   class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm text-zinc-300" for="password">Contraseña</label>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                   x-model="password" @input="evaluateStrength()"
                                   class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                            <div class="w-full h-2 mt-2 rounded bg-zinc-800">
                                <div class="h-full rounded transition-all duration-300" :style="`width: ${progress}%; background-color: ${color}`"></div>
                            </div>
                            <p class="mt-1 text-sm font-semibold" :class="textColor" x-text="label"></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-sm text-zinc-300" for="password_confirmation">Confirmar contraseña</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                   class="w-full rounded-xl border border-white/10 bg-zinc-900/70 px-4 py-3 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400">
                        </div>
                        <div class="space-y-3">
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                                Crear cuenta
                            </button>
                            <a class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white hover:border-indigo-400/60" href="{{ route('login') }}">
                                ¿Ya tienes cuenta? Inicia sesión
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function passwordChecker() {
            return {
                password: '',
                progress: 0,
                label: 'Contraseña vacía',
                color: '#475569',
                textColor: 'text-zinc-300',
                evaluateStrength() {
                    let score = 0;
                    const pass = this.password || '';
                    if (pass.length >= 8) score++;
                    if (/[A-Z]/.test(pass)) score++;
                    if (/[0-9]/.test(pass)) score++;
                    if (/[^A-Za-z0-9]/.test(pass)) score++;

                    if (score <= 1) {
                        this.progress = 25;
                        this.label = 'Contraseña débil';
                        this.color = '#dc2626';
                        this.textColor = 'text-red-500';
                    } else if (score === 2) {
                        this.progress = 50;
                        this.label = 'Contraseña moderada';
                        this.color = '#d97706';
                        this.textColor = 'text-amber-500';
                    } else if (score === 3) {
                        this.progress = 75;
                        this.label = 'Contraseña buena';
                        this.color = '#16a34a';
                        this.textColor = 'text-emerald-500';
                    } else {
                        this.progress = 100;
                        this.label = 'Contraseña segura';
                        this.color = '#0ea5e9';
                        this.textColor = 'text-sky-500';
                    }
                }
            }
        }
    </script>
</x-guest-layout>
