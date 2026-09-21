// hospitals.js - With working send password reset email
document.addEventListener("DOMContentLoaded", function () {
  // Initialize Feather icons
  if (window.feather) {
    try {
      feather.replace();
    } catch (e) {
      console.warn("Feather icons not available");
    }
  }

  const WELLCARE_LOGO = "{{ asset('assets/images/gallery/Welcare_labs.png') }}";
  const overlay = document.getElementById("pdfDownloadOverlay");

  // Utility functions
  function showDownloadSpinner() {
    if (!overlay) return;
    overlay.classList.add("show");
    overlay.setAttribute("aria-hidden", "false");
  }

  function hideDownloadSpinner() {
    if (!overlay) return;
    overlay.classList.remove("show");
    setTimeout(() => overlay.setAttribute("aria-hidden", "true"), 260);
  }

  function escapeHtml(unsafe) {
    return String(unsafe)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  function formatDate(dateString) {
    try {
      const date = new Date(dateString);
      return date.toLocaleString("en-IN", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    } catch (e) {
      return dateString || "";
    }
  }

  // ===== Send Password Reset Email Function =====
  function sendPasswordResetEmail(hospitalId) {
    if (!hospitalId) return;

    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");
    const url = `/admin/hospitals/${hospitalId}/send-reset-link`;

    if (typeof Swal !== "undefined") {
      Swal.fire({
        title: "Send password reset email?",
        text: "We will send a password reset link to this hospital's login email.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, send email",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#0f9d80",
        cancelButtonColor: "#6b7280",
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: async () => {
          try {
            const response = await fetch(url, {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN":
                  csrfToken ||
                  document.querySelector('input[name="_token"]')?.value ||
                  "",
              },
              credentials: "same-origin",
            });

            const data = await response.json();

            if (!response.ok) {
              throw new Error(
                data.message || `Request failed with status ${response.status}`,
              );
            }

            return data;
          } catch (error) {
            Swal.showValidationMessage(`Request failed: ${error.message}`);
            return null;
          }
        },
        allowOutsideClick: () => !Swal.isLoading(),
      }).then((result) => {
        if (result.isConfirmed) {
          const data = result.value;
          if (data && data.success) {
            Swal.fire({
              icon: "success",
              title: "Email Sent!",
              text:
                data.message ||
                "Password reset email has been sent successfully.",
              confirmButtonColor: "#0f9d80",
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Failed",
              text: data?.message || "Failed to send email. Please try again.",
              confirmButtonColor: "#0f9d80",
            });
          }
        }
      });
    } else {
      // Fallback to form submission
      const resetForm = document.getElementById("adminHospitalResetForm");
      if (resetForm) {
        resetForm.action = url;
        if (confirm("Send password reset email to this hospital?")) {
          resetForm.submit();
        }
      }
    }
  }

  // ===== Hospital View Modal =====
  function initHospitalViewModal() {
    const modalEl = document.getElementById("hospitalViewModal");
    if (!modalEl) return;

    let bsModal = null;
    try {
      if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
        bsModal = new bootstrap.Modal(modalEl, {
          keyboard: true,
        });
      } else {
        console.warn("Bootstrap Modal not available");
        return;
      }
    } catch (error) {
      console.error("Failed to initialize Bootstrap modal:", error);
      return;
    }

    const loadingEl = document.getElementById("hospitalViewLoading");
    const contentEl = document.getElementById("hospitalViewContent");
    const nameEl = document.getElementById("hospitalViewName");
    const idUniqueEl = document.getElementById("hospitalViewIdUnique");
    const phoneEl = document.getElementById("hospitalViewPhone");
    const emailEl = document.getElementById("hospitalViewEmail");
    const ownerNameEl = document.getElementById("hospitalViewOwnerName");
    const ownerMobileEl = document.getElementById("hospitalViewOwnerMobile");
    const doctorNameEl = document.getElementById("hospitalViewDoctorName");
    const doctorMobileEl = document.getElementById("hospitalViewDoctorMobile");
    const addressEl = document.getElementById("hospitalViewAddress");
    const timestampsEl = document.getElementById("hospitalViewTimestamps");
    const linkEl = document.getElementById("hospitalViewLink");
    const sendResetBtn = document.getElementById("sendResetLinkBtn");

    let currentHospitalId = null;

    function resetModal() {
      if (loadingEl) loadingEl.style.display = "";
      if (contentEl) contentEl.style.display = "none";
      if (nameEl) nameEl.textContent = "";
      if (idUniqueEl) idUniqueEl.textContent = "";
      if (phoneEl) phoneEl.textContent = "";
      if (emailEl) emailEl.textContent = "";
      if (ownerNameEl) ownerNameEl.textContent = "";
      if (ownerMobileEl) ownerMobileEl.textContent = "";
      if (doctorNameEl) doctorNameEl.textContent = "";
      if (doctorMobileEl) doctorMobileEl.textContent = "";
      if (addressEl) addressEl.textContent = "";
      if (timestampsEl) timestampsEl.textContent = "";
      if (linkEl) linkEl.innerHTML = "";

      currentHospitalId = null;
    }

    async function fetchAndShow(url, hospitalId) {
      resetModal();
      bsModal.show();

      try {
        const res = await fetch(url, {
          method: "GET",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
            Accept: "application/json",
          },
          credentials: "same-origin",
        });

        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }

        const data = await res.json();
        const hospital = data?.data || data;

        if (nameEl) nameEl.textContent = hospital.name || "—";
        if (idUniqueEl) {
          idUniqueEl.textContent =
            "ID: " +
            (hospital.id ?? "—") +
            (hospital.unique_id ? " • Unique ID: " + hospital.unique_id : "");
        }
        if (phoneEl) phoneEl.textContent = hospital.phone || "—";
        if (emailEl) emailEl.textContent = hospital.email || "—";
        if (ownerNameEl) ownerNameEl.textContent = hospital.owner_name || "—";
        if (ownerMobileEl)
          ownerMobileEl.textContent = hospital.owner_mobile || "—";
        if (doctorNameEl)
          doctorNameEl.textContent = hospital.doctor_name || "—";
        if (doctorMobileEl)
          doctorMobileEl.textContent = hospital.doctor_mobile || "—";
        if (addressEl) addressEl.textContent = hospital.address || "—";

        if (timestampsEl) {
          const created = hospital.created_at
            ? formatDate(hospital.created_at)
            : null;
          const updated = hospital.updated_at
            ? formatDate(hospital.updated_at)
            : null;
          timestampsEl.textContent =
            (created ? "Created: " + created : "") +
            (created && updated ? " • " : "") +
            (updated ? "Updated: " + updated : "");
        }

        if (linkEl && hospital.unique_id) {
          const urlQr =
            window.location.origin +
            "/?ref=" +
            encodeURIComponent(hospital.unique_id);
          linkEl.innerHTML = `<a href="${urlQr}" target="_blank" class="text-decoration-none">${escapeHtml(urlQr)}</a>`;
        } else if (linkEl) {
          linkEl.innerHTML = '<span class="text-muted">Not available</span>';
        }

        currentHospitalId = hospitalId;

        if (loadingEl) loadingEl.style.display = "none";
        if (contentEl) contentEl.style.display = "block";
      } catch (err) {
        console.error("Failed to fetch hospital:", err);
        const fallbackUrl = "{{ url('/admin/hospitals') }}/" + hospitalId;
        bsModal.hide();
        window.location.href = fallbackUrl;
      }
    }

    // Event delegation for view buttons
    document.addEventListener(
      "click",
      function (ev) {
        const btn = ev.target.closest(".view-hospital-btn");
        if (!btn) return;
        ev.preventDefault();
        ev.stopPropagation();

        const hospitalId = btn.dataset.hospitalId;
        const url = btn.dataset.url || "/admin/hospitals/" + hospitalId;
        fetchAndShow(url, hospitalId);
      },
      true,
    );

    // Send password reset email
    if (sendResetBtn) {
      sendResetBtn.addEventListener("click", function () {
        if (!currentHospitalId) {
          if (typeof Swal !== "undefined") {
            Swal.fire({
              icon: "warning",
              title: "No Hospital Selected",
              text: "Please select a hospital first.",
              confirmButtonColor: "#0f9d80",
            });
          }
          return;
        }

        sendPasswordResetEmail(currentHospitalId);
      });
    }
  }

  // ===== QR Preview Modal =====
  function initQRPreviewModal() {
    const modalEl = document.getElementById("qrPreviewModal");
    if (!modalEl) return;

    let bsModal = null;
    try {
      if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
        bsModal = new bootstrap.Modal(modalEl, {
          keyboard: true,
        });
      } else {
        console.warn("Bootstrap Modal not available");
        return;
      }
    } catch (error) {
      console.error("Failed to initialize Bootstrap modal:", error);
      return;
    }

    const container = document.getElementById("qrPreviewContainer");
    const titleEl = document.getElementById("qrPreviewModalLabel");

    function openPreview(trigger) {
      if (!trigger || !container) return;

      const hospitalName = trigger.dataset.hospitalName || "Hospital QR";

      if (titleEl) {
        titleEl.textContent = hospitalName;
      }

      container.innerHTML = "";

      const clone = trigger.cloneNode(true);
      clone.id = "";
      clone.classList.remove("qr-preview-trigger");
      clone.style.width = "260px";
      clone.style.height = "260px";
      clone.style.minWidth = "260px";
      clone.style.padding = "12px";
      clone.style.background = "#f9fafb";
      clone.style.borderRadius = "14px";
      clone.style.margin = "0 auto";
      clone.style.cursor = "default";

      container.appendChild(clone);
      bsModal.show();
    }

    // Handle mouse click
    document.addEventListener("click", function (ev) {
      const trigger = ev.target.closest(".qr-preview-trigger");
      if (!trigger) return;
      ev.preventDefault();
      ev.stopPropagation();
      openPreview(trigger);
    });

    // Handle keyboard (Enter/Space)
    document.addEventListener("keydown", function (ev) {
      if (ev.key !== "Enter" && ev.key !== " ") return;
      const trigger = ev.target.closest(".qr-preview-trigger");
      if (!trigger) return;
      ev.preventDefault();
      ev.stopPropagation();
      openPreview(trigger);
    });
  }

  // ===== PDF Download Functionality =====
  async function downloadQrPdf(hospitalId, hospitalName) {
    const triggerBtn =
      document.activeElement &&
      (document.activeElement.tagName === "BUTTON" ||
        document.activeElement.tagName === "A")
        ? document.activeElement
        : null;

    try {
      showDownloadSpinner();
      if (triggerBtn) {
        triggerBtn.disabled = true;
        triggerBtn.setAttribute("aria-busy", "true");
      }

      const el = document.getElementById("qr-" + hospitalId);
      if (!el) {
        if (typeof Swal !== "undefined") {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "QR element not found.",
          });
        } else {
          alert("QR element not found.");
        }
        return;
      }

      const qrClone = el.cloneNode(true);
      qrClone.style.background = "#ffffff";
      qrClone.style.display = "flex";
      qrClone.style.alignItems = "center";
      qrClone.style.justifyContent = "center";
      qrClone.style.padding = "50px";
      qrClone.style.border = "1px solid #e5e7eb";
      qrClone.style.borderRadius = "12px";
      qrClone.style.width = "420px";
      qrClone.style.height = "420px";
      qrClone.style.margin = "0 auto";

      const imgLogo = document.createElement("img");
      imgLogo.src = WELLCARE_LOGO;
      imgLogo.alt = "Wellcare Labs";
      imgLogo.style.display = "block";
      imgLogo.style.margin = "6px auto 12px auto";
      imgLogo.style.maxWidth = "160px";
      imgLogo.style.height = "auto";

      const textWrap = document.createElement("div");
      textWrap.style.textAlign = "center";
      textWrap.style.marginTop = "18px";
      textWrap.innerHTML = `
        <h2 style="font-size:18px; color:#0b2b3a; margin:6px 0 4px 0; font-weight:700;">${escapeHtml(hospitalName)}</h2>
        <p style="font-size:12px; color:#6b7280; margin:0;">
          Wellcare Labs — Scan to open website & book tests
        </p>
      `;

      const container = document.createElement("div");
      container.style.background = "#ffffff";
      container.style.padding = "26px";
      container.style.textAlign = "center";
      container.style.width = "520px";
      container.style.boxSizing = "border-box";
      container.style.borderRadius = "8px";

      container.appendChild(imgLogo);
      container.appendChild(qrClone);
      container.appendChild(textWrap);

      const wrapper = document.createElement("div");
      wrapper.style.position = "fixed";
      wrapper.style.left = "-9999px";
      wrapper.appendChild(container);
      document.body.appendChild(wrapper);

      const canvas = await html2canvas(container, {
        scale: 4,
        backgroundColor: "#ffffff",
        useCORS: true,
        logging: false,
      });
      document.body.removeChild(wrapper);

      const imgData = canvas.toDataURL("image/png");

      if (typeof jspdf === "undefined" || !jspdf.jsPDF) {
        throw new Error("jsPDF not loaded");
      }

      const { jsPDF } = jspdf;
      const pdf = new jsPDF({
        orientation: "portrait",
        unit: "pt",
        format: "a4",
      });

      const pdfWidth = pdf.internal.pageSize.getWidth();
      const pdfHeight = pdf.internal.pageSize.getHeight();

      const margin = 40;
      let imgWidth = pdfWidth - margin * 2;
      let imgHeight = (canvas.height / canvas.width) * imgWidth;

      if (imgHeight > pdfHeight - margin * 2) {
        const scale = (pdfHeight - margin * 2) / imgHeight;
        imgWidth = imgWidth * scale;
        imgHeight = imgHeight * scale;
      }

      const x = (pdfWidth - imgWidth) / 2;
      const y = margin;

      pdf.addImage(imgData, "PNG", x, y, imgWidth, imgHeight);
      const safeName = hospitalName
        ? hospitalName.replace(/[^a-z0-9_\-]/gi, "_")
        : hospitalId;

      // Small delay to ensure PDF is ready
      await new Promise((resolve) => setTimeout(resolve, 300));

      pdf.save(`hospital-${hospitalId}-${safeName}-qr.pdf`);

      if (typeof Swal !== "undefined") {
        Swal.fire({
          icon: "success",
          title: "PDF Downloaded",
          text: "QR code PDF has been downloaded successfully.",
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
        });
      }
    } catch (err) {
      console.error("PDF generation error:", err);
      if (typeof Swal !== "undefined") {
        Swal.fire({
          icon: "error",
          title: "PDF Generation Failed",
          text: "Could not generate PDF. Please try again.",
        });
      } else {
        alert("Could not generate PDF. Please try again.");
      }
    } finally {
      hideDownloadSpinner();
      if (triggerBtn) {
        triggerBtn.disabled = false;
        triggerBtn.removeAttribute("aria-busy");
      }
    }
  }

  // Copy link functionality
  document.addEventListener("click", function (e) {
    const copyBtn = e.target.closest(".copy-link-btn");
    if (copyBtn) {
      e.preventDefault();
      const link = copyBtn.getAttribute("data-link");
      if (link) {
        navigator.clipboard
          .writeText(link)
          .then(() => {
            if (typeof Swal !== "undefined") {
              Swal.fire({
                icon: "success",
                title: "Copied!",
                text: "Link copied to clipboard",
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
              });
            } else {
              alert("Link copied to clipboard");
            }
          })
          .catch((err) => {
            console.error("Failed to copy link:", err);
          });
      }
    }
  });

  // Delete confirmation
  // ===== DELETE WITH OTP (Hospital Manager Approval) =====
  document.addEventListener("click", function (e) {
    const deleteBtn = e.target.closest(".delete-btn");
    if (!deleteBtn) return;

    e.preventDefault();
    e.stopPropagation();

    const row = deleteBtn.closest(".hospital-row");
    const hospitalId = row?.dataset?.hospitalId;
    const hospitalName =
      deleteBtn.getAttribute("data-hospital-name") || "this hospital";

    if (!hospitalId) return;

    const csrfToken = document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");

    // STEP 1: Confirm send OTP
    Swal.fire({
      title: "Delete Hospital?",
      html: `
    <p>You are about to delete <strong>${hospitalName}</strong>.</p>
    <p>An OTP will be sent to your <strong>admin email</strong>.</p>
  `,
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Send OTP",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#dc2626",
      cancelButtonColor: "#6b7280",
      reverseButtons: true,

      // ✅ PREVENT CLOSING ON BLANK CLICK / ESC
      allowOutsideClick: false,
      allowEscapeKey: false,
    }).then(async (result) => {
      if (!result.isConfirmed) return;

      // STEP 2: Send OTP
      try {
        const sendRes = await fetch(
          `/admin/hospitals/${hospitalId}/send-delete-otp`,
          {
            method: "POST",
            headers: {
              Accept: "application/json",
              "X-CSRF-TOKEN": csrfToken,
            },
            credentials: "same-origin",
          },
        );

        const sendData = await sendRes.json();
        if (!sendRes.ok || !sendData.success) {
          throw new Error(sendData.message || "Failed to send OTP");
        }

        // STEP 3: Ask for OTP
        Swal.fire({
          title: "Enter OTP",
          text: "Enter the 6-digit OTP sent to your admin email.",
          input: "text",
          inputAttributes: {
            maxlength: 6,
            inputmode: "numeric",
            autocapitalize: "off",
            autocorrect: "off",
          },
          showCancelButton: true,
          confirmButtonText: "Verify & Delete",
          confirmButtonColor: "#dc2626",
          showLoaderOnConfirm: true,
          preConfirm: async (otp) => {
            if (!otp || otp.length !== 6) {
              Swal.showValidationMessage("Please enter a valid 6-digit OTP");
              return false;
            }

            try {
              const verifyRes = await fetch(
                `/admin/hospitals/${hospitalId}/verify-delete-otp`,
                {
                  method: "POST",
                  headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                  },
                  credentials: "same-origin",
                  body: JSON.stringify({ otp }),
                },
              );

              const verifyData = await verifyRes.json();

              if (!verifyRes.ok || !verifyData.success) {
                // ✅ THIS SHOWS THE ERROR INSIDE THE SAME MODAL
                Swal.showValidationMessage(
                  verifyData.message || "Wrong OTP. Please try again.",
                );
                return false;
              }

              return verifyData;
            } catch (err) {
              Swal.showValidationMessage(
                "Something went wrong. Please try again.",
              );
              return false;
            }
          },

          allowOutsideClick: false,
          allowEscapeKey: false,
        }).then((finalResult) => {
          if (finalResult.isConfirmed) {
            Swal.fire({
              icon: "success",
              title: "Hospital Deleted",
              text:
                finalResult.value.message || "Hospital deleted successfully.",
              confirmButtonColor: "#0f9d80",
            }).then(() => {
              location.reload();
            });
          }
        });
      } catch (err) {
        Swal.fire({
          icon: "error",
          title: "Delete Failed",
          text: err.message || "Something went wrong. Please try again.",
        });
      }
    });
  });

  // Event delegation for download buttons
  document.addEventListener("click", function (e) {
    const downloadBtn = e.target.closest(".download-qr-btn");
    if (downloadBtn) {
      e.preventDefault();
      e.stopPropagation();

      const hospitalId = downloadBtn.getAttribute("data-hospital-id");
      const hospitalName = downloadBtn.getAttribute("data-hospital-name");

      if (hospitalId) {
        downloadQrPdf(hospitalId, hospitalName);
      }
    }
  });

  // Expose downloadQrPdf to global scope
  window.downloadQrPdf = downloadQrPdf;

  // Initialize modals
  initHospitalViewModal();
  initQRPreviewModal();
});
