@extends('maindesign')

@section('title', 'Search results for "' . ($q ?? request('q')) . '"' )

@section('content')
<section class="premium-search-results">
  <div class="hero bg-gradient">
    <div class="container px-3 px-md-5">
      <div class="row">
        <div class="col-12">
          <div class="hero-inner text-center">
            <h1 class="hero-title mb-2">Search results for “{{ $q ?? request('q') }}”</h1>
            <p class="hero-sub mb-3">Refined search across Wellcare Labs — tests, packages and exclusive special packages.</p>

            {{-- hero stats removed per your last version --}}
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MAIN CONTENT --}}
  <div class="container px-3 px-md-5 mt-5">
    <div class="row gx-4 gy-4 align-items-start equalize-row">

      {{-- Tests --}}
      <div class="col-12 col-lg-4">
        <div class="column-panel shadow-sm rounded-4 p-3 bg-white h-100">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <h4 class="mb-0 section-title"><i class="fa fa-flask me-2 text-primary"></i> Tests</h4>
            <small class="text-muted align-self-center">{{ isset($tests) ? $tests->total() : 0 }} results</small>
          </div>

          @if(isset($tests) && $tests->count())
            <div class="list-vertical">
              @foreach($tests as $test)
                <article class="result-item d-flex flex-column justify-content-between position-relative p-3 mb-3 rounded-3 border">
                  <div>
                    <h5 class="mb-1 result-title">
                      <a href="{{ route('services') }}?q={{ urlencode($test->test_name) }}" class="text-decoration-none text-dark">
                        {{ \Illuminate\Support\Str::limit($test->test_name, 70) }}
                      </a>
                    </h5>

                    @if(!empty($test->short_description))
                      <p class="mb-2 text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($test->short_description), 100) }}</p>
                    @endif

                    @php
                      $testPrice = null;
                      if(isset($test->discounted_price) && $test->discounted_price) {
                        $testPrice = $test->discounted_price;
                      } elseif(isset($test->price) && $test->price) {
                        $testPrice = $test->price;
                      }
                    @endphp

                    @if($testPrice)
                      <div class="mt-1">
                        <span class="price-tag" style="font-weight:600 !important; font-size:1.08rem !important; color:#0b1220 !important; display:inline-block !important;">
                          ₹{{ number_format((float)$testPrice, 0) }}
                        </span>
                      </div>
                    @endif
                  </div>

                  <a href="{{ route('services') }}?q={{ urlencode($test->test_name) }}" class="btn btn-sm btn-primary rounded-pill px-3 view-btn">
                    <i class="fa fa-eye me-1"></i> View
                  </a>
                </article>
              @endforeach
            </div>

            @if(method_exists($tests, 'links'))
              <div class="mt-3 d-flex justify-content-center">
                {{ $tests->withQueryString()->links('pagination::bootstrap-5') }}
              </div>
            @endif
          @else
            <div class="text-center py-4">
              <img src="https://cdn-icons-png.flaticon.com/512/7486/7486759.png" width="96" class="mb-3 opacity-75" alt="No tests">
              <div class="fw-bold text-muted mb-1">No tests found</div>
              <div class="small text-secondary">Try a different term.</div>
            </div>
          @endif
        </div>
      </div>

      {{-- Packages --}}
      <div class="col-12 col-lg-4">
        <div class="column-panel shadow-sm rounded-4 p-3 bg-white h-100">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <h4 class="mb-0 section-title"><i class="fa fa-box me-2 text-success"></i> Packages</h4>
            <small class="text-muted align-self-center">{{ isset($packages) ? $packages->total() : 0 }} results</small>
          </div>

          @if(isset($packages) && $packages->count())
            <div class="list-vertical">
              @foreach($packages as $pkg)
                <article class="result-item d-flex flex-column justify-content-between position-relative p-3 mb-3 rounded-3 border">
                  <div>
                    <h5 class="mb-1 result-title">
                      <a href="{{ route('packages.show', ['package' => $pkg->slug ?? $pkg->id]) }}" class="text-decoration-none text-dark">
                        {{ \Illuminate\Support\Str::limit($pkg->title ?? 'Unnamed Package', 72) }}
                      </a>
                    </h5>

                    @php
                      $displayPrice = (float)($pkg->discounted_price ?? 0) ?: (float)($pkg->price ?? 0);
                    @endphp
                    @if($displayPrice)
                      <div class="mt-1">
                        <span class="price-tag" style="font-weight:600 !important; font-size:1.08rem !important; color:#0b1220 !important; display:inline-block !important;">
                          ₹{{ number_format($displayPrice, 0) }}
                        </span>
                      </div>
                    @endif
                  </div>

                  <a href="{{ route('packages.show', ['package' => $pkg->slug ?? $pkg->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3 view-btn">
                    <i class="fa fa-eye me-1"></i> View
                  </a>
                </article>
              @endforeach
            </div>

            @if(method_exists($packages, 'links'))
              <div class="mt-3 d-flex justify-content-center">
                {{ $packages->withQueryString()->links('pagination::bootstrap-5') }}
              </div>
            @endif
          @else
            <div class="text-center py-4">
              <img src="https://cdn-icons-png.flaticon.com/512/7486/7486759.png" width="96" class="mb-3 opacity-75" alt="No packages">
              <div class="fw-bold text-muted mb-1">No packages found</div>
              <div class="small text-secondary">Try another term.</div>
            </div>
          @endif
        </div>
      </div>

      {{-- Special Packages --}}
      <div class="col-12 col-lg-4">
        <div class="column-panel shadow-sm rounded-4 p-3 bg-white h-100">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <h4 class="mb-0 section-title"><i class="fa fa-star me-2 text-warning"></i> Special Packages</h4>
            <small class="text-muted align-self-center">{{ isset($specialPackages) ? $specialPackages->total() : 0 }} results</small>
          </div>

          @if(isset($specialPackages) && $specialPackages->count())
            <div class="list-vertical">
              @foreach($specialPackages as $spkg)
                <article class="result-item d-flex flex-column justify-content-between position-relative p-3 mb-3 rounded-3 border special-highlight">
                  <div>
                    <h5 class="mb-1 result-title">
                      <a href="{{ route('packages.special.show', ['package' => $spkg->slug ?? $spkg->id]) }}" class="text-decoration-none text-dark">
                        {{ \Illuminate\Support\Str::limit($spkg->title ?? 'Special Package', 72) }}
                      </a>
                    </h5>

                    @php
                      $sp_displayPrice = (float)($spkg->discounted_price ?? 0) ?: (float)($spkg->price ?? 0);
                    @endphp
                    @if($sp_displayPrice)
                      <div class="mt-1">
                        <span class="price-tag" style="font-weight:600 !important; font-size:1.08rem !important; color:#0b1220 !important; display:inline-block !important;">
                          ₹{{ number_format($sp_displayPrice, 0) }}
                        </span>
                      </div>
                    @endif
                  </div>

                  <a href="{{ route('packages.special.show', ['package' => $spkg->slug ?? $spkg->id]) }}" class="btn btn-sm btn-primary rounded-pill px-3 view-btn">
                    <i class="fa fa-eye me-1"></i> View
                  </a>
                </article>
              @endforeach
            </div>

            @if(method_exists($specialPackages, 'links'))
              <div class="mt-3 d-flex justify-content-center">
                {{ $specialPackages->withQueryString()->links('pagination::bootstrap-5') }}
              </div>
            @endif
          @else
            <div class="text-center py-4">
              <img src="https://cdn-icons-png.flaticon.com/512/7486/7486759.png" width="96" class="mb-3 opacity-75" alt="No special packages">
              <div class="fw-bold text-muted mb-1">No special packages found</div>
              <div class="small text-secondary">Try another term.</div>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</section>
