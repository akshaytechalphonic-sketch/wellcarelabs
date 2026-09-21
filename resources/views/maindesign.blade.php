{{-- resources/views/maindesign.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="NzUj58eJJtdK45u_vW6aYsjL9ugRRdRZzHVHdTtVDDE" />

    <title>@yield('title', 'One Health - Wellcare Labs')</title>
    <meta name="description" content="@yield('meta_description', 'Wellcare Labs is an ISO-certified diagnostic center in Pune offering accurate pathology tests, health checkup packages, home sample collection & timely reports.')">
    @yield('seo')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-54SNPWMKZR"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-54SNPWMKZR');
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('Front_end/assets/css/maicons.css') }}">
    <link rel="stylesheet" href="{{ asset('Front_end/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('Front_end/assets/vendor/owl-carousel/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('Front_end/assets/vendor/animate/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('Front_end/assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('Front_end/assets/css/style.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Front_end/assets/img/favicon.ico') }}">

    <!-- Swiper -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@9/swiper-bundle.min.css" />

    <!-- Font Awesome (for cart icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-48RVT0CDZ6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-48RVT0CDZ6');
    </script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

})(window,document,'script','dataLayer','GTM-PNMNMSMJ');</script>
<!-- End Google Tag Manager -->
 
    @stack('head')
</head>

