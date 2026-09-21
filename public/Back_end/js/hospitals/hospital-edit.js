// hospital-edit.js
document.addEventListener('DOMContentLoaded', function() {
  // Helper functions
  function sanitizeUniqueId(value) {
    if (!value) return value;
    return value.toString().toUpperCase().replace(/[^A-Z0-9\-_]/g, '');
  }

  function getHospitalUrl(uniqueId) {
    const base = window.location.origin;
    return base.replace(/\/$/, '') + '/h/' + encodeURIComponent(uniqueId);
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

  function updateQRPreview() {
    const uidInput = document.getElementById('unique_id');
    const openAnchor = document.getElementById('openLinkAnchor');
    
    if (!uidInput || !openAnchor) return;
    
    const uid = sanitizeUniqueId(uidInput.value);
    const hospitalUrl = getHospitalUrl(uid);
    
    openAnchor.href = hospitalUrl;
    openAnchor.textContent = hospitalUrl;
  }

  // Initialize hospital edit form
  function initHospitalEditForm() {
    const copyBtn = document.getElementById('copyLinkBtn');
    const openInNewBtn = document.getElementById('openInNewBtn');
    const form = document.getElementById('hospitalEditForm');
    const submitBtn = document.getElementById('submitBtn');
    const uidInput = document.getElementById('unique_id');

    // Show flash messages on page load
    showFlashMessages();

    // Copy link button
    if (copyBtn) {
      copyBtn.addEventListener('click', async function() {
        if (!uidInput) return;
        
        const uid = sanitizeUniqueId(uidInput.value);
        if (!uid) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'No Unique ID',
              text: 'Unique ID is missing.',
              confirmButtonColor: '#0f9d80'
            });
          }
          return;
        }
        
        const link = getHospitalUrl(uid);
        const success = await copyToClipboard(link);
        
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
        } else {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Copy Failed',
              text: 'Failed to copy link. Please try again.',
              confirmButtonColor: '#0f9d80'
            });
          }
        }
      });
    }

    // Open in new tab button
    if (openInNewBtn) {
      openInNewBtn.addEventListener('click', function() {
        if (!uidInput) return;
        
        const uid = sanitizeUniqueId(uidInput.value);
        if (!uid) {
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'No Unique ID',
              text: 'Unique ID is missing.',
              confirmButtonColor: '#0f9d80'
            });
          }
          return;
        }
        
        const link = getHospitalUrl(uid);
        window.open(link, '_blank', 'noopener,noreferrer');
      });
    }

    // Form validation and submission
    if (form) {
      form.addEventListener('submit', function(e) {
        // Sanitize unique ID before submission (even though it's readonly)
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

        // Email validation
        const emailInput = document.getElementById('email');
        if (emailInput && emailInput.value) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailRegex.test(emailInput.value)) {
            isValid = false;
            emailInput.classList.add('is-invalid');
            const errorDiv = emailInput.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('field-error')) {
              const errorDiv = document.createElement('div');
              errorDiv.className = 'field-error';
              errorDiv.textContent = 'Please enter a valid email address';
              emailInput.parentNode.insertBefore(errorDiv, emailInput.nextSibling);
            }
          }
        }

        if (!isValid) {
          e.preventDefault();
          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'error',
              title: 'Validation Error',
              text: 'Please fill in all required fields correctly.',
              confirmButtonColor: '#0f9d80'
            });
          }
          return;
        }

        // Show loading state
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>Updating...</span>';
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
        
        // Special handling for email
        if (e.target.id === 'email' && e.target.value) {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (emailRegex.test(e.target.value)) {
            e.target.classList.remove('is-invalid');
            if (e.target.nextElementSibling && e.target.nextElementSibling.classList.contains('field-error')) {
              e.target.nextElementSibling.remove();
            }
          }
        }
      });
    }

    // Update QR preview if unique ID changes (though it's readonly)
    if (uidInput) {
      uidInput.addEventListener('input', updateQRPreview);
    }

    // Initial QR preview update
    updateQRPreview();
  }

  // Initialize the form
  initHospitalEditForm();
});