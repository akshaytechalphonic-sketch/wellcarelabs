@extends('maindesign')

@section('title', isset($homepage) && !empty($homepage->meta_title) ? $homepage->meta_title : 'Advanced Diagnostic & Health Checkup Packages | Pune')
@section('meta_description', isset($homepage) && !empty($homepage->meta_description) ? $homepage->meta_description : 'Discover Wellcare Labs’ comprehensive health checkup and diagnostic services, including smart health packages, lab tests, and home sample collection with accurate, timely reports aided by expert professionals and modern equipment.')

@section('seo')
    @if(isset($homepage) && !empty($homepage->meta_tags))
        {!! $homepage->meta_tags !!}
    @endif
@endsection


@section('content')
{{-- resources/views/partials/hero-dots-inside.blade.php --}}
@push('meta')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />


<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />

<!-- Font Awesome 6 (icons) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --hero-h-desktop: 500px;
        --hero-h-tablet: 380px;
        --hero-h-mobile: 240px;
        --card-gap: 20px;

        /* smaller card widths for large screens */
        --card-w-desktop: 250px;
        --card-w-tablet: 220px;
        --card-w-mobile: calc(100% - 36px);
    }

    html,
    body {
        height: 100%;
        margin: 0;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
        color: #071033;
        box-sizing: border-box;
    }

    /* HERO / SLIDES */
    .wc-hero-section {
        position: relative;
        width: 100%;
        overflow: visible;
        background: #fff;
        box-sizing: border-box;
    }

    .wc-hero-carousel {
        position: relative;
    }

    .wc-hero-slide {
        width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        overflow: hidden !important;
        position: relative !important;
        background: #f5f7fb;
    }

    /* .wc-hero-slide img {
                              width:100% !important;
                              height:100% !important;
                              object-fit:cover !important;
                              object-position:center center !important;
                              display:block !important;
                            } */


    /* this is the chnage */

    .wc-hero-slide img {
        width: 100% !important;
        height: auto !important;
        max-height: 100% !important;
        object-fit: contain !important;
        object-position: center center !important;
        background: #f5f7fa;
    }

    /* Use aspect-ratio when supported */
    @supports (aspect-ratio: 16/6) {
        .wc-hero-slide {
            aspect-ratio: 16 / 6;
            min-height: 0;
            height: auto;
        }

        @media (max-width: 991px) {
            .wc-hero-slide {
                aspect-ratio: 16 / 8;
            }
        }

        @media (max-width: 768px) {
            .wc-hero-slide {
                aspect-ratio: 16 / 10;
            }
        }
    }

    /* Fallback fixed heights */
    @supports not (aspect-ratio: 16/6) {
        .wc-hero-slide {
            height: var(--hero-h-desktop) !important;
            min-height: var(--hero-h-desktop) !important;
        }

        @media(max-width:991px) {
            .wc-hero-slide {
                height: var(--hero-h-tablet) !important;
                min-height: var(--hero-h-tablet) !important;
            }
        }

        @media(max-width:768px) {
            .wc-hero-slide {
                height: var(--hero-h-mobile) !important;
                min-height: var(--hero-h-mobile) !important;
            }
        }
    }

    /* CONTACT (floating card on desktop/tablet) */
    .wc-hero-contact-card {
        position: absolute;
        right: 40px;
        top: 60%;
        transform: translateY(-60%);
        width: var(--card-w-desktop);
        background: rgba(255, 255, 255, 0.55);
        border-radius: 10px;
        padding: 12px;
        box-shadow: 0 6px 20px rgba(10, 20, 40, 0.08);
        z-index: 9999;
        box-sizing: border-box;
        -webkit-backdrop-filter: blur(6px);
        backdrop-filter: blur(6px);
    }

    .wc-hero-contact-card h3 {
        margin: 0 0 10px;
        font-size: 0.98rem;
        font-weight: 700;
        text-align: center;
        color: #071033;
    }

    .wc-hero-contact-card form {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    /* unified input-group styles, scoped to hero-contact-card */
    .wc-hero-contact-card .wc-hero-input-group {
        position: relative;
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .wc-hero-contact-card .wc-hero-input-group i {
        position: absolute;
        left: 9px;
        font-size: 0.9rem;
        color: #000;
        z-index: 30;
        pointer-events: none;
        -webkit-font-smoothing: antialiased;
        top: 50%;
        transform: translateY(-50%);
    }

    .wc-hero-contact-card .wc-hero-input-group input,
    .wc-hero-contact-card .wc-hero-input-group textarea {
        position: relative;
        z-index: 20;
        width: 100%;
        padding: 8px 8px 8px 34px;
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.85);
        background: rgba(255, 255, 255, 0.72);
        font-size: 0.9rem;
        color: #071033;
        box-sizing: border-box;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .wc-hero-contact-card .wc-hero-input-group textarea {
        min-height: 68px;
        resize: vertical;
    }

    .wc-hero-contact-card .wc-hero-input-group input::placeholder,
    .wc-hero-contact-card .wc-hero-input-group textarea::placeholder {
        color: rgba(7, 16, 51, 0.6);
    }

    .wc-hero-contact-card .wc-hero-input-group input:focus,
    .wc-hero-contact-card .wc-hero-input-group textarea:focus {
        border-color: #0b6efd;
        box-shadow: 0 0 6px rgba(11, 110, 253, 0.18);
        outline: none;
    }

    /* error state */
    .wc-hero-contact-card .wc-hero-input-error {
        border-color: #d93025 !important;
        color: #d93025 !important;
    }

    .wc-hero-contact-card .wc-hero-input-error::placeholder {
        color: #d93025 !important;
        opacity: 1 !important;
    }

    .wc-hero-contact-card button {
        padding: 8px;
        border: 0;
        border-radius: 8px;
        background: #0b6efd;
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background 0.18s ease, box-shadow 0.18s ease;
    }

    .wc-hero-contact-card button:hover {
        background: #084298;
        box-shadow: 0 6px 16px rgba(8, 66, 152, 0.14);
    }

    .wc-hero-small-note {
        font-size: 0.75rem;
        color: #071033;
        margin-top: 4px;
        text-align: center;
        z-index: 20;
    }

    /* MOBILE CTA – hidden by default on larger screens */
    .wc-hero-contact-cta {
        display: none;
    }

    /* DOTS pinned to right middle */
    .wc-hero-section>.owl-dots {
        position: absolute !important;
        top: 50% !important;
        right: 12px !important;
        transform: translateY(-50%) !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 8px !important;
        z-index: 10000 !important;
        pointer-events: auto;
    }

    .wc-hero-section>.owl-dots .owl-dot {
        width: 10px !important;
        height: 10px !important;
        border-radius: 50% !important;
        background: rgba(255, 255, 255, 0.95) !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: transform .16s, background .16s;
    }

    .wc-hero-section>.owl-dots .owl-dot.active {
        transform: scale(1.15);
        background: #fff !important;
    }

    .wc-hero-carousel .owl-dots {
        display: none !important;
    }

    .wc-hero-section .owl-nav {
        display: none !important;
    }


    /* responsive adjustments */
    @media(max-width:1200px) {
        .wc-hero-contact-card {
            right: 34px;
        }
    }

    @media(max-width:991px) {
        .wc-hero-contact-card {
            width: var(--card-w-tablet);
            right: 24px;
            padding: 10px;
        }

        .wc-hero-contact-card h3 {
            font-size: 0.95rem;
        }
    }

    /* MOBILE BEHAVIOUR */
    @media(max-width:768px) {

        /* hide full floating form */
        .wc-hero-contact-card {
            display: none !important;
        }

        /* show the compact CTA card */
        .wc-hero-contact-cta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: var(--card-w-mobile);
            margin: 28px auto 0px;
            padding: 14px 16px;
            background: #c4ece7;
            border-radius: 10px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(10, 20, 40, 0.06);
            color: #075a3c;
        }

        .wc-hero-contact-cta-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .wc-hero-contact-cta-icon {
            font-size: 26px;
        }

        .wc-hero-contact-cta-text-main {
            font-weight: 700;
            font-size: 1rem;
        }

        .wc-hero-contact-cta-text-sub {
            font-size: 0.85rem;
            opacity: 0.85;
        }

        .wc-hero-contact-cta-arrow {
            font-size: 18px;
        }

        /* dots bottom-center on small screens */
        .wc-hero-section>.owl-dots {
            top: 60% !important;
            bottom: 10px !important;
            left: 50% !important;
            right: auto !important;
            transform: translate(-50%, 0) !important;
            flex-direction: row !important;
            justify-content: center !important;
        }

        .wc-hero-section>.owl-dots .owl-dot {
            margin: 0 4px !important;
        }
    }

    @media(min-width:1400px) {
        .wc-hero-contact-card {
            width: 260px;
            right: 60px;
        }

        .wc-hero-section>.owl-dots {
            right: 18px !important;
        }
    }

    .wc-hero-sr-only {
        position: absolute !important;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
        border: 0;
    }

    /* SweetAlert confirm button styling – gradient like you wanted */
    .swal2-confirm {
        background: linear-gradient(135deg, #0066ff, #00ccff) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 10px 24px !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
        box-shadow: 0 5px 18px rgba(0, 102, 255, 0.32) !important;
    }

    .swal2-confirm:hover {
        opacity: 0.9 !important;
        transition: opacity .2s ease-in-out;
    }
</style>

<body>
    @php
        // Normalize hospital ref
        $rawRef = $hospitalRef ?? (session('hospital_ref') ?? (request('ref') ?? null));

        $hospitalRef = null;
        $hospitalId = null;
        $hospitalUniqueId = null;

        if ($rawRef) {
            if (is_string($rawRef)) {
                $hospitalUniqueId = $rawRef;
                $hospitalRef = ['unique_id' => $hospitalUniqueId];
            } elseif (is_array($rawRef)) {
                $hospitalRef = $rawRef;
                $hospitalUniqueId = $hospitalRef['unique_id'] ?? ($hospitalRef['uniqueId'] ?? null);
                $hospitalId = $hospitalRef['id'] ?? null;
            } elseif (is_object($rawRef)) {
                $hospitalRef = (array) $rawRef;
                $hospitalUniqueId = $hospitalRef['unique_id'] ?? ($hospitalRef['uniqueId'] ?? null);
                $hospitalId = $hospitalRef['id'] ?? null;
            }
        }

        if (empty($hospitalUniqueId) && request('ref')) {
            $hospitalUniqueId = request('ref');
            $hospitalRef = $hospitalRef ?: ['unique_id' => $hospitalUniqueId];
        }
    @endphp

    @php
        /* build slides collection */
        $slides = collect();
        if (isset($activeBanners) && $activeBanners) {
            $slides = is_iterable($activeBanners) ? collect($activeBanners) : collect([$activeBanners]);
        } elseif (isset($banners) && $banners) {
            $tmp = is_iterable($banners) ? collect($banners) : collect([$banners]);
            foreach ($tmp as $b) {
                if (
                    (isset($b->status) &&
                        (string) \Illuminate\Support\Str::lower($b->status) === 'published') ||
                    (isset($b->status) && ($b->status == 1 || $b->status === true)) ||
                    (isset($b->is_active) && ($b->is_active == 1 || $b->is_active === true)) ||
                    (isset($b->active) && ($b->active == 1 || $b->active === true))
                ) {
                    $slides->push($b);
                }
            }
        }

        $fallbackImage = asset('Front_end/assets/img/main_banner_1.png');

        $resolveSlideImage = function ($s, $fallback) {
            $fields = ['banner', 'image', 'path', 'file', 'banner_path', 'url'];
            foreach ($fields as $f) {
                if (!empty($s->{$f})) {
                    $val = trim($s->{$f});
                    if (filter_var($val, FILTER_VALIDATE_URL)) {
                        return $val;
                    }
                    $maybe = preg_replace('#^(/)?storage/#', '', $val);
                    if ($maybe && file_exists(storage_path('app/public/' . $maybe))) {
                        return asset('storage/' . $maybe);
                    }
                    if (file_exists(public_path($val))) {
                        $rel = ltrim(str_replace(public_path(), '', $val), '/\\');
                        return asset($rel);
                    }
                    return asset($val);
                }
            }
            return $fallback;
        };
    @endphp

    @if ($slides->count() > 0)
        <section id="wc-hero-section" class="wc-hero-section" aria-label="Hero banner">
            <div class="owl-carousel wc-hero-carousel owl-theme" aria-live="polite">
                @foreach ($slides as $s)
                    @php
                        $slideImage = $resolveSlideImage($s, $fallbackImage);
                        $slideAlt = $s->alt ?? ($s->title ?? 'Banner');
                    @endphp
                    <div class="wc-hero-slide" role="group" aria-label="{{ $slideAlt }}">

                        <a href="{{ $s->url }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $slideImage }}" alt="{{ $slideAlt }}" loading="lazy" />
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Floating Contact Form (desktop/tablet) -->
            <div id="wc-hero-contact-card" class="wc-hero-contact-card" aria-label="Contact form">
                <h3>Connect With WellCare Labs</h3>

                <form id="wc-hero-contact-form" action="{{ route('inquiry.store') }}" method="POST"
                    enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- Honeypot -->
                    <input type="text" name="hp_verification_check" autocomplete="new-password"
                        style="display:none !important">

                    <div class="wc-hero-input-group wc-hero-field">
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>

                    <div class="wc-hero-input-group wc-hero-field">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        <input type="email" name="email" placeholder="Your Email (optional)">
                    </div>


                    <div class="wc-hero-input-group wc-hero-field">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i>
                        <input type="text" name="phone" placeholder="Your Phone (10 digits)" required>
                    </div>
                    {{-- <div class="wc-hero-input-group wc-hero-field">

                        <input type="file" name="prescription" id="prescriptionUpload" class="form-control"
                            aria-describedby="prescriptionHelp" required>

                    </div> --}}

                    <div class="wc-hero-input-group wc-hero-field">
                        <i class="fa-solid fa-comment" aria-hidden="true"></i>
                        <textarea name="message" placeholder="Your Message" required></textarea>
                    </div>

                    <button type="submit" id="wc-hero-contact-btn">
                        Submit
                        <span class="spinner-border spinner-border-sm" id="wc-hero-contact-spinner"
                            style="display:none"></span>
                    </button>

                    <div class="wc-hero-small-note">We respect your privacy. We'll never share your info.</div>
                </form>
            </div>

            <!-- MOBILE: compact CTA card -->
            <a href="{{ url('/contact_us') }}" class="wc-hero-contact-cta" aria-label="Get a call back to book appointment">
                <div class="wc-hero-contact-cta-left">
                    <i class="fa-regular fa-calendar-check wc-hero-contact-cta-icon" aria-hidden="true"></i>
                    <div class="wc-hero-contact-cta-text">
                        <div class="wc-hero-contact-cta-text-main">Get a Call back</div>
                        <div class="wc-hero-contact-cta-text-sub">Your health, our priority — contact us today. </div>
                    </div>
                </div>
                <i class="fa-solid fa-arrow-right wc-hero-contact-cta-arrow" aria-hidden="true"></i>
            </a>
        </section>
    @else
        <section class="wc-hero-section">
            <div class="wc-hero-slide" style="min-height:var(--hero-h-desktop); background:#eef7ff;">
                <img src="{{ $fallbackImage }}" alt="Banner" style="width:100%;height:100%;object-fit:cover" />
            </div>
        </section>
    @endif

    <!-- scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* contact form validation + ajax + SweetAlert */
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("wc-hero-contact-form");
            if (!form) return;

            const btn = document.getElementById("wc-hero-contact-btn");
            const spin = document.getElementById("wc-hero-contact-spinner");

            const namePattern = /^[A-Za-z\s]+$/;
            const phonePattern = /^[0-9]{10}$/;

            function clearAllErrors() {
                form.querySelectorAll(".wc-hero-input-error").forEach(el => el.classList.remove(
                    "wc-hero-input-error"));
                form.querySelectorAll("[data-old-placeholder]").forEach(el => {
                    el.placeholder = el.dataset.oldPlaceholder;
                    delete el.dataset.oldPlaceholder;
                });
            }

            function applyInsideError(fieldName, message) {
                const el = form.querySelector(`[name="${fieldName}"]`);
                if (!el) return;

                el.value = '';
                el.classList.add("wc-hero-input-error");

                if (!el.dataset.oldPlaceholder) {
                    el.dataset.oldPlaceholder = el.placeholder || '';
                }
                el.placeholder = message;

                const restoreOnInput = function () {
                    el.classList.remove("wc-hero-input-error");
                    if (el.dataset.oldPlaceholder !== undefined) {
                        el.placeholder = el.dataset.oldPlaceholder;
                        delete el.dataset.oldPlaceholder;
                    }
                    el.removeEventListener('input', restoreOnInput);
                };
                el.addEventListener('input', restoreOnInput);
            }

            // Common SweetAlert popup
            function showHeroAlert(type, msg) {
                if (window.Swal) {
                    if (type === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you!',
                            text: msg || ' We will contact you shortly.',
                            width: '350px',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            timer: 10000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: msg || 'Something went wrong. Please try again.',
                            confirmButtonText: 'OK'
                        });
                    }
                    return;
                }

                // Fallback if Swal fails to load
                alert(msg || (type === 'success' ?
                    ' We will contact you shortly.' :
                    'Something went wrong.'));
            }

            function clientValidateAll() {
                clearAllErrors();
                let ok = true;

                const name = (form.name.value || '').trim();
                const email = (form.email.value || '').trim();
                const phone = (form.phone.value || '').trim();
                const message = (form.message.value || '').trim();
                const honeypot = (form.hp_verification_check ? form.hp_verification_check.value.trim() : '');

                if (honeypot.length > 0) {
                    showHeroAlert('error', 'Spam detected.');
                    return {
                        valid: false
                    };
                }

                if (!name) {
                    applyInsideError('name', 'Enter your name');
                    ok = false;
                } else if (!namePattern.test(name)) {
                    applyInsideError('name', 'Use letters & spaces only');
                    ok = false;
                }

                const emailEl = form.querySelector('[name="email"]');

                // Email is optional: only validate if user has typed something
                if (email) {
                    if (!emailEl.checkValidity()) {
                        applyInsideError('email', 'Enter a valid email');
                        ok = false;
                    } else if (email.length > 255) {
                        applyInsideError('email', 'Email must not exceed 255 characters');
                        ok = false;
                    }
                }


                if (!phone) {
                    applyInsideError('phone', 'Enter phone number');
                    ok = false;
                } else if (!phonePattern.test(phone)) {
                    applyInsideError('phone', 'Enter valid 10-digit phone');
                    ok = false;
                }

                if (!message) {
                    applyInsideError('message', 'Enter your message');
                    ok = false;
                }

                return {
                    valid: ok
                };
            }

            async function handleServerErrors(response) {
                // 422 validation errors
                if (response.status === 422) {
                    let json = null;
                    try {
                        json = await response.json();
                    } catch (e) { }
                    if (json && json.errors) {
                        Object.keys(json.errors).forEach(field => {
                            const msg = Array.isArray(json.errors[field]) ? json.errors[field][0] :
                                String(json.errors[field]);
                            applyInsideError(field, msg || 'Invalid input');
                        });
                        showHeroAlert('error', 'Please correct the highlighted fields and try again.');
                        return true;
                    }
                }

                // other server errors
                if (!response.ok) {
                    let json = null;
                    try {
                        json = await response.json();
                    } catch (e) { }
                    showHeroAlert('error', (json && json.message) ? json.message :
                        'Server error. Please try again later.');
                    return true;
                }

                return false;
            }

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                clearAllErrors();

                const {
                    valid
                } = clientValidateAll();
                if (!valid) return;

                btn.setAttribute('disabled', true);
                spin.style.display = 'inline-block';

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    const handled = await handleServerErrors(res);
                    if (handled) {
                        btn.removeAttribute('disabled');
                        spin.style.display = 'none';
                        return;
                    }

                    const body = await res.json().catch(() => ({}));
                    showHeroAlert('success', body.message ||
                        'Thank you for connecting with Wellcare Labs Our Health Advisor will call you shortly.'
                    );
                    form.reset();
                } catch (err) {
                    console.error(err);
                    showHeroAlert('error',
                        'Network error. Please check your connection and try again.');
                } finally {
                    btn.removeAttribute('disabled');
                    spin.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        /* hero carousel + dynamic spacing */
        (function () {
            function initHero() {
                if (typeof jQuery === 'undefined' || typeof jQuery().owlCarousel !== 'function') {
                    console.warn('jQuery or OwlCarousel missing');
                    return;
                }

                var $hero = jQuery('#wc-hero-section');
                var $carousel = $hero.find('.wc-hero-carousel');

                $carousel.owlCarousel({
                    items: 1,
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    nav: false,
                    dots: true,
                    smartSpeed: 700,
                    responsive: {
                        0: {
                            items: 1
                        },
                        768: {
                            items: 1
                        },
                        992: {
                            items: 1
                        }
                    }
                });

                try {
                    var $dots = $carousel.find('.owl-dots').first();
                    if ($dots && $dots.length) {
                        $carousel.find('.owl-dots').not($dots).remove();
                        $dots.detach().appendTo($hero);
                    }
                } catch (e) {
                    console.warn('move dots error', e);
                }

                function adjustSpacing() {
                    var heroEl = $hero.get(0),
                        cardEl = document.getElementById('wc-hero-contact-card');
                    if (!heroEl || !cardEl) return;

                    var w = window.innerWidth || document.documentElement.clientWidth;
                    var cs = window.getComputedStyle(cardEl);
                    var cardPos = cs.position;

                    heroEl.style.paddingBottom = '';

                    if (!((cardPos === 'absolute' || cardPos === 'fixed') && w > 768)) {
                        return;
                    }

                    var heroRect = heroEl.getBoundingClientRect();
                    var cardRect = cardEl.getBoundingClientRect();
                    var gap = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--card-gap')) || 20;

                    var overlap = cardRect.bottom - heroRect.bottom;

                    if (overlap > 0) {
                        var pad = Math.ceil(overlap + gap);
                        heroEl.style.paddingBottom = pad + 'px';
                    }
                }

                setTimeout(adjustSpacing, 220);
                try {
                    $carousel.on('initialized.owl.carousel refreshed.owl.carousel changed.owl.carousel', adjustSpacing);
                } catch (e) { }

                var rt = null;
                window.addEventListener('resize', function () {
                    clearTimeout(rt);
                    rt = setTimeout(adjustSpacing, 160);
                }, {
                    passive: true
                });

                window.addEventListener('orientationchange', function () {
                    clearTimeout(rt);
                    rt = setTimeout(adjustSpacing, 240);
                }, {
                    passive: true
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initHero);
            } else {
                initHero();
            }
        })();
    </script>
</body>

</html>





<!-- ===== Customize Your Own Package Section ===== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="wc-customize-card-wrapper">
    <div class="wc-customize-card">
        <div class="wc-customize-content">
            <span class="wc-customize-badge" aria-hidden="true">
                <i class="fa fa-check-circle"></i> Customise
            </span>

            <h2>Create your Own Package</h2>
            <p>Customise your package based on the tests you choose and get <strong>extra 50% OFF</strong>.</p>

            <a href="{{ route('customize.index') }}" class="wc-customize-btn-create"
                aria-label="Create your own package">
                Create Now <i class="fa fa-arrow-right ms-2" aria-hidden="true"></i>
            </a>
        </div>

        {{-- <div class="wc-customize-image" aria-hidden="true">
            <img src="Front_end/assets/img/girl_img1.jpg" alt="Person pointing — customize package">
        </div> --}}
    </div>


    <style>
        /* Global helpful reset for this component */
        .wc-customize-card,
        .wc-customize-card * {
            box-sizing: border-box;
        }

        :root {
            --card-bg: #ffffff;
            --card-border: #e6e6e8;
            --muted: #6b7280;
            --heading: #0a2540;
            --accent1: #0b4f86;
            --accent2: #077caa;
            --btn-shadow: rgba(0, 76, 153, 0.18);
            --badge-bg: #ecf9f1;
            --badge-color: #0f9d58;
        }

        /* wrapper keeps some page padding and prevents container overflow on very small screens */
        .wc-customize-card-wrapper {
            width: 100%;
            padding: 24px;
            /* page side padding */
        }

        /* Main card - clipped (no horizontal scroll) */
        .wc-customize-card {
            width: 100%;
            max-width: 1200px;
            margin: 20px auto 30px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 28px;
            box-shadow: 0 8px 28px rgba(2, 6, 23, 0.06);
            position: relative;
            overflow: hidden;
            /* <-- critical: prevents horizontal scroll while still allowing visual overlap */
        }

        /* Content column (left) */
        .wc-customize-content {
            flex: 1 1 58%;
            min-width: 220px;
        }

        .wc-customize-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--badge-bg);
            color: var(--badge-color);
            font-weight: 700;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.95rem;
            margin-bottom: 12px;
            text-decoration: none !important;
        }

        .wc-customize-badge i {
            color: var(--badge-color);
            font-size: 1rem;
        }

        .wc-customize-content h2 {
            font-size: 2.1rem;
            line-height: 1.05;
            color: var(--heading);
            margin: 6px 0 8px 0;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .wc-customize-content p {
            color: var(--muted);
            font-size: 1rem;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        /* Button */
        .wc-customize-btn-create {
            background: linear-gradient(135deg, #0066ff, #00ccff) !important;
            padding: .6rem 1.2rem !important;
            border-radius: 10px !important;
            width: 500px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
            font-weight: 700 !important;
            color: #000 !important;
            /* black text */
            border: none !important;
            cursor: pointer !important;
            transition: all 0.25s ease-in-out !important;
            box-shadow: 0 0 0 transparent !important;
            gap: 8px !important;
            /* space between text & icon */
        }

        /* ===== Hover Effect ===== */
        .wc-customize-btn-create:hover {
            background: linear-gradient(135deg, #0052cc, #00b8e6) !important;
            /* darker gradient */
            color: #fff !important;
            /* white text */
            transform: translateY(-3px) scale(1.04) !important;
            box-shadow: 0 0 25px rgba(0, 204, 255, 0.5) !important;
            /* blue-teal glow */
            text-decoration: none !important;
        }

        /* ===== Active (Pressed State) ===== */
        .wc-customize-btn-create:active {
            transform: translateY(0) scale(0.97) !important;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2) !important;
            background: linear-gradient(135deg, #0047b3, #00a3cc) !important;
            color: #fff !important;
        }

        /* ===== Focus (Accessibility) ===== */
        .wc-customize-btn-create:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(0, 204, 255, 0.35) !important;
        }

        /* ===== Disabled State ===== */
        .wc-customize-btn-create:disabled,
        .wc-customize-btn-create.disabled {
            opacity: 0.6 !important;
            cursor: not-allowed !important;
            background: linear-gradient(135deg, #9ecaff, #b5eaff) !important;
            color: #333 !important;
            box-shadow: none !important;
            transform: none !important;
        }

        /* ===== Icon inside .wc-customize-btn-create (optional tweak) ===== */
        .wc-customize-btn-create i {
            transition: transform 0.25s ease-in-out !important;
        }

        .wc-customize-btn-create:hover i {
            transform: translateX(5px) !important;
        }

        /* ===== Small Device Tweaks ===== */
        @media (max-width: 576px) {
            .wc-customize-btn-create {
                padding: 0.55rem 1rem !important;
                font-size: 0.95rem !important;
                border-radius: 8px !important;
            }
        }

        /* Image column (right) - uses clipping rather than pushing layout */
        .wc-customize-image {
            flex: 0 0 360px;
            max-width: 360px;
            text-align: right;
            position: relative;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            pointer-events: none;
            /* decorative only */
        }

        .wc-customize-image img {
            width: 100%;
            max-width: 360px;
            height: auto;
            display: block;
            border-radius: 12px;
            object-fit: cover;
            /* subtle overlap visual — clipped by parent overflow:hidden so it won't create scroll */
            transform: translateX(-8%);
            transition: transform .2s ease;
        }

        /* decorative pseudo-element behind image (clipped safely) */
        .wc-customize-image::after {
            content: "";
            position: absolute;
            right: -40px;
            /* smaller negative offset — clipped by overflow:hidden */
            top: 8%;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle at 30% 30%, rgba(11, 79, 134, 0.03), transparent 35%),
                radial-gradient(circle at 70% 70%, rgba(7, 124, 170, 0.02), transparent 40%);
            pointer-events: none;
            border-radius: 50%;
            z-index: 0;
            opacity: 0.9;
        }

        /* ensure no horizontal scroll triggered by long text or extra margin */
        .wc-customize-card * {
            max-width: 100%;
        }

        /* -------------------- Responsive breakpoints -------------------- */

        /* Large tablets / small desktops */
        @media (max-width: 1000px) {
            .wc-customize-card {
                padding: 24px;
                gap: 18px;
            }

            .wc-customize-image {
                flex-basis: 300px;
                max-width: 300px;
            }

            .wc-customize-image img {
                max-width: 300px;
                transform: translateX(-6%);
            }

            .wc-customize-image::after {
                right: -32px;
                width: 180px;
                height: 180px;
                top: 10%;
            }
        }

        /* Tablets & larger phones */
        @media (max-width: 768px) {
            .wc-customize-card {
                flex-direction: column;
                align-items: stretch;
                padding: 18px;
                border-radius: 12px;
                gap: 14px;
            }

            /* Put image above content; remove transform so it's flush and can't overflow */
            .wc-customize-image {
                order: -1;
                max-width: 100%;
                flex: none;
                justify-content: center;
                text-align: center;
                margin: 0;
                padding-top: 4px;
            }

            .wc-customize-image img {
                width: 100%;
                max-width: 420px;
                transform: none;
                border-radius: 10px;
                margin: 0 auto;
                display: block;
            }

            .wc-customize-image::after {
                display: none;
            }

            /* decorative hidden on small screens */

            .wc-customize-content {
                text-align: left;
                padding-left: 0;
                padding-right: 0;
            }

            .wc-customize-content h2 {
                font-size: 1.6rem;
            }

            .wc-customize-btn-create {
                width: 100%;
                justify-content: center;
                height: 52px;
            }
        }

        /* Very small phones */
        @media (max-width: 420px) {
            .wc-customize-card-wrapper {
                padding: 12px;
            }

            .wc-customize-card {
                padding: 14px;
            }

            .wc-customize-content h2 {
                font-size: 1.35rem;
            }

            .wc-customize-content p {
                font-size: 0.95rem;
            }

            .wc-customize-btn-create {
                height: 48px;
                padding: 0 18px;
                font-size: 0.98rem;
            }
        }
    </style>




    {{-- ========================= SPECIAL PACKAGES SECTION ========================= --}}
    {{-- ========================= HOME: SPECIAL PACKAGES (same UI) ========================= --}}
    @if (isset($specialPackages) && $specialPackages->count())
            <section id="packages-special" class="py-5" style="
            max-width:100%;
            margin:auto;
            background:#f0f4f8;
            box-shadow:
              inset 0 10px 25px -10px rgba(0,0,0,0.06),
              inset 0 -10px 25px -10px rgba(0,0,0,0.06);
            padding-top:4rem !important;
            margin-top:2rem;
            padding-bottom:3rem !important;
            margin-bottom:2rem;
            border-bottom:1px solid rgba(0,0,0,0.05);
          ">

                <div class="text-center mb-5 px-3 px-md-5" style="max-width:800px;margin:0 auto;">
                    <h1 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
                        Wellcare Exclusive <span style="color:#0d6efd;">Special Packages</span>
                    </h1>
                    <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                        background:linear-gradient(90deg,#0047ff,#00ccff);">
                    </div>
                    <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
                        Doctor-Designed. Patient-Focused. Budget-Friendly. Special Packages. </p>
                </div>



                <div class="slider-wrap position-relative">
                    <!-- arrows -->
                    <button class="btn btn-slider btn-slider-left" type="button" aria-label="Scroll left">
                        <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-slider btn-slider-right" type="button" aria-label="Scroll right">
                        <i class="bi bi-chevron-right" aria-hidden="true"></i>
                    </button>

                    <!-- slider -->
                    <div class="slider-scroll overflow-auto" tabindex="0" role="list" aria-label="Special packages slider">
                        <div class="slider-row d-flex flex-nowrap m-0"><!-- 32px gap -->

                            @foreach ($specialPackages->take(12) as $pkg)
                                @php
                                    $title = $pkg->title ?? 'Package';
                                    $packageParam = $pkg->slug ?? $pkg->id;

                                    /* ===== TEST CHIPS (CORRECT – SAME AS BASIC PACKAGES) ===== */
                                    $badges = [];

                                    /* 1️⃣ Relation-based tests */
                                    if (isset($pkg->tests) && is_iterable($pkg->tests) && count($pkg->tests)) {
                                        foreach ($pkg->tests as $t) {
                                            $badges[] = trim($t->test_name ?? ($t->title ?? ($t->name ?? '')));
                                        }

                                        /* 2️⃣ Comma-separated tests_list */
                                    } elseif (!empty($pkg->tests_list) && is_string($pkg->tests_list)) {
                                        $badges = array_map('trim', explode(',', strip_tags($pkg->tests_list)));

                                        /* 3️⃣ Safe fallback */
                                    } else {
                                        $parts = preg_split('/\r\n|\n|,/', strip_tags($pkg->content ?? ''));
                                        $parts = array_map('trim', $parts);
                                        $badges = array_filter($parts);
                                    }

                                    /* Remove duplicates */
                                    $seen = [];
                                    $badges = array_values(
                                        array_filter($badges, function ($v) use (&$seen) {
                                            $k = mb_strtolower($v);
                                            if ($k === '' || isset($seen[$k])) {
                                                return false;
                                            }
                                            $seen[$k] = true;
                                            return true;
                                        }),
                                    );

                                    $maxShow = 4;
                                    $totalBadges = count($badges);
                                    $showBadges = array_slice($badges, 0, $maxShow);

                                    // prices
                                    $mrp = (float) ($pkg->mrp ?? 0);
                                    $discounted = (float) ($pkg->discounted_price ?? 0);
                                    $price = (float) ($pkg->price ?? 0);
                                    $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);
                                    $savePercent =
                                        $mrp > 0 && $displayPrice
                                        ? round((($mrp - $displayPrice) / $mrp) * 100)
                                        : 0;
                                    $savingAmount =
                                        $mrp > 0 && $displayPrice && $mrp > $displayPrice
                                        ? $mrp - $displayPrice
                                        : 0;

                                    // image
                                    $banner = $pkg->banner ? ltrim($pkg->banner, '/') : null;
                                    $bannerPath = $banner ? storage_path('app/public/' . $banner) : null;
                                    $hasBanner = $bannerPath && file_exists($bannerPath);
                                    $defaultImage = asset('Front_end/assets/img/blog/default-package.jpg');
                                    $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 360'>
                                          <rect width='100%' height='100%' fill='#f6fbfb' /><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#9aa0a6' font-size='20'>No image available</text>
                                        </svg>";
                                    $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode($svg);

                                @endphp

                                <div class="slider-item" role="listitem" aria-label="{{ $title }}">
                                    <div class="pretty-card h-100 d-flex flex-column position-relative">
                                        <a class="img-fixed rounded-top-4 overflow-hidden bg-light d-block"
                                            href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                                            aria-label="Open {{ $title }}">
                                            @if ($hasBanner)
                                                <img src="{{ asset('storage/' . $banner) }}" class="w-100 h-100" alt="{{ $title }}"
                                                    loading="lazy" style="object-fit:cover;">
                                            @elseif(file_exists(public_path('Front_end/assets/img/blog/default-package.jpg')))
                                                <img src="{{ $defaultImage }}" class="w-100 h-100" alt="{{ $title }}" loading="lazy"
                                                    style="object-fit:cover;">
                                            @else
                                                <img src="{{ $placeholder }}" class="w-100 h-100" alt="No image" loading="lazy"
                                                    style="object-fit:cover;">
                                            @endif
                                        </a>

                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title mb-2 pkg-title">
                                                <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                                                    class="title-link text-decoration-none">
                                                    {{ \Illuminate\Support\Str::limit($title, 60) }}
                                                </a>
                                            </h5>

                                            <div class="chips mb-2">
                                                @foreach ($showBadges as $b)
                                                    <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                                                        class="chip-pill" title="{{ strip_tags($b) }}">
                                                        <span class="chip-text">{{ $b }}</span>
                                                    </a>
                                                @endforeach
                                                @if ($totalBadges > $maxShow)
                                                    <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                                                        class="chip-pill chip-more">+{{ $totalBadges - $maxShow }}
                                                        more</a>
                                                @endif
                                            </div>

                                            <div class="flex-grow-1"></div>

                                            <div class="purchase-meta mb-2">
                                                <div class="d-flex align-items-center flex-wrap gap-2">
                                                    @if ($displayPrice)
                                                        <div class="price-final">₹{{ number_format($displayPrice, 0) }}
                                                        </div>
                                                        @if ($mrp > 0 && $mrp > $displayPrice)
                                                            <div class="price-mrp">₹{{ number_format($mrp, 0) }}</div>
                                                        @endif
                                                        @if ($savePercent > 0)
                                                            <span class="discount-pill">{{ $savePercent }}% OFF</span>
                                                        @endif
                                                    @else
                                                        <div class="price-final">Contact</div>
                                                        <small class="text-muted">for price</small>
                                                    @endif
                                                </div>

                                                @if ($savingAmount > 0)
                                                    <div class="save-link">You save
                                                        ₹{{ number_format($savingAmount, 0, '.', ',') }}</div>
                                                @endif
                                            </div>

                                            {{-- ACTIONS --}}
                                            <div class="d-flex flex-column flex-sm-row gap-2 btn-foreground">
                                                <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                                                    class="btn btn-view-info btn-sm flex-fill flex-sm-grow-0"
                                                    onclick="event.stopPropagation();">
                                                    <i class="fa fa-info-circle me-1" aria-hidden="true"></i> View Info
                                                </a>

                                                <button type="button" id="cart-btn-{{ $pkg->id }}"
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
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('packages.special') }}" class="btn btn-primary">Show All Special Packages</a>
                </div>
            </section>
    @else
            <section id="packages-special" class="py-5" style="
            max-width:100%;
            margin:auto;
            background:#f0f4f8;
            box-shadow:
              inset 0 10px 25px -10px rgba(0,0,0,0.06),
              inset 0 -10px 25px -10px rgba(0,0,0,0.06);
            padding-top:4rem !important;
            margin-top:2rem;
            padding-bottom:3rem !important;
            margin-bottom:2rem;
            border-bottom:1px solid rgba(0,0,0,0.05);
          ">
                <div class="text-center px-3" style="max-width:800px;margin:0 auto;">
                    <h1 class="h3 mb-2 fw-bold" style="font-size:2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
                        Wellcare Exclusive <span style="color:#0d6efd;">Special Packages</span>
                    </h1>
                    <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                        background:linear-gradient(90deg,#0047ff,#00ccff);">
                    </div>
                    <p class="text-muted mb-0" style="font-size:1.05rem;color:#6b7280;margin:0;">
                        No special packages are available at the moment. Please check back soon.
                    </p>
                </div>
            </section>
    @endif

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        #packages-special .chip-pill {
            align-items: flex-start;
        }

        #packages-special .chip-text {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
            max-width: none !important;
            line-height: 1.25;
            display: inline;
        }

        /* --- Slider --- */
        /* Make the wrap the positioning context */
        #packages-special .slider-wrap {
            position: relative;
            padding-top: 8px;
            /* tiny space so buttons don't touch the heading */
        }

        #packages-special .slider-scroll {
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: .25rem;
        }

        #packages-special .slider-row {
            column-gap: 32px;
        }

        #packages-special .slider-item {
            scroll-snap-align: start;
            flex: 0 0 auto;
            width: 320px;
        }

        /* Mobile slider styling for Special Packages */
        @media (max-width: 767px) {
            #packages-special .slider-scroll {
                scroll-snap-type: x mandatory !important;
                overflow-x: auto !important;
                overflow-y: visible !important;
                height: auto !important;
                padding-left: 16px !important;
                padding-right: 16px !important;
            }
            #packages-special .slider-row {
                flex-wrap: nowrap !important;
                height: auto !important;
                column-gap: 16px !important;
                row-gap: 0 !important;
            }
            #packages-special .slider-item {
                width: 290px !important;
                min-width: 270px !important;
                max-width: 320px !important;
                scroll-snap-align: start !important;
                flex: 0 0 auto !important;
            }
        }

        /* --- Side buttons --- */
        /* Base button look: rounded square + blue gradient like screenshot */
        #packages-special .btn-slider {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 12px;
            /* rounded square */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(180deg, #1c5b89 0%, #164a75 100%);
            /* blue gradient */
            box-shadow: 0 10px 24px rgba(11, 46, 78, 0.18);
            transition: transform .12s ease, filter .12s ease, box-shadow .12s ease;

            /* move to top-right (overrides your old center positioning) */
            position: absolute !important;
            top: -36px !important;
            /* sit above the cards row */
            transform: none !important;
            /* kill translateY(-50%) */
            z-index: 10;
        }

        /* Hover / active like the screenshot feel */
        #packages-special .btn-slider:hover {
            filter: brightness(1.06);
            box-shadow: 0 12px 26px rgba(11, 46, 78, 0.24);
        }

        #packages-special .btn-slider:active {
            transform: translateY(1px);
        }


        /* Disabled state stays visible but dim */
        #packages-special .btn-slider:disabled {
            opacity: .45;
            cursor: default;
            box-shadow: 0 6px 16px rgba(11, 46, 78, 0.12);
        }

        /* Place them side-by-side at the right corner */
        #packages-special .btn-slider-left {
            right: 64px !important;
            /* space between buttons */
        }

        #packages-special .btn-slider-right {
            right: 6px !important;
        }

        /* Icon size (Bootstrap Icons) */
        #packages-special .btn-slider .bi {
            font-size: 1.05rem;
            line-height: 1;
        }

        @media (max-width:576px) {
            #packages-special .btn-slider {
                width: 36px;
                height: 36px;
            }
        }

        /* --- Card look --- */
        /* ===== Card base (static state) ===== */
        #packages-special .pretty-card {
            background: #fff;
            border: 0;
            border-radius: 20px;
            overflow: hidden;

            /* base shadow */
            box-shadow:
                0 8px 18px rgba(10, 38, 64, .08),
                0 2px 6px rgba(10, 38, 64, .04);

            /* animation engine */
            transition:
                box-shadow 220ms cubic-bezier(.2, .8, .2, 1),
                transform 220ms cubic-bezier(.2, .8, .2, 1);
        }

        /* ===== Hover animation (card only) ===== */
        #packages-special .pretty-card:hover {
            box-shadow:
                0 18px 40px rgba(10, 38, 64, .16),
                0 6px 14px rgba(10, 38, 64, .08);

            /* very subtle lift (safe for slider) */
            transform: translateY(-4px);
        }

        @media (prefers-reduced-motion: reduce) {
            #packages-special .pretty-card {
                transition: none;
                transform: none;
            }
        }


        /* ===== Scroll-in initial state ===== */
        /* #packages-special .pretty-card {
                          opacity: 0;
                          transform: translateY(24px);
                          transition:
                            opacity 500ms ease,
                            transform 500ms cubic-bezier(.2,.8,.2,1),
                            box-shadow 220ms cubic-bezier(.2,.8,.2,1);
                        } */
        /* ===== When card becomes visible ===== */
        /* #packages-special .pretty-card.is-visible {
                          opacity: 1;
                          transform: translateY(0);
                        } */
        @media (prefers-reduced-motion: reduce) {
            #packages-special .pretty-card {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }

        #packages-special .slider-scroll {
            scroll-behavior: smooth;
        }






        #packages-special .card-body {
            padding: 18px;
        }

        /* --- Uniform image (clickable) --- */
        #packages-special .img-fixed {
            height: 220px;
            width: 100%;
        }

        @media (max-width:576px) {
            #packages-special .img-fixed {
                height: 200px;
            }
        }

        /* --- Title --- */
        #packages-special .pkg-title {
            margin: 0;
            font-size: 1.03rem;
            font-weight: 700;
        }

        #packages-special .title-link {
            color: #ff2b6d;
        }

        #packages-special .title-link:hover {
            opacity: .9;
        }

        /* --- Small chips --- */
        #packages-special .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        #packages-special .chip-pill {
            display: inline-flex;
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

        #packages-special .chip-pill .text-truncate {
            display: inline-block;
            max-width: 170px;
        }

        @media (max-width:576px) {
            #packages-special .chip-pill .text-truncate {
                max-width: 130px;
            }
        }

        #packages-special .chip-more {
            background: #eef5ff;
        }

        /* --- Price block --- */
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
            ;
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

        /* Add spacing between ₹Final • ₹MRP • Discount */
        #packages-special .purchase-meta .d-flex.align-items-center {
            gap: 10px;
            /* adjust 6–10px depending on spacing you want */
        }

        #packages-special .save-link {
            color: #0a66d6;
            font-weight: 600;
            font-size: .9rem;
        }

        /* --- Buttons stay above links --- */
        #packages-special .btn-foreground,
        #packages-special .btn-foreground .btn {
            position: relative;
            z-index: 5;
        }

        /* --- Info button --- */
        /* --- Info button (bigger) --- */
        /* --- View Info button (Ultra Small) --- */
        #packages-special .btn-view-info {
            background: #fff;
            color: #0a66d6;
            border: 1px solid rgba(10, 102, 214, .25);
            font-weight: 600;
            border-radius: 8px;

            /* Ultra small size */
            padding: .22rem .5rem;
            /* smaller */
            font-size: .75rem;
            /* smaller text */
            min-height: 28px;
            /* very compact */
            line-height: 1;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .25rem;
        }

        #packages-special .btn-view-info i {
            font-size: .8rem;
            /* tiny icon */
        }

        #packages-special .btn-view-info:hover {
            background: #f5f9ff;
        }


        /* ====== CART BUTTON STYLES (white-outline for both states) ====== */
        /* --- Cart buttons (Add / Go to Cart) same size --- */
        #packages-special .btn-action-cart {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            /* size ↑ */
            padding: .6rem 1rem;
            /* was .45rem .75rem */
            font-size: .95rem;
            border-radius: 12px;
            font-weight: 700;
            line-height: 1.2;
            min-height: 44px;
            /* match info button */
            border: 0;
            cursor: pointer;
            min-width: 140px;
            /* a bit wider */
        }

        /* Same style for Add & Go to Cart */
        #packages-special .btn-add-cart.btn-action-cart {
            background: #f0c2c2;
            color: #2b4a66;
            border: 1px solid rgba(14, 63, 108, .12);
        }

        /* Add to Cart (white outline) hover */
        #packages-special .btn-add-cart.btn-action-cart:hover {
            background: #f7faff;
            /* slight blue tint */
            border-color: rgba(14, 63, 108, .25);
            /* slightly darker border */
            color: #173b5f;
        }


        #packages-special .btn-go-cart.btn-action-cart {
            background: #9dd24a !important;
            color: #fff !important;
            border: 0 !important;
            box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
        }

        /* Go to Cart (green) hover */
        #packages-special .btn-go-cart.btn-action-cart:hover {
            background: #8ac83d !important;
            /* slightly darker green */
            color: #fff !important;
            filter: none;
        }


        /* Progress state */
        #packages-special .btn-action-cart.is-adding {
            pointer-events: none;
            opacity: .9;
        }

        #packages-special .btn-view-info i,
        #packages-special .btn-action-cart i {
            font-size: 1.05em;
        }

        /* FINAL OVERRIDE: force green Go-to-Cart even if some global CSS sets blue */
        #packages-special .btn-go-cart,
        #packages-special .btn-go-cart.btn-action-cart,
        #packages-special .btn-go-cart.btn-cart,
        #packages-special .btn-go-cart.btn {
            background: #9dd24a !important;
            color: #fff !important;
            border: 0 !important;
            box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
        }

        #packages-special .btn-go-cart:hover {
            filter: brightness(0.95);
        }

        #packages-special .btn-go-cart:active {
            filter: brightness(0.9);
        }

        /* Space between View Info & Add/Go to Cart buttons */
        #packages-special .btn-foreground {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            /* <-- Increase/Decrease spacing here */
        }


        #packages-special .btn-add-cart.btn-action-cart:active {
            background: #eef4ff;
        }

        #packages-special .btn-go-cart.btn-action-cart:active {
            background: #7ebd33 !important;
            /* deeper green */
        }

        /* Center slider items when total width is smaller than container (e.g., only 1–2 items) */
        #packages-special .slider-row {
            display: flex;
            flex-wrap: nowrap;
        }

        #packages-special .slider-scroll.no-scroll .slider-row {
            justify-content: center;
        }

        /* Hide scrollbar for WebKit browsers (Chrome, Edge, Safari) */
        #packages-special .slider-scroll::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for Firefox */
        #packages-special .slider-scroll {
            scrollbar-width: none;
        }

        /* Hide scrollbar for IE/Edge Legacy */
        #packages-special .slider-scroll {
            -ms-overflow-style: none;
        }



        @media (min-width: 577px) {
            #packages-special .btn-foreground {
                gap: 16px;
            }
        }

        /* -------- Responsive: compact on small screens -------- */
        @media (max-width:576px) {

            #packages-special .btn-view-info,
            #packages-special .btn-action-cart {
                padding: .55rem .9rem;
                font-size: .92rem;
                min-height: 40px;
                /* slightly shorter on mobile */
                min-width: 120px;
            }

            /* keep them usable when stacked */
            #packages-special .btn-foreground {
                gap: .5rem;
            }
        }

        /* ================== DISABLE ALL HOVER EFFECTS (SPECIAL PACKAGES) ================== */

        #packages-special .pretty-card {
            transition: none !important;
        }

        #packages-special .pretty-card:hover {
            transform: none !important;
            box-shadow:
                0 8px 18px rgba(10, 38, 64, .08),
                0 2px 6px rgba(10, 38, 64, .04) !important;
        }

        @media (hover: none) {
            #packages-special .pretty-card {
                transform: none !important;
            }
        }

        #packages-special .title-link:hover,
        #packages-special .img-fixed:hover {
            opacity: 1 !important;
        }
    </style>

    <script>
        /* ---------- Slider controls (unchanged) ---------- */
        /* ---------- Slider controls (center 1–3 items) ---------- */
        (function () {
            const root = document.getElementById('packages-special');
            if (!root) return;

            const scroller = root.querySelector('.slider-scroll');
            const row = root.querySelector('.slider-row');
            const prevBtn = root.querySelector('.btn-slider-left');
            const nextBtn = root.querySelector('.btn-slider-right');
            if (!scroller || !row) return;

            const getGap = () => parseFloat(getComputedStyle(row).columnGap) || 32;
            const step = () => {
                const item = scroller.querySelector('.slider-item');
                return item ? item.getBoundingClientRect().width + getGap() : Math.round(scroller.clientWidth *
                    0.8);
            };

            function updateLayout() {
                const itemCount = row.children.length;
                const totalWidth = row.scrollWidth; // total content width
                const viewportW = scroller.clientWidth;
                const noOverflow = totalWidth <= (viewportW + 1); // fits without scrolling
                const shouldCenter = noOverflow || itemCount <= 3; // center when 1–3 cards

                // Toggle centering class (you already have the CSS for this)
                scroller.classList.toggle('no-scroll', shouldCenter);

                // Arrow visibility / disabled states
                const max = Math.max(0, scroller.scrollWidth - viewportW);
                const atStart = scroller.scrollLeft <= 8;
                const atEnd = scroller.scrollLeft >= (max - 8);

                [prevBtn, nextBtn].forEach((b) => {
                    if (!b) return;
                    if (shouldCenter) {
                        b.disabled = true;
                        b.setAttribute('aria-hidden', 'true');
                        b.hidden = true;
                    } else {
                        b.hidden = false;
                        b.setAttribute('aria-hidden', 'false');
                        b.disabled = (b === prevBtn ? (atStart || max === 0) : (atEnd || max === 0));
                        b.setAttribute('aria-disabled', b.disabled ? 'true' : 'false');
                        b.setAttribute('aria-controls', scroller.id || '');
                    }
                });
            }

            function smoothBy(dx) {
                try {
                    scroller.scrollBy({
                        left: dx,
                        behavior: 'smooth'
                    });
                } catch {
                    scroller.scrollLeft += dx;
                }
            }

            prevBtn?.addEventListener('click', () => smoothBy(-step()));
            nextBtn?.addEventListener('click', () => smoothBy(step()));
            scroller.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    smoothBy(step());
                }
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    smoothBy(-step());
                }
            });
            scroller.addEventListener('scroll', updateLayout, {
                passive: true
            });
            window.addEventListener('resize', () => requestAnimationFrame(updateLayout));

            // init
            updateLayout();
        })();
        /* ---------- CART: Add → Go to Cart (no blue color) ---------- */
        (function () {
            const CART_ADD_URL = "{{ route('cart.add') }}";
            const CART_ITEMS_URL = "{{ route('cart.items') }}";
            const CART_URL = "{{ route('cart.index') }}";
            const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            function convertToGoToCart(btn) {
                if (!btn) return;
                // cleanup any classes that might paint it blue
                btn.classList.remove('btn-add-cart', 'is-adding', 'btn-primary', 'btn-cart');
                // ensure base + state classes
                if (!btn.classList.contains('btn-action-cart')) btn.classList.add('btn-action-cart');
                btn.classList.add('btn-go-cart');

                // wipe any inline styles that could carry over
                btn.removeAttribute('style');

                // set content & click
                btn.disabled = false;
                btn.innerHTML =
                    '<i class="fa fa-shopping-cart me-1" aria-hidden="true"></i><span class="btn-text">Go to Cart</span>';
                btn.onclick = (e) => {
                    e?.preventDefault?.();
                    e?.stopPropagation?.();
                    window.location.href = "{{ route('cart.index') }}";
                };
            }

            function setAddingState(btn) {
                if (!btn) return;
                btn.classList.add('is-adding');
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding…';
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
                btn.innerHTML =
                    '<i class="fa fa-cart-plus me-1" aria-hidden="true"></i><span class="btn-text">Add to Cart</span>';
                btn.onclick = (e) => {
                    e?.preventDefault?.();
                    e?.stopPropagation?.();
                    addToCart(e, 'package', id);
                };
            }

            async function refreshSpecialCartButtons() {
                try {
                    const res = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
                        headers: {
                            'Accept': 'application/json',
                            'Cache-Control': 'no-cache',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
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
                        if (ids.has(id)) convertToGoToCart(btn);
                        else convertToAdd(btn, id);
                    });

                    if (data && data.count !== undefined) {
                        const badge = document.querySelector('#cart-count-badge');
                        if (badge) badge.textContent = data.count;
                    }
                } catch (e) {
                    console.warn('refreshSpecialCartButtons failed:', e);
                }
            }

            if (typeof window.addToCart !== 'function') {
                window.addToCart = async function (event, type, id) {
                    event?.preventDefault?.();
                    event?.stopPropagation?.();

                    const btn = (event && event.target) ? event.target.closest('button') : document
                        .querySelector('#cart-btn-' + id);
                    if (!btn) return;

                    if (btn.classList.contains('btn-go-cart')) {
                        window.location.href = CART_URL;
                        return;
                    }

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
                            body: JSON.stringify({
                                item_type: type,
                                item_id: id,
                                quantity: 1
                            }),
                            cache: 'no-store',
                            credentials: 'same-origin'
                        });

                        const ct = res.headers.get('content-type') || '';
                        const isJson = ct.includes('application/json');
                        const data = isJson ? await res.json().catch(() => ({})) : {};

                        if (!res.ok) {
                            if (res.status === 401) {
                                window.location.href = '/login';
                                return;
                            }
                            if (res.status === 419) {
                                alert('Session expired. Please refresh and try again.');
                                unsetAddingState(btn, original);
                                return;
                            }
                            alert((data && (data.message || data.error)) ? (data.message || data.error) :
                                'Failed to add to cart.');
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
                            alert((data && (data.message || data.error)) ? (data.message || data.error) :
                                'Failed to add to cart.');
                            unsetAddingState(btn, original);
                        }
                    } catch (err) {
                        console.error('addToCart error', err);
                        alert('Something went wrong while adding to cart.');
                        unsetAddingState(btn, original);
                    }
                };
            }

            document.addEventListener('DOMContentLoaded', refreshSpecialCartButtons, {
                once: true
            });
            window.addEventListener('pageshow', refreshSpecialCartButtons);
        })();


        document.addEventListener('DOMContentLoaded', () => {

            const cards = document.querySelectorAll('#packages-special .pretty-card');

            if (!('IntersectionObserver' in window)) {
                // Fallback for old browsers
                cards.forEach(card => card.classList.add('is-visible'));
                return;
            }

            // const observer = new IntersectionObserver((entries, obs) => {
            //   entries.forEach(entry => {
            //     if (entry.isIntersecting) {
            //       entry.target.classList.add('is-visible');
            //       obs.unobserve(entry.target); // animate only once
            //     }
            //   });
            // }, {
            //   threshold: 0.2   // 20% of card visible
            // });

            cards.forEach(card => observer.observe(card));
        });


        (function () {
            const root = document.getElementById('packages-special');
            if (!root) return;

            const scroller = root.querySelector('.slider-scroll');
            const row = root.querySelector('.slider-row');
            if (!scroller || !row) return;

            let autoScrollInterval = null;
            const SCROLL_DELAY = 5000; // time between moves (ms)
            const SCROLL_SPEED = 'smooth';

            function getStep() {
                const item = scroller.querySelector('.slider-item');
                const gap = parseFloat(getComputedStyle(row).columnGap) || 32;
                return item ? item.offsetWidth + gap : 300;
            }

            function autoScroll() {
                const maxScroll = scroller.scrollWidth - scroller.clientWidth;
                const next = scroller.scrollLeft + getStep();

                if (next >= maxScroll - 5) {
                    // go back to start
                    scroller.scrollTo({
                        left: 0,
                        behavior: SCROLL_SPEED
                    });
                } else {
                    scroller.scrollBy({
                        left: getStep(),
                        behavior: SCROLL_SPEED
                    });
                }
            }

            function startAutoScroll() {
                if (autoScrollInterval) return;
                autoScrollInterval = setInterval(autoScroll, SCROLL_DELAY);
            }

            function stopAutoScroll() {
                clearInterval(autoScrollInterval);
                autoScrollInterval = null;
            }

            // Pause on hover / focus
            scroller.addEventListener('mouseenter', stopAutoScroll);
            scroller.addEventListener('mouseleave', startAutoScroll);
            scroller.addEventListener('focusin', stopAutoScroll);
            scroller.addEventListener('focusout', startAutoScroll);

            // Pause on manual interaction
            scroller.addEventListener('wheel', stopAutoScroll, {
                passive: true
            });
            scroller.addEventListener('touchstart', stopAutoScroll, {
                passive: true
            });

            // Resume after interaction
            scroller.addEventListener('touchend', startAutoScroll);

            // Start when page is ready
            window.addEventListener('load', startAutoScroll);
        })();
    </script>


    {{-- ========================= END HOME: SPECIAL PACKAGES ========================= --}}

    {{-- ========================= END SPECIAL PACKAGES SECTION ========================= --}}











    {{-- packages (home) --}}

    {{-- resources/views/partials/packages-row-arrows.blade.php --}}

    {{-- ========================= HOME: BASIC PACKAGES (same UI as SPECIAL) ========================= --}}

    <section id="packages" class="py-5" style="
    max-width:100%;
    margin:auto;
    background:#f0f4f8;
    box-shadow:
      inset 0 10px 25px -10px rgba(0,0,0,0.06),
      inset 0 -10px 25px -10px rgba(0,0,0,0.06);
    padding-top:4rem !important;
    margin-top:2rem;
    padding-bottom:3rem !important;
    margin-bottom:2rem;
    border-bottom:1px solid rgba(0,0,0,0.05);
  ">

        <div class="text-center mb-5 px-3 px-md-5" style="max-width:800px;margin:0 auto;">
            <h2 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
                Wellcare Smart Health <span style="color:#0d6efd;">Checkup Packages</span>
            </h2>
            <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                background:linear-gradient(90deg,#0047ff,#00ccff);">
            </div>
            <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
                Backed by Doctors. Believed by Patients. </p>
        </div>

        @if (isset($packages) && $packages->count())
            <div class="slider-wrap position-relative">
                <!-- arrows (top-right like special) -->
                <button class="btn btn-slider btn-slider-left" type="button" aria-label="Scroll left">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </button>
                <button class="btn btn-slider btn-slider-right" type="button" aria-label="Scroll right">
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </button>

                <!-- horizontal slider -->
                <div class="slider-scroll overflow-auto" tabindex="0" role="list" aria-label="Basic packages slider">
                    <div class="slider-row d-flex flex-nowrap m-0"><!-- 32px gap -->

                        @foreach ($packages->take(12) as $pkg)
                            @php
                                        $title = $pkg->title ?? 'Package';
                                        $packageParam = $pkg->slug ?? $pkg->id;

                                        /* ===== IMAGE HANDLING (PER PACKAGE – REQUIRED) ===== */
                                        $banner = $pkg->banner ? ltrim($pkg->banner, '/') : null;
                                        $bannerPath = $banner ? storage_path('app/public/' . $banner) : null;
                                        $hasBanner = $bannerPath && file_exists($bannerPath);

                                        $defaultImage = asset('Front_end/assets/img/blog/default-package.jpg');

                                        $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 360'>
                                  <rect width='100%' height='100%' fill='#f6fbfb'/>
                                  <text x='50%' y='50%' dominant-baseline='middle'
                                        text-anchor='middle' fill='#9aa0a6'
                                        font-size='20'>No image available</text>
                                </svg>";

                                        $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode($svg);

                                        /* ===== PRICE HANDLING (PER PACKAGE – REQUIRED) ===== */
                                        $mrp = (float) ($pkg->mrp ?? 0);
                                        $discounted = (float) ($pkg->discounted_price ?? 0);
                                        $price = (float) ($pkg->price ?? 0);

                                        /* Final price priority */
                                        $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);

                                        /* Discount calculations */
                                        $savePercent =
                                            $mrp > 0 && $displayPrice
                                            ? round((($mrp - $displayPrice) / $mrp) * 100)
                                            : 0;

                                        $savingAmount =
                                            $mrp > 0 && $displayPrice && $mrp > $displayPrice
                                            ? $mrp - $displayPrice
                                            : 0;

                                        /* ===== TEST CHIPS (FINAL, CORRECT) ===== */
                                        $testChips = [];

                                        /* 1️⃣ Relation-based tests */
                                        if (isset($pkg->tests) && is_iterable($pkg->tests) && count($pkg->tests)) {
                                            foreach ($pkg->tests as $t) {
                                             
                                                $testChips[] = trim($t->test_name ?? ($t->title ?? ($t->name ?? '')));
                                            }

                                            /* 2️⃣ Comma-separated tests_list */
                                        } elseif (!empty($pkg->tests_list) && is_string($pkg->tests_list)) {
                                            $testChips = array_map('trim', explode(',', strip_tags($pkg->tests_list)));

                                            /* 3️⃣ Fallback */
                                        } else {
                                              
                                            $parts = preg_split('/\r\n|\n|,/', strip_tags($pkg->content ?? ''));
                                            $parts = array_map('trim', $parts);
                                            $testChips = array_filter($parts);
                                        }

                                        /* Remove duplicates */
                                        $seen = [];
                                        $testChips = array_values(
                                            array_filter($testChips, function ($v) use (&$seen) {
                                                $k = mb_strtolower($v);
                                                if ($k === '' || isset($seen[$k])) {
                                                    return false;
                                                }
                                                $seen[$k] = true;
                                                return true;
                                            }),
                                        );

                                        $maxShow = 4;
                                        $totalBadges = count($testChips);
                                        
                                        $showBadges = array_slice($testChips, 0, $maxShow);
                                   
                                      

                            @endphp


                            <div class="slider-item" role="listitem" aria-label="{{ $title }}">
                                <div class="pretty-card h-100 d-flex flex-column position-relative">
                                    <a class="img-fixed rounded-top-4 overflow-hidden bg-light d-block"
                                        href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                        aria-label="Open {{ $title }}">
                                        @if ($hasBanner)
                                            <img src="{{ asset('storage/' . $banner) }}" class="w-100 h-100" alt="{{ $title }}"
                                                loading="lazy" style="object-fit:cover;">
                                        @elseif(file_exists(public_path('Front_end/assets/img/blog/default-package.jpg')))
                                            <img src="{{ $defaultImage }}" class="w-100 h-100" alt="{{ $title }}" loading="lazy"
                                                style="object-fit:cover;">
                                        @else
                                            <img src="{{ $placeholder }}" class="w-100 h-100" alt="No image" loading="lazy"
                                                style="object-fit:cover;">
                                        @endif
                                    </a>

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title mb-2 pkg-title">
                                            <a href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                                class="title-link text-decoration-none">
                                                {{ \Illuminate\Support\Str::limit($title, 60) }}
                                            </a>
                                        </h5>

                                        <div class="chips mb-2">
                                            @foreach ($showBadges as $b)
                                                <a href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                                    class="chip-pill" title="{{ strip_tags($b) }}">
                                                    <span class="chip-text">{{ $b }}</span>
                                                </a>
                                            @endforeach

                                            @if ($totalBadges > $maxShow)
                                                <a href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                                    class="chip-pill chip-more">
                                                    +{{ $totalBadges - $maxShow }} more
                                                </a>
                                            @endif
                                        </div>

                                        <div class="flex-grow-1"></div>

                                        <div class="purchase-meta mb-2">
                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                @if ($displayPrice)
                                                    <div class="price-final">₹{{ number_format($displayPrice, 0) }}
                                                    </div>
                                                    @if ($mrp > 0 && $mrp > $displayPrice)
                                                        <div class="price-mrp">₹{{ number_format($mrp, 0) }}</div>
                                                    @endif
                                                    @if ($savePercent > 0)
                                                        <span class="discount-pill">{{ $savePercent }}% OFF</span>
                                                    @endif
                                                @else
                                                    <div class="price-final">Contact</div>
                                                    <small class="text-muted">for price</small>
                                                @endif
                                            </div>

                                            @if ($savingAmount > 0)
                                                <div class="save-link">You save
                                                    ₹{{ number_format($savingAmount, 0, '.', ',') }}</div>
                                            @endif
                                        </div>

                                        {{-- ACTIONS (same size/behavior as special) --}}
                                        <div class="d-flex flex-column flex-sm-row gap-2 btn-foreground">
                                            <a href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                                class="btn btn-view-info btn-sm flex-fill flex-sm-grow-0"
                                                onclick="event.stopPropagation();">
                                                <i class="fa fa-info-circle me-1" aria-hidden="true"></i> View Info
                                            </a>

                                            <button type="button" id="cart-btn-{{ $pkg->id }}"
                                                class="btn btn-action-cart btn-add-cart btn-sm flex-fill"
                                                onclick="event.preventDefault(); event.stopPropagation(); addToCartBasic(event, 'package', {{ $pkg->id }});">
                                                <i class="fa fa-cart-plus me-1" aria-hidden="true"></i>
                                                <span class="btn-text">Add to Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/packages') }}" class="btn btn-primary">Show All Smart Health packages </a>
            </div>
        @else
            <div class="text-center px-3" style="max-width:800px;margin:0 auto;">
                <p class="text-muted mb-0" style="font-size:1.05rem;color:#6b7280;margin:0;">
                    No packages are available at the moment. Please check back soon.
                </p>
            </div>
        @endif
    </section>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        #packages .chip-pill {
            align-items: flex-start;
        }

        #packages .chip-text {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
            max-width: none !important;
            line-height: 1.25;
            display: inline;
        }

        /* --- Slider (cloned from #packages-special, namespaced to #packages) --- */
        #packages .slider-wrap {
            position: relative;
            padding-top: 8px;
        }

        #packages .slider-scroll {
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: .25rem;
        }

        #packages .slider-row {
            column-gap: 32px;
        }

        #packages .slider-item {
            scroll-snap-align: start;
            flex: 0 0 auto;
            width: 320px;
        }

        /* Mobile slider styling for Main Packages */
        @media (max-width: 767px) {
            #packages .slider-scroll {
                scroll-snap-type: x mandatory !important;
                overflow-x: auto !important;
                overflow-y: visible !important;
                height: auto !important;
                padding-left: 16px !important;
                padding-right: 16px !important;
            }
            #packages .slider-row {
                flex-wrap: nowrap !important;
                height: auto !important;
                column-gap: 16px !important;
                row-gap: 0 !important;
            }
            #packages .slider-item {
                width: 290px !important;
                min-width: 270px !important;
                max-width: 320px !important;
                scroll-snap-align: start !important;
                flex: 0 0 auto !important;
            }
        }

        @media (min-width: 577px) and (max-width: 991px) {
            #packages .slider-item {
                width: 340px;
            }
        }





        /* ✅ Proper left gutter without cutting card */
        #packages .slider-wrap {
            padding-left: 48px;
        }

        @media (max-width:576px) {
            #packages .slider-wrap {
                padding-left: 24px;
            }
        }

        @media (max-width:576px) {
            #packages .img-fixed {
                height: auto;
                aspect-ratio: 4 / 3;
                /* perfect for medical banners */
            }

            #packages .img-fixed img {
                object-fit: contain;
                background: #eaf2ff;
                /* optional */
            }
        }


        /* Side buttons (top-right) */
        #packages .btn-slider {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(180deg, #1c5b89 0%, #164a75 100%);
            box-shadow: 0 10px 24px rgba(11, 46, 78, .18);
            transition: transform .12s ease, filter .12s ease, box-shadow .12s ease;
            position: absolute !important;
            top: -36px !important;
            transform: none !important;
            z-index: 10;
        }

        #packages .btn-slider:hover {
            filter: brightness(1.06);
            box-shadow: 0 12px 26px rgba(11, 46, 78, .24);
        }

        #packages .btn-slider:active {
            transform: translateY(1px);
        }

        #packages .btn-slider:disabled {
            opacity: .45;
            cursor: default;
            box-shadow: 0 6px 16px rgba(11, 46, 78, .12);
        }

        #packages .btn-slider-left {
            right: 64px !important;
        }

        #packages .btn-slider-right {
            right: 6px !important;
        }

        #packages .btn-slider .bi {
            font-size: 1.05rem;
            line-height: 1;
        }

        @media (max-width:576px) {
            #packages .btn-slider {
                width: 36px;
                height: 36px;
            }
        }

        /* Cards */
        #packages .pretty-card {
            background: #fff;
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(10, 38, 64, .06), 0 3px 8px rgba(10, 38, 64, .04);
            overflow: hidden;
        }

        #packages .pretty-card {
            opacity: 1;
            transform: none;
        }


        /* ===== Scroll-in Fade Up (Basic Packages) ===== */
        /* #packages .pretty-card {
                          opacity: 0;
                          transform: translateY(24px);
                          transition:
                            opacity 500ms ease,
                            transform 500ms cubic-bezier(.2,.8,.2,1),
                            box-shadow 220ms cubic-bezier(.2,.8,.2,1);
                        } */

        /* #packages .pretty-card.is-visible {
                          opacity: 1;
                          transform: translateY(0);
                        } */

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            #packages .pretty-card {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }


        /* ===== Hover Lift (Basic Packages) ===== */
        #packages .pretty-card:hover {
            box-shadow:
                0 18px 40px rgba(10, 38, 64, .16),
                0 6px 14px rgba(10, 38, 64, .08);
            transform: translateY(-4px);
        }

        #packages .slider-scroll {
            scroll-behavior: smooth;
        }


        #packages .card-body {
            padding: 18px;
        }

        /* Image */
        #packages .img-fixed {
            height: 220px;
            width: 100%;
        }

        @media (max-width:576px) {
            #packages .img-fixed {
                height: 200px;
            }
        }

        /* Title */
        #packages .pkg-title {
            margin: 0;
            font-size: 1.03rem;
            font-weight: 700;
        }

        #packages .title-link {
            color: #ff2b6d;
        }

        #packages .title-link:hover {
            opacity: .9;
        }

        /* Chips */
        #packages .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        #packages .chip-pill {
            display: inline-flex;
            align-items: center;
            max-width: 80%;
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

        #packages .chip-pill .text-truncate {
            display: inline-block;
            max-width: 170px;
        }

        @media (max-width:576px) {
            #packages .chip-pill .text-truncate {
                max-width: 130px;
            }
        }

        #packages .chip-more {
            background: #eef5ff;
        }

        /* Price block */
        #packages .price-final {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1a3a66;
        }

        #packages .price-mrp {
            color: #000 !important;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 600;
        }

        #packages .discount-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e9f9ef;
            color: #2a7b3f;
            font-weight: 700;
            font-size: .8rem;
            box-shadow: inset 0 0 0 1px rgba(42, 123, 63, .08);
        }

        /* Gap between ₹Final • ₹MRP • %OFF */
        #packages .purchase-meta .d-flex.align-items-center {
            gap: 10px;
        }

        #packages .save-link {
            color: #0a66d6;
            font-weight: 600;
            font-size: .9rem;
        }

        /* Buttons layering */
        #packages .btn-foreground,
        #packages .btn-foreground .btn {
            position: relative;
            z-index: 5;
        }

        /* View Info button */
        #packages .btn-view-info {
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

        #packages .btn-view-info i {
            font-size: .8rem;
        }

        #packages .btn-view-info:hover {
            background: #f5f9ff;
        }

        /* Cart buttons (identical look) */
        #packages .btn-action-cart {
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

        #packages .btn-add-cart.btn-action-cart {
            background: #f0c2c2;
            color: #2b4a66;
            border: 1px solid rgba(14, 63, 108, .12);
        }

        #packages .btn-add-cart.btn-action-cart:hover {
            background: #f7faff;
            border-color: rgba(14, 63, 108, .25);
            color: #173b5f;
        }

        #packages .btn-go-cart.btn-action-cart {
            background: #9dd24a !important;
            color: #fff !important;
            border: 0 !important;
            box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
        }

        #packages .btn-go-cart.btn-action-cart:hover {
            background: #8ac83d !important;
            color: #fff !important;
        }

        #packages .btn-action-cart.is-adding {
            pointer-events: none;
            opacity: .9;
        }

        #packages .btn-go-cart:active {
            background: #7ebd33 !important;
        }

        /* Space between View Info & Add/Go buttons */
        #packages .btn-foreground {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (min-width:577px) {
            #packages .btn-foreground {
                gap: 16px;
            }
        }

        /* Center items when not scrollable */
        #packages .slider-row {
            display: flex;
            flex-wrap: nowrap;
        }

        #packages .slider-scroll.no-scroll .slider-row {
            justify-content: center;
        }

        /* Hide scrollbars but keep scrollable */
        #packages .slider-scroll::-webkit-scrollbar {
            display: none;
        }

        #packages .slider-scroll {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }


        /* Responsive button sizing */
        @media (max-width:576px) {

            #packages .btn-view-info,
            #packages .btn-action-cart {
                padding: .55rem .9rem;
                font-size: .92rem;
                min-height: 40px;
                min-width: 120px;
            }

            #packages .btn-foreground {
                gap: .5rem;
            }
        }




        /* ✅ Left gutter like Basic Packages */
        #packages-special .slider-wrap {
            padding-left: 48px;
        }

        @media (max-width:576px) {
            #packages-special .slider-wrap {
                padding-left: 24px;
            }
        }

        /* Tablet */
        @media (min-width:577px) and (max-width:991px) {
            #packages-special .slider-item {
                width: 340px;
            }
        }

        /* Mobile */
        @media (max-width:576px) {
            #packages-special .slider-item {
                width: 300px;
            }
        }

        @media (max-width:576px) {
            #packages-special .img-fixed {
                height: auto;
                aspect-ratio: 4 / 3;
            }

            #packages-special .img-fixed img {
                object-fit: contain;
                background: #eaf2ff;
            }
        }


        /* Disable ALL hover effects for cards */
        #packages .pretty-card {
            transition: none !important;
        }

        #packages .pretty-card:hover {
            transform: none !important;
            box-shadow: 0 10px 20px rgba(10, 38, 64, .06),
                0 3px 8px rgba(10, 38, 64, .04) !important;
        }

        @media (hover: none) {
            #packages .pretty-card {
                transform: none !important;
            }
        }
    </style>

    <script>
        /* ---------- Slider controls (same behavior as special, scoped to #packages) ---------- */
        (function () {
            const root = document.getElementById('packages');
            if (!root) return;
            const scroller = root.querySelector('.slider-scroll');
            const row = root.querySelector('.slider-row');
            const prevBtn = root.querySelector('.btn-slider-left');
            const nextBtn = root.querySelector('.btn-slider-right');

            const step = () => {
                const item = scroller.querySelector('.slider-item');
                const gap = parseFloat(getComputedStyle(row).columnGap) || 32;
                return item ? item.getBoundingClientRect().width + gap : Math.round(scroller.clientWidth * 0.8);
            };

            function smoothBy(dx) {
                try {
                    scroller.scrollBy({
                        left: dx,
                        behavior: 'smooth'
                    });
                } catch {
                    scroller.scrollLeft += dx;
                }
            }

            function updateEdges() {
                const max = Math.max(0, scroller.scrollWidth - scroller.clientWidth);
                const atStart = scroller.scrollLeft <= 8;
                const atEnd = scroller.scrollLeft >= (max - 8);
                if (prevBtn) prevBtn.disabled = atStart || max === 0;
                if (nextBtn) nextBtn.disabled = atEnd || max === 0;

                // center when not scrollable
                const noScroll = scroller.scrollWidth <= scroller.clientWidth + 5;
                scroller.classList.toggle('no-scroll', noScroll);
            }

            prevBtn?.addEventListener('click', () => smoothBy(-step()));
            nextBtn?.addEventListener('click', () => smoothBy(step()));
            scroller.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    smoothBy(step());
                }
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    smoothBy(-step());
                }
            });
            scroller.addEventListener('scroll', updateEdges, {
                passive: true
            });
            window.addEventListener('resize', () => requestAnimationFrame(updateEdges));
            updateEdges();
        })();

        /* ---------- CART: Add → Go to Cart (mirrors special; scoped; no blue) ---------- */
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
                btn.innerHTML =
                    '<i class="fa fa-shopping-cart me-1" aria-hidden="true"></i><span class="btn-text">Go to Cart</span>';
                btn.onclick = (e) => {
                    e?.preventDefault?.();
                    e?.stopPropagation?.();
                    window.location.href = CART_URL;
                };
            }

            function convertToAdd(btn, id) {
                if (!btn) return;
                btn.classList.remove('btn-go-cart', 'is-adding');
                if (!btn.classList.contains('btn-action-cart')) btn.classList.add('btn-action-cart');
                if (!btn.classList.contains('btn-add-cart')) btn.classList.add('btn-add-cart');
                btn.innerHTML =
                    '<i class="fa fa-cart-plus me-1" aria-hidden="true"></i><span class="btn-text">Add to Cart</span>';
                btn.onclick = (e) => {
                    e?.preventDefault?.();
                    e?.stopPropagation?.();
                    addToCartBasic(e, 'package', id);
                };
            }

            function setAdding(btn) {
                if (!btn) return;
                btn.classList.add('is-adding');
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding…';
            }

            function unsetAdding(btn, html) {
                if (!btn) return;
                btn.classList.remove('is-adding');
                btn.disabled = false;
                btn.innerHTML = html;
            }

            async function refreshBasicCartButtons() {
                try {
                    const res = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
                        headers: {
                            'Accept': 'application/json',
                            'Cache-Control': 'no-cache',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
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

                    document.querySelectorAll('#packages button[id^="cart-btn-"]').forEach(btn => {
                        const idAttr = btn.getAttribute('id') || '';
                        const id = Number(idAttr.replace('cart-btn-', '')) || Number(btn.dataset.pkgId);
                        if (!id) return;
                        if (ids.has(id)) convertToGoToCart(btn);
                        else convertToAdd(btn, id);
                    });

                    if (data && data.count !== undefined) {
                        const badge = document.querySelector('#cart-count-badge');
                        if (badge) badge.textContent = data.count;
                    }
                } catch (e) {
                    console.warn('refreshBasicCartButtons failed:', e);
                }
            }

            // scoped addToCart for this section
            window.addToCartBasic = window.addToCartBasic || async function (event, type, id) {
                event?.preventDefault?.();
                event?.stopPropagation?.();
                const btn = (event && event.target) ? event.target.closest('button') : document.querySelector(
                    '#cart-btn-' + id);
                if (!btn) return;
                if (btn.classList.contains('btn-go-cart')) {
                    window.location.href = CART_URL;
                    return;
                }

                const original = btn.innerHTML;
                setAdding(btn);
                try {
                    const res = await fetch(CART_ADD_URL, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            item_type: type,
                            item_id: id,
                            quantity: 1
                        }),
                        cache: 'no-store',
                        credentials: 'same-origin'
                    });

                    const ct = res.headers.get('content-type') || '';
                    const isJson = ct.includes('application/json');
                    const data = isJson ? await res.json().catch(() => ({})) : {};

                    if (!res.ok) {
                        if (res.status === 401) {
                            window.location.href = '/login';
                            return;
                        }
                        if (res.status === 419) {
                            alert('Session expired. Please refresh and try again.');
                            unsetAdding(btn, original);
                            return;
                        }
                        alert((data && (data.message || data.error)) ? (data.message || data.error) :
                            'Failed to add to cart.');
                        unsetAdding(btn, original);
                        return;
                    }

                    if (data && (data.success || data.status === 'success')) {
                        convertToGoToCart(btn);
                        if (data.count !== undefined) {
                            const badge = document.querySelector('#cart-count-badge');
                            if (badge) badge.textContent = data.count;
                        }
                        refreshBasicCartButtons();
                    } else {
                        alert((data && (data.message || data.error)) ? (data.message || data.error) :
                            'Failed to add to cart.');
                        unsetAdding(btn, original);
                    }
                } catch (err) {
                    console.error('addToCart error', err);
                    alert('Something went wrong while adding to cart.');
                    unsetAdding(btn, original);
                }
            };

            document.addEventListener('DOMContentLoaded', refreshBasicCartButtons, {
                once: true
            });
            window.addEventListener('pageshow', refreshBasicCartButtons);
        })();


        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('#packages .pretty-card');

            if (!('IntersectionObserver' in window)) {
                cards.forEach(c => c.classList.add('is-visible'));
                return;
            }

            // const observer = new IntersectionObserver((entries, obs) => {
            //   entries.forEach(entry => {
            //     if (entry.isIntersecting) {
            //       entry.target.classList.add('is-visible');
            //       obs.unobserve(entry.target); // animate once
            //     }
            //   });
            // }, { threshold: 0.2 });

            cards.forEach(card => observer.observe(card));
        });



        // (function () {
        //   const root = document.getElementById('packages');
        //   if (!root) return;

        //   const scroller = root.querySelector('.slider-scroll');
        //   const row = root.querySelector('.slider-row');
        //   if (!scroller || !row) return;

        //   let autoScrollInterval = null;
        //   const SCROLL_DELAY = 5000;

        //   function getStep() {
        //     const item = scroller.querySelector('.slider-item');
        //     const gap = parseFloat(getComputedStyle(row).columnGap) || 32;
        //     return item ? item.offsetWidth + gap : 300;
        //   }

        //   function autoScroll() {
        //     const max = scroller.scrollWidth - scroller.clientWidth;
        //     const next = scroller.scrollLeft + getStep();

        //     if (next >= max - 5) {
        //       scroller.scrollTo({ left: 0, behavior: 'smooth' });
        //     } else {
        //       scroller.scrollBy({ left: getStep(), behavior: 'smooth' });
        //     }
        //   }

        //   function start() {
        //     if (!autoScrollInterval) {
        //       autoScrollInterval = setInterval(autoScroll, SCROLL_DELAY);
        //     }
        //   }

        //   function stop() {
        //     clearInterval(autoScrollInterval);
        //     autoScrollInterval = null;
        //   }

        //   /* Interaction-aware pause */
        //   scroller.addEventListener('mouseenter', stop);
        //   scroller.addEventListener('mouseleave', start);
        //   scroller.addEventListener('touchstart', stop, { passive: true });
        //   scroller.addEventListener('touchend', start);
        //   scroller.addEventListener('wheel', stop, { passive: true });

        //   window.addEventListener('load', start);
        // })();
    </script>
    {{-- ========================= END HOME: BASIC PACKAGES ========================= --}}








    <!-- Services Section -->
    {{-- <div>
        <h1 style="text-align: center; font-weight: bold; padding-top: 40px;">
            Our Services
        </h1>
    </div>
    {{-- LAB TESTS --}}
    {{-- <section class="lab-tests py-5">
        <div class="container">
            <div class="row">
                @forelse($tests as $t)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0"> --}}
                        {{-- Card Body --}}
                        {{-- <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $t->test_name }}</h5> --}}

                            {{-- Description (instead of banner)
                            @if (!empty($t->description))
                            <p class="text-muted mb-3" style="min-height: 80px;">
                                {{ Str::limit($t->description, 150) }}
                            </p>
                            @else
                            <p class="text-muted mb-3">No description available.</p>
                            @endif --}}

                            {{-- Pricing --}}
                            {{-- <p class="card-text mb-2">
                                <span class="text-decoration-line-through text-muted">
                                    ₹{{ number_format((float)($t->mrp ?? 0), 2) }}
                                </span><br>
                                <strong class="text-success fs-6">
                                    ₹{{ number_format((float)($t->discounted_price ?? 0), 2) }}
                                </strong>
                            </p>

                            {{-- Footer --}}
                            {{-- <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="badge bg-success text-white text-capitalize">
                                    {{ $t->status ?? 'published' }}
                                </span>
                                <a href="{{ route('appointments.create', ['test_id' => $t->id]) }}"
                                    class="btn btn-sm btn-primary">
                                    Book Now
                                </a> --}}
                                {{--
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center text-muted py-4">
                            No lab tests published yet.
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>
    --}}
    <!-- Services Section -->
    {{-- LAB TESTS (package-style cards without image) --}}

    <!-- Lab Tests Section -->
    <!-- Lab Tests Section -->
    <!-- Lab Tests Section -->

    <!-- Lab Tests Section -->
    {{-- <section class="lab-tests py-5">
        <div class="container-fluid px-4">
            <h1 class="text-center mb-5" style="font-weight:bold; color:white;">
                Our Special Tests
            </h1>

            @php
            use Illuminate\Support\Str;
            $charLimit = 60;
            // Only show 8 tests on home
            $homeTests = $tests->take(8);
            @endphp

            <div class="row g-4 justify-content-start">
                @forelse($homeTests as $t)
                @php
                $rawDescription = strip_tags($t->short_description ?: $t->description ?? '');
                $previewText = Str::limit($rawDescription, $charLimit, '...');
                $mrp = (float) ($t->mrp ?? 0);
                $disc = (float) ($t->discounted_price ?? 0);
                $percentOff = ($mrp > 0 && $disc < $mrp) ? round((($mrp - $disc) / $mrp) * 100) : 0; @endphp <div
                    class="col-card">
                    <div class="card package-style-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column p-3">

                            <span class="highlight-pill popular mb-2">🎉 Popular Deal 🎉</span>

                            {{-- Title (2-line clamp) --}}
                            {{-- <h5 class="card-title package-title mb-2">{{ $t->test_name }}</h5> --}}

                            {{-- Description preview (60 chars) --}}
                            {{-- <div class="package-desc-wrapper mb-2">
                                <p class="package-desc clamped" data-full-desc="{{ e($rawDescription) }}"
                                    data-preview-desc="{{ e($previewText) }}">
                                    {!! nl2br(e($previewText)) !!}
                                </p>

                                @if (strlen($rawDescription) > $charLimit)
                                <button type="button" class="read-toggle" aria-expanded="false">Read more</button>
                                @endif
                            </div> --}}

                            {{-- <div style="height:8px;"></div> --}}

                            {{-- Footer: price + Book button --}}
                            {{-- <div class="card-footer-block mt-auto">
                                <div class="price-block mb-2 d-flex align-items-center justify-content-between">
                                    <div>
                                        @if ($mrp > 0 && $disc < $mrp) <div class="disc-price">₹{{ number_format($disc,
                                            0, '.', ',') }}</div>
                                    <div class="small text-muted">
                                        <span class="mrp">₹{{ number_format($mrp, 0, '.', ',') }}</span>
                                        @if ($percentOff > 0)
                                        <span class="percent-off">{{ $percentOff }}% OFF</span>
                                        @endif
                                    </div>
                                    @else
                                    <div class="disc-price">₹{{ number_format($disc ?: $mrp, 0, '.', ',') }}</div>
                                    @endif
                                </div>

                                <div class="ms-3">
                                    <a href="{{ url('/booking') }}?test_id={{ $t->id }}"
                                        class="btn btn-package-book select-for-appointment" data-type="test"
                                        data-id="{{ $t->id }}" data-title="{{ e($t->test_name) }}"
                                        data-price="{{ $t->mrp ?? '' }}">
                                        Book Now
                                    </a> --}}

                                    {{--
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center text-muted py-4">
                    No lab tests published yet.
                </div>
            </div>
        </div>
        @endforelse
</div>

{{-- More Tests button --}}
{{-- <div class="row">
    <div class="col-12 text-center mt-4">
        <a href="{{ url('/services') }}" class="btn btn-outline-primary btn-more-tests">
            More Tests
        </a>
    </div>
</div>
</div>
</section> --}}
{{-- diplicate tets --}}
<!-- Lab Tests Section (home - limited to 8) -->
<!-- Lab Tests Section (home - limited to 8) -->


{{-- resources/views/partials/home-lab-tests.blade.php --}}
{{-- resources/views/partials/home-lab-tests.blade.php --}}
<!-- Lab Tests Section (home - limited to 8) -->
{{-- resources/views/partials/home-lab-tests.blade.php --}}
<!-- Lab Tests Section (home - limited to 6) -->
<section class="lab-tests py-5">
    <div class="container-fluid px-4">
        <div class="text-center mb-5 px-3 px-md-5" style="max-width:800px;margin:0 auto; margin-top:2rem;">
            <h2 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
                Top Book<span style="color:#0d6efd;"> Health Tests</span>
            </h2>
            <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                background:linear-gradient(90deg,#0047ff,#00ccff);">
            </div>
            <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
                Discover exclusive diagnostic tests designed for accuracy and trust.
            </p>
        </div>






        {{-- <div style="text-align:center; margin-bottom:40px;">
            <h1 style="font-weight:700; color:#0a2540; font-size:2.2rem; margin-bottom:10px;  margin-top:2rem;">
                Top Book Health Tests
            </h1>

            <div
                style="width:80px; height:4px; margin:10px auto 16px; border-radius:3px; background:linear-gradient(90deg,#0047ff,#00ccff);">
            </div>

            <p class="text-muted mb-0" style="font-size:1.3rem;color:#6b7280;margin:0;">
                Discover exclusive diagnostic tests designed for accuracy and trust.
            </p>
        </div> --}}

        @php
            use Illuminate\Support\Str;
            $charLimit = 100;
            $homeTests = $tests->take(6);
        @endphp

        <!-- Center when 1 or 2 cards -->
        <div class="row g-4 {{ $homeTests->count() <= 2 ? 'justify-content-center' : 'justify-content-start' }}">
            @forelse($homeTests as $t)
                @php
                    // Description
                    $rawDescription = trim(strip_tags($t->short_description ?: $t->description ?? ''));
                    $hasDesc = $rawDescription !== '';
                    $isLong = $hasDesc && strlen($rawDescription) > $charLimit;
                    $previewCore = $hasDesc ? Str::limit($rawDescription, $charLimit, '') : '';

                    // Pricing
                    $mrp = (float) ($t->mrp ?? 0);
                    $disc = (float) ($t->discounted_price ?? 0);
                    $priceField = (float) ($t->price ?? 0);
                    $displayPrice = $disc ?: ($priceField ?: ($mrp ?: null));
                    $savingAmount =
                        $mrp > 0 && $displayPrice && $mrp > $displayPrice ? $mrp - $displayPrice : 0;
                    $percentOff = $savingAmount > 0 ? (int) round(($savingAmount / $mrp) * 100) : 0;
                @endphp

                <div class="col-card">
                    <div class="card package-style-card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column p-3" data-test-id="{{ $t->id }}"
                            data-test-title="{{ e($t->test_name) }}" data-test-desc="{{ e($rawDescription) }}"
                            data-test-sell="{{ $displayPrice ?: 0 }}" data-test-mrp="{{ $mrp ?: 0 }}"
                            data-test-off="{{ $percentOff }}" data-test-save="{{ $savingAmount }}">

                            <h5 class="card-title package-title mb-2 text-truncate" title="{{ $t->test_name }}">
                                <a href="{{ route('services.show', ['labTest' => $t->slug]) }}"
                                    class="text-decoration-none text-reset">
                                    {{ $t->test_name }}
                                </a>
                            </h5>

                            <!-- 3-line clamp + inline Read more -->
                            <div class="package-desc-wrapper mb-2">
                                @if ($hasDesc)
                                    <p class="package-desc">
                                        {!! nl2br(e($previewCore)) !!}
                                    </p>

                                    @if ($isLong)
                                        <a href="{{ route('services.show', ['labTest' => $t->slug]) }}" class="readmore-inline">
                                            … Read more
                                        </a>
                                    @endif
                                @else
                                    <p class="package-desc">&nbsp;</p>
                                @endif
                            </div>


                            <div
                                class="card-footer-block mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                <!-- Inline price row -->
                                <div class="price-wrap d-flex align-items-center gap-2 flex-wrap">
                                    @if ($displayPrice)
                                        <span class="disc-price">₹{{ number_format($displayPrice, 0, '.', ',') }}</span>
                                        @if ($mrp > $displayPrice && $percentOff > 0)
                                            <span class="text-small"><s>₹{{ number_format($mrp, 0, '.', ',') }}</s></span>
                                            <span class="percent-off">{{ $percentOff }}% OFF</span>
                                        @endif
                                    @else
                                        <span class="disc-price">Contact</span>
                                    @endif
                                </div>

                                <!-- Add-to-cart (pink) switches to Go-to-cart (green) -->
                                <div>
                                    <button type="button" id="cart-btn-{{ $t->id }}" data-item-id="{{ $t->id }}"
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
                <div class="col-12">
                    <div class="alert alert-info px-3 px-md-5 text-center">No lab tests published yet.</div>
                </div>
            @endforelse
        </div>

        {{-- Show button only if total tests are more than 6 --}}
        @if ($tests->count() > 6)
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <a href="{{ url('/tests') }}" class="btn btn-outline-primary btn-more-tests">Show All
                        Tests</a>
                </div>
            </div>
        @endif

    </div>
</section>

{{-- ===== Modal (Read More) ===== --}}
<div class="modal fade" id="testDetailsModal" tabindex="-1" aria-hidden="true" aria-labelledby="testDetailsTitle">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow">
            <!-- Floating cancel icon (matches Services) -->
            <button type="button" class="modal-cancel-icon" data-bs-dismiss="modal" aria-label="Close">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>

            <div class="modal-header">
                <h5 class="modal-title" id="testDetailsTitle">Test details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p id="testDetailsDesc" class="mb-0"></p>
            </div>

            <div class="wc-modal-footer border-top px-3 py-3 d-flex align-items-center">
                <!-- PRICE (LEFT) -->
                <div class="price-box d-flex flex-wrap align-items-center gap-2">
                    <span class="fw-bold" id="mSell">₹0</span>
                    <small class="text-muted text-decoration-line-through" id="mMrp">₹0</small>
                    <span class="badge" id="mOff">-0%</span>
                    <span class="you-save-text" id="mSave">You save ₹0</span>
                </div>

                <!-- BUTTONS (RIGHT) -->
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

<script>
    // Keep a global set to sync modal/card state like on Services
    let currentCartTestIds = new Set();

    // Routes
    const CART_ADD_URL = "{{ route('cart.add') }}";
    const CART_ITEMS_URL = "{{ route('cart.items') }}";
    const CART_URL = "{{ route('cart.index') }}";

    // Add to Cart (robust + same styling flow)
    async function addToCart(event, type, id) {
        event?.preventDefault?.();
        const button = (event && event.target) ? event.target.closest('button') : document.querySelector(
            `#cart-btn-${id}`);
        if (!button) return;

        // If already converted, just go to cart
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

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        try {
            const res = await fetch(CART_ADD_URL, {
                method: 'POST',
                credentials: 'same-origin',
                cache: 'no-store',
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

            let data = {};
            try {
                data = await res.json();
            } catch (_) { }

            if (res.ok && data?.success) {
                // Flip clicked button
                convertButtonToGoToCart(button);

                // Flip modal/card twin if relevant
                const twinCard = document.getElementById(`cart-btn-${id}`);
                if (twinCard && twinCard !== button) convertButtonToGoToCart(twinCard);

                const mBtn = document.getElementById('mAddBtn');
                const modalOpenForId = mBtn && Number(mBtn.dataset.testId || 0) === Number(id) && document
                    .querySelector('#testDetailsModal.show');
                if (modalOpenForId && mBtn !== button) convertButtonToGoToCart(mBtn);

                // Update badge + local set
                updateCartBadgeFromResponse(data);
                currentCartTestIds.add(Number(id));

                // Soft refresh to get server truth
                setTimeout(() => {
                    try {
                        refreshCartButtons();
                    } catch (e) { }
                }, 0);
            } else {
                alert((data && data.message) ? data.message : (res.status === 419 ?
                    'Session expired. Please refresh and try again.' : 'Failed to add to cart.'));
                button.innerHTML = originalHtml;
            }
        } catch (err) {
            console.error('addToCart error', err);
            alert('Something went wrong while adding to cart.');
            button.innerHTML = originalHtml;
        } finally {
            button.disabled = false;
            delete button.dataset.processing;
        }
    }

    // Refresh buttons from server state (sync cards + open modal)
    async function refreshCartButtons() {
        try {
            const r = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            const json = await r.json().catch(() => ({
                items: []
            }));
            updateCartBadgeFromResponse(json);

            currentCartTestIds = new Set((json.items || [])
                .filter(i => String(i.item_type) === 'test')
                .map(i => Number(i.item_id)));

            document.querySelectorAll('button[id^="cart-btn-"]').forEach(btn => {
                const id = Number(btn.dataset.itemId || btn.getAttribute('data-item-id'));
                if (!id) return;
                if (currentCartTestIds.has(id)) convertButtonToGoToCart(btn);
                else revertButtonToAddToCart(btn, id);
            });

            // If modal is open, mirror the state
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
            if (badge) badge.textContent = count;
        }
    }

    // Button state swap: add → go (match Packages slider)
    function convertButtonToGoToCart(button) {
        if (!button) return;
        button.classList.remove('add-to-cart');
        button.classList.add('go-to-cart', 'btn-go-cart');
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
        mBtn.dataset.testId = id;
        if (currentCartTestIds.has(id)) convertButtonToGoToCart(mBtn);
        else revertButtonToAddToCart(mBtn, id);
    }

    // Modal (Read more)
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

        // Title + desc
        document.getElementById('testDetailsTitle').textContent = title;
        document.getElementById('testDetailsDesc').innerHTML = (desc || '').replace(/\n/g, '<br>');

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

        // Ensure modal button mirrors current cart state
        setModalAddBtnState(id);

        const modalEl = document.getElementById('testDetailsModal');
        const modal = (window.bootstrap && bootstrap.Modal) ?
            bootstrap.Modal.getOrCreateInstance(modalEl) :
            null;
        if (modal) modal.show();
        else modalEl.classList.add('show');
    });

    // Height equalizer
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
</script>

<style>
    /* ===== Shared look & grid (match Services) ===== */
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
        box-sizing: border-box;
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

    /* 3-line clamp + black medium weight */
    .package-desc {
        color: #111 !important;
        font-weight: 500;
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

    /* Inline “Read more” */
    .readmore-inline {
        display: inline-block;
        margin-top: 4px;
        font-weight: 600;
        text-decoration: none;
        color: #0d6efd;
    }

    .readmore-inline,
    .readmore-inline:focus,
    .readmore-inline:hover,
    .readmore-inline:active,
    .readmore-inline:visited {
        color: #0d6efd !important;
        text-decoration: none;
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

    /* Scoped MRP */
    .price-wrap .text-muted.small {
        font-size: .82rem !important;
        color: #7b8894 !important;
        opacity: .85;
        display: inline-flex;
        align-items: center;
        margin: 0 2px;
    }

    .price-wrap .text-small {
        color: #000 !important;
        text-decoration: line-through;
        margin-left: 8px;
        font-size: 1rem;
        font-weight: 600;
    }

    /* % OFF pill (shared) */
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

    /* You save (blue) */
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

    /* Cart buttons: EXACT visual match to Services/slider */
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

    .wc-cart-btn.add-to-cart {
        background: #f0c2c2;
        color: #2b4a66;
        border: 1px solid rgba(14, 63, 108, .12);
    }

    .wc-cart-btn.add-to-cart:hover {
        background: #f7faff;
        color: #173b5f;
        transform: translateY(-2px);
        border-color: rgba(14, 63, 108, .25);
    }

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

    /* Modal title/desc (match Services) */
    #testDetailsTitle {
        color: #e91e63;
        font-weight: 800;
    }

    #testDetailsDesc {
        color: #111;
        font-weight: 600;
    }

    /* Modal price styles (match Services) */
    #mSell {
        color: #1a3a66 !important;
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
        font-size: .8rem;
        margin-left: 8px;
    }

    #mSave {
        color: #0a66d6 !important;
        font-weight: 600 !important;
        font-size: .95rem;
        margin-left: 10px;
    }

    /* Modal footer layout */
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
    }

    #testDetailsModal .wc-modal-footer .action-buttons button {
        min-width: 110px;
    }

    /* Floating cancel icon */
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
    }

    .modal-cancel-icon:hover i {
        color: #c2185b;
    }

    /* Grid (1 / 2 / 2 / 3 / 3) */
    .row.g-4 {
        --gutter-x: 1rem;
    }

    @media (max-width: 768px) {
        .row.g-4 {
            --gutter-x: .75rem;
        }

        /* 📱 Mobile sideways horizontal scroll for tests */
        .lab-tests .row.g-4 {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            scroll-snap-type: x mandatory !important;
            padding-bottom: 15px !important;
            margin-left: -12px !important;
            margin-right: -12px !important;
            padding-left: 12px !important;
            padding-right: 12px !important;
            scrollbar-width: thin;
        }

        .lab-tests .row.g-4::-webkit-scrollbar {
            height: 6px;
        }

        .lab-tests .row.g-4::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .lab-tests .row.g-4 > .col-card {
            flex: 0 0 85% !important;
            width: 85% !important;
            max-width: 320px !important;
            min-width: 270px !important;
            scroll-snap-align: start !important;
            margin-bottom: 0 !important;
            box-sizing: border-box;
            padding-left: var(--gutter-x);
            padding-right: var(--gutter-x);
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

    /* Mobile modal fixes (keep buttons inside) */
    @media (max-width: 575.98px) {
        #testDetailsModal .wc-modal-footer {
            flex-wrap: wrap !important;
        }

        #testDetailsModal .wc-modal-footer .price-box {
            flex: 1 1 100% !important;
            order: 1;
            gap: 8px !important;
        }

        #testDetailsModal .wc-modal-footer .action-buttons {
            flex: 1 1 100% !important;
            order: 2;
            margin-left: 0 !important;
            justify-content: flex-end;
            gap: 12px !important;
        }

        #testDetailsModal .wc-modal-footer .action-buttons button {
            min-width: 0 !important;
            flex: 0 1 auto;
        }

        #testDetailsModal .wc-modal-footer .action-buttons .wc-cart-btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        #testDetailsModal .modal-dialog {
            margin: .5rem !important;
        }
    }

    @media (max-width: 420px) {
        #testDetailsModal .wc-modal-footer .action-buttons {
            justify-content: stretch;
        }

        #testDetailsModal .wc-modal-footer .action-buttons .btn-light,
        #testDetailsModal .wc-modal-footer .action-buttons .wc-cart-btn {
            flex: 1 1 100%;
        }
    }
