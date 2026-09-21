{{-- resources/views/about.blade.php --}}

@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Trusted Diagnostic & Health Checkup Services in India')

@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Learn about Wellcare Labs, a leading diagnostic and healthcare provider offering advanced lab tests, personalized health packages, and accurate reports backed by expert professionals and modern technology.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif

@endsection
@section('content')

    <div style="text-align:center;margin-top:20px;margin-bottom:30px;">
        <h1 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
            Wellcare<span style="color:#0d6efd;"> About Us</span>
        </h1>
        <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
            background:linear-gradient(90deg,#0047ff,#00ccff);">
        </div>

        <h2 class="mb-3" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px">
            Welcome to Your Health Center
        </h2>
    </div>

    <!-- About Section -->
    <div class="page-section">
        <div class="container">
            <div class="row align-items-center">

                <!-- About Us Content -->
                <div class="col-lg-12 wow fadeInUp">
                    <h3 class="mb-4" style="color:#007bff;">
                        “Where <strong>accuracy</strong> meets <strong>care</strong> for better health.”
                    </h3>

                    <div class="text-lg">
                        <p style="color:#000;">
                            Welcome to <strong>Wellcare Labs</strong> – your trusted partner in
                            accurate and reliable pathology testing. With <strong>ISO certification</strong>,
                            advanced technology, and expert professionals, we ensure accurate results with
                            <strong>on-time reporting</strong>. At Wellcare Labs, we combine precision with care,
                            because your health deserves nothing less.
                        </p>
                        <p style="color:#000;">
                            At Wellcare Labs, we believe that <strong>healthcare begins with accurate diagnostics</strong>.
                            As a trusted name in pathology, we are committed to delivering accuracy, reliability,
                            and care in every report. Our ISO-certified processes ensure the highest standards of
                            quality, safety, and efficiency, giving our patients and healthcare partners complete
                            confidence in the results.
                        </p>
                        <p style="color:#000;">
                            With advanced technology, skilled professionals, and a strong focus on precision,
                            we provide a wide range of diagnostic services to support the <strong>early detection,
                                prevention, and treatment of diseases</strong>. We take pride in our timely reporting,
                            because we understand that results delivered on time can make all the difference in
                            effective medical care.
                        </p>
                        <p style="color:#000;">
                            At Wellcare Labs, <strong>accuracy meets empathy</strong>—we go beyond just numbers
                            and reports, ensuring that every test contributes to <strong>better health
                                and well-being</strong>.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Mission Section -->
    <div class="page-section pb-0 section-bg">
        <div class="container">
            <div class="row align-items-stretch">

                <!-- Text -->
                <div class="col-lg-12 py-3 wow fadeInUp d-flex align-items-center">
                    <div>
                        <h2 class="mb-3" style="font-weight:700;">🌿 Wellcare Mission</h2>

                        <h3 class="mb-4" style="color:#007bff;">
                            “Precision in every test, care in every step.”
                        </h3>

                        <p class="mb-4" style="color:#000;">
                            To make healthcare simple, trustworthy, and accessible by offering precise and timely diagnostic
                            services
                            that help you and your loved ones stay healthy.
                            At Wellcare Labs, our mission is to make healthcare <strong>accurate, accessible, and
                                compassionate</strong> for everyone.
                            We are dedicated to delivering precise diagnostic services that empower doctors and patients
                            with the confidence
                            to make informed healthcare decisions. By combining <strong>cutting-edge technology, skilled
                                professionals, and ISO-certified processes</strong>,
                            we ensure every test meets the highest standards of quality and reliability. Beyond just
                            reports, we believe in building
                            <strong>trust through care, empathy, and integrity</strong>, because at Wellcare, every result
                            is more than a number—it’s a step towards
                            healthier lives and stronger communities.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Vision Section -->
    <div class="page-section pb-0 section-bg">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-12 wow fadeInUp">
                    <h2 class="mb-3" style="font-weight:700;">Wellcare Vision</h2>

                    <h3 class="mb-4" style="color:#007bff;">
                        “Wellcare – Inspiring trust, shaping healthier lives.”
                    </h3>

                    <p class="mb-4" style="color:#000;">
                        To be the most trusted name in diagnostics where accuracy meets empathy, ensuring every patient
                        feels cared for, confident, and supported on their health journey.
                        At Wellcare Labs, our vision is to be the most trusted name in diagnostics, where
                        <strong>accuracy meets empathy</strong>. We strive to transform healthcare by delivering
                        reliable and timely results that empower patients and doctors to make confident decisions.
                        Guided by innovation, compassion, and a commitment to excellence, Wellcare envisions a
                        future where every individual has access to world-class diagnostics, ensuring healthier
                        communities and a brighter tomorrow.
                    </p>
                </div>

            </div>
        </div>
    </div>


    {{-- ================= ABOUT PAGE BLOGS ================= --}}
    <div style="background:#f8fafc; padding:20px 20px 30px; width:100%; margin-top:20px;">

        {{-- ===== Styles ===== --}}
        <style>
            :root {
                --blog-card-width: 300px;
                --blog-card-height: 420px;
            }

            /* Card hover */
            .blog-card {
                width: var(--blog-card-width);
                height: var(--blog-card-height);

                background: #fff;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);

                display: flex;
                flex-direction: column;

                transition: transform .35s ease, box-shadow .35s ease;
                opacity: 0;
                transform: translateY(30px);
            }

            .blog-image-wrapper {
                width: 100%;
                height: 260px;
                background: linear-gradient(180deg, #eef2f7, #f8fafc);
                overflow: hidden;
            }


            .blog-image-wrapper img {
                width: 100%;
                height: 168px;
                /* 🔥 keeps full image */
                display: block;
                object-fit: unset;
                /* not needed anymore */
            }


            .blog-image-wrapper {
                background: linear-gradient(180deg, #eef2f7, #f8fafc);
            }

            @media (max-width: 320px) {
                :root {
                    --blog-card-width: 260px;
                }
            }



            .blog-card.show {
                opacity: 1;
                transform: translateY(0);
            }

            .blog-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 16px 36px rgba(0, 0, 0, 0.12);
            }

            /* Image */
            .blog-image-wrapper {
                width: 100%;
                height: 180px;
                overflow: hidden;
                background: #eaeaea;
            }

            /*
                        .blog-image-wrapper img {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                            transition: transform .45s ease;
                        } */

            .blog-card:hover img {
                transform: scale(1.08);
            }

            /* Content */
            .blog-title {
                font-size: 1.15rem;
                font-weight: 600;
                margin-bottom: 10px;
                color: #0d6efd;
                transition: color .25s ease;
            }

            .blog-card:hover .blog-title {
                color: #084298;
            }

            .blog-excerpt {
                font-size: .95rem;
                color: #000000;
                line-height: 1.6;
            }

            .blog-link {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                margin-top: 14px;
                font-weight: 600;
                color: #0d6efd;
                text-decoration: none;
                transition: gap .25s ease, color .25s ease;
            }

            .blog-link:hover {
                gap: 10px;
                color: #084298;
            }

            /* View all button */
            .view-all-wrap {
                text-align: center;
                margin-top: 50px;
                opacity: 0;
                transform: translateY(20px);
                transition: all .6s ease;
            }

            .view-all-wrap.show {
                opacity: 1;
                transform: translateY(0);
            }

            .view-all-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 26px;
                border-radius: 999px;
                background: #0d6efd;
                color: #fff;
                font-weight: 600;
                text-decoration: none;
                transition: transform .25s ease, box-shadow .25s ease;
                box-shadow: 0 8px 18px rgba(13, 110, 253, .25);
            }

            .view-all-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 12px 26px rgba(13, 110, 253, .35);
            }

            /* Make card content stretch */
            .blog-card {
                display: flex;
                flex-direction: column;
            }

            /* Make body take remaining height */
            .blog-body {
                padding: 22px;
                display: flex;
                flex-direction: column;
                flex: 1;
                /* 🔥 important */
            }

            /* Push "Read more" to bottom */
            .blog-body .blog-link {
                margin-top: auto;
                /* 🔥 this fixes it */
            }

            /* ===== Blogs Grid ===== */
            .about-blogs-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 300px));
                gap: 25px;
                justify-content: center;
                justify-items: center;
            }

            @media (max-width: 768px) {
                .about-blogs-grid {
                    display: flex !important;
                    flex-wrap: nowrap !important;
                    overflow-x: auto !important;
                    -webkit-overflow-scrolling: touch !important;
                    scroll-snap-type: x mandatory !important;
                    gap: 16px !important;
                    padding: 10px 5px 20px 5px !important;
                    justify-content: flex-start !important;
                    scrollbar-width: thin;
                }

                .about-blogs-grid::-webkit-scrollbar {
                    height: 6px;
                }

                .about-blogs-grid::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 4px;
                }

                .about-blogs-grid .blog-card {
                    flex: 0 0 85% !important;
                    width: 85% !important;
                    min-width: 270px !important;
                    max-width: 320px !important;
                    scroll-snap-align: start !important;
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }
            }
        </style>

        {{-- ===== Heading ===== --}}
        <h2 style="font-size:2rem; font-weight:700; margin-bottom:15px; text-align:center;">
            Wellcare Health <span style="color:#0d6efd;">Blogs & Insights</span>
        </h2>

        <p style="font-size:1rem; color:#555; max-width:800px; margin:0 auto 40px; text-align:center; line-height:1.7;">
            Stay informed with expert health tips, preventive care advice, and diagnostic insights from Wellcare Labs.
        </p>

        {{-- ===== Grid ===== --}}
        <div class="about-blogs-grid">

            @forelse($blogs as $blog)
                <div class="blog-card fade-item">

                    @if ($blog->featured_image)
                        <div class="blog-image-wrapper">
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}">
                        </div>
                    @endif

                    <div class="blog-body">

                        <h3 class="blog-title">
                            {{ Str::limit($blog->title, 65) }}
                        </h3>

                        <p class="blog-excerpt">
                            {{ $blog->short_description
                ? Str::limit($blog->short_description, 120)
                : Str::limit(strip_tags($blog->content), 120) }}
                        </p>


                        <a href="{{ route('blogs.show', $blog->slug) }}" class="blog-link">
                            Read more →
                        </a>
                    </div>
                </div>
            @empty
                <p style="text-align:center; color:#777; grid-column:1/-1;">
                    No blogs available right now.
                </p>
            @endforelse

        </div>

        {{-- ===== View All ===== --}}
        {{-- ===== View All ===== --}}
        @if ($blogs->count() > 2)
            <div class="view-all-wrap fade-item">
                <a href="{{ route('blogs.index') }}" style="
                 background: linear-gradient(135deg, #0066ff, #00ccff);
                 padding: .6rem 1.2rem;
                 border-radius: 10px;
                 display: inline-block;
                 text-decoration: none;
                 font-weight: 700;
                 color: #000;
                 border: none;
                 cursor: pointer;
                 transition: all 0.25s ease-in-out;
               ">
                    View All Blogs →
                </a>
            </div>
        @endif



        {{-- ===== Scroll Animation Script ===== --}}
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const items = document.querySelectorAll(".fade-item");

                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("show");
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.15
                });

                items.forEach(item => observer.observe(item));
            });

            document.querySelectorAll('.view-all-wrap a').forEach(btn => {
                btn.addEventListener('mouseenter', () => {
                    btn.style.color = '#fff';
                    btn.style.transform = 'translateY(-3px) scale(1.04)';
                    btn.style.boxShadow = '0 0 25px rgba(0, 204, 255, 0.45)';
                });
                btn.addEventListener('mouseleave', () => {
                    btn.style.color = '#000';
                    btn.style.transform = 'none';
                    btn.style.boxShadow = 'none';
                });
            });
        </script>

    </div>

    {{-- ================= END BLOGS ================= --}}


    <!-- WHY CHOOSE US - Diagnostic Centre -->
    <div style="background:#f8fafc; padding:20px 20px 60px 20px; width:100%;">
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








    <!-- Wellcare Team Section -->
    {{-- <div class="page-section">
        <div class="container">
            <div class="col-lg-10 mx-auto mt-5">

                <h1 class="text-center mb-3 wow fadeInUp" style="font-weight:700; color:#000;">
                    Wellcare Team
                </h1>
                <h3 class="text-center mb-5" style="color:#007bff; font-weight:500;">
                    “Dedicated experts bringing <strong>care</strong> and <strong>compassion</strong> to every patient.”
                </h3>

                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4 wow zoomIn">
                        <div class="card-doctor">
                            <div class="header">
                                <img src="{{ asset('Front_end/assets/img/doctors/doctor_1.jpg') }}" alt="">
                                <div class="meta">
                                    <a href="#"><span class="mai-call"></span></a>
                                    <a href="#"><span class="mai-logo-whatsapp"></span></a>
                                </div>
                            </div>
                            <div class="body">
                                <p class="text-xl mb-0">Dr. Stein Albert</p>
                                <span class="text-sm text-grey">Cardiology</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 wow zoomIn">
                        <div class="card-doctor">
                            <div class="header">
                                <img src="{{ asset('Front_end/assets/img/doctors/doctor_2.jpg') }}" alt="">
                                <div class="meta">
                                    <a href="#"><span class="mai-call"></span></a>
                                    <a href="#"><span class="mai-logo-whatsapp"></span></a>
                                </div>
                            </div>
                            <div class="body">
                                <p class="text-xl mb-0">Dr. Alexa Melvin</p>
                                <span class="text-sm text-grey">Dental</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 wow zoomIn">
                        <div class="card-doctor">
                            <div class="header">
                                <img src="{{ asset('Front_end/assets/img/doctors/doctor_3.jpg') }}" alt="">
                                <div class="meta">
                                    <a href="#"><span class="mai-call"></span></a>
                                    <a href="#"><span class="mai-logo-whatsapp"></span></a>
                                </div>
                            </div>
                            <div class="body">
                                <p class="text-xl mb-0">Dr. Rebecca Steffany</p>
                                <span class="text-sm text-grey">General Health</span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div> --}}



@endsection