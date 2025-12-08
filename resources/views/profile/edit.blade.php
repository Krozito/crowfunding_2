@extends('colaborador.layouts.panel')

@section('title', 'Mi perfil')
@section('active', 'perfil')
@section('back_url', route('colaborador.dashboard'))
@section('back_label', 'Volver al panel')

@section('content')
<div class="px-4 pt-6 pb-10 lg:px-8 space-y-6">
    <header class="max-w-4xl mx-auto space-y-2">
        <p class="text-xs uppercase tracking-[0.3em] text-zinc-500">Perfil</p>
        <h1 class="text-2xl font-bold text-white">Actualiza tu información</h1>
        <p class="text-sm text-zinc-400">Modifica tus datos de contacto y credenciales. Los cambios clave se reflejarán en el panel y notificaciones.</p>
    </header>

    @if (session('status') === 'profile-updated')
        <div class="max-w-4xl mx-auto rounded-2xl border border-emerald-400/40 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-100">
            Datos actualizados correctamente.
        </div>
    @endif

    @if (session('status') === 'verification-link-sent')
        <div class="max-w-4xl mx-auto rounded-2xl border border-sky-400/40 bg-sky-500/10 px-4 py-3 text-sm text-sky-100">
            Te enviamos un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <section class="max-w-4xl mx-auto rounded-3xl border border-white/15 bg-[#030712] shadow-[0_24px_60px_rgba(0,0,0,0.55)] p-5 space-y-4 relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-1 bg-sky-500/70"></div>
        <header class="space-y-1">
            <h2 class="text-lg font-semibold text-white">Datos personales</h2>
            <p class="text-sm text-zinc-400">Actualiza tu nombre y correo. Si cambias el correo deberás verificarlo nuevamente.</p>
        </header>

        <form method="post" action="{{ route('profile.update') }}" class="grid gap-4 md:grid-cols-2" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="md:col-span-2 flex flex-col items-center gap-3 text-center">
                @if (!empty($user->foto_perfil))
                    <img src="{{ asset('storage/'.$user->foto_perfil) }}" alt="Foto de perfil" class="h-20 w-20 rounded-full object-cover border border-white/20 shadow-lg">
                @else
                    <div class="h-20 w-20 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-xs text-zinc-500">
                        Sin foto
                    </div>
                @endif
                <label for="foto_perfil" class="inline-flex items-center gap-2 rounded-xl border-2 border-dashed border-white/20 bg-white/5 px-4 py-2 text-sm text-zinc-200 hover:border-sky-400/70 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span id="foto-name">Sube una imagen (JPG, PNG, WEBP, máx. 4MB)</span>
                    <input id="foto_perfil" name="foto_perfil" type="file" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                </label>
                @error('foto_perfil')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 md:col-span-1">
                <label for="name" class="text-sm text-zinc-200">Nombre</label>
                <input id="name" name="name" type="text" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 md:col-span-1">
                <label for="email" class="text-sm text-zinc-200">Correo electrónico</label>
                <input id="email" name="email" type="email" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 space-y-1">
                        <p class="text-xs text-amber-200">Tu correo no está verificado.</p>
                        <button form="send-verification" class="text-xs font-semibold text-sky-200 underline hover:text-sky-100">
                            Reenviar enlace de verificación
                        </button>
                    </div>
                @endif
            </div>

            <div class="space-y-2 md:col-span-1">
                <label for="profesion" class="text-sm text-zinc-200">Profesión</label>
                <input id="profesion" name="profesion" type="text" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" value="{{ old('profesion', $user->profesion) }}" autocomplete="organization-title">
                @error('profesion')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2 md:col-span-1">
                <label for="experiencia" class="text-sm text-zinc-200">Experiencia (resumen)</label>
                <input id="experiencia" name="experiencia" type="text" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" value="{{ old('experiencia', $user->experiencia) }}" autocomplete="on">
                @error('experiencia')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y
            <div class="space-y-2 md:col-span-2">
                <label for="biografia" class="text-sm text-zinc-200">Biograf?a</label>
                <textarea id="biografia" name="biografia" rows="4" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500">{{ old('biografia', $user->biografia) }}</textarea>
                @error('biografia')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>


            <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                <a href="{{ route('colaborador.dashboard') }}" class="text-sm text-gray-400 hover:text-white">Cancelar</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
                    Guardar cambios
                </button>
            </div>
        </form>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>
    </section>

    <section class="max-w-4xl mx-auto rounded-3xl border border-white/15 bg-[#030712] shadow-[0_24px_60px_rgba(0,0,0,0.55)] p-5 space-y-4">
        <header class="space-y-1">
            <h2 class="text-lg font-semibold text-white">Cambiar contraseña</h2>
            <p class="text-sm text-zinc-400">Usa una contraseña segura que no reutilices en otros sitios.</p>
        </header>

        <form method="post" action="{{ route('password.update') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('put')

            <div class="space-y-2 md:col-span-2">
                <label for="current_password" class="text-sm text-zinc-200">Contraseña actual</label>
                <input id="current_password" name="current_password" type="password" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" autocomplete="current-password">
                @error('current_password')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="text-sm text-zinc-200">Nueva contraseña</label>
                <input id="password" name="password" type="password" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" autocomplete="new-password">
                @error('password')
                    <p class="text-xs text-red-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="text-sm text-zinc-200">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-sky-500 focus:ring-sky-500" autocomplete="new-password">
            </div>

            <div class="md:col-span-2 flex justify-end gap-2 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
                    Actualizar contraseña
                </button>
            </div>
        </form>
    </section>
</div>
@endsection
