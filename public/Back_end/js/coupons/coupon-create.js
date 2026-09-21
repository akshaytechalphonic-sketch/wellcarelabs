document.addEventListener("DOMContentLoaded", function () {

  // ====== ACTIVE SWITCH ======
  const activeSwitch = document.getElementById("activeSwitch");
  const activeCheckbox = document.getElementById("is_active");

  function syncActiveSwitch() {
    if (!activeSwitch || !activeCheckbox) return;

    // Add/remove class for green background
    activeSwitch.classList.toggle("active", activeCheckbox.checked);

    // Update text
    const textEl = document.getElementById("activeStatusText");
    if (textEl) {
      textEl.textContent = activeCheckbox.checked ? "Active" : "Inactive";
      textEl.style.color = activeCheckbox.checked ? "#0f9d80" : "#dc3545";
    }
  }

  if (activeCheckbox) {
    activeCheckbox.addEventListener("change", syncActiveSwitch);
    syncActiveSwitch();
  }


  // ====== FRONTEND SWITCH ======
  const frontendSwitch = document.getElementById("frontendSwitch");
  const frontendCheckbox = document.getElementById("show_on_frontend");

  function syncFrontendSwitch() {
    if (!frontendSwitch || !frontendCheckbox) return;

    // Add/remove class for green background
    frontendSwitch.classList.toggle("active", frontendCheckbox.checked);

    // Update text
    const textEl = document.getElementById("frontendStatusText");
    if (textEl) {
      textEl.textContent = frontendCheckbox.checked ? "Yes" : "No";
      textEl.style.color = frontendCheckbox.checked ? "#0f9d80" : "#dc3545";
    }
  }

  if (frontendCheckbox) {
    frontendCheckbox.addEventListener("change", syncFrontendSwitch);
    syncFrontendSwitch();
  }


  // ====== VALUE HINTS + CONSTRAINTS ======
  const typeSelect = document.getElementById("couponType");
  const valueInput = document.getElementById("couponValue");
  const valueLabel = document.getElementById("valueLabel");
  const valueHelp = document.getElementById("valueHelp");

  function applyValueConstraints() {
    const t = (typeSelect?.value || "").toLowerCase();

    if (!t) {
      if (valueLabel) valueLabel.textContent = "Value";
      if (valueHelp) valueHelp.textContent = "Enter percentage or amount.";
      valueInput?.removeAttribute("max");
      valueInput?.setAttribute("step", "0.01");
      return;
    }

    if (t === "percent") {
      if (valueLabel) valueLabel.textContent = "Value (%)";
      if (valueHelp) valueHelp.textContent = "Enter percentage (0–100).";
      valueInput?.setAttribute("min", "0");
      valueInput?.setAttribute("max", "100");
      valueInput?.setAttribute("step", "0.01");
    } else {
      if (valueLabel) valueLabel.textContent = "Value (₹)";
      if (valueHelp) valueHelp.textContent = "Enter flat amount in ₹.";
      valueInput?.setAttribute("min", "0");
      valueInput?.removeAttribute("max");
      valueInput?.setAttribute("step", "0.01");
    }
  }

  if (typeSelect && valueInput) {
    typeSelect.addEventListener("change", applyValueConstraints);
    applyValueConstraints();
  }


  // ====== SUBMIT GUARD ======
  const form = document.getElementById("couponForm");

  if (form) {
    form.addEventListener("submit", function (e) {
      const btn = form.querySelector('button[type="submit"]');
      const starts = form.querySelector('input[name="starts_at"]')?.value;
      const expires = form.querySelector('input[name="expires_at"]')?.value;

      // Validate percentage range
      if (typeSelect?.value?.toLowerCase() === "percent" && valueInput) {
        const v = parseFloat(valueInput.value);
        if (!isNaN(v)) {
          if (v < 0) valueInput.value = 0;
          if (v > 100) valueInput.value = 100;
        }
      }

      // Validate date order
      if (starts && expires && new Date(starts) > new Date(expires)) {
        e.preventDefault();
        alert("Expires At must be after Starts At.");
        return;
      }

      // Prevent double submit
      if (btn) {
        btn.setAttribute("disabled", "disabled");
        const original = btn.innerHTML;
        btn.dataset.original = original;

        const isUpdate = btn.textContent.includes("Update");
        btn.innerHTML =
          '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>' +
          (isUpdate ? "Updating..." : "Creating...") +
          "</span>";

        setTimeout(() => {
          if (btn.hasAttribute("disabled")) {
            btn.removeAttribute("disabled");
            btn.innerHTML = btn.dataset.original || original;
          }
        }, 3000);
      }
    });
  }

});
