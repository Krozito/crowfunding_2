@php
    $accentKey = $active ?? 'dashboard';
    $accentPalette = [
        'dashboard' => ['from' => '#6366f1', 'to' => '#22d3ee', 'soft' => 'rgba(99,102,241,0.12)', 'ring' => 'rgba(99,102,241,0.55)'],
        'perfil' => ['from' => '#22c55e', 'to' => '#14b8a6', 'soft' => 'rgba(34,197,94,0.12)', 'ring' => 'rgba(20,184,166,0.55)'],
    ];
    $accent = $accentPalette[$accentKey] ?? $accentPalette['dashboard'];
@endphp
<style>
    .admin-shell {
        --admin-accent-from: {{ $accent['from'] }};
        --admin-accent-to: {{ $accent['to'] }};
        --admin-accent-soft: {{ $accent['soft'] }};
        --admin-accent-ring: {{ $accent['ring'] }};
        background: #0b0c12;
        min-height: calc(100vh - 64px);
        overflow: hidden;
    }
    .admin-shell .admin-sidebar {
        width: 100%;
        max-width: 260px;
        background: linear-gradient(180deg, #0f0f14 0%, #0c0c12 100%);
        border-right: 1px solid rgba(255,255,255,0.06);
        box-shadow: 8px 0 24px rgba(0,0,0,0.35);
        position: sticky;
        top: 64px;
        height: calc(100vh - 64px);
        overflow-y: auto;
    }
    .admin-link {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.75rem 0.9rem;
        border-radius: 14px;
        color: #e4e4ed;
        font-size: 0.92rem;
        transition: background 120ms ease, color 120ms ease, border 120ms ease;
    }
    .admin-link:hover {
        background: rgba(255,255,255,0.06);
        color: #fff;
    }
    .admin-link.active {
        background: var(--admin-accent-soft);
        color: #fff;
        border: 1px solid var(--admin-accent-ring);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.04), 0 0 0 1px rgba(255,255,255,0.02);
    }
    .admin-scroll {
        scrollbar-color: #0f1017 #0a0b10;
    }
    .admin-scroll::-webkit-scrollbar {
        width: 10px;
    }
    .admin-scroll::-webkit-scrollbar-track {
        background: #0a0b10;
    }
    .admin-scroll::-webkit-scrollbar-thumb {
        background: #202230;
        border-radius: 10px;
        border: 2px solid #0a0b10;
    }
    .admin-scroll::-webkit-scrollbar-thumb:hover {
        background: #2f3244;
    }
    .admin-main::-webkit-scrollbar,
    .admin-sidebar::-webkit-scrollbar {
        width: 10px;
    }
    .admin-main::-webkit-scrollbar-track,
    .admin-sidebar::-webkit-scrollbar-track {
        background: #0b0c12;
    }
    .admin-main::-webkit-scrollbar-thumb,
    .admin-sidebar::-webkit-scrollbar-thumb {
        background: #202230;
        border-radius: 10px;
        border: 2px solid #0b0c12;
    }
    .admin-main::-webkit-scrollbar-thumb:hover,
    .admin-sidebar::-webkit-scrollbar-thumb:hover {
        background: #2f3244;
    }
    .admin-main {
        height: calc(100vh - 64px);
        overflow-y: auto;
        padding-right: 0.5rem;
    }
</style>
<div class="h-full min-h-screen lg:sticky lg:top-0 lg:left-0 flex flex-col bg-[#0f0f14] text-slate-100">
    <div class="flex items-center justify-between gap-3 px-4 py-4 border-b border-white/5">
        <div class="leading-tight">
            <p class="text-[11px] font-semibold uppercase tracking-[0.32em] text-slate-500">Navegación</p>
            <p class="text-sm font-semibold text-white">Panel colaborador</p>
        </div>
    </div>
    @php
        $linkClass = 'admin-link';
        $activeClass = 'active';
    @endphp
    <nav class="flex-1 px-3 py-3 space-y-1">
        <a href="{{ route('colaborador.dashboard') }}" class="{{ $linkClass }} {{ $active === 'dashboard' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-400"></span>
                Inicio
            </span>
            <span class="text-xs text-indigo-100/90">Inicio</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ $linkClass }} {{ $active === 'perfil' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                Mi perfil
            </span>
            <span class="text-xs text-indigo-100/90">Editar</span>
        </a>
    </nav>
    <div class="border-t border-white/5 px-4 py-4 text-xs leading-tight text-slate-400">
        <p class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>
        <p class="uppercase tracking-[0.3em] text-[11px] text-indigo-200">COLABORADOR</p>
    </div>
</div>