</style>





<!-- list of popular health test -->
{{-- <section class="popular-tests">
    <h3>Popular Health Tests</h3>
    <div class="popular-tests-list">
        <a href="javascript:void(0)">Blood Test</a>
        <a href="javascript:void(0)">Hba1c Test</a>
        <a href="javascript:void(0)">Thyroid Test</a>
        <a href="javascript:void(0)">Liver Function Test</a>
        <a href="javascript:void(0)">Lipid Test</a>
        <a href="javascript:void(0)">Sugar Test</a>
        <a href="javascript:void(0)">Bilirubin Test</a>
        <a href="javascript:void(0)">Kidney Function Test</a>
    </div>
</section> --}}
{{--
<style>
    /* ===== Popular Health Tests Section ===== */
    .popular-tests {
        margin-top: 40px;
        margin-bottom: 40px;
        padding-left: 20px;
        padding-right: 20px;
        text-align: center;
    }

    .popular-tests h3 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #0d2b55;
        margin-bottom: 16px;
    }

    .popular-tests-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        pointer-events: none;
        /* visual-only pills */
    }

    .popular-tests-list a {
        text-decoration: none;
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        background-color: #e9f0f6;
        color: #2c3e50;
        font-size: 0.95rem;
        font-weight: 500;
        pointer-events: none;
        cursor: default;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .popular-tests-list a:hover {
        background-color: #dce8f2;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .popular-tests {
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .popular-tests h3 {
            font-size: 1.8rem;
        }

        .popular-tests-list a {
            font-size: 0.9rem;
            padding: 7px 14px;
        }
    }
</style>


--}}




<!-- WHY CHOOSE US - Diagnostic Centre -->
<div style="background:#f8fafc; padding:90px 20px 60px 20px; width:100%;">
    <h2 style="font-size:2rem; font-weight:700; margin-bottom:15px; text-align:center;">
        Why Choose <span style="color:#0d6efd;">Wellcare Labs</span>
    </h2>
    <p
        style="font-size:1rem; color:#555; max-width:800px; margin:0 auto 40px auto; text-align:center; line-height:1.7;">
        We combine cutting-edge technology with experienced professionals to deliver
        reliable results that empower better healthcare decisions. Here’s why patients
        and doctors trust us every day:
    </p>

    <!-- Features grid -->
    <div
        style="display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; max-width:1100px; margin:0 auto;">



        <!-- Feature 2 -->
        <div
            style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
            <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">⏱️</div>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ 8+ years of trusted excellence in
                diagnostics
            </h3>

            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ ISO-certified for quality and
                reliability

            </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">
                ✅ 100% accurate & timely reports
            </h3>

        </div>

        <!-- Feature 3 -->
        <div
            style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
            <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">✅</div>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Advanced technology & modern
                equipment </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;"> ✅ Expert pathologists & skilled
                technicians </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;"> ✅ Wide range of health check-ups &
                tests
            </h3>

        </div>

        <!-- Feature 4 -->
        <div
            style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
            <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">🤝</div>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Convenient home sample collection
            </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Online report access anytime,
                anywhere</h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Patient-friendly care with a human
                touch</h3>

        </div>

    </div>
