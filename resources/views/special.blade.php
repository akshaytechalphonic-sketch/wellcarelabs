{{-- resources/views/special.blade.php --}}

@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Special Health Checkup Packages in Pune from ₹599 | Wellcare')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Book special health checkup packages in Pune from ₹599. Gym, fever, diabetes, vitamin, pregnancy, and arthritis panels with home sample collection.')

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
          'name' => 'What are special health checkup packages?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Special packages are focused test bundles for one need, such as diabetes, fever, vitamins, pregnancy, arthritis, or gym fitness. They cost less than a full body checkup because they test fewer areas.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'How much do special health packages cost in Pune?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'At Wellcare Labs, special packages run from ₹599 to ₹4,999. The Essential Diabetes Panel is ₹599, and the Advanced Arthritis Package is ₹4,999. Check each card for the latest price.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Which package is best for diabetes screening?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'The Essential Diabetes Panel at ₹599 is the starting point. The card shows CBC, HbA1c, lipid profile, and fasting glucose, plus one more test. Your doctor can advise if you need extra tests.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'What is inside the gym panels?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'The Smart Gym Panel (₹1,599) lists CBC, ESR, HbA1c, and hs-CRP, plus more. The Advance Gym Panel (₹3,999) adds vitamin D and apolipoprotein A1 and B. Both suit regular gym-goers.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'What is the difference between the Smart and Advanced Fever Profiles?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'The Smart plan (₹699) covers CBC, ESR, SGPT, and SGOT, plus three more tests. The Advanced plan (₹1,599) adds bilirubin, CRP, and a rapid dengue NS-1 antigen test, among others.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'What does the ANC package cover?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'ANC means antenatal care, the checks done during pregnancy. The cards list blood group and Rh type, anti-HCV, CBC, and HbA1c, plus six more tests. Ask your gynaecologist which version you need.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Can the cancer detection profile diagnose cancer?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => "No. It measures tumour markers such as AFP, CA 19.9, and CEA. These can rise for reasons other than cancer. A high result means you need a doctor's review, not that you have cancer."
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Who should take an arthritis package?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Consider it if you have long-term joint pain, stiffness, or swelling. The Advance package lists anti-CCP, ANA, and ASO along with vitamin D. A doctor should read the results.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Do I need to fast before a special package?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'It depends on the package. Tests like fasting glucose and lipid profile need fasting, while many fever tests do not. Our team confirms the exact rule when you book.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Is home sample collection available in Pune?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Yes. We collect samples across Pune and PCMC, including Pimple Saudagar, Wakad, Baner, Aundh, Hinjawadi, and Kharadi. Share your pin code when you book so we can confirm your area.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => "Do I need a doctor's prescription?",
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Not for most screening packages. If you have symptoms, are pregnant, or already take treatment, talk to your doctor first. They can tell you which package matches your case.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'How soon will I get my reports?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Report time depends on the tests in your package. Our team shares the expected time when you book. You receive reports digitally, so you can open and share them with your doctor.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Can I add more tests to a special package?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Yes. Use our Customise Packages page or ask our team to add tests. This helps when a ready-made bundle misses one test your doctor asked for.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Should I take a special package or a full body checkup?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Choose a special package when you have one clear concern. Choose a full body checkup for a yearly overview of blood, liver, kidneys, thyroid, and vitamins. Avoid paying twice for the same tests.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'What should I do if a result looks abnormal?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Stay calm. One abnormal value does not always mean disease. Food, stress, water intake, and testing methods can all shift results. Share the report with your doctor, who reads it along with your health history.'
          ]
        ],
        [
          '@type' => 'Question',
          'name' => 'Is Wellcare Labs reliable and certified?',
          'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => 'Wellcare Labs holds ISO 9001:2015 certification for its quality management system and has over 8 years in diagnostics. Our team includes pathologists and trained phlebotomists, and our centre is at Sai Vision Mall, Pimple Saudagar.'
          ]
        ]
      ]
    ];

    $bizSchema = [
      '@context' => 'https://schema.org',
      '@type' => 'MedicalBusiness',
      'name' => 'Wellcare Labs - Special Health Checkup Packages Pune',
      'image' => asset('Front_end/assets/img/favicon.ico'),
      'telephone' => '+919158980898',
      'priceRange' => '₹599 - ₹4999',
      'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Shop 113, First Floor, A-Wing, Sai Vision Mall, Pimple Saudagar',
        'addressLocality' => 'Pune',
        'addressRegion' => 'Maharashtra',
        'postalCode' => '411027',
        'addressCountry' => 'IN'
      ],
      'url' => 'https://wellcarelabs.in/packages/special'
    ];
  @endphp
  <script type="application/ld+json">
  {!! json_encode($faqSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>
  <script type="application/ld+json">
  {!! json_encode($bizSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>
@endsection


@section('content')

  <section id="packages-special" class="py-5" style="
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
        Wellcare Exclusive <span style="color:#0d6efd;">Special Packages</span>
      </h1>
      <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                  background:linear-gradient(90deg,#0047ff,#00ccff);"></div>
      <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
        Doctor-Designed. Patient-Focused. Budget-Friendly. Special Packages. </p>
      </p>
    </div>

    @if($packages->count())
      <div class="container-fluid px-3 px-md-5">
        {{-- Always center rows so when a new row starts, it starts from the center --}}
        <div class="row g-4 justify-content-center tablet-grid">
          @foreach($packages as $pkg)
            @php
              $title = $pkg->title ?? 'Package';

              // prices
              $mrp = (float) ($pkg->mrp ?? 0);
              $discounted = (float) ($pkg->discounted_price ?? 0);
              $price = (float) ($pkg->price ?? 0);
              $displayPrice = $discounted > 0 ? $discounted : ($price > 0 ? $price : null);
              $savePercent = ($mrp > 0 && $displayPrice) ? round((($mrp - $displayPrice) / $mrp) * 100) : 0;
              $savingAmount = ($mrp > 0 && $displayPrice && $mrp > $displayPrice) ? ($mrp - $displayPrice) : 0;

              // image
              $banner = $pkg->banner ? ltrim($pkg->banner, '/') : null;
              $bannerPath = $banner ? storage_path('app/public/' . $banner) : null;
              $hasBanner = $bannerPath && file_exists($bannerPath);
              $defaultImage = asset('Front_end/assets/img/blog/default-package.jpg');
              $svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 360'><rect width='100%' height='100%' fill='#f6fbfb'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='#9aa0a6' font-size='20'>No image available</text></svg>";
              $placeholder = 'data:image/svg+xml;utf8,' . rawurlencode($svg);

              // chips (tests)
              $tests = [];
              if (isset($pkg->tests) && is_iterable($pkg->tests) && $pkg->tests->count()) {
                foreach ($pkg->tests as $t) {
                  $tests[] = trim($t->test_name ?? ($t->title ?? ($t->name ?? '')));
                }
              } elseif (!empty($pkg->tests_list) && is_string($pkg->tests_list)) {
                $tests = array_map('trim', explode(',', strip_tags($pkg->tests_list)));
              } else {
                $parts = preg_split('/\r\n|\n|,/', $pkg->content ?? '');
                $parts = array_map(fn($v) => trim(rtrim($v, ", \t\n\r\0\x0B")), $parts);
                $parts = array_filter($parts, fn($v) => $v !== '');
                $tests = array_values($parts);
              }
              $seen = [];
              $tests = array_values(array_filter(array_map(function ($v) use (&$seen) {
                $k = mb_strtolower($v);
                if ($k === '' || isset($seen[$k]))
                  return null;
                $seen[$k] = true;
                return $v;
              }, $tests)));
              $maxShow = 4;
              $totalBadges = count($tests);
              $showBadges = array_slice($tests, 0, $maxShow);
              // dd($showBadges[3]);

              $packageParam = $pkg->slug ?? $pkg->id;
            @endphp

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
              <div class="pretty-card h-100 d-flex flex-column position-relative w-100">
                <a class="img-fixed rounded-top-4 overflow-hidden bg-light d-block"
                  href="{{ route('packages.special.show', ['package' => $packageParam]) }}" aria-label="Open {{ $title }}">
                  @if($hasBanner)
                    <img src="{{ asset('storage/' . $banner) }}" class="pkg-img" alt="{{ $title }}" loading="lazy"
                      style="object-fit:cover;">

                  @elseif(file_exists(public_path('Front_end/assets/img/blog/default-package.jpg')))
                    <img src="{{ $defaultImage }}" class="pkg-img" alt="{{ $title }}" loading="lazy" style="object-fit:cover;">
                  @else
                    <img src="{{ $placeholder }}" class="pkg-img" alt="No image" loading="lazy" style="object-fit:cover;">
                  @endif
                </a>

                <div class="card-body d-flex flex-column">
                  <h5 class="card-title mb-2 pkg-title">
                    <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                      class="title-link text-decoration-none">
                      {{ \Illuminate\Support\Str::limit($title, 60) }}
                    </a>
                  </h5>

                  {{-- Chips / badges (always render the container to preserve height) --}}
                  <div class="chips mb-2">
                    @foreach($showBadges as $b)
                      <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}" class="chip-pill"
                        title="{{ strip_tags($b) }}">
                        <span class="text-truncate">{{ \Illuminate\Support\Str::limit($b, 32) }}</span>
                      </a>
                    @endforeach
                    @if($totalBadges > $maxShow)
                      <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                        class="chip-pill chip-more">+{{ $totalBadges - $maxShow }} more</a>
                    @endif
                  </div>

                  <div class="flex-grow-1"></div>

                  <div class="purchase-meta mb-2">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                      @if($displayPrice)
                        <div class="price-final">₹{{ number_format($displayPrice, 0) }}</div>
                        @if($mrp > 0 && $mrp > $displayPrice)
                          <div class="price-mrp">₹{{ number_format($mrp, 0) }}</div>
                        @endif
                        @if($savePercent > 0)
                          <span class="discount-pill">{{ $savePercent }}% OFF</span>
                        @endif
                      @else
                        <div class="price-final">Contact</div>
                        <small class="text-muted">for price</small>
                      @endif
                    </div>

                    @if($savingAmount > 0)
                      <div class="save-link">You save ₹{{ number_format($savingAmount, 0, '.', ',') }}</div>
                    @endif
                  </div>

                  {{-- ACTIONS --}}
                  <div class="d-flex flex-column flex-sm-row gap-2 btn-foreground">
                    <a href="{{ route('packages.special.show', ['package' => $packageParam]) }}"
                      class="btn btn-view-info btn-sm flex-fill flex-sm-grow-0" onclick="event.stopPropagation();">
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

        @if(method_exists($packages, 'links') && $packages->hasPages())
          <div class="pagination-wrapper d-flex justify-content-center mt-4 mb-4">
            {{ $packages->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
          </div>
        @endif

      </div>
    @else
      <div class="alert alert-info text-center">No special packages available right now.</div>
    @endif
  </section>

  {{-- ================= SPECIAL PACKAGES INFORMATION & FAQS SECTION ================= --}}
  <section id="special-packages-guide" class="sp-guide-section py-5">
    <div class="container">

      {{-- 1. HERO / OVERVIEW CARD --}}
      <div class="sp-hero-card mb-5">
        <div class="sp-hero-badge">
          <i class="fa-solid fa-bullseye me-1"></i> Targeted Diagnostic Bundles in Pune & PCMC
        </div>
        <h2 class="sp-hero-title">
          Special Health Checkup Packages in Pune From ₹599: <span class="sp-gradient-text">Pick the Test Bundle That Fits Your Problem</span>
        </h2>
        <div class="sp-title-divider"></div>
        <div class="sp-hero-text">
          <p>
            A full body checkup is great for a yearly overview. But sometimes you have one clear question. Is my sugar creeping up? Why does this fever keep coming back? Are my vitamin levels low?
          </p>
          <p>
            Wellcare Labs special health checkup packages in Pune answer one question at a time. Each bundle groups the tests your doctor would usually ask for that concern. Prices start at ₹599, and our trained phlebotomist can collect your sample at home.
          </p>
        </div>

        {{-- Action Strip --}}
        <div class="sp-cta-strip">
          <a href="#packages-special" class="sp-btn sp-btn-primary">
            <i class="fa-solid fa-arrow-up me-2"></i> Browse Special Packages
          </a>
          <a href="{{ route('customize.index') }}" class="sp-btn sp-btn-outline">
            <i class="fa-solid fa-sliders me-2"></i> Customise Package Online
          </a>
        </div>
      </div>

      {{-- 2. SPECIAL PACKAGES AT A GLANCE TABLE --}}
      <div class="sp-block mb-5">
        <div class="sp-section-header text-center">
          <span class="sp-pill-tag">Price & Panels Overview</span>
          <h3 class="sp-block-title">Special Packages in Pune at a Glance</h3>
          <div class="sp-title-divider mx-auto"></div>
          <p class="text-muted sp-block-subtitle">Focused test bundles designed to diagnose specific concerns without paying for tests you don't need.</p>
        </div>

        <div class="sp-table-responsive shadow-sm">
          <table class="table sp-table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" style="min-width: 230px;">Package</th>
                <th scope="col" style="min-width: 290px;">Tests shown on the card</th>
                <th scope="col" class="text-center" style="min-width: 110px;">Price</th>
                <th scope="col" style="min-width: 230px;">Best for</th>
                <th scope="col" class="text-center" style="min-width: 120px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-essential-diabetes-panel') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Essential Diabetes Panel</div>
                  </a>
                  <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Diabetes Care</span>
                </td>
                <td class="text-secondary small">
                  CBC, HbA1c, lipid profile, fasting glucose, plus 1 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹599</span>
                </td>
                <td>
                  <span class="sp-best-for">Sugar screening</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-essential-diabetes-panel') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-smart-fever-profile') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Smart Fever Profile</div>
                  </a>
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Fever Care</span>
                </td>
                <td class="text-secondary small">
                  CBC, ESR, SGPT, SGOT, plus 3 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹699</span>
                </td>
                <td>
                  <span class="sp-best-for">Fever with body ache</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-smart-fever-profile') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-essential-vitamin-profile') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Essential Vitamin Profile</div>
                  </a>
                  <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">Vital Nutrients</span>
                </td>
                <td class="text-secondary small">
                  Vitamin B12, vitamin D3
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹799</span>
                </td>
                <td>
                  <span class="sp-best-for">Tiredness, low energy</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-essential-vitamin-profile') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-smart-arthritis-package') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Smart Arthritis Package</div>
                  </a>
                  <span class="badge bg-secondary-subtle text-secondary-emphasis border">Bone & Joint</span>
                </td>
                <td class="text-secondary small">
                  SGPT, SGOT, CRP, calcium, plus 5 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹1,199</span>
                </td>
                <td>
                  <span class="sp-best-for">Joint pain</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-smart-arthritis-package') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-essential-anc-package') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Essential ANC Package</div>
                  </a>
                  <span class="badge bg-pink-subtle text-danger border border-danger-subtle">Maternity Care</span>
                </td>
                <td class="text-secondary small">
                  Blood group and Rh, anti-HCV, CBC, HbA1c, plus 6 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹1,499</span>
                </td>
                <td>
                  <span class="sp-best-for">Pregnancy checks</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-essential-anc-package') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-smart-gym-panel') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Smart Gym Panel</div>
                  </a>
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Fitness & Athlete</span>
                </td>
                <td class="text-secondary small">
                  CBC, ESR, HbA1c, hs-CRP, plus 12 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹1,599</span>
                </td>
                <td>
                  <span class="sp-best-for">Regular gym-goers</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-smart-gym-panel') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-advanced-fever-profile') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Advanced Fever Profile</div>
                  </a>
                  <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">Deeper Fever Screen</span>
                </td>
                <td class="text-secondary small">
                  Bilirubin, CRP, CBC, dengue NS-1 rapid, plus 5 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹1,599</span>
                </td>
                <td>
                  <span class="sp-best-for">Fever that needs a deeper check</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-advanced-fever-profile') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-cancer-detection-profile-male') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Cancer Detection Profile Male</div>
                  </a>
                  <span class="badge bg-dark-subtle text-dark border">Specialized Markers</span>
                </td>
                <td class="text-secondary small">
                  AFP, CA 19.9, CEA, CBC, plus 1 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹1,999</span>
                </td>
                <td>
                  <span class="sp-best-for">Men who want marker screening</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-cancer-detection-profile-male') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-advance-anc-profile') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Advance ANC Profile</div>
                  </a>
                  <span class="badge bg-pink-subtle text-danger border border-danger-subtle">Comprehensive ANC</span>
                </td>
                <td class="text-secondary small">
                  Blood group and Rh, anti-HCV, CBC, HbA1c, plus 6 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹2,999</span>
                </td>
                <td>
                  <span class="sp-best-for">Pregnancy with a wider panel</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-advance-anc-profile') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-advance-gym-panel') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Advance Gym Panel</div>
                  </a>
                  <span class="badge bg-success-subtle text-success border border-success-subtle">Pro Training & Cardio</span>
                </td>
                <td class="text-secondary small">
                  Vitamin D, apolipoprotein A1 and B, CBC, plus 14 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹3,999</span>
                </td>
                <td>
                  <span class="sp-best-for">Serious training and heart-risk markers</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-advance-gym-panel') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
              <tr>
                <td>
                  <a href="{{ route('packages.special.show', 'wellcare-advance-arthritis-package') }}" class="text-decoration-none">
                    <div class="fw-bold sp-pkg-name">Advance Arthritis Package</div>
                  </a>
                  <span class="badge bg-secondary-subtle text-secondary-emphasis border">Advanced Joint Care</span>
                </td>
                <td class="text-secondary small">
                  Vitamin D, anti-CCP, ANA, ASO, plus 10 more
                </td>
                <td class="text-center">
                  <span class="sp-price-tag">₹4,999</span>
                </td>
                <td>
                  <span class="sp-best-for">Long-standing joint symptoms</span>
                </td>
                <td class="text-center">
                  <a href="{{ route('packages.special.show', 'wellcare-advance-arthritis-package') }}" class="btn btn-sm sp-btn-table">Book Plan</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- 3. WHAT ARE SPECIAL HEALTH CHECKUP PACKAGES? --}}
      <div class="sp-block mb-5">
        <div class="row align-items-center g-4">
          <div class="col-lg-7">
            <span class="sp-pill-tag">Targeted Diagnostics</span>
            <h3 class="sp-block-title">What Are Special Health Checkup Packages?</h3>
            <div class="sp-title-divider"></div>
            <p class="sp-lead-text">
              A special package is a short list of tests built around one health need. You skip tests you do not need, so you pay less and get a clearer answer.
            </p>
            <div class="sp-analogy-box p-3 p-md-4 rounded-3 border-start border-4 border-primary bg-light">
              <div class="d-flex align-items-start">
                <i class="fa-solid fa-camera-retro text-primary fs-3 me-3 mt-1"></i>
                <div>
                  <strong class="d-block text-dark mb-1">Wide Lens vs Close-Up Lens:</strong>
                  <span class="text-secondary">
                    Think of a full body checkup as a wide photo of your health. A special package is a close-up of one spot.
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="sp-feature-callout p-4 rounded-4 shadow-sm bg-white border">
              <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-crosshairs text-primary me-2"></i>Why Choose a Targeted Bundle?</h5>
              <ul class="list-unstyled mb-0 sp-checklist">
                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Faster, issue-specific diagnostic answers</li>
                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Pay only for relevant parameters you actually need</li>
                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Doctor-approved panel combinations</li>
                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Hassle-free home blood sample collection in Pune</li>
                <li><i class="fa-solid fa-circle-check text-success me-2"></i> Seamless digital report delivery on your phone</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      {{-- 4. WHICH SPECIAL PACKAGE SHOULD YOU CHOOSE? --}}
      <div class="sp-block mb-5">
        <div class="sp-section-header text-center">
          <span class="sp-pill-tag">Clinical Guide</span>
          <h3 class="sp-block-title">Which Special Package Should You Choose?</h3>
          <div class="sp-title-divider mx-auto"></div>
          <p class="text-muted sp-block-subtitle">Understand what each clinical category covers and how it answers your immediate health concern.</p>
        </div>

        <div class="row g-4">
          {{-- Gym & Fitness --}}
          <div class="col-md-6 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-cat-icon bg-success-subtle text-success mb-3">
                <i class="fa-solid fa-dumbbell"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Gym & Fitness Panels</h5>
              <p class="text-secondary small mb-3">
                Lifting heavy and eating clean does not guarantee healthy numbers. The <strong>Smart Gym Panel</strong> checks blood count, inflammation markers like ESR and hs-CRP, and HbA1c. The <strong>Advance Gym Panel</strong> adds vitamin D and apolipoprotein A1 and B, which show heart risk more closely than basic cholesterol alone.
              </p>
              <a href="#packages-special" class="text-primary fw-bold small text-decoration-none">View Gym Panels <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>

          {{-- Fever Profiles --}}
          <div class="col-md-6 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-cat-icon bg-warning-subtle text-warning-emphasis mb-3">
                <i class="fa-solid fa-temperature-arrow-up"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Fever Profiles</h5>
              <p class="text-secondary small mb-3">
                Fever has many causes. The <strong>Smart Fever Profile</strong> starts with CBC, ESR, and liver enzymes SGPT and SGOT. The <strong>Advanced Fever Profile</strong> adds bilirubin, CRP, and a rapid dengue NS-1 antigen test. Pick Advanced if the fever is high, lasts more than a few days, or dengue is going around.
              </p>
              <a href="{{ route('packages.special.show', 'wellcare-smart-fever-profile') }}" class="text-primary fw-bold small text-decoration-none">View Fever Profiles <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>

          {{-- Diabetes Panel --}}
          <div class="col-md-6 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-cat-icon bg-primary-subtle text-primary mb-3">
                <i class="fa-solid fa-droplet"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Diabetes Panel</h5>
              <p class="text-secondary small mb-3">
                The <strong>Essential Diabetes Panel</strong> at ₹599 pairs HbA1c, which shows your average sugar over about three months, with fasting glucose and a lipid profile. It suits anyone with a family history of diabetes or a desk job.
              </p>
              <a href="{{ route('packages.special.show', 'wellcare-essential-diabetes-panel') }}" class="text-primary fw-bold small text-decoration-none">View Diabetes Panel <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>

          {{-- Vitamin Profile --}}
          <div class="col-md-6 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-cat-icon bg-info-subtle text-info-emphasis mb-3">
                <i class="fa-solid fa-sun"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Vitamin Profile</h5>
              <p class="text-secondary small mb-3">
                Low vitamin D and B12 are common in India, even in sunny cities. The <strong>Essential Vitamin Profile</strong> checks both for ₹799. It helps when you feel tired, have aches, or follow a vegetarian diet.
              </p>
              <a href="{{ route('packages.special.show', 'wellcare-essential-vitamin-profile') }}" class="text-primary fw-bold small text-decoration-none">View Vitamin Profile <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>

          {{-- Cancer Detection Profile --}}
          <div class="col-md-6 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-cat-icon bg-dark-subtle text-dark mb-3">
                <i class="fa-solid fa-ribbon"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">Cancer Detection Profile for Men</h5>
              <p class="text-secondary small mb-3">
                This bundle lists AFP, CA 19.9, and CEA. These are tumour markers. They can rise for reasons other than cancer, such as infection or inflammation. Treat the result as a signal to talk to a doctor, never as a diagnosis.
              </p>
              <a href="{{ route('packages.special.show', 'wellcare-cancer-detection-profile-male') }}" class="text-primary fw-bold small text-decoration-none">View Male Cancer Screen <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>

          {{-- ANC Packages --}}
          <div class="col-md-6 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-cat-icon bg-pink-subtle text-danger mb-3">
                <i class="fa-solid fa-person-pregnant"></i>
              </div>
              <h5 class="fw-bold text-dark mb-2">ANC Packages for Pregnancy</h5>
              <p class="text-secondary small mb-3">
                ANC stands for antenatal care. Both packages list blood group and Rh type, anti-HCV, CBC, and HbA1c. Rh type matters in pregnancy, so it is checked early. Your gynaecologist can tell you whether the Essential or Advance version fits.
              </p>
              <a href="{{ route('packages.special.show', 'wellcare-essential-anc-package') }}" class="text-primary fw-bold small text-decoration-none">View ANC Packages <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </div>

          {{-- Arthritis Packages --}}
          <div class="col-12 col-lg-8">
            <div class="sp-category-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="d-flex align-items-center mb-3">
                <div class="sp-cat-icon bg-danger-subtle text-danger me-3 mb-0">
                  <i class="fa-solid fa-bone"></i>
                </div>
                <div>
                  <h5 class="fw-bold text-dark mb-0">Arthritis Packages (Joint Health)</h5>
                  <span class="small text-muted">Smart (₹1,199) & Advance (₹4,999)</span>
                </div>
              </div>
              <p class="text-secondary small mb-0">
                The <strong>Smart Arthritis Package</strong> lists CRP, calcium, and liver enzymes. The <strong>Advance package</strong> adds anti-CCP, ANA, ASO, and vitamin D. If joint pain has lasted weeks, book a doctor visit along with the test.
              </p>
            </div>
          </div>

          {{-- Quick Callout CTA --}}
          <div class="col-12 col-lg-4">
            <div class="sp-category-card p-4 rounded-4 bg-gradient-blue text-white border-0 h-100 shadow-sm d-flex flex-column justify-content-between">
              <div>
                <span class="badge bg-white text-primary mb-2">Immediate Support</span>
                <h5 class="fw-bold text-white mb-2">Need Guidance?</h5>
                <p class="text-white-50 small mb-3">
                  Unsure which specialized panel your condition requires? Speak directly with our clinical support team.
                </p>
              </div>
              <div class="d-flex flex-wrap gap-2">
                <a href="tel:+919158980898" class="btn btn-sm btn-light fw-bold">Call Now</a>
                <a href="https://wa.me/919158980898?text=Hello%20Wellcare%20Labs!%20I%20need%20help%20choosing%20a%20Special%20Package." target="_blank" class="btn btn-sm btn-outline-light">Chat on WhatsApp</a>
              </div>
            </div>
          </div>
        </div>

        {{-- Mid-section Action Strip --}}
        <div class="sp-cta-strip mt-4 justify-content-center">
          <a href="#packages-special" class="sp-btn sp-btn-primary">
            <i class="fa-solid fa-cart-shopping me-1"></i> Book Now
          </a>
          <a href="tel:+919158980898" class="sp-btn sp-btn-phone">
            <i class="fa-solid fa-phone me-1"></i> Call Us
          </a>
          <a href="https://wa.me/919158980898?text=Hello%20Wellcare%20Labs!%20I%20would%20like%20to%20book%20a%20Special%20Package." target="_blank" class="sp-btn sp-btn-whatsapp">
            <i class="fa-brands fa-whatsapp me-1"></i> Chat on WhatsApp
          </a>
        </div>
      </div>

      {{-- 5. WHEN A FULL BODY CHECKUP IS THE BETTER CHOICE --}}
      <div class="sp-block mb-5">
        <div class="sp-versus-card p-4 p-md-5 rounded-4 bg-white border shadow-sm">
          <div class="row align-items-center g-4">
            <div class="col-lg-8">
              <span class="sp-pill-tag">Smart Decision</span>
              <h4 class="fw-bold text-dark mb-3">When a Full Body Checkup Is the Better Choice</h4>
              <p class="text-secondary mb-3" style="line-height: 1.75;">
                Pick a special package when one worry is on your mind. Pick a full body checkup when you want a yearly overview, or if you are 30 or older and have not tested in a year. Our Smart Full Body Checkup starts at ₹799. If you already have a doctor's list, our Customise Packages page lets you add what you need.
              </p>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ url('/packages') }}" class="btn btn-primary fw-bold rounded-pill px-4">
                  <i class="fa-solid fa-heart-pulse me-1"></i> Explore Full Body Checkups
                </a>
                <a href="{{ route('customize.index') }}" class="btn btn-outline-primary fw-bold rounded-pill px-4">
                  <i class="fa-solid fa-sliders me-1"></i> Customise Packages
                </a>
              </div>
            </div>
            <div class="col-lg-4 text-center">
              <div class="p-3 rounded-4 bg-light border">
                <i class="fa-solid fa-scale-balanced text-primary fs-1 mb-2"></i>
                <div class="fw-bold text-dark">One Concern vs Whole Body</div>
                <div class="small text-muted mt-1">Avoid duplicate billing by selecting the right screening format today.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- 6. HOW TO PREPARE FOR YOUR TEST --}}
      <div class="sp-block mb-5">
        <div class="sp-section-header text-center">
          <span class="sp-pill-tag">Preparation</span>
          <h3 class="sp-block-title">How to Prepare for Your Test</h3>
          <div class="sp-title-divider mx-auto"></div>
          <p class="text-muted sp-block-subtitle">Rules differ by package, so confirm yours at booking. These habits help most:</p>
        </div>

        <div class="row g-3">
          <div class="col-md-6 col-lg">
            <div class="sp-prep-card p-3 rounded-3 bg-white border h-100">
              <div class="sp-prep-num">1</div>
              <h6 class="fw-bold text-dark mb-1">Check Fasting Rule</h6>
              <p class="text-secondary small mb-0">Fast if your package includes fasting glucose or a lipid profile. Drink only plain water.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg">
            <div class="sp-prep-card p-3 rounded-3 bg-white border h-100">
              <div class="sp-prep-num">2</div>
              <h6 class="fw-bold text-dark mb-1">Skip Alcohol & Workouts</h6>
              <p class="text-secondary small mb-0">Skip alcohol and heavy workouts the day before your sample collection.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg">
            <div class="sp-prep-card p-3 rounded-3 bg-white border h-100">
              <div class="sp-prep-num">3</div>
              <h6 class="fw-bold text-dark mb-1">Inform Medication</h6>
              <p class="text-secondary small mb-0">Tell the phlebotomist about every medicine and supplement you take regularly.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg">
            <div class="sp-prep-card p-3 rounded-3 bg-white border h-100">
              <div class="sp-prep-num">4</div>
              <h6 class="fw-bold text-dark mb-1">Keep Prior Reports Ready</h6>
              <p class="text-secondary small mb-0">Keep older reports ready for comparison with your new test values.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg">
            <div class="sp-prep-card p-3 rounded-3 bg-white border h-100">
              <div class="sp-prep-num">5</div>
              <h6 class="fw-bold text-dark mb-1">Period & Urine Timing</h6>
              <p class="text-secondary small mb-0">If you are on your period, ask us before booking a urine test.</p>
            </div>
          </div>
        </div>
      </div>

      {{-- 7. HOW HOME SAMPLE COLLECTION WORKS IN PUNE --}}
      <div class="sp-block mb-5">
        <div class="sp-section-header text-center">
          <span class="sp-pill-tag">Doorstep Convenience</span>
          <h3 class="sp-block-title">How Home Sample Collection Works in Pune</h3>
          <div class="sp-title-divider mx-auto"></div>
          <p class="text-muted sp-block-subtitle">Looking for health tests near you? We come straight to your door.</p>
        </div>

        <div class="row g-3 mb-4">
          <div class="col-12 col-md-4 col-lg sp-step-col">
            <div class="sp-step-card p-3 rounded-4 bg-white border text-center h-100">
              <div class="sp-step-circle">1</div>
              <h6 class="fw-bold text-dark mb-2">Pick & Book</h6>
              <p class="text-secondary small mb-0">Pick a package and tap Add to Cart, or call us directly.</p>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg sp-step-col">
            <div class="sp-step-card p-3 rounded-4 bg-white border text-center h-100">
              <div class="sp-step-circle">2</div>
              <h6 class="fw-bold text-dark mb-2">Choose Slot</h6>
              <p class="text-secondary small mb-0">Choose a time slot. Early morning works best for fasting tests.</p>
            </div>
          </div>
          <div class="col-12 col-md-4 col-lg sp-step-col">
            <div class="sp-step-card p-3 rounded-4 bg-white border text-center h-100">
              <div class="sp-step-circle">3</div>
              <h6 class="fw-bold text-dark mb-2">Phlebotomist Visit</h6>
              <p class="text-secondary small mb-0">Our phlebotomist arrives, follows hygiene steps, and collects your samples.</p>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg sp-step-col">
            <div class="sp-step-card p-3 rounded-4 bg-white border text-center h-100">
              <div class="sp-step-circle">4</div>
              <h6 class="fw-bold text-dark mb-2">Lab Testing</h6>
              <p class="text-secondary small mb-0">Your samples go to the certified lab for high-precision testing.</p>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg sp-step-col">
            <div class="sp-step-card p-3 rounded-4 bg-white border text-center h-100">
              <div class="sp-step-circle">5</div>
              <h6 class="fw-bold text-dark mb-2">Digital Reports</h6>
              <p class="text-secondary small mb-0">You receive digital reports to read and share with your doctor.</p>
            </div>
          </div>
        </div>

        {{-- Coverage area box --}}
        <div class="sp-coverage-box p-4 rounded-4 bg-light border">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
              <strong class="text-dark d-block mb-1"><i class="fa-solid fa-location-dot text-primary me-2"></i>Pune & PCMC Service Areas:</strong>
              <span class="text-secondary small">
                We serve Pune and PCMC, including Pimple Saudagar, Pimpri, Wakad, Baner, Aundh, Hinjawadi, Kharadi, and Kothrud.
              </span>
            </div>
            <a href="tel:+919158980898" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold text-nowrap">
              <i class="fa-solid fa-phone me-1"></i> Call +91 915 898 0898
            </a>
          </div>
        </div>
      </div>

      {{-- 8. WHY BOOK SPECIAL PACKAGES WITH WELLCARE LABS? --}}
      <div class="sp-block mb-5">
        <div class="sp-section-header text-center">
          <span class="sp-pill-tag">Why Wellcare</span>
          <h3 class="sp-block-title">Why Book Special Packages With Wellcare Labs?</h3>
          <div class="sp-title-divider mx-auto"></div>
          <p class="text-muted sp-block-subtitle">Reliable pathology expertise, verified clinical quality, and transparent pricing in Pune.</p>
        </div>

        <div class="row g-3">
          <div class="col-md-6 col-lg-4">
            <div class="sp-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-trust-icon text-primary mb-3"><i class="fa-solid fa-certificate fs-2"></i></div>
              <h6 class="fw-bold text-dark">ISO 9001:2015 Certified</h6>
              <p class="text-secondary small mb-0">Certified quality management system ensuring stringent clinical standards and hygiene protocols.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="sp-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-trust-icon text-success mb-3"><i class="fa-solid fa-clock-rotate-left fs-2"></i></div>
              <h6 class="fw-bold text-dark">8+ Years in Diagnostics</h6>
              <p class="text-secondary small mb-0">Over 8 years of dependable pathology experience serving thousands of patients and doctors across Pune.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="sp-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-trust-icon text-info mb-3"><i class="fa-solid fa-user-doctor fs-2"></i></div>
              <h6 class="fw-bold text-dark">Pathologists & Trained Phlebotomists</h6>
              <p class="text-secondary small mb-0">Qualified pathologists and compassionate phlebotomists handle your blood samples with utmost care.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="sp-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-trust-icon text-danger mb-3"><i class="fa-solid fa-location-dot fs-2"></i></div>
              <h6 class="fw-bold text-dark">A Real, Verified Address</h6>
              <p class="text-secondary small mb-0">Shop 113, First Floor, A-Wing, Sai Vision Mall, Pimple Saudagar, Pune 411027.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="sp-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-trust-icon text-warning mb-3"><i class="fa-solid fa-tag fs-2"></i></div>
              <h6 class="fw-bold text-dark">Clear, Upfront Pricing</h6>
              <p class="text-secondary small mb-0">Clear prices shown on every package card with zero hidden charges or surprise fees.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="sp-trust-card p-4 rounded-4 bg-white border h-100 shadow-sm">
              <div class="sp-trust-icon text-secondary mb-3"><i class="fa-solid fa-hand-holding-medical fs-2"></i></div>
              <h6 class="fw-bold text-dark">Honest Clinical Support</h6>
              <p class="text-secondary small mb-0">One honest note: These tests screen and support your doctor. They do not diagnose on their own, and they cannot replace medical advice.</p>
            </div>
          </div>
        </div>
      </div>

      {{-- 9. FREQUENTLY ASKED QUESTIONS --}}
      <div class="sp-block mb-5" id="sp-faqs">
        <div class="sp-section-header text-center">
          <span class="sp-pill-tag">Got Questions?</span>
          <h3 class="sp-block-title">Frequently Asked Questions</h3>
          <div class="sp-title-divider mx-auto"></div>
          <p class="text-muted sp-block-subtitle">Clear answers about special packages, fasting, report timing, and test selection.</p>
        </div>

        <div class="sp-faq-accordion">
          {{-- FAQ 1 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">1</span>
              <span class="sp-faq-qtext">What are special health checkup packages?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Special packages are focused test bundles for one need, such as diabetes, fever, vitamins, pregnancy, arthritis, or gym fitness. They cost less than a full body checkup because they test fewer areas.</p>
            </div>
          </div>

          {{-- FAQ 2 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">2</span>
              <span class="sp-faq-qtext">How much do special health packages cost in Pune?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>At Wellcare Labs, special packages run from ₹599 to ₹4,999. The Essential Diabetes Panel is ₹599, and the Advanced Arthritis Package is ₹4,999. Check each card for the latest price.</p>
            </div>
          </div>

          {{-- FAQ 3 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">3</span>
              <span class="sp-faq-qtext">Which package is best for diabetes screening?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>The Essential Diabetes Panel at ₹599 is the starting point. The card shows CBC, HbA1c, lipid profile, and fasting glucose, plus one more test. Your doctor can advise if you need extra tests.</p>
            </div>
          </div>

          {{-- FAQ 4 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">4</span>
              <span class="sp-faq-qtext">What is inside the gym panels?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>The Smart Gym Panel (₹1,599) lists CBC, ESR, HbA1c, and hs-CRP, plus more. The Advance Gym Panel (₹3,999) adds vitamin D and apolipoprotein A1 and B. Both suit regular gym-goers.</p>
            </div>
          </div>

          {{-- FAQ 5 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">5</span>
              <span class="sp-faq-qtext">What is the difference between the Smart and Advanced Fever Profiles?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>The Smart plan (₹699) covers CBC, ESR, SGPT, and SGOT, plus three more tests. The Advanced plan (₹1,599) adds bilirubin, CRP, and a rapid dengue NS-1 antigen test, among others.</p>
            </div>
          </div>

          {{-- FAQ 6 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">6</span>
              <span class="sp-faq-qtext">What does the ANC package cover?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>ANC means antenatal care, the checks done during pregnancy. The cards list blood group and Rh type, anti-HCV, CBC, and HbA1c, plus six more tests. Ask your gynaecologist which version you need.</p>
            </div>
          </div>

          {{-- FAQ 7 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">7</span>
              <span class="sp-faq-qtext">Can the cancer detection profile diagnose cancer?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>No. It measures tumour markers such as AFP, CA 19.9, and CEA. These can rise for reasons other than cancer. A high result means you need a doctor's review, not that you have cancer.</p>
            </div>
          </div>

          {{-- FAQ 8 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">8</span>
              <span class="sp-faq-qtext">Who should take an arthritis package?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Consider it if you have long-term joint pain, stiffness, or swelling. The Advance package lists anti-CCP, ANA, and ASO along with vitamin D. A doctor should read the results.</p>
            </div>
          </div>

          {{-- FAQ 9 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">9</span>
              <span class="sp-faq-qtext">Do I need to fast before a special package?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>It depends on the package. Tests like fasting glucose and lipid profile need fasting, while many fever tests do not. Our team confirms the exact rule when you book.</p>
            </div>
          </div>

          {{-- FAQ 10 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">10</span>
              <span class="sp-faq-qtext">Is home sample collection available in Pune?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Yes. We collect samples across Pune and PCMC, including Pimple Saudagar, Wakad, Baner, Aundh, Hinjawadi, and Kharadi. Share your pin code when you book so we can confirm your area.</p>
            </div>
          </div>

          {{-- FAQ 11 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">11</span>
              <span class="sp-faq-qtext">Do I need a doctor's prescription?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Not for most screening packages. If you have symptoms, are pregnant, or already take treatment, talk to your doctor first. They can tell you which package matches your case.</p>
            </div>
          </div>

          {{-- FAQ 12 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">12</span>
              <span class="sp-faq-qtext">How soon will I get my reports?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Report time depends on the tests in your package. Our team shares the expected time when you book. You receive reports digitally, so you can open and share them with your doctor.</p>
            </div>
          </div>

          {{-- FAQ 13 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">13</span>
              <span class="sp-faq-qtext">Can I add more tests to a special package?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Yes. Use our <a href="{{ route('customize.index') }}" class="text-primary fw-bold">Customise Packages</a> page or ask our team to add tests. This helps when a ready-made bundle misses one test your doctor asked for.</p>
            </div>
          </div>

          {{-- FAQ 14 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">14</span>
              <span class="sp-faq-qtext">Should I take a special package or a full body checkup?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Choose a special package when you have one clear concern. Choose a full body checkup for a yearly overview of blood, liver, kidneys, thyroid, and vitamins. Avoid paying twice for the same tests.</p>
            </div>
          </div>

          {{-- FAQ 15 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">15</span>
              <span class="sp-faq-qtext">What should I do if a result looks abnormal?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Stay calm. One abnormal value does not always mean disease. Food, stress, water intake, and testing methods can all shift results. Share the report with your doctor, who reads it along with your health history.</p>
            </div>
          </div>

          {{-- FAQ 16 --}}
          <div class="sp-faq-item">
            <button class="sp-faq-question" type="button" aria-expanded="false">
              <span class="sp-faq-num">16</span>
              <span class="sp-faq-qtext">Is Wellcare Labs reliable and certified?</span>
              <span class="sp-faq-arrow"><i class="fa-solid fa-chevron-down"></i></span>
            </button>
            <div class="sp-faq-answer">
              <p>Wellcare Labs holds ISO 9001:2015 certification for its quality management system and has over 8 years in diagnostics. Our team includes pathologists and trained phlebotomists, and our centre is at Sai Vision Mall, Pimple Saudagar.</p>
            </div>
          </div>
        </div>

      </div>

      {{-- 10. BOOK YOUR SPECIAL HEALTH PACKAGE TODAY (FINAL CTA BANNER) --}}
      <div class="sp-final-banner p-4 p-md-5 rounded-4 text-center text-white shadow">
        <h3 class="fw-bold mb-3 text-white">Book Your Special Health Package Today</h3>
        <p class="sp-final-desc mx-auto mb-4" style="max-width: 780px; opacity: 0.95; font-size: 1.08rem; line-height: 1.7;">
          Pick your package above and tap Add to Cart with doorstep sample collection across Pune & PCMC. Need extra tests? Customise your own package online in minutes.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
          <a href="#packages-special" class="sp-btn sp-btn-light">
            <i class="fa-solid fa-arrow-up text-primary me-2"></i> Browse Packages Above
          </a>
          <a href="{{ route('customize.index') }}" class="sp-btn sp-btn-outline-light">
            <i class="fa-solid fa-sliders me-2"></i> Customise Package Online
          </a>
        </div>
      </div>

    </div>
  </section>
@endsection

@section('scripts')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    /* --- Card look --- */
    #packages-special .pretty-card {
      background: #fff;
      border: 0;
      border-radius: 20px;
      box-shadow: 0 10px 20px rgba(10, 38, 64, .06), 0 3px 8px rgba(10, 38, 64, .04);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    /* ================= SPECIAL PACKAGES – ANIMATIONS ================= */

    /* Initial hidden state */
    #packages-special .pretty-card {
      opacity: 0;
      transform: translateY(26px);
      transition:
        opacity 520ms ease,
        transform 520ms cubic-bezier(.2, .8, .2, 1),
        box-shadow 220ms cubic-bezier(.2, .8, .2, 1);
    }

    /* When visible */
    #packages-special .pretty-card.is-visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Hover lift – card only */
    #packages-special .pretty-card:hover {
      transform: translateY(-6px);
      box-shadow:
        0 20px 45px rgba(10, 38, 64, .16),
        0 6px 16px rgba(10, 38, 64, .08);
    }

    /* Accessibility */
    @media (prefers-reduced-motion: reduce) {
      #packages-special .pretty-card {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
      }
    }

    #packages-special .card-body {
      padding: 18px;
      display: flex;
      flex-direction: column;
      flex: 1 1 auto;
    }

    /* --- Grid: center every row; make columns equal-height containers --- */
    #packages-special .row.justify-content-center {
      justify-content: center;
    }

    #packages-special .row>[class*="col"] {
      display: flex;
    }

    #packages-special .row.g-4>[class*="col-"] {
      margin-bottom: 1.5rem;
    }

    /* --- Uniform image --- */

    /* --- Title clamp --- */
    #packages-special .pkg-title {
      margin: 0;
      font-size: 1.03rem;
      font-weight: 700;
      min-height: calc(1.3em * 2);
    }

    #packages-special .title-link {
      color: #ff2b6d;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      overflow: hidden;
      -webkit-line-clamp: 2;
    }

    #packages-special .title-link:hover {
      opacity: .9;
    }

    /* --- Chips (always reserve space) --- */
    #packages-special .chips {
      min-height: 32px;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    #packages-special .chips:empty::before {
      content: "";
      display: block;
      height: 32px;
    }

    #packages-special .chip-pill {
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

    #packages-special .chip-pill {
      max-width: 100%;
    }

    /* #packages-special .chip-pill .text-truncate{ display:inline-block; max-width:170px; } */
    @media (max-width:576px) {
      #packages-special .chip-pill .text-truncate {
        max-width: 130px;
      }
    }

    #packages-special .chip-more {
      background: #eef5ff;
    }

    /* --- Price block (reserve consistent space) --- */
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

    #packages-special .purchase-meta {
      min-height: 48px;
    }

    #packages-special .purchase-meta .d-flex.align-items-center {
      gap: 10px;
    }

    #packages-special .save-link {
      color: #0a66d6;
      font-weight: 600;
      font-size: .9rem;
    }

    /* --- Buttons layer --- */
    #packages-special .btn-foreground,
    #packages-special .btn-foreground .btn {
      position: relative;
      z-index: 5;
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    @media (min-width:577px) {
      #packages-special .btn-foreground {
        gap: 16px;
      }
    }

    /* --- View Info button (compact) --- */
    #packages-special .btn-view-info {
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

    #packages-special .btn-view-info i {
      font-size: .8rem;
    }

    #packages-special .btn-view-info:hover {
      background: #f5f9ff;
    }

    /* --- Cart buttons --- */
    #packages-special .btn-action-cart {
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

    /* Soft pink add-to-cart (as requested) */
    #packages-special .btn-add-cart.btn-action-cart {
      background: #f0c2c2;
      color: #2b4a66;
      border: 1px solid rgba(14, 63, 108, .12);
      transition: background .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
    }

    #packages-special .btn-add-cart.btn-action-cart:hover {
      background: #eef4ff;
      border-color: rgba(14, 63, 108, .25);
      color: #173b5f;
    }

    #packages-special .btn-add-cart.btn-action-cart:active {
      background: #eef4ff;
    }

    /* Go to cart (green) */
    #packages-special .btn-go-cart.btn-action-cart {
      background: #9dd24a !important;
      color: #fff !important;
      border: 0 !important;
      box-shadow: 0 6px 12px rgba(157, 210, 74, .25) !important;
    }

    #packages-special .btn-go-cart.btn-action-cart:hover {
      background: #8ac83d !important;
    }

    #packages-special .btn-go-cart.btn-action-cart:active {
      background: #7ebd33 !important;
    }

    #packages-special .btn-action-cart.is-adding {
      pointer-events: none;
      opacity: .9;
    }

    #packages-special .btn-view-info i,
    #packages-special .btn-action-cart i {
      font-size: 1.05em;
    }

    /* Bottom spacing for each card (requested earlier) */
    #packages-special .pretty-card {
      margin-bottom: 24px;
    }

    /* Mobile compact tweaks */
    @media (max-width:576px) {

      #packages-special .btn-view-info,
      #packages-special .btn-action-cart {
        padding: .55rem .9rem;
        font-size: .92rem;
        min-height: 40px;
        min-width: 120px;
      }

      #packages-special .btn-foreground {
        gap: .5rem;
      }
    }

    /* Show horizontal scrollbar */


    /* ================= SAME CARD IMAGE EVERYWHERE ================= */



    /* Image always fills card */

    /* ================= SAME CARD IMAGE EVERYWHERE ================= */

    #packages-special .img-fixed {
      position: relative;
      height: 240px;
      /* SAME height mobile + desktop */
      width: 100%;
      overflow: hidden;
    }

    /* Image always fills card */
    #packages-special .pkg-img {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      display: block;
      object-fit: cover;
      /* 🔥 SAME ON ALL SCREENS */
      object-position: center;
    }

    #packages-special .pretty-card {
      max-width: 340px;
      /* try 260px / 280px / 300px */
      margin-left: auto;
      margin-right: auto;
    }

    /* ================= MOBILE HORIZONTAL SCROLL FOR SPECIAL PACKAGES ================= */
    @media (max-width: 767px) {
      #packages-special .container-fluid {
        padding-left: 10px !important;
        padding-right: 10px !important;
        overflow: hidden !important;
      }

      #packages-special .tablet-grid,
      #packages-special .row.tablet-grid {
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

      #packages-special .tablet-grid > [class*="col"] {
        flex: 0 0 285px !important;
        max-width: 285px !important;
        min-width: 270px !important;
        width: 285px !important;
        scroll-snap-align: start !important;
        margin-bottom: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      #packages-special .pretty-card {
        max-width: 100% !important;
        width: 100% !important;
        margin-left: unset !important;
        margin-right: unset !important;
      }
    }


    /* ================= TABLET: FORCE 2 CARDS GRID ================= */

    /* All tablets: iPad Mini, Air, Pro, Surface */
    @media (min-width: 768px) and (max-width: 1024px) {
      #packages-special .tablet-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(280px, 1fr));
        gap: 28px;
        justify-content: center;
      }

      /* Disable Bootstrap column sizing */
      #packages-special .tablet-grid>[class*="col"] {
        flex: unset !important;
        max-width: unset !important;
        width: auto !important;
      }
    }
  </style>

  <script>
    /* ---------- CART: Add → Go to Cart (same behavior as Home) ---------- */
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
        btn.innerHTML = '<i class="fa fa-shopping-cart me-1" aria-hidden="true"></i><span class="btn-text">Go to Cart</span>';
        btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); window.location.href = CART_URL; };
      }

      function setAddingState(btn) {
        if (!btn) return;
        btn.classList.add('is-adding');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding…';
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
        btn.innerHTML = '<i class="fa fa-cart-plus me-1" aria-hidden="true"></i><span class="btn-text">Add to Cart</span>';
        btn.onclick = (e) => { e?.preventDefault?.(); e?.stopPropagation?.(); addToCart(e, 'package', id); };
      }

      async function refreshSpecialCartButtons() {
        try {
          const res = await fetch(CART_ITEMS_URL + '?_=' + Date.now(), {
            headers: { 'Accept': 'application/json', 'Cache-Control': 'no-cache', 'X-Requested-With': 'XMLHttpRequest' },
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
            if (ids.has(id)) convertToGoToCart(btn); else convertToAdd(btn, id);
          });

          if (data && data.count !== undefined) {
            const badge = document.querySelector('#cart-count-badge');
            if (badge) badge.textContent = data.count;
          }
        } catch (e) { console.warn('refreshSpecialCartButtons failed:', e); }
      }

      if (typeof window.addToCart !== 'function') {
        window.addToCart = async function (event, type, id) {
          event?.preventDefault?.();
          event?.stopPropagation?.();

          const btn = (event && event.target) ? event.target.closest('button') : document.querySelector('#cart-btn-' + id);
          if (!btn) return;
          if (btn.classList.contains('btn-go-cart')) { window.location.href = CART_URL; return; }

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
              body: JSON.stringify({ item_type: type, item_id: id, quantity: 1 }),
              cache: 'no-store',
              credentials: 'same-origin'
            });

            const ct = res.headers.get('content-type') || '';
            const isJson = ct.includes('application/json');
            const data = isJson ? await res.json().catch(() => ({})) : {};

            if (!res.ok) {
              if (res.status === 401) { window.location.href = '/login'; return; }
              if (res.status === 419) { alert('Session expired. Please refresh and try again.'); unsetAddingState(btn, original); return; }
              alert((data && (data.message || data.error)) ? (data.message || data.error) : 'Failed to add to cart.');
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
              alert((data && (data.message || data.error)) ? (data.message || data.error) : 'Failed to add to cart.');
              unsetAddingState(btn, original);
            }
          } catch (err) {
            console.error('addToCart error', err);
            alert('Something went wrong while adding to cart.');
            unsetAddingState(btn, original);
          }
        };
      }

      document.addEventListener('DOMContentLoaded', refreshSpecialCartButtons, { once: true });
      window.addEventListener('pageshow', refreshSpecialCartButtons);
    })();


    document.addEventListener('DOMContentLoaded', () => {
      const cards = document.querySelectorAll('#packages-special .pretty-card');

      if (!('IntersectionObserver' in window)) {
        cards.forEach(card => card.classList.add('is-visible'));
        return;
      }

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const card = entry.target;

            // stagger delay
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

    /* ================= SPECIAL PACKAGES FAQ ACCORDION TOGGLE ================= */
    document.addEventListener('DOMContentLoaded', function () {
      const faqItems = document.querySelectorAll('.sp-faq-item');
      faqItems.forEach(item => {
        const questionBtn = item.querySelector('.sp-faq-question');
        if (!questionBtn) return;
        questionBtn.addEventListener('click', function () {
          const isOpen = item.classList.contains('active');
          // close all others
          faqItems.forEach(other => {
            if (other !== item) {
              other.classList.remove('active');
              const otherBtn = other.querySelector('.sp-faq-question');
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

      // Smooth scroll for internal anchor links (like #packages-special)
      document.querySelectorAll('a[href="#packages-special"]').forEach(link => {
        link.addEventListener('click', function (e) {
          const target = document.getElementById('packages-special');
          if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });
    });
  </script>

  {{-- ================= SPECIAL PACKAGES GUIDE STYLES ================= --}}
  <style>
    .sp-guide-section {
      background: #ffffff;
      color: #1e293b;
      font-family: inherit;
    }

    .sp-guide-section > .container {
      max-width: 1160px;
      margin: 0 auto;
    }

    .sp-gradient-text {
      background: linear-gradient(135deg, #0d6efd 0%, #00c6ff 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .sp-title-divider {
      width: 70px;
      height: 4px;
      border-radius: 2px;
      background: linear-gradient(90deg, #0047ff, #00ccff);
      margin: 12px 0 20px;
    }

    .sp-pill-tag {
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
    .sp-hero-card {
      background: linear-gradient(180deg, #f8faff 0%, #ffffff 100%);
      border: 1px solid #e2e8f0;
      border-radius: 24px;
      padding: 36px 32px;
      box-shadow: 0 10px 30px rgba(10, 37, 64, 0.04);
    }

    .sp-hero-badge {
      display: inline-flex;
      align-items: center;
      background: #eff6ff;
      color: #1d4ed8;
      font-size: 0.85rem;
      font-weight: 700;
      padding: 6px 14px;
      border-radius: 999px;
      margin-bottom: 16px;
      border: 1px solid rgba(29, 78, 216, 0.2);
    }

    .sp-hero-title {
      font-size: 2.1rem;
      font-weight: 800;
      color: #0a2540;
      line-height: 1.3;
    }

    .sp-hero-text p {
      font-size: 1.08rem;
      line-height: 1.75;
      color: #475569;
      margin-bottom: 12px;
    }

    /* --- CTA Buttons Strip --- */
    .sp-cta-strip {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 24px;
    }

    .sp-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 22px;
      font-size: 0.95rem;
      font-weight: 700;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.25s ease;
      cursor: pointer;
      border: none;
    }

    .sp-btn-primary {
      background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
      color: #ffffff !important;
      box-shadow: 0 4px 14px rgba(13, 110, 253, 0.3);
    }

    .sp-btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
      color: #ffffff !important;
    }

    .sp-btn-phone {
      background: #f1f5f9;
      color: #0a2540 !important;
      border: 1px solid #cbd5e1;
    }

    .sp-btn-phone:hover {
      background: #e2e8f0;
      color: #0a2540 !important;
      transform: translateY(-2px);
    }

    .sp-btn-whatsapp {
      background: #25D366;
      color: #ffffff !important;
      box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
    }

    .sp-btn-whatsapp:hover {
      background: #20bd5a;
      color: #ffffff !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    }

    .sp-btn-light {
      background: #ffffff;
      color: #0a2540 !important;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
    }

    .sp-btn-light:hover {
      background: #f8fafc;
      transform: translateY(-2px);
    }

    .sp-btn-outline {
      background: #ffffff;
      color: #0d6efd !important;
      border: 1.5px solid #0d6efd;
    }

    .sp-btn-outline:hover {
      background: #eff6ff;
      color: #0056b3 !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }

    .sp-btn-outline-light {
      background: rgba(255, 255, 255, 0.15);
      color: #ffffff !important;
      border: 1.5px solid rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(4px);
    }

    .sp-btn-outline-light:hover {
      background: rgba(255, 255, 255, 0.3);
      color: #ffffff !important;
      transform: translateY(-2px);
    }

    .sp-btn-phone-light {
      background: rgba(255, 255, 255, 0.15);
      color: #ffffff !important;
      border: 1px solid rgba(255, 255, 255, 0.4);
      backdrop-filter: blur(4px);
    }

    .sp-btn-phone-light:hover {
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff !important;
      transform: translateY(-2px);
    }

    /* --- Section Titles --- */
    .sp-block-title {
      font-size: 1.85rem;
      font-weight: 800;
      color: #0a2540;
      margin-bottom: 6px;
    }

    .sp-block-subtitle {
      font-size: 1.05rem;
      margin-bottom: 24px;
    }

    /* --- Tables --- */
    .sp-table-responsive {
      border-radius: 16px;
      overflow-x: auto;
      border: 1px solid #e2e8f0;
      background: #ffffff;
    }

    .sp-table {
      margin-bottom: 0;
      border-collapse: separate;
      border-spacing: 0;
    }

    .sp-table thead th {
      background: #f8fafc;
      color: #0a2540;
      font-weight: 700;
      font-size: 0.92rem;
      padding: 16px 20px;
      border-bottom: 2px solid #e2e8f0;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .sp-table tbody td {
      padding: 16px 20px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 0.96rem;
      color: #334155;
    }

    .sp-table tbody tr:hover td {
      background: #fbfdff;
    }

    .sp-pkg-name {
      font-size: 1.02rem;
      color: #0a2540;
    }

    .sp-price-tag {
      font-weight: 800;
      font-size: 1.15rem;
      color: #10b981;
    }

    .sp-best-for {
      font-size: 0.93rem;
      color: #475569;
      font-weight: 600;
    }

    .sp-btn-table {
      background: #0d6efd;
      color: #ffffff !important;
      font-weight: 700;
      border-radius: 8px;
      padding: 6px 14px;
      font-size: 0.85rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .sp-btn-table:hover {
      background: #0047ff;
      transform: translateY(-1px);
    }

    /* --- Lead text & analogy --- */
    .sp-lead-text {
      font-size: 1.12rem;
      line-height: 1.8;
      color: #334155;
      margin-bottom: 20px;
    }

    .sp-checklist li {
      padding: 8px 0;
      font-size: 0.97rem;
      color: #334155;
      display: flex;
      align-items: center;
      border-bottom: 1px dashed #f1f5f9;
    }
    .sp-checklist li:last-child {
      border-bottom: none;
    }

    /* --- Category Cards --- */
    .sp-category-card {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .sp-category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 25px rgba(10, 37, 64, 0.08) !important;
    }

    .sp-cat-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.35rem;
    }

    .bg-gradient-blue {
      background: linear-gradient(135deg, #0a2540 0%, #0d6efd 100%);
    }

    /* --- Preparation Cards --- */
    .sp-prep-card {
      position: relative;
      padding-left: 56px !important;
      transition: all 0.2s ease;
    }
    .sp-prep-card:hover {
      border-color: #0d6efd !important;
      box-shadow: 0 6px 16px rgba(13, 110, 253, 0.06);
    }
    .sp-prep-num {
      position: absolute;
      left: 16px;
      top: 16px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #eaf2ff;
      color: #0d6efd;
      font-weight: 800;
      font-size: 0.95rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* --- Step Cards --- */
    .sp-step-card {
      transition: all 0.25s ease;
    }
    .sp-step-card:hover {
      border-color: #0d6efd !important;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(10, 37, 64, 0.06);
    }
    .sp-step-circle {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: linear-gradient(135deg, #0d6efd 0%, #00c6ff 100%);
      color: #ffffff;
      font-weight: 800;
      font-size: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 14px;
      box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
    }

    /* --- Trust Cards --- */
    .sp-trust-card {
      transition: all 0.25s ease;
    }
    .sp-trust-card:hover {
      transform: translateY(-4px);
      border-color: #cbd5e1 !important;
      box-shadow: 0 10px 24px rgba(10, 37, 64, 0.06) !important;
    }

    /* --- FAQ Accordion --- */
    .sp-faq-accordion {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .sp-faq-item {
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      background: #ffffff;
      overflow: hidden;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .sp-faq-item:hover {
      border-color: #cbd5e1;
      box-shadow: 0 4px 12px rgba(10, 37, 64, 0.04);
    }

    .sp-faq-item.active {
      border-color: #0d6efd;
      box-shadow: 0 6px 18px rgba(13, 110, 253, 0.08);
    }

    .sp-faq-question {
      width: 100%;
      text-align: left;
      background: none;
      border: none;
      padding: 18px 22px;
      display: flex;
      align-items: center;
      gap: 16px;
      cursor: pointer;
      font-size: 1.04rem;
      font-weight: 700;
      color: #0a2540;
      transition: background-color 0.2s ease;
    }

    .sp-faq-question:hover {
      background-color: #f8faff;
    }

    .sp-faq-num {
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

    .sp-faq-item.active .sp-faq-num {
      background: #0d6efd;
      color: #ffffff;
    }

    .sp-faq-qtext {
      flex: 1;
      margin: 0;
      line-height: 1.45;
    }

    .sp-faq-arrow {
      font-size: 0.9rem;
      color: #64748b;
      transition: transform 0.3s ease;
      flex-shrink: 0;
    }

    .sp-faq-item.active .sp-faq-arrow {
      transform: rotate(180deg);
      color: #0d6efd;
    }

    .sp-faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s cubic-bezier(0, 1, 0, 1), padding 0.35s ease;
      padding: 0 22px;
      color: #475569;
      font-size: 0.98rem;
      line-height: 1.75;
      background: #ffffff;
    }

    .sp-faq-item.active .sp-faq-answer {
      max-height: 600px;
      padding: 4px 22px 20px 70px;
      transition: max-height 0.4s ease-in, padding 0.35s ease;
    }

    .sp-faq-answer p {
      margin: 0;
    }

    /* --- Final Banner --- */
    .sp-final-banner {
      background: linear-gradient(135deg, #0a2540 0%, #0d6efd 100%);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    @media (max-width: 768px) {
      .sp-hero-card {
        padding: 24px 18px;
      }
      .sp-hero-title {
        font-size: 1.65rem;
      }
      .sp-block-title {
        font-size: 1.45rem;
      }
      .sp-faq-question {
        padding: 14px 16px;
        gap: 12px;
        font-size: 0.97rem;
      }
      .sp-faq-item.active .sp-faq-answer {
        padding: 4px 16px 16px 16px;
      }
      .sp-cta-strip {
        flex-direction: column;
      }
      .sp-cta-strip .sp-btn {
        width: 100%;
      }
    }
  </style>
@endsection