// ===== Highlight search text in code column (Coupon Listing Page) =====
function highlightQuery() {
  try {
    const params = new URLSearchParams(window.location.search);
    const q = (params.get("q") || "").trim();
    if (!q) return;

    const esc = q.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const re = new RegExp(esc, "ig");

    document.querySelectorAll("#couponAdminBody .coupon-code").forEach((el) => {
      const text = el.textContent || "";
      el.innerHTML = text.replace(
        re,
        (match) =>
          `<mark style="background:#fffb8f;color:#000;padding:0 .12rem;border-radius:2px">${match}</mark>`
      );
    });
  } catch (err) {
    console.warn("Highlight failed", err);
  }
}

// ===== Coupon Create/Edit Switch UI (Active + Show on Frontend) =====
function initCouponSwitches() {
  function bindSwitch(labelId, checkboxId, textId, onText, offText) {
    const label = document.getElementById(labelId);
    const checkbox = document.getElementById(checkboxId);
    const text = document.getElementById(textId);

    // if not found on this page, skip
    if (!label || !checkbox) return;

    const update = () => {
      // background state
      if (checkbox.checked) {
        label.classList.add("is-on");
      } else {
        label.classList.remove("is-on");
      }

      // text state
      if (text) {
        text.textContent = checkbox.checked ? onText : offText;
        text.style.color = checkbox.checked ? "#0f9d80" : "#dc3545";
      }
    };

    checkbox.addEventListener("change", update);
    update();
  }

  bindSwitch("activeSwitch", "is_active", "activeStatusText", "Active", "Inactive");
  bindSwitch("frontendSwitch", "show_on_frontend", "frontendStatusText", "Yes", "No");
}

// ===== Initialize =====
document.addEventListener("DOMContentLoaded", function () {
  highlightQuery();      // works only on coupon listing page
  initCouponSwitches();  // works only on create/edit page
});
