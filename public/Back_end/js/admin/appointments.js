// public/js/admin/appointments.js

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    initFilterControls();
    initRescheduleModal();
    initStatusMenuFlow();
    initExportInline();
    initItemsModal();
    initUploadReport();
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
    const fromInp = document.getElementById('appointmentsDateFrom');
    const toInp = document.getElementById('appointmentsDateTo');
    const dateErrorMsg = document.getElementById('apDateError');

    const exportToggle = document.getElementById('apExportToggle');
    const exportMenu = document.getElementById('apExportMenu');

    function closeDatePopover() {
        if (datePopover) datePopover.classList.remove('show');
    }

    function closeExportMenu() {
        if (exportMenu) exportMenu.classList.remove('show');
    }

    // Update Apply button state
    function updateApplyStateFixed() {
        if (!dateApply || !fromInp || !toInp) return;

        const hasFrom = !!fromInp.value.trim();
        const hasTo = !!toInp.value.trim();

        // ❌ Case 1: both empty → disable Apply
        if (!hasFrom && !hasTo) {
            dateApply.disabled = true;
            if (dateErrorMsg) {
                dateErrorMsg.textContent = 'Please select From and To dates, or use Clear.';
                dateErrorMsg.style.display = 'block';
            }
            return;
        }

        // ❌ Case 2: only one filled → disable Apply
        if ((hasFrom && !hasTo) || (!hasFrom && hasTo)) {
            dateApply.disabled = true;
            if (dateErrorMsg) {
                dateErrorMsg.textContent = 'Please select both From and To dates.';
                dateErrorMsg.style.display = 'block';
            }
            return;
        }

        // ✅ Case 3: both filled → enable Apply
        dateApply.disabled = false;
        if (dateErrorMsg) {
            dateErrorMsg.textContent = '';
            dateErrorMsg.style.display = 'none';
        }
    }

    // Event listeners
    if (fromInp) {
        fromInp.addEventListener('input', updateApplyStateFixed);
        fromInp.addEventListener('change', updateApplyStateFixed);
    }
    if (toInp) {
        toInp.addEventListener('input', updateApplyStateFixed);
        toInp.addEventListener('change', updateApplyStateFixed);
    }
    updateApplyStateFixed();

    if (dateToggle && datePopover) {
        dateToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const show = !datePopover.classList.contains('show');
            closeExportMenu();
            if (show) datePopover.classList.add('show');
            else closeDatePopover();
        });
    }

    if (dateApply && filterForm) {
        dateApply.addEventListener('click', function(e) {
            e.preventDefault();
            if (dateApply.disabled) return;
            filterForm.submit();
        });
    }

    if (dateClose) {
        dateClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeDatePopover();
        });
    }

    if (dateClear && filterForm) {
        dateClear.addEventListener('click', function(e) {
            e.preventDefault();
            if (fromInp) fromInp.value = '';
            if (toInp) toInp.value = '';
            if (dateErrorMsg) {
                dateErrorMsg.textContent = '';
                dateErrorMsg.style.display = 'none';
            }
            updateApplyStateFixed();
            filterForm.submit();
        });
    }

    if (exportToggle && exportMenu) {
        exportToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const show = !exportMenu.classList.contains('show');
            closeDatePopover();
            if (show) exportMenu.classList.add('show');
            else closeExportMenu();
        });
    }

    // Close popovers on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.date-wrap')) closeDatePopover();
        if (!e.target.closest('.export-wrap')) closeExportMenu();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDatePopover();
            closeExportMenu();
        }
    });
}

