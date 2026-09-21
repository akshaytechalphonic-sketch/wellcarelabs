<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register | WellCare Lab</title>

  <!-- Bootstrap / CSS -->
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">

  <style>
    /* Small visual tweaks to match the login page look */
    body { background: linear-gradient(120deg,#33b27b 0%,#2980b9 100%); color: #243040; font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial; }
    .account-page { min-height: 100vh; display: flex; align-items: center; }
    .card.glass {
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
      border: 1px solid rgba(255,255,255,0.12);
      backdrop-filter: blur(8px) saturate(120%);
      box-shadow: 0 12px 36px rgba(10,20,30,0.15);
    }
    .auth-title-section h3 { color: #fff; margin-bottom: 6px; }
    .auth-title-section p { color: rgba(255,255,255,0.92); margin-bottom: 0; }
    .form-label { color: rgba(255,255,255,0.95); font-weight: 600; }
    .form-control { background: rgba(255,255,255,0.95); border: 1px solid rgba(27,39,54,0.08); color: #243040; }
    .btn-primary { background: linear-gradient(90deg,#33b27b,#2a9e6a); border: none; font-weight: 700; }
    .small-muted { color: rgba(255,255,255,0.85); font-size: 13px; }
    .password-wrapper { position: relative; }
    .password-toggle {
      position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; padding: 6px;
    }
    .password-toggle svg { stroke: #243040; fill: none; }
    .pw-strength { height: 6px; background: rgba(255,255,255,0.08); border-radius: 6px; overflow: hidden; margin-top: 6px; }
    .pw-strength > i { display:block; height:100%; width:0%; background: linear-gradient(90deg,#f44336,#ff9800,#33b27b); transition: width .28s ease; }
    @media (max-width: 768px) {
      .card.glass { padding: 1rem; }
      .auth-title-section h3 { font-size: 18px; }
    }
  </style>
</head>
<body>

  <div class="account-page">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
          <div class="card glass p-3 shadow">
            <div class="card-body p-4">

              <!-- Logo -->
              <div class="mb-4 text-center">
                <a class="auth-logo d-inline-block" href="{{ url('/') }}" aria-label="WellCare home">
                  <img src="{{ asset('assets/images/gallery/welcare_labs.png') }}" alt="WellCare logo" height="56">
                </a>
              </div>

              <!-- Title -->
              <div class="auth-title-section mb-3 text-center">
                <h3 class="fs-20 fw-medium mb-2">Create your account</h3>
                <p class="mb-0">Register to access the WellCare Lab dashboard.</p>
              </div>

              <!-- Session / Validation -->
              @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
              @endif
              @if ($errors->any())
                <div class="alert alert-danger mb-3">Please fix the errors below.</div>
              @endif

              <!-- Register Form -->
              <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                @csrf

                <!-- Name -->
                <div class="mb-3">
                  <label for="name" class="form-label">Full name</label>
                  <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required autocomplete="name" autofocus>
                  <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                </div>

                <!-- Email -->
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
                  <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="password-wrapper">
                    <input id="password" name="password" type="password" class="form-control" required autocomplete="new-password" placeholder="At least 8 characters">
                    <button type="button" id="togglePassword" class="password-toggle" aria-pressed="false" aria-label="Toggle password visibility" title="Show password">
                      <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.4"/></svg>
                    </button>
                  </div>
                  <div class="small-muted" style="margin-top:8px;">Use at least 8 characters. Avoid common words.</div>
                  <div class="pw-strength"><i id="pwStrengthBar" style="width:0%"></i></div>
                  <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                  <label for="password_confirmation" class="form-label">Confirm password</label>
                  <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
                  <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                </div>

                <!-- Submit -->
                <div class="mb-0 d-grid">
                  <button type="submit" id="submitRegister" class="btn btn-primary btn-lg">Register</button>
                </div>
              </form>

              <!-- Already registered -->
              <div class="text-center text-white-50 mt-3">
                <p class="mb-0">Already have an account?
                  <a href="{{ route('login') }}" class="text-white ms-2 fw-medium">Login here</a>
                </p>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('assets/js/vendor.min.js') }}"></script>
  <script src="{{ asset('assets/js/app.min.js') }}"></script>

  <script>
    (function(){
      const pw = document.getElementById('password');
      const toggle = document.getElementById('togglePassword');
      const pwBar = document.getElementById('pwStrengthBar');
      const form = document.getElementById('registerForm');
      const submitBtn = document.getElementById('submitRegister');

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

      if (pw){
        pw.addEventListener('input', () => {
          pwBar.style.width = pwScore(pw.value) + '%';
        });
      }

      if (toggle && pw){
        toggle.addEventListener('click', function(){
          const isPass = pw.type === 'password';
          pw.type = isPass ? 'text' : 'password';
          toggle.setAttribute('aria-pressed', String(isPass));
          toggle.title = isPass ? 'Hide password' : 'Show password';
        });
      }

      form.addEventListener('submit', function(e){
        // basic client-side check to prevent empty fields and mismatched passwords
        const name = document.getElementById('name');
        const email = document.getElementById('email');
        const passConfirm = document.getElementById('password_confirmation');

        if (!name.value.trim() || !email.value.trim() || !pw.value.trim() || !passConfirm.value.trim()){
          e.preventDefault();
          if (!name.value.trim()) name.focus();
          return;
        }

        if (pw.value !== passConfirm.value){
          e.preventDefault();
          alert('Passwords do not match.');
          passConfirm.focus();
          return;
        }

        submitBtn.disabled = true;
        submitBtn.innerText = 'Registering...';
      });
    })();
  </script>

</body>
</html>
