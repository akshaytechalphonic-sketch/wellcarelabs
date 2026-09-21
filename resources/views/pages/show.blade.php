@extends('maindesign')

@section('title', $page->meta_title . ' - Page Details')
@section('meta_description', $page->meta_description . ' - Page Details')

@section('seo')
    {!! $page->meta_tags !!}
@endsection

@section('content')
@php
  $bannerUrl = $page->banner_image ? asset('storage/' . $page->banner_image) : asset('Front_end/assets/img/bg_image_1.jpg');
@endphp
<div class="page-banner overlay-dark bg-image" style="background-image: url({{ $bannerUrl }}); padding: 80px 0 60px 0;">
    <div class="banner-section">
        <div class="container text-center wow fadeInUp">
            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb breadcrumb-dark bg-transparent justify-content-center py-0 mb-2" style="font-size: 0.95rem;">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white opacity-75">Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </nav>
            <h1 class="font-weight-bold text-white mb-0" style="font-size: 2.5rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                {{ $page->title }}
            </h1>
        </div>
    </div>
</div>

<div class="page-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-lg p-4 p-md-5 bg-white mb-5" style="border-radius: 12px; margin-top: -40px; position: relative; z-index: 10;">
                    
                    {{-- Page Body Content --}}
                    <div class="page-custom-content ck-content">
                        @if($page->sections->count() > 0)
                            @foreach($page->sections as $section)
                                @if(view()->exists('partials.sections.' . $section->type))
                                    @include('partials.sections.' . $section->type, ['section' => $section])
                                @else
                                    @include('partials.sections.text', ['section' => $section])
                                @endif
                            @endforeach
                        @else
                            {!! $page->content !!}
                        @endif
                    </div>

                </div>

                {{-- Associated Packages --}}
                @if(isset($page->packages) && $page->packages->count() > 0)
                <div class="mt-5 mb-5">
                    <h3 class="text-center font-weight-bold mb-4" style="color: #0a2540; font-weight: 800;">Featured Health Checkup Packages</h3>
                    <div class="row justify-content-center g-4">
                        @foreach($page->packages as $pkg)
                            @php
                                $pkgBanner = $pkg->banner ? asset('storage/' . ltrim($pkg->banner, '/')) : asset('Front_end/assets/img/blog/default-package.jpg');
                                $mrp = (float)($pkg->mrp ?? 0);
                                $disc = (float)($pkg->discounted_price ?? 0);
                                $displayPrice = $disc ?: ($pkg->price ?: null);
                            @endphp
                            <div class="col-12 col-md-6 col-lg-4 d-flex">
                                <div class="card h-100 shadow-sm border-0 w-100 p-3" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #fff; transition: transform 0.2s;">
                                    <img src="{{ $pkgBanner }}" alt="{{ $pkg->title }}" class="img-fluid rounded-3 mb-3" style="height: 180px; width: 100%; object-fit: cover;">
                                    <h5 class="fw-bold mb-2">
                                        <a href="{{ route('packages.show', ['package' => $pkg->slug ?: $pkg->id]) }}" class="text-decoration-none text-dark" style="font-weight: 700;">
                                            {{ $pkg->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-3 flex-grow-1">{{ \Illuminate\Support\Str::limit(strip_tags($pkg->short_description ?: $pkg->content), 90) }}</p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div>
                                            @if($displayPrice)
                                                <span class="fw-bold fs-5 text-primary" style="font-weight: 800; color: #0047ff;">₹{{ number_format($displayPrice, 0) }}</span>
                                                @if($mrp > $displayPrice)
                                                    <span class="text-muted text-decoration-line-through small ms-1"><s>₹{{ number_format($mrp, 0) }}</s></span>
                                                @endif
                                            @endif
                                        </div>
                                        <a href="{{ route('packages.show', ['package' => $pkg->slug ?: $pkg->id]) }}" class="btn btn-primary btn-sm rounded-pill px-3" style="background: #0047ff; border: none; font-weight: 600;">
                                            View Package
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Associated FAQs Accordion (Placed at very end of page - Matching user design screenshot) --}}
                @if(isset($page->faqs) && $page->faqs->count() > 0)
                <div class="mt-5 mb-5 p-4 p-md-5 bg-white rounded-4 shadow-sm" style="border-radius: 20px !important; border: 1px solid #e2e8f0;">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold mb-2" style="font-size: 2.2rem; color: #0a2540; font-weight: 800;">Frequently Asked Questions</h2>
                        <div style="width: 55px; height: 3px; background: #007bff; margin: 10px auto 16px; border-radius: 2px;"></div>
                        <p class="text-muted" style="font-size: 0.95rem; max-width: 700px; margin: 0 auto;">
                            Find quick answers to common questions about test booking, home sample collection, reports and payments at Wellcare Labs
                        </p>
                    </div>

                    <div class="accordion custom-faq-accordion mt-4" id="dynamicPageFaqAccordion" style="max-width: 960px; margin: 0 auto;">
                        @foreach($page->faqs as $index => $faq)
                            <div class="accordion-item mb-3" style="border: 1px solid #e9ecef; border-radius: 12px; overflow: hidden; background: #f8f9fa;">
                                <h2 class="accordion-header" id="faqHeading{{ $faq->id }}">
                                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }} d-flex justify-content-between align-items-center w-100 px-4 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" style="background: #f8f9fa; color: #0a2540; font-weight: 700; font-size: 1rem; border: none; box-shadow: none;">
                                        <span class="faq-question-text" style="font-size: 0.98rem;">{{ $index + 1 }}. {{ $faq->question }}</span>
                                        <i class="fa-solid fa-play faq-arrow-icon text-dark ms-2" style="font-size: 0.75rem; transition: transform 0.3s ease;"></i>
                                    </button>
                                </h2>
                                <div id="faqCollapse{{ $faq->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#dynamicPageFaqAccordion">
                                    <div class="accordion-body bg-white text-secondary lh-base px-4 py-3 border-top" style="font-size: 0.95rem; color: #495057;">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<style>
    /* Custom FAQ Accordion matching user design screenshot */
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

            </div>
        </div>
    </div>