// 2. Reschedule Modal
function initRescheduleModal() {
    const modalEl = document.getElementById('adminRescheduleModal');
    const form = document.getElementById('adminRescheduleForm');
    const dateEl = document.getElementById('rescheduleDate');
    const timeEl = document.getElementById('rescheduleTime');
    const customEl = document.getElementById('customTimeInput');
    const idEl = document.getElementById('rescheduleAppointmentId');

    const dateError = document.getElementById('dateError');
    const timeError = document.getElementById('timeError');
    const errorEl = document.getElementById('rescheduleError');
    const submitBtn = document.getElementById('rescheduleSubmitBtn');

    if (!modalEl || !form) return;

    function todayYMD() {
        const now = new Date();
        const y = now.getFullYear();
        const m = String(now.getMonth() + 1).padStart(2, '0');
        const d = String(now.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    function isDateBeforeToday(ymd) {
        if (!ymd) return true;
        const [y, m, d] = ymd.split('-').map(Number);
        const chosen = new Date(y, m - 1, d, 0, 0, 0, 0);
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0, 0);
        return chosen < today;
    }

    function parseClockLikeTime(str) {
        if (!str) return null;
        const s = str.trim();

        let m = s.match(/^(\d{1,2})(?::(\d{2}))?\s*([AaPp][Mm])$/);
        if (m) {
            let h = parseInt(m[1], 10),
                min = parseInt(m[2] ?? '0', 10);
            const ap = m[3].toLowerCase();
            if (h === 12) h = (ap === 'am') ? 0 : 12;
            else if (ap === 'pm') h += 12;
            return (h >= 0 && h < 24 && min >= 0 && min < 60) ? { h, min } : null;
        }
        m = s.match(/^([01]?\d|2[0-3]):([0-5]\d)$/);
        if (m) return { h: parseInt(m[1], 10), min: parseInt(m[2], 10) };
        m = s.match(/^(\d{1,2})\s*([AaPp][Mm])$/);
        if (m) {
            let h = parseInt(m[1], 10),
                min = 0,
                ap = m[2].toLowerCase();
            if (h === 12) h = (ap === 'am') ? 0 : 12;
            else if (ap === 'pm') h += 12;
            return (h >= 0 && h < 24) ? { h, min } : null;
        }
        return null;
    }

    function to24Hour(str) {
        if (!str) return '';
        const s = str.trim();

        let m = s.match(/^(\d{1,2}):(\d{2})\s*([AaPp][Mm])$/);
        if (m) {
            let h = parseInt(m[1], 10),
                min = m[2],
                ap = m[3].toUpperCase();
            if (ap === 'PM' && h < 12) h += 12;
            if (ap === 'AM' && h === 12) h = 0;
            return `${String(h).padStart(2,'0')}:${min}`;
        }
        m = s.match(/^(\d{1,2})\s*([AaPp][Mm])$/);
        if (m) {
            let h = parseInt(m[1], 10),
                ap = m[2].toUpperCase();
            if (ap === 'PM' && h < 12) h += 12;
            if (ap === 'AM' && h === 12) h = 0;
            return `${String(h).padStart(2,'0')}:00`;
        }
        m = s.match(/^([01]?\d|2[0-3]):([0-5]\d)$/);
        if (m) return `${String(parseInt(m[1],10)).padStart(2,'0')}:${m[2]}`;

        return s;
    }

    function setMinToToday() {
        if (dateEl) dateEl.min = todayYMD();
    }

    // Public function to open modal
    window.openRescheduleModalForSelect = function(selectEl) {
        try {
            const id = selectEl?.dataset?.id;
            if (!id) return;
            form.action = `/admin/appointments/${id}/reschedule`;
            idEl.value = id;

            [dateError, timeError, errorEl].forEach(n => {
                if (n) {
                    n.style.display = 'none';
                    n.textContent = '';
                }
            });
            if (customEl) {
                customEl.value = '';
                customEl.style.display = 'none';
            }
            if (timeEl) timeEl.value = '';

            setMinToToday();

            // Try to prefill existing date/time
            try {
                const row = document.querySelector(`tr[data-appointment-id="${id}"]`);
                if (row && dateEl) {
                    const dateCell = row.querySelector('td:nth-child(4)');
                    if (dateCell) {
                        const txt = (dateCell.innerText || dateCell.textContent || '').trim();
                        const parts = txt.split(',');
                        if (parts.length > 0) {
                            const dparts = parts[0].trim().split(' ');
                            if (dparts.length === 3) {
                                const day = dparts[0].padStart(2, '0');
                                const monStr = dparts[1];
                                const monthMap = {
                                    Jan: '01', Feb: '02', Mar: '03', Apr: '04', May: '05', Jun: '06',
                                    Jul: '07', Aug: '08', Sep: '09', Oct: '10', Nov: '11', Dec: '12'
                                };
                                const mm = monthMap[monStr] || '';
                                const year = dparts[2] || '';
                                if (mm && year) {
                                    const ymd = `${year}-${mm}-${day}`;
                                    dateEl.value = isDateBeforeToday(ymd) ? '' : ymd;
                                }
                            }
                        }
                    }
                    const timeNode = row.querySelector('.date-col .time');
                    if (timeNode && timeEl) {
                        const raw = (timeNode.getAttribute('data-time-raw') || '').trim();
                        const shown = (timeNode.textContent || '').trim();
                        const src = raw || (shown === '-' ? '' : shown);
                        timeEl.value = src || '';
                    }
                }
            } catch (e) {
                console.warn('Could not prefill date/time', e);
            }

            // Show modal
            if (typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else {
                modalEl.style.display = 'block';
            }
        } catch (e) {
            console.error(e);
            alert('Failed to open reschedule modal');
        }
    };

    // Time slot change handler
    if (timeEl && customEl) {
        timeEl.addEventListener('change', () => {
            if (timeError) {
                timeError.style.display = 'none';
                timeError.textContent = '';
            }
            if (timeEl.value === 'custom') {
                customEl.style.display = 'block';
                customEl.focus();
            } else {
                customEl.value = '';
                customEl.style.display = 'none';
            }
        });
    }

    // Date validation
    if (dateEl && dateError) {
        dateEl.addEventListener('change', () => {
            dateError.style.display = 'none';
            dateError.textContent = '';

            const v = dateEl.value;
            if (!v) return;

            if (isDateBeforeToday(v)) {
                const [y, m, d] = todayYMD().split('-');
                dateError.textContent = `Value must be ${d}-${m}-${y} or later.`;
                dateError.style.display = 'block';
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', async function(ev) {
            ev.preventDefault();

            if (dateError) dateError.style.display = 'none';
            if (timeError) timeError.style.display = 'none';
            if (errorEl) errorEl.style.display = 'none';

            setMinToToday();
            const dateVal = dateEl ? dateEl.value : '';
            if (!dateVal) {
                if (dateError) {
                    dateError.textContent = 'Please select a date.';
                    dateError.style.display = 'block';
                }
                return;
            }
            if (isDateBeforeToday(dateVal)) {
                if (dateError) {
                    const [y, m, d] = todayYMD().split('-');
                    dateError.textContent = `Value must be ${d}-${m}-${y} or later.`;
                    dateError.style.display = 'block';
                }
                return;
            }

            const userTimeInput = timeEl.value === 'custom' ? 
                (customEl ? customEl.value.trim() : '') : 
                (timeEl ? timeEl.value.trim() : '');
            if (!userTimeInput) {
                if (timeError) {
                    timeError.textContent = 'Please select a time slot or choose "Custom..." and enter a time.';
                    timeError.style.display = 'block';
                }
                return;
            }

            const clock = parseClockLikeTime(userTimeInput);
            if (clock && dateVal === todayYMD()) {
                const now = new Date();
                const candidate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), clock.h, clock.min, 0, 0);
                if (candidate < now) {
                    if (timeError) {
                        timeError.textContent = 'Selected time is already past for today. Pick a future time or enter free text.';
                        timeError.style.display = 'block';
                    }
                    return;
                }
            }

            const url = form.action;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const body = new URLSearchParams();
            body.append('_method', 'PATCH');
            body.append('date', dateVal);
            body.append('time_slot', to24Hour(userTimeInput));

            if (submitBtn) {
                submitBtn.disabled = true;
                const orig = submitBtn.innerHTML;
                submitBtn.innerHTML = 'Saving...';
            }

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: body.toString()
                });

                if (!res.ok) {
                    let payload = null;
                    try {
                        payload = await res.json();
                    } catch (_) {}

                    if (payload?.errors) {
                        const firstDateErr = payload.errors.date?.[0];
                        const firstTimeErr = payload.errors.time_slot?.[0];
                        if (firstDateErr && dateError) {
                            dateError.textContent = firstDateErr;
                            dateError.style.display = 'block';
                        }
                        if (firstTimeErr && timeError) {
                            timeError.textContent = firstTimeErr;
                            timeError.style.display = 'block';
                        }
                        if (!firstDateErr && !firstTimeErr && errorEl) {
                            errorEl.textContent = payload.message || 'Failed to reschedule.';
                            errorEl.style.display = 'block';
                        }
                    } else {
                        const t = (payload && payload.message) ?
                            payload.message :
                            await res.text().catch(() => `HTTP ${res.status}`);
                        if (errorEl) {
                            errorEl.textContent = t || 'Failed to reschedule.';
                            errorEl.style.display = 'block';
                        }
                    }
                    return;
                }

                // Success
                if (typeof bootstrap !== 'undefined') {
                    const modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                } else {
                    modalEl.style.display = 'none';
                }
                window.location.reload();
            } catch (err) {
                console.error(err);
                if (errorEl) {
                    errorEl.textContent = err?.message || 'Failed to reschedule.';
                    errorEl.style.display = 'block';
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save & Mark Rescheduled';
                }
            }
        });
    }
}

