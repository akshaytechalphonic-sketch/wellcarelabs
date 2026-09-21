{{-- resources/views/partials/share-modal.blade.php --}}

<style>
  :root {
    --wc-primary: #0f9d80;
    --wc-border: #e2e8f0;
    --wc-border-soft: #edf2f7;
    --wc-text: #0f172a;
    --wc-muted: #64748b;
    --wc-bg: #ffffff;
    --wc-bg-soft: #f8fafc;
  }

  #shareModal .modal-dialog {
    max-width: 420px;
  }

  .wc-share-modal-card {
    border-radius: 16px;
    border: 1px solid var(--wc-border-soft);
    background: var(--wc-bg);
    box-shadow: 0 14px 35px rgba(0, 0, 0, 0.14);
    overflow: hidden;
  }

  .wc-share-modal-header {
    background: linear-gradient(115deg, var(--wc-primary), #14b8a6);
    color: #fff;
    padding: 12px 18px;
  }

  .wc-share-modal-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
  }

  .wc-share-modal-pill {
    width: 28px;
    height: 28px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
  }

  .wc-share-modal-header .btn-close {
    filter: invert(1);
    opacity: .9;
  }

  .wc-share-modal-body {
    padding: 14px 18px;
    background: var(--wc-bg-soft);
  }

  .wc-share-actions-grid {
    display: grid;
    gap: 10px;
    margin-bottom: 14px;
  }

  .wc-share-btn-main {
    border-radius: 12px;
    padding: 10px 14px;
    font-size: 0.88rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: .16s;
    cursor: pointer;
    border: 1px solid var(--wc-border);
    background: #fff;
    color: var(--wc-text);
  }

  .wc-share-btn-main i {
    font-size: 1rem;
  }

  .wc-share-btn-whatsapp {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    border: none;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
  }

  .wc-share-btn-whatsapp:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 26px rgba(0, 0, 0, 0.22);
  }

  .wc-share-btn-outline:hover {
    border-color: var(--wc-primary);
    color: var(--wc-primary);
  }

  .wc-share-label {
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 4px;
    color: var(--wc-text);
  }

  .wc-share-textarea {
    border: 1px solid var(--wc-border);
    border-radius: 10px;
    padding: 8px 10px;
    font-size: 0.86rem;
    background: #fff;
    resize: vertical;
  }

  .wc-share-textarea:focus {
    border-color: var(--wc-primary);
    box-shadow: 0 0 0 1px rgba(15, 118, 110, 0.12);
  }

  .wc-share-modal-footer {
    padding: 10px 14px;
    display: flex;
    justify-content: space-between;
    background: #fff;
    border-top: 1px solid var(--wc-border-soft);
  }

  .wc-share-footer-btn {
    padding: 6px 12px;
    font-size: 0.78rem;
    border-radius: 10px;
    border: 1px solid var(--wc-border);
    background: #fff;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .wc-share-footer-btn:hover {
    border-color: var(--wc-primary);
    color: var(--wc-primary);
  }

  .wc-share-footer-btn-secondary {
    background: #f3f4f6;
    color: var(--wc-muted);
  }

  /* Email section */
  .wc-share-email-section {
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px dashed var(--wc-border-soft);
    display: none;
  }

  .wc-share-email-input {
    width: 100%;
    border-radius: 10px;
    border: 1px solid var(--wc-border);
    padding: 7px 10px;
    font-size: 0.86rem;
    margin-bottom: 6px;
  }

  .wc-share-email-input:focus {
    outline: none;
    border-color: var(--wc-primary);
    box-shadow: 0 0 0 1px rgba(15, 118, 110, 0.12);
  }

  /* NEW Gmail-style Send Email button (strong CTA, like WhatsApp) */
  .wc-share-email-send-btn {
    width: 100%;
    border-radius: 12px;
    border: none;
    padding: 10px 14px;
    font-size: 0.88rem;
    font-weight: 600;
    background: linear-gradient(135deg, #ea4335, #d93025);
    /* Gmail red tones */
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 10px 20px rgba(234, 67, 53, 0.18);
    transition: .16s;
  }

  .wc-share-email-send-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 26px rgba(234, 67, 53, 0.28);
  }

  .wc-share-email-send-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
  }

  .wc-share-email-status {
    font-size: 0.78rem;
    margin-top: 4px;
  }

  /* Recipient info block above buttons */
  .wc-share-recipient {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 10px;
    font-size: 0.8rem;
  }

  .wc-share-recipient-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .wc-share-recipient-label {
    font-weight: 500;
    color: var(--wc-muted);
  }

  .wc-share-recipient-value {
    font-weight: 600;
    color: var(--wc-text);
    max-width: 65%;
    text-align: right;
    word-break: break-all;
  }
</style>

