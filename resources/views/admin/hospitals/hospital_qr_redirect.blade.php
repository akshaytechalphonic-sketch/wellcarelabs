<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Opening Wellcare Labs…</title>

  {{-- meta-refresh fallback to homepage in case JS is disabled --}}
  <meta http-equiv="refresh" content="0.45;url={{ $redirectUrl }}">

  <style>
    body{font-family:system-ui,Arial,Helvetica,sans-serif;margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#fff}
    .card{padding:18px;border-radius:10px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,0.06);max-width:420px}
    .muted{color:#6b7280}
    .spinner{width:36px;height:36px;border-radius:50%;border:4px solid rgba(11,110,253,0.18);border-top-color:#0b6efd;margin:8px auto;animation:spin 1s linear infinite}
    @keyframes spin{to{transform:rotate(360deg)}}
  </style>
</head>
<body>
  <div class="card" role="status" aria-live="polite">
    <div class="spinner" aria-hidden="true"></div>
    <div><strong>Opening Wellcare Labs…</strong></div>
    <div class="muted" style="margin-top:8px">If not redirected, <a href="{{ $redirectUrl }}">click here</a>.</div>
  </div>

<script>
(function () {
  // payload from server (safe copy - for cookie fallback only)
  var payload = {!! json_encode($payload) !!};
  var redirectUrl = {!! json_encode($redirectUrl) !!};

  try {
    // Client-side cookie fallback (30 days) — keep simple to work on localhost
    var cookieName = 'hospital_ref';
    var cookieVal = encodeURIComponent(JSON.stringify(payload));
    var expires = new Date(Date.now() + 30*24*60*60*1000).toUTCString();
    document.cookie = cookieName + '=' + cookieVal + '; expires=' + expires + '; path=/';

    // Replace the location so '/h/...' is removed from history and browser shows '/'
    setTimeout(function () {
      try { window.location.replace(redirectUrl); } catch(e) { window.location.href = redirectUrl; }
    }, 120);
  } catch (e) {
    // fallback redirect on any error
    setTimeout(function () { window.location.replace(redirectUrl); }, 300);
  }
})();
</script>
</body>
</html>
