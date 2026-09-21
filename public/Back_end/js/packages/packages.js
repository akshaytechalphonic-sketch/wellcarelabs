// public/assets/js/admin/packages.js

// Main initialization
document.addEventListener('DOMContentLoaded', function () {
    initHighlightQuery();
    initPackageViewModal();
    initSpecialToggle();
    initDeleteConfirmation();
});

// 1. Highlight query matches (title only)
function initHighlightQuery() {
    try {
        const params = new URLSearchParams(window.location.search);
        const q = (params.get('q') || '').trim();
        if (!q) return;

        const esc = q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const re = new RegExp(esc, 'ig');

        document.querySelectorAll('#packageAdminBody .package-title').forEach(el => {
            const text = el.textContent || '';
            el.innerHTML = text.replace(re, match =>
                `<mark style="background:#fffb8f;color:#000;padding:0 .12rem;border-radius:2px">${match}</mark>`
            );
        });
    } catch (err) {
        console.warn('Highlight failed', err);
    }
}

// 2. Package View Modal
function initPackageViewModal() {
    const modalEl = document.getElementById('packageViewModal');
    if (!modalEl) return;

    const bsModal = new bootstrap.Modal(modalEl, { keyboard: true });

    const loadingEl = document.getElementById('packageViewLoading');
    const contentEl = document.getElementById('packageViewContent');
    const bannerEl = document.getElementById('packageViewBanner');
    const titleEl = document.getElementById('packageViewTitle');
    const specialBadgeEl = document.getElementById('packageViewSpecialBadge');
    const mrpEl = document.getElementById('packageViewMrp');
    const sellingEl = document.getElementById('packageViewSellingPrice');
    const statusEl = document.getElementById('packageViewStatus');
    const timestampsEl = document.getElementById('packageViewTimestamps');
    const bodyHtmlEl = document.getElementById('packageViewBodyHtml');
    const extrasEl = document.getElementById('packageViewExtras');

    // ✅ Helpers
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function numberFormat(n) {
        try {
            const num = Number(n);
            return num.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } catch (e) {
            return String(n);
        }
    }

    function capitalize(s) {
        if (!s) return s;
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    function formatDate(s) {
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
    }

    // ✅ Content formatting like blog modal
    function formatContentToHtml(text) {
        if (!text) return '';

        // If already HTML, return directly
        if (/<[a-z][\s\S]*>/i.test(text)) {
            return text;
        }

        const safe = escapeHtml(text);

        return safe
            .split(/\n\s*\n/g)
            .map(p => p.trim())
            .filter(Boolean)
            .map(p => `<p>${p.replace(/\n/g, "<br>")}</p>`)
            .join("");
    }

    function resetModal() {
        loadingEl.style.display = '';
        contentEl.style.display = 'none';

        bannerEl.src = '';
        bannerEl.alt = '';
        bannerEl.style.display = 'none';

        titleEl.textContent = '';
        specialBadgeEl.innerHTML = '';
        mrpEl.textContent = '';
        sellingEl.textContent = '';
        statusEl.textContent = '';
        timestampsEl.textContent = '';
        bodyHtmlEl.innerHTML = '';
        extrasEl.innerHTML = '';
    }

    async function fetchAndShow(url, packageId) {
        resetModal();
        bsModal.show();

        try {
            const res = await fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!res.ok) throw new Error('Non-ok response: ' + res.status);

            const data = await res.json();
            const pkg = data && data.data ? data.data : data;

            // Title
            titleEl.textContent = pkg.title || '—';

            // Banner
            if (pkg.banner) {
                bannerEl.src = pkg.banner.startsWith('http')
                    ? pkg.banner
                    : `${window.location.origin}/storage/${pkg.banner}`;
                bannerEl.alt = pkg.title || 'banner';
                bannerEl.style.display = 'block';
            } else {
                bannerEl.style.display = 'none';
            }

            // Special badge
            if (pkg.is_special || Number(pkg.is_special) === 1) {
                specialBadgeEl.innerHTML =
                    `<span class="badge bg-warning text-dark">Special</span>` +
                    (pkg.special_label
                        ? ` <span class="badge bg-light text-dark">${escapeHtml(String(pkg.special_label))}</span>`
                        : '');
            } else {
                specialBadgeEl.innerHTML = '';
            }

            // Prices
            mrpEl.textContent =
                (pkg.mrp !== undefined && pkg.mrp !== null)
                    ? '₹' + numberFormat(pkg.mrp)
                    : '—';

            sellingEl.textContent =
                (pkg.discounted_price !== undefined && pkg.discounted_price !== null)
                    ? '₹' + numberFormat(pkg.discounted_price)
                    : '—';

            // Status
            statusEl.textContent = pkg.status ? capitalize(String(pkg.status)) : '—';

            // Timestamps
            const created = pkg.created_at ? formatDate(pkg.created_at) : null;
            const updated = pkg.updated_at ? formatDate(pkg.updated_at) : null;

            timestampsEl.textContent =
                (created ? ('Created: ' + created) : '') +
                (created && updated ? ' • ' : '') +
                (updated ? ('Updated: ' + updated) : '');

            // Body content
            if (pkg.content) {
                bodyHtmlEl.innerHTML = formatContentToHtml(pkg.content);
            } else if (pkg.short_description) {
                bodyHtmlEl.innerHTML = `<p>${escapeHtml(pkg.short_description)}</p>`;
            } else {
                bodyHtmlEl.innerHTML = '<p class="text-muted"><em>No description provided.</em></p>';
            }

            // Extras (Included tests)
            extrasEl.innerHTML = '';
            // If the package has real tests associated with it (loaded from relationship, checking for test_name property)
            const hasRealTests = pkg.tests && pkg.tests.length && pkg.tests.some(t => t && (t.test_name !== undefined && t.test_name !== null));

            if (hasRealTests) {
                const wrap = document.createElement('div');
                wrap.className = "package-tests-wrap";

                const heading = document.createElement('h6');
                heading.className = "mb-2 fw-bold";
                heading.textContent = `Included tests (${pkg.tests.length})`;

                const ul = document.createElement('ul');
                ul.className = "package-tests-list";

                pkg.tests.forEach(t => {
                    const li = document.createElement('li');
                    li.textContent = t.test_name || t.name || t.title || String(t);
                    ul.appendChild(li);
                });

                wrap.appendChild(heading);
                wrap.appendChild(ul);
                extrasEl.appendChild(wrap);
            }

            loadingEl.style.display = 'none';
            contentEl.style.display = '';

        } catch (err) {
            console.error('Failed to fetch package', err);

            // fallback to normal page
            const fallbackShow = `${window.location.origin}/admin/packages/${packageId}`;
            try { bsModal.hide(); } catch (e) {}
            window.location.href = fallbackShow;
        }
    }

    // Event listener for view buttons
    document.addEventListener('click', function (ev) {
        const btn = ev.target.closest('.view-package-btn');
        if (!btn) return;

        ev.preventDefault();

        const packageId = btn.dataset.packageId;
        const url = btn.dataset.url || (`/admin/packages/${packageId}`);

        if (!packageId) return;
        fetchAndShow(url, packageId);
    });
}