// 3. Status Menu Flow
function initStatusMenuFlow() {
    function closeAllStatusMenus() {
        document.querySelectorAll('.status-menu.show').forEach(m => {
            m.classList.remove('show', 'up');
            m.setAttribute('aria-hidden', 'true');
            const parent = m.closest('.status-action');
            if (parent) {
                const btn = parent.querySelector('.status-action-btn');
                btn && btn.setAttribute('aria-expanded', 'false');
            }
            m.style.maxHeight = '';
            m.style.position = '';
            m.style.zIndex = '';
            m.style.background = '';
        });
    }

    function positionMenu(menu, btn) {
        if (!menu || !btn) return;

        menu.classList.remove('up');
        menu.style.top = '';
        menu.style.bottom = '';

        const prevDisplay = menu.style.display;
        const prevVis = menu.style.visibility;
        menu.style.display = 'block';
        menu.style.visibility = 'hidden';

        const menuRect = menu.getBoundingClientRect();
        const btnRect = btn.getBoundingClientRect();

        const spaceBelow = window.innerHeight - btnRect.bottom - 8;
        const spaceAbove = btnRect.top - 8;

        if (menuRect.height > spaceBelow && spaceAbove > spaceBelow) {
            menu.classList.add('up');
        } else {
            menu.classList.remove('up');
        }

        menu.style.display = prevDisplay || '';
        menu.style.visibility = prevVis || '';
    }

    function openUploadModalForAppointment(appointmentId) {
        const row = document.querySelector(`tr[data-appointment-id="${appointmentId}"]`);
        if (!row) return;

        const patient = (row.querySelector('.patient-name')?.textContent || '').trim();
        const test = (row.querySelector('.item-col .muted')?.textContent ||
            row.querySelector('.item-col')?.textContent || '').trim();

        const appointmentIdEl = document.getElementById('uploadAppointmentId');
        const patientHidden = document.getElementById('uploadPatientName');
        const testHidden = document.getElementById('uploadTestName');
        const patientDisplay = document.getElementById('patient_name_display');
        const testDisplay = document.getElementById('test_name_display');
        const shareMsg = document.getElementById('postUploadShareMessage');
        const fileInput = document.getElementById('reportFileInput');
        const dateInput = document.getElementById('reportDate');
        const alertBox = document.getElementById('uploadReportAlert');

        if (appointmentIdEl) appointmentIdEl.value = appointmentId;
        if (patientHidden) patientHidden.value = patient;
        if (testHidden) testHidden.value = test;
        if (patientDisplay) patientDisplay.value = patient;
        if (testDisplay) testDisplay.value = test;

        // Get phone from contact column
        const mobileInput = document.getElementById('mobileNumberInput');
        let phone = '';
        try {
            const contactMuted = row.querySelectorAll('.contact-col .muted');
            if (contactMuted && contactMuted.length >= 2) {
                phone = (contactMuted[1].textContent || '').trim();
            } else {
                const contactCol = row.querySelector('.contact-col');
                if (contactCol) {
                    phone = (contactCol.textContent || '')
                        .trim()
                        .split('\n')
                        .map(s => s.trim())
                        .filter(Boolean)
                        .pop() || '';
                }
            }
        } catch (e) {
            phone = '';
        }
        phone = phone.replace(/\s+/g, ' ').trim();
        if (mobileInput) {
            mobileInput.value = (phone && phone !== '-' && phone !== '—') ? phone : '';
        }

        // Default message
        const defMsg = `Wellcare Labs - Report for ${patient} (${appointmentId}).`;
        if (shareMsg) shareMsg.value = defMsg;

        // Reset form state
        try {
            if (fileInput) {
                fileInput.value = '';
                fileInput.removeAttribute('data-filename');
            }
            if (dateInput) dateInput.value = '';
            if (alertBox) {
                alertBox.classList.add('d-none');
                alertBox.classList.remove('alert-danger', 'alert-success');
                alertBox.innerText = '';
            }
            const modalForm = document.getElementById('uploadReportForm');
            if (modalForm) {
                modalForm.querySelectorAll('.field-error').forEach(e => {
                    e.textContent = '';
                    e.style.display = 'none';
                });
                modalForm.querySelectorAll('.input-invalid').forEach(i => i.classList.remove('input-invalid'));
            }
        } catch (e) {
            console.warn(e);
        }

        // Show modal
        const modalEl = document.getElementById('uploadReportModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (modalEl) {
            modalEl.style.display = 'block';
        }
    }

    // Event delegation for status actions
    document.addEventListener('click', function(ev) {
        const btn = ev.target.closest('.status-action-btn');
        const item = ev.target.closest('.status-item');

        if (btn) {
            ev.preventDefault();
            ev.stopPropagation();

            const wrap = btn.closest('.status-action');
            if (!wrap) return;
            const menu = wrap.querySelector('.status-menu');
            if (!menu) return;

            const isOpen = menu.classList.contains('show');
            closeAllStatusMenus();

            if (!isOpen) {
                menu.classList.add('show');
                menu.setAttribute('aria-hidden', 'false');
                btn.setAttribute('aria-expanded', 'true');

                menu.style.position = 'absolute';
                menu.style.zIndex = '11000';
                menu.style.background = '#fff';

                positionMenu(menu, btn);
            }
            return;
        }

        if (item) {
            ev.preventDefault();
            ev.stopPropagation();

            const actionWrap = item.closest('.status-action');
            if (!actionWrap) return;

            const appointmentId = actionWrap.dataset.appointmentId;
            const newStatus = item.getAttribute('data-status');
            if (!appointmentId || !newStatus) return;

            if (newStatus === 'Reschedule') {
                closeAllStatusMenus();
                if (typeof window.openRescheduleModalForSelect === 'function') {
                    const fakeSel = {
                        dataset: { id: appointmentId }
                    };
                    window.openRescheduleModalForSelect(fakeSel);
                }
                return;
            }

            if (newStatus === 'Completed') {
                closeAllStatusMenus();
                openUploadModalForAppointment(appointmentId);
                return;
            }

            const swalAvailable = (typeof Swal !== 'undefined');

            const doConfirmThenUpdate = async () => {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                    const body = new URLSearchParams();
                    body.append('_method', 'PATCH');
                    body.append('status', newStatus);

                    const res = await fetch(`/admin/appointments/${appointmentId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: body.toString()
                    });

                    if (!res.ok) {
                        const t = await res.text().catch(() => null);
                        throw new Error(t || `HTTP ${res.status}`);
                    }

                    // Update UI
                    const row = document.querySelector(`tr[data-appointment-id="${appointmentId}"]`);
                    if (row) {
                        const pill = row.querySelector('.status-pill');
                        if (pill) {
                            pill.className = 'status-pill ' + newStatus.toLowerCase();
                            pill.textContent = newStatus;
                        }
                    }

                    const iconEl = actionWrap.querySelector('.status-action-btn .fa');
                    if (iconEl) {
                        const map = {
                            'approved': 'fa-check',
                            'completed': 'fa-circle-check',
                            'cancelled': 'fa-xmark',
                            'reschedule': 'fa-calendar-alt',
                            'pending': 'fa-clock'
                        };
                        const cls = map[newStatus.toLowerCase()] || 'fa-clock';
                        iconEl.className = 'fa ' + cls;
                    }

                    // Show success message
                    if (swalAvailable) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            timer: 2600,
                            showConfirmButton: false,
                            icon: 'success',
                            title: `Status updated to ${newStatus}`
                        });
                    } else {
                        alert(`Status updated to ${newStatus}`);
                    }
                } catch (err) {
                    console.error(err);
                    if (swalAvailable) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: err.message || 'Failed to update status. Please try again.'
                        });
                    } else {
                        alert('Failed to update status. Please try again.');
                    }
                } finally {
                    closeAllStatusMenus();
                }
            };

            closeAllStatusMenus();

            // Confirmation dialog
            if (swalAvailable) {
                Swal.fire({
                    title: `Change status to "${newStatus}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, change it',
                    cancelButtonText: 'Cancel'
                }).then((res) => {
                    if (res.isConfirmed) {
                        Swal.fire({
                            title: 'Updating...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                        doConfirmThenUpdate().then(() => Swal.close());
                    }
                });
            } else {
                if (confirm(`Change status of ID ${appointmentId} to "${newStatus}"?`)) {
                    doConfirmThenUpdate();
                }
            }

            return;
        }

        // Close menus on outside click
        if (!ev.target.closest('.status-action')) {
            closeAllStatusMenus();
        }
    });

    // Close on Escape
    document.addEventListener('keydown', function(ev) {
        if (ev.key === 'Escape') {
            closeAllStatusMenus();
        }
    });
}

// 4. Export Inline Downloads
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
                'appointments_' +
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

// 5. Items Modal
function initItemsModal() {
    const modalEl = document.getElementById('itemsModal');
    const listEl = document.getElementById('itemsModalList');
    const metaEl = document.getElementById('itemsModalMeta');
    const subtotalEl = document.getElementById('itemsSubtotal');
    const discountEl = document.getElementById('itemsDiscount');
    const totalEl = document.getElementById('itemsTotal');
    const couponWrap = document.getElementById('itemsCouponWrap');
    const componentsHint = document.getElementById('itemsModalComponentsHint');

    if (!modalEl) return;

    function money(n) {
        try {
            const num = parseFloat(n || 0);
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
            if (componentsHint) componentsHint.style.display = 'none';
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
            left.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <span class="itm-badge">${(it.item_type || '').toString().toUpperCase()}</span>
                    <span class="fw-semibold">${it.item_name || ''}</span>
                </div>
                ${hasComp ? `
                    <div class="mt-2 p-2" style="background:#f8fafc; border:1px dashed #e5eff3; border-radius:8px;">
                        <div class="itm-meta fw-bold mb-1">Components</div>
                        <ul class="mb-0" style="padding-left:18px;">
                            ${it.components.map(c => `<li class="itm-meta">${(c.name || c.title || '')} — ${money(c.price || 0)}</li>`).join('')}
                        </ul>
                    </div>
                ` : ``}
            `;

            const right = document.createElement('div');
            right.style.flex = '0 0 auto';
            right.innerHTML = `<div class="itm-ttl">${money(lineTotal)}</div>`;

            wrap.appendChild(left);
            wrap.appendChild(right);
            listEl.appendChild(wrap);
        });

        if (componentsHint) {
            componentsHint.style.display = hasComponents ? 'block' : 'none';
        }
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

            const titleEl = document.getElementById('itemsModalLabel');
            if (titleEl) titleEl.textContent = `Items `;
            if (metaEl) metaEl.textContent = patient ? `Patient: ${patient}` : '';

            renderItems(items);

            if (subtotalEl) subtotalEl.textContent = money(subtotal);
            if (discountEl) discountEl.textContent = money(discount);
            if (totalEl) totalEl.textContent = money(total);
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

// 6. Upload Report
function initUploadReport() {
    const uploadForm = document.getElementById('uploadReportForm');
    const uploadModalEl = document.getElementById('uploadReportModal');
    const uploadAlert = document.getElementById('uploadReportAlert');
    const uploadSubmitBtn = document.getElementById('uploadReportSubmitBtn');
    const uploadCancelBtn = document.getElementById('uploadReportCancelBtn');

    if (!uploadForm) return;

    const MAX_FILE_BYTES = 10 * 1024 * 1024;
    const ALLOWED_TYPE = 'application/pdf';

    function clearFieldErrors(form) {
        form.querySelectorAll('.field-error').forEach(el => {
            el.textContent = '';
            el.style.display = 'none';
        });
        form.querySelectorAll('.input-invalid').forEach(el => el.classList.remove('input-invalid'));
    }

    function showFieldError(form, fieldName, message) {
        const errEl = form.querySelector('.field-error[data-for="' + fieldName + '"]');
        const inputEl = form.querySelector('[name="' + fieldName + '"]');
        if (errEl) {
            errEl.textContent = message;
            errEl.style.display = 'block';
        }
        if (inputEl) inputEl.classList.add('input-invalid');
    }

    function showAlert(type, text) {
        if (!uploadAlert) return;
        uploadAlert.classList.remove('d-none', 'alert-danger', 'alert-success');
        uploadAlert.classList.add('alert-' + (type === 'success' ? 'success' : 'danger'));
        uploadAlert.textContent = text;
        uploadAlert.classList.remove('d-none');
    }

    function hideAlert() {
        if (uploadAlert) {
            uploadAlert.classList.add('d-none');
            uploadAlert.classList.remove('alert-danger', 'alert-success');
            uploadAlert.textContent = '';
        }
    }

    function disableSubmit(disabled = true) {
        if (uploadSubmitBtn) {
            uploadSubmitBtn.disabled = disabled;
            uploadSubmitBtn.innerHTML = disabled ?
                '<i class="fa-solid fa-spinner fa-spin me-1"></i> Uploading...' :
                'Upload & Mark Complete';
        }
        if (uploadCancelBtn) uploadCancelBtn.disabled = disabled;
    }

    // Form submission
    uploadForm.addEventListener('submit', async function(ev) {
        ev.preventDefault();
        clearFieldErrors(uploadForm);
        hideAlert();

        const appointmentId = uploadForm.querySelector('input[name="appointment_id"]')?.value || '';
        const reportDateEl = uploadForm.querySelector('input[name="report_date"]');
        const fileInput = uploadForm.querySelector('input[name="report_file"]');
        const stampEl = uploadForm.querySelector('input[name="stamp_header_footer"]');
        const shareMsgEl = uploadForm.querySelector('textarea[name="prefill_message"]');

        let hasError = false;

        /* ---------------- Mobile validation ---------------- */
        const mobileEl = uploadForm.querySelector('input[name="mobile_number"]');
        let mobileVal = (mobileEl?.value || '').trim();

        if (/^\d{10}$/.test(mobileVal)) {
            mobileVal = '+91' + mobileVal;
        }

        const mobilePattern = /^\+?\d{10,15}$/;

        if (!mobileVal) {
            showFieldError(uploadForm, 'mobile_number', 'Please provide a mobile number.');
            hasError = true;
        } else if (!mobilePattern.test(mobileVal)) {
            showFieldError(uploadForm, 'mobile_number', 'Invalid mobile number format.');
            hasError = true;
        } else if (mobileEl) {
            mobileEl.value = mobileVal;
        }

        /* ---------------- Required checks ---------------- */
        if (!appointmentId) {
            showAlert('danger', 'Appointment id missing. Please reopen the upload modal.');
            hasError = true;
        }

        if (!reportDateEl || !reportDateEl.value) {
            showFieldError(uploadForm, 'report_date', 'Please select a report date.');
            hasError = true;
        }

        if (!fileInput || !fileInput.files?.length) {
            showFieldError(uploadForm, 'report_file', 'Please choose a PDF file.');
            hasError = true;
        } else {
            const f = fileInput.files[0];
            if (f.type !== ALLOWED_TYPE) {
                showFieldError(uploadForm, 'report_file', 'Invalid file type — only PDF allowed.');
                hasError = true;
            } else if (f.size > MAX_FILE_BYTES) {
                showFieldError(uploadForm, 'report_file', 'File too large — max 10MB allowed.');
                hasError = true;
            }
        }

        if (!shareMsgEl || !shareMsgEl.value.trim()) {
            showFieldError(uploadForm, 'prefill_message', 'Please provide a share message.');
            hasError = true;
        }

        if (hasError) return;

        /* ---------------- Submit via AJAX ---------------- */
        const formData = new FormData(uploadForm);
        if (stampEl) formData.set('stamp_header_footer', stampEl.checked ? '1' : '0');

        disableSubmit(true);

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const res = await fetch(uploadForm.action, {
                method: uploadForm.method || 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
                credentials: 'same-origin',
            });

            const payload = await res.json().catch(() => null);

            if (!res.ok || !payload?.success) {
                if (payload?.errors) {
                    Object.keys(payload.errors).forEach(k => {
                        showFieldError(uploadForm, k, payload.errors[k][0]);
                    });
                    showAlert('danger', 'Please fix the highlighted fields.');
                } else {
                    showAlert('danger', payload?.message || 'Upload failed.');
                }
                return;
            }

            /* ---------------- Close upload modal ---------------- */
            try {
                if (typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(uploadModalEl).hide();
                }
            } catch (_) {}

            /* ---------------- Prepare Share Modal ---------------- */
            const reportId = payload.report_id;
            const reportUrl = payload.report_url;
            const appointmentIdFromPayload = payload.appointment_id || appointmentId;

            if (!reportId) {
                console.error('Report ID missing in response', payload);
                showAlert('danger', 'Report uploaded but report ID missing.');
                return;
            }

            // Pick phone + email from table row
            let phoneForShare = '';
            let emailForShare = '';
            const row = document.querySelector(`tr[data-appointment-id="${appointmentIdFromPayload}"]`);
            if (row) {
                const contactMuted = row.querySelectorAll('.contact-col .muted');
                if (contactMuted[0]) emailForShare = contactMuted[0].textContent.trim();
                if (contactMuted[1]) phoneForShare = contactMuted[1].textContent.trim();
            }

            const shareLauncher = document.getElementById('autoShareLauncher');
            const shareMsgText = shareMsgEl.value.trim();

            if (shareLauncher) {
                const baseAction = uploadForm.dataset.shareActionBase || '';
                const shareAction = baseAction.replace('__ID__', appointmentIdFromPayload);

                shareLauncher.dataset.shareUrl = reportUrl;
                shareLauncher.dataset.shareAction = shareAction;
                shareLauncher.dataset.shareMessage = shareMsgText;
                shareLauncher.dataset.sharePhone = phoneForShare;
                shareLauncher.dataset.shareEmail = emailForShare;
                shareLauncher.dataset.reportId = reportId;

                shareLauncher.click();
            }

            /* ---------------- Update table row UI ---------------- */
            if (row) {
                const pill = row.querySelector('.status-pill');
                if (pill) {
                    pill.className = 'status-pill completed';
                    pill.textContent = 'Completed';
                }
                const icon = row.querySelector('.status-action-btn .fa');
                if (icon) icon.className = 'fa fa-circle-check';
            }

        } catch (err) {
            console.error(err);
            showAlert('danger', err.message || 'Upload failed.');
        } finally {
            disableSubmit(false);
        }
    });
}