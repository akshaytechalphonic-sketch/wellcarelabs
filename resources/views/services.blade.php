{{-- resources/views/services.blade.php --}}


@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Wellcare Labs Services – Diagnostic & Health Testing Solutions in India')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Discover comprehensive diagnostic and lab services at Wellcare Labs, including advanced blood tests, health screenings, preventive diagnostics, and accurate reports backed by expert professionals and modern technology.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif

@endsection


@section('content')
    <section class="page-services" style="background:#f0f4f8;">
        <div style="text-align:center;;margin-bottom:30px;">
            <h1
                style="font-size:2.2rem;font-weight:700;color:#0a2540;position:relative;display:inline-block; margin-top:20px">
                Wellcare Top <span style="color:#0d6efd;">Book Health Test
                </span>
                <div
                    style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;background:linear-gradient(90deg,#0047ff,#00ccff);">
                </div>
            </h1>
            <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
                Discover exclusive diagnostic tests designed for accuracy and trust.
            </p>






            <!-- Centered modern search bar (page-specific classes: services-*) -->
            <div class="services-search-bar-container text-center my-4" style="position:relative;">
                <form method="GET" action="{{ route('services') }}" class="services-search-bar mx-auto" role="search"
                    id="servicesLiveSearchForm">
                    <button type="submit" class="services-search-icon-btn" aria-label="Search">
                        <i class="fa fa-search"></i>
                    </button>

                    <input type="search" name="q" value="{{ request('q') }}" class="services-search-input"
                        id="servicesLiveSearchInput" placeholder="Search Tests, e.g. CBC, Thyroid..." autocomplete="off"
                        aria-label="Search tests">

                    <!-- Clear / cancel button INSIDE the same search bar (non-submit) -->
                    <button type="button" id="servicesClearBtn" class="services-clear-btn" aria-label="Clear search"
                        title="Clear search" style="display: {{ request('q') ? 'inline-flex' : 'none' }};">
                        <i class="fa fa-times"></i>
                    </button>
                </form>
            </div>
        </div>

        @php
            use Illuminate\Support\Str;
            $charLimit = 100; // match Home
        @endphp

        <section class="lab-tests py-5">

            <!-- Optional result count centered below -->
            @if (request('q'))
                <a href="{{ route('services') }}" class="clear-btn">
                    <i class="fa fa-times"></i>
                </a>
            @endif

            <div class="container-fluid px-4">

                {{-- Mobile carousel wrapper (only wraps on mobile via CSS) --}}
                <div class="tests-carousel-wrap" id="tests-carousel-wrap">
                    {{-- Mobile prev/next arrows (hidden on desktop via CSS) --}}
                    <button class="tests-carousel-arrow tests-carousel-prev" id="testsCarouselPrev" aria-label="Previous tests" type="button">
                        <i class="fa fa-chevron-left"></i>
                    </button>
                    <button class="tests-carousel-arrow tests-carousel-next" id="testsCarouselNext" aria-label="Next tests" type="button">
                        <i class="fa fa-chevron-right"></i>
                    </button>

                <!-- same row logic as Home: center when 1 or 2 results -->
                <div class="row g-4 {{ $tests->count() <= 2 ? 'justify-content-center' : 'justify-content-start' }}"
                    id="test-cards-row">

                    @forelse($tests as $t)
                        @php
                            // --- Description (same as Home) ---
                            $rawDescription = trim(strip_tags($t->short_description ?: $t->description ?? ''));
                            $hasDesc = $rawDescription !== '';
                            $isLong = $hasDesc && strlen($rawDescription) > $charLimit;
                            $previewCore = $hasDesc ? Str::limit($rawDescription, $charLimit, '') : '';

                            // --- Pricing (same as Home) ---
                            $mrp = (float) ($t->mrp ?? 0);
                            $disc = (float) ($t->discounted_price ?? 0);
                            $priceField = (float) ($t->price ?? 0);
                            $displayPrice = $disc ?: ($priceField ?: ($mrp ?: null));
                            $savingAmount =
                                $mrp > 0 && $displayPrice && $mrp > $displayPrice ? $mrp - $displayPrice : 0;
                            $percentOff = $savingAmount > 0 ? (int) round(($savingAmount / $mrp) * 100) : 0;
                        @endphp

                        <!-- same column shell as Home (custom grid via CSS) -->
                        <div class="col-card">
                            <div class="card package-style-card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column p-3" data-test-id="{{ $t->id }}"
                                    data-test-title="{{ e($t->test_name) }}" data-test-desc="{{ e($rawDescription) }}"
                                    data-test-sell="{{ $displayPrice ?: 0 }}" data-test-mrp="{{ $mrp ?: 0 }}"
                                    data-test-off="{{ $percentOff }}" data-test-save="{{ $savingAmount }}">

                                    <h5 class="card-title package-title mb-2 text-truncate" title="{{ $t->test_name }}">
                                        <a href="{{ route('services.show', ['labTest' => $t->slug]) }}" class="text-decoration-none text-reset">
                                            {{ $t->test_name }}
                                        </a>
                                    </h5>

                                    <!-- description: 3-line clamp + inline Read more (same as Home) -->
                                    <div class="package-desc-wrapper mb-2">
                                        @if ($hasDesc)
                                            <p class="package-desc">
                                                {!! nl2br(e($previewCore)) !!}
                                            </p>

                                            @if ($isLong)
                                                <a href="{{ route('services.show', ['labTest' => $t->slug]) }}" class="readmore-inline">
                                                    Read more
                                                </a>
                                            @endif
                                        @else
                                            <p class="package-desc">&nbsp;</p>
                                        @endif

                                    </div>

                                    <div
                                        class="card-footer-block mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                        <!-- inline price row (same as Home) -->
                                        <div class="price-wrap d-flex align-items-center gap-2 flex-wrap">
                                            @if ($displayPrice)
                                                <span
                                                    class="disc-price">₹{{ number_format($displayPrice, 0, '.', ',') }}</span>
                                                @if ($mrp > $displayPrice && $percentOff > 0)
                                                    <span
                                                        class="text-small"><s>₹{{ number_format($mrp, 0, '.', ',') }}</s></span>
                                                    <span class="percent-off">{{ $percentOff }}% OFF</span>
                                                @endif
                                            @else
                                                <span class="disc-price">Contact</span>
                                            @endif
                                        </div>

                                        <!-- Add to Cart (same classes as Home; pink → green states via JS/CSS) -->
                                        <div>
                                            <button type="button" id="cart-btn-{{ $t->id }}"
                                                data-item-id="{{ $t->id }}"
                                                class="wc-cart-btn add-to-cart text-nowrap"
                                                onclick="addToCart(event, 'test', {{ $t->id }})"
                                                aria-label="Add {{ $t->test_name }} to cart">
                                                <i class="fa fa-cart-plus me-1"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>

                                    @if ($mrp > $displayPrice && $savingAmount > 0)
                                        <div class="you-save-text">You save
                                            ₹{{ number_format($savingAmount, 0, '.', ',') }}</div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <!-- 👇 Add this image line right here -->
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486759.png" alt="No results"
                                width="120" class="mb-3 opacity-75">

                            @if (request('q'))
                                <h5 class="fw-bold text-muted">No tests found for “{{ request('q') }}”</h5>
                                <p class="text-secondary mb-3">Try searching with a different name or keyword.</p>
                                <a href="{{ route('tests') }}" class="btn btn-outline-primary rounded-pill px-4">
                                    Show All Tests
                                </a>
                            @else
                                <h5 class="fw-bold text-muted">No tests available</h5>
                            @endif


                        </div>

                    @endempty

                    {{-- Pagination --}}
                    @if ($tests->hasPages())
                        <div class="pagination-outer mt-4 mb-4">
                            <div class="pagination-inner">
                                {{ $tests->withQueryString()->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                        <style>
                            .pagination-outer {
                                width: 100%;
                                display: flex;
                                justify-content: center;
                            }

                            .pagination-inner {
                                display: inline-flex;
                            }

                            .pagination-inner nav {
                                display: flex;
                                justify-content: center;
                            }

                            .pagination-inner .pagination {
                                justify-content: center;
                            }
                        </style>
                    @endif



                </div>{{-- /.tests-carousel-wrap --}}


    </section>
</section>

<!-- ===== Same modal as Home (shared JS expects these IDs) ===== -->
<div class="modal fade" id="testDetailsModal" tabindex="-1" aria-labelledby="testDetailsTitle" aria-modal="true"
    role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="testDetailsTitle">Test details</h5>
                <button type="button" class="modal-cancel-icon" data-bs-dismiss="modal" aria-label="Close"
                    fdprocessedid="jr1p3l">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>

            <div class="modal-body">
                <p id="testDetailsDesc" class="mb-0"></p>
            </div>

            <div class="wc-modal-footer border-top px-3 py-3 d-flex align-items-center">
                <!-- PRICE (LEFT SIDE) -->
                <div class="price-box d-flex flex-wrap align-items-center gap-2">
                    <span class="fw-bold" id="mSell">₹0</span>
                    <small class="text-muted text-decoration-line-through" id="mMrp">₹0</small>
                    <span class="badge" id="mOff">-0%OFF</span>
                    <span class="you-save-text" id="mSave">You save ₹0</span>
                </div>

                <!-- BUTTONS (RIGHT SIDE CORNER) -->
                <div class="action-buttons ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="wc-cart-btn add-to-cart text-nowrap" id="mAddBtn">Add to
                        Cart</button>
                </div>
            </div>

        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('scripts')
<script>
    let currentCartTestIds = new Set();

    // ===== Same endpoints as Home =====
    const CART_ADD_URL = "{{ route('cart.add') }}";
    const CART_ITEMS_URL = "{{ route('cart.items') }}";
    const CART_URL = "{{ route('cart.index') }}";

    // ----- Add to Cart (same as Home, plus twin sync for modal/card) -----
    async function addToCart(event, type, id) {
        event.preventDefault();
        const button = event.target.closest('button');
        if (!button) return;

        // Already converted? go to cart.
        if (button.classList.contains('go-to-cart') || button.classList.contains('btn-go-cart')) {
            window.location.href = CART_URL;
            return;
        }
        if (button.dataset.processing === '1') return;

        button.dataset.processing = '1';
        button.disabled = true;
        const originalHtml = button.innerHTML;
        button.innerHTML =
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';

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
                // 1) Flip the clicked button
                convertButtonToGoToCart(button);

                // 2) Flip the matching "twin" button(s) to keep UI in sync
                if (type === 'test') {
                    // Card twin (when added from modal)
                    const twinCard = document.getElementById(`cart-btn-${id}`);
                    if (twinCard && twinCard !== button) {
                        convertButtonToGoToCart(twinCard);
                    }
                    // Modal twin (when added from card and modal is open)
                    const mBtn = document.getElementById('mAddBtn');
                    const modalShowingThisId =
                        mBtn && Number(mBtn.dataset.testId || 0) === id &&
                        document.querySelector('#testDetailsModal.show');
                    if (modalShowingThisId && mBtn !== button) {
                        convertButtonToGoToCart(mBtn);
                    }
                }

                // 3) Update badge immediately
                updateCartBadgeFromResponse(data);

                // 4) Keep global set and do a soft refresh for server truth
                if (currentCartTestIds instanceof Set) {
                    currentCartTestIds.add(Number(id));
                }
                setTimeout(() => {
                    try {
                        refreshCartButtons();
                    } catch (e) {}
                }, 0);
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
    }

    // ----- Refresh buttons (same as Home) -----
    async function refreshCartButtons() {
        try {
            const r = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
                credentials: 'same-origin'
            });
            const json = await r.json().catch(() => ({
                items: []
            }));
            updateCartBadgeFromResponse(json);

            // Build global set
            currentCartTestIds = new Set((json.items || [])
                .filter(i => i.item_type === 'test')
                .map(i => Number(i.item_id)));

            // Cards
            document.querySelectorAll('button[id^="cart-btn-"]').forEach(btn => {
                const id = Number(btn.dataset.itemId || btn.getAttribute('data-item-id'));
                if (!id) return;
                if (currentCartTestIds.has(id)) convertButtonToGoToCart(btn);
                else revertButtonToAddToCart(btn, id);
            });

            // Modal (if open) — ensure it mirrors state
            const mBtn = document.getElementById('mAddBtn');
            const openModal = document.querySelector('#testDetailsModal.show');
            if (mBtn && openModal) {
                const mid = Number(mBtn.dataset.testId || 0);
                setModalAddBtnState(mid);
            }
        } catch (err) {
            console.error('refreshCartButtons error', err);
        }
    }

    function updateCartBadgeFromResponse(json) {
        let count = null;
        if (json && typeof json.count !== 'undefined' && json.count !== null) {
            count = Number(json.count);
        } else if (json && Array.isArray(json.items)) {
            count = json.items.length;
        }
        if (count !== null) {
            const badge = document.getElementById('cart-count-badge') || document.getElementById('cart-count');
            if (badge) {
                badge.textContent = count;
            }
        }
    }

    // ----- Button state swap: add → go (apply slider's .btn-go-cart too) -----
    function convertButtonToGoToCart(button) {
        if (!button) return;
        button.classList.remove('add-to-cart');
        button.classList.add('go-to-cart', 'btn-go-cart'); // slider hook
        button.setAttribute('aria-label', 'Test is in cart. Go to cart');
        button.innerHTML = '<i class="fa fa-shopping-cart me-1"></i> Go to Cart';
        button.onclick = () => window.location.href = CART_URL;
    }

    function revertButtonToAddToCart(button, id) {
        if (!button) return;
        button.classList.remove('go-to-cart', 'btn-go-cart');
        button.classList.add('add-to-cart');
        button.setAttribute('aria-label', 'Add test to cart');
        button.innerHTML = '<i class="fa fa-cart-plus me-1"></i> Add to Cart';
        button.onclick = (ev) => addToCart(ev, 'test', id);
        if (!button.dataset.itemId && id) button.dataset.itemId = id;
    }

    function setModalAddBtnState(id) {
        const mBtn = document.getElementById('mAddBtn');
        if (!mBtn || !id) return;
        mBtn.dataset.testId = id; // remember which test the modal is showing
        if (currentCartTestIds.has(id)) {
            convertButtonToGoToCart(mBtn);
        } else {
            revertButtonToAddToCart(mBtn, id);
        }
    }

    // ----- Modal (Read more) -----
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-open-modal="1"]');
        if (!trigger) return;

        const cardBody = trigger.closest('.card-body');
        if (!cardBody) return;

        const id = Number(cardBody.dataset.testId || 0);
        const title = cardBody.dataset.testTitle || 'Details';
        const desc = cardBody.dataset.testDesc || '';
        const sell = Number(cardBody.dataset.testSell || 0);
        const mrp = Number(cardBody.dataset.testMrp || 0);
        const off = Number(cardBody.dataset.testOff || 0);
        const save = Number(cardBody.dataset.testSave || Math.max(mrp - sell, 0));

        // Title
        const titleEl = document.getElementById('testDetailsTitle');
        titleEl.textContent = title;

        // Description
        const descEl = document.getElementById('testDetailsDesc');
        descEl.innerHTML = (desc || '').replace(/\n/g, '<br>');

        // Prices
        document.getElementById('mSell').textContent = '₹' + sell.toLocaleString('en-IN');

        const mrpEl = document.getElementById('mMrp');
        const offEl = document.getElementById('mOff');
        const saveEl = document.getElementById('mSave');

        if (mrp > 0 && sell > 0 && mrp > sell) {
            mrpEl.textContent = '₹' + mrp.toLocaleString('en-IN');
            mrpEl.style.display = '';
            offEl.textContent = `${off}%`;
            offEl.style.display = '';
            saveEl.textContent = `You save ₹${save.toLocaleString('en-IN')}`;
            saveEl.style.display = '';
        } else {
            mrpEl.style.display = 'none';
            offEl.style.display = 'none';
            saveEl.style.display = 'none';
        }

        // Ensure modal button state matches current cart for this test
        setModalAddBtnState(id);

        const modalEl = document.getElementById('testDetailsModal');
        const modal = (window.bootstrap && bootstrap.Modal) ?
            bootstrap.Modal.getOrCreateInstance(modalEl) :
            null;
        if (modal) modal.show();
        else modalEl.classList.add('show');
    });

    // height equalizer (same as Home)
    function matchCardHeights() {
        const cards = document.querySelectorAll('.package-style-card');
        if (!cards || cards.length === 0) return;
        let maxHeight = 0;
        cards.forEach(c => {
            c.style.height = 'auto';
            maxHeight = Math.max(maxHeight, c.offsetHeight);
        });
        cards.forEach(c => c.style.height = maxHeight + 'px');
    }

    document.addEventListener('DOMContentLoaded', () => {
        refreshCartButtons();
        setTimeout(matchCardHeights, 60);
    });
    // Also run after full page load to catch late reflows (fonts/images)
    window.addEventListener('load', () => setTimeout(matchCardHeights, 20));

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            refreshCartButtons();
            setTimeout(matchCardHeights, 40);
        }
    });
    window.addEventListener('pageshow', () => {
        refreshCartButtons();
        setTimeout(matchCardHeights, 40);
    });
    window.addEventListener('storage', (ev) => {
        if (!ev.key) return;
        if (ev.key === 'cart:updated') refreshCartButtons();
    });
    window.addEventListener('resize', () => setTimeout(matchCardHeights, 60));

    function debounce(fn, wait) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // ----- Page-specific live search (uses services* IDs/classes to avoid global collision) -----
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('servicesLiveSearchInput');
        const form = document.getElementById('servicesLiveSearchForm');
        const resultsGrid = document.getElementById('test-cards-row');
        const paginationRow = document.querySelector('.row.mt-4');
        const clearBtn = document.getElementById('servicesClearBtn');
        let typingTimer;

        // Helper: debounce (delay) typing
        function debounceLocal(func, delay) {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(func, delay);
        }

        // show/hide clear button based on input
        function updateClearVisibility() {
            if (!clearBtn || !input) return;
            clearBtn.style.display = input.value.trim() ? 'inline-flex' : 'none';
        }

        if (input) {
            input.addEventListener('input', function() {
                const query = input.value.trim();
                updateClearVisibility();
                debounceLocal(() => performSearch(query), 350); // wait 350ms after typing stops
            });
        }

        // clear button behavior (non-submit)
        if (clearBtn) {
            clearBtn.addEventListener('click', function(ev) {
                ev.preventDefault();
                if (!input) return;
                input.value = '';
                updateClearVisibility();

                // trigger live search refresh
                if (typeof performSearch === 'function') performSearch('');
                else if (form) form.submit();
            });
        }

        async function performSearch(query) {
            try {
                const url = new URL("{{ route('services') }}", window.location.origin);
                if (query) url.searchParams.set('q', query);
                else url.searchParams.delete('q');

                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                });

                if (!res.ok) return;
                const html = await res.text();

                // Parse returned HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newGrid = doc.getElementById('test-cards-row');
                const newPagination = doc.querySelector('.row.mt-4');

                if (newGrid && resultsGrid) {
                    resultsGrid.innerHTML = newGrid.innerHTML;
                }
                if (newPagination && paginationRow) {
                    paginationRow.innerHTML = newPagination.innerHTML;
                }

                // Optional: scroll up slightly to show updated results
                resultsGrid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Re-run your cart button sync if it exists
                if (typeof refreshCartButtons === 'function') refreshCartButtons();

                // Re-run match heights after DOM swap
                setTimeout(matchCardHeights, 40);

            } catch (err) {
                console.error('Live search error:', err);
            }
        }

        // Prevent form full reload (Enter key)
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!input) return;
                performSearch(input.value.trim());
            });
        }

        // initial clear button state
        updateClearVisibility();
    });

    // ===== Mobile Carousel: Prev / Next arrows =====
    (function() {
        function setupCarouselArrows() {
            const wrap = document.getElementById('tests-carousel-wrap');
            const prevBtn = document.getElementById('testsCarouselPrev');
            const nextBtn = document.getElementById('testsCarouselNext');
            if (!wrap || !prevBtn || !nextBtn) return;

            // Only activate on mobile (≤767px)
            function isMobile() { return window.innerWidth <= 767; }

            // Scroll by exactly one "page" = viewport width (holds 4 cards: 2col × 2row)
            function scrollPage(dir) {
                if (!isMobile()) return;
                var pageWidth = window.innerWidth;
                wrap.scrollBy({ left: dir * pageWidth, behavior: 'smooth' });
            }

            prevBtn.addEventListener('click', function() { scrollPage(-1); });
            nextBtn.addEventListener('click', function() { scrollPage(1); });

            // Update arrow visibility based on scroll position
            function updateArrows() {
                if (!isMobile()) {
                    prevBtn.style.opacity = '';
                    nextBtn.style.opacity = '';
                    return;
                }
                var atStart = wrap.scrollLeft <= 4;
                var atEnd = wrap.scrollLeft + wrap.clientWidth >= wrap.scrollWidth - 4;
                prevBtn.style.opacity = atStart ? '0.35' : '1';
                nextBtn.style.opacity = atEnd ? '0.35' : '1';
            }

            wrap.addEventListener('scroll', updateArrows, { passive: true });
            window.addEventListener('resize', updateArrows);
            updateArrows();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupCarouselArrows);
        } else {
            setupCarouselArrows();
        }
    })();