</div>

<!-- PATIENT TESTIMONIALS SECTION -->
@if(isset($testimonials) && $testimonials->count() > 0)
    <style>
        .testimonials-section {
            background: #ffffff;
            padding: 90px 20px 60px 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .testimonials-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .testimonials-header .subtitle {
            color: #d97706;
            /* orange/gold */
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
            display: block;
        }

        .testimonials-header h2 {
            font-size: 2.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .testimonials-header p {
            color: #64748b;
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1100px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .testimonials-section {
                padding: 50px 15px 40px 15px !important;
            }

            /* 📱 Mobile sideways horizontal scroll for reviews */
            .testimonials-grid {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
                scroll-snap-type: x mandatory !important;
                gap: 16px !important;
                padding-bottom: 20px !important;
                margin-left: -5px !important;
                margin-right: -5px !important;
                padding-left: 5px !important;
                padding-right: 5px !important;
                scrollbar-width: thin;
            }

            .testimonials-grid::-webkit-scrollbar {
                height: 6px;
            }

            .testimonials-grid::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 4px;
            }

            .testimonial-item-card {
                flex: 0 0 88% !important;
                width: 88% !important;
                min-width: 280px !important;
                max-width: 340px !important;
                scroll-snap-align: start !important;
                padding: 22px !important;
            }
        }

        .testimonial-item-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
            padding: 30px;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-sizing: border-box;
        }

        .testimonial-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12);
        }

        .testimonial-user-row {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eff6ff;
        }

        .testimonial-user-info h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .testimonial-user-info span {
            font-size: 0.8rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .testimonial-quote-icon {
            position: absolute;
            top: 30px;
            right: 30px;
            font-size: 2.2rem;
            color: #f1f5f9;
        }

        .testimonial-stars {
            margin-bottom: 15px;
            display: flex;
            gap: 4px;
        }

        .testimonial-stars i {
            color: #fbbf24;
            /* Gold stars */
        }

        .testimonial-text {
            font-size: 0.98rem;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 25px;
            flex-grow: 1;
            font-style: italic;
        }

        .testimonial-video-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio */
            border-radius: 12px;
            overflow: hidden;
            margin-top: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .testimonial-video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>

    <div class="testimonials-section">
        <div class="testimonials-header">
            <span class="subtitle">Guest Reviews</span>
            <h2 style="font-size:2rem; font-weight:700; margin-bottom:15px; text-align:center;">
                What Our Patients <span style="color:#0d6efd;">Say About Us</span>
            </h2>
            {{-- <h2>What Our Patients Say About Us</h2> --}}
            <p>Read honest reviews from families who trust Wellcare Labs with their health diagnostics.</p>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonials as $t)
                <div class="testimonial-item-card">
                    <i class="fa-solid fa-quote-right testimonial-quote-icon"></i>
                    <div class="testimonial-user-row">
                        <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}" class="testimonial-avatar">
                        <div class="testimonial-user-info">
                            <h4>{{ $t->name }}</h4>
                            <span>{{ $t->designation ?: 'PATIENT' }}</span>
                        </div>
                    </div>
                    <div class="testimonial-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star" style="color: {{ $i <= $t->rating ? '#fbbf24' : '#e2e8f0' }}"></i>
                        @endfor
                    </div>
                    <p class="testimonial-text">
                        "{{ $t->review }}"
                    </p>
                    @if($t->embed_video_url)
                        <div class="testimonial-video-wrapper">
                            <iframe src="{{ $t->embed_video_url }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif














<!-- About us -->
{{-- <div class="page-section pb-0 section-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 py-3 wow fadeInUp">
                <h1 class="mb-3" style="font-weight:700; color:#0a2540;font-size: 2.2rem;">About Us</h1>
                <h3 class="mb-4" style="color:#007bff; font-size: 1.5rem;">“Where <strong>accuracy</strong> meets
                    <strong>care</strong> for better health.”</h3>
                <p class="mb-4" style="color:#000;">
                    Welcome to Wellcare Labs – your trusted partner in accurate and reliable pathology testing. With ISO
                    certification, advanced technology, and expert professionals, we ensure 100% accurate results with
                    on-time reporting. At Wellcare Labs, we combine precision with care, because your health deserves
                    nothing less.
                </p>
                <a href="{{ url('/about') }}" class="btn btn-primary">Read More</a>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="400ms">
                <div class="img-place custom-img-1">
                    <img src="{{ asset('Front_end/assets/img/bg-doctor.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- .page-section -->

<!-- Our Mission -->

{{-- <div class="page-section pb-0 section-bg">
    <div class="container">
        <div class="row align-items-stretch">
            <!-- IMAGE on left -->
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="400ms">
                <div class="img-place custom-img-1">
                    <img src="{{ asset('Front_end/assets/img/healthcare1.png') }}" alt="Healthcare" class="mission-img">
                </div>
            </div> --}}

            <!-- TEXT on right -->
            {{-- <div class="col-lg-6 py-3 wow fadeInUp d-flex align-items-center">
                <div>
                    <h1 class="mb-3" style="font-weight:700;color:#0a2540;font-size: 2.2rem;">🌿 Wellcare Mission</h1>

                    <h3 class="mb-4" style="color:#007bff;font-size: 1.5rem; ">“Precision in every test, care in every
                        step.”</h3>

                    <p class="mb-4" style="color:#000;">
                        To make healthcare simple, trustworthy, and accessible by offering precise and timely diagnostic
                        services
                        that help you and your loved ones stay healthy.
                    </p>
                    <a href="{{ url('/about') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- .page-section -->

<!-- Our Vision -->
{{-- <div class="page-section pb-0 section-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 py-3 wow fadeInUp">
                <h1 class="mb-3" style="font-weight:700;color:#0a2540;font-size: 2.2rem;">Wellcare Vision</h1>
                <h3 class="mb-4" style="color:#007bff; font-size: 1.5rem;">“Wellcare – Inspiring trust, shaping
                    healthier lives.”</h3>

                <p class="mb-4" style="color:#000;">
                    To be the most trusted name in diagnostics where accuracy meets empathy, ensuring every patient
                    feels cared for, confident, and supported on their health journey.
                </p>
                <a href="{{ url('/about') }}" class="btn btn-primary">Read More</a>

            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-delay="400ms">
                <div class="img-place custom-img-1">
                    <img src="{{ asset('Front_end/assets/img/bg-doctor.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>
</div> --}}


{{--
<!-- WHY CHOOSE US - Diagnostic Centre -->
<div style="background:#f8fafc; padding:90px 20px 60px 20px; width:100%;">
    <h2 style="font-size:2rem; font-weight:700; margin-bottom:15px; text-align:center;">
        Why Choose <span style="color:#0d6efd;">Wellcare Labs</span>
    </h2>
    <p
        style="font-size:1rem; color:#555; max-width:800px; margin:0 auto 40px auto; text-align:center; line-height:1.7;">
        We combine cutting-edge technology with experienced professionals to deliver
        reliable results that empower better healthcare decisions. Here’s why patients
        and doctors trust us every day:
    </p>

    <!-- Features grid -->
    <div
        style="display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; max-width:1100px; margin:0 auto;">



        <!-- Feature 2 -->
        <div
            style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
            <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">⏱️</div>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ 8+ years of trusted excellence in
                diagnostics
            </h3>

            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ ISO-certified for quality and
                reliability

            </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">
                ✅ 100% accurate & timely reports
            </h3>

        </div>

        <!-- Feature 3 -->
        <div
            style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
            <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">✅</div>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Advanced technology & modern equipment
            </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;"> ✅ Expert pathologists & skilled
                technicians </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;"> ✅ Wide range of health check-ups & tests
            </h3>

        </div>

        <!-- Feature 4 -->
        <div
            style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
            <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">🤝</div>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Convenient home sample collection</h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Online report access anytime, anywhere
            </h3>
            <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Patient-friendly care with a human touch
            </h3>

        </div>

    </div>
</div>
--}}






<!-- Appointment -->



<!-- .page-section -->

<!-- App Banner -->
<!-- Professional Contact Us Popup Modal (inline CSS) -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="contactModalLabel"
    aria-hidden="true" style="--backdrop-color: rgba(20,30,40,0.55);">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:520px;">
        <div class="modal-content"
            style="border-radius:12px; overflow:hidden; border:0; box-shadow:0 10px 30px rgba(16,24,40,0.12);">

            <!-- Header -->
            <div class="modal-header" style="background:#ffffff; border-bottom:1px solid #eef2f6; padding:14px 18px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="width:44px; height:44px; border-radius:8px; background:#f1f7ff; display:flex; align-items:center; justify-content:center; border:1px solid #e6f0ff;">
                        <!-- subtle lab icon (SVG) -->
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M7 7l1-4h8l1 4" stroke="#0d6efd" stroke-width="1.6" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M7 7h10v10a3 3 0 0 1-3 3H10a3 3 0 0 1-3-3V7z" stroke="#97bffb" stroke-width="1.2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div>
                        <h5 id="contactModalLabel" style="margin:0; font-size:1rem; color:#12232f; font-weight:600;">
                            Contact Wellcare Labs
                        </h5>
                        <div style="font-size:12px; color:#6c7b86; margin-top:2px;">Need help booking a test? Leave
                            details — we’ll call.</div>
                    </div>
                </div>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    style="border:none; background:transparent; color:#55626b; font-size:20px; line-height:1; opacity:0.9;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body" style="background:#fff; padding:20px 22px;">

                <form id="contactForm" autocomplete="off" novalidate>
                    <!-- Name -->
                    <div class="form-group" style="margin-bottom:12px;">
                        <label for="name"
                            style="display:block; font-size:13px; color:#22313a; margin-bottom:6px; font-weight:600;">Full
                            Name</label>
                        <input type="text" id="name" name="name" required class="form-control"
                            style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e6edf3; font-size:14px; color:#0f2130; background:#fff;">
                    </div>

                    <!-- Email -->
                    <div class="form-group" style="margin-bottom:12px;">
                        <label for="email"
                            style="display:block; font-size:13px; color:#22313a; margin-bottom:6px; font-weight:600;">Email</label>
                        <input type="email" id="email" name="email" required class="form-control"
                            style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e6edf3; font-size:14px; color:#0f2130; background:#fff;">
                    </div>

                    <!-- Phone -->
                    <div class="form-group" style="margin-bottom:12px;">
                        <label for="phone"
                            style="display:block; font-size:13px; color:#22313a; margin-bottom:6px; font-weight:600;">Phone
                            Number</label>
                        <input type="tel" id="phone" name="phone" required class="form-control"
                            style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e6edf3; font-size:14px; color:#0f2130; background:#fff;">
                    </div>

                    <!-- Message -->
                    <div class="form-group" style="margin-bottom:16px;">
                        <label for="message"
                            style="display:block; font-size:13px; color:#22313a; margin-bottom:6px; font-weight:600;">Message</label>
                        <textarea id="message" name="message" rows="3" required class="form-control"
                            style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #e6edf3; font-size:14px; color:#0f2130; background:#fff; resize:vertical;"></textarea>
                    </div>

                    <!-- Small note -->
                    <div style="font-size:12px; color:#7b8a93; margin-bottom:12px;">We respect your privacy — your
                        number will only be used to contact you about this request.</div>

                    <!-- Submit -->
                    <button type="submit" class="btn"
                        style="width:100%; background:#0d6efd; color:#fff; border:none; padding:11px 14px; border-radius:8px; font-weight:600; font-size:15px;">
                        Submit
                    </button>
                </form>

                <!-- Thank You -->
                <div id="thankYouMessage"
                    style="display:none; text-align:center; margin-top:16px; padding:18px; background:#fbfcfe; border-radius:10px; border:1px solid #eef4ff;">
                    <div
                        style="width:56px; height:56px; margin:0 auto 10px; border-radius:50%; background:#e6faf0; display:flex; align-items:center; justify-content:center;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5" stroke="#19a974" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 style="margin:0; color:#12232f; font-weight:700;">Thanks — we’ll call you soon</h5>
                    <p style="margin:8px 0 0 0; color:#56646d; font-size:13px;">A member of our team will contact
                        you to assist with booking.</p>

                    <ul
                        style="list-style:disc; text-align:left; padding-left:20px; max-width:320px; margin:12px auto 0; color:#4f6068; font-size:13px; line-height:1.5;">
                        <li><strong>Fast response:</strong> within 24 hours.</li>
                        <li><strong>Home sample collection</strong> available in selected areas.</li>
                        <li><strong>Accurate reports:</strong> NABL/ISO processes.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
    <script>
        $(function () {
            // // === 1) Carousel + WOW ===
            //  if ($('#doctorSlideshow').length && typeof $.fn.owlCarousel === 'function') {
            //    $('#doctorSlideshow').owlCarousel({
            //      loop: true,
            //      nav: true,
            //      dots: false,
            //      margin: 30,
            //      responsive: { 0:{items:1}, 576:{items:2}, 768:{items:3} }
            //    });
            //  }
            // if (typeof WOW === 'function') { new WOW().init(); }

            // === 2) Contact popup logic (Bootstrap 4) ===
            var $modal = $('#contactModal');
            var $form = $('#contactForm');
            var $thankYou = $('#thankYouMessage');

            if ($modal.length) {
                $modal.modal('show');
            }
            if ($modal.length && $form.length && $thankYou.length) {
                $form.on('submit', function (e) {
                    e.preventDefault();
                    $form.hide();
                    $thankYou.show().css('opacity', '1');
                    setTimeout(function () {
                        $thankYou.css('opacity', '0');
                    }, 2000);
                    setTimeout(function () {
                        $modal.modal('hide');
                        $form[0].reset();
                        $form.show();
                        $thankYou.hide().css('opacity', '1');
                    }, 3000);
                });
            }

            // === 3) Packages row scroll buttons ===
            var $row = $('#packagesRow');
            var $btnLeft = $('.btn-scroll-left');
            var $btnRight = $('.btn-scroll-right');

            if ($row.length) {
                function getScrollAmount() {
                    var w = $row[0].clientWidth || $(window).width();
                    var $pack = $row.find('.pack-col').first();
                    var cardWidth = $pack.length ? $pack.outerWidth(true) : 260;
                    return Math.min(Math.round(w * 0.6), cardWidth * 3);
                }

                function updateBtns() {
                    var max = $row[0].scrollWidth - $row[0].clientWidth - 2;
                    if ($btnLeft.length) {
                        if ($row.scrollLeft() > 8) {
                            $btnLeft.prop('disabled', false).css('opacity', 1);
                        } else {
                            $btnLeft.prop('disabled', true).css('opacity', 0.45);
                        }
                    }
                    if ($btnRight.length) {
                        if ($row.scrollLeft() < max - 8) {
                            $btnRight.prop('disabled', false).css('opacity', 1);
                        } else {
                            $btnRight.prop('disabled', true).css('opacity', 0.45);
                        }
                    }
                    if (max <= 4) {
                        $btnLeft.add($btnRight).hide();
                    } else {
                        $btnLeft.add($btnRight).show();
                    }
                }

                if ($btnLeft.length) {
                    $btnLeft.on('click', function (e) {
                        e.preventDefault();
                        var amt = getScrollAmount();
                        $row.stop().animate({
                            scrollLeft: $row.scrollLeft() - amt
                        }, 450);
                    });
                }
                if ($btnRight.length) {
                    $btnRight.on('click', function (e) {
                        e.preventDefault();
                        var amt = getScrollAmount();
                        $row.stop().animate({
                            scrollLeft: $row.scrollLeft() + amt
                        }, 450);
                    });
                }

                var updateTimer;
                $row.on('scroll', function () {
                    clearTimeout(updateTimer);
                    updateTimer = setTimeout(updateBtns, 60);
                });
                $(window).on('resize', function () {
                    clearTimeout(updateTimer);
                    updateTimer = setTimeout(updateBtns, 150);
                });
                setTimeout(updateBtns, 200);
            }

            // ---------- Helper: measure target height using a hidden clone ----------
            function measureHeightForHtml($el, html) {
                // create a clone, place offscreen, apply same width & font styles
                var $clone = $('<div/>').css({
                    position: 'absolute',
                    visibility: 'hidden',
                    left: -9999,
                    top: -9999,
                    width: $el.width(),
                    'line-height': $el.css('line-height'),
                    'font-size': $el.css('font-size'),
                    'font-family': $el.css('font-family'),
                    'white-space': 'normal',
                    'word-break': 'break-word'
                }).html(html);

                $('body').append($clone);
                var h = $clone.outerHeight();
                $clone.remove();
                return h;
            }

            // === 4) Lab Tests: Read more / Show less with smooth height + fade animation ===
            $(document).on('click', '.read-toggle', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var $wrapper = $btn.closest('.package-desc-wrapper');
                var $p = $wrapper.find('.package-desc');

                if (!$p.length) return;

                var isExpanded = $p.hasClass('expanded');

                // current content HTML and next content HTML
                var currentHtml = $p.html();
                var nextHtml = isExpanded ? ($p.data('preview-desc') || '') : ($p.data('full-desc') || '');

                // measure heights
                var currentH = $p.outerHeight();
                var targetH = measureHeightForHtml($p, nextHtml.replace(/\n/g, '<br>'));

                // lock current height and animate
                $p.css({
                    height: currentH,
                    overflow: 'hidden',
                    opacity: 1
                });

                // animate to target height and fade
                $p.stop(true).animate({
                    height: targetH,
                    opacity: 0.02
                }, 260, function () {
                    // swap content
                    $p.html(nextHtml.replace(/\n/g, '<br>'));

                    // update classes
                    if (isExpanded) {
                        $p.removeClass('expanded').addClass('clamped');
                        $btn.attr('aria-expanded', 'false').text('Read more');
                    } else {
                        $p.removeClass('clamped').addClass('expanded');
                        $btn.attr('aria-expanded', 'true').text('Show less');
                    }

                    // measure final height (content may reflow slightly)
                    var finalH = $p.outerHeight();

                    // animate back to final height and full opacity
                    $p.css({
                        height: targetH,
                        opacity: 0.02
                    });
                    $p.stop(true).animate({
                        height: finalH,
                        opacity: 1
                    }, 240, function () {
                        // remove fixed height to allow natural layout after animation
                        $p.css({
                            height: '',
                            overflow: ''
                        });

                        // on mobile, scroll card into view after animation
                        if ($(window).width() <= 576) {
                            var $card = $btn.closest('.package-style-card');
                            if ($card.length) setTimeout(function () {
                                $card[0].scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }, 80);
                        }
                    });
                });
            });

            // === 5) Add tooltip for truncated titles ===
            function addTitleTooltips() {
                $('.package-title').each(function () {
                    var el = this;
                    var $title = $(this);
                    // small timeout to ensure rendering completed if triggered early
                    setTimeout(function () {
                        if (el.scrollHeight > el.clientHeight + 1) {
                            $title.attr('title', $title.text().trim());
                        } else {
                            $title.removeAttr('title');
                        }
                    }, 10);
                });
            }
            addTitleTooltips();
            $(window).on('resize', function () {
                // recalc tooltips after resize
                clearTimeout(window._labTestsTooltipTimer);
                window._labTestsTooltipTimer = setTimeout(addTitleTooltips, 120);
            });
        });
    </script>
@endpush