</div>

<style>
    /* Styling rules for CKEditor parsed content */
    .page-custom-content {
        color: #334155;
        font-size: 1.05rem;
        line-height: 1.75;
    }
    .page-custom-content h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 1.75rem;
        margin-bottom: 0.75rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 0.5rem;
    }
    .page-custom-content h3 {
        font-size: 1.4rem;
        font-weight: 600;
        color: #1e293b;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }
    .page-custom-content h4 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #334155;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
    }
    .page-custom-content p {
        margin-bottom: 1.25rem;
    }
    .page-custom-content ul, 
    .page-custom-content ol {
        margin-bottom: 1.25rem;
        padding-left: 1.5rem;
    }
    .page-custom-content li {
        margin-bottom: 0.5rem;
    }
    .page-custom-content blockquote {
        border-left: 4px solid #00d2b4;
        background-color: #f8fafc;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        border-radius: 0 8px 8px 0;
        color: #475569;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const CART_ADD_URL = "{{ route('cart.add') }}";
        const CART_ITEMS_URL = "{{ route('cart.items') }}";
        const CART_URL = "{{ route('cart.index') }}";

        let currentCartTestIds = new Set();

        async function loadCartItems() {
            try {
                const res = await fetch(CART_ITEMS_URL);
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.success && Array.isArray(data.items)) {
                    currentCartTestIds = new Set(
                        data.items
                            .filter(item => item.item_type === 'test')
                            .map(item => Number(item.item_id))
                    );
                    refreshCartButtons();
                }
            } catch (e) {
                console.error('loadCartItems error', e);
            }
        }

        function refreshCartButtons() {
            document.querySelectorAll('.wc-cart-btn.add-to-cart').forEach(btn => {
                const id = Number(btn.dataset.itemId || 0);
                if (id && currentCartTestIds.has(id)) {
                    convertButtonToGoToCart(btn);
                }
            });
        }

        function convertButtonToGoToCart(button) {
            if (!button) return;
            button.classList.remove('add-to-cart');
            button.classList.add('go-to-cart', 'btn-go-cart');
            button.setAttribute('aria-label', 'Test is in cart. Go to cart');
            button.innerHTML = '<i class="fa fa-shopping-cart me-1"></i> Go to Cart';
            button.onclick = () => window.location.href = CART_URL;
            button.style.background = 'linear-gradient(90deg, #10b981, #059669)'; // Green gradient for "Go to Cart"
        }

        window.addToCart = async function(event, type, id) {
            if (event && typeof event.preventDefault === 'function') {
                event.preventDefault();
            }
            const button = event ? event.target.closest('button') : null;
            if (!button) return;

            if (button.classList.contains('go-to-cart') || button.classList.contains('btn-go-cart')) {
                window.location.href = CART_URL;
                return;
            }
            if (button.dataset.processing === '1') return;

            button.dataset.processing = '1';
            button.disabled = true;
            const originalHtml = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Adding...';

            const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

            try {
                const res = await fetch(CART_ADD_URL, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_type: type,
                        item_id: id,
                        quantity: 1
                    })
                });

                const data = await res.json().catch(() => ({}));
                if (res.ok && data.success) {
                    convertButtonToGoToCart(button);
                    if (typeof window.refreshCartBadge === 'function') {
                        await window.refreshCartBadge();
                    }
                } else {
                    alert(data.message || 'Failed to add to cart.');
                    button.innerHTML = originalHtml;
                }
            } catch (err) {
                console.error('addToCart error', err);
                alert('Error adding to cart.');
                button.innerHTML = originalHtml;
            } finally {
                button.disabled = false;
                delete button.dataset.processing;
            }
        };

        // Load active items on start
        loadCartItems();
    });
</script>
@endsection
