@extends('layouts.app')

@section('title', 'Edit FAQ')

@section('content')

<style>
    :root {
        --wc-text: #09131c;
        --wc-muted: #6b7680;
        --wc-border: #d9e2ec;
        --wc-accent: #0f9d80;
        --wc-accent-2: #1fb28a;
        --wc-bg-soft: #f5f8fa;
        --wc-danger: #dc2626;
    }

    /* Layout */
    .admin-page-wrapper {
        margin: 20px 22px 25px 22px;
    }

    .admin-page-card-flush {
        margin-left: -18px;
        margin-right: -18px;
        width: calc(100% + 36px);
        border-radius: 12px;
        box-shadow: 0 8px 26px rgba(15, 23, 42, 0.06);
        background: #ffffff;
    }

    @media (max-width: 768px) {
        .admin-page-wrapper { margin: 15px 12px 18px; }
        .admin-page-card-flush { margin-left: 0; margin-right: 0; width: 100%; }
    }

    .panel-title {
        margin: 0;
        font-size: 21px;
        font-weight: 700;
        color: var(--wc-text);
        line-height: 1.1;
    }

    .card-body-soft {
        background: #f7fafc;
    }

    /* Buttons */
    .btn-primary-pill {
        background: linear-gradient(90deg, var(--wc-accent), var(--wc-accent-2));
        border: none;
        color: #fff !important;
        padding: 8px 16px;
        font-weight: 600;
        border-radius: 999px;
        box-shadow: 0 6px 18px rgba(15, 157, 128, 0.18);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        text-decoration: none;
    }
    .btn-primary-pill:hover {
        filter: brightness(0.97);
        box-shadow: 0 8px 22px rgba(15, 157, 128, 0.25);
    }

    .btn-ghost-secondary {
        background: #fff;
        border-radius: 999px;
        border: 1px solid #e5e7eb;
        color: #374151 !important;
        padding: 7px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.86rem;
        font-weight: 500;
        text-decoration: none;
    }
    .btn-ghost-secondary:hover { background: #f9fafb; }

    /* Labels & inputs */
    .wc-field-label {
        font-weight: 700;
        color: var(--wc-text);
        margin-bottom: 6px;
        font-size: 0.9rem;
    }

    .wc-control {
        border-radius: 8px;
        border: 1px solid var(--wc-border);
        padding: 8px 10px;
        transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        height: 42px;
    }

    textarea.wc-control {
        height: auto;
        min-height: 120px;
    }

    .wc-control:focus {
        border-color: var(--wc-accent);
        box-shadow: 0 0 0 3px rgba(15,157,128,0.14);
        outline: none;
    }

    .wc-control.is-invalid {
        border-color: var(--wc-danger) !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
    }

    .wc-help-text {
        font-size: 0.8rem;
        color: var(--wc-muted);
    }

    .field-error {
        color: var(--wc-danger);
        font-size: 0.82rem;
        margin-top: 6px;
        font-weight: 600;
    }

    /* ===== Custom Dropdown UI (Same as Package Edit) ===== */
    .wc-select-shell {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--wc-border);
        padding: 8px 14px;
        border-radius: 999px;
        background: #ffffff;
        min-height: 44px;
        cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .wc-select-shell:hover {
        border-color: var(--wc-accent);
        background-color: #f9fafb;
    }

    .wc-select-shell:focus-within,
    .wc-select-shell.open {
        border-color: var(--wc-accent);
        box-shadow: 0 0 0 3px rgba(15,157,128,0.18);
    }

    .wc-select-shell i {
        color: var(--wc-accent);
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .status-select {
        position: absolute;
        inset: 0;
        opacity: 0;
        pointer-events: none;
    }

    .wc-select-display {
        font-weight: 600;
        font-size: 0.92rem;
        color: var(--wc-text);
        padding-right: 24px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .wc-select-placeholder {
        color: var(--wc-muted);
        font-weight: 500;
    }

    .wc-select-shell::after {
        content: '\f078';
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        color: var(--wc-muted);
        pointer-events: none;
        transition: transform .18s ease, color .18s ease;
    }

    .wc-select-shell.open::after {
        transform: translateY(-50%) rotate(180deg);
        color: var(--wc-accent);
    }

    .wc-select-menu {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 6px);
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow:
            0 18px 45px rgba(15,23,42,0.12),
            0 0 0 1px rgba(148,163,184,0.18);
        padding: 6px;
        z-index: 40;
        opacity: 0;
        transform: translateY(4px);
        pointer-events: none;
        transition: opacity .18s ease, transform .18s ease;
    }

    .wc-select-menu.show {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .wc-select-option {
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        padding: 8px 10px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--wc-text);
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
    }

    .wc-select-option span.label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .wc-select-option span.badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #9ca3af;
        flex-shrink: 0;
    }

    .wc-select-option[data-value="1"] span.badge-dot {
        background: #16a34a;
    }

    .wc-select-option[data-value="0"] span.badge-dot {
        background: #9ca3af;
    }

    .wc-select-option:hover {
        background: #f3f4ff;
    }

    .wc-select-option.active {
        background: #eefdf5;
        color: var(--wc-accent);
    }

    .wc-select-option.active span.badge-dot {
        box-shadow: 0 0 0 3px rgba(34,197,94,0.25);
    }

    .wc-select-option .kbd {
        font-size: 0.7rem;
        border-radius: 6px;
        padding: 2px 6px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        color: #6b7280;
    }
</style>

<div class="admin-page-wrapper">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="panel-title">Edit FAQ</h3>
            <div class="wc-help-text mt-1">Update existing frequently asked question</div>
        </div>

        <a href="{{ route('admin.faqs.index') }}" class="btn-ghost-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card admin-page-card-flush">
        <div class="card-body card-body-soft p-4">

            @php
                // dropdown expects 1/0
                $oldActive = old('is_active', (int)($faq->is_active ?? 1));
            @endphp

            <form action="{{ route('admin.faqs.update', $faq->id) }}"
                  method="POST"
                  id="faqEditForm"
                  novalidate>
                @csrf
                @method('PUT')

                {{-- Question --}}
                <div class="mb-3">
                    <label class="wc-field-label">Question <span class="text-danger">*</span></label>

                    <input type="text"
                           name="question"
                           class="form-control wc-control @error('question') is-invalid @enderror"
                           placeholder="Enter FAQ question..."
                           value="{{ old('question', $faq->question) }}">

                    @error('question')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Answer --}}
                <div class="mb-3">
                    <label class="wc-field-label">Answer <span class="text-danger">*</span></label>

                    <textarea name="answer"
                              class="form-control wc-control @error('answer') is-invalid @enderror"
                              placeholder="Enter FAQ answer..."
                              rows="5">{{ old('answer', $faq->answer) }}</textarea>

                    @error('answer')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sort Order --}}
                <div class="mb-3">
                    <label class="wc-field-label">Sort Order</label>

                    <input type="number"
                           name="sort_order"
                           class="form-control wc-control @error('sort_order') is-invalid @enderror"
                           value="{{ old('sort_order', $faq->sort_order ?? 0) }}">

                    <div class="wc-help-text mt-1">Lower number will show first.</div>

                    @error('sort_order')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status Dropdown (Custom UI like Package Edit) --}}
                <div class="mb-3">
                    <label class="wc-field-label">
                        Status <span class="text-danger">*</span>
                    </label>

                    <div class="wc-select-shell" id="faqStatusDropdown" tabindex="0">
                        <i class="fa-solid fa-circle-dot"></i>

                        {{-- Displayed text --}}
                        <div class="wc-select-display" id="faqStatusDisplay">
                            @if ((string)$oldActive === '1')
                                Active
                            @elseif ((string)$oldActive === '0')
                                Inactive
                            @else
                                <span class="wc-select-placeholder">-- Select status --</span>
                            @endif
                        </div>

                        {{-- Native select (hidden but submits form) --}}
                        <select name="is_active"
                                id="faqStatusSelect"
                                class="status-select"
                                required>
                            <option value="1" {{ (string)$oldActive === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ (string)$oldActive === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>

                        {{-- Custom menu --}}
                        <div class="wc-select-menu" id="faqStatusMenu">
                            <button type="button" class="wc-select-option" data-value="1">
                                <span class="label">
                                    <span class="badge-dot"></span>
                                    Active
                                </span>
                                <span class="kbd">A</span>
                            </button>

                            <button type="button" class="wc-select-option" data-value="0">
                                <span class="label">
                                    <span class="badge-dot"></span>
                                    Inactive
                                </span>
                                <span class="kbd">I</span>
                            </button>
                        </div>
                    </div>

                    <div class="wc-help-text mt-1">
                        If Inactive, FAQ will not show on frontend.
                    </div>

                    @error('is_active')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn-primary-pill">
                        <i class="fa-solid fa-check"></i> Update FAQ
                    </button>

                    <a href="{{ route('admin.faqs.index') }}" class="btn-ghost-secondary">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Prevent double submit
    const form = document.getElementById('faqEditForm');
    if (form) {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
            }
        });
    }

    // ===== Custom FAQ Status Dropdown (Same logic as Package) =====
    const statusShell   = document.getElementById('faqStatusDropdown');
    const statusSelect  = document.getElementById('faqStatusSelect');
    const statusDisplay = document.getElementById('faqStatusDisplay');
    const statusMenu    = document.getElementById('faqStatusMenu');
    const statusOptions = statusMenu ? statusMenu.querySelectorAll('.wc-select-option') : [];

    function setStatusValue(value) {
        if (!statusSelect || !statusDisplay) return;

        const option = Array.from(statusSelect.options).find(o => o.value === value);
        if (!option) return;

        statusSelect.value = value;
        statusDisplay.textContent = option.textContent.trim();
        statusDisplay.classList.remove('wc-select-placeholder');

        statusOptions.forEach(btn => {
            if (btn.dataset.value === value) btn.classList.add('active');
            else btn.classList.remove('active');
        });
    }

    function openMenu() {
        if (!statusShell || !statusMenu) return;
        statusShell.classList.add('open');
        statusMenu.classList.add('show');
    }

    function closeMenu() {
        if (!statusShell || !statusMenu) return;
        statusShell.classList.remove('open');
        statusMenu.classList.remove('show');
    }

    function toggleMenu() {
        if (!statusMenu) return;
        if (statusMenu.classList.contains('show')) closeMenu();
        else openMenu();
    }

    // Init active
    if (statusSelect && statusSelect.value !== '') {
        setStatusValue(statusSelect.value);
    }

    if (statusShell && statusMenu) {
        statusShell.addEventListener('click', function (e) {
            if (e.target.closest('.wc-select-menu')) return;
            toggleMenu();
        });

        statusMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        statusOptions.forEach(btn => {
            btn.addEventListener('click', function () {
                const val = this.dataset.value;
                setStatusValue(val);
                closeMenu();
            });
        });

        document.addEventListener('click', function (e) {
            if (!statusShell.contains(e.target)) closeMenu();
        });

        // Keyboard shortcuts A / I
        statusShell.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeMenu();
                statusShell.blur();
            }
            if (e.key.toLowerCase() === 'a') {
                setStatusValue('1');
                closeMenu();
            }
            if (e.key.toLowerCase() === 'i') {
                setStatusValue('0');
                closeMenu();
            }
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleMenu();
            }
        });
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', function () {
            if (this.value !== '') setStatusValue(this.value);
        });
    }
});
</script>

@endsection
