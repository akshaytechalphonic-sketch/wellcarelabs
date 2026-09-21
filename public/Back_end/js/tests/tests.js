// Back_end/js/tests/tests.js - Lab Tests View Modal functionality
document.addEventListener("DOMContentLoaded", function () {
  // Check if modal exists
  const modalEl = document.getElementById("labtestViewModal");
  if (!modalEl) {
    return;
  }

  // Store modal instance
  let bsModal = null;

  // Initialize Bootstrap modal with fallback
  function initModal() {
    // Check if Bootstrap is available
    if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
      bsModal = new bootstrap.Modal(modalEl, {
        keyboard: true,
        backdrop: true,
      });
    } else {
      // Create a simple vanilla JS modal fallback
      bsModal = {
        _backdrop: null,
        _escHandler: null,

        show: function () {
          modalEl.style.display = "block";
          modalEl.classList.add("show");
          document.body.classList.add("modal-open");

          // Add backdrop
          this._backdrop = document.createElement("div");
          this._backdrop.className = "modal-backdrop fade show";
          document.body.appendChild(this._backdrop);

          // Close on backdrop click
          this._backdrop.onclick = () => this.hide();

          // Escape key to close
          this._escHandler = (e) => {
            if (e.key === "Escape") this.hide();
          };
          document.addEventListener("keydown", this._escHandler);

          // Trigger shown event
          setTimeout(() => {
            modalEl.classList.add("fade-in");
          }, 10);
        },

        hide: function () {
          modalEl.style.display = "none";
          modalEl.classList.remove("show", "fade-in");
          document.body.classList.remove("modal-open");

          // Remove backdrop
          if (this._backdrop) {
            this._backdrop.remove();
            this._backdrop = null;
          }

          // Remove escape handler
          if (this._escHandler) {
            document.removeEventListener("keydown", this._escHandler);
            this._escHandler = null;
          }

          // Reset modal content
          resetModal();
        },
      };
    }
  }

  // Initialize modal
  initModal();
  function initLabtestDropdowns(scope = document) {
    if (typeof bootstrap === "undefined" || !bootstrap.Dropdown) return;

    scope.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((el) => {
      bootstrap.Dropdown.getOrCreateInstance(el);
    });
  }

  // after initModal()
  initLabtestDropdowns(document);

  // fallback click handler
  document.addEventListener("click", function (e) {
    const btn = e.target.closest('[data-bs-toggle="dropdown"]');
    if (!btn) return;

    if (typeof bootstrap !== "undefined" && bootstrap.Dropdown) {
      bootstrap.Dropdown.getOrCreateInstance(btn).toggle();
    }
  });

  // Modal elements
  const loadingEl = document.getElementById("labtestViewLoading");
  const contentEl = document.getElementById("labtestViewContent");
  const errorEl = document.getElementById("labtestViewError");
  const errorTextEl = document.getElementById("labtestViewErrorText");
  const titleEl = document.getElementById("labtestViewTitle");
  const statusBadgeEl = document.getElementById("labtestViewStatusBadge");
  const timestampsEl = document.getElementById("labtestViewTimestamps");
  const bodyHtmlEl = document.getElementById("labtestViewBodyHtml");
  const mrpEl = document.getElementById("labtestViewMrp");
  const b2bEl = document.getElementById("labtestViewB2b");
  const discountedEl = document.getElementById("labtestViewDiscounted");
  const extrasEl = document.getElementById("labtestViewExtras");

  // Helper functions
  const escapeHtml = (unsafe) => {
    if (!unsafe) return "";
    return String(unsafe)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  };

  const numberFormat = (n) => {
    try {
      const num = Number(n);
      return num.toLocaleString("en-IN", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    } catch (e) {
      return String(n);
    }
  };

  const formatDate = (s) => {
    try {
      const d = new Date(s);
      return d.toLocaleString("en-IN", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      });
    } catch (e) {
      return s;
    }
  };

  // Reset modal to initial state
  function resetModal() {
    if (loadingEl) loadingEl.style.display = "";
    if (contentEl) contentEl.style.display = "none";
    if (errorEl) errorEl.style.display = "none";
    if (titleEl) titleEl.textContent = "Loading...";
    if (statusBadgeEl) statusBadgeEl.innerHTML = "";
    if (timestampsEl) timestampsEl.textContent = "";
    if (bodyHtmlEl) bodyHtmlEl.innerHTML = "";
    if (mrpEl) mrpEl.textContent = "—";
    if (b2bEl) b2bEl.textContent = "—";
    if (discountedEl) discountedEl.textContent = "—";
    if (extrasEl) extrasEl.innerHTML = "";
  }

  // Show error in modal
  function showError(message) {
    if (loadingEl) loadingEl.style.display = "none";
    if (contentEl) contentEl.style.display = "none";
    if (errorEl) {
      if (errorTextEl) errorTextEl.textContent = message;
      errorEl.style.display = "block";
    }
  }

  // Hide error in modal
  function hideError() {
    if (errorEl) errorEl.style.display = "none";
  }

  // Process and display lab test data
  function displayTestData(test) {
    hideError();

    // Title
    if (titleEl) {
      titleEl.textContent =
        test.test_name || test.name || test.title || "Untitled Test";
    }

    // Status badge
    if (statusBadgeEl) {
      const status = (test.status || "draft").toLowerCase();
      const badgeClass =
        status === "published"
          ? "bg-success"
          : status === "draft"
          ? "bg-secondary"
          : "bg-warning";
      const statusText = status.charAt(0).toUpperCase() + status.slice(1);
      statusBadgeEl.innerHTML = `<span class="badge ${badgeClass}">${statusText}</span>`;
    }

    // Timestamps
    if (timestampsEl) {
      const created = test.created_at ? formatDate(test.created_at) : null;
      const updated = test.updated_at ? formatDate(test.updated_at) : null;
      let timestampText = "";
      if (created) timestampText += `Created: ${created}`;
      if (created && updated) timestampText += " • ";
      if (updated) timestampText += `Updated: ${updated}`;
      timestampsEl.textContent = timestampText;
    }

    // Description/Body
    if (bodyHtmlEl) {
      if (test.description) {
        bodyHtmlEl.innerHTML = `<div class="test-description">${escapeHtml(
          test.description
        )}</div>`;
      } else if (test.short_description) {
        bodyHtmlEl.innerHTML = `<div class="test-description">${escapeHtml(
          test.short_description
        )}</div>`;
      } else {
        bodyHtmlEl.innerHTML =
          '<p class="text-muted"><em>No description available</em></p>';
      }
    }

    // Pricing
    if (mrpEl) {
      mrpEl.textContent =
        test.mrp !== null && test.mrp !== undefined
          ? `₹${numberFormat(test.mrp)}`
          : "—";
    }

    if (b2bEl) {
      const b2bVal = test.b2b ?? test.b2b_price ?? null;
      b2bEl.textContent =
        b2bVal !== null && b2bVal !== undefined
          ? `₹${numberFormat(b2bVal)}`
          : "—";
    }

    if (discountedEl) {
      discountedEl.textContent =
        test.discounted_price !== null && test.discounted_price !== undefined
          ? `₹${numberFormat(test.discounted_price)}`
          : "—";
    }

    // Extras
    if (extrasEl) {
      extrasEl.innerHTML = "";

      let extraHtml = "";

      if (test.test_code) {
        extraHtml += `<div class="mb-2"><strong>Test Code:</strong> ${escapeHtml(
          test.test_code
        )}</div>`;
      }

      if (test.category) {
        extraHtml += `<div class="mb-2"><strong>Category:</strong> ${escapeHtml(
          test.category
        )}</div>`;
      }

      if (test.preparation) {
        extraHtml += `<div class="mb-2"><strong>Preparation:</strong> ${escapeHtml(
          test.preparation
        )}</div>`;
      }

      if (test.turnaround_time) {
        extraHtml += `<div class="mb-2"><strong>Turnaround Time:</strong> ${escapeHtml(
          test.turnaround_time
        )}</div>`;
      }
    }

    // Show content
    if (loadingEl) loadingEl.style.display = "none";
    if (contentEl) contentEl.style.display = "block";
  }

  // Fetch test data from API
  async function fetchTestData(url, testId) {
    try {
      const response = await fetch(url, {
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          Accept: "application/json",
          "Content-Type": "application/json",
        },
        credentials: "same-origin",
      });

      if (!response.ok) {
        let errorMsg = `HTTP error! status: ${response.status}`;
        try {
          const errorData = await response.json();
          errorMsg = errorData.message || errorData.error || errorMsg;
        } catch (e) {
          // Ignore JSON parsing error
        }
        throw new Error(errorMsg);
      }

      const data = await response.json();

      // Handle different response structures
      if (data.success === false) {
        throw new Error(data.message || "Request failed");
      }

      return data.data || data.test || data;
    } catch (error) {
      throw error;
    }
  }

  // Show modal with test data
  async function showTestModal(testId) {
    const url = `/admin/labtests/${testId}`;

    resetModal();
    bsModal.show();

    try {
      const testData = await fetchTestData(url, testId);
      displayTestData(testData);
    } catch (error) {
      showError(`Failed to load test details: ${error.message}`);
    }
  }

  // Event delegation for view buttons
  document.addEventListener("click", function (event) {
    const viewBtn = event.target.closest(".view-test-btn");

    if (viewBtn) {
      event.preventDefault();
      event.stopPropagation();

      const testId = viewBtn.getAttribute("data-test-id");

      if (!testId) {
        showError("Invalid test ID");
        return;
      }

      showTestModal(testId);
    }

    // Handle delete button confirmation
    const deleteBtn = event.target.closest(".delete-btn");
    if (deleteBtn) {
      const form = deleteBtn.closest(".delete-form");
      if (form) {
        const confirmMsg = form.getAttribute("data-confirm") || "Are you sure?";
        if (!confirm(confirmMsg)) {
          event.preventDefault();
          event.stopPropagation();
        }
      }
    }
  });

  // Also handle click on the eye icon itself (if button has child elements)
  document.addEventListener("click", function (event) {
    if (event.target.classList.contains("fa-eye")) {
      const viewBtn = event.target.closest(".view-test-btn");
      if (viewBtn) {
        event.preventDefault();
        event.stopPropagation();

        const testId = viewBtn.getAttribute("data-test-id");
        if (testId) {
          showTestModal(testId);
        }
      }
    }
  });

  // Close modal when clicking on backdrop (for fallback modal)
  modalEl.addEventListener("click", function (event) {
    if (event.target === modalEl && bsModal && bsModal._backdrop) {
      bsModal.hide();
    }
  });

  // Add some basic CSS for the fallback modal
  if (typeof bootstrap === "undefined") {
    const fallbackCss = `
            .modal.fade-in {
                opacity: 1;
                transition: opacity 0.3s ease;
            }
            .modal-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1040;
                width: 100vw;
                height: 100vh;
                background-color: rgba(0,0,0,0.5);
            }
        `;
    const style = document.createElement("style");
    style.textContent = fallbackCss;
    document.head.appendChild(style);
  }
});
