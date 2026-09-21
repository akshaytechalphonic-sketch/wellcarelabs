// blogs.js - Blog management functionality
document.addEventListener("DOMContentLoaded", function () {
  // Modal elements
  const modalEl = document.getElementById("blogViewModal");
  if (!modalEl) return;

  let blogModal = null;

  // Initialize Bootstrap modal with fallback
  function initBlogModal() {
    if (typeof bootstrap !== "undefined" && bootstrap.Modal) {
      blogModal = new bootstrap.Modal(modalEl, {
        keyboard: true,
        backdrop: true,
      });
    } else {
      blogModal = {
        _backdrop: null,
        _escHandler: null,

        show: function () {
          modalEl.style.display = "block";
          modalEl.classList.add("show");
          document.body.classList.add("modal-open");

          this._backdrop = document.createElement("div");
          this._backdrop.className = "modal-backdrop fade show";
          document.body.appendChild(this._backdrop);

          this._backdrop.onclick = () => this.hide();

          this._escHandler = (e) => {
            if (e.key === "Escape") this.hide();
          };
          document.addEventListener("keydown", this._escHandler);

          setTimeout(() => {
            modalEl.classList.add("fade-in");
          }, 10);
        },

        hide: function () {
          modalEl.style.display = "none";
          modalEl.classList.remove("show", "fade-in");
          document.body.classList.remove("modal-open");

          if (this._backdrop) {
            this._backdrop.remove();
            this._backdrop = null;
          }

          if (this._escHandler) {
            document.removeEventListener("keydown", this._escHandler);
            this._escHandler = null;
          }

          resetBlogModal();
        },
      };
    }
  }

  initBlogModal();

  // Modal content elements
  const loadingEl = document.getElementById("blogViewLoading");
  const contentEl = document.getElementById("blogViewContent");
  const titleEl = document.getElementById("blogViewTitle");
  const badgeEl = document.getElementById("blogViewStatusBadge");
  const timeEl = document.getElementById("blogViewTimestamps");
  const bodyEl = document.getElementById("blogViewBodyHtml");
  const imageEl = document.getElementById("blogViewImage");
  const shortDescWrap = document.getElementById("blogViewShortDescWrap");
  const shortDescEl = document.getElementById("blogViewShortDesc");

  // Helper functions

  const formatDate = (dateString) => {
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
  };

  // ✅ This makes your content align + formatted like show blade + clickable backlinks

  // Reset modal to loading state
  function resetBlogModal() {
    if (loadingEl) loadingEl.style.display = "";
    if (contentEl) contentEl.style.display = "none";
    if (titleEl) titleEl.textContent = "";
    if (badgeEl) badgeEl.innerHTML = "";
    if (timeEl) timeEl.textContent = "";
    if (bodyEl) bodyEl.innerHTML = "";
    if (imageEl) {
      imageEl.src = "";
      imageEl.style.display = "none";
    }
    if (shortDescWrap) shortDescWrap.style.display = "none";
    if (shortDescEl) shortDescEl.textContent = "";
  }

  // Display blog data in modal
  function displayBlogData(blog) {
    // Title
    if (titleEl) titleEl.textContent = blog.title || "Untitled Blog";

    // Status badge
    if (badgeEl) {
      const status = (blog.status || "draft").toLowerCase();
      const badgeClass = status === "published" ? "bg-success" : "bg-secondary";
      badgeEl.innerHTML = `<span class="badge ${badgeClass} text-white">${status}</span>`;
    }

    // Timestamps
    if (timeEl) {
      const created = blog.created_at ? formatDate(blog.created_at) : null;
      const updated = blog.updated_at ? formatDate(blog.updated_at) : null;

      let timestampText = "";
      if (created) timestampText += `Created: ${created}`;
      if (created && updated) timestampText += " • ";
      if (updated) timestampText += `Updated: ${updated}`;

      timeEl.textContent = timestampText;
    }

    // Featured image
    if (imageEl && blog.featured_image) {
      let imageSrc = blog.featured_image;

      if (!imageSrc.startsWith("http") && !imageSrc.startsWith("/")) {
        imageSrc = "/storage/" + imageSrc;
      }

      imageEl.src = imageSrc;
      imageEl.style.display = "block";
      imageEl.alt = blog.title || "Blog image";
    } else if (imageEl) {
      imageEl.style.display = "none";
    }

    // Short description
    if (shortDescWrap && shortDescEl) {
      if (blog.short_description) {
        shortDescEl.textContent = blog.short_description;
        shortDescWrap.style.display = "block";
      } else {
        shortDescWrap.style.display = "none";
      }
    }

    if (bodyEl) {
      if (blog.content) {
        bodyEl.innerHTML = blog.content; // 🔥 render CKEditor HTML directly
      } else {
        bodyEl.innerHTML =
          '<p class="text-muted"><em>No content available</em></p>';
      }
    }

    // Show content
    if (loadingEl) loadingEl.style.display = "none";
    if (contentEl) contentEl.style.display = "block";
  }

  // Fetch blog data from API
  async function fetchBlogData(url) {
    const response = await fetch(url, {
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
      },
      credentials: "same-origin",
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data.data || data.blog || data;
  }

  // Show modal with blog data
  async function showBlogModal(blogId, url) {
    resetBlogModal();
    blogModal.show();

    try {
      const blogData = await fetchBlogData(url);
      displayBlogData(blogData);
    } catch (error) {
      blogModal.hide();
      window.location.href = url;
    }
  }

  // Event delegation for view buttons
  document.addEventListener("click", function (event) {
    const viewBtn = event.target.closest(".view-blog-btn");
    if (viewBtn) {
      event.preventDefault();
      event.stopPropagation();

      const blogId = viewBtn.getAttribute("data-blog-id");
      const url = viewBtn.getAttribute("data-url");

      if (blogId && url) {
        showBlogModal(blogId, url);
      }
    }

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

  // Close modal when clicking on backdrop (fallback modal)
  modalEl.addEventListener("click", function (event) {
    if (event.target === modalEl && blogModal && blogModal._backdrop) {
      blogModal.hide();
    }
  });

  // Add fallback CSS if Bootstrap is not available
  if (typeof bootstrap === "undefined") {
    const fallbackCss = `
      .modal.fade-in { opacity: 1; transition: opacity 0.3s ease; }
      .modal-backdrop {
        position: fixed; top: 0; left: 0; z-index: 1040;
        width: 100vw; height: 100vh;
        background-color: rgba(0,0,0,0.5);
      }
    `;
    const style = document.createElement("style");
    style.textContent = fallbackCss;
    document.head.appendChild(style);
  }
});

// ✅ Status filter dropdown fix (works even in ajax fragments)
document.addEventListener("click", function (e) {
  const btn = e.target.closest('[data-bs-toggle="dropdown"]');
  if (!btn) return;

  if (typeof bootstrap !== "undefined" && bootstrap.Dropdown) {
    bootstrap.Dropdown.getOrCreateInstance(btn).toggle();
  }
});
