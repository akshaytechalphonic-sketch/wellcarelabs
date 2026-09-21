@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Awards, Certifications & Accreditations – Wellcare Labs | Pune')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Explore the official certifications, ISO 9001:2015 accreditations, NABL compliance, and excellence awards of Wellcare Labs Pune. Certified diagnostic precision.')

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
        }

        /* Hero Section */
        .awards-hero-v2 {
            background: linear-gradient(135deg, #0b132b 0%, #1c2541 50%, #1e3a8a 100%);
            color: #ffffff;
            padding: 85px 20px 75px;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(11, 19, 43, 0.25);
        }

        .awards-hero-v2::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            right: -20%;
            bottom: -50%;
            background: radial-gradient(circle at 50% 30%, rgba(245, 158, 11, 0.12) 0%, transparent 60%);
            pointer-events: none;
        }

        .awards-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.4);
            color: #fbbf24;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }

        .awards-hero-v2 h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
            background: linear-gradient(180deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .awards-hero-v2 p {
            font-size: 1.2rem;
            max-width: 780px;
            margin: 0 auto 25px;
            color: #94a3b8;
            line-height: 1.7;
        }

        .gold-accent-bar {
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 50%, #d97706 100%);
            margin: 0 auto;
            border-radius: 2px;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.6);
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
            border: 1px solid rgba(226, 232, 240, 0.9);
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .trust-card-pill:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.12);
            border-color: #3b82f6;
        }

        .trust-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .trust-card-pill h4 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .trust-card-pill p {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0;
        }

        /* Section Titles */
        .section-title-wrap {
            text-align: center;
            margin-bottom: 50px;
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
            max-width: 620px;
            margin: 0 auto;
        }

        /* Certifications Grid */
        .cert-card-v2 {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            padding: 35px 30px;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .cert-card-v2:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15);
            border-color: #3b82f6;
        }

        .cert-card-v2::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .cert-card-v2:hover::before {
            opacity: 1;
        }

        .cert-badge-pill {
            position: absolute;
            top: 24px;
            right: 24px;
            background: #f0fdf4;
            color: #166534;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 50px;
            border: 1px solid #bbf7d0;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .cert-icon-wrapper-v2 {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #f0f9ff;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            margin-bottom: 25px;
        }

        .cert-card-v2 h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .cert-card-v2 p {
            font-size: 0.98rem;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        /* Enhanced Framed Certificates Showcase */
        .certificate-frame-container {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 35px;
            flex-wrap: wrap;
        }

        .cert-frame-v2 {
            background: #fffdf9;
            border: 16px solid #0f172a;
            box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.3), 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 35px;
            max-width: 500px;
            width: 100%;
            position: relative;
            border-radius: 6px;
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }

        .cert-frame-v2:hover {
            transform: scale(1.02);
        }

        .cert-frame-gold-border {
            border: 2px solid #d4af37;
            padding: 28px 24px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .cert-frame-gold-border::before {
            content: '';
            position: absolute;
            top: 4px;
            left: 4px;
            right: 4px;
            bottom: 4px;
            border: 1px solid #eab308;
        }

        .cert-frame-header {
            font-family: 'Cinzel', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #856404;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 6px;
        }

        .cert-frame-sub {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 22px;
            font-weight: 600;
        }

        .cert-frame-title {
            font-size: 1rem;
            color: #475569;
            font-weight: 500;
            margin-bottom: 12px;
        }

        .cert-frame-entity {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }

        .cert-frame-text {
            font-size: 0.92rem;
            color: #334155;
            line-height: 1.65;
            margin-bottom: 30px;
        }

        .cert-frame-footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }

        .cert-seal-v2 {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: 4px double #ffffff;
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.4);
            color: #ffffff;
            font-size: 2.2rem;
            position: relative;
        }

        .cert-signature-v2 {
            text-align: center;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            min-width: 130px;
        }

        .cert-signature-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0;
            line-height: 1.1;
        }

        .cert-signature-title {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Timeline Section for Awards */
        .awards-timeline-box-v2 {
            background: #f8fafc;
            border-radius: 30px;
            padding: 50px 35px;
            border: 1px solid #e2e8f0;
        }

        .award-row-v2 {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 35px;
        }

        .award-row-v2:last-child {
            margin-bottom: 0;
        }

        .award-year-badge {
            font-size: 1.8rem;
            font-weight: 900;
            color: #ffffff;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            padding: 10px 24px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
            min-width: 120px;
            text-align: center;
            flex-shrink: 0;
        }

        .award-content-card-v2 {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            border: 1px solid #f1f5f9;
            flex-grow: 1;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .award-icon-box-v2 {
            width: 58px;
            height: 58px;
            border-radius: 16px;
            background: #fef3c7;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .award-text-v2 h4 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .award-text-v2 p {
            font-size: 0.98rem;
            color: #475569;
            margin-bottom: 0;
            line-height: 1.6;
        }

        @media(max-width: 768px) {
            .awards-hero-v2 h1 {
                font-size: 2.2rem;
            }

            .award-row-v2 {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .award-year-badge {
                min-width: auto;
                padding: 6px 16px;
                font-size: 1.3rem;
            }

            .award-content-card-v2 {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-package">

        {{-- Hero Header --}}
        <div class="awards-hero-v2">
            <div class="awards-hero-badge">
                <i class="fa-solid fa-medal"></i> Quality Accreditations
            </div>
            <h1>Accreditations & Certificates</h1>
            <p>Wellcare Labs is dedicated to delivering gold-standard clinical precision. Our processes, testing equipment,
                and verified reports strictly comply with top medical guidelines and ISO/NABL standards in Pune.</p>
            <div class="gold-accent-bar"></div>
        </div>

        {{-- Trust Highlights Strip --}}
        <div class="container trust-highlights-strip">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-certificate"></i></div>
                        <div>
                            <h4>ISO 9001:2015</h4>
                            <p>Certified Quality System</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-square-check"></i></div>
                        <div>
                            <h4>NABL Standards</h4>
                            <p>Strict Guideline Audit</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-shield-virus"></i></div>
                        <div>
                            <h4>ICMR Approved</h4>
                            <p>Health Board Verified</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="trust-card-pill">
                        <div class="trust-icon-box"><i class="fa-solid fa-microscope"></i></div>
                        <div>
                            <h4>100% Precision</h4>
                            <p>Automated Analyzer Runs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Certifications --}}
        <div class="page-section pb-5">
            <div class="container">
                <div class="section-title-wrap">
                    <span class="section-subtitle-tag">Verification & Trust</span>
                    <h2>Official Accreditations & Standards</h2>
                    <div class="sub">Validating our continuous dedication to absolute pathology report accuracy and patient
                        safety.</div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="cert-card-v2">
                            <span class="cert-badge-pill"><i class="fa-solid fa-check"></i> Certified</span>
                            <div class="cert-icon-wrapper-v2">
                                <i class="fa-solid fa-stamp"></i>
                            </div>
                            <h3>ISO 9001:2015 Certification</h3>
                            <p>Wellcare Labs holds ISO 9001:2015 certification, verifying that our laboratory management,
                                safety protocols, and sample processing pipelines operate at peak international standards.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="cert-card-v2">
                            <span class="cert-badge-pill"><i class="fa-solid fa-check"></i> Compliant</span>
                            <div class="cert-icon-wrapper-v2">
                                <i class="fa-solid fa-flask-vial"></i>
                            </div>
                            <h3>NABL Quality Compliance</h3>
                            <p>Our testing protocols follow NABL (National Accreditation Board for Testing and Calibration
                                Laboratories) quality control standards, ensuring error-free report generation.</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="cert-card-v2">
                            <span class="cert-badge-pill"><i class="fa-solid fa-check"></i> Registered</span>
                            <div class="cert-icon-wrapper-v2">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <h3>ICMR & Health Authorities</h3>
                            <p>Approved and registered with Indian health authorities, validating our diagnostic procedures,
                                chemical reagents, and sanitation protocols across Pune.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Framed Certificates Showcase --}}
        <div class="page-section py-5 bg-light" style="border-radius: 30px; margin-bottom: 50px;">
            <div class="container">
                <div class="section-title-wrap">
                    <span class="section-subtitle-tag">Document Showcase</span>
                    <h2>Official Certificates of Quality</h2>
                    <div class="sub">Authentic accreditation credentials certifying Wellcare Diagnostic procedures</div>
                </div>

                <div class="certificate-frame-container">
                    {{-- Cert 1 --}}
                    <div class="cert-frame-v2">
                        <div class="cert-frame-gold-border">
                            <div class="cert-frame-header">Certificate</div>
                            <div class="cert-frame-sub">of ISO Registration</div>
                            <div class="cert-frame-title">This is to certify that the Quality System of</div>
                            <div class="cert-frame-entity">Wellcare Labs</div>
                            <div class="cert-frame-text">
                                Has been assessed and found compliant with standard quality controls.<br>
                                <strong>ISO 9001:2015 Quality Management System</strong><br>
                                Scope: Clinical Pathology, Biochemistry & Immunology Testing.
                            </div>
                            <div class="cert-frame-footer">
                                <div class="cert-seal-v2">
                                    <i class="fa-solid fa-award"></i>
                                </div>
                                <div class="cert-signature-v2">
                                    <div class="cert-signature-name">Dr. A. K. Joshi</div>
                                    <div class="cert-signature-title">Pathology Director</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Cert 2 --}}
                    <div class="cert-frame-v2">
                        <div class="cert-frame-gold-border">
                            <div class="cert-frame-header">Accreditation</div>
                            <div class="cert-frame-sub">NABL Compliance</div>
                            <div class="cert-frame-title">This document certifies standard operations of</div>
                            <div class="cert-frame-entity">Wellcare Diagnostics</div>
                            <div class="cert-frame-text">
                                Following the guidelines of National Testing Standard Authorities.<br>
                                Maintaining strict criteria for lab environment, equipment calibration, and medical staff
                                qualifications.
                            </div>
                            <div class="cert-frame-footer">
                                <div class="cert-seal-v2"
                                    style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                                    <i class="fa-solid fa-certificate"></i>
                                </div>
                                <div class="cert-signature-v2">
                                    <div class="cert-signature-name">K. Raghavan</div>
                                    <div class="cert-signature-title">Quality Officer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Awards & Milestones --}}
        <div class="page-section pb-5">
            <div class="container">
                <div class="section-title-wrap">
                    <span class="section-subtitle-tag">Honors & Milestones</span>
                    <h2>Awards & Recognition</h2>
                    <div class="sub">Key accolades celebrating our commitment to diagnostic innovation and patient care.
                    </div>
                </div>

                <div class="awards-timeline-box-v2">
                    <div class="award-row-v2">
                        <div class="award-year-badge">2025</div>
                        <div class="award-content-card-v2">
                            <div class="award-icon-box-v2"><i class="fa-solid fa-trophy"></i></div>
                            <div class="award-text-v2">
                                <h4>Clinical Excellence Award</h4>
                                <p>Awarded for top pathology precision metrics, quick report turnaround times, and
                                    outstanding patient support frameworks in the Pune West region.</p>
                            </div>
                        </div>
                    </div>

                    <div class="award-row-v2">
                        <div class="award-year-badge">2024</div>
                        <div class="award-content-card-v2">
                            <div class="award-icon-box-v2"><i class="fa-solid fa-laptop-code"></i></div>
                            <div class="award-text-v2">
                                <h4>Best Health-Tech Integration</h4>
                                <p>Recognized for launching automated report sharing systems, WhatsApp report fetching, and
                                    fully digitized booking capabilities for home collections.</p>
                            </div>
                        </div>
                    </div>

                    <div class="award-row-v2">
                        <div class="award-year-badge">2023</div>
                        <div class="award-content-card-v2">
                            <div class="award-icon-box-v2"><i class="fa-solid fa-heart-circle-check"></i></div>
                            <div class="award-text-v2">
                                <h4>Patient Trust Award</h4>
                                <p>Voted by over 50,000+ local patients for compassion in diagnostics, premium patient care
                                    service, and affordable pricing models.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection