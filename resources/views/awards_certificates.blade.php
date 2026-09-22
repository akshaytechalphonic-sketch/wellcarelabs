@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Awards, Certifications & Recognitions – Wellcare Labs | Pune')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Recognised for Quality, Trust & Excellence. Explore official certifications, ISO 9001:2015, MSME, DPIIT Startup India, and Maharashtra Business Icon Award 2025 at Wellcare Labs.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif
@endsection

@push('head')
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Outfit:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,800;1,600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }

        /* Hero Section */
        .awards-hero-v2 {
            background: linear-gradient(135deg, #071326 0%, #0d2347 50%, #163b75 100%);
            color: #ffffff;
            padding: 85px 20px 80px;
            text-align: center;
            border-radius: 0 0 44px 44px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(7, 19, 38, 0.35);
        }

        .awards-hero-v2::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            right: -20%;
            bottom: -50%;
            background: radial-gradient(circle at 50% 25%, rgba(245, 158, 11, 0.15) 0%, transparent 65%);
            pointer-events: none;
        }

        .awards-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.45);
            color: #fbbf24;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 7px 20px;
            border-radius: 50px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .awards-hero-v2 h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
            background: linear-gradient(180deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .awards-hero-tagline {
            font-size: 1.45rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
        }

        .awards-hero-v2 p {
            font-size: 1.12rem;
            max-width: 840px;
            margin: 0 auto 12px;
            color: #cbd5e1;
            line-height: 1.75;
        }

        .gold-accent-bar {
            width: 90px;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 50%, #d97706 100%);
            margin: 25px auto 0;
            border-radius: 2px;
            box-shadow: 0 0 14px rgba(245, 158, 11, 0.7);
        }

        /* Trust Highlights Strip */
        .trust-highlights-strip {
            margin-top: -85px;
            position: relative;
            z-index: 10;
            margin-bottom: 60px;
        }

        .trust-card-pill {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid rgba(226, 232, 240, 0.95);
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            height: 100%;
        }

        .trust-card-pill:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.12);
            border-color: #3b82f6;
        }

        .trust-icon-box {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .trust-icon-box.gold {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
        }

        .trust-card-pill h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
            line-height: 1.3;
        }

        .trust-card-pill p {
            font-size: 0.86rem;
            color: #64748b;
            margin-bottom: 0;
            line-height: 1.35;
        }

        /* Section Titles */
        .section-title-wrap {
            text-align: center;
            margin-bottom: 45px;
        }

        .section-subtitle-tag {
            color: #2563eb;
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
            display: block;
        }

        .section-title-wrap h2 {
            font-size: 2.3rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .section-title-wrap .sub {
            color: #64748b;
            font-size: 1.05rem;
            max-width: 660px;
            margin: 0 auto;
        }

        /* Certificate Cards Grid */
        .real-cert-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            justify-items: center;
        }

        .real-cert-card {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            max-width: 390px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .real-cert-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.18);
            border-color: #3b82f6;
        }

        .real-cert-card.featured-award {
            border: 2px solid #f59e0b;
            box-shadow: 0 14px 40px -10px rgba(245, 158, 11, 0.28);
        }

        .real-cert-card.featured-award:hover {
            border-color: #d97706;
            box-shadow: 0 26px 50px -10px rgba(245, 158, 11, 0.38);
        }

        .cert-img-wrapper {
            position: relative;
            display: block;
            overflow: hidden;
            background: #f8fafc;
            cursor: pointer;
        }

        .real-cert-card img {
            width: 100%;
            height: 300px;
            object-fit: contain;
            padding: 18px;
            transition: transform 0.4s ease;
            display: block;
        }

        .real-cert-card:hover img {
            transform: scale(1.04);
        }

        .cert-hover-zoom {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(2px);
        }

        .real-cert-card:hover .cert-hover-zoom {
            opacity: 1;
        }

        .badge-award-featured {
            position: absolute;
            top: 14px;
            right: 14px;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #ffffff;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 5px 12px;
            border-radius: 50px;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.45);
            z-index: 2;
        }

        .real-cert-caption {
            padding: 20px;
            border-top: 1px solid #f1f5f9;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 5px;
            flex-grow: 1;
        }

        .real-cert-caption .cert-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.06rem;
            font-weight: 700;
            color: #0f172a;
        }

        .real-cert-caption .cert-title-row i {
            color: #2563eb;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .real-cert-card.featured-award .real-cert-caption .cert-title-row i {
            color: #f59e0b;
        }

        .real-cert-caption .cert-sub-text {
            font-size: 0.88rem;
            color: #64748b;
            margin-left: 28px;
            line-height: 1.4;
        }

        .cert-card-footer {
            padding: 12px 20px 16px;
            background: #ffffff;
            border-top: 1px dashed #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cert-verified-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.78rem;
            font-weight: 700;
            color: #16a34a;
        }

        .cert-view-btn {
            font-size: 0.85rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s ease;
        }

        .cert-view-btn:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* Health & Diagnostic Information Box */
        .info-card-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
            border-radius: 26px;
            padding: 38px 32px;
            display: flex;
            align-items: flex-start;
            gap: 26px;
            box-shadow: 0 10px 30px -10px rgba(2, 132, 199, 0.12);
        }

        .info-card-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: #0284c7;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
        }

        .info-card-content h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .info-card-content p {
            font-size: 1.08rem;
            color: #334155;
            line-height: 1.75;
            margin-bottom: 0;
        }

        /* Commitment to Quality Pillars */
        .commitment-card {
            background: #ffffff;
            border-radius: 22px;
            border: 1px solid #e2e8f0;
            padding: 32px 26px;
            height: 100%;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .commitment-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.14);
            border-color: #3b82f6;
        }

        .commitment-icon-box {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.55rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .commitment-card:hover .commitment-icon-box {
            background: #2563eb;
            color: #ffffff;
            transform: scale(1.05);
        }

        .commitment-card h4 {
            font-size: 1.28rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .commitment-card p {
            font-size: 0.96rem;
            color: #475569;
            line-height: 1.68;
            margin-bottom: 0;
        }

        /* Call To Action Box */
        .cta-trust-box {
            background: linear-gradient(135deg, #071326 0%, #163b75 100%);
            border-radius: 32px;
            color: #ffffff;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 25px 50px -15px rgba(7, 19, 38, 0.4);
            position: relative;
            overflow: hidden;
        }

        .cta-trust-box::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.28) 0%, transparent 70%);
            pointer-events: none;
        }

        .cta-trust-box h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .cta-trust-box p.lead-text {
            font-size: 1.18rem;
            max-width: 820px;
            margin: 0 auto 18px;
            color: #cbd5e1;
            line-height: 1.75;
        }

        .cta-trust-box p.tagline-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fbbf24;
            margin-bottom: 32px;
        }

        .btn-cta-explore {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            font-size: 1.12rem;
            font-weight: 700;
            padding: 15px 38px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-cta-explore:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 35px rgba(37, 99, 235, 0.6);
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff !important;
        }

        /* Certificate Lightbox Modal */
        #certModal .modal-content {
            background-color: transparent;
            border: none;
        }

        #certModal .modal-header {
            border: none;
            padding-bottom: 0;
        }

        #certModal .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
            opacity: 0.9;
        }

        #certModal .cert-modal-body {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        #certModal img {
            max-height: 80vh;
            max-width: 100%;
            object-fit: contain;
            border-radius: 12px;
        }

        @media(max-width: 768px) {
            .awards-hero-v2 {
                padding: 65px 16px 65px;
            }

            .awards-hero-v2 h1 {
                font-size: 2.2rem;
            }

            .awards-hero-tagline {
                font-size: 1.2rem;
            }

            .info-card-box {
                flex-direction: column;
                gap: 16px;
                padding: 25px 20px;
            }

            .real-cert-grid {
                grid-template-columns: 1fr;
            }

            .real-cert-card {
                max-width: 100%;
            }

            .real-cert-card img {
                height: 250px;
            }

            .cta-trust-box {
                padding: 45px 20px;
            }

            .cta-trust-box h2 {
                font-size: 1.85rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-package">

        {{-- Hero Header --}}
        <div class="awards-hero-v2">
            <div class="awards-hero-badge">
                <i class="fa-solid fa-award"></i> Quality, Trust & Excellence
            </div>
            <h1>Awards, Certifications & Recognitions</h1>
            <div class="awards-hero-tagline">Recognised for Quality, Trust & Excellence</div>
            <p>At Wellcare Labs, we believe that quality diagnostics begin with strong systems, responsible processes and a commitment to patient care.</p>
            <p>Our certifications, registrations and recognitions reflect our continued focus on building a reliable and patient-focused diagnostic healthcare services.</p>
            <div class="gold-accent-bar"></div>
        </div>

        {{-- Trust Highlights Strip (4 Core Accreditations) --}}
        <div class="container trust-highlights-strip">
            <div class="row g-3">
                {{-- 1. ISO 9001:2015 --}}
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-certificate"></i></div>
                        <div>
                            <h4>ISO 9001:2015 Certification</h4>
                            <p>Certified Quality Management System</p>
                        </div>
                    </div>
                </div>

                {{-- 2. MSME Registration --}}
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-building-shield"></i></div>
                        <div>
                            <h4>MSME Registration</h4>
                            <p>Recognised as a Micro, Small & Medium Enterprise</p>
                        </div>
                    </div>
                </div>

                {{-- 3. DPIIT Startup India --}}
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-rocket"></i></div>
                        <div>
                            <h4>DPIIT Startup India Recognition</h4>
                            <p>Recognised Startup under Startup India</p>
                        </div>
                    </div>
                </div>

                {{-- 4. Maharashtra Business Icon Award 2025 --}}
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box gold"><i class="fa-solid fa-trophy"></i></div>
                        <div>
                            <h4>Maharashtra Business Icon Award 2025</h4>
                            <p>Recognised for Business Excellence</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Official Certificates & Awards Gallery --}}
        <div class="page-section py-5 bg-light" style="border-radius: 32px; margin-bottom: 55px;">
            <div class="container">
                <div class="section-title-wrap">
                    <span class="section-subtitle-tag">Document Showcase</span>
                    <h2>Official Accreditations & Recognitions</h2>
                    <div class="sub">Authentic registration and quality certification credentials for Wellcare Labs</div>
                </div>

                <div class="real-cert-grid">

                    {{-- 1. Maharashtra Business Icon Award 2025 (Featured Award) --}}
                    <div class="real-cert-card featured-award">
                        <span class="badge-award-featured"><i class="fa-solid fa-star me-1"></i> Award 2025</span>
                        <div class="cert-img-wrapper" onclick="openCertModal('{{ asset('Front_end/assets/img/20260818_155808.jpg.jpeg') }}', 'Maharashtra Business Icon Award 2025')">
                            <img src="{{ asset('Front_end/assets/img/20260818_155808.jpg.jpeg') }}"
                                 alt="Maharashtra Business Icon Award 2025 – Wellcare Medical Services Pvt. Ltd." loading="lazy">
                            <div class="cert-hover-zoom">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Click to View Full Size
                            </div>
                        </div>
                        <div class="real-cert-caption">
                            <div class="cert-title-row">
                                <i class="fa-solid fa-trophy"></i>
                                <span>Maharashtra Business Icon Award 2025</span>
                            </div>
                            <div class="cert-sub-text">Recognised for Business Excellence</div>
                        </div>
                        <div class="cert-card-footer">
                            <span class="cert-verified-badge"><i class="fa-solid fa-circle-check"></i> Verified Honor</span>
                            <a href="{{ asset('Front_end/assets/img/20260818_155808.jpg.jpeg') }}" target="_blank" rel="noopener" class="cert-view-btn">
                                Open Document <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 2. ISO 9001:2015 Certification --}}
                    <div class="real-cert-card">
                        <div class="cert-img-wrapper" onclick="openCertModal('{{ asset('Front_end/assets/img/ISO 9001 2015.jpeg') }}', 'ISO 9001:2015 Certification')">
                            <img src="{{ asset('Front_end/assets/img/ISO 9001 2015.jpeg') }}"
                                 alt="ISO 9001:2015 Certificate – Wellcare Labs" loading="lazy">
                            <div class="cert-hover-zoom">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Click to View Full Size
                            </div>
                        </div>
                        <div class="real-cert-caption">
                            <div class="cert-title-row">
                                <i class="fa-solid fa-certificate"></i>
                                <span>ISO 9001:2015 Certification</span>
                            </div>
                            <div class="cert-sub-text">Certified Quality Management System</div>
                        </div>
                        <div class="cert-card-footer">
                            <span class="cert-verified-badge"><i class="fa-solid fa-circle-check"></i> ISO Certified</span>
                            <a href="{{ asset('Front_end/assets/img/ISO 9001 2015.jpeg') }}" target="_blank" rel="noopener" class="cert-view-btn">
                                Open Document <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 3. DPIIT Startup India Recognition --}}
                    <div class="real-cert-card">
                        <div class="cert-img-wrapper" onclick="openCertModal('{{ asset('Front_end/assets/img/DPIIT Startup India Certificate - Wellcare Labs.jpeg') }}', 'DPIIT Startup India Recognition')">
                            <img src="{{ asset('Front_end/assets/img/DPIIT Startup India Certificate - Wellcare Labs.jpeg') }}"
                                 alt="DPIIT Startup India Certificate – Wellcare Labs" loading="lazy">
                            <div class="cert-hover-zoom">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Click to View Full Size
                            </div>
                        </div>
                        <div class="real-cert-caption">
                            <div class="cert-title-row">
                                <i class="fa-solid fa-rocket"></i>
                                <span>DPIIT Startup India Recognition</span>
                            </div>
                            <div class="cert-sub-text">Recognised Startup under Startup India</div>
                        </div>
                        <div class="cert-card-footer">
                            <span class="cert-verified-badge"><i class="fa-solid fa-circle-check"></i> DPIIT Recognized</span>
                            <a href="{{ asset('Front_end/assets/img/DPIIT Startup India Certificate - Wellcare Labs.jpeg') }}" target="_blank" rel="noopener" class="cert-view-btn">
                                Open Document <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 4. MSME Udyam Registration (Page 1) --}}
                    <div class="real-cert-card">
                        <div class="cert-img-wrapper" onclick="openCertModal('{{ asset('Front_end/assets/img/MSME Udyam 1.jpeg') }}', 'MSME Registration (Part 1)')">
                            <img src="{{ asset('Front_end/assets/img/MSME Udyam 1.jpeg') }}"
                                 alt="MSME Udyam Registration Certificate – Wellcare Labs" loading="lazy">
                            <div class="cert-hover-zoom">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Click to View Full Size
                            </div>
                        </div>
                        <div class="real-cert-caption">
                            <div class="cert-title-row">
                                <i class="fa-solid fa-building-shield"></i>
                                <span>MSME Registration (Part 1)</span>
                            </div>
                            <div class="cert-sub-text">Micro, Small & Medium Enterprise</div>
                        </div>
                        <div class="cert-card-footer">
                            <span class="cert-verified-badge"><i class="fa-solid fa-circle-check"></i> MSME Registered</span>
                            <a href="{{ asset('Front_end/assets/img/MSME Udyam 1.jpeg') }}" target="_blank" rel="noopener" class="cert-view-btn">
                                Open Document <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>

                    {{-- 5. MSME Udyam Certificate (Page 2) --}}
                    <div class="real-cert-card">
                        <div class="cert-img-wrapper" onclick="openCertModal('{{ asset('Front_end/assets/img/MSME Udyam 2.jpeg') }}', 'MSME Registration (Part 2)')">
                            <img src="{{ asset('Front_end/assets/img/MSME Udyam 2.jpeg') }}"
                                 alt="MSME Udyam Certificate – Wellcare Labs" loading="lazy">
                            <div class="cert-hover-zoom">
                                <i class="fa-solid fa-magnifying-glass-plus"></i> Click to View Full Size
                            </div>
                        </div>
                        <div class="real-cert-caption">
                            <div class="cert-title-row">
                                <i class="fa-solid fa-stamp"></i>
                                <span>MSME Registration (Part 2)</span>
                            </div>
                            <div class="cert-sub-text">Recognised Enterprise Registration</div>
                        </div>
                        <div class="cert-card-footer">
                            <span class="cert-verified-badge"><i class="fa-solid fa-circle-check"></i> Official Gov Record</span>
                            <a href="{{ asset('Front_end/assets/img/MSME Udyam 2.jpeg') }}" target="_blank" rel="noopener" class="cert-view-btn">
                                Open Document <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Health & Diagnostic Information Section --}}
        <div class="container mb-5">
            <div class="info-card-box">
                <div class="info-card-icon">
                    <i class="fa-solid fa-book-medical"></i>
                </div>
                <div class="info-card-content">
                    <h3>Health & Diagnostic Information</h3>
                    <p>Wellcare Labs shares patient education content based on established medical and scientific resources, including information published by recognised institutions such as ICMR and other public health bodies.</p>
                </div>
            </div>
        </div>

        {{-- Our Commitment to Quality Section --}}
        <div class="page-section py-5">
            <div class="container">
                <div class="section-title-wrap">
                    <span class="section-subtitle-tag">Patient First Principles</span>
                    <h2>Our Commitment to Quality</h2>
                    <div class="sub">
                        At Wellcare Labs, certifications and recognitions are more than achievements — they represent our responsibility towards patients, healthcare professionals and the communities we serve.
                        <div class="mt-2 fw-semibold text-primary">We continue to focus on:</div>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- 1. Quality --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="commitment-card">
                            <div class="commitment-icon-box">
                                <i class="fa-solid fa-award"></i>
                            </div>
                            <h4>Quality</h4>
                            <p>Structured processes and a continuous focus on improvement.</p>
                        </div>
                    </div>

                    {{-- 2. Accuracy --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="commitment-card">
                            <div class="commitment-icon-box">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <h4>Accuracy</h4>
                            <p>Reliable diagnostic services designed to support better healthcare decisions.</p>
                        </div>
                    </div>

                    {{-- 3. Patient Care --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="commitment-card">
                            <div class="commitment-icon-box">
                                <i class="fa-solid fa-hand-holding-heart"></i>
                            </div>
                            <h4>Patient Care</h4>
                            <p>Convenient, transparent and patient-friendly diagnostic experiences.</p>
                        </div>
                    </div>

                    {{-- 4. Continuous Improvement --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="commitment-card">
                            <div class="commitment-icon-box">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h4>Continuous Improvement</h4>
                            <p>A commitment to improving our services, systems and patient experience over time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Building Trust Through Better Diagnostics (Call to Action) --}}
        <div class="container mb-5 pb-4">
            <div class="cta-trust-box">
                <h2>Building Trust Through Better Diagnostics</h2>
                <p class="lead-text">
                    From routine blood tests and preventive health checkups to convenient home sample collection, Wellcare Labs continues to work towards making diagnostic healthcare more accessible and affordable to everyone.
                </p>
                <p class="tagline-text">
                    Wellcare Labs — Redefining Quality Diagnostics for Better India.
                </p>
                <div>
                    <a href="https://wellcarelabs.in/" class="btn-cta-explore">
                        <i class="fa-solid fa-compass"></i> Explore Our Services
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Interactive Certificate Modal --}}
    <div class="modal fade" id="certModal" tabindex="-1" aria-labelledby="certModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title text-white fw-bold" id="certModalTitle">Certificate View</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="cert-modal-body mt-2">
                    <img id="certModalImg" src="" alt="Certificate Full View">
                    <div class="mt-3 d-flex justify-content-between align-items-center w-100 px-2">
                        <span id="certModalCaption" class="fw-bold text-dark"></span>
                        <a id="certModalLink" href="" target="_blank" rel="noopener" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Open Original Image
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCertModal(src, title) {
            const modalEl = document.getElementById('certModal');
            if (!modalEl) return;
            document.getElementById('certModalImg').src = src;
            document.getElementById('certModalTitle').textContent = title;
            document.getElementById('certModalCaption').textContent = title;
            document.getElementById('certModalLink').href = src;
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    </script>
@endsection