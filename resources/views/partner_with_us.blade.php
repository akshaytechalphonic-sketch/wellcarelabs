@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'B2B Diagnostics & Franchise Partnership – Wellcare Labs | Pune')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Partner with Wellcare Labs for corporate wellness deals, collection center franchises, and doctor testing referrals. Elevate diagnostic care in Pune PCMC.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif
@endsection

@push('head')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Hero Section */
        .partner-hero-v2 {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            color: #ffffff;
            padding: 85px 20px 75px;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(30, 27, 75, 0.25);
        }

        .partner-hero-v2::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            right: -20%;
            bottom: -50%;
            background: radial-gradient(circle at 70% 30%, rgba(99, 102, 241, 0.2) 0%, transparent 60%);
            pointer-events: none;
        }

        .partner-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(165, 180, 252, 0.4);
            color: #c7d2fe;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 18px;
            border-radius: 50px;
            margin-bottom: 20px;
            backdrop-filter: blur(8px);
        }

        .partner-hero-v2 h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
            background: linear-gradient(180deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .partner-hero-v2 p {
            font-size: 1.2rem;
            max-width: 780px;
            margin: 0 auto 25px;
            color: #c7d2fe;
            line-height: 1.7;
        }

        .partner-accent-bar {
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #6366f1 0%, #818cf8 50%, #4f46e5 100%);
            margin: 0 auto;
            border-radius: 2px;
            box-shadow: 0 0 12px rgba(99, 102, 241, 0.6);
        }

        /* Benefits Grid */
        .model-card-v2 {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            padding: 40px 32px;
            height: 100%;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .model-card-v2:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.18);
            border-color: #6366f1;
        }

        .model-card-v2::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .model-card-v2:hover::before {
            opacity: 1;
        }

        .model-icon-v2 {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 25px;
        }

        .model-card-v2 h3 {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 14px;
        }

        .model-card-v2 p {
            font-size: 0.98rem;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 22px;
        }

        .model-benefits-list-v2 {
            list-style: none;
            padding: 0;
            margin: 0 0 25px 0;
            margin-top: auto;
        }

        .model-benefits-list-v2 li {
            font-size: 0.94rem;
            color: #334155;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .model-benefits-list-v2 li i {
            color: #10b981;
            font-size: 1rem;
        }

        /* B2B Metrics Strip */
        .metrics-container-v2 {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 30px;
            padding: 45px 25px;
            margin-bottom: 60px;
            border: 1px solid #e2e8f0;
        }

        .why-us-metric-v2 {
            text-align: center;
            padding: 15px;
        }

        .why-us-metric-num-v2 {
            font-size: 2.8rem;
            font-weight: 900;
            color: #4f46e5;
            line-height: 1;
            margin-bottom: 6px;
            letter-spacing: -1px;
        }

        .why-us-metric-label-v2 {
            font-size: 0.98rem;
            color: #475569;
            font-weight: 700;
        }

        /* Workflow Steps */
        .step-card-v2 {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 30px 24px;
            text-align: center;
            position: relative;
            height: 100%;
        }

        .step-number-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
        }

        .step-card-v2 h4 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .step-card-v2 p {
            font-size: 0.92rem;
            color: #64748b;
            margin-bottom: 0;
            line-height: 1.5;
        }

        /* Lead Generation Form */
        .partner-form-card-v2 {
            background: #ffffff;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            padding: 50px 45px;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05);
        }

        .partner-form-card-v2 h2 {
            font-size: 2.1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
            text-align: center;
        }

        .partner-form-card-v2 p.sub {
            color: #64748b;
            font-size: 1.02rem;
            margin-bottom: 35px;
            text-align: center;
        }

        .form-grid-2-v2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }

        .form-group-full-v2 {
            grid-column: span 2;
        }

        .partner-label-v2 {
            font-size: 0.85rem;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        .partner-control-v2 {
            border-radius: 12px;
            border: 1.5px solid #cbd5e1;
            padding: 13px 18px;
            font-size: 0.98rem;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .partner-control-v2:focus {
            border-color: #4f46e5;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            outline: none;
        }

        .btn-partner-submit-v2 {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.08rem;
            border: 0;
            padding: 16px 32px;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.25s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
        }

        .btn-partner-submit-v2:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
        }

        @media(max-width: 768px) {
            .partner-hero-v2 h1 {
                font-size: 2.2rem;
            }

            .form-grid-2-v2 {
                grid-template-columns: 1fr;
            }

            .form-group-full-v2 {
                grid-column: span 1;
            }

            .partner-form-card-v2 {
                padding: 30px 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-package">

        {{-- Hero --}}
        <div class="partner-hero-v2">
            <div class="partner-hero-badge">
                <i class="fa-solid fa-handshake"></i> Wellcare Network
            </div>
            <h1> Partnerships & Franchise</h1>
            <p>Expand your diagnostics capabilities or build your collection center with India's high precision pathology
                brand. Join Pune & PCMC's most trusted health diagnostics network.</p>
            <div class="partner-accent-bar"></div>
        </div>

        {{-- Partnership Models --}}
        <div class="page-section pb-5">
            <div class="container">
                <div class="row g-4">
                    {{-- Franchise --}}
                    <div class="col-lg-4">
                        <div class="model-card-v2">
                            <div class="model-icon-v2"><i class="fa-solid fa-store"></i></div>
                            <h3>Collection Center Franchise</h3>
                            <p>Launch a local diagnostics collection branch under the Wellcare brand. Benefit from robust
                                logistic networks, premium branding materials, and marketing support.</p>
                            <ul class="model-benefits-list-v2">
                                <li><i class="fa-solid fa-circle-check"></i> Competitive Profit Margins</li>
                                <li><i class="fa-solid fa-circle-check"></i> Standard Logistics Pickups</li>
                                <li><i class="fa-solid fa-circle-check"></i> Complete IT & System setup</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Corporate --}}
                    <div class="col-lg-4">
                        <div class="model-card-v2">
                            <div class="model-icon-v2"><i class="fa-solid fa-building"></i></div>
                            <h3>Corporate Wellness Deals</h3>
                            <p>Provide customized pre-employment checks, executive health plans, and regular annual
                                assessments to safeguard your workforce and lower medical overheads.</p>
                            <ul class="model-benefits-list-v2">
                                <li><i class="fa-solid fa-circle-check"></i> Customized Testing Panels</li>
                                <li><i class="fa-solid fa-circle-check"></i> On-site Corporate camps</li>
                                <li><i class="fa-solid fa-circle-check"></i> Dedicated Corporate manager</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Referral --}}
                    <div class="col-lg-4">
                        <div class="model-card-v2">
                            <div class="model-icon-v2"><i class="fa-solid fa-user-doctor"></i></div>
                            <h3>Doctor & Clinic Referrals</h3>
                            <p>Collaborate for diagnostic precision requirements. Offer your clinic patients highly accurate
                                blood testing with custom referral arrangements.</p>
                            <ul class="model-benefits-list-v2">
                                <li><i class="fa-solid fa-circle-check"></i> High precision processing</li>
                                <li><i class="fa-solid fa-circle-check"></i> WhatsApp API report deliveries</li>
                                <li><i class="fa-solid fa-circle-check"></i> Priority sample processing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Why Us Metrics --}}
        <div class="container">
            <div class="metrics-container-v2">
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="why-us-metric-v2">
                            <div class="why-us-metric-num-v2">3000+</div>
                            <div class="why-us-metric-label-v2">Testing Panel catalog</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="why-us-metric-v2">
                            <div class="why-us-metric-num-v2">24/7</div>
                            <div class="why-us-metric-label-v2">Operational Lab Support</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="why-us-metric-v2">
                            <div class="why-us-metric-num-v2">15K+</div>
                            <div class="why-us-metric-label-v2">Satisfied patients</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="why-us-metric-v2">
                            <div class="why-us-metric-num-v2">100%</div>
                            <div class="why-us-metric-label-v2">Standard Guidelines</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Partnership Workflow --}}
        <div class="page-section pb-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold fs-2 text-dark">4-Step Partnership Journey</h2>
                    <p class="text-muted fs-5">Fast-track onboarding designed to get your diagnostic center operational</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="step-card-v2">
                            <div class="step-number-badge">1</div>
                            <h4>Submit Inquiry</h4>
                            <p>Fill out the B2B proposal form below with your requirements.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="step-card-v2">
                            <div class="step-number-badge">2</div>
                            <h4>Review & Discuss</h4>
                            <p>Our business manager contacts you to finalize scope & terms.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="step-card-v2">
                            <div class="step-number-badge">3</div>
                            <h4>Setup & Logistics</h4>
                            <p>We deploy IT portals, collection kits, and sample pickup routes.</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="step-card-v2">
                            <div class="step-number-badge">4</div>
                            <h4>Go-Live</h4>
                            <p>Start processing samples with 12-hour report turnarounds.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submission Form --}}
        <div class="page-section pb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="partner-form-card-v2">
                            <h2>Partner Application / Inquiry</h2>
                            <p class="sub">Please fill out this form to connect with our B2B accounts coordinator.</p>

                            {{-- Fallback: Laravel validation errors / success (non-AJAX) --}}
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success mb-4" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form id="partnerForm" action="{{ route('inquiry.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                {{-- Honeypot Trap --}}
                                <div style="position:absolute; left:-5000px; width:1px; height:1px; overflow:hidden;">
                                    <label for="hp_verification_check">Verification Check</label>
                                    <input type="text" id="hp_verification_check" name="hp_verification_check"
                                        tabindex="-1">
                                </div>

                                {{-- Hidden compiled message --}}
                                <input type="hidden" name="message" id="real_message">

                                <div class="form-grid-2-v2">
                                    {{-- Name --}}
                                    <div class="form-group">
                                        <label for="fullName" class="partner-label-v2">Contact Person <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="fullName" name="name" class="form-control partner-control-v2"
                                            required placeholder="Your full name..">
                                    </div>

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <label for="emailAddress" class="partner-label-v2">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="emailAddress" name="email"
                                            class="form-control partner-control-v2" required
                                            placeholder="Your business email..">
                                    </div>

                                    {{-- Phone --}}
                                    <div class="form-group">
                                        <label for="phone" class="partner-label-v2">Mobile Number <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" id="phone" name="phone" class="form-control partner-control-v2"
                                            required placeholder="10-digit mobile number..">
                                    </div>



                                    {{-- Proposal File Upload --}}
                                    <div class="form-group">
                                        <label for="prescription" class="partner-label-v2">Upload Proposal/Profile
                                            (Optional)</label>
                                        <input type="file" name="prescription" id="prescription"
                                            class="form-control partner-control-v2" accept="image/*,application/pdf">
                                        <small class="text-muted mt-1 d-block">Supported formats: PDF, JPG, PNG — Max size
                                            5MB.</small>
                                    </div>

                                    {{-- Message Notes --}}
                                    <div class="form-group-full-v2">
                                        <label for="partner_note" class="partner-label-v2">Partnership Proposal Details
                                            <span class="text-danger">*</span></label>
                                        <textarea id="partner_note" class="form-control partner-control-v2" rows="5"
                                            required
                                            placeholder="Please outline your business proposal or partnership interest details.."></textarea>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="form-group-full-v2 mt-3">
                                        <button type="submit" class="btn-partner-submit-v2">
                                            <i class="fa-solid fa-paper-plane"></i>
                                            <span>Submit Inquiry</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('partnerForm');
            if (!form) return;

            form.addEventListener('submit', function (e) {
                const type = document.getElementById('partnership_type').value;
                const note = document.getElementById('partner_note').value;
                const company = document.getElementById('company_name').value || 'N/A';

                document.getElementById('real_message').value = `Partnership Type: ${type}\nCompany/Clinic Name: ${company}\n\nProposal/Interest:\n${note}`;
            });
        });
    </script>
@endsection