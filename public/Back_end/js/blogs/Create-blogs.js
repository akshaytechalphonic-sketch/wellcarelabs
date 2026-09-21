// blog-create.js
document.addEventListener("DOMContentLoaded", function () {
  /* =========================
     STATUS DROPDOWN (UI ONLY)
  ========================== */
  const shell = document.getElementById("statusDropdown");
  const select = document.getElementById("statusSelect");
  const display = document.getElementById("statusDisplay");
  const menu = document.getElementById("statusMenu");
  const options = menu.querySelectorAll(".wc-select-option");

  function setStatus(value) {
    select.value = value;
    display.textContent = value;
    display.classList.remove("wc-select-placeholder");

    options.forEach((option) => {
      option.classList.toggle("active", option.dataset.value === value);
    });
  }

  shell.addEventListener("click", function (e) {
    if (!e.target.closest(".wc-select-menu")) {
      shell.classList.toggle("open");
      menu.classList.toggle("show");
    }
  });

  options.forEach((option) => {
    option.addEventListener("click", function () {
      setStatus(this.dataset.value);
      shell.classList.remove("open");
      menu.classList.remove("show");
    });
  });

  document.addEventListener("click", function (e) {
    if (!shell.contains(e.target)) {
      shell.classList.remove("open");
      menu.classList.remove("show");
    }
  });

  if (select.value) {
    setStatus(select.value);
  }

  /* =========================
     IMAGE PREVIEW ONLY
     (NO VALIDATION)
  ========================== */
  const imageInput = document.getElementById("featuredImageInput");
  const preview = document.getElementById("featuredImagePreview");
  const img = preview.querySelector("img");

  imageInput.addEventListener("change", function () {
    if (!this.files || !this.files[0]) {
      preview.style.display = "none";
      return;
    }

    const file = this.files[0];

    const reader = new FileReader();
    reader.onload = function (e) {
      img.src = e.target.result;
      preview.style.display = "block";
    };
    reader.readAsDataURL(file);
  });

  /* =========================
     FORM SUBMIT (UI STATE ONLY)
  ========================== */
  const form = document.getElementById("blogCreateForm");
  const btn = document.getElementById("blogCreateBtn");

  form.addEventListener("submit", function () {
    btn.disabled = true;
    btn.innerHTML =
      '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>Creating...</span>';
  });
});
