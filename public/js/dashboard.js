/* public/js/dashboard.js
   Expects window.DashboardConfig as described in the project
*/
(function(){
  // Defensive: ensure config exists
  const cfg = window.DashboardConfig || {};
  const CSRF_TOKEN = (function(){
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta && meta.getAttribute ? (meta.getAttribute('content') || '') : '';
  })();

  // Configurable URLs (fall back to sensible defaults)
  const BASE_APPOINTMENTS_URL = cfg.baseAppointmentsUrl || '/appointments';
  const BASE_ADMIN_PACKAGES = cfg.baseAdminPackagesUrl || '/admin/packages';
  const BASE_ADMIN_LABTESTS = cfg.baseAdminLabtestsUrl || '/admin/labtests';
  const EXPORT_EXCEL_BASE = cfg.exportExcelUrl || '/appointments/export/excel';
  const EXPORT_PDF_BASE = cfg.exportPdfUrl || '/appointments/export/pdf';
  const COUNTS_URL = cfg.countsUrl || '/dashboard/counts';

  /* ---------------------------
     Shared state & constants
     --------------------------- */
  let currentPage = 1;
  let lastPage = 1;
  let isFetching = false;
  let searchQuery = (cfg.initialSearchQuery || '').trim();
  let searchDebounceTimer = null;
  const SEARCH_DEBOUNCE_MS = 350;

  /* ---------------------------
     SweetAlert2 delete confirmation (AJAX & non-AJAX)
     --------------------------- */
  function attachDeleteConfirm(root = document) {
    if (typeof Swal === 'undefined') {
      // SweetAlert2 not loaded — skip attaching
      return;
    }

    const selector = 'form.delete-form, form[data-confirm]';
    root.querySelectorAll(selector).forEach(form => {
      if (form.__deleteConfirmAttached) return;
      form.__deleteConfirmAttached = true;

      form.addEventListener('submit', function (e) {
        e.preventDefault();

        const message = form.getAttribute('data-confirm') || 'Are you sure?';
        const ajax = form.dataset.ajax === "true" || form.getAttribute('data-ajax') === "true";

        Swal.fire({
          title: 'Confirm Delete',
          text: message,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel',
          reverseButtons: true
        }).then(result => {
          if (!result.isConfirmed) return;

          if (ajax) {
            const fd = new FormData(form);

            fetch(form.action, {
              method: (form.getAttribute('method') || 'POST').toUpperCase(),
              headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              },
              body: fd,
              credentials: 'same-origin'
            })
            .then(async res => {
              const ct = res.headers.get('content-type') || '';
              let json = null;
              if (ct.includes('application/json')) json = await res.json().catch(()=>null);
              if (!res.ok) {
                const msg = (json && (json.message || json.error)) ? (json.message || json.error) : `HTTP ${res.status}`;
                throw new Error(msg);
              }
              return json || { success: true, message: 'Deleted successfully' };
            })
            .then(data => {
              Swal.fire({ title: 'Deleted!', text: data.message || 'Deleted successfully', icon: 'success', confirmButtonText: 'OK' });
              // remove row / card if present
              const row = form.closest('tr, .card, .list-group-item, .row');
              if (row) row.remove();

              // If the admin fragment has a reload URL we can attempt to reload it:
              try {
                const container = form.closest('#packageAdminBody, #labtestAdminBody');
                if (container && container.dataset && container.dataset.loadedUrl) {
                  // small defer to allow UI to settle
                  setTimeout(() => {
                    if (typeof window.Dashboard !== 'undefined' && window.Dashboard.loadAdminPackages && container.id === 'packageAdminBody') {
                      window.Dashboard.loadAdminPackages(container.dataset.loadedUrl);
                    } else if (typeof window.Dashboard !== 'undefined' && window.Dashboard.loadAdminLabTests && container.id === 'labtestAdminBody') {
                      window.Dashboard.loadAdminLabTests(container.dataset.loadedUrl);
                    }
                  }, 250);
                }
              } catch (e) { /* ignore */ }
            })
            .catch(err => {
              console.error('AJAX delete error', err);
              Swal.fire({ title: 'Error', text: err.message || 'Something went wrong', icon: 'error' });
            });

          } else {
            // normal submit (create page reload)
            form.submit();
          }
        });
      });
    });
  }

  /* ---------------------------
     DOM ready: wire UI & sidebar
     --------------------------- */
  document.addEventListener('DOMContentLoaded', function() {
    const kpiWrapper = document.getElementById('dashboardKpiWrapper');
    const appointmentsCard = document.getElementById('appointmentsCard');
    const packageCard = document.getElementById('packageAdminCard');
    const labtestCard = document.getElementById('labtestAdminCard');


    if (kpiWrapper) kpiWrapper.style.display = 'block';
    if (appointmentsCard) appointmentsCard.style.display = 'none';
    if (packageCard) packageCard.style.display = 'none';
    if (labtestCard) labtestCard.style.display = 'none';

    // Sidebar: All Appointments
    document.getElementById('sidebarAllAppointmentsBtn')?.addEventListener('click', function(e){
      e.preventDefault();
      if (kpiWrapper) kpiWrapper.style.display = 'none';
      if (packageCard) packageCard.style.display = 'none';
      if (labtestCard) labtestCard.style.display = 'none';
      if (appointmentsCard) appointmentsCard.style.display = 'block';

      currentPage = 1;
      const si = document.getElementById('appointmentsSearchInput');
      searchQuery = si ? si.value.trim() : searchQuery;
      updateExportLinks();
      updateClearButton();

      loadAppointments(currentPage);
    });

    // Sidebar: Packages
    document.getElementById('sidebarPackageBtn')?.addEventListener('click', function(e){
      e.preventDefault();
      if (kpiWrapper) kpiWrapper.style.display = 'none';
      if (appointmentsCard) appointmentsCard.style.display = 'none';
      if (labtestCard) labtestCard.style.display = 'none';
      const url = this.dataset.url || this.getAttribute('href') || BASE_ADMIN_PACKAGES;
      loadAdminPackages(url);
    });

    // Sidebar: Lab Tests
    const sidebarLabTestBtn = document.getElementById('sidebarLabTestBtn') || document.getElementById('sidebarTestBtn');
    sidebarLabTestBtn?.addEventListener('click', function(e){
      e.preventDefault();

      if (kpiWrapper) kpiWrapper.style.display = 'none';
      if (appointmentsCard) appointmentsCard.style.display = 'none';
      if (packageCard) packageCard.style.display = 'none';
      const url = this.dataset.url || this.getAttribute('href') || BASE_ADMIN_LABTESTS;

      // <-- CHANGED: navigate full-page for lab tests (no AJAX)
      // This avoids using loadAdminLabTests so lab tests behave like packages/banners
      if (url) {
        window.location.href = url;
        return;
      }
    });

    // Search / export / clear wiring
    const searchInput = document.getElementById('appointmentsSearchInput');
    const searchBtn = document.getElementById('appointmentsSearchBtn');
    const clearBtn = document.getElementById('appointmentsClearBtn');

    // set initial value on the input if present
    if (searchInput && (!searchInput.value || searchInput.value.trim() === '')) {
      searchInput.value = searchQuery || '';
    }

    searchInput?.addEventListener('input', function() {
      if (searchDebounceTimer) clearTimeout(searchDebounceTimer);
      searchDebounceTimer = setTimeout(() => {
        searchQuery = (searchInput.value || '').trim();
        updateExportLinks();
        updateClearButton();
      }, SEARCH_DEBOUNCE_MS);
    });

    searchInput?.addEventListener('keydown', function(e){
      if (e.key === 'Enter') { e.preventDefault(); if (isAppointmentsVisible()) triggerSearch(); }
    });

    searchBtn?.addEventListener('click', function(e){ e.preventDefault(); if (isAppointmentsVisible()) triggerSearch(); });
    clearBtn?.addEventListener('click', function(e){ e.preventDefault(); searchQuery=''; if (searchInput) searchInput.value=''; updateExportLinks(); updateClearButton(); if (isAppointmentsVisible()) { currentPage = 1; loadAppointments(currentPage); } });

    // Manual reload button for appointments
    document.getElementById('appointmentsReloadBtn')?.addEventListener('click', function(e){
      e.preventDefault();
      loadAppointments(currentPage);
    });

    document.getElementById('reloadPackagesBtn')?.addEventListener('click', function(){
      const url = document.getElementById('packageAdminBody')?.dataset.loadedUrl || BASE_ADMIN_PACKAGES;
      loadAdminPackages(url);
    });
    document.getElementById('reloadTestsBtn')?.addEventListener('click', function(){
      const url = document.getElementById('labtestAdminBody')?.dataset.loadedUrl || BASE_ADMIN_LABTESTS;
      // <-- CHANGED: full-page navigation for reload of lab tests (no AJAX)
      if (url) window.location.href = url;
    });

    updateExportLinks();
    updateClearButton(); // <-- FIXED: call function
    attachDeleteConfirm(document); // wire static delete forms on initial page load
  });

  /* helpers */
  function isAppointmentsVisible() {
    const el = document.getElementById('appointmentsCard'); if (!el) return false;
    return el.style && el.style.display !== 'none';
  }
  function triggerSearch() {
    const input = document.getElementById('appointmentsSearchInput');
    searchQuery = input ? input.value.trim() : searchQuery;
    updateExportLinks();
    updateClearButton();
    currentPage = 1;
    loadAppointments(currentPage);
  }
  function updateClearButton() {
    const clearBtn = document.getElementById('appointmentsClearBtn'); if (!clearBtn) return;
    clearBtn.style.display = (searchQuery && searchQuery.length > 0) ? '' : 'none';
  }
  function updateExportLinks() {
    const ex = document.getElementById('exportExcelBtn');
    const pdf = document.getElementById('exportPdfBtn');
    const baseExcel = EXPORT_EXCEL_BASE;
    const basePdf = EXPORT_PDF_BASE;
    if (ex) ex.href = searchQuery ? `${baseExcel}?q=${encodeURIComponent(searchQuery)}` : baseExcel;
    if (pdf) pdf.href = searchQuery ? `${basePdf}?q=${encodeURIComponent(searchQuery)}` : basePdf;
  }

  /* loadAppointments - robust: supports JSON (data or html) and plain HTML fragments */
  function loadAppointments(page = 1) {
    const tbody = document.getElementById('appointmentsTableBody');
    const paginationContainer = document.getElementById('appointmentsPagination');
    const lastUpdatedEl = document.getElementById('lastUpdated');

    if (!tbody || !paginationContainer || !lastUpdatedEl) {
      console.warn('Appointments UI missing; aborting loadAppointments.' );
      return;
    }
    if (isFetching) return;
    isFetching = true;

    // show loading row (colspan 10)
    tbody.innerHTML = `<tr><td colspan="10" class="text-center text-muted py-3">Loading…</td></tr>`;

    let url = `${BASE_APPOINTMENTS_URL}?page=${page}`;
    if (searchQuery && searchQuery.length > 0) url += `&q=${encodeURIComponent(searchQuery)}`;

    fetch(url, {
      headers: {
        // accept both JSON and HTML; server will choose what to return
        "Accept": "application/json, text/html, */*",
        "X-Requested-With": "XMLHttpRequest"
      },
      credentials: 'same-origin'
    })
    .then(async response => {
      const ct = (response.headers.get('content-type') || '').toLowerCase();
      const text = await response.text().catch(()=>null);

      if (!response.ok) {
        console.error('Appointments fetch non-ok:', response.status);
        console.log('Response preview:', text ? text.substring(0,400) : '[no body]');
        throw new Error('HTTP ' + response.status);
      }

      // If Content-Type says JSON or the response text looks like JSON, parse it
      if (ct.includes('application/json') || (/^\s*\{/.test(text))) {
        let json = null;
        try { json = JSON.parse(text); } catch (e) {
          console.error('Invalid JSON from appointments endpoint', e, text);
          throw new Error('Invalid JSON response');
        }
        return { type: 'json', payload: json };
      }

      // Otherwise treat it as HTML fragment
      return { type: 'html', payload: text };
    })
    .then(result => {
      if (!result) throw new Error('Empty response');

      // --- Case A: server returned plain HTML fragment (or JSON.html) ---
      if (result.type === 'html' || (result.type === 'json' && result.payload && typeof result.payload.html === 'string')) {
        const html = (result.type === 'html') ? result.payload : result.payload.html;

        // Where to inject?
        // Prefer a wrapper if present, otherwise put into tbody.
        const wrapperIds = ['appointmentsCardBody', 'appointmentsCard', 'appointmentsTableWrapper'];
        let wrapper = null;
        for (const id of wrapperIds) { const el = document.getElementById(id); if (el) { wrapper = el; break; } }
        // fallback to tbody
        if (!wrapper) wrapper = tbody;

        // Heuristics:
        // - If returned HTML contains a <table ...> or <thead> or full markup, inject into wrapper.
        // - If it contains only <tr> or <tbody> contents, inject into tbody.
        const lower = html.toLowerCase();
        if (lower.includes('<table') || lower.includes('<thead') || lower.includes('<div') || wrapper !== tbody && (lower.includes('<tr') || lower.includes('<tbody'))) {
          // Put the server fragment into the wrapper
          wrapper.innerHTML = html;
        } else {
          // probably rows only - inject into tbody
          tbody.innerHTML = html;
        }

        // Update pagination if fragment included it (server-rendered).
        // Try to extract pagination from injected fragment if available:
        const pag = document.getElementById('appointmentsPagination') || paginationContainer;
        // If server included its own pagination markup (with class pagination), prefer that
        if (wrapper.querySelector && wrapper.querySelector('.pagination')) {
          pag.innerHTML = wrapper.querySelector('.pagination').outerHTML;
        }

        // If server returned timestamp in JSON.html payload maybe not possible; fallback to now
        try { lastUpdatedEl.textContent = 'Last updated: ' + new Date().toLocaleString(); } catch (e){}

        // Re-attach behaviors in newly injected fragment
        try {
          attachDeleteConfirm(wrapper);
          safeFeatherReplace();

          // Re-wire pagination links rendered by server
          wrapper.querySelectorAll('.pagination a, #appointmentsPagination a.page-link').forEach(a => {
            a.addEventListener('click', function(e) {
              e.preventDefault();
              const href = this.getAttribute('href') || this.dataset.url;
              if (!href) return;
              const u = new URL(href, window.location.origin);
              const p = u.searchParams.get('page') || 1;
              currentPage = Number(p);
              loadAppointments(currentPage);   
            });
          });

          // Re-bind view-items buttons inside injected content
          wrapper.querySelectorAll('.view-items-btn').forEach(btn => {
            btn.addEventListener('click', function () {
              const itemsJson = this.getAttribute('data-items') || '[]';
              const total = this.getAttribute('data-total') || '';
              let items;
              try { items = JSON.parse(itemsJson); } catch (e) { items = []; }

              const modalBody = document.getElementById('appointmentItemsModalBody');
              const modalTotal = document.getElementById('appointmentItemsModalTotal');
              if (!modalBody) return;

              modalBody.innerHTML = '';
              if (items.length === 0) {
                modalBody.innerHTML = '<div class="text-muted">No items</div>';
              } else {
                for (const it of items) {
                  const name = escapeHtml(it.item_name || it.name || '');
                  const qty = Number(it.quantity || it.qty || 1);
                  const priceNum = Number(it.item_price ?? it.price ?? it.mrp ?? 0) || 0;
                  const price = priceNum ? `₹${priceNum.toFixed(2)}` : '—';
                  modalBody.insertAdjacentHTML('beforeend', `<div class="d-flex justify-content-between py-1 border-bottom"><div>${name} ${qty > 1 ? '×'+qty : ''}</div><div class="text-end">${price}</div></div>`);
                }
              }
              modalTotal.textContent = total ? `Total: ₹${Number(total).toFixed(2)}` : '';
              const modal = new bootstrap.Modal(document.getElementById('appointmentItemsModal'));
              modal.show();
            });
          });
        } catch (e) { console.warn('Error wiring injected appointment fragment', e); }

        return;
      }

      // --- Case B: server returned JSON with data array (legacy API) ---
      if (result.type === 'json' && result.payload) {
        const payload = result.payload;
        const items = Array.isArray(payload.data) ? payload.data : (payload.data || []);
        currentPage = payload.current_page || page;
        lastPage = payload.last_page || 1;
        renderAppointments(items);
        renderPaginationControls(currentPage, lastPage);

        const stamp = payload.timestamp || payload.updated_at || new Date().toISOString();
        try {
          lastUpdatedEl.textContent = 'Last updated: ' + (new Date(stamp)).toLocaleString('en-GB', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit', hour12:true });
        } catch (e) {
          lastUpdatedEl.textContent = 'Last updated: ' + new Date().toLocaleString();
        }
        return;
      }

      throw new Error('Unexpected response format from server');
    })
    .catch(err => {
      console.error('Fetch error:', err);
      if (tbody) tbody.innerHTML = `<tr><td colspan="10" class="text-center text-danger">Error loading data (${escapeHtml(err.message)})</td></tr>`;
    })
    .finally(() => { isFetching = false; });
  }

  /* renderAppointments (IMPROVED: detects & displays time slots & robust price detection) */
  function renderAppointments(items) {
    const tbody = document.getElementById('appointmentsTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    if (!items || items.length === 0) {
      tbody.innerHTML = `<tr><td colspan="10" class="text-center text-muted py-3">No appointments found</td></tr>`;
      return;
    }

    function fmtDateAndTime(app) {
      // Try many common shapes for date/time fields
      const maybeDate = app.date ?? app.datetime ?? app.date_time ?? null;
      const maybeTime = app.time ?? app.time_slot ?? app.slot ?? app.appointment_time ?? app.start_time ?? null;

      // If date is an ISO string that includes time, parse it
      if (maybeDate) {
        try {
          const d = new Date(maybeDate);
          if (!isNaN(d.getTime())) {
            const dateStr = d.toLocaleDateString('en-GB', { day:'2-digit', month:'2-digit', year:'numeric' });
            const timeStr = d.toLocaleTimeString('en-GB', { hour:'2-digit', minute:'2-digit', hour12: true });
            // If there's also a separate explicit time, prefer that for time display
            if (maybeTime && String(maybeTime).trim() !== '') {
              return `${dateStr} • ${String(maybeTime).trim()}`;
            }
            return `${dateStr} • ${timeStr}`;
          }
        } catch (e) { /* fall through */ }
      }

      // If no parsable date, but separate fields exist:
      if (maybeDate && maybeTime) {
        return `${escapeHtml(String(maybeDate))} • ${escapeHtml(String(maybeTime))}`;
      }
      if (maybeDate) {
        return escapeHtml(String(maybeDate));
      }
      if (maybeTime) {
        return escapeHtml(String(maybeTime));
      }
      return '—';
    }

    // sum items helper (returns numeric)
    function sumItemsPrice(itemsArr) {
      if (!Array.isArray(itemsArr) || itemsArr.length === 0) return 0;
      let s = 0;
      for (const it of itemsArr) {
        const qty = Number(it.quantity ?? it.qty ?? 1) || 1;
        const priceVal = (it.item_price ?? it.price ?? it.mrp ?? 0);
        const priceNum = Number(priceVal) || 0;
        s += priceNum * qty;
      }
      return s;
    }

    for (const app of items) {
      const id = escapeHtml(app.id ?? '');
      const name = escapeHtml(app.name ?? '');
      const email = escapeHtml(app.email ?? '');
      const phone = escapeHtml(app.phone ?? '');
      const dateAndTime = fmtDateAndTime(app);
      const message = escapeHtml(app.message ?? '');
      const status = app.status ?? '';

      // items array & total_price come from controller
      const itemsArr = Array.isArray(app.items) ? app.items : [];

      // compact summary: first item + "(+ n more)"
      let itemSummary = '—';
      if (itemsArr.length > 0) {
        const first = escapeHtml(itemsArr[0].item_name || itemsArr[0].name || '');
        itemSummary = itemsArr.length === 1 ? first : `${first} (+ ${itemsArr.length - 1} more)`;
      }

      // Determine total price robustly:
      // priority:
      // 1) app.total_price (explicit)
      // 2) app.total_price_display
      // 3) app.total or app.totalPrice
      // 4) if package/test keys exist directly (app.package_price, app.package?.price...), try those
      // 5) sum items (item_price/price/mrp)
      // 6) fallback to null
      let totalNum = null;
      const candidateVals = ['total_price', 'total_price_display', 'total', 'totalPrice', 'amount', 'price'];
      for (const k of candidateVals) {
        if (typeof app[k] !== 'undefined' && app[k] !== null && app[k] !== '') {
          const n = Number(app[k]);
          if (!isNaN(n)) { totalNum = n; break; }
        }
      }

      // Check package/test nested price fields (if present)
      if (totalNum === null) {
        try {
          if (app.package && (typeof app.package.price !== 'undefined')) {
            const n = Number(app.package.price);
            if (!isNaN(n)) totalNum = n;
          }
        } catch(e){}
      }
      if (totalNum === null) {
        try {
          if (app.test && (typeof app.test.mrp !== 'undefined')) {
            const n = Number(app.test.mrp);
            if (!isNaN(n)) totalNum = n;
          }
        } catch(e){}
      }

      // Fallback to summing items
      if (totalNum === null) {
        const s = sumItemsPrice(itemsArr);
        if (s > 0) totalNum = s;
      }

      const totalPrice = (totalNum !== null && !isNaN(totalNum)) ? `₹${Number(totalNum).toFixed(2)}` : '—';

      const dispStatus = (status === 'Pending') ? `<span class="badge bg-warning text-dark">${escapeHtml(status)}</span>` :
                         (status === 'Approved') ? `<span class="badge bg-success text-white">${escapeHtml(status)}</span>` :
                         (status === 'Completed') ? `<span class="badge bg-primary text-white">${escapeHtml(status)}</span>` :
                         (status === 'Cancelled') ? `<span class="badge bg-danger text-white">${escapeHtml(status)}</span>` :
                         `<span class="badge bg-secondary text-white">${escapeHtml(status)}</span>`;

      const selectHtml = `
        <select class="form-select form-select-sm update-status" data-id="${id}">
          <option value="Pending" ${status === 'Pending' ? 'selected' : ''}>Pending</option>
          <option value="Approved" ${status === 'Approved' ? 'selected' : ''}>Approved</option>
          <option value="Completed" ${status === 'Completed' ? 'selected' : ''}>Completed</option>
          <option value="Cancelled" ${status === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
        </select>
      `;

      // prepare view items button payload (if items exist)
      let viewBtnHtml = '';
      if (itemsArr.length > 0) {
        // safe JSON encode: JSON.stringify then escape
        const itemsJson = escapeHtml(JSON.stringify(itemsArr));
        const totalForBtn = (totalNum !== null && !isNaN(totalNum)) ? Number(totalNum).toFixed(2) : '';
        viewBtnHtml = `<button type="button" class="btn btn-sm btn-outline-secondary view-items-btn" data-items="${itemsJson}" data-total="${totalForBtn}">View</button>`;
      } else {
        viewBtnHtml = `<button type="button" class="btn btn-sm btn-outline-secondary" disabled>—</button>`;
      }

      tbody.insertAdjacentHTML('beforeend', `
        <tr data-appointment-id="${id}">
          <td class="col-id">${id}</td>
          <td class="col-name">${name}</td>
          <td class="col-email">${email}</td>
          <td class="col-phone">${phone}</td>
          <td class="col-date">${dateAndTime}</td>
          <td class="col-item">${itemSummary}</td>
          <td class="col-price">${totalPrice}</td>
          <td class="col-message">${message}</td>
          <td class="col-status">${dispStatus}</td>
          <td class="col-action d-flex gap-1 align-items-center">${selectHtml}${viewBtnHtml}</td>
        </tr>
      `);
    }

    // attach handlers for the dynamic "View" buttons
    document.querySelectorAll('.view-items-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const itemsJson = this.getAttribute('data-items') || '[]';
        const total = this.getAttribute('data-total') || '';
        let items;
        try { items = JSON.parse(itemsJson); } catch (e) { items = []; }

        const modalBody = document.getElementById('appointmentItemsModalBody');
        const modalTotal = document.getElementById('appointmentItemsModalTotal');
        if (!modalBody) return;

        modalBody.innerHTML = '';
        if (items.length === 0) {
          modalBody.innerHTML = '<div class="text-muted">No items</div>';
        } else {
          for (const it of items) {
            const name = escapeHtml(it.item_name || it.name || '');
            const qty = Number(it.quantity || it.qty || 1);
            const priceNum = Number(it.item_price ?? it.price ?? it.mrp ?? 0) || 0;
            const price = priceNum ? `₹${priceNum.toFixed(2)}` : '—';
            modalBody.insertAdjacentHTML('beforeend', `<div class="d-flex justify-content-between py-1 border-bottom"><div>${name} ${qty > 1 ? '×'+qty : ''}</div><div class="text-end">${price}</div></div>`);
          }
        }
        modalTotal.textContent = total ? `Total: ₹${Number(total).toFixed(2)}` : '';
        const modal = new bootstrap.Modal(document.getElementById('appointmentItemsModal'));
        modal.show();
      });
    });
  }

  /* renderPaginationControls */
  function renderPaginationControls(cur, last) {
    const container = document.getElementById('appointmentsPagination');
    if (!container) return;
    container.innerHTML = '';
    function li(text, disabled=false, Published=false, onClick=null) {
      const el = document.createElement('li');
      el.className = 'page-item' + (disabled ? ' disabled' : '') + (Published ? ' Published' : '');
      el.innerHTML = `<a class="page-link" href="javascript:void(0)">${text}</a>`;
      if (!disabled && onClick) el.addEventListener('click', onClick);
      return el;
    }
    container.appendChild(li('Previous', cur <= 1, false, () => { if (cur>1) { currentPage = cur-1; loadAppointments(currentPage); } }));
    const maxButtons = 7;
    let start = Math.max(1, cur - Math.floor(maxButtons/2));
    let end = start + maxButtons - 1;
    if (end > last) { end = last; start = Math.max(1, end - maxButtons + 1); }
    if (start > 1) { container.appendChild(li('1', false, false, () => { currentPage=1; loadAppointments(1); })); if (start>2) { const dot=document.createElement('li'); dot.className='page-item disabled'; dot.innerHTML='<a class="page-link">…</a>'; container.appendChild(dot); } }
    for (let p=start;p<=end;p++) container.appendChild(li(String(p), false, p===cur, () => { if (p!==currentPage) { currentPage=p; loadAppointments(currentPage); } }));
    if (end < last) { if (end < last-1) { const dot=document.createElement('li'); dot.className='page-item disabled'; dot.innerHTML='<a class="page-link">…</a>'; container.appendChild(dot); } container.appendChild(li(String(last), false, false, () => { currentPage=last; loadAppointments(last); })); }
    container.appendChild(li('Next', cur >= last, false, () => { if (cur < last) { currentPage = cur + 1; loadAppointments(currentPage); } }));
  }

  /* Optimistic status update */
  document.addEventListener('change', function(e) {
    if (!e.target.classList.contains('update-status')) return;

    const selectEl = e.target;
    const appointmentId = selectEl.dataset.id;
    const newStatus = selectEl.value;
    const row = document.querySelector(`tr[data-appointment-id="${appointmentId}"]`);
    const statusCell = row?.querySelector('.col-status');

    const prev = selectEl.getAttribute('data-prev') || selectEl.querySelector('option[selected]')?.value || selectEl.value;
    selectEl.setAttribute('data-prev', prev);

    // optimistic badge
    if (statusCell) {
      const badgeHtml = (newStatus === 'Pending') ? `<span class="badge bg-warning text-dark">${escapeHtml(newStatus)}</span>` :
                        (newStatus === 'Approved') ? `<span class="badge bg-success text-white">${escapeHtml(newStatus)}</span>` :
                        (newStatus === 'Completed') ? `<span class="badge bg-primary text-white">${escapeHtml(newStatus)}</span>` :
                        (newStatus === 'Cancelled') ? `<span class="badge bg-danger text-white">${escapeHtml(newStatus)}</span>` :
                        `<span class="badge bg-secondary text-white">${escapeHtml(newStatus)}</span>`;
      statusCell.innerHTML = badgeHtml;
    }

    selectEl.disabled = true;

    fetch(`${BASE_APPOINTMENTS_URL}/${appointmentId}/status`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({ status: newStatus })
    })
    .then(async res => {
      const json = await res.json().catch(()=>null);
      if (!res.ok || !json || !json.success) throw new Error((json && json.message) ? json.message : `HTTP ${res.status}`);
      selectEl.setAttribute('data-prev', newStatus);
    })
    .catch(err => {
      console.error('Update error:', err);
      alert('❌ Failed to update status: ' + err.message);
      const prevVal = selectEl.getAttribute('data-prev') || '';
      if (prevVal) {
        selectEl.value = prevVal;
        if (statusCell) {
          const badgeRevert = (prevVal === 'Pending') ? `<span class="badge bg-warning text-dark">${escapeHtml(prevVal)}</span>` :
                             (prevVal === 'Approved') ? `<span class="badge bg-success text-white">${escapeHtml(prevVal)}</span>` :
                             (prevVal === 'Completed') ? `<span class="badge bg-primary text-white">${escapeHtml(prevVal)}</span>` :
                             (prevVal === 'Cancelled') ? `<span class="badge bg-danger text-white">${escapeHtml(prevVal)}</span>` :
                             `<span class="badge bg-secondary text-white">${escapeHtml(prevVal)}</span>`;
          statusCell.innerHTML = badgeRevert;
        }
      }
      loadAppointments(currentPage);
    })
    .finally(() => selectEl.disabled = false);
  });

  /* Admin fragments loader (packages & tests) */
  function enhanceInjectedAdminUI(container, reloadFn) {
    if (!container) return;

    // Make 'Back' links AJAX-friendly
    container.querySelectorAll('a[data-ajax="true"]').forEach(a => {
      a.addEventListener('click', function(e){ e.preventDefault(); const url = this.dataset.url || this.getAttribute('href'); if (!url) return; reloadFn(url); });
    });

    // ---- Improved form submit handler: robust, shows validation errors, normalizes values ----
    container.querySelectorAll('form[data-ajax="true"]').forEach(f => {
      f.addEventListener('submit', async function(e){
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);

        // Defensive: ensure mrp & discounted_price are present
        try {
          const mrpEl = form.querySelector('[name="mrp"]');
          const discEl = form.querySelector('[name="discounted_price"]');
          if (mrpEl) fd.set('mrp', mrpEl.value === undefined ? '' : mrpEl.value);
          if (discEl) fd.set('discounted_price', discEl.value === undefined ? '' : discEl.value);

          // Normalize status to allowed strings: Published or Draft
          const statusEl = form.querySelector('[name="status"]');
          if (statusEl) {
            if (statusEl.type === 'checkbox') {
              fd.set('status', statusEl.checked ? 'Published' : 'Draft');
            } else {
              fd.set('status', statusEl.value);
            }
          }
        } catch (err) {
          console.warn('Form normalization error', err);
        }

        const action = form.getAttribute('action') || container.dataset.loadedUrl || window.location.href;
        const method = (form.getAttribute('method') || 'POST').toUpperCase();

        // Clear previous validation UI
        try {
          form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
          form.querySelectorAll('.invalid-feedback').forEach(el => { el.textContent = ''; el.style.display = 'none'; });
          const genErr = form.querySelector('#formGeneralErrors');
          if (genErr) { genErr.classList.add('d-none'); genErr.textContent = ''; }
        } catch (e) {}

        try {
          const resp = await fetch(action, {
            method,
            headers: {
              'X-CSRF-TOKEN': CSRF_TOKEN,
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: fd,
            credentials: 'same-origin'
          });

          const ct = resp.headers.get('content-type') || '';
          let json = null;
          if (ct.includes('application/json')) {
            json = await resp.json().catch(()=>null);
          } else {
            // fallback: try text for debugging
            const txt = await resp.text().catch(()=>null);
            console.warn('Non-JSON response for AJAX form submit', resp.status, txt ? txt.substring(0,200) : '');
          }

          if (!resp.ok) {
            if (json && json.errors) {
              // display field validation errors
              Object.keys(json.errors).forEach(function(k){
                const msgs = json.errors[k] || [];
                const field = form.querySelector('[name="'+k+'"]');
                if (field) {
                  field.classList.add('is-invalid');
                  let fb = field.nextElementSibling;
                  if (!fb || !fb.classList.contains('invalid-feedback')) {
                    fb = form.querySelector('[data-error-for="'+k+'"]') || null;
                  }
                  if (fb) { fb.textContent = msgs.join(', '); fb.style.display = ''; }
                } else {
                  const gen = form.querySelector('#formGeneralErrors');
                  if (gen) { gen.classList.remove('d-none'); gen.textContent = msgs.join(', '); }
                }
              });
              return; // stop; user must fix validation errors
            }
            // other HTTP errors
            throw new Error('HTTP ' + resp.status + (json && json.message ? ' — ' + json.message : ''));
          }

          // success -> reload fragment to reflect changes
          const currentUrl = container.dataset.loadedUrl || BASE_ADMIN_PACKAGES;
          reloadFn(currentUrl);
        } catch (err) {
          console.error('Error submitting form (enhanced handler):', err);
          alert('Error submitting form: ' + (err.message || 'Unknown error'));
        }
      });
    });

    // Keep pagination links AJAX
    container.querySelectorAll('.pagination a').forEach(a => {
      a.addEventListener('click', function(e){ e.preventDefault(); const href = this.getAttribute('href'); if (!href) return; container.dataset.loadedUrl = href; reloadFn(href); });
    });

    // Attach delete confirm handlers for any delete forms inside this injected fragment
    attachDeleteConfirm(container);
  }

  /* safe feather replacement helper */
  function safeFeatherReplace() { try { if (window.feather && typeof window.feather.replace === 'function') window.feather.replace(); } catch(e) { console.warn('feather.replace failed', e); } }

  /* ----------------------------
     UPDATED: loadAdminPackages
     - Handles JSON { html: "<...>" } and plain HTML fragments.
     - Falls back to redirect when a full HTML page is returned (login/redirect).
     ---------------------------- */
  function loadAdminPackages(url = BASE_ADMIN_PACKAGES) {
    const body = document.getElementById('packageAdminBody');
    const card = document.getElementById('packageAdminCard');
    const appointmentsCard = document.getElementById('appointmentsCard');
    const otherCard = document.getElementById('labtestAdminCard');
    if (!body) return;
    body.innerHTML = `<div class="text-center py-4">Loading packages…</div>`;
    body.dataset.loadedUrl = url;

    fetch(url, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json, text/html'
      },
      credentials: 'same-origin'
    })
    .then(async res => {
      const ct = (res.headers.get('content-type') || '').toLowerCase();
      const text = await res.text().catch(()=>'');

      // If server returned a full HTML page (e.g. redirect to login), do a hard redirect
      if (/<!doctype|<html/i.test(text)) { window.location.href = url; return null; }

      // If JSON or looks like JSON, parse and extract html
      if (ct.includes('application/json') || /^\s*\{/.test(text)) {
        try {
          const json = JSON.parse(text);
          if (json && typeof json.html === 'string') return json.html;
          // fallback: if json itself is stringish, coerce
          return String(text);
        } catch (e) {
          console.warn('Failed to parse JSON for packages response, falling back to text', e);
          return text;
        }
      }

      // otherwise treat as HTML fragment
      return text;
    })
    .then(html => {
      if (html === null) return; // redirected
      if (typeof html !== 'string') html = String(html || '');
      if (otherCard) otherCard.style.display = 'none';
      body.innerHTML = html;
      enhanceInjectedAdminUI(body, loadAdminPackages);
      if (appointmentsCard) appointmentsCard.style.display = 'none';
      if (card) card.style.display = 'block';
      safeFeatherReplace();
    })
    .catch(err => {
      body.innerHTML = `<div class="text-danger py-4 text-center">Failed loading packages: ${escapeHtml(err.message)}</div>`;
      console.error('loadAdminPackages error', err);
    });
  }

  /* ----------------------------
     UPDATED: loadAdminLabTests
     - Handles JSON { html: "<...>" } and plain HTML fragments.
     - Falls back to redirect when a full HTML page is returned (login/redirect).
     ---------------------------- */
