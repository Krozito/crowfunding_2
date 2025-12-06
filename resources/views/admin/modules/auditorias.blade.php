<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Auditoría | CrowdUp Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-950 text-zinc-100 font-sans min-h-screen">
    <div class="relative isolate overflow-hidden bg-zinc-950">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-indigo-600/30 blur-2xl"></div>
        <div class="absolute right-0 top-24 h-72 w-72 rounded-full bg-fuchsia-500/25 blur-2xl"></div>
    </div>

    <header class="sticky top-0 z-30 border-b border-white/10 bg-zinc-950/80 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm text-zinc-300 hover:text-white">
                    ← Volver al dashboard
                </a>
                <h1 class="text-lg font-semibold text-white">Auditoría y cumplimiento</h1>
            </div>
            <div class="flex items-center gap-3 text-xs leading-tight">
                <span class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</span>
                <span class="text-zinc-400 uppercase tracking-wide">ADMIN</span>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-full px-0 pt-0 pb-6">
        <div class="grid gap-0 lg:grid-cols-[280px_1fr] lg:min-h-[calc(100vh-64px)] lg:overflow-hidden admin-shell">
            <aside class="lg:sticky lg:top-0 admin-sidebar">
                @include('admin.partials.modules', ['active' => 'auditorias'])
            </aside>

            <div class="space-y-8 lg:overflow-y-auto lg:h-full lg:pr-2 admin-scroll admin-main">
                <section class="rounded-3xl border border-white/10 bg-zinc-900/70 p-8 shadow-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-zinc-400">Compliance</p>
                    <h2 class="mt-2 text-2xl font-bold text-white">Flujos de auditoría</h2>
                    <p class="mt-2 text-sm text-zinc-400">
                        Espacio para revisar comprobantes, reportes sospechosos y aprobaciones de desembolsos (HU16-HU19, HU34).
                    </p>
                    <div class="mt-6 rounded-2xl border border-dashed border-white/15 bg-white/5 p-6 text-sm text-zinc-300">
                        Próximamente: bandeja de auditoría, colas de revisión y panel AML/KYC.
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>








