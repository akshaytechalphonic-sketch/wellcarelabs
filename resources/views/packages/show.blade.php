{{-- resources/views/packages/show.blade.php --}}
@extends('maindesign')

@section('title', $package->meta_title )
@section('meta_description', $package->meta_description )

@section('seo')
    {!! $package->meta_tags !!}
@endsection

@php
  // image handling
  $banner = $package->banner ? ltrim($package->banner, '/') : null;
  $bannerPath = $banner ? storage_path('app/public/' . $banner) : null;
  $defaultImage = file_exists(public_path('Front_end/assets/img/blog/default-package.jpg'))
                  ? asset('Front_end/assets/img/blog/default-package.jpg')
                  : 'https://via.placeholder.com/1200x800?text=No+Image';

  // price
  $mrp = (float)($package->mrp ?? 0);
  $discounted = (float)($package->discounted_price ?? 0);
  $price = (float)($package->price ?? 0);
  $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);
  $savePercent = ($mrp > 0 && $displayPrice) ? round((($mrp - $displayPrice) / $mrp) * 100) : 0;
  $savingAmount = ($mrp > 0 && $displayPrice && $mrp > $displayPrice) ? ($mrp - $displayPrice) : 0;

  // tests list
  $tests = [];
  if ($package->tests()->count() > 0) {
    foreach ($package->tests as $t) {
      $rawTitle = trim($t->test_name ?? '');
      $count = (int)($t->parameters_count ?? 0);
      $slug = $t->slug;

      if (preg_match('/^(.*?)\s*\((\d+)\)$/', $rawTitle, $m)) {
        $cleanTitle = trim($m[1]);
        $paramCount = $count > 0 ? $count : (int)$m[2];
        $tests[] = [
          'title' => $cleanTitle,
          'parameters_count' => $paramCount,
          'display' => "{$cleanTitle}({$paramCount})",
          'slug' => $slug
        ];
      } else {
        $tests[] = [
          'title' => $rawTitle,
          'parameters_count' => $count,
          'display' => $count > 0 ? "{$rawTitle}({$count})" : $rawTitle,
          'slug' => $slug
        ];
      }
    }
  } else {
    $parts = preg_split('/\r\n|\n|,/', $package->content ?? '');
    $parts = array_map(fn($v) => trim(rtrim($v, ", \t\n\r\0\x0B")), $parts);
    $parts = array_filter($parts, fn($v) => $v !== '');
    foreach ($parts as $p) {
      if (preg_match('/^(.*?)\s*\((\d+)\)$/', $p, $m)) {
        $cleanTitle = trim($m[1]);
        $paramCount = (int)$m[2];
        $dbTest = \App\Models\LabTest::whereRaw('LOWER(test_name) = ?', [strtolower($cleanTitle)])->first();
        $tests[] = [
          'title' => $cleanTitle,
          'parameters_count' => $paramCount,
          'display' => "{$cleanTitle}({$paramCount})",
          'slug' => $dbTest ? $dbTest->slug : null
        ];
      } else {
        $dbTest = \App\Models\LabTest::whereRaw('LOWER(test_name) = ?', [strtolower($p)])->first();
        $tests[] = [
          'title' => $p,
          'parameters_count' => 0,
          'display' => $p,
          'slug' => $dbTest ? $dbTest->slug : null
        ];
      }
    }
  }
  // dedupe
  $seen = [];
  $tests = array_values(array_filter(array_map(function($v) use (&$seen){
    $k = mb_strtolower($v['display']);
    if ($k === '' || isset($seen[$k])) return null;
    $seen[$k] = true;
    return $v;
  }, $tests)));

  // Main package parameter count
  $sumParams = array_sum(array_column($tests, 'parameters_count'));
  $mainPackageParamCount = !empty($package->parameters_count) ? (int)$package->parameters_count : ($sumParams > 0 ? $sumParams : null);

  // description clean
  $rawDesc = $package->short_description ?? $package->content ?? '';
  $descriptionParts = preg_split('/\r\n|\n|,/', $rawDesc);
  $descriptionParts = array_map(fn($v) => trim(rtrim($v, ", \t\n\r\0\x0B")), $descriptionParts);
  $descriptionParts = array_filter($descriptionParts, fn($v)=> $v !== '');
  $cleanDescription = implode(', ', $descriptionParts);

  // if description duplicates the tests list, hide it
  $testsJoined = implode(', ', array_column($tests, 'display'));
  $showDescription = true;
  if ($cleanDescription !== '' && $testsJoined !== '') {
    if (mb_strtolower(trim($cleanDescription)) === mb_strtolower(trim($testsJoined))) {
      $showDescription = false;
    }
  } elseif ($cleanDescription === '') {
    $showDescription = false;
  }
