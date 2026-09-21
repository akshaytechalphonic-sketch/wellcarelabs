<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Reset Password | WellCare Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reset your password — WellCare Admin" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
      :root {
        --card-radius: 14px;
        --accent-1: #33b27b;
        --accent-2: #2980b9;
        --muted: #6c757d;
        --input-bg: rgba(255,255,255,0.95);
        --text-dark: #243040;
      }

      html,body { height: 100%; }
      body {
        margin: 0;
        font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        display: grid;
        place-items: center;
        background: linear-gradient(120deg,var(--accent-1) 0%, var(--accent-2) 100%);
        background-attachment: fixed;
        color: var(--text-dark);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }

      body::before {
        content: "";
        position: fixed; inset: 0;
        background-image: url("https://www.transparenttextures.com/patterns/asfalt-light.png");
        opacity: 0.06; z-index: 0; pointer-events: none;
      }

      .account-page { width: 100%; max-width: 720px; margin: 2rem; }
      .recover-center { display:flex; align-items:center; justify-content:center; min-height:520px; }

      .recover-card {
        width: 100%; max-width: 520px;
        border-radius: var(--card-radius);
        background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
        padding: 2rem 2rem 1.8rem;
        box-shadow: 0 12px 40px rgba(10,20,30,0.16);
        backdrop-filter: blur(10px) saturate(130%);
        -webkit-backdrop-filter: blur(10px) saturate(130%);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
        animation: fadeInUp .6s cubic-bezier(.2,.9,.3,1);
      }

      @keyframes fadeInUp { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }

      .auth-logo img {
        height: 90px; display:block; margin:0 auto 14px; object-fit:contain;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,0.25));
      }

      .auth-title-section h3 { color:#fff; margin-bottom:6px; font-weight:700; font-size:22px; }
      .auth-title-section p { color:rgba(255,255,255,0.9); margin-bottom:0; }

      .form-control {
        background: var(--input-bg);
        border: 1px solid rgba(27,39,54,0.06);
        color: var(--text-dark);
        padding: 0.65rem 0.75rem;
        font-size: 15px;
        border-radius: 8px;
      }
      .form-control:focus { border-color: var(--accent-1); box-shadow: 0 0 0 3px rgba(51,178,123,0.12); }
      .form-label { color: rgba(255,255,255,0.92); font-weight: 600; }

      .btn-primary.custom {
        background: linear-gradient(90deg,var(--accent-1), #2a9e6a);
        border: none; color: #fff; font-weight: 700; padding: 0.8rem;
        border-radius: 8px; box-shadow: 0 8px 24px rgba(22,66,66,0.08);
        transition: all 0.25s ease;
      }
      .btn-primary.custom:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(22,66,66,0.12); }
      .btn-primary.custom:disabled { opacity:.7; cursor:not-allowed; transform:none; box-shadow:none; }

      .card-alert {
        margin-bottom: 12px; padding:.6rem .9rem; border-radius:8px; color:#fff;
        background: rgba(52,199,89,0.12); border:1px solid rgba(52,199,89,0.08);
      }
      .alert-validation {
        background: rgba(255,235,205,0.12);
        border: 1px solid rgba(255,224,184,0.06);
        padding:.6rem .75rem; border-radius:8px; color:#fff; font-size:13px; margin-bottom:10px;
      }
      .text-white-75 { color: rgba(255,255,255,0.85) !important; }

      @media (max-width:720px){
        .account-page { margin: 1rem; max-width: 100%; }
        .recover-center { min-height: auto; padding: 1rem 0; }
      }

      .footer-login {
        position: fixed; bottom: 12px; left: 0; width: 100%;
        display: flex; justify-content: center; z-index: 2; pointer-events: none;
      }
      .footer-login .footer-glass{
        background: rgba(255, 255, 255, 0.82);
        backdrop-filter: blur(6px) saturate(120%); -webkit-backdrop-filter: blur(6px) saturate(120%);
        border: 1px solid rgba(255, 255, 255, 0.42); pointer-events: all; transition: all .28s ease;
        padding: 8px 14px; border-radius: 12px; box-shadow: 0 8px 26px rgba(10,20,30,0.08);
        display: inline-flex; gap: 8px; align-items: center;
      }
      .footer-login .footer-glass strong { color:#243040; font-weight:700; }
      .footer-login .footer-glass .muted { color: var(--muted); font-size: 13px; margin-left: 4px; }

      @media (max-width:420px){
        .footer-login { bottom:8px; }
        .footer-login .footer-glass { padding:6px 10px; font-size:13px; }
      }
    </style>
</head>

<body>
  <div class="account-page">
    <div class="container-fluid p-0">
      <div class="row g-0">
        <div class="col-12 recover-center">
          <div class="recover-card">
            <div class="text-center">
              <a class="auth-logo" href="{{ url('/') }}" aria-label="WellCare home">
                <img src="{{ asset('assets/images/gallery/Welcare_labs.png') }}" alt="WellCare Labs logo" />
              </a>
            </div>

            <div class="auth-title-section text-center mb-3">
              <h3>Reset Password</h3>
              <p>Set a new password for your account.</p>
            </div>

            {{-- Session status --}}
            @if (session('status'))
              <div class="alert alert-success card-alert" role="status">
                {{ session('status') }}
              </div>
            @endif

            {{-- Validation errors (list at the top, like reference) --}}
            @if ($errors->any())
              <div class="alert-validation" role="alert">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            {{-- Reset password form --}}
            <form method="POST" action="{{ route('password.store') }}" class="my-3" id="resetForm" novalidate>
              @csrf

              <!-- Password Reset Token -->
              <input type="hidden" name="token" value="{{ request()->route('token') }}">

              <!-- Email Address -->
              <div class="form-group mb-3">
                <label for="email" class="form-label">Email address</label>
                <input
                  id="email"
                  name="email"
                  type="email"
                  value="{{ old('email', request()->email) }}"
                  required
                  autocomplete="username"
                  autofocus
                  class="form-control @error('email') is-invalid @enderror"
                  placeholder="Enter your email">
                @error('email')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>

              <!-- Password -->
              <div class="form-group mb-3">
                <label for="password" class="form-label">New password</label>
                <input
                  id="password"
                  name="password"
                  type="password"
                  required
                  autocomplete="new-password"
                  class="form-control @error('password') is-invalid @enderror"
                  placeholder="Enter new password">
                @error('password')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>

              <!-- Confirm Password -->
              <div class="form-group mb-3">
                <label for="password_confirmation" class="form-label">Confirm new password</label>
                <input
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  required
                  autocomplete="new-password"
                  class="form-control @error('password_confirmation') is-invalid @enderror"
                  placeholder="Re-enter new password">
                @error('password_confirmation')
                  <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
              </div>

              <div class="form-group mb-0">
                <div class="d-grid">
                  <button class="btn btn-primary custom" type="submit" id="resetBtn">Reset Password</button>
                </div>
              </div>
            </form>

            <div class="text-center text-white-75 mt-3">
              <p class="mb-0">
                Remembered your password?
                <a class="text-white ms-2 fw-medium" href="{{ route('login') }}">Back to Login</a>
              </p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer (matching login/recover UI) -->
  <footer class="footer-login" aria-hidden="false">
    <div class="footer-glass" role="contentinfo" aria-label="Footer">
      <span>&copy; <script>document.write(new Date().getFullYear())</script></span>
      <strong style="margin-left:6px;">WellCare™</strong>
      <span class="muted">All rights reserved.</span>
    </div>
  </footer>

  <!-- Vendor JS -->
  <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>

  <script>
    try { if (typeof feather !== 'undefined') feather.replace() } catch(e){}

    (function(){
      const form = document.getElementById('resetForm');
      const btn  = document.getElementById('resetBtn');

      form.addEventListener('submit', function(e){
        const email = document.getElementById('email');
        const pass  = document.getElementById('password');
        const pass2 = document.getElementById('password_confirmation');

        if (!email.value.trim() || !pass.value.trim() || !pass2.value.trim()){
          e.preventDefault();
          ( !email.value.trim() ? email : (!pass.value.trim() ? pass : pass2) ).focus();
          return;
        }
        if (pass.value !== pass2.value){
          e.preventDefault();
          alert('Passwords do not match.');
          pass2.focus();
          return;
        }

        btn.disabled = true;
        btn.innerText = 'Resetting...';
      });

      @if ($errors->any())
        (function(){
          const firstInvalid = document.querySelector('.is-invalid');
          if(firstInvalid) firstInvalid.focus();
        })();
      @endif
    })();
  </script>
</body>
</html>
