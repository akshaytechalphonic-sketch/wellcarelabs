{{-- resources/views/services/show.blade.php --}}
@extends('maindesign')

@section('title', $labTest->meta_title )
@section('meta_description', $labTest->meta_description)

@section('seo')
    {!! $labTest->meta_tags !!}
@endsection

@php
  // Pricing logic
  $mrp = (float)($labTest->mrp ?? 0);
  $discounted = (float)($labTest->discounted_price ?? 0);
  $price = (float)($labTest->price ?? 0);
  $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);
  $savePercent = ($mrp > 0 && $displayPrice) ? round((($mrp - $displayPrice) / $mrp) * 100) : 0;
  $savingAmount = ($mrp > 0 && $displayPrice && $mrp > $displayPrice) ? ($mrp - $displayPrice) : 0;
@endphp

@push('head')
<!-- FontAwesome and Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<style>
/* Page Layout */
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
.test-header-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    padding: 28px;
    margin-bottom: 24px;
    position: relative;
}

.test-title-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
}

.test-badge-group {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}
.parameters-badge {
    background: #eef2ff;
    color: #4f46e5;
    font-size: 0.8rem;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.test-title {
    font-size: 2rem;
    color: #ff2b6d;
    font-weight: 800;
    margin: 0;
    line-height: 1.3;
}

.share-btn-wrap button {
    background: #f1f5f9;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.share-btn-wrap button:hover {
    background: #ff2b6d;
    color: #ffffff;
}

/* Price & Action Row */
.price-action-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 18px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 24px;
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
    font-size: 1.8rem;
    font-weight: 900;
    color: #1e3a8a;
}
.test-mrp-price {
    color: #94a3b8;
    text-decoration: line-through;
    font-size: 1.1rem;
    font-weight: 600;
}
.discount-badge {
    background: #dcfce7;
    color: #15803d;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}
.savings-message {
    color: #0d6efd;
    font-weight: 700;
    font-size: 0.95rem;
    margin-top: 4px;
}

/* Key Details Grid */
.meta-indicators-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 24px;
}
.indicator-tile {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.indicator-icon {
    background: #ffffff;
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0d6efd;
    font-size: 1.25rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
}
.indicator-info {
    display: flex;
    flex-direction: column;
}
.indicator-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.indicator-value {
    font-size: 0.95rem;
    color: #1e293b;
    font-weight: 700;
}

/* Detail Block Section */
.detail-block-section {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
    padding: 28px;
    margin-bottom: 24px;
}
.section-headline {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 16px;
    position: relative;
    padding-bottom: 8px;
}
.section-headline::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 3px;
    background: #ff2b6d;
    border-radius: 2px;
}
.detail-text-content {
    color: #475569;
    line-height: 1.7;
    font-size: 1rem;
}