@endphp

@push('head')
<!-- FontAwesome and Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<style>
/* Page Layout matching Single Test Page */
.test-detail-container {
    width: 100%;
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 18px;
}

/* Breadcrumbs */
.breadcrumb-container {
    margin-bottom: 1.5rem;
}
.breadcrumb-container .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 8px;
    align-items: center;
    font-size: 0.9rem;
}
.breadcrumb-container .breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: #94a3b8;
    padding-right: 8px;
}
.breadcrumb-container a {
    color: #64748b;
    text-decoration: none;
    font-weight: 500;
}
.breadcrumb-container a:hover {
    color: #0d6efd;
}
.breadcrumb-container .active {
    color: #ff2b6d;
    font-weight: 600;
}

/* Page Structure: Two Columns */
.test-layout {
    display: flex;
    gap: 28px;
    align-items: flex-start;
}
.test-main-content {
    flex: 1 1 72%;
    max-width: 72%;
}
.test-sidebar {
    flex: 0 0 28%;
    max-width: 28%;
    position: sticky;
    top: 100px;
}

/* Header Card Block */
.package-card {
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    margin-bottom: 24px;
}
.package-inner {
    display: flex;
    gap: 0;
    align-items: stretch;
}
.img-col {
    flex: 0 0 38%;
    max-width: 38%;
    background: #f8fafc;
}
.content-col {
    flex: 1 1 62%;
    max-width: 62%;
    display: flex;
    flex-direction: column;
}

/* Image */
.package-image-wrap {
    padding: 16px;
    box-sizing: border-box;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.package-image {
    width: 100%;
    height: auto;
    max-height: 320px;
    object-fit: contain;
    display: block;
    border-radius: 12px;
    border: 4px solid #ffffff;
    box-shadow: 0 6px 18px rgba(2, 6, 23, 0.08);
}

/* Body */
.package-body {
    padding: 24px 28px;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.package-title {
    font-size: 1.8rem;
    margin: 0 0 12px 0;
    color: #ff2b6d;
    font-weight: 800;
    line-height: 1.3;
}

/* Badges */
.badges-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
}
.badge-pill {
    background: #eef2ff !important;
    color: #4f46e5 !important;
    padding: 6px 12px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 12.5px;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
    border: 1px solid rgba(79, 70, 229, 0.12);
    text-decoration: none !important;
    transition: all 0.2s ease-in-out;
}
a.badge-pill:hover {
    background: #4f46e5 !important;
    color: #ffffff !important;
    border-color: #4f46e5;
}

/* Description */
.package-desc {
    color: #475569;
    line-height: 1.65;
    margin-bottom: 14px;
    font-size: 0.95rem;
}
.read-more-btn {
    color: #0d6efd;
    font-weight: 700;
    text-decoration: none;
    margin-left: 4px;
}
.read-more-btn:hover {
    text-decoration: underline;
}

/* Specifications Details Box */
.specs-details-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 18px;
}
.specs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 14px;
}
.spec-item {
    display: flex;
    flex-direction: column;
}
.spec-label {
    font-size: 0.75rem;
    color: #64748b;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.spec-val {
    font-size: 0.95rem;
    color: #0f172a;
    font-weight: 700;
}

