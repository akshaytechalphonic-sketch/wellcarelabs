// resources/js/admin-alerts.js
document.addEventListener("DOMContentLoaded", function () {
  // ===== Bootstrap Toast Alerts (auto-hide) =====
  const AUTO_HIDE_DELAY = 4000;

  function showToast(id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (typeof bootstrap !== "undefined" && bootstrap.Toast) {
      new bootstrap.Toast(el, { delay: AUTO_HIDE_DELAY }).show();
    } else {
      setTimeout(() => {
        el.style.transition = "opacity 0.5s ease";
        el.style.opacity = "0";
        setTimeout(() => el.remove(), 600);
      }, AUTO_HIDE_DELAY);
    }
  }

  showToast("packageSuccessToast");
  showToast("packageDangerToast");

  // ===== Delete confirmation (SweetAlert2 or fallback confirm) =====
  document.body.addEventListener(
    "click",
    function (e) {
      const btn = e.target.closest(".delete-btn");
      if (!btn) return;
      e.preventDefault();

      const form = btn.closest("form.delete-form");
      const name = btn.getAttribute("data-package-name") || "this item";
      const message = `Are you sure you want to delete ${name}? This action cannot be undone.`;

      const submitForm = () => {
        btn.disabled = true;
        form.submit();
      };

      if (typeof Swal !== "undefined") {
        Swal.fire({
          title: "Confirm Delete",
          text: message,
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Yes, Delete",
          cancelButtonText: "Cancel",
          reverseButtons: true,
        }).then((result) => {
          if (result.isConfirmed) submitForm();
        });
      } else {
        if (confirm(message)) submitForm();
      }
    },
    true
  );

  // ===== Highlight search term (optional) =====
  (function highlightQuery() {
    try {
      const params = new URLSearchParams(window.location.search);
      const q = (params.get("q") || "").trim();
      if (!q) return;
      const esc = q.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
      const re = new RegExp(esc, "ig");

      document
        .querySelectorAll(
          "#packageAdminBody .package-title, #packageAdminBody .package-content"
        )
        .forEach((el) => {
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
  })();
});