// 3. Special toggle behavior (AJAX)
function initSpecialToggle() {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function showToast(msg, type = 'success') {
        if (window.Swal && typeof Swal.fire === 'function') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2200,
                icon: type,
                title: msg
            });
        } else {
            const el = document.createElement('div');
            el.textContent = msg;
            el.style.position = 'fixed';
            el.style.right = '18px';
            el.style.top = '18px';
            el.style.background = type === 'success' ? '#0b6efd' : '#dc3545';
            el.style.color = '#fff';
            el.style.padding = '8px 12px';
            el.style.borderRadius = '6px';
            el.style.zIndex = 12000;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2400);
        }
    }

    async function toggleSpecialAjax(packageId, currentlySpecial, btn) {
        const url = `/admin/packages/${packageId}/special-toggle`;
        const body = new URLSearchParams();
        body.append('_method', 'POST');
        body.append('is_special', currentlySpecial ? '0' : '1');

        try {
            btn.disabled = true;

            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-star', 'fa-star-o');
                icon.classList.add(currentlySpecial ? 'fa-star-o' : 'fa-star');
            }
            btn.dataset.isSpecial = currentlySpecial ? '0' : '1';

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: body.toString(),
                credentials: 'same-origin'
            });

            if (!res.ok) throw new Error('Server returned ' + res.status);

            const json = await res.json();
            if (!json || !json.success) throw new Error(json?.message || 'Failed to update');

            const row = document.querySelector(`#package-row-${packageId}`);
            if (row) {
                const specialCell = row.querySelector('td:nth-child(4)');
                if (specialCell) {
                    specialCell.innerHTML = Number(json.is_special) === 1
                        ? '<span class="badge bg-warning text-dark">Special</span>'
                        : '<span class="muted">—</span>';
                }
            }

            showToast(json.message || (currentlySpecial ? 'Unmarked as special' : 'Marked as special'), 'success');
        } catch (err) {
            console.error('Toggle special error', err);

            const icon = btn.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-star', 'fa-star-o');
                icon.classList.add(currentlySpecial ? 'fa-star' : 'fa-star-o');
            }
            btn.dataset.isSpecial = currentlySpecial ? '1' : '0';

            showToast('Failed to update. Please try again.', 'error');
        } finally {
            btn.disabled = false;
        }
    }

    document.addEventListener('click', function (ev) {
        const btn = ev.target.closest('.toggle-special-btn');
        if (!btn) return;

        ev.preventDefault();

        const packageId = btn.dataset.packageId;
        const isSpecial = Number(btn.dataset.isSpecial || 0) === 1;
        if (!packageId) return;

        toggleSpecialAjax(packageId, isSpecial, btn);
    });
}

// 4. Delete confirmation
function initDeleteConfirmation() {
    document.addEventListener('click', function (ev) {
        if (ev.target.closest('.delete-btn')) {
            ev.preventDefault();

            const form = ev.target.closest('form');
            const button = ev.target.closest('.delete-btn');
            const packageName = button ? button.getAttribute('data-package-name') : 'this package';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Package',
                    html: `Are you sure you want to delete <strong>${packageName}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            } else {
                if (confirm(`Are you sure you want to delete ${packageName}?`)) {
                    form.submit();
                }
            }
        }
    });
}