/* Price & Action Row */
.price-action-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: auto;
}
.price-details {
    display: flex;
    flex-direction: column;
}
.price-row-main {
    display: flex;
    align-items: baseline;
    gap: 10px;
}
.test-final-price {
    font-size: 1.6rem;
    font-weight: 900;
    color: #1e3a8a;
}
.test-mrp-price {
    color: #94a3b8;
    text-decoration: line-through;
    font-size: 1.05rem;
    font-weight: 600;
}
.discount-badge {
    background: #dcfce7;
    color: #15803d;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
}
.savings-message {
    color: #0d6efd;
    font-weight: 700;
    font-size: 0.9rem;
    margin-top: 4px;
}

/* Buttons */
.btn-action-cart {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 24px;
    font-size: 0.95rem;
    border-radius: 12px;
    font-weight: 700;
    min-height: 44px;
    min-width: 150px;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.btn-add-cart {
    background: #f8c2c2;
    color: #1e293b;
    border: 1px solid rgba(30, 41, 59, 0.08);
}
.btn-add-cart:hover {
    background: #f5b0b0;
}
.btn-go-cart {
    background: #9dd24a !important;
    color: #ffffff !important;
    box-shadow: 0 6px 15px rgba(157, 210, 74, 0.25);
    border: 0 !important;
}
.btn-go-cart:hover {
    background: #8ec33b !important;
}
.btn-action-cart.is-adding {
    pointer-events: none;
    opacity: 0.9;
}

/* Interactive Tabs Section - Matching Single Test Page */
.test-tabs-container {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    padding: 28px;
    margin-bottom: 24px;
}
.tabs-navigation {
    display: flex;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 24px;
    overflow-x: auto;
    gap: 8px;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}
.tabs-navigation::-webkit-scrollbar {
    display: none;
}
.tab-trigger {
    background: transparent;
    border: none;
    padding: 12px 18px;
    font-weight: 700;
    font-size: 0.95rem;
    color: #64748b;
    cursor: pointer;
    position: relative;
    white-space: nowrap;
    transition: color 0.2s;
}
.tab-trigger:hover {
    color: #ff2b6d;
}
.tab-trigger.active {
    color: #ff2b6d;
}
.tab-trigger.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 100%;
    height: 2px;
    background: #ff2b6d;
}
.tab-pane {
    display: none;
    animation: fadeIn 0.4s ease;
}
.tab-pane.active {
    display: block;
}
.pane-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 12px;
}
.pane-body {
    color: #475569;
    line-height: 1.7;
    font-size: 0.98rem;
}

/* FAQ Accordion */
.package-faq-section {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    padding: 28px;
    margin-bottom: 24px;
}
.custom-faq-accordion .accordion-button::after {
    display: none !important;
}
.custom-faq-accordion .accordion-button.collapsed .faq-arrow-icon {
    transform: rotate(0deg);
}
.custom-faq-accordion .accordion-button:not(.collapsed) .faq-arrow-icon {
    transform: rotate(90deg);
    color: #007bff !important;
}
.custom-faq-accordion .accordion-button:not(.collapsed) {
    background: #f8f9fa !important;
    color: #0a2540 !important;
}

