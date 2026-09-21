document.addEventListener('DOMContentLoaded', function () {
  // Banner preview
  const bannerInput      = document.getElementById('banner');
  const previewContainer = document.getElementById('bannerPreviewContainer');
  const previewImg       = document.getElementById('bannerPreview');

  if (bannerInput) {
    bannerInput.addEventListener('change', function () {
      const file = this.files && this.files[0];
      if (!file) {
        if (previewContainer) previewContainer.style.display = 'none';
        if (previewImg) previewImg.src = '#';
        return;
      }

      const allowed = ['image/jpeg','image/png','image/webp'];
      if (!allowed.includes(file.type)) {
        if (previewContainer) previewContainer.style.display = 'none';
        if (previewImg) previewImg.src = '#';
        return;
      }

      const reader = new FileReader();
      reader.onload = function (ev) {
        if (previewImg) previewImg.src = ev.target.result;
        if (previewContainer) previewContainer.style.display = 'block';
      };
      reader.readAsDataURL(file);
    });
  }

  // Special toggle pill behavior
  const pill             = document.getElementById('specialTogglePill');
  const checkbox         = document.getElementById('is_special');
  const specialLabelWrap = document.getElementById('specialLabelWrapper');
  const specialLabelInput= document.getElementById('special_label');

  function syncPillState() {
    if (!pill || !checkbox) return;
    if (checkbox.checked) pill.classList.add('active');
    else pill.classList.remove('active');
  }

  syncPillState();

  if (pill && checkbox) {
    pill.addEventListener('click', function (e) {
      e.preventDefault();
      checkbox.checked = !checkbox.checked;
      syncPillState();
      if (checkbox.checked) {
        if (specialLabelWrap) specialLabelWrap.style.display = '';
        setTimeout(() => { specialLabelInput && specialLabelInput.focus(); }, 130);
      } else {
        if (specialLabelWrap) specialLabelWrap.style.display = 'none';
        if (specialLabelInput) specialLabelInput.value = '';
      }
    });

    checkbox.addEventListener('change', function () {
      syncPillState();
      if (checkbox.checked) {
        if (specialLabelWrap) specialLabelWrap.style.display = '';
      } else {
        if (specialLabelWrap) specialLabelWrap.style.display = 'none';
        if (specialLabelInput) specialLabelInput.value = '';
      }
    });
  }

  // Custom status dropdown
  const statusShell   = document.getElementById('statusDropdown');
  const statusSelect  = document.getElementById('statusSelect');
  const statusDisplay = document.getElementById('statusDisplay');
  const statusMenu    = document.getElementById('statusMenu');
  const statusOptions = statusMenu ? statusMenu.querySelectorAll('.wc-select-option') : [];

  function setStatusValue(value) {
    if (!statusSelect || !statusDisplay) return;

    const option = Array.from(statusSelect.options).find(o => o.value === value);
    if (!option) return;

    statusSelect.value = value;
    statusDisplay.textContent = option.textContent.trim();
    statusDisplay.classList.remove('wc-select-placeholder');

    // mark active in menu
    statusOptions.forEach(btn => {
      if (btn.dataset.value === value) btn.classList.add('active');
      else btn.classList.remove('active');
    });
  }

  function getCurrentValue() {
    if (!statusSelect) return '';
    return statusSelect.value || '';
  }

  function openMenu() {
    if (!statusShell || !statusMenu) return;
    statusShell.classList.add('open');
    statusMenu.classList.add('show');
  }

  function closeMenu() {
    if (!statusShell || !statusMenu) return;
    statusShell.classList.remove('open');
    statusMenu.classList.remove('show');
  }

  function toggleMenu() {
    if (!statusMenu) return;
    if (statusMenu.classList.contains('show')) closeMenu();
    else openMenu();
  }

  // Initialize active item based on old('status')
  const initialVal = getCurrentValue();
  if (initialVal) {
    setStatusValue(initialVal);
  } else {
    statusOptions.forEach(btn => btn.classList.remove('active'));
  }

  if (statusShell && statusMenu) {
    // Click on shell (but not on menu itself)
    statusShell.addEventListener('click', function (e) {
      // if click inside menu, ignore (menu handles its own)
      if (e.target.closest('.wc-select-menu')) return;
      toggleMenu();
    });

    // Stop propagation from menu so shell click doesn't fire
    statusMenu.addEventListener('click', function (e) {
      e.stopPropagation();
    });

    // Click on option
    statusOptions.forEach(btn => {
      btn.addEventListener('click', function () {
        const val = this.dataset.value;
        setStatusValue(val);
        closeMenu();
      });
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!statusShell.contains(e.target)) {
        closeMenu();
      }
    });

    // Keyboard shortcuts D / P when shell focused
    statusShell.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        closeMenu();
        statusShell.blur();
      }
      if (e.key.toLowerCase() === 'd') {
        setStatusValue('Draft');
        closeMenu();
      }
      if (e.key.toLowerCase() === 'p') {
        setStatusValue('Published');
        closeMenu();
      }
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggleMenu();
      }
    });
  }

  // Keep native select in sync if changed programmatically
  if (statusSelect) {
    statusSelect.addEventListener('change', function () {
      if (this.value) {
        setStatusValue(this.value);
      }
    });
  }

  // Prevent double submit
  const form = document.getElementById('packageCreateForm');
  if (form) {
    form.addEventListener('submit', function () {
      const submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>Creating...</span>';
      }
    });
  }
});