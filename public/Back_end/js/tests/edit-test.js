document.addEventListener('DOMContentLoaded', function () {
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

  // Initialize status from current value
  const initialVal = getCurrentValue();
  if (initialVal) {
    setStatusValue(initialVal);
  } else {
    statusOptions.forEach(btn => btn.classList.remove('active'));
  }

  if (statusShell && statusMenu) {
    statusShell.addEventListener('click', function (e) {
      if (e.target.closest('.wc-select-menu')) return;
      toggleMenu();
    });

    statusMenu.addEventListener('click', function (e) {
      e.stopPropagation();
    });

    statusOptions.forEach(btn => {
      btn.addEventListener('click', function () {
        const val = this.dataset.value;
        setStatusValue(val);
        closeMenu();
      });
    });

    document.addEventListener('click', function (e) {
      if (!statusShell.contains(e.target)) {
        closeMenu();
      }
    });

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

  if (statusSelect) {
    statusSelect.addEventListener('change', function () {
      if (this.value) {
        setStatusValue(this.value);
      }
    });
  }

  // Prevent double submission
  const form = document.getElementById('labtestEditForm');
  if (form) {
    form.addEventListener('submit', function () {
      const btn = document.getElementById('submitBtn');
      if (btn) {
        btn.setAttribute('disabled', 'disabled');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>Updating...</span>';
        setTimeout(() => {
          if (btn.hasAttribute('disabled')) {
            btn.removeAttribute('disabled');
            btn.innerHTML = orig;
          }
        }, 6000);
      }
    });
  }
});