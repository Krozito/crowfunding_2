@php
    $accentKey = $active ?? 'dashboard';
    $accentPalette = [
        'dashboard' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'proyectos' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'recompensas' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'avances' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'fondos' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'proveedores' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'reportes' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
        'perfil' => ['from' => '#06b6d4', 'to' => '#6366f1', 'soft' => 'rgba(6,182,212,0.12)', 'ring' => 'rgba(6,182,212,0.55)'],
    ];
    $accent = $accentPalette[$accentKey] ?? $accentPalette['dashboard'];
@endphp
<style>
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
        max-width: 280px;
        background: linear-gradient(180deg, #0f0f14 0%, #0c0c12 100%);
        border-right: 1px solid rgba(255,255,255,0.06);
        box-shadow: 8px 0 24px rgba(0,0,0,0.35);
        transition: transform 200ms ease, opacity 200ms ease;
        position: sticky;
        top: 64px;
        height: calc(100vh - 64px);
        overflow-y: auto;
    }
    .admin-shell.collapsed .admin-sidebar {
        transform: translateX(-110%);
        opacity: 0;
        pointer-events: none;
    }
    .admin-toggle-floating {
        position: fixed;
        left: 12px;
        top: 76px;
        z-index: 60;
        display: none;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 0.85rem;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.08);
        background: rgba(15,15,20,0.9);
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        box-shadow: 0 12px 32px rgba(0,0,0,0.35);
        backdrop-filter: blur(10px);
    }
    .admin-sidebar-collapsed .admin-toggle-floating {
        display: inline-flex;
    }
    .admin-toggle-floating:hover {
        border-color: rgba(129,140,248,0.5);
        background: rgba(30,32,45,0.95);
    }
    @media (min-width: 1024px) {
        .admin-shell.collapsed {
            grid-template-columns: 0 1fr !important;
        }
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
    .admin-hero {
        background: linear-gradient(120deg, var(--admin-accent-from), var(--admin-accent-to));
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow: 0 24px 70px rgba(0,0,0,0.45), 0 0 0 1px rgba(255,255,255,0.05);
    }
    .admin-accent-card {
        position: relative;
        overflow: hidden;
        box-shadow: 0 -3px 0 0 var(--admin-accent-to) inset, 0 24px 70px rgba(0,0,0,0.45);
        background: rgba(255,255,255,0.02);
    }
    .admin-accent-card::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(circle at 10% 10%, color-mix(in srgb, var(--admin-accent-from) 35%, transparent) 0%, transparent 35%),
                    radial-gradient(circle at 80% 20%, color-mix(in srgb, var(--admin-accent-to) 38%, transparent) 0%, transparent 38%);
        opacity: 0.35;
    }
    .admin-accent-card > * {
        position: relative;
        z-index: 1;
    }
    .admin-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.65rem 1rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: transform 120ms ease, box-shadow 150ms ease, filter 150ms ease;
    }
    .admin-btn:active {
        transform: translateY(1px);
    }
    .admin-btn-primary {
        background: linear-gradient(135deg, var(--admin-accent-from), var(--admin-accent-to));
        color: #fff;
        border: 1px solid var(--admin-accent-ring);
        box-shadow: 0 12px 35px color-mix(in srgb, var(--admin-accent-to) 45%, transparent), 0 0 0 1px rgba(255,255,255,0.05);
    }
    .admin-btn-primary:hover {
        filter: brightness(1.05);
    }
    .admin-btn-ghost {
        color: #e4e4ed;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.03);
    }
    .admin-btn-ghost:hover {
        border-color: var(--admin-accent-ring);
        color: #fff;
    }
    .admin-main {
        height: calc(100vh - 64px);
        overflow-y: auto;
        padding-right: 0.5rem;
    }
    @media (min-width: 1024px) {
        .admin-main {
            padding-right: 0.75rem;
        }
    }
</style>
@once
<script>
document.addEventListener('click', (event) => {
    const btn = event.target.closest('[data-admin-toggle]');
    if (!btn) return;
    const shell = btn.closest('.admin-shell') || document.querySelector('.admin-shell');
    if (shell) {
        shell.classList.toggle('collapsed');
        document.body.classList.toggle('admin-sidebar-collapsed', shell.classList.contains('collapsed'));
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const shell = document.querySelector('.admin-shell');
    if (!shell) return;
    if (!document.querySelector('.admin-toggle-floating')) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.setAttribute('data-admin-toggle', 'true');
        btn.className = 'admin-toggle-floating';
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg><span>Menu</span>`;
        document.body.appendChild(btn);
    }
    document.body.classList.toggle('admin-sidebar-collapsed', shell.classList.contains('collapsed'));
});
</script>
@endonce
<div class="h-full min-h-screen lg:sticky lg:top-0 lg:left-0 flex flex-col bg-[#0f0f14] text-slate-100">
    <div class="flex items-center justify-between gap-3 px-4 py-4 border-b border-white/5">
        <div class="leading-tight">
            <p class="text-[11px] font-semibold uppercase tracking-[0.32em] text-slate-500">Navegacion</p>
            <p class="text-sm font-semibold text-white">Panel creador</p>
        </div>
        <button type="button" data-admin-toggle class="inline-flex items-center gap-2 rounded-lg border border-white/10 bg-white/5 px-3 py-2 text-xs font-semibold text-white hover:border-indigo-400/60 hover:bg-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            Menu
        </button>
    </div>
    @php
        $active = $accentKey;
        $linkClass = 'admin-link';
        $activeClass = 'active';
    @endphp
    <nav class="flex-1 px-3 py-3 space-y-1">
        <a href="{{ route('creador.dashboard') }}" class="{{ $linkClass }} {{ $active === 'dashboard' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-400"></span>
                Inicio
            </span>
            <span class="text-xs text-indigo-100/90">Panel</span>
        </a>
        <a href="{{ route('creador.proyectos') }}" class="{{ $linkClass }} {{ $active === 'proyectos' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-300"></span>
                Proyectos
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
        <a href="{{ route('creador.recompensas') }}" class="{{ $linkClass }} {{ $active === 'recompensas' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-200"></span>
                Recompensas
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
        <a href="{{ route('creador.avances') }}" class="{{ $linkClass }} {{ $active === 'avances' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-200"></span>
                Avances
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
        <a href="{{ route('creador.fondos') }}" class="{{ $linkClass }} {{ $active === 'fondos' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-100"></span>
                Fondos
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
        <a href="{{ route('creador.proveedores') }}" class="{{ $linkClass }} {{ $active === 'proveedores' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-100"></span>
                Proveedores
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
        <a href="{{ route('creador.reportes') }}" class="{{ $linkClass }} {{ $active === 'reportes' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-100"></span>
                Reporte de pagos
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
        <a href="{{ route('creador.perfil') }}" class="{{ $linkClass }} {{ $active === 'perfil' ? $activeClass : '' }}">
            <span class="flex items-center gap-2">
                <span class="inline-flex h-2 w-2 rounded-full bg-indigo-200"></span>
                Perfil &amp; verificacion
            </span>
            <span class="text-xs text-indigo-100/90">Ir</span>
        </a>
    </nav>
    <div class="border-t border-white/5 px-4 py-4 text-xs leading-tight text-slate-400">
        <p class="font-semibold text-white">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</p>
        <p class="uppercase tracking-[0.3em] text-[11px] text-indigo-200">CREADOR</p>
    </div>
</div>