<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content wc-share-modal-card">
      <div class="modal-header wc-share-modal-header">
        <h5 class="wc-share-modal-title">
          <span class="wc-share-modal-pill">
            <i class="fa-solid fa-share-nodes"></i>
          </span>
          Share Report
        </h5>
      </div>

      <div class="modal-body wc-share-modal-body">

        {{-- Recipient info from appointment / report --}}
        <div class="wc-share-recipient">
          <div class="wc-share-recipient-item">
            <span class="wc-share-recipient-label">WhatsApp</span>
            <span id="shareWhatsAppDisplay" class="wc-share-recipient-value">—</span>
          </div>
          <div class="wc-share-recipient-item">
            <span class="wc-share-recipient-label">Email</span>
            <span id="shareEmailDisplay" class="wc-share-recipient-value">—</span>
          </div>
        </div>

        <div class="wc-share-actions-grid">
          <button id="waShare" type="button" class="wc-share-btn-main wc-share-btn-whatsapp">
            <i class="fa-brands fa-whatsapp"></i>
            Send via WhatsApp
          </button>

          {{-- Click to reveal email form below --}}
          <button id="emailShareToggle" type="button" class="wc-share-btn-main wc-share-btn-outline">
            <i class="fa-solid fa-envelope"></i>
            Send via Email
          </button>
        </div>

        <label class="wc-share-label">Message (optional)</label>
        <textarea id="shareMessage"
          class="form-control wc-share-textarea"
          rows="2"
          placeholder="Hi, your report is ready. Please check the link below."></textarea>

        {{-- Inline email section --}}
        <div id="shareEmailSection" class="wc-share-email-section">
          <label class="wc-share-label" for="shareEmailInput">Recipient Email</label>
          <input type="email" id="shareEmailInput" class="wc-share-email-input" placeholder="name@example.com">

          <button id="shareEmailSendBtn" type="button" class="wc-share-email-send-btn">
            <i class="fa-brands fa-google"></i>
            Send Email
          </button>

          <div id="shareEmailStatus" class="wc-share-email-status"></div>
        </div>

      </div>

      <div class="modal-footer wc-share-modal-footer">
        <a id="openReport" class="wc-share-footer-btn" target="_blank">
          <i class="fa-regular fa-file-pdf"></i> Open Report
        </a>
        <button class="wc-share-footer-btn wc-share-footer-btn-secondary" data-bs-dismiss="modal">
          <i class="fa-solid fa-xmark"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

{{-- WhatsApp hidden form --}}
<form id="waShareForm" method="POST">
  @csrf
  <input type="hidden" name="report_url" id="waReportUrl">
  <input type="hidden" name="message" id="waMessage">
  <input type="hidden" name="phone" id="waPhone">
</form>