@endsection

@push('styles')
<style>
  :root{
    --brand-blue:#0047ff;
    --brand-blue-2:#00ccff;
    --heading:#0a2540;
    --muted:#6b7280;
    --card-shadow: 0 8px 30px rgba(10,30,60,0.06);
  }

  /* PAGE BACKGROUND */
  .premium-search-results { background:#f6f9fc; padding-bottom:4rem; }

  /* HERO */
  .hero {
    padding: 3.2rem 0 1.6rem;
    background: linear-gradient(180deg, rgba(0,71,255,0.03), rgba(0,204,255,0.01));
    border-bottom: 1px solid rgba(10,30,60,0.03);
  }
  .hero-inner {
    max-width: 920px;
    margin: 0 auto;
    padding: 1.6rem;
    border-radius: 12px;
  }
  .hero-title {
    font-size: 2.1rem;
    font-weight: 800;
    color: var(--heading);
    letter-spacing: -0.4px;
    margin-bottom: 0.25rem;
  }
  .hero-sub {
    color: var(--muted);
    font-size: 1.03rem;
    margin-bottom: 0.6rem;
    line-height: 1.45;
  }

  /* LAYOUT CARDS */
  .column-panel { min-height: 320px; }
  .list-vertical { max-height: calc(100vh - 380px); overflow:auto; padding-right:6px; }

  .result-item {
    position: relative;
    min-height: 230px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: #fff;
    border: 1px solid rgba(10,30,60,0.04);
    border-radius: 12px;
  }

  .result-title { font-size: 1rem; margin: 0; font-weight: 700; }
  .result-title a { color: #0a2540; text-decoration: none; }
  .result-title a:hover { text-decoration: underline; }

  .avatar-box, .avatar-star { width:48px; height:48px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:700; }

  /* view button */
  .view-btn {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: linear-gradient(90deg,#007bff,#00c6ff);
    border: none;
    color: #fff !important;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    transition: 0.3s;
    font-weight: 600;
  }
  .view-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(0,0,0,0.2); }

  /* price */
  .price-tag { font-weight: 600 !important; font-size: 1.08rem !important; color:#0b1220 !important; display:inline-block !important; }

  /* special highlight */
  .special-highlight { border-left: 4px solid rgba(255,170,0,0.12); background: linear-gradient(180deg,#fffdf8,#ffffff); }

  @media (max-width: 991px) {
    .hero-title { font-size: 1.6rem; }
    .hero-sub { font-size: .98rem; }
    .list-vertical { max-height: none; overflow: visible; }
    .result-item { min-height: auto; }
    .hero-inner { padding: 1rem; }
  }
</style>
@endpush

@push('scripts')
<script>
  (function () {
    const debounce = (fn, delay = 120) => {
      let t;
      return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn(...args), delay);
      };
    };

    function equalizeRowHeights() {
      const cols = Array.from(document.querySelectorAll('.equalize-row > .col-12.col-lg-4'));
      if (!cols.length) {
        const panels = document.querySelectorAll('.column-panel');
        if (panels.length) {
          cols.length = 0;
          panels.forEach(p => cols.push(p.parentElement));
        }
      }

      const itemsPerCol = cols.map(col => col ? Array.from(col.querySelectorAll('.result-item')) : []);
      const maxCount = itemsPerCol.reduce((m, arr) => Math.max(m, arr.length), 0);
      itemsPerCol.forEach(arr => arr.forEach(it => it.style.height = 'auto'));

      for (let i = 0; i < maxCount; i++) {
        let maxHeight = 0;
        for (let arr of itemsPerCol) {
          const el = arr[i];
          if (el) {
            const h = el.getBoundingClientRect().height;
            if (h > maxHeight) maxHeight = Math.ceil(h);
          }
        }
        if (maxHeight > 0) {
          for (let arr of itemsPerCol) {
            const el = arr[i];
            if (el) el.style.height = maxHeight + 'px';
          }
        }
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      equalizeRowHeights();
      setTimeout(equalizeRowHeights, 250);
    });

    window.addEventListener('load', function () {
      equalizeRowHeights();
      setTimeout(equalizeRowHeights, 250);
    });

    window.addEventListener('resize', debounce(function () {
      if (window.innerWidth <= 991) {
        document.querySelectorAll('.result-item').forEach(it => it.style.height = 'auto');
        return;
      }
      equalizeRowHeights();
    }, 120));

    window.equalizeRowHeights = equalizeRowHeights;
  })();
</script>
@endpush