/* Sidebar Styling matching Single Test Page */
.promo-sidebar-card {
    background: linear-gradient(135deg, #1e3a8a, #0d6efd);
    border-radius: 16px;
    padding: 30px 24px;
    color: #ffffff;
    text-align: center;
    box-shadow: 0 15px 35px rgba(13, 110, 253, 0.15);
}
.sidebar-icon {
    font-size: 3rem;
    color: #38bdf8;
    margin-bottom: 16px;
}
.sidebar-title {
    font-weight: 800;
    font-size: 1.35rem;
    margin-bottom: 10px;
    line-height: 1.3;
}
.sidebar-desc {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin-bottom: 24px;
    line-height: 1.5;
}
.sidebar-action-box {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 12px;
    padding: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}
.sidebar-action-box i {
    color: #38bdf8;
}
.sidebar-action-phone {
    font-weight: 700;
    font-size: 1.05rem;
}
.sidebar-app-links {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.app-btn {
    background: #ffffff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 10px 16px;
    color: #0f172a;
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.app-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}
.app-btn i {
    font-size: 1.4rem;
}
.app-btn-text {
    text-align: left;
}
.app-btn-label {
    display: block;
    font-size: 0.65rem;
    color: #64748b;
    line-height: 1;
}
.app-btn-name {
    font-weight: 700;
    font-size: 0.9rem;
    line-height: 1.1;
}

/* Sticky Bottom Callback Bar */
.sticky-callback-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.06);
    padding: 16px 0;
    z-index: 1000;
    display: none;
    animation: slideUp 0.3s ease-out;
}
@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.sticky-callback-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.callback-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.callback-icon {
    background: #eff6ff;
    color: #3b82f6;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.callback-title {
    font-weight: 700;
    color: #1e293b;
    display: block;
}
.callback-desc {
    font-size: 0.85rem;
    color: #64748b;
}
.callback-btn {
    background: #ff2b6d;
    color: #ffffff;
    font-weight: 700;
    padding: 10px 24px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 0.95rem;
    box-shadow: 0 4px 10px rgba(255, 43, 109, 0.2);
    transition: background 0.2s, transform 0.2s;
}
.callback-btn:hover {
    background: #e61b5c;
    color: #ffffff;
    transform: translateY(-1px);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive details */
@media (max-width: 991px) {
    .test-layout {
        flex-direction: column;
    }
    .test-main-content, .test-sidebar {
        max-width: 100%;
        flex-basis: 100%;
    }
    .test-sidebar {
        position: static;
        margin-top: 24px;
        width: 100%;
    }
    .package-inner {
        flex-direction: column;
    }
    .img-col, .content-col {
        max-width: 100%;
        flex-basis: 100%;
    }
    .package-image {
        max-height: 240px;
    }
    .package-body {
        padding: 18px 16px;
    }
    .price-action-box {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-action-cart {
        width: 100%;
    }
    .sticky-callback-inner {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    .callback-info {
        flex-direction: column;
        text-align: center;
    }
    .callback-btn {
        text-align: center;
    }
}
</style>
@endpush

@section('content')
<div class="test-detail-container">

  {{-- BREADCRUMBS --}}
  <div class="breadcrumb-container">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">Packages</a></li>
      <li class="breadcrumb-item active">{{ $package->title }}</li>
    </ol>
  </div>

  <div class="test-layout">

    {{-- LEFT MAIN COLUMN --}}
    <div class="test-main-content">

      {{-- HEADER CARD BLOCK --}}
      <div class="package-card">
        <div class="package-inner">

          {{-- LEFT IMAGE --}}
          <div class="img-col">
            <div class="package-image-wrap">
              @if($bannerPath && file_exists($bannerPath))
                <img src="{{ asset('storage/' . $banner) }}" alt="{{ $package->image_alt ?: $package->title }}" class="package-image">
              @else
                <img src="{{ $defaultImage }}" alt="{{ $package->image_alt ?: $package->title }}" class="package-image">
              @endif
            </div>
          </div>

          {{-- RIGHT CONTENT --}}
          <div class="content-col">
            <div class="package-body">

              {{-- Title --}}
              <h1 class="package-title">{{ $package->title }}</h1>

              {{-- Badges --}}
              @if(count($tests))
                <div class="badges-row">
                  @foreach($tests as $t)
                    @if(!empty($t['slug']))
                      <a href="{{ route('services.show', ['labTest' => $t['slug']]) }}" class="badge-pill">
                        {{ \Illuminate\Support\Str::limit($t['display'], 60) }}
                      </a>
                    @else
                      <div class="badge-pill">{{ \Illuminate\Support\Str::limit($t['display'], 60) }}</div>
                    @endif
                  @endforeach
                </div>
              @endif

              {{-- Description --}}
              @if($showDescription)
                @php
                    $plainText = strip_tags($cleanDescription);
                    $isLong = strlen($plainText) > 150;
                @endphp
                <div class="package-desc">
                    @if($isLong)
                        <span class="short-desc">{{ \Illuminate\Support\Str::limit($plainText, 300) }}</span>
                        <span class="full-desc" style="display: none;">{!! $cleanDescription !!}</span>
                        <a href="javascript:void(0);" class="read-more-btn">Read More</a>
                    @else
                        {!! $cleanDescription !!}
                    @endif
                </div>
              @endif

              {{-- Specifications details box --}}
              @if(!empty($package->sample_type) || !empty($package->package_code) || !empty($package->fasting) || !empty($mainPackageParamCount))
                <div class="specs-details-box">
                  <div class="specs-grid">
                    @if(!empty($mainPackageParamCount))
                      <div class="spec-item">
                        <span class="spec-label"><i class="fa-solid fa-list-check text-primary me-1"></i>Parameters</span>
                        <span class="spec-val">{{ $mainPackageParamCount }} Parameters</span>
                      </div>
                    @endif
                    @if(!empty($package->sample_type))
                      <div class="spec-item">
                        <span class="spec-label"><i class="fa-solid fa-vial text-primary me-1"></i>Sample Type</span>
                        <span class="spec-val">{{ $package->sample_type }}</span>
                      </div>
                    @endif
                    @if(!empty($package->package_code))
                      <div class="spec-item">
                        <span class="spec-label"><i class="fa-solid fa-barcode text-primary me-1"></i>Code</span>
                        <span class="spec-val">{{ $package->package_code }}</span>
                      </div>
                    @endif
                    @if(!empty($package->fasting))
                      <div class="spec-item">
                        <span class="spec-label"><i class="fa-solid fa-clock text-primary me-1"></i>Fasting</span>
                        <span class="spec-val">{{ $package->fasting }}</span>
                      </div>
                    @endif
                  </div>
                </div>
              @endif

              {{-- Price + Add to Cart --}}
              <div class="price-action-box">
                <div class="price-details">
                  <div class="price-row-main">
                    @if($displayPrice)
                      <span class="test-final-price">₹{{ number_format($displayPrice, 0) }}</span>
                      @if($mrp > $displayPrice)
                        <span class="test-mrp-price">₹{{ number_format($mrp, 0) }}</span>
                        <span class="discount-badge">{{ $savePercent }}% OFF</span>
                      @endif
                    @else
                      <span class="test-final-price">Contact</span>
                    @endif
                  </div>
                  @if($savingAmount > 0)
                    <div class="savings-message">
                      <i class="fa-solid fa-circle-check"></i> You save ₹{{ number_format($savingAmount, 0) }}
                    </div>
                  @endif
                </div>

                <div id="cart-action-{{ $package->id }}">
                  <button type="button"
                          id="cart-btn-{{ $package->id }}"
                          class="btn btn-add-cart btn-action-cart"
                          onclick="addToCart(event, 'package', {{ $package->id }})">
                    <i class="fa fa-cart-plus me-1" aria-hidden="true"></i>
                    <span class="btn-text">Add to Cart</span>
                  </button>
                </div>
              </div>

            </div>
          </div>

        </div>
      </div>

      {{-- Dynamic Specifications & Tabs Section matching Single Test Page --}}
      @php
        $hasTabs = !empty($package->why_done) || !empty($package->who_should_test) || !empty($package->how_to_read) || !empty($package->what_to_ask);
        $firstActiveSet = false;
      @endphp

      @if($hasTabs)
        <div class="test-tabs-container">
          {{-- Tabs buttons row --}}
          <div class="tabs-navigation">
            @if(!empty($package->why_done))
              <button type="button" class="tab-trigger active" data-tab="tab-why-done">Why is the Test?</button>
              @php $firstActiveSet = 'tab-why-done'; @endphp
            @endif
            @if(!empty($package->who_should_test))
              <button type="button" class="tab-trigger {{ !$firstActiveSet ? 'active' : '' }}" data-tab="tab-who-should">Who should take it ?</button>
              @php if (!$firstActiveSet) $firstActiveSet = 'tab-who-should'; @endphp
            @endif
            @if(!empty($package->how_to_read))
              <button type="button" class="tab-trigger {{ !$firstActiveSet ? 'active' : '' }}" data-tab="tab-how-read">Why is it done?</button>
              @php if (!$firstActiveSet) $firstActiveSet = 'tab-how-read'; @endphp
            @endif
            @if(!empty($package->what_to_ask))
              <button type="button" class="tab-trigger {{ !$firstActiveSet ? 'active' : '' }}" data-tab="tab-what-ask">Preparation required?</button>
              @php if (!$firstActiveSet) $firstActiveSet = 'tab-what-ask'; @endphp
            @endif
          </div>

          {{-- Tabs content area --}}
          <div class="tabs-content">
            @if(!empty($package->why_done))
              <div class="tab-pane active" id="tab-why-done">
                <h3 class="pane-title">Why is the Test?</h3>
                <div class="pane-body">{!! ($package->why_done) !!}</div>
              </div>
            @endif

            @if(!empty($package->who_should_test))
              <div class="tab-pane {{ $firstActiveSet === 'tab-who-should' ? 'active' : '' }}" id="tab-who-should">
                <h3 class="pane-title">Who should take it ?</h3>
                <div class="pane-body">{!! ($package->who_should_test) !!}</div>
              </div>
            @endif

            @if(!empty($package->how_to_read))
              <div class="tab-pane {{ $firstActiveSet === 'tab-how-read' ? 'active' : '' }}" id="tab-how-read">
                <h3 class="pane-title">Why is it done?</h3>
                <div class="pane-body">{!! ($package->how_to_read) !!}</div>
              </div>
            @endif

            @if(!empty($package->what_to_ask))
              <div class="tab-pane {{ $firstActiveSet === 'tab-what-ask' ? 'active' : '' }}" id="tab-what-ask">
                <h3 class="pane-title">Preparation required?</h3>
                <div class="pane-body">{!! ($package->what_to_ask) !!}</div>
              </div>
            @endif
          </div>
        </div>
      @endif

      {{-- Package FAQs Section --}}
      @if(!empty($package->faqs) && is_array($package->faqs) && count($package->faqs) > 0)
        <div class="package-faq-section">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2" style="font-size: 1.8rem; color: #0a2540;">Frequently Asked Questions</h2>
                <div style="width: 50px; height: 3px; background: #007bff; margin: 8px auto 14px; border-radius: 2px;"></div>
                <p class="text-muted" style="font-size: 0.95rem; max-width: 700px; margin: 0 auto;">
                    Find quick answers to common questions about this package.
                </p>
            </div>

            <div class="accordion custom-faq-accordion" id="packageFaqAccordion" style="max-width: 960px; margin: 0 auto;">
                @foreach($package->faqs as $index => $faq)
                    <div class="accordion-item mb-3" style="border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; background: #f8f9fa;">
                        <h2 class="accordion-header" id="faqHeading{{ $index }}">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} d-flex justify-content-between align-items-center w-100 px-4 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" style="background: #f8f9fa; color: #0a2540; font-weight: 700; font-size: 1rem; border: none; box-shadow: none;">
                                <span class="faq-question-text" style="font-size: 0.98rem; text-align: left;">{{ $index + 1 }}. {{ $faq['question'] ?? '' }}</span>
                                <i class="fa-solid fa-play faq-arrow-icon text-dark ms-2" style="font-size: 0.75rem; transition: transform 0.3s ease;"></i>
                            </button>
                        </h2>
                        <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#packageFaqAccordion">
                            <div class="accordion-body bg-white text-secondary lh-base px-4 py-3 border-top" style="font-size: 0.95rem; color: #495057;">
                                {!! nl2br(e($faq['answer'] ?? '')) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
      @endif

    </div>

    {{-- RIGHT SIDEBAR Promo/App download matching Single Test Page --}}
    <div class="test-sidebar">
      <div class="promo-sidebar-card">
        <div class="sidebar-icon">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2 class="sidebar-title">Book Diagnostics Anywhere</h2>
        <p class="sidebar-desc">Get safe, hygienically captured blood samples collected from the comfort of your home. Reports delivered to your device.</p>
        
        <div class="sidebar-action-box">
          <i class="fa-solid fa-phone"></i>
          <span class="sidebar-action-phone">Call Us: +91 915 898 0898</span>
        </div>

        <div class="sidebar-app-links">
          <a href="#" class="app-btn">
            <i class="fa-brands fa-google-play"></i>
            <div class="app-btn-text">
              <span class="app-btn-label">GET IT ON</span>
              <span class="app-btn-name">Google Play</span>
            </div>
          </a>
          <a href="#" class="app-btn">
            <i class="fa-brands fa-apple"></i>
            <div class="app-btn-text">
              <span class="app-btn-label">Download on the</span>
              <span class="app-btn-name">App Store</span>
            </div>
          </a>
        </div>
      </div>
    </div>

  </div>

</div>

{{-- STICKY CALLBACK ASSISTANCE BAR --}}
<div class="sticky-callback-bar">
  <div class="sticky-callback-inner">
    <div class="callback-info">
      <div class="callback-icon">
        <i class="fa-solid fa-headset"></i>
      </div>
      <div>
        <span class="callback-title">Need Help Booking Your Package?</span>
        <span class="callback-desc">Request a free call back and speak to our healthcare assistant.</span>
      </div>
    </div>
    <a href="{{ route('contact_us') }}" class="callback-btn">
      Request Callback
    </a>
  </div>
</div>
@endsection

@section('scripts')
<script>
  (function(){
    const CART_ADD_URL   = "{{ route('cart.add') }}";
    const CART_ITEMS_URL = "{{ route('cart.items') }}";
    const CART_URL       = "{{ route('cart.index') }}";
    const PACKAGE_ID     = {{ $package->id }};
    const CSRF           = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Tab switcher logic
    const triggers = document.querySelectorAll('.tab-trigger');
    const panes = document.querySelectorAll('.tab-pane');
    triggers.forEach(t => {
      t.addEventListener('click', () => {
        const target = t.getAttribute('data-tab');
        triggers.forEach(tr => tr.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        
        t.classList.add('active');
        const activePane = document.getElementById(target);
        if (activePane) activePane.classList.add('active');
      });
    });

    // Sticky Callback Bar show/hide on scroll
    window.addEventListener('scroll', () => {
      const bar = document.querySelector('.sticky-callback-bar');
      if (bar) {
        if (window.scrollY > 300) {
          bar.style.display = 'block';
        } else {
          bar.style.display = 'none';
        }
      }
    });

    // helpers to mutate buttons
    function convertToGoToCart(btn){
      if(!btn) return;
      btn.classList.remove('btn-add-cart','is-adding');
      if(!btn.classList.contains('btn-action-cart')) btn.classList.add('btn-action-cart');
      btn.classList.add('btn-go-cart');
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-shopping-cart me-1" aria-hidden="true"></i><span class="btn-text">Go to Cart</span>';
      btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); window.location.href = CART_URL; };
    }

    function convertToAdd(btn, id){
      if(!btn) return;
      btn.classList.remove('btn-go-cart','is-adding');
      if(!btn.classList.contains('btn-action-cart')) btn.classList.add('btn-action-cart');
      if(!btn.classList.contains('btn-add-cart'))    btn.classList.add('btn-add-cart');
      btn.disabled = false;
      btn.innerHTML = '<i class="fa fa-cart-plus me-1" aria-hidden="true"></i><span class="btn-text">Add to Cart</span>';
      btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); addToCart(e, 'package', id); };
    }

    function setAddingState(btn){
      if(!btn) return;
      btn.classList.add('is-adding');
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding…';
    }

    function unsetAddingState(btn, html){
      if(!btn) return;
      btn.classList.remove('is-adding');
      btn.disabled = false;
      btn.innerHTML = html;
    }

    // initial refresh
    async function refreshCartAction(){
      try{
        const res = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
          headers: { 'Accept':'application/json', 'Cache-Control':'no-cache', 'X-Requested-With':'XMLHttpRequest' },
          credentials:'same-origin'
        });
        if(!res.ok) return;
        const data = await res.json().catch(()=> ({}));
        const items = data.items || [];
        const inCart = items.some(i => String(i.item_type) === 'package' && Number(i.item_id) === Number(PACKAGE_ID));

        const container = document.getElementById('cart-action-' + PACKAGE_ID);
        if(!container) return;

        if(inCart){
          container.innerHTML = `
            <button type="button" class="btn btn-go-cart btn-action-cart" onclick="location.href='${CART_URL}'">
              <i class="fa fa-shopping-cart me-1" aria-hidden="true"></i> <span class="btn-text">Go to Cart</span>
            </button>
          `;
        } else {
          container.innerHTML = `
            <button type="button" id="cart-btn-${PACKAGE_ID}" class="btn btn-add-cart btn-action-cart" onclick="addToCart(event, 'package', ${PACKAGE_ID})">
              <i class="fa fa-cart-plus me-1" aria-hidden="true"></i> <span class="btn-text">Add to Cart</span>
            </button>
          `;
        }

        if (data.items) {
          const badge = document.querySelector('#cart-count-badge');
          if (badge) {
            const count = data.items.reduce((s, it) => s + (parseInt(it.quantity || 0) || 0), 0);
            badge.textContent = count;
          }
        }

      }catch(e){ console.warn('refreshCartAction failed:', e); }
    }

    // add to cart flow
    async function addToCart(event, itemType, itemId){
      event?.preventDefault?.();
      event?.stopPropagation?.();

      const btn = (event && event.target) ? event.target.closest('button') : document.getElementById('cart-btn-' + itemId);
      if(!btn) return;
      if(btn.classList.contains('btn-go-cart')){ window.location.href = CART_URL; return; }

      const original = btn.innerHTML;
      setAddingState(btn);

      try{
        const res = await fetch(CART_ADD_URL, {
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept':'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ item_type:itemType, item_id:itemId, quantity:1 }),
          cache:'no-store',
          credentials:'same-origin'
        });

        const ct = res.headers.get('content-type') || '';
        const isJson = ct.includes('application/json');
        const data = isJson ? await res.json().catch(()=> ({})) : {};

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

          setTimeout(refreshCartAction, 300);
        } else {
          alert((data && (data.message || data.error)) ? (data.message || data.error) : 'Failed to add to cart.');
          unsetAddingState(btn, original);
        }

      }catch(err){
        console.error('addToCart error', err);
        alert('Something went wrong while adding to cart.');
        unsetAddingState(btn, original);
      }
    }

    document.addEventListener('DOMContentLoaded', refreshCartAction, { once:true });
    window.addEventListener('pageshow', refreshCartAction);

    // Read more toggle
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.read-more-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const parent = this.closest('.package-desc');
                if (parent) {
                    parent.querySelector('.short-desc').style.display = 'none';
                    parent.querySelector('.full-desc').style.display = 'inline';
                    this.style.display = 'none';
                }
            });
        });
    });

    window.addToCart = addToCart;
  })();
</script>
@endsection