</script>

<style>
    /* ===== Shared look & grid (same as Home) ===== */
    :root {
        --desc-font-size: 0.95rem;
        --desc-line-height: 1.45;
        --desc-lines: 3;
    }

    .package-style-card {
        display: flex;
        flex-direction: column;
        border-radius: 12px;
        background: #fff;
        border: 1px solid #f0e9ef;
        transition: transform .12s ease, box-shadow .12s ease;
        overflow: visible;
    }

    .package-style-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(33, 45, 70, 0.06);
    }

    .package-desc-wrapper {
        font-size: var(--desc-font-size);
        line-height: var(--desc-line-height);
        min-height: calc(var(--desc-lines) * var(--desc-line-height) * 1em);
        display: flex;
        flex-direction: column;
    }

    /* Description: 3-line clamp + black, medium weight */
    .package-desc {
        color: #111 !important;
        font-weight: 600;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.32rem;
        max-height: calc(1.32rem * 3);
        position: relative;
        margin: 0;
    }

    /* Inline Read more */
    .readmore-inline {
        display: inline-block;
        margin-top: 4px;
        font-weight: 600;
        text-decoration: none;
        color: #0d6efd;
    }

    .readmore-inline:hover {
        text-decoration: underline;
    }

    /* Inline price row */
    .price-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .disc-price {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1a3a66;
    }

    /* Scoped MRP (don’t affect global .text-muted.small) */
    .price-wrap .text-small {
        /* font-size:.82rem !important; color:#7b8894 !important; opacity:.85;
    display:inline-flex; align-items:center; margin:0 2px; */

        color: #000 !important;
        text-decoration: line-through;
        margin-left: 8px;
        font-size: 1rem;
        font-weight: 600;
    }

    /* % OFF pill (same) */
    .percent-off {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        background: #e9f9ef;
        color: #2a7b3f;
        font-weight: 700;
        font-size: .8rem;
        box-shadow: inset 0 0 0 1px rgba(42, 123, 63, .08);
        line-height: 1;
        white-space: nowrap;
    }

    /* You save text (blue) */
    .you-save-text {
        color: #0a66d6 !important;
        font-weight: 600;
        font-size: .9rem;
        margin-top: 2px;
        display: inline-block;
    }

    .wc-modal-footer {
        position: sticky;
        bottom: 0;
        background: #fff;
        z-index: 1;
    }

    .card-footer-block {
        gap: 10px;
        flex-wrap: nowrap;
    }

    .price-wrap {
        min-width: 0;
        flex: 1 1 auto;
    }

    /* --- Cart buttons: EXACT match with Packages slider (colors), compact size kept --- */
    .wc-cart-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 44px;
        padding: 8px 14px;
        font-weight: 700;
        font-size: .95rem;
        border: none;
        border-radius: 10px;
        transition: background .2s ease, transform .15s ease, box-shadow .15s ease;
    }

    /* BEFORE ADD: slider pink */
    .wc-cart-btn.add-to-cart {
        background: #f0c2c2;
        /* same as Packages slider */
        color: #2b4a66;
        border: 1px solid rgba(14, 63, 108, .12);
    }

    .wc-cart-btn.add-to-cart:hover {
        background: #f7faff;
        /* light blue hover, same as slider */
        color: #173b5f;
        transform: translateY(-2px);
        border-color: rgba(14, 63, 108, .25);
    }

    /* AFTER ADD: premium green (exactly like slider) */
    .wc-cart-btn.go-to-cart,
    .wc-cart-btn.btn-go-cart {
        background: #9dd24a !important;
        color: #fff !important;
        border: 0 !important;
        box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
    }

    .wc-cart-btn.go-to-cart:hover,
    .wc-cart-btn.btn-go-cart:hover {
        background: #8ac83d !important;
        transform: translateY(-2px);
    }

    .wc-cart-btn.go-to-cart:active,
    .wc-cart-btn.btn-go-cart:active {
        background: #7ebd33 !important;
    }

    .readmore-inline,
    .readmore-inline:focus,
    .readmore-inline:hover,
    .readmore-inline:active,
    .readmore-inline:visited {
        color: #0d6efd !important;
        text-decoration: none;
    }

    /* ===== Read More Modal — Title & Description (moved from JS) ===== */
    #testDetailsTitle {
        color: #e91e63;
        font-weight: 800;
    }

    #testDetailsDesc {
        color: #111;
        font-weight: 600;
    }

    /* ===== Read More Modal — Price Section ===== */
    #mSell {
        color: #1a3a66 !important;
        /* Selling price */
        font-weight: 800 !important;
        font-size: 1.2rem;
    }

    #mMrp {
        color: #000 !important;
        text-decoration: line-through;
        margin-left: 8px;
        font-size: 1rem;
        font-weight: 600;
    }

    #mOff {
        background: #e9f9ef !important;
        color: #2a7b3f !important;
        font-weight: 700 !important;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.8rem;
        margin-left: 8px;
    }

    #mSave {
        color: #0a66d6 !important;
        font-weight: 600 !important;
        font-size: .95rem;
        margin-left: 10px;
    }

    /* ===== Read More Modal — Footer Layout & Spacing (Buttons RIGHT) ===== */
    #testDetailsModal .wc-modal-footer {
        position: sticky;
        bottom: 0;
        z-index: 1;
        background: #fff;

        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;

        gap: 14px !important;
        padding-top: 14px !important;
        padding-bottom: 14px !important;
    }

    #testDetailsModal .wc-modal-footer .price-box {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px !important;
    }

    #testDetailsModal .wc-modal-footer .action-buttons {
        display: flex;
        gap: 18px !important;
        /* spacing between Close and Add to Cart */
    }

    #testDetailsModal .wc-modal-footer .action-buttons button {
        min-width: 110px;
        /* optional: balanced button widths */
    }

    .wc-modal-footer .d-flex.flex-wrap {
        align-items: center;
        gap: 10px;
    }

    .modal-cancel-icon {
        position: absolute;
        top: 10px;
        right: 12px;
        background: #fff;
        border: none;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
        cursor: pointer;
    }

    .modal-cancel-icon i {
        color: #e91e63;
        font-size: 22px;
        font-weight: bold;
        ;
    }

    .modal-cancel-icon:hover i {
        color: #c2185b;
    }

    /* ===== Services page search bar (page-specific) styles — avoids colliding with global search bar ===== */
    .services-search-bar-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .services-search-bar {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 999px;
        /* full rounded */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 0 10px 0 0;
        width: 100%;
        max-width: 600px;
        transition: box-shadow 0.2s ease;
        position: relative;
    }

    .services-search-bar:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    }

    .services-search-icon-btn {
        background: linear-gradient(135deg, #0047ff, #00ccff);
        border: none;
        color: #fff;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .services-search-icon-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 3px 8px rgba(0, 71, 255, 0.3);
    }

    .services-search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1rem;
        padding: 12px 16px;
        border-radius: 999px;
        color: #333;

        /* make room for clear button so input text never hides under it */
        padding-right: 56px;
    }

    .services-search-input::placeholder {
        color: #999;
        font-size: 0.95rem;
    }

    /* position services clear button inside the same search-bar so it never overlaps global search */
    .services-search-bar .services-clear-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #6b7280;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        cursor: pointer;
        z-index: 5;
        border-radius: 999px;
    }

    .services-search-bar .services-clear-btn:hover {
        color: #333;
        background: rgba(0, 0, 0, 0.04);
    }

    /* keep your global .clear-btn fallback intact (used elsewhere) */
    .clear-btn {
        color: #888;
        margin-right: 10px;
        font-size: 1.2rem;
        transition: color 0.2s ease;
    }

    .clear-btn:hover {
        color: #333;
    }

    /* ===== Fallback (No Results) ===== */
    #test-cards-row .no-results {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 50px 10px;
        color: #6b7280;
    }

    #test-cards-row .no-results h5 {
        font-weight: 600;
        color: #333;
    }

    #test-cards-row .no-results p {
        color: #777;
    }

    #test-cards-row .no-results img {
        opacity: 0.8;
        margin-bottom: 1rem;
    }

    /* No results fallback styling */
    .col-12.text-center.py-5 {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .col-12.text-center.py-5 img {
        opacity: 0.85;
        transition: transform 0.3s ease;
    }

    .col-12.text-center.py-5 img:hover {
        transform: scale(1.05);
    }

    /* Responsive mobile tweaks */
    @media (max-width: 576px) {
        .services-search-bar {
            max-width: 90%;
        }

        .services-search-icon-btn {
            width: 42px;
            height: 42px;
            font-size: 1rem;
        }

        .services-search-input {
            font-size: 0.95rem;
            padding: 10px 12px;
            padding-right: 52px;
        }
    }

    /* ===== MOBILE HORIZONTAL CAROUSEL (≤767px only) ===== */
    /* Desktop: carousel wrapper is invisible/passthrough */
    .tests-carousel-wrap {
        position: relative;
    }

    /* Hide arrows on desktop */
    .tests-carousel-arrow {
        display: none;
    }

    @media (max-width: 767px) {
        /* --- Scroll container --- */
        .tests-carousel-wrap {
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            /* hide scrollbar */
            scrollbar-width: none;
            -ms-overflow-style: none;
            /* show arrows space */
            padding-left: 0;
            padding-right: 0;
        }
        .tests-carousel-wrap::-webkit-scrollbar {
            display: none;
        }

        /* --- Card grid inside carousel --- */
        #test-cards-row {
            /* Show as flex row; each snap-page is exactly viewport-width wide */
            display: flex !important;
            flex-wrap: wrap !important;
            /* 2 cards per row within each snap page */
            width: max-content; /* grows as needed */
            margin: 0 !important;
        }

        /* Each col-card: exactly 50vw wide (2 per visible row) */
        .row.g-4 > .col-card {
            flex: 0 0 calc(50vw - 16px) !important;
            width: calc(50vw - 16px) !important;
            max-width: calc(50vw - 16px) !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
            margin-bottom: 14px !important;
            /* Snap every 4th card (after every 2 cols × 2 rows = 4) — handled by snap groups */
        }

        /* Snap every 2 cols × 2 rows = group of 4: snap at position 1, 5, 9... */
        /* col-card:nth-child(4n+1) is the first of each group of 4 */
        .row.g-4 > .col-card:nth-child(4n+1) {
            scroll-snap-align: start;
        }

        /* --- Arrow buttons: show on mobile --- */
        .tests-carousel-arrow {
            display: flex;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.92);
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.13);
            align-items: center;
            justify-content: center;
            color: #0047ff;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.18s;
        }
        .tests-carousel-arrow:hover {
            background: #f0f6ff;
        }
        .tests-carousel-prev {
            left: 2px;
        }
        .tests-carousel-next {
            right: 2px;
        }

        /* Ensure no-results full-width */
        #test-cards-row .col-12.text-center.py-5 {
            width: 100vw !important;
            flex: 0 0 100vw !important;
        }
    }
    /* ===== END MOBILE CAROUSEL ===== */

    /* Responsive custom grid (1 / 2 / 2 / 3 / 3) */
    .row.g-4 {
        --gutter-x: 1rem;
    }

    @media (max-width: 768px) {
        .row.g-4 {
            --gutter-x: .75rem;
        }
    }

    .row.g-4>.col-card {
        box-sizing: border-box;
        padding-left: var(--gutter-x);
        padding-right: var(--gutter-x);
        margin-bottom: 28px;
        flex: 0 0 100% !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    @media (min-width:576px) {
        .row.g-4>.col-card {
            flex: 0 0 50% !important;
            width: 50% !important;
            max-width: 50% !important;
        }
    }

    @media (min-width:768px) {
        .row.g-4>.col-card {
            flex: 0 0 50% !important;
            width: 50% !important;
            max-width: 50% !important;
        }
    }

    @media (min-width:992px) {
        .row.g-4>.col-card {
            flex: 0 0 33.333333% !important;
            width: 33.333333% !important;
            max-width: 33.333333% !important;
        }
    }

    @media (min-width:1200px) {
        .row.g-4>.col-card {
            flex: 0 0 33.333333% !important;
            width: 33.333333% !important;
            max-width: 33.333333% !important;
        }
    }

    /* ===== Mobile fixes: keep modal buttons inside on small screens ===== */
    @media (max-width: 575.98px) {

        /* Let the footer wrap to a new line instead of forcing everything in one row */
        #testDetailsModal .wc-modal-footer {
            flex-wrap: wrap !important;
        }

        /* Put price row on its own full-width line */
        #testDetailsModal .wc-modal-footer .price-box {
            flex: 1 1 100% !important;
            order: 1;
            gap: 8px !important;
        }

        /* Put buttons on their own full-width line and avoid overflow */
        #testDetailsModal .wc-modal-footer .action-buttons {
            flex: 1 1 100% !important;
            order: 2;
            margin-left: 0 !important;
            /* override the ms-auto effect on small screens */
            justify-content: flex-end;
            /* align buttons to the right by default */
            gap: 12px !important;
        }

        /* Let buttons shrink instead of overflowing the modal */
        #testDetailsModal .wc-modal-footer .action-buttons button {
            min-width: 0 !important;
            /* was 110px; allow shrinking on tight widths */
            flex: 0 1 auto;
            /* allow buttons to shrink to fit */
        }

        /* Make the cart button easier to tap and guaranteed to fit */
        #testDetailsModal .wc-modal-footer .action-buttons .wc-cart-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Slightly reduce left/right modal margins to gain a bit of room */
        #testDetailsModal .modal-dialog {
            margin: .5rem !important;
        }
    }

    /* Optional: on extra-narrow screens, stack buttons 2-per-line or full width */
    @media (max-width: 420px) {
        #testDetailsModal .wc-modal-footer .action-buttons {
            justify-content: stretch;
            /* allow full width */
        }

        #testDetailsModal .wc-modal-footer .action-buttons .btn-light,
        #testDetailsModal .wc-modal-footer .action-buttons .wc-cart-btn {
            flex: 1 1 100%;
            /* each button full width, one per line */
        }
    }

    .package-desc {
        color: #111 !important;
        font-weight: 600;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
        overflow: hidden;
        line-height: 1.32rem;
        margin: 0;
    }

    .readmore-inline {
        display: inline-block;
        margin-top: 6px;
        font-weight: 600;
        color: #0d6efd;
        cursor: pointer;
    }

    .readmore-inline:hover {
        text-decoration: underline;
    }

    .package-desc-wrapper {
        display: flex;
        flex-direction: column;
    }
</style>
@endsection
