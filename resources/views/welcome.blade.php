<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>WellCare Lab - Home</title>

  <!-- local CSS assets (or CDN if you prefer) -->
  <link rel="stylesheet" href="{{ asset('Front_end/assets/css/maicons.css') }}">
  <link rel="stylesheet" href="{{ asset('Front_end/assets/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('Front_end/assets/vendor/owl-carousel/css/owl.carousel.css') }}">
  <link rel="stylesheet" href="{{ asset('Front_end/assets/vendor/animate/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('Front_end/assets/css/theme.css') }}">
  <link rel="stylesheet" href="{{ asset('Front_end/assets/css/style.css') }}">

  <style>
    /* safety for fixed header */
    body { padding-top: 72px; }
    .hero { min-height: 220px; display:flex; align-items:center; }
    .package-banner { max-height:140px; object-fit:cover; width:100%; }
    .package-card { transition: transform .12s ease; }
    .package-card:hover { transform: translateY(-4px); box-shadow: 0 6px 20px rgba(0,0,0,.06); }
    .selected-badge { min-width:220px; display:inline-block; }
    .card-service .icon-box { font-size:28px; }
    @media (max-width:576px){
      body { padding-top: 64px; }
      .package-banner { max-height:120px; }
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('assets/images/gallery/welcare_labs.png') }}" alt="WellCare Lab" height="44" class="me-2">
        <span class="h6 mb-0">WellCare Lab</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
              aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#packages">Packages</a></li>
          <li class="nav-item"><a class="nav-link" href="#appointment-form">Book</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>

  {{-- HERO --}}
  <section class="page-hero bg-image text-white" style="background-image: url('{{ asset('Front_end/assets/img/main_banner_1.png') }}');">
    <div class="container hero text-center py-5">
      <div class="row w-100">
        <div class="col-lg-10 mx-auto">
          <h1 class="display-5 text-white">Welcome to WellCare Lab</h1>
          <p class="lead mb-4 text-white">Your trusted partner for accurate and timely lab results.</p>
          <a href="#appointment-form" class="btn btn-primary btn-lg">Book Appointment</a>
        </div>
      </div>
    </div>
  </section>

  {{-- PACKAGES --}}
  <section id="packages" class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="mb-0 text-primary">Our Packages</h3>
      <small class="text-muted">Choose a package that fits your needs</small>
    </div>

    @isset($packages)
      @if($packages->count())
        <div class="row g-4">
          @foreach($packages as $pkg)
            <div class="col-md-4">
              <div class="card package-card h-100">
                @php
                  // prefer storage path if the file exists there, otherwise show nothing
                  $bannerPath = $pkg->banner ? storage_path('app/public/' . ltrim($pkg->banner, '/')) : null;
                  $bannerUrl = $pkg->banner ? asset('storage/' . ltrim($pkg->banner, '/')) : null;
                  $bannerVisible = $bannerPath && file_exists($bannerPath);
                @endphp

                @if($bannerVisible)
                  <img src="{{ $bannerUrl }}" alt="{{ $pkg->title }}" class="package-banner card-img-top">
                @endif

                <div class="card-body d-flex flex-column">
                  <h5 class="card-title">{{ $pkg->title }}</h5>
                  <p class="card-text text-muted" style="flex:1;">
                    {{ \Illuminate\Support\Str::limit(strip_tags($pkg->content ?? $pkg->short_description ?? ''), 140) }}
                  </p>

                  <div class="d-flex justify-content-between align-items-center mt-3">
                    @if(Route::has('packages.show'))
                      <a href="{{ route('packages.show', $pkg->slug ?? $pkg->id) }}" class="btn btn-outline-primary btn-sm">View details</a>
                    @endif
                    <span class="badge {{ (isset($pkg->status) && strtolower($pkg->status) === 'Published') ? 'bg-success' : 'bg-secondary' }}">
                      {{ $pkg->status ?? 'Published' }}
                    </span>
                  </div>

                  <div class="mt-3 d-flex justify-content-between align-items-center">
                    <div>
                      @if(isset($pkg->price))
                        <div class="fw-bold">₹{{ number_format((float)$pkg->price, 2) }}</div>
                      @endif
                      @if(isset($pkg->mrp) && $pkg->mrp > 0)
                        <small class="text-muted"><s>₹{{ number_format((float)$pkg->mrp,2) }}</s></small>
                      @endif
                    </div>

                    <button type="button"
                            class="btn btn-sm btn-primary select-for-appointment"
                            data-id="{{ $pkg->id }}"
                            data-title="{{ $pkg->title }}"
                            data-price="{{ $pkg->price ?? '' }}">
                      Book for this package
                    </button>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="alert alert-info">No packages are available right now. Please check back later.</div>
      @endif
    @else
      {{-- fallback static --}}
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card shadow-sm h-100">
            <div class="card-body text-center">
              <h4 class="h6 text-primary">Basic Health Check</h4>
              <p>Standard health package including blood tests and general screening.</p>
            </div>
          </div>
        </div>
        {{-- ... two more static --}}
      </div>
    @endisset
  </section>

  {{-- LAB TESTS --}}
  <section class="lab-tests py-5">
    <div class="container">
      <div class="row">
        @forelse($tests as $t)
          <div class="col-md-4 mb-4">
            <div class="card h-100">
              @php
                $testBannerPath = $t->banner_path ? storage_path('app/public/' . ltrim($t->banner_path, '/')) : null;
                $testBannerUrl  = $t->banner_path ? asset('storage/' . ltrim($t->banner_path, '/')) : null;
                $testBannerVisible = $testBannerPath && file_exists($testBannerPath);
              @endphp

              @if($testBannerVisible)
                <img src="{{ $testBannerUrl }}" class="card-img-top" style="height:180px;object-fit:cover;" alt="{{ $t->test_name }}">
              @endif

              <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $t->test_name }}</h5>
                <p class="card-text mb-2">
                  MRP: <strong>₹{{ number_format((float)($t->mrp ?? 0), 2) }}</strong><br>
                  Discounted: <strong>₹{{ number_format((float)($t->discounted_price ?? 0), 2) }}</strong>
                </p>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <span class="badge bg-success text-white text-capitalize">{{ $t->status ?? 'published' }}</span>
                  <a href="#" class="btn btn-sm btn-primary">Book Now</a>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="card">
              <div class="card-body text-center text-muted py-4">No lab tests published yet.</div>
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  {{-- APPOINTMENT --}}
  <section class="bg-light py-5" id="appointment-form">
    <div class="container">
      <div class="card shadow">
        <div class="card-body">
          <h2 class="text-center text-info mb-3">Book an Appointment</h2>
          <p class="text-center mb-4">Fill out the form below and we’ll get back to you soon.</p>

          @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
          @endif

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('appointments.store') }}" method="POST" class="row g-3" id="appointmentForm">
            @csrf
            <div class="col-12">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="col-md-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="col-md-6">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>

            <div class="col-md-6">
              <label for="date" class="form-label">Preferred Date</label>
              <input type="date" id="date" name="date" class="form-control" value="{{ old('date') }}" required>
            </div>

            {{-- Department / Service (OPTIONAL) Removed since not used --}}

            <div class="col-md-6">
              <label for="package_select" class="form-label">Select Package (optional)</label>
              <select id="package_select" name="package_id" class="form-select">
                <option value="">{{ __('No Package / Just Service') }}</option>
                @isset($packages)
                  @foreach($packages as $p)
                    <option value="{{ $p->id }}"
                            data-title="{{ e($p->title) }}"
                            data-price="{{ $p->price ?? '' }}"
                            {{ old('package_id') == $p->id ? 'selected' : '' }}>
                      {{ $p->title }} @if(isset($p->price)) — ₹{{ number_format((float)$p->price,2) }}@endif
                    </option>
                  @endforeach
                @endisset
              </select>

              <div class="mt-2">
                <small class="text-muted">Selected: <span id="selectedPackage" class="fw-semibold">{{ old('package_name') ?? 'None' }}</span></small>
              </div>

              {{-- hidden input so server receives package name too if you want --}}
              <input type="hidden" name="package_name" id="package_name_input" value="{{ old('package_name','') }}">
            </div>

            <div class="col-12">
              <label for="message" class="form-label">Message (Optional)</label>
              <textarea id="message" name="message" class="form-control" rows="4">{{ old('message') }}</textarea>
            </div>

            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary px-4">Book Appointment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  {{-- FOOTER --}}
  <footer id="contact" class="page-footer bg-dark text-white text-center py-4">
    <div class="container">
      <p class="mb-1">Contact us: <a href="tel:+1234567890" class="text-primary">+1 234 567 890</a> | <a href="mailto:info@wellcarelab.com" class="text-primary">info@wellcarelab.com</a></p>
      <p class="mb-0">&copy; {{ date('Y') }} WellCare Lab. All Rights Reserved. | <a href="#" class="text-primary">Privacy Policy</a></p>
    </div>
  </footer>

  {{-- SCRIPTS --}}
  <script src="{{ asset('Front_end/assets/js/jquery-3.5.1.min.js') }}"></script>
  <script src="{{ asset('Front_end/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('Front_end/assets/vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('Front_end/assets/vendor/wow/wow.min.js') }}"></script>
  <script src="{{ asset('Front_end/assets/js/theme.js') }}"></script>

  <script>
    try { new WOW().init(); } catch(e){}

    // Select a package from the package card "Book for this package" button
    $(document).on('click', '.select-for-appointment', function(){
      const $btn = $(this);
      const pkgId = $btn.data('id');
      const title = $btn.data('title') || 'Selected package';
      const price = $btn.data('price') || '';

      const $pkgSelect = $('#package_select');
      if($pkgSelect.length && $pkgSelect.find('option[value="' + pkgId + '"]').length){
        $pkgSelect.val(pkgId).trigger('change');
      }

      const display = title + (price ? ' — ₹' + Number(price).toLocaleString() : '');
      $('#selectedPackage').text(display);

      // set hidden input so server receives package name if you need it
      $('#package_name_input').val(title);

      // scroll to appointment form
      $('html, body').animate({ scrollTop: $('#appointment-form').offset().top - 20 }, 500);
    });

    // When user changes the select manually
    $(document).on('change', '#package_select', function(){
      const $opt = $(this).find('option:selected');
      const title = $opt.data('title') || ($opt.text() || 'None');
      const price = $opt.data('price') || '';
      const display = title + (price ? ' — ₹' + Number(price).toLocaleString() : '');
      $('#selectedPackage').text(display);
      $('#package_name_input').val(title);
    });

    // initialize selected text on load (if there is an old selected option)
    $(function(){
      const $pkg = $('#package_select').find('option:selected');
      if($pkg && $pkg.val()){
        const title = $pkg.data('title') || $pkg.text();
        const price = $pkg.data('price') || '';
        $('#selectedPackage').text(title + (price ? ' — ₹' + Number(price).toLocaleString() : ''));
        $('#package_name_input').val(title);
      }
    });
  </script>
</body>
</html>