/* Interactive Tabs Section */
.test-tabs-container {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, 0.8);
    /* box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04); */
    padding: 28px;
    margin-bottom: 24px;
}
.tabs-navigation {
    display: flex;
    border-bottom: 2px solid #f1f5f9;
    margin-bottom: 24px;
    overflow-x: auto;
    gap: 8px;
    scrollbar-width: none; /* Hide scrollbar for clean UI */
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

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Sidebar Styling */
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

/* Cart Action Button */
.btn-action-cart {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 28px;
    font-size: 1rem;
    border-radius: 12px;
    font-weight: 700;
    min-height: 48px;
    min-width: 170px;
    /* border: 0; */
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
}
.btn-go-cart:hover {
    background: #8ec33b !important;
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
    display: none; /* Controlled by JS scroll trigger */
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
    .meta-indicators-grid {
        grid-template-columns: 1fr;
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
      <li class="breadcrumb-item"><a href="{{ route('services') }}">Book Test</a></li>
      <li class="breadcrumb-item active">{{ $labTest->test_name }}</li>
    </ol>
  </div>

  <div class="test-layout">
    
    {{-- LEFT MAIN COLUMN --}}
    <div class="test-main-content">
      
      {{-- HEADER BLOCK --}}
      <div class="test-header-card">
        <div class="test-title-section">
          <div>
            <h1 class="test-title">{{ $labTest->test_name }}</h1>
            <div class="test-badge-group">
              <span class="parameters-badge">
                <i class="fa-solid fa-list-check"></i>
                Includes {{ $labTest->parameters_count ?: 1 }} Parameter{{ ($labTest->parameters_count ?: 1) > 1 ? 's' : '' }}
              </span>
            </div>
          </div>
          <div class="share-btn-wrap">
            <button type="button" onclick="shareTest()" title="Share Link" aria-label="Share">
              <i class="fa-solid fa-share-nodes"></i>
            </button>
          </div>
        </div>

        {{-- Pricing Row inside Header Block --}}
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

          {{-- Add to Cart Action --}}
          <div id="cart-action-{{ $labTest->id }}">
            <button type="button"
                    id="cart-btn-{{ $labTest->id }}"
                    class="btn btn-add-cart btn-action-cart"
                    onclick="addToCart(event, 'test', {{ $labTest->id }})">
              <i class="fa fa-cart-plus me-1" aria-hidden="true"></i>
              <span class="btn-text">Add to Cart</span>
            </button>
          </div>
        </div>

        {{-- Metadata indicators grid --}}
        <div class="meta-indicators-grid">
          
          {{-- Sample Type --}}
          <div class="indicator-tile">
            <div class="indicator-icon">
              <i class="fa-solid fa-droplet"></i>
            </div>
            <div class="indicator-info">
              <span class="indicator-label">Sample Type</span>
              <span class="indicator-value">{{ $labTest->sample_type ?: 'Blood' }}</span>
            </div>
          </div>

          {{-- Test Code --}}
          <div class="indicator-tile">
            <div class="indicator-icon">
              <i class="fa-solid fa-barcode"></i>
            </div>
            <div class="indicator-info">
              <span class="indicator-label">Test Code</span>
              <span class="indicator-value">{{ $labTest->test_code ?: 'WCL-' . $labTest->id }}</span>
            </div>
          </div>

          {{-- Fasting --}}
          <div class="indicator-tile">
            <div class="indicator-icon">
              <i class="fa-solid fa-clock"></i>
            </div>
            <div class="indicator-info">
              <span class="indicator-label">Fasting</span>
              <span class="indicator-value">{{ $labTest->fasting ?: 'No' }}</span>
            </div>
          </div>

        </div>

      </div>

      {{-- DESCRIPTION SECTION --}}
      <div class="detail-block-section">
        <h2 class="section-headline">About the Test</h2>
        <div class="detail-text-content">
          @if(!empty($labTest->description))
            {!! ($labTest->description) !!}
          @else
            No description available for this health checkup test.
          @endif
        </div>
      </div>

      {{-- INTERACTIVE TABS --}}
      <div class="test-tabs-container">
        <div class="tabs-navigation">
          <button type="button" class="tab-trigger active" data-tab="why_done">Why is the Test?</button>
          <button type="button" class="tab-trigger" data-tab="who_should">Who should take it ?</button>
          <button type="button" class="tab-trigger" data-tab="how_read">Why is it done?</button>
          <button type="button" class="tab-trigger" data-tab="what_ask">Preparation required?</button>
        </div>

        <div class="tabs-content">
          
          <div class="tab-pane active" id="why_done">
            <h3 class="pane-title">Why is the Test ?</h3>
            <div class="pane-body">
              {!! !empty($labTest->why_done) ? $labTest->why_done : 'Please consult with our health advisor or reference medical guidelines regarding the clinical objectives of this test.' !!}
            </div>
          </div>

          <div class="tab-pane" id="who_should">
            <h3 class="pane-title">Who should take it ?</h3>
            <div class="pane-body">
              {!! !empty($labTest->who_should_test) ? $labTest->who_should_test : 'This test is typically recommended as part of routine health evaluations, preventive screenings, or as advised by your doctor.' !!}
            </div>
          </div>

          <div class="tab-pane" id="how_read">
            <h3 class="pane-title">Why is it done ?</h3>
            <div class="pane-body">
              {!! !empty($labTest->how_to_read) ? $labTest->how_to_read : 'Results must be reviewed against laboratory reference ranges and evaluated by your practitioner in the context of your overall clinical history.' !!}
            </div>
          </div>

          <div class="tab-pane" id="what_ask">
            <h3 class="pane-title">Preparation required?</h3>
            <div class="pane-body">
              {!! !empty($labTest->what_to_ask) ? $labTest->what_to_ask : 'Ask your healthcare provider about what the test results indicate, what next steps are required, and if any treatment or lifestyle adjustments are recommended.' !!}
            </div>
          </div>

        </div>
      </div>

    </div>

    {{-- RIGHT SIDEBAR Promo/App download --}}
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
        <span class="callback-title">Need Help Booking Your Test?</span>
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
    const TEST_ID        = {{ $labTest->id }};
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

    // Share link helper
    window.shareTest = function() {
      if (navigator.share) {
        navigator.share({
          title: "{{ $labTest->test_name }} Details",
          url: window.location.href
        }).catch(err => console.log('Share canceled', err));
      } else {
        // Copy to clipboard fallback
        navigator.clipboard.writeText(window.location.href)
          .then(() => alert('Link copied to clipboard!'))
          .catch(() => alert('Failed to copy link.'));
      }
    }

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
      btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); addToCart(e, 'test', id); };
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
        const inCart = items.some(i => String(i.item_type) === 'test' && Number(i.item_id) === Number(TEST_ID));

        const container = document.getElementById('cart-action-' + TEST_ID);
        if(!container) return;

        if(inCart){
          container.innerHTML = `
            <button type="button" class="btn btn-go-cart btn-action-cart" onclick="location.href='${CART_URL}'">
              <i class="fa fa-shopping-cart me-1" aria-hidden="true"></i> <span class="btn-text">Go to Cart</span>
            </button>
          `;
        } else {
          container.innerHTML = `
            <button type="button" id="cart-btn-${TEST_ID}" class="btn btn-add-cart btn-action-cart" onclick="addToCart(event, 'test', ${TEST_ID})">
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
    window.addToCart = addToCart;
  })();
</script>
@endsection