function loadAdminLabTests(url = BASE_ADMIN_LABTESTS) {
  const body = document.getElementById('labtestAdminBody');
  const card = document.getElementById('labtestAdminCard');
  const appointmentsCard = document.getElementById('appointmentsCard');
  const otherCard = document.getElementById('packageAdminCard');
  if (!body) return;
  body.innerHTML = `<div class="text-center py-4">Loading tests…</div>`;
  body.dataset.loadedUrl = url;

  fetch(url, {
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json, text/html'
    },
    credentials: 'same-origin'
  })
  .then(async res => {
    const ct = (res.headers.get('content-type') || '').toLowerCase();
    const text = await res.text().catch(()=>'');

    // Full HTML (redirect to login or full page) -> hard redirect
    if (/<!doctype|<html/i.test(text)) { window.location.href = url; return null; }

    // Heuristic: JSON response
    if (ct.includes('application/json') || /^\s*\{/.test(text)) {
      try {
        const json = JSON.parse(text);

        // If server provided html property, get it
        if (json && typeof json.html === 'string') {
          let html = json.html;

          // If html looks double-encoded (contains literal backslash-n or escaped unicode),
          // unescape common sequences so innerHTML renders correctly.
          // Convert visible `\n`, `\t`, `\/`, `\"`, `\uXXXX` into actual chars:
          if (/\\n|\\u[0-9a-fA-F]{4}|\\\//.test(html)) {
            // Unescape unicode \uXXXX
            html = html.replace(/\\u([0-9a-fA-F]{4})/g, (_, g) =>
              String.fromCharCode(parseInt(g, 16))
            );
            // Unescape common backslash sequences
            html = html.replace(/\\n/g, '\n').replace(/\\r/g, '\r').replace(/\\t/g, '\t');
            html = html.replace(/\\\//g, '/').replace(/\\"/g, '"').replace(/\\\\/g, '\\');
          }
          return html;
        }

        // fallback: maybe server sent html as top-level string or other shape
        return String(text);
      } catch (e) {
        console.warn('Failed to parse JSON for labtests response, falling back to text', e);
        return text;
      }
    }

    // treat as HTML fragment
    return text;
  })
  .then(html => {
    if (html === null) return; // redirected
    if (typeof html !== 'string') html = String(html || '');
    if (otherCard) otherCard.style.display = 'none';
    body.innerHTML = html;
    enhanceInjectedAdminUI(body, loadAdminLabTests);
    
    if (appointmentsCard) appointmentsCard.style.display = 'none';
    if (card) card.style.display = 'block';
    safeFeatherReplace();
  })
  .catch(err => {
    body.innerHTML = `<div class="text-danger py-4 text-center">Failed loading tests: ${escapeHtml(err.message)}</div>`;
    console.error('loadAdminLabTests error', err);
  });
}


  /* utility */
  function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    return String(text)
      .replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;')
      .replaceAll('"','&quot;').replaceAll("'",'&#039;');
  }

  // expose for console/debugging if needed
  window.Dashboard = {
    loadAppointments,
    loadAdminPackages,
    loadAdminLabTests,
    config: cfg
  };

  /* dashboard-kpis.js (uses COUNTS_URL from cfg) */
