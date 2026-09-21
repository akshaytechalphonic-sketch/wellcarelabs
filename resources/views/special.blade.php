{{-- resources/views/special.blade.php --}}

@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Wellcare Labs Special Health Checkup Packages – Diagnostic Offers & Bundles')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Discover exclusive special health checkup and diagnostic packages at Wellcare Labs, featuring comprehensive test bundles designed for preventive screening, personalized care, and value-driven health insights.')

@section('seo')
  @if(isset($page) && !empty($page->meta_tags))
    {!! $page->meta_tags !!}
  @endif
@endsection


@section('content')

  <section id="packages-special" class="py-5" style="
      max-width:100%;
      margin:auto;
      background:#f0f4f8;
      box-shadow:
        inset 0 10px 25px -10px rgba(0,0,0,0.06),
        inset 0 -10px 25px -10px rgba(0,0,0,0.06);
      border-bottom:1px solid rgba(0,0,0,0.05);
    ">

    <div class="text-center mb-5 px-3 px-md-5" style="max-width:800px;margin:0 auto;">
      <h1 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
        Wellcare Exclusive <span style="color:#0d6efd;">Special Packages</span>
      </h1>
      <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                  background:linear-gradient(90deg,#0047ff,#00ccff);"></div>
      <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
        Doctor-Designed. Patient-Focused. Budget-Friendly. Special Packages. </p>
      </p>
    </div>

    @if($packages->count())
      <div class="container-fluid px-3 px-md-5">
        {{-- Always center rows so when a new row starts, it starts from the center --}}
        <div class="row g-4 justify-content-center tablet-grid">
          @foreach($packages as $pkg)
            @php
              $title = $pkg->title ?? 'Package';

              // prices
              $mrp = (float) ($pkg->mrp ?? 0);
              $discounted = (float) ($pkg->discounted_price ?? 0);
              $price = (float) ($pkg->price ?? 0);
              $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);
              $savePercent = ($mrp > 0 && $displayPrice) ? round((($mrp - $displayPrice) / $mrp) * 100) : 0;
              $savingAmount = ($mrp > 0 && $displayPrice && $mrp > $displayPrice) ? ($mrp - $displayPrice) : 0;

              // image
              $banner = $pkg->banner ? ltrim($pkg->banner, '/') : null;
              $bannerPath = $banner ? storage_path('app/public/' . $banner) : null;
              $hasBanner = $bannerPath && file_exists($bannerPath);
              $defaultImage = asset('Front_end/assets/img/blog/default-package.jpg');
              $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 360'><rect width='100%' height='100%' fill='#f6fbfb'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#9aa0a6' font-size='20'>No image available</text></svg>";
              $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode($svg);

              // chips (tests)
              $tests = [];
              if (isset($pkg->tests) && is_iterable($pkg->tests) && $pkg->tests->count()) {
                foreach ($pkg->tests as $t) {
                  $tests[] = trim($t->test_name ?? ($t->title ?? ($t->name ?? '')));
                }
              } elseif (!empty($pkg->tests_list) && is_string($pkg->tests_list)) {
                $tests = array_map('trim', explode(',', strip_tags($pkg->tests_list)));
              } else {
                $parts = preg_split('/\r\n|\n|,/', $pkg->content ?? '');
                $parts = array_map(fn($v) => trim(rtrim($v, ", \t\n\r\0\x0B")), $parts);
                $parts = array_filter($parts, fn($v) => $v !== '');
                $tests = array_values($parts);
              }
              $seen = [];
              $tests = array_values(array_filter(array_map(function ($v) use (&$seen) {
                $k = mb_strtolower($v);
                if ($k === '' || isset($seen[$k]))
                  return null;
                $seen[$k] = true;
                return $v;
              }, $tests)));
              $maxShow = 4;
              $totalBadges = count($tests);
              $showBadges = array_slice($tests, 0, $maxShow);
              // dd($showBadges[3]);

              $packageParam = $pkg->slug ?? $pkg->id;
            @endphp

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
              <div class="pretty-card h-100 d-flex flex-column position-relative w-100">
                <a class="img-fixed rounded-top-4 overflow-hidden bg-light d-block"
                  href="{{ route('packages.special.show', ['package' => $packageParam]) }}" aria-label="Open {{ $title }}">
                  @if($hasBanner)
                    <img src="{{ asset('storage/' . $banner) }}" class="pkg-img" alt="{{ $title }}" loading="lazy"
                      style="object-fit:cover;">

                  @elseif(file_exists(public_path('Front_end/assets/img/blog/default-package.jpg')))
                    <img src="{{ $defaultImage }}" class="pkg-img" alt="{{ $title }}" loading="lazy" style="object-fit:cover;">
                  @else
                    <img src="{{ $placeholder }}" class="pkg-img" alt="No image" loading="lazy" style="object-fit:cover;">
                  @endif
                </a>

                <div class="card-body d-flex flex-column">
                  <h5 class="card-title mb-2 pkg-title">
                    <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                      class="title-link text-decoration-none">
                      {{ \Illuminate\Support\Str::limit($title, 60) }}
                    </a>
                  </h5>

                  {{-- Chips / badges (always render the container to preserve height) --}}
                  <div class="chips mb-2">
                    @foreach($showBadges as $b)
                      <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}" class="chip-pill"
                        title="{{ strip_tags($b) }}">
                        <span class="text-truncate">{{ \Illuminate\Support\Str::limit($b, 32) }}</span>
                      </a>
                    @endforeach
                    @if($totalBadges > $maxShow)
                      <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                        class="chip-pill chip-more">+{{ $totalBadges - $maxShow }} more</a>
                    @endif
                  </div>

                  <div class="flex-grow-1"></div>

                  <div class="purchase-meta mb-2">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                      @if($displayPrice)
                        <div class="price-final">₹{{ number_format($displayPrice, 0) }}</div>
                        @if($mrp > 0 && $mrp > $displayPrice)
                          <div class="price-mrp">₹{{ number_format($mrp, 0) }}</div>
                        @endif
                        @if($savePercent > 0)
                          <span class="discount-pill">{{ $savePercent }}% OFF</span>
                        @endif
                      @else
                        <div class="price-final">Contact</div>
                        <small class="text-muted">for price</small>
                      @endif
                    </div>

                    @if($savingAmount > 0)
                      <div class="save-link">You save ₹{{ number_format($savingAmount, 0, '.', ',') }}</div>
                    @endif
                  </div>

                  {{-- ACTIONS --}}
                  <div class="d-flex flex-column flex-sm-row gap-2 btn-foreground">
                    <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                      class="btn btn-view-info btn-sm flex-fill flex-sm-grow-0" onclick="event.stopPropagation();">
                      <i class="fa fa-info-circle me-1" aria-hidden="true"></i> View Info
                    </a>

                    <button type="button" id="cart-btn-{{ $pkg->id }}" data-pkg-id="{{ $pkg->id }}"
                      class="btn btn-action-cart btn-add-cart btn-sm flex-fill"
                      onclick="event.preventDefault(); event.stopPropagation(); addToCart(event, 'package', {{ $pkg->id }});">
                      <i class="fa fa-cart-plus me-1" aria-hidden="true"></i>
                      <span class="btn-text">Add to Cart</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        @if(method_exists($packages, 'links') && $packages->hasPages())
          <div class="pagination-wrapper d-flex justify-content-center mt-4 mb-4">
            {{ $packages->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
          </div>
        @endif

      </div>
    @else
      <div class="alert alert-info text-center">No special packages available right now.</div>
    @endif
  </section>
@endsection

@section('scripts')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    /* --- Card look --- */
    #packages-special .pretty-card {
      background: #fff;
      border: 0;
      border-radius: 20px;
      box-shadow: 0 10px 20px rgba(10, 38, 64, .06), 0 3px 8px rgba(10, 38, 64, .04);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    /* ================= SPECIAL PACKAGES – ANIMATIONS ================= */

    /* Initial hidden state */
    #packages-special .pretty-card {
      opacity: 0;
      transform: translateY(26px);
      transition:
        opacity 520ms ease,
        transform 520ms cubic-bezier(.2, .8, .2, 1),
        box-shadow 220ms cubic-bezier(.2, .8, .2, 1);
    }

    /* When visible */
    #packages-special .pretty-card.is-visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Hover lift – card only */
    #packages-special .pretty-card:hover {
      transform: translateY(-6px);
      box-shadow:
        0 20px 45px rgba(10, 38, 64, .16),
        0 6px 16px rgba(10, 38, 64, .08);
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
      #packages-special .pretty-card {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
      }
    }

    #packages-special .card-body {
      padding: 18px;
      display: flex;
      flex-direction: column;
      flex: 1 1 auto;
    }

    /* --- Grid: center every row; make columns equal-height containers --- */
    #packages-special .row.justify-content-center {
      justify-content: center;
    }

    #packages-special .row>[class*="col"] {
      display: flex;
    }

    #packages-special .row.g-4>[class*="col-"] {
      margin-bottom: 1.5rem;
    }

    /* --- Uniform image --- */

    /* --- Title clamp --- */
    #packages-special .pkg-title {
      margin: 0;
      font-size: 1.03rem;
      font-weight: 700;
      min-height: calc(1.3em * 2);
    }

    #packages-special .title-link {
      color: #ff2b6d;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      -webkit-line-clamp: 2;
    }

    #packages-special .title-link:hover {
      opacity: .9;
    }

    /* --- Chips (always reserve space) --- */
    #packages-special .chips {
      min-height: 32px;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    #packages-special .chips:empty::before {
      content: "";
      display: block;
      height: 32px;
    }

    #packages-special .chip-pill {
      align-items: center;
      max-width: 100%;
      padding: 4px 10px;
      border-radius: 999px;
      background: #eaf2ff;
      color: #1e4a86;
      font-weight: 600;
      font-size: .8rem;
      text-decoration: none;
      border: none;
      box-shadow: inset 0 0 0 1px rgba(30, 74, 134, .07);
    }

    #packages-special .chip-pill {
      max-width: 100%;
    }

    /* #packages-special .chip-pill .text-truncate{ display:inline-block; max-width:170px; } */
    @media (max-width:576px) {
      #packages-special .chip-pill .text-truncate {
        max-width: 130px;
      }
    }

    #packages-special .chip-more {
      background: #eef5ff;
    }

    /* --- Price block (reserve consistent space) --- */
    #packages-special .price-final {
      font-size: 1.2rem;
      font-weight: 800;
      color: #1a3a66;
    }

    #packages-special .price-mrp {
      color: #000 !important;
      text-decoration: line-through;
      margin-left: 8px;
      font-weight: 600;
    }

    #packages-special .discount-pill {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      background: #e9f9ef;
      color: #2a7b3f;
      font-weight: 700;
      font-size: .8rem;
      box-shadow: inset 0 0 0 1px rgba(42, 123, 63, .08);
    }

    #packages-special .purchase-meta {
      min-height: 48px;
    }

    #packages-special .purchase-meta .d-flex.align-items-center {
      gap: 10px;
    }

    #packages-special .save-link {
      color: #0a66d6;
      font-weight: 600;
      font-size: .9rem;
    }

    /* --- Buttons layer --- */
    #packages-special .btn-foreground,
    #packages-special .btn-foreground .btn {
      position: relative;
      z-index: 5;
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    @media (min-width:577px) {
      #packages-special .btn-foreground {
        gap: 16px;
      }
    }

    /* --- View Info button (compact) --- */
    #packages-special .btn-view-info {
      background: #fff;
      color: #0a66d6;
      border: 1px solid rgba(10, 102, 214, .25);
      font-weight: 600;
      border-radius: 8px;
      padding: .22rem .5rem;
      font-size: .75rem;
      min-height: 28px;
      line-height: 1;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .25rem;
    }

    #packages-special .btn-view-info i {
      font-size: .8rem;
    }

    #packages-special .btn-view-info:hover {
      background: #f5f9ff;
    }

    /* --- Cart buttons --- */
    #packages-special .btn-action-cart {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .6rem;
      padding: .6rem 1rem;
      font-size: .95rem;
      border-radius: 12px;
      font-weight: 700;
      line-height: 1.2;
      min-height: 44px;
      border: 0;
      cursor: pointer;
      min-width: 140px;
    }

    /* Soft pink add-to-cart (as requested) */
    #packages-special .btn-add-cart.btn-action-cart {
      background: #f0c2c2;
      color: #2b4a66;
      border: 1px solid rgba(14, 63, 108, .12);
      transition: background .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    #packages-special .btn-add-cart.btn-action-cart:hover {
      background: #eef4ff;
      border-color: rgba(14, 63, 108, .25);
      color: #173b5f;
    }

    #packages-special .btn-add-cart.btn-action-cart:active {
      background: #eef4ff;
    }

    /* Go to cart (green) */
    #packages-special .btn-go-cart.btn-action-cart {
      background: #9dd24a !important;
      color: #fff !important;
      border: 0 !important;
      box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
    }

    #packages-special .btn-go-cart.btn-action-cart:hover {
      background: #8ac83d !important;
    }

    #packages-special .btn-go-cart.btn-action-cart:active {
      background: #7ebd33 !important;
    }

    #packages-special .btn-action-cart.is-adding {
      pointer-events: none;
      opacity: .9;
    }

    #packages-special .btn-view-info i,
    #packages-special .btn-action-cart i {
      font-size: 1.05em;
    }

    /* Bottom spacing for each card (requested earlier) */
    #packages-special .pretty-card {
      margin-bottom: 24px;
    }

    /* Mobile compact tweaks */
    @media (max-width:576px) {

      #packages-special .btn-view-info,
      #packages-special .btn-action-cart {
        padding: .55rem .9rem;
        font-size: .92rem;
        min-height: 40px;
        min-width: 120px;
      }

      #packages-special .btn-foreground {
        gap: .5rem;
      }
    }

    /* Show horizontal scrollbar */


    /* ================= SAME CARD IMAGE EVERYWHERE ================= */



    /* Image always fills card */

    /* ================= SAME CARD IMAGE EVERYWHERE ================= */

    #packages-special .img-fixed {
      position: relative;
      height: 240px;
      /* SAME height mobile + desktop */
      width: 100%;
      overflow: hidden;
    }

    /* Image always fills card */
    #packages-special .pkg-img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      display: block;
      object-fit: cover;
      /* 🔥 SAME ON ALL SCREENS */
      object-position: center;
    }

    #packages-special .pretty-card {
      max-width: 340px;
      /* try 260px / 280px / 300px */
      margin-left: auto;
      margin-right: auto;
    }

    /* ================= MOBILE HORIZONTAL SCROLL FOR SPECIAL PACKAGES ================= */
    @media (max-width: 767px) {
      #packages-special .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
        overflow: hidden !important;
      }

      #packages-special .tablet-grid,
      #packages-special .row.tablet-grid {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        overflow-y: hidden !important;
        -webkit-overflow-scrolling: touch !important;
        scroll-snap-type: x mandatory !important;
        gap: 16px !important;
        padding-left: 5px !important;
        padding-right: 15px !important;
        padding-bottom: 20px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        justify-content: flex-start !important;
      }

      #packages-special .tablet-grid > [class*="col"] {
        flex: 0 0 285px !important;
        max-width: 285px !important;
        min-width: 270px !important;
        width: 285px !important;
        scroll-snap-align: start !important;
        margin-bottom: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      #packages-special .pretty-card {
        max-width: 100% !important;
        width: 100% !important;
        margin-left: unset !important;
        margin-right: unset !important;
      }
    }


    /* ================= TABLET: FORCE 2 CARDS GRID ================= */

    /* All tablets: iPad Mini, Air, Pro, Surface */
    @media (min-width: 768px) and (max-width: 1024px) {
      #packages-special .tablet-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(280px, 1fr));
        gap: 28px;
        justify-content: center;
      }

      /* Disable Bootstrap column sizing */
      #packages-special .tablet-grid>[class*="col"] {
        flex: unset !important;
        max-width: unset !important;
        width: auto !important;
      }
    }
  </style>

  <script>
    /* ---------- CART: Add → Go to Cart (same behavior as Home) ---------- */
    (function () {
      const CART_ADD_URL = "{{ route('cart.add') }}";
      const CART_ITEMS_URL = "{{ route('cart.items') }}";
      const CART_URL = "{{ route('cart.index') }}";
      const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      function convertToGoToCart(btn) {
        if (!btn) return;
        btn.classList.remove('btn-add-cart', 'is-adding', 'btn-primary', 'btn-cart');
        if (!btn.classList.contains('btn-action-cart')) btn.classList.add('btn-action-cart');
        btn.classList.add('btn-go-cart');
        btn.removeAttribute('style');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-shopping-cart me-1" aria-hidden="true"></i><span class="btn-text">Go to Cart</span>';
        btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); window.location.href = CART_URL; };
      }

      function setAddingState(btn) {
        if (!btn) return;
        btn.classList.add('is-adding');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding…';
      }

      function unsetAddingState(btn, html) {
        if (!btn) return;
        btn.classList.remove('is-adding');
        btn.disabled = false;
        btn.innerHTML = html;
      }

      function convertToAdd(btn, id) {
        if (!btn) return;
        btn.classList.remove('btn-go-cart', 'is-adding');
        if (!btn.classList.contains('btn-action-cart')) btn.classList.add('btn-action-cart');
        if (!btn.classList.contains('btn-add-cart')) btn.classList.add('btn-add-cart');
        btn.innerHTML = '<i class="fa fa-cart-plus me-1" aria-hidden="true"></i><span class="btn-text">Add to Cart</span>';
        btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); addToCart(e, 'package', id); };
      }

      async function refreshSpecialCartButtons() {
        try {
          const res = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
            headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
          });
          if (!res.ok) return;
          const ct = res.headers.get('content-type') || '';
          if (!ct.includes('application/json')) return;
          const data = await res.json().catch(() => ({}));

          const ids = new Set((data.items || [])
            .filter(i => String(i.item_type) === 'package')
            .map(i => Number(i.item_id))
            .filter(Number.isFinite));

          document.querySelectorAll('#packages-special button[id^="cart-btn-"]').forEach(btn => {
            const idAttr = btn.getAttribute('id') || '';
            const id = Number(idAttr.replace('cart-btn-', '')) || Number(btn.dataset.pkgId);
            if (!id) return;
            if (ids.has(id)) convertToGoToCart(btn); else convertToAdd(btn, id);
          });

          if (data && data.count !== undefined) {
            const badge = document.querySelector('#cart-count-badge');
            if (badge) badge.textContent = data.count;
          }
        } catch (e) { console.warn('refreshSpecialCartButtons failed:', e); }
      }

      if (typeof window.addToCart !== 'function') {
        window.addToCart = async function (event, type, id) {
          event?.preventDefault?.();
          event?.stopPropagation?.();

          const btn = (event && event.target) ? event.target.closest('button') : document.querySelector('#cart-btn-' + id);
          if (!btn) return;
          if (btn.classList.contains('btn-go-cart')) { window.location.href = CART_URL; return; }

          const original = btn.innerHTML;
          setAddingState(btn);

          try {
            const res = await fetch(CART_ADD_URL, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify({ item_type: type, item_id: id, quantity: 1 }),
              cache: 'no-store',
              credentials: 'same-origin'
            });

            const ct = res.headers.get('content-type') || '';
            const isJson = ct.includes('application/json');
            const data = isJson ? await res.json().catch(() => ({})) : {};

            if (!res.ok) {
              if (res.status === 401) { window.location.href = '/login'; return; }
              if (res.status === 419) { alert('Session expired. Please refresh and try again.'); unsetAddingState(btn, original); return; }
              alert((data && (data.message || data.error)) ? (data.message || data.error) : 'Failed to add to cart.');
              unsetAddingState(btn, original);
              return;
            }

            if (data && (data.success || data.status === 'success')) {
              convertToGoToCart(btn);
              if (data.count !== undefined) {
                const badge = document.querySelector('#cart-count-badge');
                if (badge) badge.textContent = data.count;
              }
              refreshSpecialCartButtons();
            } else {
              alert((data && (data.message || data.error)) ? (data.message || data.error) : 'Failed to add to cart.');
              unsetAddingState(btn, original);
            }
          } catch (err) {
            console.error('addToCart error', err);
            alert('Something went wrong while adding to cart.');
            unsetAddingState(btn, original);
          }
        };
      }

      document.addEventListener('DOMContentLoaded', refreshSpecialCartButtons, { once: true });
      window.addEventListener('pageshow', refreshSpecialCartButtons);
    })();


    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#packages-special .pretty-card');

      if (!('IntersectionObserver' in window)) {
        cards.forEach(card => card.classList.add('is-visible'));
        return;
      }

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const card = entry.target;

            // stagger delay
            const index = [...cards].indexOf(card);
            card.style.transitionDelay = `${index * 80}ms`;

            card.classList.add('is-visible');
            observer.unobserve(card);
          }
        });
      }, {
        threshold: 0.25,
        rootMargin: '0px 0px -60px 0px'
      });

      cards.forEach(card => observer.observe(card));
    });
  </script>
@endsection