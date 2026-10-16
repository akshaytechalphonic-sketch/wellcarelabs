@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Full Body Checkup Packages in Pune ₹799+ | Home Collection')

@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Book a complete health checkup in Pune from ₹799. Home blood sample collection, 76 to 88 parameters, digital reports. Compare 8 packages and book online today.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif

    {{-- SEO Structured Data: FAQPage & MedicalBusiness Schema --}}
    @php
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'What is a full body checkup?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'A full body checkup is a set of blood and urine tests that show how your key organs work. It checks blood sugar, cholesterol, liver and kidney function, thyroid function, and vitamins, so we can catch problems early.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What tests are included in a full body checkup package?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Most packages include CBC, HbA1c or fasting glucose, lipid profile, liver and kidney tests, thyroid profile, and urine tests. Many also add vitamin D, vitamin B12, and iron studies.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How much does a full body checkup cost in Pune?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'At Wellcare Labs, packages run from ₹799 to ₹2,599. The price depends on how many tests you want. Check the package card for the latest price before you book.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Which full body checkup package is best for me?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Healthy adults under 30 can start with the Smart plan. Adults over 30 can choose Complete or Master plans. Men and women can pick plans with PSA or hormone tests. Call us if you are unsure.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Do I need to fast before a full body checkup?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Fast for 8 to 12 hours, or 10 to 12 hours for the Master Male plan. Drink plain water only. Skip tea, coffee, juice, alcohol, and smoking until your sample is taken.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How often should I get a full body checkup?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Most healthy adults do well with one checkup a year, especially after 30. If you have diabetes, high blood pressure, a thyroid problem, or a family history of heart disease, your doctor may advise more often.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Is home sample collection available in Pune?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. We collect samples at home across Pune and PCMC, including Pimple Saudagar, Wakad, Baner, Aundh, Hinjawadi, and Kharadi. Share your pin code when you book, and we will confirm your area.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How do I book a full body checkup at Wellcare Labs?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Pick a package on this page and tap Add to Cart. You can also call +91 915 898 0898 or message us on WhatsApp. Choose a morning slot, and our phlebotomist will visit at that time.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'How soon will I get my reports?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Many reports are ready within about 15 hours of sample collection. Some tests take longer. You get your reports digitally, so you can open them on your phone and share them with your doctor.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Can a full body checkup detect every disease?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'No. It screens for common problems like diabetes, thyroid issues, anaemia, and high cholesterol. It does not replace scans, a physical exam, or a doctor’s advice. Your results may lead to extra tests.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Do I need a doctor’s prescription?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'No prescription is needed for a preventive checkup. If you have symptoms or a health condition, talk to your doctor first. They can tell you which tests matter most for you.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Can women take the test during periods?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Blood tests are usually fine. Urine tests may need another day. Hormone tests such as FSH, LH, and oestradiol are often timed to certain days of your cycle, so ask your doctor or call us before booking.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Can I take my regular medicines before the test?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Usually yes, unless your doctor says otherwise. Tell the phlebotomist about all the medicines. Many doctors ask you to take thyroid tablets after the sample. If you take diabetes medicines, ask your doctor how to time them.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Can I add extra tests to my package?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Yes. Visit our Customise Packages page or call us to add the tests you need, such as a specific hormone or vitamin test. Our team can help you build a plan that fits your budget.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'What should I do if my report shows abnormal values?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Stay calm. One abnormal value does not always mean disease. Food, stress, water intake, and testing methods can all shift a number. Share your report with your doctor, who will read it along with your health history.'
                    ]
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Is Wellcare Labs reliable and certified?',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'Wellcare Labs holds ISO 9001:2015 certification for its quality management system and has over 8 years of experience in diagnostics. Our team includes pathologists and trained phlebotomists, and our centre is at Sai Vision Mall, Pimple Saudagar.'
                    ]
                ]
            ]
        ];

        $bizSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalBusiness',
            'name' => 'Wellcare Labs - Full Body Checkup Packages Pune',
            'image' => asset('Front_end/assets/img/favicon.ico'),
            'telephone' => '+919158980898',
            'priceRange' => '₹799 - ₹2599',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Shop 113, First Floor, A-Wing, Sai Vision Mall, Pimple Saudagar',
                'addressLocality' => 'Pune',
                'addressRegion' => 'Maharashtra',
                'postalCode' => '411027',
                'addressCountry' => 'IN'
            ],
            'url' => 'https://wellcarelabs.in/packages'
        ];
    @endphp
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($bizSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection
@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endpush


@section('content')

    <section id="all-packages" class="py-5" style="
            max-width:100%;
            margin:auto;
            background:#f0f4f8;
            box-shadow:
              inset 0 10px 25px -10px rgba(0,0,0,0.06),
              inset 0 -10px 25px -10px rgba(0,0,0,0.06);
            border-bottom:1px solid rgba(0,0,0,0.05);
          ">





        <div class="text-center mb-5 px-3 px-md-5" style="max-width:800px;margin:0 auto;">
            <h1 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
                Wellcare Smart Health <span style="color:#0d6efd;"> Checkup Packages</span>

            </h1>
            <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                        background:linear-gradient(90deg,#0047ff,#00ccff);">
            </div>
            <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
                Backed by Doctors. Believed by Patients. </p>
        </div>


        @if ($packages->count())
            <div class="container-fluid px-3 px-md-5">
                {{-- Always center rows so when a new row starts, it starts from the center --}}
                <div class="row g-4 justify-content-center tablet-grid">
                    @foreach ($packages->where('is_special', 0) as $pkg)
                        @php
                            $title = $pkg->title ?? 'Package';

                            // prices
                            $mrp = (float) ($pkg->mrp ?? 0);
                            $discounted = (float) ($pkg->discounted_price ?? 0);
                            $price = (float) ($pkg->price ?? 0);
                            $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);
                            $savePercent = $mrp > 0 && $displayPrice ? round((($mrp - $displayPrice) / $mrp) * 100) : 0;
                            $savingAmount =
                                $mrp > 0 && $displayPrice && $mrp > $displayPrice ? $mrp - $displayPrice : 0;

                            // image
                            $banner = $pkg->banner ? ltrim($pkg->banner, '/') : null;
                            $bannerPath = $banner ? storage_path('app/public/' . $banner) : null;
                            $hasBanner = $bannerPath && file_exists($bannerPath);
                            $defaultImage = asset('Front_end/assets/img/blog/default-package.jpg');
                            $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 360'>
                                                    <rect width='100%' height='100%' fill='#f6fbfb' /><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#9aa0a6' font-size='20'>No image available</text>
                                                  </svg>";
                            $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode($svg);

                            // chips (tests) — same logic as detail page
                            $tests = [];
                            if (isset($pkg->tests) && is_iterable($pkg->tests) && $pkg->tests->count()) {
                                foreach ($pkg->tests as $t) {
                                    $tTitle = trim($t->test_name ?? $t->title ?? '');
                                    $tCount = (int) ($t->parameters_count ?? 0);
                                    if (preg_match('/^(.*?)\s*\((\d+)\)$/', $tTitle, $m)) {
                                        $tTitle = trim($m[1]);
                                        $tCount = $tCount > 0 ? $tCount : (int) $m[2];
                                    }
                                    $tests[] = $tCount > 0 ? "{$tTitle}({$tCount})" : $tTitle;
                                }
                            } elseif (!empty($pkg->tests_list) && is_string($pkg->tests_list)) {
                                $rawParts = array_map('trim', explode(',', strip_tags($pkg->tests_list)));
                                foreach ($rawParts as $p) {
                                    if (preg_match('/^(.*?)\s*\((\d+)\)$/', $p, $m)) {
                                        $cleanTitle = trim($m[1]);
                                        $cnt = (int) $m[2];
                                        $tests[] = "{$cleanTitle}({$cnt})";
                                    } else {
                                        $tests[] = $p;
                                    }
                                }
                            } else {
                                $parts = preg_split('/\r\n|\n|,/', $pkg->content ?? '');
                                $parts = array_map(fn($v) => trim(rtrim($v, ", \t\n\r\0\x0B")), $parts);
                                $parts = array_filter($parts, fn($v) => $v !== '');
                                foreach ($parts as $p) {
                                    if (preg_match('/^(.*?)\s*\((\d+)\)$/', $p, $m)) {
                                        $cleanTitle = trim($m[1]);
                                        $cnt = (int) $m[2];
                                        $tests[] = "{$cleanTitle}({$cnt})";
                                    } else {
                                        $tests[] = $p;
                                    }
                                }
                            }
                            $seen = [];
                            $tests = array_values(
                                array_filter(
                                    array_map(function ($v) use (&$seen) {
                                        $k = mb_strtolower($v);
                                        if ($k === '' || isset($seen[$k])) {
                                            return null;
                                        }
                                        $seen[$k] = true;
                                        return $v;
                                    }, $tests),
                                ),
                            );
                            $maxShow = 4;
                            $totalBadges = count($tests);
                            $showBadges = array_slice($tests, 0, $maxShow);

                            $packageParam = $pkg->slug ?? $pkg->id;
                        @endphp

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                            <div class="pretty-card h-100 d-flex flex-column position-relative w-100">
                                <a class="img-fixed rounded-top-4 overflow-hidden bg-light d-block"
                                    href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                    aria-label="Open {{ $title }}">
                                    @if ($hasBanner)
                                        <img src="{{ asset('storage/' . $banner) }}" class="pkg-img" alt="{{ $title }}" loading="lazy"
                                            style="object-fit:cover;">
                                    @elseif(file_exists(public_path('Front_end/assets/img/blog/default-package.jpg')))
                                        <img src="{{ $defaultImage }}" class="pkg-img" alt="{{ $title }}" loading="lazy"
                                            style="object-fit:cover;">
                                    @else
                                        <img src="{{ $placeholder }}" class="pkg-img" alt="No image" loading="lazy"
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

                                    {{-- Chips / badges (always render the container to preserve height) --}}
                                    <div class="chips mb-2">
                                        @foreach ($showBadges as $b)
                                            <a href="{{ route('packages.show', ['package' => $packageParam]) }}" class="chip-pill"
                                                title="{{ strip_tags($b) }}">
                                                <span class="text-truncate">{{ \Illuminate\Support\Str::limit($b, 32) }}</span>
                                            </a>
                                        @endforeach
                                        @if ($totalBadges > $maxShow)
                                            <a href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                                class="chip-pill chip-more">+{{ $totalBadges - $maxShow }} more</a>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1"></div>

                                    <div class="purchase-meta mb-2">
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            @if ($displayPrice)
                                                <div class="price-final">₹{{ number_format($displayPrice, 0) }}</div>
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
                                        <a href="{{ route('packages.show', ['package' => $packageParam]) }}"
                                            class="btn btn-view-info btn-sm flex-fill flex-sm-grow-0"
                                            onclick="event.stopPropagation();">
                                            <i class="fa fa-info-circle me-1" aria-hidden="true"></i> View Info
                                        </a>

                                        <button type="button" id="cart-btn-{{ $pkg->id }}" data-pkg-id="{{ $pkg->id }}"
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

                @if (method_exists($packages, 'links') && $packages->hasPages())
                    <div class="pagination-wrapper d-flex justify-content-center mt-4 mb-4">
                        {{ $packages->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @else
            <div class="alert alert-info text-center">No packages available right now.</div>
        @endif
    </section>

    {{-- ================= FULL BODY CHECKUP INFORMATION & FAQS SECTION ================= --}}
    <section id="full-body-checkup-guide" class="fbc-guide-section py-5">
        <div class="container">

            {{-- 1. HERO / OVERVIEW CARD --}}
            <div class="fbc-hero-card mb-5">
                <div class="fbc-hero-badge">
                    <i class="fa-solid fa-shield-heart me-1"></i> Preventive Health Screening in Pune & PCMC
                </div>
                <h2 class="fbc-hero-title">
                    Full Body Checkup in Pune From ₹799: <span class="fbc-gradient-text">Spot Silent Health Risks Before Symptoms Do</span>
                </h2>
                <div class="fbc-title-divider"></div>
                <div class="fbc-hero-text">
                    <p>
                        Feeling fine does not always mean you are fine. High sugar, a weak thyroid, and low vitamin levels can stay quiet for years. A full-body checkup finds them early, when they are easy to manage.
                    </p>
                    <p>
                        At Wellcare Labs, you can book a full-body checkup in Pune from ₹799. Pick a package, choose a morning slot, and our trained phlebotomist collects your blood and urine samples at home. Your reports reach you digitally.
                    </p>
                </div>

                {{-- Action Strip --}}
                <div class="fbc-cta-strip">
                    <a href="#all-packages" class="fbc-btn fbc-btn-primary">
                        <i class="fa-solid fa-arrow-up me-2"></i> Browse & Book Checkup Packages
                    </a>
                    <a href="{{ route('customize.index') }}" class="fbc-btn fbc-btn-outline">
                        <i class="fa-solid fa-sliders me-2"></i> Customise Package Online
                    </a>
                </div>
            </div>

            {{-- 2. PACKAGES AT A GLANCE TABLE --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Price & Comparison</span>
                    <h3 class="fbc-block-title">Full Body Checkup Packages in Pune at a Glance</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Choose the right preventive diagnostic panel for you and your family.</p>
                </div>

                <div class="fbc-table-responsive shadow-sm">
                    <table class="table fbc-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 240px;">Package</th>
                                <th scope="col" class="text-center" style="min-width: 110px;">Parameters</th>
                                <th scope="col" class="text-center" style="min-width: 120px;">Price</th>
                                <th scope="col" style="min-width: 250px;">Best for</th>
                                <th scope="col" class="text-center" style="min-width: 130px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Smart Full Body Checkup Plan</div>
                                    <span class="badge bg-light text-primary border">Popular Choice</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">76</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹799</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">A simple yearly screening</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Complete Health Checkup</div>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">79</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹999</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">Adults who want HbA1c and iron added</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Complete Health Checkup with Vitamin Screening</div>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">82</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹1,499</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">People who feel tired often</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Female Special Health Checkup</div>
                                    <span class="badge bg-pink-subtle text-danger border border-danger-subtle">Women's Health</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">86</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹1,699</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">Women who want hormone tests</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Male Special Health Checkup</div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Men's Health</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">87</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹1,899</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">Men who want a PSA test</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Master Full Body Checkup</div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Widest Coverage</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">88</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹2,499</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">The widest panel in one visit</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Master Female Full Body Checkup</div>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">86</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹2,599</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">Women who want calcium and folic acid too</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold fbc-pkg-name">Master Male Full Body Checkup</div>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-param-badge">86</span>
                                </td>
                                <td class="text-center">
                                    <span class="fbc-price-tag">₹2,599</span>
                                </td>
                                <td>
                                    <span class="fbc-best-for">Men who want pancreas tests too</span>
                                </td>
                                <td class="text-center">
                                    <a href="#all-packages" class="btn btn-sm fbc-btn-table">Book Plan</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Quick Facts Cards --}}
                <div class="fbc-quick-facts-box mt-4 p-4 rounded-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>Quick facts for every package:</h5>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="fbc-fact-card">
                                <div class="fbc-fact-icon bg-blue"><i class="fa-solid fa-vial"></i></div>
                                <div>
                                    <div class="fbc-fact-label">Samples</div>
                                    <div class="fbc-fact-value">Blood and urine</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="fbc-fact-card">
                                <div class="fbc-fact-icon bg-amber"><i class="fa-regular fa-clock"></i></div>
                                <div>
                                    <div class="fbc-fact-label">Fasting</div>
                                    <div class="fbc-fact-value">8 to 12 hours <span class="small text-muted d-block">(10 to 12 hrs for Master Male)</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="fbc-fact-card">
                                <div class="fbc-fact-icon bg-green"><i class="fa-solid fa-house-medical"></i></div>
                                <div>
                                    <div class="fbc-fact-label">Home collection</div>
                                    <div class="fbc-fact-value">Pune & PCMC</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="fbc-fact-card">
                                <div class="fbc-fact-icon bg-purple"><i class="fa-solid fa-file-waveform"></i></div>
                                <div>
                                    <div class="fbc-fact-label">Reports</div>
                                    <div class="fbc-fact-value">Ready within ~15 hours & shared digitally</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. WHAT IS A FULL BODY CHECKUP? --}}
            <div class="fbc-block mb-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="fbc-pill-tag">Overview</span>
                        <h3 class="fbc-block-title">What Is a Full Body Checkup?</h3>
                        <div class="fbc-title-divider"></div>
                        <p class="fbc-lead-text">
                            A full body checkup is a group of blood and urine tests that show how your main organs work. It covers your blood, sugar, cholesterol, liver, kidneys, thyroid, and key vitamins.
                        </p>
                        <div class="fbc-analogy-box p-3 p-md-4 rounded-3 border-start border-4 border-primary bg-light">
                            <div class="d-flex align-items-start">
                                <i class="fa-solid fa-car-burst text-primary fs-3 me-3 mt-1"></i>
                                <div>
                                    <strong class="d-block text-dark mb-1">Preventive Maintenance Analogy:</strong>
                                    <span class="text-secondary">
                                        Think of it like servicing your car before a long trip. You do it to avoid a breakdown, not because the engine already failed.
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="fbc-feature-callout p-4 rounded-4 shadow-sm bg-white border">
                            <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-heart-pulse text-danger me-2"></i>Comprehensive Screening Highlights</h5>
                            <ul class="list-unstyled mb-0 fbc-checklist">
                                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Complete Blood Count & Anaemia Check</li>
                                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Blood Sugar & 3-Month HbA1c</li>
                                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Cardiac Lipid & Cholesterol Profile</li>
                                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Liver & Kidney Metabolic Clearing</li>
                                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Thyroid Hormones (T3, T4, TSH)</li>
                                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Vital Vitamins (D & B12) & Iron Levels</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. WHICH TESTS ARE INSIDE OUR HEALTH CHECKUP PACKAGES? --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Diagnostic Breakdown</span>
                    <h3 class="fbc-block-title">Which Tests Are Inside Our Health Checkup Packages?</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Understand what each critical panel assesses and why it protects your daily health.</p>
                </div>

                <div class="fbc-table-responsive shadow-sm mb-4">
                    <table class="table fbc-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 220px;">Test</th>
                                <th scope="col" style="min-width: 280px;">What it checks</th>
                                <th scope="col" style="min-width: 280px;">Why it matters</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-primary">CBC (28 parameters)</td>
                                <td>Red cells, white cells, platelets</td>
                                <td>Shows signs of anaemia and infection</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">HbA1c</td>
                                <td>Average blood sugar over 3 months</td>
                                <td>Spots prediabetes and diabetes</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Lipid profile</td>
                                <td>Cholesterol and triglycerides</td>
                                <td>Shows your heart's risk</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Liver function test (LFT)</td>
                                <td>Liver enzymes and proteins</td>
                                <td>Flags liver stress early</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Kidney profile</td>
                                <td>How well kidneys clear waste</td>
                                <td>Catches kidney strain</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Thyroid profile (T3, T4, TSH)</td>
                                <td>Thyroid hormones</td>
                                <td>Explains tiredness and weight change</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Vitamin D and B12</td>
                                <td>Key nutrient levels</td>
                                <td>Low levels are common in India</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Iron studies</td>
                                <td>Iron, TIBC, transferrin saturation</td>
                                <td>Finds iron shortage</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary">Urine complete</td>
                                <td>Urine chemistry and cells</td>
                                <td>Shows infection or sugar traces</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Some Plans Add More --}}
                <div class="fbc-more-tests-card p-4 rounded-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-plus-circle text-primary me-2"></i>Some plans add more targeted biomarkers:</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="fbc-addon-card h-100 p-3 rounded-3 bg-white border">
                                <div class="fbc-addon-header text-primary fw-bold mb-2">
                                    <i class="fa-solid fa-mars me-1"></i> For Men
                                </div>
                                <p class="mb-0 text-secondary">
                                    <strong>PSA (prostate check)</strong>. Master Male also adds <strong>amylase and lipase</strong> for pancreatic health.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fbc-addon-card h-100 p-3 rounded-3 bg-white border">
                                <div class="fbc-addon-header text-danger fw-bold mb-2">
                                    <i class="fa-solid fa-venus me-1"></i> For Women
                                </div>
                                <p class="mb-0 text-secondary">
                                    <strong>Oestradiol, FSH, LH, and prolactin</strong>. Master Female also adds <strong>calcium and folic acid</strong>.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fbc-addon-card h-100 p-3 rounded-3 bg-white border">
                                <div class="fbc-addon-header text-success fw-bold mb-2">
                                    <i class="fa-solid fa-microscope me-1"></i> Master Full Body
                                </div>
                                <p class="mb-0 text-secondary">
                                    Adds <strong>hs-CRP (cardiac inflammation), ESR, rheumatoid factor, total IgE</strong>, and <strong>electrolytes</strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. WHY GET A FULL BODY HEALTH CHECKUP? --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Health Value</span>
                    <h3 class="fbc-block-title">Why Get a Full Body Health Checkup?</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Most common lifestyle problems start without a single symptom. A checkup gives you real numbers instead of guesses.</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="fbc-benefit-card p-4 rounded-4 h-100 shadow-sm bg-white border">
                            <div class="fbc-benefit-icon text-primary bg-primary-subtle mb-3">
                                <i class="fa-solid fa-radar"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Early detection</h5>
                            <p class="text-secondary mb-0">
                                Diabetes, thyroid trouble, anaemia, and high cholesterol often show no warning signs at first.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="fbc-benefit-card p-4 rounded-4 h-100 shadow-sm bg-white border">
                            <div class="fbc-benefit-icon text-success bg-success-subtle mb-3">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h5 class="fw-bold text-dark">A clear baseline</h5>
                            <p class="text-secondary mb-0">
                                Your numbers this year become the yardstick for next year, helping track subtle organ changes over time.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="fbc-benefit-card p-4 rounded-4 h-100 shadow-sm bg-white border">
                            <div class="fbc-benefit-icon text-warning bg-warning-subtle mb-3">
                                <i class="fa-solid fa-piggy-bank"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Smaller bills later</h5>
                            <p class="text-secondary mb-0">
                                Early treatment usually costs far less than managing a late, complicated health emergency.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="fbc-benefit-card p-4 rounded-4 h-100 shadow-sm bg-white border">
                            <div class="fbc-benefit-icon text-info bg-info-subtle mb-3">
                                <i class="fa-solid fa-apple-whole"></i>
                            </div>
                            <h5 class="fw-bold text-dark">Better daily choices</h5>
                            <p class="text-secondary mb-0">
                                You can adjust food, supplements, and exercise using facts rather than guesswork or generic advice.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Real Life Example --}}
                <div class="fbc-example-card p-4 rounded-4 border-start border-4 border-success bg-white shadow-sm">
                    <div class="d-flex align-items-start">
                        <div class="fbc-example-badge me-3 mt-1">
                            <i class="fa-solid fa-lightbulb text-warning fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-2">Real-Life Example: Why Early Screening Matters</h5>
                            <p class="mb-0 text-secondary" style="line-height:1.75;">
                                Say you are 36, you sit ten hours a day, and you feel drained by 4 pm. You blame work. A checkup shows low vitamin D and a high HbA1c. A doctor visit and a few diet changes now can matter far more than hearing the same news at 46.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. WHO SHOULD BOOK A FULL BODY CHECKUP? --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Eligibility & Risk Factors</span>
                    <h3 class="fbc-block-title">Who Should Book a Full Body Checkup?</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">You do not need to feel sick. Consider booking if you fit any of these profiles:</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="fbc-who-card p-3 rounded-3 bg-white border d-flex align-items-center">
                            <div class="fbc-who-icon me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                            <div class="text-dark fw-medium">Are 30 or older and have not been tested in the last year</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fbc-who-card p-3 rounded-3 bg-white border d-flex align-items-center">
                            <div class="fbc-who-icon me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                            <div class="text-dark fw-medium">Have a family history of diabetes, heart disease, or thyroid problems</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fbc-who-card p-3 rounded-3 bg-white border d-flex align-items-center">
                            <div class="fbc-who-icon me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                            <div class="text-dark fw-medium">Work a desk job or sit for long hours</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fbc-who-card p-3 rounded-3 bg-white border d-flex align-items-center">
                            <div class="fbc-who-icon me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                            <div class="text-dark fw-medium">Feel tired, gain or lose weight without reason, or get frequent headaches</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fbc-who-card p-3 rounded-3 bg-white border d-flex align-items-center">
                            <div class="fbc-who-icon me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                            <div class="text-dark fw-medium">Smoke or drink alcohol</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fbc-who-card p-3 rounded-3 bg-white border d-flex align-items-center">
                            <div class="fbc-who-icon me-3 text-primary"><i class="fa-solid fa-circle-check fs-4"></i></div>
                            <div class="text-dark fw-medium">Want to check up on your parents but cannot take them to a lab</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. WHICH FULL BODY CHECKUP PACKAGE SHOULD YOU CHOOSE? --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Selection Guide</span>
                    <h3 class="fbc-block-title">Which Full Body Checkup Package Should You Choose?</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Simple, direct guidance based on age, gender, and personal diagnostic needs.</p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-guide-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-guide-badge bg-primary text-white mb-3">Under 30 & Healthy</div>
                            <h5 class="fw-bold text-dark">Smart Plan (₹799)</h5>
                            <p class="text-secondary mb-3">
                                Start with the Smart plan at ₹799. It covers blood count, fasting sugar, lipids, liver, kidneys, thyroid, and urine.
                            </p>
                            <a href="#all-packages" class="text-primary fw-bold small text-decoration-none">View Smart Plan <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-guide-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-guide-badge bg-success text-white mb-3">30 or Older (First Full Checkup)</div>
                            <h5 class="fw-bold text-dark">Complete Health Checkup (₹999 / ₹1,499)</h5>
                            <p class="text-secondary mb-3">
                                Choose the complete plan at ₹999. It adds HbA1c and iron studies. Pick the vitamin screening version at ₹1,499 if you also want vitamin D and B12.
                            </p>
                            <a href="#all-packages" class="text-primary fw-bold small text-decoration-none">View Complete Plans <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-guide-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-guide-badge bg-danger text-white mb-3">Women</div>
                            <h5 class="fw-bold text-dark">Female Special Plan (₹1,699)</h5>
                            <p class="text-secondary mb-3">
                                Adds oestradiol, FSH, LH, and prolactin. These help when periods are irregular or you notice hormonal changes in your 40s.
                            </p>
                            <a href="#all-packages" class="text-primary fw-bold small text-decoration-none">View Female Special <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-guide-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-guide-badge bg-info text-white mb-3">Men</div>
                            <h5 class="fw-bold text-dark">Male Special Plan (₹1,899)</h5>
                            <p class="text-secondary mb-3">
                                Adds a PSA test for prostate health. It also includes ESR, rheumatoid factor, and essential electrolytes.
                            </p>
                            <a href="#all-packages" class="text-primary fw-bold small text-decoration-none">View Male Special <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-guide-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-guide-badge bg-dark text-white mb-3">Widest Panel</div>
                            <h5 class="fw-bold text-dark">Master Full Body Checkup (₹2,499)</h5>
                            <p class="text-secondary mb-3">
                                Covers 88 parameters, including hs-CRP cardiac inflammation, total IgE allergy markers, electrolytes, and comprehensive organ panels.
                            </p>
                            <a href="#all-packages" class="text-primary fw-bold small text-decoration-none">View Master Checkup <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-guide-card p-4 rounded-4 fbc-guide-custom h-100 shadow-sm border">
                            <div class="fbc-guide-badge bg-primary text-white mb-3">Customised Testing</div>
                            <h5 class="fw-bold text-dark">Build Your Own Package</h5>
                            <p class="text-secondary mb-3">
                                Want specific tests instead of a fixed bundle? Pick 2 or more tests, watch your live price update, and save up to 70%.
                            </p>
                            <a href="{{ route('customize.index') }}" class="btn btn-sm btn-primary fw-bold rounded-pill px-3 py-2">
                                <i class="fa-solid fa-sliders me-1"></i> Customise Package <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 8. HOW TO PREPARE FOR YOUR CHECKUP --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Patient Guidelines</span>
                    <h3 class="fbc-block-title">How to Prepare for Your Checkup</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">A few small steps make your diagnostic results accurate and dependable.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-prep-card p-3 rounded-3 bg-white border h-100">
                            <div class="fbc-prep-num">1</div>
                            <h6 class="fw-bold text-dark mb-1">Fast for 8 to 12 Hours</h6>
                            <p class="text-secondary small mb-0">Fast for 8 to 12 hours before sample collection. Drink only plain water.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-prep-card p-3 rounded-3 bg-white border h-100">
                            <div class="fbc-prep-num">2</div>
                            <h6 class="fw-bold text-dark mb-1">Skip Stimulants</h6>
                            <p class="text-secondary small mb-0">Skip tea, coffee, juice, alcohol, and smoking until your sample is collected.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-prep-card p-3 rounded-3 bg-white border h-100">
                            <div class="fbc-prep-num">3</div>
                            <h6 class="fw-bold text-dark mb-1">Avoid Heavy Workouts</h6>
                            <p class="text-secondary small mb-0">Avoid hard workouts and excessively heavy meals the evening before the test.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-prep-card p-3 rounded-3 bg-white border h-100">
                            <div class="fbc-prep-num">4</div>
                            <h6 class="fw-bold text-dark mb-1">Inform Medication</h6>
                            <p class="text-secondary small mb-0">Tell the phlebotomist about every medicine, hormone tablet, and supplement you take.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-prep-card p-3 rounded-3 bg-white border h-100">
                            <div class="fbc-prep-num">5</div>
                            <h6 class="fw-bold text-dark mb-1">Morning Urine Sample</h6>
                            <p class="text-secondary small mb-0">Give the first morning midstream urine sample if you can for optimum accuracy.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-prep-card p-3 rounded-3 bg-white border h-100">
                            <div class="fbc-prep-num">6</div>
                            <h6 class="fw-bold text-dark mb-1">Keep Prior Reports Ready</h6>
                            <p class="text-secondary small mb-0">Keep older reports handy for comparison and baseline review by your doctor.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 9. HOW HOME SAMPLE COLLECTION WORKS IN PUNE --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Doorstep Convenience</span>
                    <h3 class="fbc-block-title">How Home Sample Collection Works in Pune</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Looking for a full body checkup near you? We come straight to your door.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4 col-lg fbc-step-col">
                        <div class="fbc-step-card p-3 rounded-4 bg-white border text-center h-100">
                            <div class="fbc-step-circle">1</div>
                            <h6 class="fw-bold text-dark mb-2">Pick & Book</h6>
                            <p class="text-secondary small mb-0">Pick a package and tap Add to Cart, or call us directly.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg fbc-step-col">
                        <div class="fbc-step-card p-3 rounded-4 bg-white border text-center h-100">
                            <div class="fbc-step-circle">2</div>
                            <h6 class="fw-bold text-dark mb-2">Morning Slot</h6>
                            <p class="text-secondary small mb-0">Choose an early morning slot so fasting is quick and comfortable.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg fbc-step-col">
                        <div class="fbc-step-card p-3 rounded-4 bg-white border text-center h-100">
                            <div class="fbc-step-circle">3</div>
                            <h6 class="fw-bold text-dark mb-2">Phlebotomist Visit</h6>
                            <p class="text-secondary small mb-0">Our phlebotomist arrives, follows strict hygiene steps, and collects your samples.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg fbc-step-col">
                        <div class="fbc-step-card p-3 rounded-4 bg-white border text-center h-100">
                            <div class="fbc-step-circle">4</div>
                            <h6 class="fw-bold text-dark mb-2">Lab Processing</h6>
                            <p class="text-secondary small mb-0">Your samples go to our certified lab for accurate testing.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg fbc-step-col">
                        <div class="fbc-step-card p-3 rounded-4 bg-white border text-center h-100">
                            <div class="fbc-step-circle">5</div>
                            <h6 class="fw-bold text-dark mb-2">Digital Reports</h6>
                            <p class="text-secondary small mb-0">You receive digital reports on your phone to read and share with your doctor.</p>
                        </div>
                    </div>
                </div>

                {{-- Covered Areas Tag Strip --}}
                <div class="fbc-coverage-box p-4 rounded-4 bg-light border">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <strong class="text-dark d-block mb-1"><i class="fa-solid fa-map-location-dot text-primary me-2"></i>Pune & PCMC Coverage Areas:</strong>
                            <span class="text-secondary small">
                                We serve Pune and PCMC, including Pimple Saudagar, Pimpri, Wakad, Baner, Aundh, Hinjawadi, Kharadi, and Kothrud. Share your pin code when you book to confirm your area.
                            </span>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold text-nowrap">
                            <i class="fa-solid fa-truck-medical me-1"></i> Doorstep Collection Available
                        </span>
                    </div>
                </div>
            </div>

            {{-- 10. HOW TO READ YOUR FULL BODY CHECKUP REPORT --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Report Interpretation</span>
                    <h3 class="fbc-block-title">How to Read Your Full Body Checkup Report</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Your report prints its own normal range beside each result. Always use that range first. Here are common reference values for two key tests.</p>
                </div>

                <div class="row g-4 justify-content-center">
                    <div class="col-lg-8">
                        <div class="fbc-table-responsive shadow-sm mb-3">
                            <table class="table fbc-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col" style="min-width: 200px;">Test</th>
                                        <th scope="col" style="min-width: 180px;">Usually normal</th>
                                        <th scope="col" style="min-width: 200px;">Needs a doctor’s review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-dark">Fasting glucose</td>
                                        <td><span class="badge bg-success-subtle text-success border border-success-subtle">Below 100 mg/dL</span></td>
                                        <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">100 and above</span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-dark">HbA1c</td>
                                        <td><span class="badge bg-success-subtle text-success border border-success-subtle">Below 5.7%</span></td>
                                        <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">5.7% and above</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center mb-0 p-3">
                            <i class="fa-solid fa-circle-info fs-4 text-primary me-3 flex-shrink-0"></i>
                            <div class="small text-secondary">
                                <strong>Important Note:</strong> One result outside the range does not mean you are ill. Food, stress, water intake, and testing methods can all shift a number. Your doctor reads the whole report along with your personal health history.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 11. WHY BOOK YOUR HEALTH CHECKUP WITH WELLCARE LABS? --}}
            <div class="fbc-block mb-5">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Why Wellcare</span>
                    <h3 class="fbc-block-title">Why Book Your Health Checkup With Wellcare Labs?</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">High precision diagnostic standards, experienced pathologists, and transparent pricing in Pune.</p>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-trust-icon text-primary mb-3"><i class="fa-solid fa-certificate fs-2"></i></div>
                            <h6 class="fw-bold text-dark">ISO 9001:2015 Certified</h6>
                            <p class="text-secondary small mb-0">Certified quality management system ensuring stringent clinical diagnostic quality and hygiene protocols.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-trust-icon text-success mb-3"><i class="fa-solid fa-clock-rotate-left fs-2"></i></div>
                            <h6 class="fw-bold text-dark">8+ Years in Diagnostics</h6>
                            <p class="text-secondary small mb-0">Over 8 years of trusted pathology expertise serving individuals, doctors, and healthcare institutions across Pune.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-trust-icon text-info mb-3"><i class="fa-solid fa-user-doctor fs-2"></i></div>
                            <h6 class="fw-bold text-dark">Pathologists & Trained Phlebotomists</h6>
                            <p class="text-secondary small mb-0">Our team includes certified pathologists and skilled phlebotomists who handle your samples with care.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-trust-icon text-danger mb-3"><i class="fa-solid fa-location-dot fs-2"></i></div>
                            <h6 class="fw-bold text-dark">A Real, Verified Address</h6>
                            <p class="text-secondary small mb-0">Shop 113, First Floor, A-Wing, Sai Vision Mall, Pimple Saudagar, Pune 411027.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-trust-icon text-warning mb-3"><i class="fa-solid fa-tag fs-2"></i></div>
                            <h6 class="fw-bold text-dark">Clear Package Details</h6>
                            <p class="text-secondary small mb-0">Every card shows exactly what you pay and how many parameters you get with zero hidden fees.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="fbc-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
                            <div class="fbc-trust-icon text-secondary mb-3"><i class="fa-solid fa-hand-holding-medical fs-2"></i></div>
                            <h6 class="fw-bold text-dark">Honest Clinical Responsibility</h6>
                            <p class="text-secondary small mb-0">One honest note: A checkup is a screen, not a diagnosis. It cannot find every disease, and it does not replace your doctor.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 12. FULL BODY CHECKUP FAQS --}}
            <div class="fbc-block mb-5" id="fbc-faqs">
                <div class="fbc-section-header text-center">
                    <span class="fbc-pill-tag">Got Questions?</span>
                    <h3 class="fbc-block-title">Full Body Checkup FAQs</h3>
                    <div class="fbc-title-divider mx-auto"></div>
                    <p class="text-muted fbc-block-subtitle">Everything you need to know about testing, sample collection, fasting, and reports.</p>
                </div>

                <div class="fbc-faq-accordion">
                    {{-- FAQ 1 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">1</span>
                            <span class="fbc-faq-qtext">What is a full body checkup?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>A full body checkup is a set of blood and urine tests that show how your key organs work. It checks blood sugar, cholesterol, liver and kidney function, thyroid function, and vitamins, so we can catch problems early.</p>
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">2</span>
                            <span class="fbc-faq-qtext">What tests are included in a full body checkup package?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Most packages include CBC, HbA1c or fasting glucose, lipid profile, liver and kidney tests, thyroid profile, and urine tests. Many also add vitamin D, vitamin B12, and iron studies.</p>
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">3</span>
                            <span class="fbc-faq-qtext">How much does a full body checkup cost in Pune?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>At Wellcare Labs, packages run from ₹799 to ₹2,599. The price depends on how many tests you want. Check the package card for the latest price before you book.</p>
                        </div>
                    </div>

                    {{-- FAQ 4 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">4</span>
                            <span class="fbc-faq-qtext">Which full body checkup package is best for me?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Healthy adults under 30 can start with the Smart plan. Adults over 30 can choose Complete or Master plans. Men and women can pick plans with PSA or hormone tests. Call us if you are unsure.</p>
                        </div>
                    </div>

                    {{-- FAQ 5 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">5</span>
                            <span class="fbc-faq-qtext">Do I need to fast before a full body checkup?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Yes. Fast for 8 to 12 hours, or 10 to 12 hours for the Master Male plan. Drink plain water only. Skip tea, coffee, juice, alcohol, and smoking until your sample is taken.</p>
                        </div>
                    </div>

                    {{-- FAQ 6 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">6</span>
                            <span class="fbc-faq-qtext">How often should I get a full body checkup?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Most healthy adults do well with one checkup a year, especially after 30. If you have diabetes, high blood pressure, a thyroid problem, or a family history of heart disease, your doctor may advise more often.</p>
                        </div>
                    </div>

                    {{-- FAQ 7 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">7</span>
                            <span class="fbc-faq-qtext">Is home sample collection available in Pune?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Yes. We collect samples at home across Pune and PCMC, including Pimple Saudagar, Wakad, Baner, Aundh, Hinjawadi, and Kharadi. Share your pin code when you book, and we will confirm your area.</p>
                        </div>
                    </div>

                    {{-- FAQ 8 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">8</span>
                            <span class="fbc-faq-qtext">How do I book a full body checkup at Wellcare Labs?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Pick a package on this page and tap Add to Cart. You can also call +91 915 898 0898 or message us on WhatsApp. Choose a morning slot, and our phlebotomist will visit at that time.</p>
                        </div>
                    </div>

                    {{-- FAQ 9 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">9</span>
                            <span class="fbc-faq-qtext">How soon will I get my reports?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Many reports are ready within about 15 hours of sample collection. Some tests take longer. You get your reports digitally, so you can open them on your phone and share them with your doctor.</p>
                        </div>
                    </div>

                    {{-- FAQ 10 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">10</span>
                            <span class="fbc-faq-qtext">Can a full body checkup detect every disease?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>No. It screens for common problems like diabetes, thyroid issues, anaemia, and high cholesterol. It does not replace scans, a physical exam, or a doctor’s advice. Your results may lead to extra tests.</p>
                        </div>
                    </div>

                    {{-- FAQ 11 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">11</span>
                            <span class="fbc-faq-qtext">Do I need a doctor’s prescription?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>No prescription is needed for a preventive checkup. If you have symptoms or a health condition, talk to your doctor first. They can tell you which tests matter most for you.</p>
                        </div>
                    </div>

                    {{-- FAQ 12 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">12</span>
                            <span class="fbc-faq-qtext">Can women take the test during periods?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Blood tests are usually fine. Urine tests may need another day. Hormone tests such as FSH, LH, and oestradiol are often timed to certain days of your cycle, so ask your doctor or call us before booking.</p>
                        </div>
                    </div>

                    {{-- FAQ 13 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">13</span>
                            <span class="fbc-faq-qtext">Can I take my regular medicines before the test?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Usually yes, unless your doctor says otherwise. Tell the phlebotomist about all the medicines. Many doctors ask you to take thyroid tablets after the sample. If you take diabetes medicines, ask your doctor how to time them.</p>
                        </div>
                    </div>

                    {{-- FAQ 14 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">14</span>
                            <span class="fbc-faq-qtext">Can I add extra tests to my package?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Yes. Visit our <a href="{{ route('customize.index') }}" class="text-primary fw-bold">Customise Packages</a> page or call us to add the tests you need, such as a specific hormone or vitamin test. Our team can help you build a plan that fits your budget.</p>
                        </div>
                    </div>

                    {{-- FAQ 15 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">15</span>
                            <span class="fbc-faq-qtext">What should I do if my report shows abnormal values?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Stay calm. One abnormal value does not always mean disease. Food, stress, water intake, and testing methods can all shift a number. Share your report with your doctor, who will read it along with your health history.</p>
                        </div>
                    </div>

                    {{-- FAQ 16 --}}
                    <div class="fbc-faq-item">
                        <button class="fbc-faq-question" type="button" aria-expanded="false">
                            <span class="fbc-faq-num">16</span>
                            <span class="fbc-faq-qtext">Is Wellcare Labs reliable and certified?</span>
                            <span class="fbc-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
                        </button>
                        <div class="fbc-faq-answer">
                            <p>Wellcare Labs holds ISO 9001:2015 certification for its quality management system and has over 8 years of experience in diagnostics. Our team includes pathologists and trained phlebotomists, and our centre is at Sai Vision Mall, Pimple Saudagar.</p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 13. BOOK YOUR FULL BODY CHECKUP IN PUNE TODAY (FINAL CTA BANNER) --}}
            <div class="fbc-final-banner p-4 p-md-5 rounded-4 text-center text-white shadow">
                <h3 class="fw-bold mb-3 text-white">Book Your Full Body Checkup in Pune Today</h3>
                <p class="fbc-final-desc mx-auto mb-4" style="max-width: 780px; opacity: 0.95; font-size: 1.08rem; line-height: 1.7;">
                    Pick your package above and tap Add to Cart to book online with home sample collection across Pune & PCMC. Prefer custom tests? Build your own package tailored to your exact health goals.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="#all-packages" class="fbc-btn fbc-btn-light">
                        <i class="fa-solid fa-arrow-up text-primary me-2"></i> Browse Packages Above
                    </a>
                    <a href="{{ route('customize.index') }}" class="fbc-btn fbc-btn-outline-light">
                        <i class="fa-solid fa-sliders me-2"></i> Customise Package Online
                    </a>
                </div>
            </div>

        </div>
    </section>
@endsection


@section('scripts')

    <style>
        html,
        body {
            overflow-y: auto !important;
            overflow-x: hidden !important;
            height: auto !important;
        }

        /* --- Card look --- */
        #all-packages .pretty-card {
            background: #fff;
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(10, 38, 64, .06), 0 3px 8px rgba(10, 38, 64, .04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* ================= STAGGERED SCROLL-IN ANIMATION ================= */

        /* Initial state */
        #all-packages .pretty-card {
            opacity: 0;
            transform: translateY(26px);
            transition:
                opacity 520ms ease,
                transform 520ms cubic-bezier(.2, .8, .2, 1),
                box-shadow 220ms cubic-bezier(.2, .8, .2, 1);
        }

        /* Visible state */
        #all-packages .pretty-card.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Hover lift (card only) */
        #all-packages .pretty-card:hover {
            transform: translateY(-6px);
            box-shadow:
                0 20px 45px rgba(10, 38, 64, .16),
                0 6px 16px rgba(10, 38, 64, .08);
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            #all-packages .pretty-card {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }

        #all-packages .card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }

        /* --- Grid: center every row; make columns equal-height containers --- */
        #all-packages .row.justify-content-center {
            justify-content: center;
        }

        #all-packages .row>[class*="col"] {
            display: flex;
        }

        #all-packages .row.g-4>[class*="col-"] {
            margin-bottom: 1.5rem;
        }



        /* --- Title clamp --- */
        #all-packages .pkg-title {
            margin: 0;
            font-size: 1.03rem;
            font-weight: 700;
            min-height: calc(1.3em * 2);
        }

        #all-packages .title-link {
            color: #ff2b6d;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 2;
        }

        #all-packages .title-link:hover {
            opacity: .9;
        }

        /* --- Chips (always reserve space) --- */
        #all-packages .chips {
            min-height: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        #all-packages .chips:empty::before {
            content: "";
            display: block;
            height: 32px;
        }

        #all-packages .chip-pill {
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

        #all-packages .chip-pill .text-truncate {
            display: inline-block;
            max-width: 170px;
        }

        @media (max-width:576px) {
            #all-packages .chip-pill .text-truncate {
                max-width: 130px;
            }
        }

        #all-packages .chip-more {
            background: #eef5ff;
        }

        /* --- Price block (reserve consistent space) --- */
        #all-packages .price-final {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1a3a66;
        }

        #all-packages .price-mrp {
            color: #000 !important;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 600;
        }

        #all-packages .discount-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e9f9ef;
            color: #2a7b3f;
            font-weight: 700;
            font-size: .8rem;
            box-shadow: inset 0 0 0 1px rgba(42, 123, 63, .08);
        }

        #all-packages .purchase-meta {
            min-height: 48px;
        }

        #all-packages .purchase-meta .d-flex.align-items-center {
            gap: 10px;
        }

        #all-packages .save-link {
            color: #0a66d6;
            font-weight: 600;
            font-size: .9rem;
        }

        /* --- Buttons layer --- */
        #all-packages .btn-foreground,
        #all-packages .btn-foreground .btn {
            position: relative;
            z-index: 5;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (min-width:577px) {
            #all-packages .btn-foreground {
                gap: 16px;
            }
        }

        /* --- View Info button (compact) --- */
        #all-packages .btn-view-info {
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

        #all-packages .btn-view-info i {
            font-size: .8rem;
        }

        #all-packages .btn-view-info:hover {
            background: #f5f9ff;
        }

        /* --- Cart buttons --- */
        #all-packages .btn-action-cart {
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

        /* Soft pink add-to-cart (same as special page) */
        #all-packages .btn-add-cart.btn-action-cart {
            background: #f0c2c2;
            color: #2b4a66;
            border: 1px solid rgba(14, 63, 108, .12);
            transition: background .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        #all-packages .btn-add-cart.btn-action-cart:hover {
            background: #eef4ff;
            border-color: rgba(14, 63, 108, .25);
            color: #173b5f;
        }

        #all-packages .btn-add-cart.btn-action-cart:active {
            background: #eef4ff;
        }

        /* Go to cart (green) */
        #all-packages .btn-go-cart.btn-action-cart {
            background: #9dd24a !important;
            color: #fff !important;
            border: 0 !important;
            box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
        }

        #all-packages .btn-go-cart.btn-action-cart:hover {
            background: #8ac83d !important;
        }

        #all-packages .btn-go-cart.btn-action-cart:active {
            background: #7ebd33 !important;
        }

        #all-packages .btn-action-cart.is-adding {
            pointer-events: none;
            opacity: .9;
        }

        #all-packages .btn-view-info i,
        #all-packages .btn-action-cart i {
            font-size: 1.05em;
        }

        /* Bottom spacing and width for each card (matching special packages) */
        #all-packages .pretty-card {
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 24px;
        }

        /* Mobile compact tweaks */
        @media (max-width:576px) {

            #all-packages .btn-view-info,
            #all-packages .btn-action-cart {
                padding: .55rem .9rem;
                font-size: .92rem;
                min-height: 40px;
                min-width: 120px;
            }

            #all-packages .btn-foreground {
                gap: .5rem;
            }
        }

        /* ================= IMAGE (SAME AS SPECIAL PAGE) ================= */






        /* ================= TABLET: FORCE 2 CARDS ================= */

        @media (min-width: 600px) and (max-width: 1024px) {
            #all-packages .row.justify-content-center {
                display: grid;
                grid-template-columns: repeat(2, minmax(280px, 1fr));
                gap: 28px;
                justify-content: center;
            }

            #all-packages .row.justify-content-center>[class*="col"] {
                flex: unset !important;
                max-width: unset !important;
                width: auto !important;
            }
        }

        /* ================= CARD HEIGHT CONSISTENCY ================= */

        #all-packages .chips {
            min-height: 48px;
        }

        #all-packages .chips:empty::before {
            content: "";
            display: block;
            height: 48px;
        }

        #all-packages .btn-foreground {
            margin-top: auto;
        }

        /* ================= SAME IMAGE BEHAVIOR AS SPECIAL PAGE ================= */

        /* ================= SMART RESPONSIVE IMAGE SYSTEM ================= */

        /* BASE: container */


        /* BASE image */


        /* ========================================================= */
        /* DESKTOP & LARGE TABLETS (Landscape marketing look) */
        /* ========================================================= */


        /* ========================================================= */
        /* TABLETS (iPad Mini, Air, Pro, Surface Pro) */
        /* ========================================================= */
        /* ================= TABLET GRID (SAME AS SPECIAL PACKAGE) ================= */

        /* iPad Mini, Air, Pro, Surface Pro, Nest Hub */
        @media (min-width: 600px) and (max-width: 1024px) {

            #all-packages .tablet-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(300px, 1fr));
                gap: 28px;
                justify-content: center;
            }

            /* Disable Bootstrap column sizing */
            #all-packages .tablet-grid>[class*="col"] {
                flex: unset !important;
                max-width: unset !important;
                width: auto !important;
            }
        }


        /* ========================================================= */
        /* PHONES, FOLDABLES, HUBS - HORIZONTAL SCROLLING */
        /* ========================================================= */
        @media (max-width: 767px) {
            #all-packages .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
                overflow: hidden !important;
            }

            #all-packages .tablet-grid,
            #all-packages .row.tablet-grid {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                scroll-snap-type: x mandatory !important;
                gap: 16px !important;
                padding-left: 5px !important;
                padding-right: 15px !important;
                padding-bottom: 20px !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                justify-content: flex-start !important;
            }

            #all-packages .tablet-grid > [class*="col"] {
                flex: 0 0 285px !important;
                max-width: 285px !important;
                min-width: 270px !important;
                width: 285px !important;
                scroll-snap-align: start !important;
                margin-bottom: 0 !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            #all-packages .pretty-card {
                max-width: 100% !important;
                width: 100% !important;
                margin-inline: unset !important;
            }
        }


        /* ================= PACKAGE IMAGE — FINAL FIX ================= */


        /* Slightly smaller image on very small phones */


        /* ================= MOBILE IMAGE FIX (NO CROP) ================= */



        /* ================= MOBILE IMAGE FIX (FINAL – NO BOTTOM CROP) ================= */

        /* ===================================================== */
        /* FINAL IMAGE SYSTEM — NO CROP, NO OVERFLOW, ALL DEVICES */
        /* ===================================================== */

        #all-packages .img-fixed {
            position: relative;
            height: 240px;
            width: 100%;
            overflow: hidden;
        }

        #all-packages .pkg-img {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            display: block !important;
            object-fit: cover !important;
            object-position: center !important;
        }
    </style>

    <script>
        /* ---------- CART: Add → Go to Cart (same behavior as Special) ---------- */
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

            async function refreshAllPackagesCartButtons() {
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

                    document.querySelectorAll('#all-packages button[id^="cart-btn-"]').forEach(btn => {
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
                    console.warn('refreshAllPackagesCartButtons failed:', e);
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
                            credentials: 'same-origin',
                            keepalive: true
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
                            refreshAllPackagesCartButtons();
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

            document.addEventListener('DOMContentLoaded', refreshAllPackagesCartButtons, {
                once: true
            });
            window.addEventListener('pageshow', refreshAllPackagesCartButtons);
        })();

        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('#all-packages .pretty-card');

            if (!('IntersectionObserver' in window)) {
                cards.forEach(card => card.classList.add('is-visible'));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const card = entry.target;

                        // stagger delay based on index
                        const index = [...cards].indexOf(card);
                        card.style.transitionDelay = `${index * 80}ms`;

                        card.classList.add('is-visible');
                        observer.unobserve(card);
                    }
                });
            }, {
                threshold: 0.25,
                rootMargin: '0px 0px -60px 0px'
            });

            cards.forEach(card => observer.observe(card));
        });

        /* ================= FAQ ACCORDION TOGGLE ================= */
        document.addEventListener('DOMContentLoaded', function () {
            const faqItems = document.querySelectorAll('.fbc-faq-item');
            faqItems.forEach(item => {
                const questionBtn = item.querySelector('.fbc-faq-question');
                if (!questionBtn) return;
                questionBtn.addEventListener('click', function () {
                    const isOpen = item.classList.contains('active');
                    // close all others
                    faqItems.forEach(other => {
                        if (other !== item) {
                            other.classList.remove('active');
                            const otherBtn = other.querySelector('.fbc-faq-question');
                            if (otherBtn) otherBtn.setAttribute('aria-expanded', 'false');
                        }
                    });
                    // toggle current
                    if (isOpen) {
                        item.classList.remove('active');
                        questionBtn.setAttribute('aria-expanded', 'false');
                    } else {
                        item.classList.add('active');
                        questionBtn.setAttribute('aria-expanded', 'true');
                    }
                });
            });

            // Smooth scroll for internal anchor links (like #all-packages)
            document.querySelectorAll('a[href="#all-packages"]').forEach(link => {
                link.addEventListener('click', function (e) {
                    const target = document.getElementById('all-packages');
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
        });
    </script>

    {{-- ================= FULL BODY CHECKUP GUIDE STYLES ================= --}}
    <style>
        .fbc-guide-section {
            background: #ffffff;
            color: #1e293b;
            font-family: inherit;
        }

        .fbc-guide-section > .container {
            max-width: 1160px;
            margin: 0 auto;
        }

        .fbc-gradient-text {
            background: linear-gradient(135deg, #0d6efd 0%, #00c6ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .fbc-title-divider {
            width: 70px;
            height: 4px;
            border-radius: 2px;
            background: linear-gradient(90deg, #0047ff, #00ccff);
            margin: 12px 0 20px;
        }

        .fbc-pill-tag {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #eaf2ff;
            color: #0047ff;
            margin-bottom: 10px;
        }

        /* --- Hero Card --- */
        .fbc-hero-card {
            background: linear-gradient(145deg, #ffffff 0%, #f4f8fe 100%);
            border: 1px solid #dbeafe;
            border-radius: 24px;
            padding: 38px 36px;
            box-shadow: 0 12px 30px -8px rgba(13, 110, 253, 0.08);
        }

        .fbc-hero-badge {
            display: inline-flex;
            align-items: center;
            background: #ecfdf5;
            color: #047857;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 999px;
            margin-bottom: 16px;
            border: 1px solid rgba(5, 150, 105, 0.25);
        }

        .fbc-hero-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #0a2540;
            line-height: 1.28;
            margin-bottom: 12px;
        }

        .fbc-hero-text p {
            font-size: 1.05rem;
            line-height: 1.75;
            color: #334155;
            margin-bottom: 12px;
        }

        /* --- CTA Buttons Strip --- */
        .fbc-cta-strip {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .fbc-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 24px;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            border: none;
        }

        .fbc-btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(13, 110, 253, 0.3);
        }

        .fbc-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
            color: #ffffff !important;
        }

        .fbc-btn-outline {
            background: #ffffff;
            color: #0d6efd !important;
            border: 1.5px solid #0d6efd;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.08);
        }

        .fbc-btn-outline:hover {
            background: #eff6ff;
            color: #0047ff !important;
            transform: translateY(-2px);
        }

        .fbc-btn-light {
            background: #ffffff;
            color: #0a2540 !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        }

        .fbc-btn-light:hover {
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .fbc-btn-outline-light {
            background: transparent;
            color: #ffffff !important;
            border: 1.5px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
        }

        .fbc-btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff !important;
            border-color: #ffffff;
            transform: translateY(-2px);
        }

        /* --- Section Titles --- */
        .fbc-block-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0a2540;
            margin-bottom: 6px;
        }

        .fbc-block-subtitle {
            font-size: 1.05rem;
            color: #475569;
            margin-bottom: 24px;
        }

        /* --- Tables --- */
        .fbc-table-responsive {
            border-radius: 16px;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 6px 20px rgba(10, 37, 64, 0.04);
        }

        .fbc-table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .fbc-table thead th {
            background: #f8fafc;
            color: #0a2540;
            font-weight: 700;
            font-size: 0.86rem;
            padding: 16px 20px;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .fbc-table tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.96rem;
            color: #334155;
        }

        .fbc-table tbody tr:hover td {
            background: #f8faff;
        }

        .fbc-pkg-name {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a2540;
            margin-bottom: 3px;
        }

        .fbc-param-badge {
            display: inline-block;
            background: #eff6ff;
            color: #0284c7;
            font-weight: 800;
            font-size: 0.92rem;
            padding: 4px 12px;
            border-radius: 999px;
            border: 1px solid #bfdbfe;
        }

        .fbc-price-tag {
            font-weight: 800;
            font-size: 1.25rem;
            color: #059669;
        }

        .fbc-best-for {
            font-size: 0.94rem;
            color: #475569;
        }

        .fbc-btn-table {
            background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
            color: #ffffff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 3px 10px rgba(13, 110, 253, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .fbc-btn-table:hover {
            background: #0047ff;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(13, 110, 253, 0.35);
        }

        /* --- Quick Facts --- */
        .fbc-quick-facts-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .fbc-fact-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100%;
            box-shadow: 0 3px 10px rgba(10, 37, 64, 0.03);
            transition: all 0.2s ease;
        }

        .fbc-fact-card:hover {
            transform: translateY(-2px);
            border-color: #bfdbfe;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.06);
        }

        .fbc-fact-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .fbc-fact-icon.bg-blue { background: #eaf2ff; color: #0d6efd; }
        .fbc-fact-icon.bg-amber { background: #fef3c7; color: #d97706; }
        .fbc-fact-icon.bg-green { background: #ecfdf5; color: #059669; }
        .fbc-fact-icon.bg-purple { background: #f3e8ff; color: #9333ea; }

        .fbc-fact-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #64748b;
        }

        .fbc-fact-value {
            font-weight: 700;
            color: #0a2540;
            font-size: 0.98rem;
            line-height: 1.35;
        }

        /* --- Lead text & analogy --- */
        .fbc-lead-text {
            font-size: 1.12rem;
            line-height: 1.8;
            color: #334155;
            margin-bottom: 20px;
        }

        .fbc-checklist li {
            padding: 10px 14px;
            font-size: 0.96rem;
            color: #1e293b;
            font-weight: 600;
            display: flex;
            align-items: center;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 8px;
            border: 1px solid #f1f5f9;
        }
        .fbc-checklist li:last-child {
            margin-bottom: 0;
        }

        /* --- More Tests Card --- */
        .fbc-more-tests-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        /* --- Benefit Cards --- */
        .fbc-benefit-card {
            padding: 26px 20px;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 4px 14px rgba(10, 37, 64, 0.03);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .fbc-benefit-card:hover {
            transform: translateY(-4px);
            border-color: #cbd5e1;
            box-shadow: 0 12px 28px rgba(10, 37, 64, 0.08) !important;
        }

        .fbc-benefit-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        /* --- Who Cards --- */
        .fbc-who-card {
            padding: 16px 20px;
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 3px 10px rgba(10, 37, 64, 0.03);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.2s ease;
        }
        .fbc-who-card:hover {
            transform: translateY(-2px);
            border-color: #0d6efd;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.08);
        }

        /* --- Guide Cards --- */
        .fbc-guide-card {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 26px 22px;
            box-shadow: 0 4px 14px rgba(10, 37, 64, 0.03);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .fbc-guide-card:hover {
            transform: translateY(-4px);
            border-color: #0d6efd;
            box-shadow: 0 12px 28px rgba(13, 110, 253, 0.09) !important;
        }

        .fbc-guide-custom {
            background: linear-gradient(145deg, #eff6ff 0%, #dbeafe 100%) !important;
            border: 1px solid #93c5fd !important;
        }

        .fbc-guide-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* --- Preparation Cards --- */
        .fbc-prep-card {
            position: relative;
            padding: 20px 20px 20px 64px !important;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 3px 10px rgba(10, 37, 64, 0.03);
            transition: all 0.2s ease;
        }
        .fbc-prep-card:hover {
            border-color: #0d6efd !important;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.06);
            transform: translateY(-2px);
        }
        .fbc-prep-num {
            position: absolute;
            left: 16px;
            top: 18px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #00c6ff 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
        }

        /* --- Step Cards --- */
        .fbc-step-card {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 24px 18px;
            text-align: center;
            box-shadow: 0 4px 14px rgba(10, 37, 64, 0.03);
            transition: all 0.25s ease;
        }
        .fbc-step-card:hover {
            border-color: #0d6efd !important;
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(13, 110, 253, 0.08);
        }
        .fbc-step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #00c6ff 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.28);
        }

        /* --- Trust Cards --- */
        .fbc-trust-card {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 26px 22px;
            box-shadow: 0 4px 14px rgba(10, 37, 64, 0.03);
            transition: all 0.25s ease;
        }
        .fbc-trust-card:hover {
            transform: translateY(-4px);
            border-color: #cbd5e1 !important;
            box-shadow: 0 10px 24px rgba(10, 37, 64, 0.06) !important;
        }

        /* --- FAQ Accordion --- */
        .fbc-faq-accordion {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .fbc-faq-item {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .fbc-faq-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(10, 37, 64, 0.04);
        }

        .fbc-faq-item.active {
            border-color: #0d6efd;
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.08);
        }

        .fbc-faq-question {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            font-size: 1.05rem;
            font-weight: 700;
            color: #0a2540;
            transition: background-color 0.2s ease;
        }

        .fbc-faq-question:hover {
            background-color: #f8faff;
        }

        .fbc-faq-num {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #0d6efd;
            font-weight: 800;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .fbc-faq-item.active .fbc-faq-num {
            background: #0d6efd;
            color: #ffffff;
        }

        .fbc-faq-qtext {
            flex: 1;
            margin: 0;
            line-height: 1.45;
        }

        .fbc-faq-arrow {
            font-size: 0.9rem;
            color: #64748b;
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .fbc-faq-item.active .fbc-faq-arrow {
            transform: rotate(180deg);
            color: #0d6efd;
        }

        .fbc-faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(0, 1, 0, 1), padding 0.35s ease;
            padding: 0 22px;
            color: #475569;
            font-size: 0.98rem;
            line-height: 1.75;
            background: #ffffff;
        }

        .fbc-faq-item.active .fbc-faq-answer {
            max-height: 600px;
            padding: 4px 22px 20px 70px;
            transition: max-height 0.4s ease-in, padding 0.35s ease;
        }

        .fbc-faq-answer p {
            margin: 0;
        }

        /* --- Final Banner --- */
        .fbc-final-banner {
            background: linear-gradient(135deg, #0a2540 0%, #0d6efd 100%);
            border-radius: 24px;
            padding: 48px 36px;
            box-shadow: 0 16px 36px rgba(10, 37, 64, 0.15);
        }

        @media (max-width: 768px) {
            .fbc-hero-card {
                padding: 24px 18px;
            }
            .fbc-hero-title {
                font-size: 1.65rem;
            }
            .fbc-block-title {
                font-size: 1.45rem;
            }
            .fbc-faq-question {
                padding: 14px 16px;
                gap: 12px;
                font-size: 0.97rem;
            }
            .fbc-faq-item.active .fbc-faq-answer {
                padding: 4px 16px 16px 16px;
            }
            .fbc-cta-strip {
                flex-direction: column;
            }
            .fbc-cta-strip .fbc-btn {
                width: 100%;
            }
        }
    </style>
@endsection