(function () {
  // Prefer URL from config if present
  const countsUrl = (window.DashboardConfig && window.DashboardConfig.countsUrl) || '/dashboard/counts';

  // Grab KPI elements once (may be missing on some pages)
  const elCustomers = document.getElementById('kpi-customers');
  const elAppointments = document.getElementById('kpi-appointments');
  const elPackages = document.getElementById('kpi-packages');

  // If none of the KPI elements exist, abort silently (this file may be included on pages without KPIs)
  if (!elCustomers && !elAppointments && !elPackages) {
    return;
  }

  function updateKpiElements(data) {
    try {
      if (data && typeof data === 'object') {
        if (typeof data.customers !== 'undefined' && elCustomers) {
          elCustomers.textContent = data.customers;
        }
        if (typeof data.appointments !== 'undefined' && elAppointments) {
          elAppointments.textContent = data.appointments;
        }
        if (typeof data.packages !== 'undefined' && elPackages) {
          elPackages.textContent = data.packages;
        }
      }
    } catch (err) {
      console.warn('updateKpiElements failed:', err);
    }
  }

  async function fetchCounts() {
    try {
      const resp = await fetch(countsUrl, {
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
      });
      if (!resp.ok) throw new Error('Network response not ok: ' + resp.status);
      const json = await resp.json();

      // update KPI numbers if present
      updateKpiElements(json);

      // update the "last updated" element only if it exists and server provided a timestamp
      const lastUpdatedEl = document.getElementById('lastUpdated');
      if (lastUpdatedEl && json && (json.timestamp || json.updated_at)) {
        try {
          lastUpdatedEl.textContent = 'Last updated: ' + (json.timestamp || json.updated_at);
        } catch (e) {
          console.warn('Could not set lastUpdated textContent:', e);
        }
      }
    } catch (err) {
      // Don't let this stop interval polling; log for debugging
      console.error('Error fetching dashboard counts:', err);
    }
  }

  // Run once DOM is ready and then on an interval
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      fetchCounts();
      setInterval(fetchCounts, 15000);
    });
  } else {
    fetchCounts();
    setInterval(fetchCounts, 15000);
  }
})();


})();