@push('scripts')
<script>
  (function() {
    let currentReportUrl = '';
    let currentShareAction = '';
    let currentDefaultMsg = '';
    let currentReportId = '';
    let currentEmail = '';
    let currentPhone = '';

    const baseEmailUrl = "{{ url('admin/reports') }}";

    const shareModalEl = document.getElementById('shareModal');
    const shareMessageEl = document.getElementById('shareMessage');
    const openReportEl = document.getElementById('openReport');

    const waReportUrlEl = document.getElementById('waReportUrl');
    const waMessageEl = document.getElementById('waMessage');
    const waPhoneEl = document.getElementById('waPhone');
    const waFormEl = document.getElementById('waShareForm');
    const waBtn = document.getElementById('waShare');

    const emailToggleBtn = document.getElementById('emailShareToggle');
    const emailSectionEl = document.getElementById('shareEmailSection');
    const emailInputEl = document.getElementById('shareEmailInput');
    const emailSendBtn = document.getElementById('shareEmailSendBtn');
    const emailStatusEl = document.getElementById('shareEmailStatus');

    const waDisplayEl = document.getElementById('shareWhatsAppDisplay');
    const emailDisplayEl = document.getElementById('shareEmailDisplay');

    function refresh() {
      const msg = (shareMessageEl.value.trim() || '');
      const link = currentReportUrl || '#';

      waReportUrlEl.value = link;
      waMessageEl.value = msg;
      openReportEl.href = link;

      if (waPhoneEl) {
        waPhoneEl.value = currentPhone || '';
      }
    }

    // Attach to any trigger that has data-share-url
    document.querySelectorAll('[data-share-url]').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();

        currentReportUrl = this.dataset.shareUrl || '';
        currentShareAction = this.dataset.shareAction || '';
        currentDefaultMsg = this.dataset.shareMessage || '';
        currentReportId = this.dataset.reportId || '';

        // read both styles: data-share-email/phone OR data-email/phone
        currentEmail = this.dataset.shareEmail || this.dataset.email || '';
        currentPhone = this.dataset.sharePhone || this.dataset.phone || '';

        shareMessageEl.value = currentDefaultMsg ||
          'Hi, your report is ready. Please check the link below.';

        waFormEl.action = currentShareAction || waFormEl.action;
        refresh();

        // Email section default state
        emailSectionEl.style.display = 'none';
        emailInputEl.value = currentEmail;
        emailStatusEl.textContent = '';
        emailStatusEl.style.color = '';

        // show recipient info
        waDisplayEl.textContent = currentPhone || 'Not available';
        emailDisplayEl.textContent = currentEmail || 'Not available';

        new bootstrap.Modal(shareModalEl).show();
      });
    });

    shareMessageEl.addEventListener('input', refresh);

    // ===== WhatsApp send with SweetAlert (no toast) =====
    waBtn.addEventListener('click', async function() {
      const phone = (currentPhone || '').trim();
      const message = (shareMessageEl.value || '').trim();
      const link = currentReportUrl || '';

      if (!waFormEl.action) {
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Share action URL missing.',
          });
        }
        return;
      }

      if (!phone) {
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'WhatsApp number missing',
            text: 'No phone number is available to send this report.',
          });
        }
        return;
      }

      // basic phone sanity check
      const digits = phone.replace(/\D/g, '');
      if (digits.length < 10) {
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Invalid number',
            text: 'Please update the patient WhatsApp number before sending.',
          });
        }
        return;
      }

      const payload = {
        report_url: link,
        message: message,
        phone: phone
      };

      const originalHtml = waBtn.innerHTML;
      waBtn.disabled = true;
      waBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';

      try {
        const res = await fetch(waFormEl.action, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
          throw new Error(data.message || 'Failed to send WhatsApp message.');
        }

        if (window.Swal) {
          Swal.fire({
            icon: 'success',
            title: 'WhatsApp sent',
            text: data.message || 'Report shared via WhatsApp successfully.',
            timer: 2000,
            showConfirmButton: false
          });
        }

        const shareInstance = bootstrap.Modal.getInstance(shareModalEl);
        if (shareInstance) shareInstance.hide();

        if (data.wa_link) {
          window.open(data.wa_link, '_blank');
        }

      } catch (err) {
        if (window.Swal) {
          Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: err.message || 'Failed to send WhatsApp message.',
          });
        }
      } finally {
        waBtn.disabled = false;
        waBtn.innerHTML = originalHtml;
      }
    });

    // Toggle email section inside modal
    emailToggleBtn.addEventListener('click', () => {
      const visible = emailSectionEl.style.display === 'block';
      emailSectionEl.style.display = visible ? 'none' : 'block';

      if (!visible) {
        if (!emailInputEl.value) {
          emailInputEl.value = currentEmail;
        }
        emailInputEl.focus();
      }
    });

    // Send email via AJAX
    emailSendBtn.addEventListener('click', function() {
      const email = (emailInputEl.value || '').trim();
      const message = (shareMessageEl.value || '').trim();

      emailStatusEl.textContent = '';
      emailStatusEl.style.color = '';

      if (!currentReportId) {
        emailStatusEl.textContent = 'Report ID missing.';
        emailStatusEl.style.color = '#b91c1c';
        return;
      }

      if (!email) {
        emailStatusEl.textContent = 'Please enter recipient email.';
        emailStatusEl.style.color = '#b91c1c';
        emailInputEl.focus();
        return;
      }

      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        emailStatusEl.textContent = 'Please enter a valid email address.';
        emailStatusEl.style.color = '#b91c1c';
        emailInputEl.focus();
        return;
      }

      const url = `${baseEmailUrl}/${currentReportId}/share-email`;

      emailSendBtn.disabled = true;
      emailSendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';

      fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            email: email,
            message: message
          })
        })
        .then(async (res) => {
          const data = await res.json().catch(() => ({}));
          if (!res.ok) throw new Error(data.message || 'Failed to send email.');
          return data;
        })
        .then((data) => {
          emailStatusEl.textContent = data.message || 'Report emailed successfully.';
          emailStatusEl.style.color = '#15803d';

          if (window.Swal) {
            Swal.fire({
              icon: 'success',
              title: 'Sent!',
              text: data.message || 'Report emailed successfully.',
              timer: 1800,
              showConfirmButton: false
            });
          }

          const shareInstance = bootstrap.Modal.getInstance(shareModalEl);
          if (shareInstance) shareInstance.hide();
        })
        .catch((err) => {
          emailStatusEl.textContent = err.message || 'Failed to send email.';
          emailStatusEl.style.color = '#b91c1c';
        })
        .finally(() => {
          emailSendBtn.disabled = false;
          emailSendBtn.innerHTML = '<i class="fa-brands fa-google"></i> Send Email';
        });
    });
  })();
</script>
@endpush