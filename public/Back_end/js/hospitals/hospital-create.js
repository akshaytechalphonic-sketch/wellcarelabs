// hospital-create.js
document.addEventListener('DOMContentLoaded', function() {
  // Helper functions
  function generateRandomId(length = 8) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < length; i++) {
      result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
  }

  function sanitizeUniqueId(value) {
    if (!value) return value;
    return value.toString().toUpperCase().replace(/[^A-Z0-9\-_]/g, '');
  }

  function updatePreview() {
    const uidInput = document.getElementById('uniqueId');
    const placeholder = document.getElementById('qrPlaceholder');
    const qrLinkInput = document.getElementById('qrLinkInput');
    const openLinkBtn = document.getElementById('openLinkBtn');
    const qrImg = document.getElementById('qrImg');

    // Hide image by default
    if (qrImg) {
      qrImg.style.display = 'none';
    }

    const uid = uidInput ? (uidInput.value || '').trim() : '';
    
    if (!uid) {
      if (placeholder) placeholder.style.display = 'block';
      if (qrLinkInput) qrLinkInput.value = '';
      if (openLinkBtn) {
        openLinkBtn.href = '#';
        openLinkBtn.classList.add('disabled');
        openLinkBtn.setAttribute('aria-disabled', 'true');
      }
      return;
    }

    const base = window.location.origin;
    const shortlink = base.replace(/\/$/, '') + '/h/' + encodeURIComponent(uid);
    
    if (qrLinkInput) qrLinkInput.value = shortlink;
    if (openLinkBtn) {
      openLinkBtn.href = shortlink;
      openLinkBtn.classList.remove('disabled');
      openLinkBtn.removeAttribute('aria-disabled');
    }
    if (placeholder) placeholder.style.display = 'none';
  }

  function fetchAndShowQrImageForUid(uid) {
    if (!uid) return;
    
    const base = window.location.origin;
    const shortlink = base.replace(/\/$/, '') + '/h/' + encodeURIComponent(uid);
    const qrImg = document.getElementById('qrImg');
    const placeholder = document.getElementById('qrPlaceholder');

    // Using QR code API
    const qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(shortlink);

    if (qrImg) {
      qrImg.src = qrApiUrl;
      qrImg.style.display = 'none'; // Hide until loaded
      
      qrImg.onload = function() {
        qrImg.style.display = 'block';
        if (placeholder) placeholder.style.display = 'none';
      };
      
      qrImg.onerror = function() {
        qrImg.style.display = 'none';
        if (placeholder) {
          placeholder.textContent = 'Failed to load QR image. Try again.';
          placeholder.style.display = 'block';
        }
      };
      
      // Set a timeout to handle slow loading
      setTimeout(() => {
        if (qrImg.complete && qrImg.naturalHeight !== 0) {
          qrImg.style.display = 'block';
          if (placeholder) placeholder.style.display = 'none';
        }
      }, 1000);
    }
  }

  function showFlashMessages() {
    const payload = document.getElementById('flashPayload');
    if (!payload) return;
    
    const successMsg = payload.getAttribute('data-success') || '';
    const dangerMsg = payload.getAttribute('data-danger') || '';
    
    if (successMsg && typeof Swal !== 'undefined') {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: successMsg,
        showConfirmButton: false,
        timer: 2800,
        timerProgressBar: true
      });
    } else if (dangerMsg && typeof Swal !== 'undefined') {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: dangerMsg,
        showConfirmButton: false,
        timer: 3200,
        timerProgressBar: true
      });
    }
  }

  function copyToClipboard(text) {
    if (!text) return false;
    
    return new Promise((resolve) => {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text)
          .then(() => resolve(true))
          .catch(() => {
            // Fallback method
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
              const successful = document.execCommand('copy');
              document.body.removeChild(textArea);
              resolve(successful);
            } catch (err) {
              document.body.removeChild(textArea);
              resolve(false);
            }
          });
      } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
          const successful = document.execCommand('copy');
          document.body.removeChild(textArea);
          resolve(successful);
        } catch (err) {
          document.body.removeChild(textArea);
          resolve(false);
        }
      }
    });
  }

  // Initialize form functionality
  function initHospitalCreateForm() {
    const generateBtn = document.getElementById('generateIdBtn');
    const uidInput = document.getElementById('uniqueId');
    const copyBtn = document.getElementById('copyLinkBtn');
    const form = document.getElementById('hospitalCreateForm');

    // Show flash messages on page load
    showFlashMessages();

    // Live shortlink preview
    if (uidInput) {
      uidInput.addEventListener('input', function() {
        const sanitizedValue = sanitizeUniqueId(this.value);
        if (this.value !== sanitizedValue) {
          this.value = sanitizedValue;
        }
        updatePreview();
      });
    }

    // Generate button
    if (generateBtn) {
      generateBtn.addEventListener('click', function() {
        if (!uidInput) return;
        
        try {
          if (!uidInput.value || !uidInput.value.trim()) {
            uidInput.value = generateRandomId(8);
          } else {
            uidInput.value = sanitizeUniqueId(uidInput.value || '');
          }

          updatePreview();
          
          const uid = uidInput.value.trim();
          fetchAndShowQrImageForUid(uid);
        } catch (err) {
          console.error('Generate click failed:', err);
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: 'Failed to generate ID. Please try again.',
              confirmButtonColor: '#0f9d80'
            });
          }
        }
      });
    }

    // Copy shortlink button
    if (copyBtn) {
      copyBtn.addEventListener('click', async function() {
        const qrLinkInput = document.getElementById('qrLinkInput');
        if (!qrLinkInput || !qrLinkInput.value) return;
        
        const success = await copyToClipboard(qrLinkInput.value);
        
        if (success && typeof Swal !== 'undefined') {
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Link copied to clipboard',
            showConfirmButton: false,
            timer: 1400
          });
        } else if (success) {
          alert('Link copied to clipboard');
        }
      });
    }

    // Form submission handling
    if (form) {
      form.addEventListener('submit', function(e) {
        // Sanitize unique ID before submission
        if (uidInput) {
          uidInput.value = sanitizeUniqueId(uidInput.value || '');
        }

        // Validate required fields
        const requiredInputs = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredInputs.forEach(input => {
          if (!input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
            
            // Add error message if not exists
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('field-error')) {
              const errorDiv = document.createElement('div');
              errorDiv.className = 'field-error';
              errorDiv.textContent = 'This field is required';
              input.parentNode.insertBefore(errorDiv, input.nextSibling);
            }
          } else {
            input.classList.remove('is-invalid');
            // Remove custom error messages
            if (input.nextElementSibling && input.nextElementSibling.classList.contains('field-error')) {
              input.nextElementSibling.remove();
            }
          }
        });

        if (!isValid) {
          e.preventDefault();
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Validation Error',
              text: 'Please fill in all required fields.',
              confirmButtonColor: '#0f9d80'
            });
          }
          return;
        }

        // Show loading state
        const submitBtn = form.querySelector('#submitBtn') || form.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>Creating...</span>';
        }
      });
    }

    // Remove error styling when user starts typing
    if (form) {
      form.addEventListener('input', function(e) {
        if (e.target.hasAttribute('required') && e.target.value.trim()) {
          e.target.classList.remove('is-invalid');
          // Remove custom error message
          if (e.target.nextElementSibling && e.target.nextElementSibling.classList.contains('field-error')) {
            e.target.nextElementSibling.remove();
          }
        }
      });
    }

    // Initial preview
    updatePreview();
  }

  // Initialize the form
  initHospitalCreateForm();
});