<body>

    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PNMNMSMJ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <header>
        <!-- TOPBAR -->
        <div class="topbar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 text-sm">
                        <div class="site-info">
                            <a href="tel:+91 915 898 0898">
                                <span class="mai-call text-primary"></span> +91 915 898 0898
                            </a>
                            <span class="divider">|</span>
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@wellcarelabs.in" target="_blank"
                                rel="noopener noreferrer">
                                <span class="mai-mail text-primary"></span> info@wellcarelabs.in
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-4 text-right text-sm">
                        <div class="social-mini-button">
                            <a href="https://www.facebook.com/profile.php?id=61580091951966" target="_blank"
                                rel="noopener noreferrer">
                                <span class="mai-logo-facebook-f"></span>
                            </a>

                            <a href="https://www.instagram.com/wellcarelabs?igsh=MTB5a2hxY2xrNG8wZw==" target="_blank"
                                rel="noopener noreferrer">
                                <span class="mai-logo-instagram"></span>
                            </a>

                            <a href="https://x.com/WellcareLabs" target="_blank" rel="noopener noreferrer">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>

                            <a href="https://www.linkedin.com/company/wellcarelabs-in/" target="_blank"
                                rel="noopener noreferrer">
                                <span class="mai-logo-linkedin"></span>
                            </a>


                            <a href="https://youtube.com/@wellcarelabsindia?si=2xAmOth2aXYyxp_t" target="_blank"
                                rel="noopener noreferrer">
                                <span class="mai-logo-youtube"></span>
                            </a>





                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm custom-navbar" role="navigation">
            <div class="container d-flex align-items-center">

                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('Front_end/assets/img/new_logo_banner.png') }}"
                        alt="Wellcare Labs Diagnostic Center">
                </a>

                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupport"
                    aria-controls="navbarSupport" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Collapsible content -->
                <div class="collapse navbar-collapse custom-collapse" id="navbarSupport">

                    <!-- SEARCH BAR (2nd) -->
                    <form id="globalSearchForm" method="GET" action="{{ route('global.search') }}"
                        class="search-bar my-2 my-lg-0" role="search">
                        <button type="submit" class="search-icon-btn" aria-label="Search">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>

                        <input id="globalSearchInput" name="q" type="search" value="{{ request('q') }}"
                            class="search-input-field"
                            placeholder="Search tests, packages, e.g. CBC, Thyroid, Full Body..." autocomplete="off"
                            aria-label="Search site">

                        <button type="button" id="clearSearchBtn" class="clear-btn" aria-label="Clear search">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </form>

                    <!-- MAIN MENU -->
                    <ul class="navbar-nav main-menu align-items-lg-center">
                        <li class="nav-item {{ request()->is('/') ? 'Published' : '' }}">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>

                        <li class="nav-item {{ request()->is('about_us') ? 'Published' : '' }}">
                            <a class="nav-link" href="{{ url('/about_us') }}">About Us</a>
                        </li>

                        {{-- <li class="nav-item {{ request()->is('awards_certificates') ? 'Published' : '' }}">
              <a class="nav-link" href="{{ url('/awards_certificates') }}">Awards</a>
            </li> --}}

                        <li class="nav-item dropdown {{ request()->is('packages*') ? 'Published' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown">
                                Packages
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ url('/packages') }}">Smart Health Packages </a>
                                <a class="dropdown-item" href="{{ url('/packages/special') }}">Special Packages</a>
                                <a class="dropdown-item" href="{{ route('customize.index') }}">Customise Packages</a>
                            </div>
                        </li>

                        <li
                            class="nav-item dropdown {{ request()->is('tests*') || request()->is('p/*') ? 'Published' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown">
                                Services
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ url('/tests') }}">All Tests</a>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="{{ route('pages.index') }}">Service Pages</a>


                            </div>
                        </li>

                        {{-- <li class="nav-item {{ request()->is('partner_with_us') ? 'Published' : '' }}">
              <a class="nav-link" href="{{ url('/partner_with_us') }}">Partner with Us</a>
            </li> --}}

                        <li class="nav-item {{ request()->is('contact_us') ? 'Published' : '' }}">
                            <a class="nav-link" href="{{ url('/contact_us') }}">Contact Us</a>
                        </li>
                    </ul>

                    <!-- RIGHT: Cart + Booking -->
                    <div class="navbar-right d-flex align-items-center mt-2 mt-lg-0">

                        <!-- CART ICON -->
                        <div class="nav-cart-wrapper position-relative mx-lg-2 mx-1">
                            <a href="{{ url('/cart') }}" class="nav-link position-relative p-0">
                                <i class="fa fa-shopping-cart fa-lg"></i>
                                <span id="cart-count-badge" class="badge badge-danger"
                                    style="position:absolute; top:-4px; right:-10px; font-size:11px; border-radius:50%; padding:3px 6px; min-width:18px; text-align:center;">
                                    0
                                </span>
                            </a>

                            <div id="cart-dropdown" class="cart-dropdown shadow-sm" style="display:none;">
                                <div class="cart-header px-3 py-2 border-bottom">
                                    <strong>Your Cart</strong>
                                </div>
                                <div id="cart-items-list" class="cart-items px-3 py-2">
                                    <div class="empty text-muted small">Your cart is empty</div>
                                </div>
                                <div class="cart-footer border-top px-3 py-2 text-right">
                                    <div class="cart-total mb-2 text-left">
                                        Total: ₹ <span id="cart-total">0.00</span>
                                    </div>
                                    <a href="{{ url('/cart') }}" class="btn btn-sm btn-outline-primary">View
                                        Cart</a>
                                    <a href="{{ url('/checkout') }}" class="btn btn-sm btn-primary ml-1">Checkout</a>
                                </div>
                            </div>
                        </div>

                        <!-- CTA -->
                        <a class="btn btn-appointment ml-lg-2" href="{{ url('/tests') }}">Book a Test</a>
                    </div>

                </div>
            </div>
        </nav>

        <!-- Floating Call Button -->
        <a href="tel:+919158980898" class="call-float" aria-label="Call Wellcare">
            <img src="https://cdn-icons-png.flaticon.com/512/597/597177.png" alt="Call" class="call-icon">
        </a>

        <!-- Floating WhatsApp Button -->
        <a href="https://wa.me/917083968841?text=Hello%20Wellcare!%20I%20would%20like%20to%20book%20a%20lab%20test."
            class="whatsapp-float" target="_blank" aria-label="Chat on WhatsApp">
            <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="whatsapp-icon">
            <span class="whatsapp-text">Chat with us</span>
        </a>

        <style>
            /*Footer helath chekup packages css  */

            .footer-location-list {
                list-style: none;
                padding: 0;
                margin: 0;
                columns: 2;
                /* ⭐ TWO columns side-by-side */
                column-gap: 20px;
            }

            .footer-location-list li {
                margin-bottom: 6px;
            }

            .footer-location-list a {
                color: #e5e7eb;
                text-decoration: none;
                font-size: 0.95rem;
            }

            .footer-location-list a:hover {
                color: #00ccff;
                /* highlight effect */
                text-decoration: none;
            }

            /* 📱 Mobile - switch back to one column */
            @media(max-width: 600px) {
                .footer-location-list {
                    columns: 1;
                }
            }

            /* ===== Common styles ===== */
            /* ===== FLOATING CALL & WHATSAPP BUTTONS ===== */

            /* Common base */
            .call-float,
            .whatsapp-float {
                position: fixed !important;
                right: 20px !important;
                box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
                text-decoration: none;
                transition: all 0.3s ease;
                z-index: 99999 !important;
                /* stay above modals, headers, etc */
                display: flex !important;
                /* override any display:none */
                align-items: center;
            }

            /* --- Call button --- */
            .call-float {
                bottom: 150px;
                width: 60px;
                height: 60px;
                background-color: #0a9ef0;
                border-radius: 50%;
                justify-content: center;
                animation: callBlink 1.8s infinite ease-in-out;
            }

            .call-icon {
                width: 30px;
                height: 30px;
                filter: brightness(0) invert(1);
            }

            .call-float:hover {
                background-color: #007acc;
                transform: scale(1.1);
                animation: none;
            }

            /* Call blink animation */
            @keyframes callBlink {
                0% {
                    opacity: 1;
                    transform: scale(1);
                    box-shadow: 0 0 5px rgba(10, 158, 240, 0.5);
                }

                50% {
                    opacity: 0.7;
                    transform: scale(1.08);
                    box-shadow: 0 0 15px rgba(10, 158, 240, 0.9);
                }

                100% {
                    opacity: 1;
                    transform: scale(1);
                    box-shadow: 0 0 5px rgba(10, 158, 240, 0.5);
                }
            }

            /* --- WhatsApp button --- */
            .whatsapp-float {
                bottom: 60px;
                background-color: #25d366;
                color: #fff;
                font-weight: 600;
                border-radius: 50px;
                gap: 10px;
                padding: 10px 16px;
                animation: containerBlink 1.8s infinite ease-in-out;
                justify-content: flex-start;
            }

            .whatsapp-float:hover {
                background-color: #20ba5a;
                transform: scale(1.05);
                animation: none;
            }

            .whatsapp-icon {
                width: 28px;
                height: 28px;
            }

            .whatsapp-text {
                font-size: 15px;
            }

            /* WhatsApp pulse animation */
            @keyframes containerBlink {
                0% {
                    opacity: 1;
                    transform: scale(1);
                    box-shadow: 0 0 5px rgba(14, 212, 87, 0.9);
                }

                50% {
                    opacity: 0.7;
                    transform: scale(1.05);
                    box-shadow: 0 0 15px rgba(14, 212, 87, 0.9);
                }

                100% {
                    opacity: 1;
                    transform: scale(1);
                    box-shadow: 0 0 5px rgba(14, 212, 87, 0.9);
                }
            }

            /* ===== Tablet (reduce overlap / adjust position) ===== */
            @media (max-width: 1024px) {
                .call-float {
                    bottom: 130px !important;
                    right: 18px !important;
                }

                .whatsapp-float {
                    bottom: 50px !important;
                    right: 18px !important;
                }
            }

            /* ===== Mobile (icons only + tighter spacing) ===== */
            @media (max-width: 600px) {
                .call-float {
                    width: 55px;
                    height: 55px;
                    bottom: 85px !important;
                    right: 15px !important;
                }

                .call-icon {
                    width: 26px;
                    height: 26px;
                }

                .whatsapp-float {
                    width: 55px;
                    height: 55px;
                    bottom: 20px !important;
                    right: 15px !important;
                    justify-content: center;
                    padding: 0;
                    border-radius: 50%;
                }

                .whatsapp-text {
                    display: none;
                    /* hide text on mobile */
                }
            }

            /* ===== 📱 MOBILE FOOTER ADJUSTMENTS ===== */
            @media (max-width: 768px) {
                .page-footer {
                    padding-top: 25px !important;
                    padding-bottom: 95px !important; /* Space for floating WhatsApp & Call buttons */
                }

                .page-footer .row > div[class*="col-"] {
                    padding-top: 14px !important;
                    padding-bottom: 14px !important;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                }

                .page-footer .row > div[class*="col-"]:last-child {
                    border-bottom: none;
                }

                .page-footer p[style*="font-weight:600"] {
                    font-size: 16px !important;
                    color: #00ccff !important;
                    margin-bottom: 10px !important;
                }

                .footer-location-list {
                    columns: 2 !important; /* Keep 2 compact columns on mobile */
                    column-gap: 15px !important;
                }

                .footer-location-list li {
                    margin-bottom: 6px !important;
                }

                .footer-location-list a,
                .footer-menu a {
                    font-size: 14px !important;
                    display: inline-block;
                    padding: 3px 0 !important;
                }

                .footer-sosmed {
                    display: flex !important;
                    flex-wrap: wrap !important;
                    gap: 10px !important;
                    margin-top: 12px !important;
                }

                #copyright {
                    font-size: 12.5px !important;
                    text-align: center !important;
                    opacity: 0.75;
                    margin-top: 10px !important;
                }
            }
        </style>
    </header>




    <!-- Main content -->
    <main>
        @yield('content')
    </main>



    <!-- FOOTER -->
    <footer class="page-footer">
        <div class="container">
            <div class="row px-md-3">
                <div class="col-sm-6 col-lg-3 py-3">
                    {{-- <h5>Our Packages and Tests</h5> --}}
                    <p style="font-size:16px; font-weight:600; margin:0;">Our Packages and Tests</p>
                    <ul class="footer-menu">
                        <li><a href="{{ url('/packages') }}">Smart Health Packages </a></li>
                        <li><a href="{{ url('/packages/special') }}">Special Packages</a></li>
                        <li><a href=" {{ route('customize.index') }}">Customise Packages</a></li>

                        <li><a href="{{ url('/tests') }}">Top Booked Tests</a></li>


                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 py-3">
                   
                    <p style="font-size:16px; font-weight:600; margin:0;">More</p>
                    <ul class="footer-menu">
                        <li><a href="{{ url('/tests') }}">Book a Test</a></li>

                        {{-- <li><a href="{{ url('/about_us') }}">About Us</a></li> --}}
                        <li><a href="{{ url('/awards_certificates') }}">Awards & Certifications</a></li>
                        <li><a href="{{ url('/partner_with_us') }}">Partner with Us (B2B)</a></li>
                        {{-- <li><a href="{{ url('/contact_us') }}">Contact Us</a></li> --}}
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                        <li><a href="{{ route('blogs.index') }}">Blogs</a></li>
                        <li><a href="{{ route('founder') }}">Meet Our Founder</a></li>
                        {{-- <li><a  href="{{ url('/partner_with_us') }}">Partner with Us</a></li> --}}
                        <li><a href="{{ route('privacy.terms') }}">Privacy Policy & Terms and Condition</a></li>



                    </ul>
                </div>
                <div class="col-sm-6 col-lg-3 py-3 service-areas">
                    {{-- <h5>Health Checkup Packages & Tests in Pune & PCMC</h5> --}}
                    <p style="font-size:16px; font-weight:600; margin:0;">Health Checkup Packages & Tests in Pune & PCMC</p>
                    <ul class="footer-location-list">
                        <li><a href="{{ url('/packages') }}">Pimpri</a></li>
                        <li><a href="{{ url('/packages/special') }}">Aundh</a></li>
                        <li><a href=" {{ route('customize.index') }}">Baner</a></li>
                        <li><a href="{{ url('/tests') }}">Balewadi</a></li>
                        <li><a href="{{ url('/booking') }}">Hinjawadi</a></li>
                        <li><a href="{{ url('/packages') }}">Wakad</a></li>
                        <li><a href="{{ url('/packages/special') }}">Punawale</a></li>
                        <li><a href="{{ url('/tests') }}">Ravet</a></li>
                        <li><a href=" {{ route('customize.index') }}">Lohegaon</a></li>
                        <li><a href="{{ url('/booking') }}">Kharadi</a></li>
                        <li><a href="{{ url('/tests') }}">Kalyani Nagar</a></li>
                        <li><a href=" {{ route('customize.index') }}">Lohegaon</a></li>
                        <li><a href="{{ url('/packages/special') }}">Kothrud</a></li>
                        <li><a href="{{ url('/packages') }}">Bavdhan</a></li>
                    </ul>
                </div>

                <div class="col-sm-6 col-lg-3 py-3">
                    {{-- <h5>Contact</h5> --}}
                    <p style="font-size:16px; font-weight:600; margin:0;">Contact</p>
                    <p class="footer-link mt-2"> Address: Shop 113, First floor, A-Wing, Sai vision Mall, Pimple
                        Saudagar, Pimpri-Chinchwad, Pune, Maharashtra 411027.
                    </p>
                    <a href="tel:+91 9158980898">
                        <span class="mai-call text-primary"></span> +91 915 898 0898
                    </a><br>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@wellcarelabs.in" target="_blank">
                        <span class="mai-mail text-primary"></span> info@wellcarelabs.in

                    </a>

                    {{-- <h5 class="mt-3">Social Media</h5> --}}
                    <p style="font-size:16px; font-weight:600; margin:0;">Social Media</p>
                    <div class="footer-sosmed mt-3">
                        <a href="https://www.facebook.com/profile.php?id=6158009195196" target="_blank"
                            rel="noopener noreferrer">
                            <span class="mai-logo-facebook-f"></span>
                        </a>


                        <a href="https://www.instagram.com/wellcarelabs?igsh=MTB5a2hxY2xrNG8wZw==" target="_blank"
                            rel="noopener noreferrer">
                            <span class="mai-logo-instagram"></span>
                        </a>
                        <a href="https://x.com/WellcareLabs" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>

                        <a href="https://www.linkedin.com/company/wellcarelabs-in/" target="_blank"
                            rel="noopener noreferrer">
                            <span class="mai-logo-linkedin"></span>
                        </a>


                        <a href="https://youtube.com/@wellcarelabsindia?si=2xAmOth2aXYyxp_t" target="_blank"
                            rel="noopener noreferrer">
                            <span class="mai-logo-youtube"></span>
                        </a>



                    </div>

                </div>
            </div>

            <hr>
            <p id="copyright">Copyright &copy; {{ date('Y') }}Wellcare™. All Rights Reserved.</p>
        </div>


    </footer>

    <!-- JS -->
    <script src="{{ asset('Front_end/assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('Front_end/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Front_end/assets/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('Front_end/assets/vendor/wow/wow.min.js') }}"></script>
    <script src="{{ asset('Front_end/assets/js/theme.js') }}"></script>
    <script src="{{ asset('Front_end/assets/js/coustom.js') }}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('globalSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            const form = document.getElementById('globalSearchForm');

            if (!input || !clearBtn) return;

            function toggleClearButton() {
                clearBtn.style.display = input.value.trim() ? 'flex' : 'none';
            }

            // initial state
            toggleClearButton();

            // show/hide on input
            input.addEventListener('input', toggleClearButton);

            // clear + redirect home on click
            clearBtn.addEventListener('click', function() {
                input.value = '';
                toggleClearButton();
                // change to homepage route or services if you prefer
                window.location.href = "{{ route('home') }}";
            });

            // optional: prevent submitting empty search
            form.addEventListener('submit', function(e) {
                if (!input.value.trim()) {
                    e.preventDefault();
                    window.location.href = "{{ route('home') }}";
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('globalSearchInput');
            const clearBtn = document.getElementById('clearSearchBtn');
            const form = document.getElementById('globalSearchForm');

            function toggleClearButton() {
                clearBtn.style.display = input.value.trim() ? 'flex' : 'none';
            }

            // Show/hide clear icon when typing
            input.addEventListener('input', toggleClearButton);
            toggleClearButton();

            // Clear and redirect home on click
            clearBtn.addEventListener('click', function() {
                input.value = '';
                clearBtn.style.display = 'none';
                // redirect to homepage
                window.location.href = "{{ route('home') }}";
            });

            // Optional: prevent empty search submit
            form.addEventListener('submit', function(e) {
                if (!input.value.trim()) {
                    e.preventDefault();
                    window.location.href = "{{ route('home') }}";
                }
            });
        });

        (function() {
            const input = document.getElementById('globalSearchInput');
            const list = document.getElementById('globalSearchList');
            const form = document.getElementById('globalSearchForm');
            const ajaxUrl = "{{ route('global.search.ajax') }}";
            let controller = null;
            let selectedIndex = -1;

            function debounce(fn, wait) {
                let t;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn(...args), wait);
                };
            }

            function renderResults(data) {
                if (!list) return;
                list.innerHTML = '';
                const frag = document.createDocumentFragment();

                if ((data.tests && data.tests.length) || (data.packages && data.packages.length)) {
                    if (data.tests && data.tests.length) {
                        const header = document.createElement('h6');
                        header.className = 'dropdown-header';
                        header.textContent = 'Tests';
                        frag.appendChild(header);
                        data.tests.forEach(t => {
                            const a = document.createElement('a');
                            a.className = 'dropdown-item';
                            a.href = "{{ url('/tests') }}?q=" + encodeURIComponent(t.test_name);
                            a.innerHTML = `<strong>${escapeHtml(t.test_name)}</strong>`;
                            frag.appendChild(a);
                        });
                    }
                    if (data.packages && data.packages.length) {
                        const header = document.createElement('h6');
                        header.className = 'dropdown-header';
                        header.textContent = 'Packages';
                        frag.appendChild(header);
                        data.packages.forEach(p => {
                            const a = document.createElement('a');
                            a.className = 'dropdown-item';
                            a.href = "{{ url('/packages') }}?q=" + encodeURIComponent(p.title);
                            a.textContent = p.title;
                            frag.appendChild(a);
                        });
                    }
                    // final "See all results" link
                    const sep = document.createElement('div');
                    sep.className = 'dropdown-divider';
                    frag.appendChild(sep);
                    const all = document.createElement('a');
                    all.className = 'dropdown-item text-center';
                    all.href = "{{ route('global.search') }}?q=" + encodeURIComponent(input.value.trim());
                    all.textContent = 'See all results';
                    frag.appendChild(all);

                    list.appendChild(frag);
                    list.style.display = '';
                    input.setAttribute('aria-expanded', 'true');
                } else {
                    list.style.display = 'none';
                    input.setAttribute('aria-expanded', 'false');
                }
            }

            function escapeHtml(s) {
                return (s + '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [c]));
            }

            async function doSearch(q) {
                if (controller) controller.abort();
                controller = new AbortController();
                if (!q) {
                    list.style.display = 'none';
                    input.setAttribute('aria-expanded', 'false');
                    return;
                }

                try {
                    const res = await fetch(ajaxUrl + '?q=' + encodeURIComponent(q), {
                        signal: controller.signal,
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (!res.ok) {
                        list.style.display = 'none';
                        return;
                    }
                    const data = await res.json();
                    renderResults(data);
                } catch (err) {
                    if (err.name !== 'AbortError') console.error('Search error', err);
                }
            }

            const deb = debounce((ev) => doSearch(ev.target.value.trim()), 300);

            if (input) {
                input.addEventListener('input', deb);
                // keyboard nav in dropdown
                input.addEventListener('keydown', function(e) {
                    const items = list.querySelectorAll('.dropdown-item');
                    if (!items.length) return;
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                        items[selectedIndex].classList.add('active');
                        items[(selectedIndex - 1) >= 0 ? selectedIndex - 1 : 0].classList?.remove('active');
                        items[selectedIndex].focus();
                    }
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        selectedIndex = Math.max(selectedIndex - 1, 0);
                        items[selectedIndex].classList.add('active');
                    }
                    if (e.key === 'Enter') {
                        if (selectedIndex >= 0 && items[selectedIndex]) {
                            e.preventDefault();
                            window.location = items[selectedIndex].href;
                        }
                    }
                    if (e.key === 'Escape') {
                        list.style.display = 'none';
                        input.blur();
                    }
                });

                // hide on outside click
                document.addEventListener('click', function(ev) {
                    if (!document.querySelector('.global-search-wrapper')) return;
                    if (!document.querySelector('.global-search-wrapper').contains(ev.target)) {
                        list.style.display = 'none';
                        input.setAttribute('aria-expanded', 'false');
                    }
                });

                // keyboard shortcut '/'
                document.addEventListener('keydown', function(e) {
                    if (e.key === '/' && document.activeElement.tagName.toLowerCase() !== 'input' && document
                        .activeElement.tagName.toLowerCase() !== 'textarea') {
                        e.preventDefault();
                        input.focus();
                        input.select();
                    }
                });
            }

            // if form submitted, let server handle full results
            if (form) {
                form.addEventListener('submit', function() {
                    list.style.display = 'none';
                });
            }
        })();


        document.addEventListener('DOMContentLoaded', function() {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const badge = document.getElementById('cart-count-badge');

            // refresh badge by calling cart.count route
            window.refreshCartBadge = async function() {
                try {
                    const res = await fetch("{{ route('cart.count') }}", {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) {
                        console.warn('cart.count request failed', res.status);
                        return 0;
                    }
                    const json = await res.json();
                    const n = Number(json.count || 0);
                    if (badge) {
                        badge.innerText = n > 0 ? n : 0;
                        // show badge (you can hide when 0 if you prefer)
                        badge.style.display = 'inline-block';
                    }
                    return n;
                } catch (err) {
                    console.error('refreshCartBadge error', err);
                    return 0;
                }
            };

            // preserve your existing addToCart if present; wrap to refresh badge after success
            const originalAddToCart = window.addToCart;
            window.addToCart = async function(type, id, qty = 1) {
                if (typeof originalAddToCart === 'function') {
                    // call existing implementation (which already updates badge for add)
                    try {
                        await originalAddToCart(type, id, qty);
                    } catch (err) {
                        console.warn('original addToCart failed', err);
                    }
                    // give server a moment, then refresh badge
                    setTimeout(() => {
                        window.refreshCartBadge();
                    }, 250);
                    return;
                }

                // fallback: perform add here
                try {
                    const res = await fetch("{{ route('cart.add') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            item_type: type,
                            item_id: id,
                            quantity: qty
                        })
                    });
                    const json = await res.json();
                    if (json.success) {
                        await window.refreshCartBadge();
                        const t = document.createElement('div');
                        t.textContent = 'Added to cart';
                        t.style.position = 'fixed';
                        t.style.right = '20px';
                        t.style.bottom = '20px';
                        t.style.background = '#28a745';
                        t.style.color = '#fff';
                        t.style.padding = '8px 12px';
                        t.style.borderRadius = '6px';
                        t.style.zIndex = 9999;
                        document.body.appendChild(t);
                        setTimeout(() => t.remove(), 1000);
                    } else {
                        alert(json.message || 'Failed to add item');
                    }
                } catch (err) {
                    console.error('addToCart fallback error', err);
                    alert('Error adding item to cart');
                }
            };
        });


        $(document).ajaxError(function(event, xhr) {
            if (xhr.status === 419) {
                location.reload();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity=""
        crossorigin="anonymous"></script>

    <script>
        /**
         * 🔁 Keep CSRF token fresh for long-open pages
         * Prevents "CSRF token mismatch" after inactivity
         */
        setInterval(async () => {
            try {
                const res = await fetch('/refresh-csrf', {
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) return;

                const data = await res.json();
                if (data.token) {
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta) {
                        meta.setAttribute('content', data.token);
                    }
                }
            } catch (e) {
                // silently ignore (network / tab inactive)
            }
        }, 5 * 60 * 1000); // every 5 minutes
    </script>


    @yield('scripts')
</body>

</html>
