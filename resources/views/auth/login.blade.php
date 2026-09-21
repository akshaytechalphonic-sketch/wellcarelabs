<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Log In | WellCare Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="WellCare Admin login page" />
  <meta name="author" content="WellCare" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <!-- Favicon -->
  <link href="{{ asset('assets/images/favicon.ico') }}" rel="icon" type="image/x-icon" />

  <!-- App CSS -->
  <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

  <style>
    :root {
      --card-radius: 14px;
      --accent-1: #33b27b;
      --accent-2: #2980b9;
      --muted: rgba(0, 0, 0, 0.65);
      --input-bg: rgba(255,255,255,0.95);
      --text-dark: #243040;
      --eye-color: #243040; /* dark eye icon color */
    }

    html,body { height: 100%; margin: 0; }

    body {
      font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      display: grid;
      place-items: center;
      background: linear-gradient(120deg, var(--accent-1) 0%, var(--accent-2) 100%);
      background-attachment: fixed;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      color: #fff;
    }

    /* soft pattern */
    body::before {
      content: "";
      position: fixed;
      inset: 0;
      background-image: url("https://www.transparenttextures.com/patterns/asfalt-light.png");
      opacity: 0.06;
      z-index: 0;
      pointer-events: none;
    }

    .login-wrap {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 920px;
      margin: 2rem;
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 2rem;
      align-items: center;
    }

    /* Left visual section */
    .login-visual {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      padding: 2.2rem;
      color: white;
      border-radius: var(--card-radius);
      background: linear-gradient(135deg, rgba(0,0,0,0.12), rgba(255,255,255,0.05));
      box-shadow: 0 12px 40px rgba(10,20,30,0.18);
      min-height: 420px;
      align-items: flex-start;
      justify-content: center;
      animation: visualIn .6s cubic-bezier(.2,.9,.3,1) both;
    }

    @keyframes visualIn {
      from { opacity: 0; transform: translateY(18px) scale(.98) }
      to { opacity: 1; transform: translateY(0) scale(1) }
    }

    .login-visual h2 {
      margin: 0 0 .25rem;
      font-size: 28px;
      font-weight: 700;
    }

    .login-visual p {
      margin: 0;
      opacity: .95;
      line-height: 1.4;
      color: rgba(255,255,255,0.95);
    }

    /* Transparent login card */
    .login-card {
      background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
      border-radius: var(--card-radius);
      box-shadow: 0 12px 40px rgba(10,20,30,0.16);
      padding: 2rem 2rem 1.8rem;
      backdrop-filter: blur(10px) saturate(130%);
      -webkit-backdrop-filter: blur(10px) saturate(130%);
      border: 1px solid rgba(255,255,255,0.12);
      animation: fadeInUp .6s cubic-bezier(.2,.9,.3,1);
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Logo — removed shady/drop-shadow effect for cleaner look */
    .auth-logo img {
      height: 90px;
      display: block;
      margin: 0 auto 14px;
      object-fit: contain;
      filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.25));
      -webkit-filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.25));
    }


    .form-title { text-align: center; margin-bottom: 12px; }
    .form-title h4 { margin: 0; font-size: 22px; font-weight: 700; color: #fff; }
    .small-muted { color: rgba(255,255,255,0.85); font-size: 13px; margin-top: 6px; }

    /* Inputs */
    .form-control {
      background: var(--input-bg);
      border: 1px solid rgba(27,39,54,0.1);
      color: var(--text-dark);
    }

    .form-control::placeholder { color: #98a2af; }
    .form-control:focus {
      box-shadow: 0 0 0 3px rgba(51,178,123,0.12) !important;
      border-color: var(--accent-1) !important;
    }

    .form-label { color: rgba(255,255,255,0.9); font-weight: 500; }

    /* Password field */
    .password-wrapper { position: relative; }
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      border: none;
      background: transparent;
      color: var(--eye-color);
      cursor: pointer;
      padding: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    /* make the eye svg stroke a dark color so it's visible */
    .password-toggle svg { stroke: var(--eye-color); fill: none; }

    /* focus state for accessibility */
    .password-toggle:focus {
      outline: 2px solid rgba(36,48,64,0.12);
      border-radius: 6px;
    }

    /* hover state - slightly darker */
    .password-toggle:hover svg { stroke: #1b2a33; }

    /* Password strength bar */
    .pw-strength {
      height: 6px;
      background: rgba(255,255,255,0.08);
      border-radius: 6px;
      overflow: hidden;
      margin-top: 6px;
    }

    .pw-strength > i {
      display: block;
      height: 100%;
      width: 0%;
      background: linear-gradient(90deg,#f44336,#ff9800,#33b27b);
      transition: width .28s ease;
    }

    /* Buttons */
    .btn-primary.custom {
      background: linear-gradient(90deg,var(--accent-1), #2a9e6a);
      border: none;
      color: #fff;
      font-weight: 700;
      padding: .75rem 1rem;
      box-shadow: 0 8px 24px rgba(22,66,66,0.1);
    }
    .btn-primary.custom:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(22,66,66,0.15); }

    /* Outline / secondary button for register */
    .btn-outline-light {
      background: transparent;
      border: 1px solid rgba(255,255,255,0.18);
      color: #fff;
      font-weight: 700;
      padding: .68rem 0.95rem;
    }
    .btn-outline-light:hover { background: rgba(255,255,255,0.06); transform: translateY(-2px); }

    .small-links {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      margin-top: 12px;
      font-size: 13px;
    }

    .small-links a { color: rgba(255,255,255,0.95); text-decoration: none; }
    .small-links a:hover { text-decoration: underline; }

    .alert-validation {
      background: rgba(255,235,205,0.12);
      border: 1px solid rgba(255,224,184,0.06);
      padding: .6rem .75rem;
      border-radius: 8px;
      color: #fff;
      font-size: 13px;
      margin-bottom: 10px;
    }

    .card-alert {
      margin-bottom: 12px;
      padding: .6rem .9rem;
      border-radius: 8px;
      color: #fff;
      background: rgba(52,199,89,0.12);
      border: 1px solid rgba(52,199,89,0.08);
    }

    /* Responsive */
    @media (max-width: 980px) {
      .login-wrap { grid-template-columns: 1fr; max-width: 520px; }
      .login-visual { order: 2; text-align: center; min-height: 150px; }
      .auth-logo img { height: 72px; margin-bottom: 10px; } /* slightly smaller on narrow screens */
    }

    /* Footer */
    .footer-login {
      position: fixed;
      bottom: 12px;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: center;
      z-index: 999;
      pointer-events: none;
    }

    .footer-glass {
      background: rgba(255, 255, 255, 0.93);
      backdrop-filter: blur(8px) saturate(150%);
      -webkit-backdrop-filter: blur(8px) saturate(150%);
      border: 1px solid rgba(255, 255, 255, 0.65);
      pointer-events: all;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .footer-year {
      color: #1d2b3a;
      font-weight: 600;
      text-shadow: 0 1px 2px rgba(255,255,255,0.8);
    }

    .footer-brand {
      color: #243040;
      font-weight: 700;
    }

    .footer-rights {
      color: #2c3e50;
      font-size: 13px;
      opacity: 0.9;
      margin-left: 4px;
      text-shadow: 0 1px 2px rgba(255,255,255,0.6);
    }

    .footer-glass:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.18);
    }
  </style>
</head>
<body>
  <div class="login-wrap" role="main" aria-labelledby="login-heading">
    <div class="login-visual" aria-hidden="true">
      <h2>WellCare Admin</h2>
      <p>Securely manage packages, tests, appointments and banners. Built for speed and reliability — log in to continue to the admin dashboard.</p>
      <div style="margin-top:16px;">
        <img src="{{ asset('assets/images/gallery/Welcare_labs.png') }}" alt="WellCare Labs" style="height:64px; opacity:.95">
      </div>
    </div>

    <div class="login-card" role="region" aria-labelledby="login-heading" aria-live="polite">
      <a href="{{ url('/') }}" class="auth-logo" aria-label="Return to site home">
        <img src="{{ asset('assets/images/gallery/Welcare_labs.png') }}" alt="WellCare Labs logo">
      </a>

      <div class="form-title">
        <h4 id="login-heading">Welcome back</h4>
        <div class="small-muted">Sign in to your account</div>
      </div>

      {{-- Session status --}}
      @if (session('status'))
        <div class="alert alert-success card-alert" role="status">{{ session('status') }}</div>
      @endif

      {{-- Validation errors --}}
      @if ($errors->any())
        <div class="alert-validation" role="alert">Please fix the highlighted fields below.</div>
      @endif

      <form method="POST" action="{{ route('login') }}" novalidate id="loginForm" autocomplete="on">
        @csrf

        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="you@company.com" autofocus>
          @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="password-wrapper">
            <input type="password" id="password" name="password" required class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="••••••••" autocomplete="current-password">
            <button type="button" id="togglePassword" class="password-toggle" aria-pressed="false" title="Show password" aria-label="Toggle password visibility">
              <!-- eye icon with dark stroke -->
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>
          <div id="pwHelp" class="small-muted" style="margin-top:8px;">Use at least 8 characters. Avoid common words.</div>
          <div class="pw-strength"><i id="pwStrengthBar" style="width:0%"></i></div>
          @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="small-links">
          <div>
            <label class="form-check" style="display:inline-flex; align-items:center; gap:.5rem;">
              <input type="checkbox" id="remember_me" name="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
              <span style="font-size:13px; color:rgba(255,255,255,0.9)">Remember me</span>
            </label>
          </div>
          <div>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="text-muted">Forgot password?</a>
            @endif
          </div>
        </div>

        <div class="mt-3 d-grid">
          <button type="submit" id="submitBtn" class="btn btn-primary custom btn-lg">Log In</button>
        </div>

        <!-- Registration CTA (separate from form submission) -->
        <!-- <div class="mt-3" style="display:flex; gap:10px; align-items:center; justify-content:center;">
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg" role="button" id="registerBtn">Create account</a>
          @else
            If app doesn't expose register route, link to a public sign-up page or hide -->
            <!-- <a href="{{ url('/register') }}" class="btn btn-outline-light btn-lg" role="button" id="registerBtn">Create account</a>
          @endif
        </div> -->

      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer-login">
    <div class="footer-glass">
      <span class="footer-year">&copy; <script>document.write(new Date().getFullYear())</script></span>
      <strong class="footer-brand">WellCare™</strong>
      <span class="footer-rights">All rights reserved.</span>
    </div>
  </footer>

  <!-- JS -->
  <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

  <script>
    try { if (typeof feather !== 'undefined') feather.replace(); } catch(e){}

    (function(){
      const pw = document.getElementById('password');
      const toggle = document.getElementById('togglePassword');
      const pwBar = document.getElementById('pwStrengthBar');
      const submitBtn = document.getElementById('submitBtn');
      const form = document.getElementById('loginForm');
      const registerBtn = document.getElementById('registerBtn');

      toggle.addEventListener('click', function(){
        const isPass = pw.type === 'password';
        pw.type = isPass ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', String(isPass));
        toggle.title = isPass ? 'Hide password' : 'Show password';
      });

      function pwScore(s){
        let score = 0;
        if (!s) return 0;
        if (s.length >= 8) score += 25;
        if (/[a-z]/.test(s) && /[A-Z]/.test(s)) score += 20;
        if (/\d/.test(s)) score += 20;
        if (/[^A-Za-z0-9]/.test(s)) score += 20;
        if (s.length >= 12) score += 15;
        return Math.min(100, score);
      }

      pw.addEventListener('input', () => pwBar.style.width = pwScore(pw.value) + '%');

      form.addEventListener('submit', function(e){
        const email = document.getElementById('email');
        if (!email.value.trim() || !pw.value.trim()){
          e.preventDefault();
          if (!email.value.trim()) email.focus();
          return;
        }
        submitBtn.disabled = true;
        submitBtn.innerText = 'Signing in...';
      });

      // optional: if you want to prevent accidental submit if user presses enter on register anchor
      if (registerBtn){
        registerBtn.addEventListener('click', function(e){
          // anchor will navigate naturally; nothing needed.
        });
      }

      @if ($errors->any())
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) firstInvalid.focus();
      @endif
    })();
  </script>
</body>
</html>
