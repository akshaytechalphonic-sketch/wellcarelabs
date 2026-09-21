@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Our Leadership & Vision – Wellcare Labs | Pune')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Meet the leadership behind Wellcare Labs. Discover our founders, vision, and clinical journey in redefining quality diagnostics in India.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif
@endsection

@push('head')
    <style>
        /* Hero Section */
        .founder-hero {
            background: linear-gradient(135deg, #0a2540 0%, #113866 50%, #0047ff 100%);
            color: #ffffff;
            padding: 70px 20px 65px;
            text-align: center;
            border-radius: 0 0 32px 32px;
            margin-bottom: 45px;
            position: relative;
            box-shadow: 0 10px 30px rgba(10, 37, 64, 0.15);
        }

        .founder-hero h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .founder-hero p {
            font-size: 1.15rem;
            max-width: 750px;
            margin: 0 auto;
            color: #cbd5e1;
            line-height: 1.65;
            font-weight: 400;
        }

        .founder-hero .accent-bar {
            width: 90px;
            height: 4px;
            background: linear-gradient(90deg, #00ccff, #0047ff);
            margin: 22px auto 0;
            border-radius: 4px;
        }

        /* Profile Cards */
        .leader-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.05);
            padding: 40px;
            margin-bottom: 40px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .leader-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.09);
        }

        .leader-img-box {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            background: linear-gradient(180deg, #f8fafc 0%, #edf2f7 100%);
            max-width: 320px;
            margin: 0 auto;
            text-align: center;
        }

        .leader-img-box img {
            width: 100%;
            height: 380px;
            object-fit: cover;
            object-position: top center;
            display: block;
            transition: transform 0.3s ease;
        }

        .leader-card:hover .leader-img-box img {
            transform: scale(1.02);
        }

        .leader-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            color: #475569;
            padding: 6px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            cursor: default;
        }

        .leader-name {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0a2540;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
            cursor: default;
        }

        .leader-title {
            font-size: 1.1rem;
            color: #0d6efd;
            font-weight: 700;
            margin-bottom: 20px;
            cursor: default;
        }

        .leader-bio {
            cursor: default;
        }

        .leader-bio p {
            color: #334155;
            line-height: 1.8;
            font-size: 1.02rem;
            margin-bottom: 14px;
            cursor: default;
            text-decoration: none !important;
        }

        .leader-bio p a {
            color: inherit;
            text-decoration: none;
            cursor: default;
        }

        .leader-quote {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border-left: 5px solid #0d6efd;
            padding: 22px 26px;
            border-radius: 0 16px 16px 0;
            margin: 24px 0 16px;
            position: relative;
            cursor: default;
        }

        .leader-quote-text {
            font-style: italic;
            font-size: 1.05rem;
            color: #0f172a;
            font-weight: 600;
            line-height: 1.6;
            margin-bottom: 10px;
            cursor: default;
        }

        .leader-quote-text i {
            color: #0d6efd;
            font-size: 1.3rem;
            margin-right: 6px;
        }

        .quote-author {
            font-style: normal;
            font-weight: 700;
            color: #0a2540;
            font-size: 0.95rem;
            display: block;
            cursor: default;
        }

        .tagline-pill {
            display: inline-block;
            background: linear-gradient(90deg, #0d6efd, #00ccff);
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 6px 18px;
            border-radius: 50px;
            box-shadow: 0 3px 10px rgba(13, 110, 253, 0.2);
            cursor: default;
        }

        /* Milestones / Timeline */
        .milestones-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            padding: 45px 40px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.05);
            margin-bottom: 50px;
        }

        .milestones-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .milestones-header h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0a2540;
            margin-bottom: 8px;
        }

        .milestones-header p {
            color: #64748b;
            font-size: 1.05rem;
        }

        .timeline-container {
            position: relative;
            max-width: 850px;
            margin: 0 auto;
            padding-left: 35px;
            border-left: 3px solid #e2e8f0;
        }

        .timeline-block {
            position: relative;
            margin-bottom: 28px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px 24px;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .timeline-block:hover {
            transform: translateX(4px);
            border-color: #0047ff;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 71, 255, 0.08);
        }

        .timeline-block:last-child {
            margin-bottom: 0;
        }

        .timeline-block::before {
            content: '';
            position: absolute;
            left: -51px;
            top: 22px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0047ff, #00ccff);
            border: 4px solid #ffffff;
            box-shadow: 0 0 0 3px #bfdbfe;
        }

        .timeline-year-badge {
            display: inline-block;
            background: #0047ff;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.85rem;
            padding: 3px 14px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .timeline-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: #0a2540;
            margin-bottom: 6px;
        }

        .timeline-desc {
            color: #475569;
            font-size: 0.98rem;
            line-height: 1.65;
            margin: 0;
        }

        .milestones-footer {
            text-align: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .milestones-footer .footer-tagline {
            font-weight: 800;
            font-size: 1.25rem;
            color: #0047ff;
            letter-spacing: -0.2px;
        }

        @media (max-width: 768px) {
            .founder-hero {
                padding: 50px 15px 45px;
            }
            .founder-hero h1 {
                font-size: 1.9rem;
            }
            .founder-hero p {
                font-size: 0.98rem;
            }
            .leader-card {
                padding: 24px 18px;
            }
            .leader-img-box {
                margin-bottom: 24px;
            }
            .leader-img-box img {
                height: 320px;
            }
            .leader-name {
                font-size: 1.75rem;
            }
            .leader-title {
                font-size: 1rem;
            }
            .milestones-card {
                padding: 28px 18px;
            }
            .milestones-header h2 {
                font-size: 1.75rem;
            }
            .timeline-container {
                padding-left: 24px;
            }
            .timeline-block::before {
                left: -39px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="founder-hero">
        <div class="container">
            <h1>Our Leadership & Vision</h1>
            <p>Driving quality diagnostics, technological innovation, and patient-first healthcare values across India.</p>
            <div class="accent-bar"></div>
        </div>
    </div>

    <div class="container mb-5">
        
        <!-- 1. Founder & Director Section (Abhijit Pansare) -->
        <div class="leader-card">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center">
                    <div class="leader-img-box">
                        <img src="{{ asset('Front_end/assets/img/cofounder.png') }}" alt="Abhijit Pansare">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="leader-badge">
                        <i class="fa fa-user-tie"></i> Founder Leadership
                    </div>
                    <h2 class="leader-name">Abhijit Pansare</h2>
                    <div class="leader-title">Founder & Director, Wellcare Labs</div>

                    <div class="leader-bio">
                        <p>
                            Abhijit Pansare is the Founder & Director of Wellcare Labs, with a vision to make quality diagnostics more accessible, reliable, and patient-focused.
                        </p>
                        <p>
                            With an academic background in Microbiology and an M.Sc. from Savitribai Phule Pune University, he brings together scientific knowledge, entrepreneurial thinking, and a strong commitment to building dependable healthcare services.
                        </p>
                        <p>
                            As the founder, Abhijit leads the organization’s strategic growth, service quality, technology initiatives, team development, and patient-centric approach.
                        </p>
                        <p>
                            His vision goes beyond diagnostics — to build a healthcare brand where quality meets accessibility, technology meets convenience, and every patient is treated with empathy and trust.
                        </p>
                    </div>

                    <div class="leader-quote">
                        <div class="leader-quote-text">
                            <i class="fa fa-quote-left"></i>
                            “Our goal is not just to deliver diagnostic reports, but to build trust through every test, every report, and every patient experience.”
                        </div>
                        <span class="quote-author">Abhijit Pansare &mdash; Founder & Director, Wellcare Labs</span>
                    </div>

                    <div class="mt-3">
                        <span class="tagline-pill">Redefining Quality Diagnostics for Better India.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Co-Founder Section (Swapnil Jadhav) -->
        <div class="leader-card">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center">
                    <div class="leader-img-box">
                        <img src="{{ asset('Front_end/assets/img/founderceo.png') }}" alt="Swapnil Jadhav">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="leader-badge">
                        <i class="fa fa-user-shield"></i> Co-Founder Leadership
                    </div>
                    <h2 class="leader-name">Swapnil Jadhav</h2>
                    <div class="leader-title">Co-Founder, Wellcare Labs</div>

                    <div class="leader-bio">
                        <p>
                            Swapnil Jadhav is the Co-Founder of Wellcare Labs, driven by a vision to make quality diagnostics more accessible, convenient, and trustworthy.
                        </p>
                        <p>
                            As part of the founding team, he contributes to business growth, strategic partnerships, market expansion, and customer relationships, helping shape Wellcare Labs into a patient-focused diagnostic healthcare brand.
                        </p>
                        <p>
                            For Swapnil, building Wellcare Labs is more than building a business — it is about creating a healthcare experience where quality meets accessibility and every patient feels valued.
                        </p>
                        <p>
                            With an entrepreneurial mindset and a strong focus on sustainable growth, he continues to work toward expanding Wellcare Labs and its vision of delivering dependable diagnostic services across India.
                        </p>
                    </div>

                    <div class="leader-quote">
                        <div class="leader-quote-text">
                            <i class="fa fa-quote-left"></i>
                            “We are building more than a diagnostic business — we are building trust, one patient at a time.”
                        </div>
                        <span class="quote-author">Swapnil Jadhav &mdash; Co-Founder, Wellcare Labs</span>
                    </div>

                    <div class="mt-3">
                        <span class="tagline-pill">Redefining Quality Diagnostics for Better India.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Clinical Journey & Milestones -->
        <div class="milestones-card">
            <div class="milestones-header">
                <h2>Clinical Journey & Milestones</h2>
                <p>Our key growth phases, achievements, and roadmap toward healthcare excellence.</p>
            </div>

            <div class="timeline-container">
                
                <div class="timeline-block">
                    <span class="timeline-year-badge">2018</span>
                    <div class="timeline-title">The Beginning</div>
                    <p class="timeline-desc">
                        The journey began with a vision to build a trusted and patient-focused healthcare and diagnostics platform.
                    </p>
                </div>

                <div class="timeline-block">
                    <span class="timeline-year-badge">2024</span>
                    <div class="timeline-title">Wellcare Labs</div>
                    <p class="timeline-desc">
                        Wellcare Labs was established with a focus on accessible, reliable, and technology-driven diagnostic services.
                    </p>
                </div>

                <div class="timeline-block">
                    <span class="timeline-year-badge">2025</span>
                    <div class="timeline-title">Recognition & Growth</div>
                    <p class="timeline-desc">
                        Expanded diagnostic services and strengthened the brand’s presence, receiving the Maharashtra Business Icon Award 2025.
                    </p>
                </div>

                <div class="timeline-block">
                    <span class="timeline-year-badge">2026</span>
                    <div class="timeline-title">Expanding the Vision</div>
                    <p class="timeline-desc">
                        Continued expansion of diagnostic services, health packages, home sample collection, digital reporting, and technology-driven healthcare solutions.
                    </p>
                </div>

                <div class="timeline-block">
                    <span class="timeline-year-badge">Today</span>
                    <div class="timeline-title">Building for Tomorrow</div>
                    <p class="timeline-desc">
                        Wellcare Labs continues its journey toward becoming a trusted diagnostic healthcare brand, with a vision to make quality diagnostics accessible across India.
                    </p>
                </div>

            </div>

            <div class="milestones-footer">
                <div class="footer-tagline">
                    Redefining Quality Diagnostics for Better India.
                </div>
            </div>
        </div>

    </div>
@endsection