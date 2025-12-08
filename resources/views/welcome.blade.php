<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>CrowdUp — Financia ideas que merecen existir</title>
  <meta name="description" content="Lanza tu campaña de crowdfunding en minutos. Reúne a tu comunidad, recauda fondos y haz realidad tu proyecto con CrowdUp." />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

  @vite(['resources/css/app.css'])
  <style>
    /* Helpers para el carrusel nativo */
    .snap-x{scroll-snap-type:x mandatory}
    .snap-start{scroll-snap-align:start}
    .carousel::-webkit-scrollbar{height:8px}
    .carousel::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:9999px}
    .glass{backdrop-filter:saturate(140%) blur(10px);background:rgba(255,255,255,.08)}
  </style>
</head>

<body class="font-sans antialiased bg-white text-gray-800 dark:bg-zinc-950 dark:text-zinc-100 selection:bg-indigo-500 selection:text-white">

  <!-- NAV -->
  <header class="sticky top-0 z-50 bg-white/70 dark:bg-zinc-950/70 backdrop-blur border-b border-zinc-100/70 dark:border-zinc-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <a href="{{ url('/') }}" class="flex items-center gap-2">
        <img src="/images/brand/mark.png" alt="CrowdUp" class="h-7 w-7" />
        <span class="font-extrabold tracking-tight text-xl">Crowd<span class="text-indigo-600">Up</span></span>
      </a>

      <nav class="hidden md:flex items-center gap-8 text-sm">
        <a href="#como-funciona" class="hover:text-indigo-600">Cómo funciona</a>
        <a href="#campanas" class="hover:text-indigo-600">Campañas</a>
        <a href="#testimonios" class="hover:text-indigo-600">Historias</a>
        <a href="#faq" class="hover:text-indigo-600">FAQ</a>
      </nav>

      <div class="flex items-center gap-3">
        @if (Route::has('login'))
          @auth
            <a href="{{ url('/dashboard') }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">Panel</a>
          @else
            <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">Entrar</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm">Crear cuenta</a>
            @endif
          @endauth
        @endif
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="relative overflow-hidden">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 -z-10">
      <img src="/images/hero/texture.jpg" alt="" class="w-full h-full object-cover opacity-90 dark:opacity-90" />
      <div class="absolute inset-0 bg-gradient-to-b from-white via-white/70 to-white dark:from-zinc-950 dark:via-zinc-950/70 dark:to-zinc-950"></div>
      <div class="absolute -top-32 -right-24 w-[36rem] h-[36rem] rounded-full blur-3xl opacity-30" style="background: radial-gradient(closest-side, #6366f1, transparent 70%);"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-24 grid lg:grid-cols-2 gap-12 items-center">
      <div>
        <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full dark:text-indigo-300 dark:bg-indigo-900/30">Lanza en minutos</span>
        <h1 class="mt-5 text-4xl sm:text-5xl font-extrabold leading-tight">
          Financia ideas que <span class="text-indigo-600">merecen existir</span>
        </h1>
        <p class="mt-4 text-lg text-zinc-600 dark:text-zinc-300">
          Crea tu campaña, comparte con tu comunidad y recauda fondos de forma segura. Sin fricción, sin trucos.
        </p>

        <div class="mt-6 flex flex-wrap items-center gap-3">
          <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/20">
            Empieza tu campaña
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0-5 5m5-5H6"/></svg>
          </a>
          <a href="#como-funciona" class="inline-flex items-center px-5 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800">
            Ver cómo funciona
          </a>
          <p class="w-full text-sm text-zinc-500 dark:text-zinc-400"><em>Sin comisiones ocultas. Puedes cancelar cuando quieras.</em></p>
        </div>

        <div class="mt-8 flex items-center gap-6">
          <div class="flex -space-x-2">
            <img src="/images/avatars/a1.jpg" class="h-9 w-9 rounded-full ring-2 ring-white dark:ring-zinc-900 object-cover" alt="">
            <img src="/images/avatars/a2.jpg" class="h-9 w-9 rounded-full ring-2 ring-white dark:ring-zinc-900 object-cover" alt="">
            <img src="/images/avatars/a3.jpg" class="h-9 w-9 rounded-full ring-2 ring-white dark:ring-zinc-900 object-cover" alt="">
          </div>
          <p class="text-sm text-zinc-600 dark:text-zinc-300">
            <strong>+120k</strong> donantes activos este mes
          </p>
        </div>
      </div>

      <div class="relative">
        <div class="rounded-2xl overflow-hidden shadow-2xl ring-1 ring-black/5 dark:ring-white/10">
          <!-- Reemplaza la imagen o coloca un video poster -->
          <img src="/images/hero/cover.jpg" alt="Campañas exitosas" class="w-full h-full object-cover">
        </div>
        <!-- Tarjeta flotante -->
        <div class="glass absolute -bottom-6 -left-6 rounded-xl p-4 shadow-lg border border-white/10">
          <p class="text-xs uppercase tracking-wider text-zinc-300">Recaudado hoy</p>
          <p class="text-2xl font-bold">US$ <span data-counter-to="184230">0</span></p>
        </div>
      </div>
    </div>
  </section>

  <!-- TRUST BAR -->
  <section class="border-y border-zinc-100 dark:border-zinc-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
      <div><p class="text-2xl font-extrabold" data-counter-to="4.9">0</p><p class="text-sm text-zinc-500">Calificación promedio</p></div>
      <div><p class="text-2xl font-extrabold" data-counter-to="3200">0</p><p class="text-sm text-zinc-500">Campañas activas</p></div>
      <div><p class="text-2xl font-extrabold" data-counter-to="98">0</p><p class="text-sm text-zinc-500">% de entregas exitosas</p></div>
      <div><p class="text-2xl font-extrabold" data-counter-to="65">0</p><p class="text-sm text-zinc-500">Países con donantes</p></div>
    </div>
  </section>

  <!-- CÓMO FUNCIONA -->
  <section id="como-funciona" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="text-3xl sm:text-4xl font-extrabold">Lanza en 3 pasos</h2>
      <p class="mt-3 text-zinc-600 dark:text-zinc-300">Creamos la ruta más corta entre tu idea y sus primeros donantes.</p>
    </div>

    <div class="mt-10 grid md:grid-cols-3 gap-6">
      <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 hover:shadow-lg transition">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white font-bold">1</span>
        <h3 class="mt-4 font-semibold text-lg">Crea tu campaña</h3>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Sube imágenes, define meta y recompensas. <em></em> “Puedes editarla después”.</p>
      </div>
      <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 hover:shadow-lg transition">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white font-bold">2</span>
        <h3 class="mt-4 font-semibold text-lg">Comparte con tu comunidad</h3>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Link único, botones sociales y actualizaciones automáticas para mantener el interés.</p>
      </div>
      <div class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 hover:shadow-lg transition">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-white font-bold">3</span>
        <h3 class="mt-4 font-semibold text-lg">Recibe fondos seguro</h3>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Pagos cifrados, verificación KYC y retiro a tu cuenta bancaria.</p>
      </div>
    </div>
  </section>

  <!-- CAMPAÑAS DESTACADAS (Carrusel) -->
  <section id="campanas" class="bg-zinc-50 dark:bg-zinc-900/30 border-y border-zinc-100 dark:border-zinc-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
      <div class="flex items-end justify-between">
        <div>
          <h2 class="text-3xl font-extrabold">Campañas destacadas</h2>
          <p class="mt-2 text-zinc-600 dark:text-zinc-300">Historias que ya están moviendo a miles.</p>
        </div>
        <div class="flex items-center gap-2">
          <button class="carousel-prev inline-flex h-10 w-10 items-center justify-center rounded-full border border-zinc-300 dark:border-zinc-700 hover:bg-white dark:hover:bg-zinc-800" aria-label="Anterior">‹</button>
          <button class="carousel-next inline-flex h-10 w-10 items-center justify-center rounded-full border border-zinc-300 dark:border-zinc-700 hover:bg-white dark:hover:bg-zinc-800" aria-label="Siguiente">›</button>
        </div>
      </div>

      <div class="mt-6 relative">
        <div class="carousel flex gap-6 overflow-x-auto snap-x scroll-smooth pb-2" tabindex="0">
          <!-- CARD 1 -->
          <article class="min-w-[85%] sm:min-w-[55%] lg:min-w-[32%] snap-start bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800">
            <img src="/images/campaigns/c1.jpg" alt="Campaña 1" class="h-52 w-full object-cover">
            <div class="p-6">
              <h3 class="font-semibold text-lg">Purificador solar para escuelas rurales</h3>
              <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Llevamos agua segura a 10 comunidades.</p>
              <div class="mt-4">
                <div class="h-2 w-full rounded-full bg-zinc-200 dark:bg-zinc-800">
                  <div class="h-2 rounded-full bg-indigo-600" style="width:72%"></div>
                </div>
                <div class="mt-2 flex justify-between text-sm">
                  <span>72% financiado</span><span>US$ 18,200</span>
                </div>
              </div>
              <a href="{{ route('login') }}" class="mt-4 inline-flex px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Apoyar</a>
            </div>
          </article>

          <!-- CARD 2 -->
          <article class="min-w-[85%] sm:min-w-[55%] lg:min-w-[32%] snap-start bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800">
            <img src="/images/campaigns/c2.jpg" alt="Campaña 2" class="h-52 w-full object-cover">
            <div class="p-6">
              <h3 class="font-semibold text-lg">Documental: Voces de la Amazonía</h3>
              <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Historias que necesitan ser escuchadas.</p>
              <div class="mt-4">
                <div class="h-2 w-full rounded-full bg-zinc-200 dark:bg-zinc-800">
                  <div class="h-2 rounded-full bg-indigo-600" style="width:45%"></div>
                </div>
                <div class="mt-2 flex justify-between text-sm">
                  <span>45% financiado</span><span>US$ 9,050</span>
                </div>
              </div>
              <a href="{{ route('login') }}" class="mt-4 inline-flex px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Apoyar</a>
            </div>
          </article>

          <!-- CARD 3 -->
          <article class="min-w-[85%] sm:min-w-[55%] lg:min-w-[32%] snap-start bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-200 dark:border-zinc-800">
            <img src="/images/campaigns/c3.jpg" alt="Campaña 3" class="h-52 w-full object-cover">
            <div class="p-6">
              <h3 class="font-semibold text-lg">Kit de prótesis impresas en 3D</h3>
              <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Acceso asequible para más personas.</p>
              <div class="mt-4">
                <div class="h-2 w-full rounded-full bg-zinc-200 dark:bg-zinc-800">
                  <div class="h-2 rounded-full bg-indigo-600" style="width:88%"></div>
                </div>
                <div class="mt-2 flex justify-between text-sm">
                  <span>88% financiado</span><span>US$ 26,740</span>
                </div>
              </div>
              <a href="{{ route('login') }}" class="mt-4 inline-flex px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Apoyar</a>
            </div>
          </article>
        </div>
      </div>

      <p class="mt-6 text-center text-sm text-zinc-600 dark:text-zinc-300"><em>¿Tienes una idea? <a href="{{ route('register') }}" class="underline decoration-indigo-600 underline-offset-4">Crea tu campaña gratis</a>.</em></p>
    </div>
  </section>

  <!-- TESTIMONIOS (Carrusel simple) -->
  <section id="testimonios" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-2xl mx-auto">
      <h2 class="text-3xl sm:text-4xl font-extrabold">Historias que inspiran</h2>
      <p class="mt-3 text-zinc-600 dark:text-zinc-300">Personas como tú ya lo están logrando.</p>
    </div>

    <div class="mt-10 grid lg:grid-cols-3 gap-6">
      <figure class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800">
        <blockquote class="text-lg">“En 21 días financiamos nuestra primera producción. La comunidad respondió desde el día 1.”</blockquote>
        <figcaption class="mt-4 flex items-center gap-3">
          <img src="/images/avatars/t1.jpg" class="h-10 w-10 rounded-full object-cover" alt="">
          <div><p class="font-semibold">María S.</p><p class="text-sm text-zinc-500">Directora, Studio Andino</p></div>
        </figcaption>
      </figure>
      <figure class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800">
        <blockquote class="text-lg">“El panel de métricas nos ayudó a ajustar el mensaje y duplicar conversiones.”</blockquote>
        <figcaption class="mt-4 flex items-center gap-3">
          <img src="/images/avatars/t2.jpg" class="h-10 w-10 rounded-full object-cover" alt="">
          <div><p class="font-semibold">Luis P.</p><p class="text-sm text-zinc-500">Creador de producto</p></div>
        </figcaption>
      </figure>
      <figure class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800">
        <blockquote class="text-lg">“Pude agradecer a cada donante con recompensas automáticas. ¡Súper simple!”</blockquote>
        <figcaption class="mt-4 flex items-center gap-3">
          <img src="/images/avatars/t3.jpg" class="h-10 w-10 rounded-full object-cover" alt="">
          <div><p class="font-semibold">Daniela R.</p><p class="text-sm text-zinc-500">ONG Agua Viva</p></div>
        </figcaption>
      </figure>
    </div>
  </section>

  <!-- CTA SECCIONAL -->
  <section class="relative overflow-hidden">
    <div class="absolute inset-0 -z-10">
      <img src="/images/cta/bg.jpg" class="w-full h-full object-cover opacity-20 dark:opacity-25" alt="">
      <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-fuchsia-600/20"></div>
    </div>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 text-center">
      <h2 class="text-3xl sm:text-4xl font-extrabold">Tu proyecto merece una oportunidad</h2>
      <p class="mt-3 text-lg text-zinc-700 dark:text-zinc-200">Publica hoy. Valida en horas, no en meses.</p>
      <div class="mt-6">
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/20">Crear campaña gratis</a>
      </div>
      <p class="mt-2 text-xs text-zinc-500"><em>No necesitas tarjeta para comenzar.</em></p>
    </div>
  </section>

    <!-- FAQ -->
  <section id="faq" class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-16">
    <h2 class="text-3xl font-extrabold text-center">Preguntas frecuentes</h2>
    <div class="mt-8 divide-y divide-zinc-200 dark:divide-zinc-800">
      @php
        $faqs = [
          [
            'q' => '¿Qué tipo de proyectos puedo publicar?',
            'a' => 'CrowdUp está pensado para proyectos creativos, sociales, educativos y de producto con impacto positivo: documentales, causas comunitarias, tecnología con propósito, ONGs, etc. Sólo pedimos que tengas un objetivo claro, un presupuesto transparente y que respetes nuestras políticas (sin armas, apuestas, esquemas financieros ni contenido ilegal).',
          ],
          [
            'q' => '¿Cómo pueden aportar los colaboradores a un proyecto?',
            'a' => 'Cada proyecto tiene una página pública con su descripción, metas y niveles de recompensa. Los colaboradores eligen el monto que quieren aportar, seleccionan una recompensa (si aplica) y pagan con los métodos de pago habilitados en su país. El aporte queda registrado al instante y el colaborador recibe un comprobante por correo.',
          ],
          [
            'q' => '¿Qué pasa con el dinero que se recauda?',
            'a' => 'Los fondos no van directo al creador. Se mantienen en custodia (escrow) dentro de la plataforma y se liberan por etapas, según los hitos financieros del proyecto. Para cada desembolso, el creador debe justificar el uso con facturas y comprobantes, que pueden ser revisados por auditores y vistos por los colaboradores.',
          ],
          [
            'q' => '¿Cómo se garantiza la transparencia del uso de fondos?',
            'a' => 'Cada pago a proveedores queda registrado con monto, fecha, concepto, proveedor e incluso documentos de respaldo. Los colaboradores pueden ver el flujo completo: aportes, desembolsos y gastos. Además, existe un módulo de auditoría y un sistema de reportes sospechosos para que cualquier irregularidad sea revisada.',
          ],
          [
            'q' => '¿Los colaboradores reciben ganancias o intereses por aportar?',
            'a' => 'No. CrowdUp no es una plataforma de inversión ni de préstamos. Los colaboradores no reciben rendimientos financieros ni intereses; apoyan proyectos a cambio de recompensas simbólicas, productos, experiencias o simplemente por el impacto social que generan.',
          ],
          [
            'q' => '¿Qué necesito para lanzar mi proyecto?',
            'a' => 'Necesitas una idea clara, una meta de recaudación realista, un plan de uso de fondos y material básico para contar tu historia (texto, imágenes o video). Según el país y el monto que quieras recaudar, podemos solicitar verificación de identidad (KYC) y algunos datos adicionales para cumplir con normativa y cuidar a la comunidad.',
          ],
        ];
      @endphp

      @foreach($faqs as $i => $f)
        <details class="group py-5">
          <summary class="flex cursor-pointer items-center justify-between">
            <h3 class="font-medium">{{ $f['q'] }}</h3>
            <span class="ml-4 inline-flex h-6 w-6 items-center justify-center rounded-full border border-zinc-300 dark:border-zinc-700 text-xs group-open:rotate-45 transition">+</span>
          </summary>
          <p class="mt-3 text-zinc-600 dark:text-zinc-300">{{ $f['a'] }}</p>
        </details>
      @endforeach
    </div>
  </section>


  <!-- FOOTER -->
  <footer class="border-t border-zinc-100 dark:border-zinc-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
      <div>
        <a href="{{ url('/') }}" class="flex items-center gap-2">
          <img src="/images/brand/mark.svg" class="h-6 w-6" alt="">
          <span class="font-extrabold">Crowd<span class="text-indigo-600">Up</span></span>
        </a>
        <p class="mt-3 text-zinc-600 dark:text-zinc-300">La plataforma latina para financiar ideas con impacto.</p>
      </div>
      <div>
        <p class="font-semibold">Producto</p>
        <ul class="mt-3 space-y-2">
          <li><a href="#como-funciona" class="hover:text-indigo-600">Cómo funciona</a></li>
          <li><a href="#campanas" class="hover:text-indigo-600">Campañas</a></li>
          <li><a href="#" class="hover:text-indigo-600">Precios</a></li>
        </ul>
      </div>
      <div>
        <p class="font-semibold">Compañía</p>
        <ul class="mt-3 space-y-2">
          <li><a href="#" class="hover:text-indigo-600">Nosotros</a></li>
          <li><a href="#" class="hover:text-indigo-600">Prensa</a></li>
          <li><a href="#" class="hover:text-indigo-600">Contacto</a></li>
        </ul>
      </div>
      <div>
        <p class="font-semibold">Legal</p>
        <ul class="mt-3 space-y-2">
          <li><a href="#" class="hover:text-indigo-600">Términos</a></li>
          <li><a href="#" class="hover:text-indigo-600">Privacidad</a></li>
          <li><a href="#" class="hover:text-indigo-600">Cookies</a></li>
        </ul>
      </div>
    </div>
    <div class="text-center text-xs text-zinc-500 pb-8">
      Laravel v{{ Illuminate\Foundation\Application::VERSION }} — PHP v{{ PHP_VERSION }}
    </div>
  </footer>

  <!-- JS ligero para contadores y carrusel -->
  <script>
    // Contadores animados
    const easeOut = t => 1 - Math.pow(1 - t, 4);
    document.querySelectorAll('[data-counter-to]').forEach(el => {
      const target = parseFloat(el.dataset.counterTo);
      const isInt = Number.isInteger(target);
      const dur = 1200, start = performance.now();
      const step = now => {
        const p = Math.min(1, (now - start)/dur);
        const val = easeOut(p) * target;
        el.textContent = isInt ? Math.round(val).toLocaleString() : val.toFixed(1);
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    });

    // Carrusel básico (scroll-snap + botones)
    const carousel = document.querySelector('.carousel');
    const next = document.querySelector('.carousel-next');
    const prev = document.querySelector('.carousel-prev');
    const cardWidth = () => carousel.querySelector('article').getBoundingClientRect().width + 24; // gap approx

    next?.addEventListener('click', () => { carousel.scrollBy({left: cardWidth(), behavior: 'smooth'}); });
    prev?.addEventListener('click', () => { carousel.scrollBy({left: -cardWidth(), behavior: 'smooth'}); });

    // Drag to scroll
    let isDown = false, startX, scrollLeft;
    carousel.addEventListener('mousedown', (e)=>{isDown=true;startX=e.pageX-carousel.offsetLeft;scrollLeft=carousel.scrollLeft;});
    carousel.addEventListener('mouseleave', ()=>isDown=false);
    carousel.addEventListener('mouseup', ()=>isDown=false);
    carousel.addEventListener('mousemove', (e)=>{
      if(!isDown) return;
      e.preventDefault();
      const x = e.pageX - carousel.offsetLeft;
      const walk = (x - startX) * 1.2;
      carousel.scrollLeft = scrollLeft - walk;
    });
  </script>
</body>
</html>
