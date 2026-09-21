// public/assets/js/admin/hospital-qr-appointments.js

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    initFilterControls();
    initItemsModal();
    initExportInline();
});

// 1. Filter Controls
function initFilterControls() {
    const filterForm = document.getElementById('appointmentsFilterForm');
    const searchInput = document.getElementById('appointmentsSearchInput');

    const dateToggle = document.getElementById('apDateToggle');
    const datePopover = document.getElementById('apDatePopover');
    const dateApply = document.getElementById('apDateApplyBtn');
    const dateClose = document.getElementById('apDateCloseBtn');
    const dateClear = document.getElementById('apDateClearBtn');
    const dateFromInp = document.getElementById('apDateFrom');
    const dateToInp = document.getElementById('apDateTo');
    const dateErrorMsg = document.getElementById('apDateError');

    const exportToggle = document.getElementById('apExportToggle');
    const exportMenu = document.getElementById('apExportMenu');

    function closeDatePopover() {
        if (datePopover) datePopover.classList.remove('show');
    }

    function closeExportMenu() {
        if (exportMenu) exportMenu.classList.remove('show');
    }

    function updateApplyState() {
        if (!dateApply || !dateFromInp || !dateToInp) return;

        const fromVal = (dateFromInp.value || '').trim();
        const toVal = (dateToInp.value || '').trim();

        const hasFrom = !!fromVal;
        const hasTo = !!toVal;
        let error = '';

        if (!hasFrom && !hasTo) {
            // both empty → don't allow Apply
            error = 'Please select From and To dates.';
        } else if ((hasFrom && !hasTo) || (!hasFrom && hasTo)) {
            // only one filled
            error = 'Please select both From and To dates.';
        } else if (hasFrom && hasTo && toVal < fromVal) {
            // invalid range
            error = '"To" date cannot be earlier than "From" date.';
        }

        if (error) {
            dateApply.disabled = true;
            if (dateErrorMsg) {
                dateErrorMsg.textContent = error;
                dateErrorMsg.style.display = 'block';
            }
        } else {
            dateApply.disabled = false;
            if (dateErrorMsg) {
                dateErrorMsg.textContent = '';
                dateErrorMsg.style.display = 'none';
            }
        }
    }

    // Event listeners
    if (dateFromInp) {
        dateFromInp.addEventListener('input', updateApplyState);
        dateFromInp.addEventListener('change', updateApplyState);
    }
    if (dateToInp) {
        dateToInp.addEventListener('input', updateApplyState);
        dateToInp.addEventListener('change', updateApplyState);
    }
    updateApplyState();

    if (dateToggle && datePopover) {
        dateToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const willShow = !datePopover.classList.contains('show');
            closeExportMenu();
            if (willShow) datePopover.classList.add('show');
            else closeDatePopover();
        });
    }

    if (dateApply && filterForm) {
        dateApply.addEventListener('click', function(e) {
            e.preventDefault();
            updateApplyState();
            if (dateApply.disabled) return;
            filterForm.submit();
        });
    }

    if (dateClose) {
        dateClose.addEventListener('click', function(e) {
            e.preventDefault();
            if (dateErrorMsg) {
                dateErrorMsg.textContent = '';
                dateErrorMsg.style.display = 'none';
            }
            closeDatePopover();
        });
    }

    if (dateClear && filterForm) {
        dateClear.addEventListener('click', function(e) {
            e.preventDefault();

            if (dateFromInp) dateFromInp.value = '';
            if (dateToInp) dateToInp.value = '';

            if (dateErrorMsg) {
                dateErrorMsg.textContent = '';
                dateErrorMsg.style.display = 'none';
            }

            updateApplyState();
            filterForm.submit();
        });
    }

    if (exportToggle && exportMenu) {
        exportToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const willShow = !exportMenu.classList.contains('show');
            closeDatePopover();

            if (willShow) exportMenu.classList.add('show');
            else closeExportMenu();
        });
    }

    // Close popovers on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.date-wrap')) closeDatePopover();
        if (!e.target.closest('.export-wrap')) closeExportMenu();
    });

    // Close on Escape key
    document.addEventListener('keydown', function(ev) {
        if (ev.key === 'Escape') {
            closeDatePopover();
            closeExportMenu();
        }
    });
}

