@extends('creator.layouts.panel')

@section('title', 'Solicitar verificacion')
@section('active', 'perfil')
@section('back_url', route('creador.perfil'))
@section('back_label', 'Volver a perfil')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 space-y-6">
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

        <section class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-600/25 via-zinc-900/70 to-zinc-900/70 p-8 shadow-2xl ring-1 ring-indigo-500/10 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Verificacion</p>
                    <h2 class="text-2xl font-bold text-white">Envio de documentos KYC</h2>
                    <p class="text-sm text-zinc-400">Sube documento frontal y reverso, y una selfie opcional. Administracion revisara tu solicitud.</p>
                </div>
                <span class="rounded-full bg-white/5 px-3 py-1 text-[11px] font-semibold text-zinc-200">Solo administracion aprueba</span>
            </div>

            @if ($pendiente)
                <div class="rounded-xl border border-amber-400/30 bg-amber-500/10 px-4 py-3 text-sm text-amber-100">
                    Ya tienes una solicitud pendiente. Te avisaremos cuando cambie de estado.
                </div>
            @else
                <form method="POST" action="{{ route('creador.perfil.verificacion') }}" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="text-sm text-zinc-300">Documento frontal *</label>
                            <input type="file" name="documento_frontal" required class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-500/20 file:px-4 file:py-2 file:text-emerald-100 hover:file:bg-emerald-500/30">
                        </div>
                        <div>
                            <label class="text-sm text-zinc-300">Documento reverso *</label>
                            <input type="file" name="documento_reverso" required class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-500/20 file:px-4 file:py-2 file:text-emerald-100 hover:file:bg-emerald-500/30">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-zinc-300">Selfie (opcional)</label>
                        <input type="file" name="selfie" class="mt-1 block w-full text-sm text-white file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-500/20 file:px-4 file:py-2 file:text-emerald-100 hover:file:bg-emerald-500/30">
                    </div>
                    <div>
                        <label class="text-sm text-zinc-300">Nota para administracion</label>
                        <textarea name="nota" rows="2" class="mt-1 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white focus:border-indigo-400 focus:ring-indigo-400" placeholder="Datos adicionales, pais de emision, referencia...">{{ old('nota') }}</textarea>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-500">
                            Enviar solicitud a administracion
                        </button>
                    </div>
                </form>
            @endif
        </section>
    </div>
@endsection
