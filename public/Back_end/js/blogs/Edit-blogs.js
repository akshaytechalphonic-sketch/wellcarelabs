// blog-edit.js
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

  menu.addEventListener("click", function (e) {
    e.stopPropagation();
  });

  shell.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      shell.classList.remove("open");
      menu.classList.remove("show");
      shell.blur();
    }

    if (e.key.toLowerCase() === "d") {
      setStatus("Draft");
      shell.classList.remove("open");
      menu.classList.remove("show");
      e.preventDefault();
    }

    if (e.key.toLowerCase() === "p") {
      setStatus("Published");
      shell.classList.remove("open");
      menu.classList.remove("show");
      e.preventDefault();
    }

    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      shell.classList.toggle("open");
      menu.classList.toggle("show");
    }
  });

  if (select.value) {
    setStatus(select.value);
  }

  /* =========================
     IMAGE PREVIEW (NO VALIDATION)
  ========================== */
  const imageInput = document.getElementById("featuredImageInput");
  const newPreview = document.getElementById("newImagePreview");
  const newImg = newPreview?.querySelector("img");
  const removeCheckbox = document.getElementById("removeImageCheckbox");
  const currentImage = document.getElementById("currentImage");

  if (imageInput && newPreview && newImg) {
    imageInput.addEventListener("change", function () {
      if (!this.files || !this.files[0]) {
        newPreview.style.display = "none";
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        newImg.src = e.target.result;
        newPreview.style.display = "block";
      };
      reader.readAsDataURL(this.files[0]);
    });
  }

  /* =========================
     IMAGE REMOVE VISUAL ONLY
  ========================== */
  if (removeCheckbox && currentImage) {
    removeCheckbox.addEventListener("change", function () {
      if (this.checked) {
        currentImage.style.opacity = "0.4";
        currentImage.style.filter = "grayscale(1)";
      } else {
        currentImage.style.opacity = "1";
        currentImage.style.filter = "none";
      }
    });
  }

  /* =========================
     FORM SUBMIT (UI STATE ONLY)
  ========================== */
  const form = document.getElementById("blogEditForm");
  const btn = document.getElementById("blogUpdateBtn");

  if (form && btn) {
    form.addEventListener("submit", function () {
      btn.disabled = true;
      btn.innerHTML =
        '<i class="fa-solid fa-spinner fa-spin me-1"></i><span>Updating...</span>';
    });
  }

  /* =========================
     AUTO DISMISS SUCCESS ALERT
  ========================== */
  const successAlert = document.querySelector(".alert-success");
  if (successAlert && typeof bootstrap !== "undefined") {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(successAlert);
      bsAlert.close();
    }, 5000);
  }
});