// 2. Items Modal
function initItemsModal() {
    const modalEl = document.getElementById('itemsModal');
    const listEl = document.getElementById('itemsModalList');
    const metaEl = document.getElementById('modalPatientName');
    const subtotalEl = document.getElementById('itemsSubtotal');
    const discountEl = document.getElementById('itemsDiscount');
    const totalEl = document.getElementById('itemsTotal');
    const couponWrap = document.getElementById('itemsCouponWrap');
    const titleEl = document.getElementById('itemsModalLabel');

    if (!modalEl) {
        console.error('Items modal element not found!');
        return;
    }

    function formatCurrency(amount) {
        try {
            const num = parseFloat(amount || 0);
            return '₹' + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        } catch (e) {
            return '₹0.00';
        }
    }

    function renderItems(items = []) {
        if (!listEl) return;
        
        listEl.innerHTML = '';
        let hasComponents = false;

        if (!items || !items.length) {
            listEl.innerHTML = '<div class="text-muted">No items found for this appointment.</div>';
            return;
        }

        items.forEach((it) => {
            const qty = it.quantity || 1;
            const lineTotal = (Number(it.item_price || 0) * qty);
            const hasComp = Array.isArray(it.components) && it.components.length > 0;

            if (hasComp) hasComponents = true;

            const wrap = document.createElement('div');
            wrap.className = 'itm-line';

            const left = document.createElement('div');
            left.style.flex = '1 1 auto';
            
            let compHtml = '';
            if (hasComp) {
                const compItems = it.components.map(c => 
                    `<li class="itm-meta">${(c.name || c.title || '')} — ${formatCurrency(c.price || 0)}</li>`
                ).join('');
                
                compHtml = `
                    <div class="mt-2 p-2" style="background:#f8fafc; border:1px dashed #e5eff3; border-radius:8px;">
                        <div class="itm-meta fw-bold mb-1">Components</div>
                        <ul class="mb-0" style="padding-left:18px;">
                            ${compItems}
                        </ul>
                    </div>
                `;
            }

            left.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <span class="itm-badge">${(it.item_type || '').toString().toUpperCase()}</span>
                    <span class="fw-semibold">${it.item_name || ''}</span>
                </div>
                ${compHtml}
            `;

            const right = document.createElement('div');
            right.style.flex = '0 0 auto';
            right.innerHTML = `<div class="itm-ttl">${formatCurrency(lineTotal)}</div>`;

            wrap.appendChild(left);
            wrap.appendChild(right);
            listEl.appendChild(wrap);
        });
    }

    // Event listener for view items buttons
    document.addEventListener('click', function(ev) {
        const btn = ev.target.closest('.view-items-btn');
        if (!btn) return;

        try {
            const patient = btn.getAttribute('data-appointment-name') || '';
            const apptId = btn.getAttribute('data-appointment-id') || '';

            let itemsRaw = btn.getAttribute('data-items') || '[]';
            let items = [];
            try {
                items = JSON.parse(itemsRaw);
            } catch (e) {
                items = [];
            }

            const subtotal = parseFloat(btn.getAttribute('data-subtotal') || '0') || 0;
            const discount = parseFloat(btn.getAttribute('data-discount') || '0') || 0;
            const total = parseFloat(btn.getAttribute('data-total') || '0') || 0;
            const coupon = (btn.getAttribute('data-coupon') || '').trim();

            if (titleEl) titleEl.textContent = `Items (ID: ${apptId})`;
            if (metaEl) metaEl.textContent = patient ? `Patient: ${patient}` : '';

            renderItems(items);

            if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
            if (discountEl) discountEl.textContent = formatCurrency(discount);
            if (totalEl) totalEl.textContent = formatCurrency(total);
            if (couponWrap) couponWrap.textContent = coupon ? `(${coupon})` : '';

            // Show modal
            if (modalEl && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        } catch (e) {
            console.error('Failed to open items modal', e);
            alert('Unable to open items popup.');
        }
    });
}

// 3. Export Inline Downloads
function initExportInline() {
    function filenameFromDisposition(disposition) {
        if (!disposition) return null;

        const m1 = disposition.match(/filename\*=UTF-8''([^;]+)/i);
        if (m1 && m1[1]) return decodeURIComponent(m1[1]);

        const m2 = disposition.match(/filename="([^"]+)"/i);
        if (m2 && m2[1]) return m2[1];

        return null;
    }

    async function fetchAndDownload(url, mimeFallback) {
        const res = await fetch(url, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': '*/*'
            }
        });

        if (!res.ok) {
            const txt = await res.text().catch(() => null);
            throw new Error('Export failed — ' + res.status + (txt ? (': ' + txt) : ''));
        }

        const disp = res.headers.get('Content-Disposition') || '';
        let filename = filenameFromDisposition(disp);

        if (!filename) {
            const ct = (res.headers.get('Content-Type') || mimeFallback || '').toLowerCase();
            let ext = 'dat';

            if (ct.includes('pdf')) {
                ext = 'pdf';
            } else if (ct.includes('spreadsheet') || ct.includes('excel') || ct.includes('xlsx')) {
                ext = 'xlsx';
            }

            filename =
                'hospital-qr-appointments_' +
                (new Date()).toISOString().slice(0, 19).replace(/[:T]/g, '-') +
                '.' + ext;
        }

        const blob = await res.blob();
        const blobUrl = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
        setTimeout(() => URL.revokeObjectURL(blobUrl), 2000);
    }

    document.addEventListener('click', async function(ev) {
        const link = ev.target.closest('[data-export]');
        if (!link) return;

        ev.preventDefault();
        ev.stopPropagation();

        const href = link.getAttribute('href');
        if (!href) return;

        const type = (link.getAttribute('data-export') || '').toLowerCase();
        const mime = link.getAttribute('data-mime') ||
            (type === 'excel' ?
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' :
                'application/pdf');

        const original = link.innerHTML;
        link.setAttribute('aria-disabled', 'true');
        link.classList.add('btn-looks-disabled');
        link.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin me-2"></i>
            <span class="label">Preparing…</span>
        `;

        try {
            await fetchAndDownload(href, mime);
        } catch (err) {
            console.error(err);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Export failed',
                    text: err.message || 'Please try again.'
                });
            } else {
                alert(err.message || 'Export failed. Please try again.');
            }
        } finally {
            link.innerHTML = original;
            link.removeAttribute('aria-disabled');
            link.classList.remove('btn-looks-disabled');
        }
    }, { capture: true });
}