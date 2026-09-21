// resources/js/admin/banners.js

document.addEventListener('DOMContentLoaded', function() {
    // ===== SORTING by Sr.No. on current page (client-side) =====
    const initSorting = () => {
        const header = document.getElementById('banner-srno-header');
        const tbody  = document.getElementById('banners-tbody');
        if (!header || !tbody) return;

        let sortDir = 'asc';

        header.addEventListener('click', function () {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            if (!rows.length) return;

            rows.sort((a, b) => {
                const av = parseInt(a.querySelector('.banner-srno')?.textContent.trim() || '0', 10);
                const bv = parseInt(b.querySelector('.banner-srno')?.textContent.trim() || '0', 10);

                if (isNaN(av) || isNaN(bv)) return 0;
                return sortDir === 'asc' ? av - bv : bv - av;
            });

            sortDir = (sortDir === 'asc') ? 'desc' : 'asc';

            rows.forEach(row => tbody.appendChild(row));

            Array.from(tbody.querySelectorAll('.banner-srno')).forEach((cell, index) => {
                cell.textContent = index + 1;
            });
        });
    };

    // ===== CLICK-TO-TOGGLE STATUS (AJAX) =====
    const initToggleStatus = () => {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';

        document.addEventListener('click', async function (e) {
            const btn = e.target.closest('.badge-status-toggle');
            if (!btn) return;

            const url = btn.dataset.url;
            if (!url || !csrf) {
                console.warn('Missing URL or CSRF token for banner toggle');
                return;
            }

            btn.disabled = true;
            btn.style.opacity = '0.7';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'PATCH' })
                });

                if (!res.ok) throw new Error('Network response was not ok');

                const data = await res.json();
                const nowActive = !!data.is_active;

                btn.dataset.active = nowActive ? '1' : '0';
                btn.classList.toggle('is-on', nowActive);

                const label = btn.parentElement.querySelector('.status-text');
                if (label) {
                    label.textContent = nowActive ? 'Active' : 'Inactive';
                    label.classList.toggle('text-success', nowActive);
                    label.classList.toggle('text-muted', !nowActive);
                }
            } catch (err) {
                console.error('Toggle failed', err);
                alert('Failed to update banner status. Please try again.');
            } finally {
                btn.disabled = false;
                btn.style.opacity = '1';
            }
        });
    };

    // ===== DELETE CONFIRMATION =====
    const initDeleteConfirmation = () => {
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('.delete-form');
            if (!form) return;

            e.preventDefault();
            
            const confirmMessage = form.dataset.confirm || 'Are you sure you want to delete this banner?';
            
            if (confirm(confirmMessage)) {
                form.submit();
            }
        });
    };

    // Initialize all functions
    initSorting();
    initToggleStatus();
    initDeleteConfirmation();
});