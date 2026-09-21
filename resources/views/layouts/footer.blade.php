<footer class="footer-login text-center">
  <div class="container-fluid py-3">
    <div class="footer-glass d-inline-block px-4 py-2 rounded-3 shadow-sm">
      <span class="text-dark fw-semibold">
        &copy; <script>document.write(new Date().getFullYear())</script> 
        <strong>WellCare™</strong>
      </span>
      <span class="text-muted ms-1">All rights reserved.</span>
    </div>
  </div>
</footer>

<style>
  /* ===== Login Footer Styling ===== */
  .footer-login {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: transparent;
    z-index: 2;
    pointer-events: none;
  }

  .footer-login .footer-glass {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(6px) saturate(120%);
    -webkit-backdrop-filter: blur(6px) saturate(120%);
    border: 1px solid rgba(255, 255, 255, 0.4);
    pointer-events: all;
    transition: all 0.3s ease;
  }

  .footer-login .footer-glass:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .footer-login span {
    font-size: 13px;
  }

  @media (max-width: 576px) {
    .footer-login .footer-glass {
      padding: 6px 12px;
      font-size: 12px;
    }
  }
</style>
