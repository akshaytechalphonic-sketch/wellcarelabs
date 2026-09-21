@extends('layouts.app')

@section('title', 'Dashboard - Edit Banner')

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

  .admin-page-wrapper {
    margin: 20px 22px 25px 22px;
  }

  .admin-page-card-flush {
    margin-left: -18px;
    margin-right: -18px;
    width: calc(100% + 36px);
    border-radius: 12px;
    box-shadow: 0 8px 26px rgba(0, 0, 0, 0.04);
  }

  @media (max-width:768px) {
    .admin-page-wrapper {
      margin: 14px 12px 18px;
    }

    .admin-page-card-flush {
      margin: 0;
      width: 100%;
    }
  }

  .panel-title {
    font-size: 21px;
    font-weight: 700;
    color: var(--wc-text);
  }

  /* Buttons Modern */
  .btn-primary-pill {
    background: linear-gradient(90deg, var(--wc-accent), var(--wc-accent-2));
    color: #fff !important;
    border: none;
    padding: 8px 16px;
    font-weight: 600;
    border-radius: 999px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .btn-primary-pill:hover {
    filter: brightness(.96);
  }

  .btn-ghost-secondary {
    background: #fff;
    border-radius: 999px;
    border: 1px solid #e5e7eb;
    color: #374151 !important;
    padding: 7px 14px;
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .87rem;
  }

  .btn-ghost-secondary:hover {
    background: #f9fafb;
  }

  .wc-field-label {
    font-weight: 700;
    color: var(--wc-text);
    margin-bottom: 4px;
  }

  .wc-control {
    border-radius: 8px;
    border: 1px solid var(--wc-border);
    padding: 8px 10px;
    transition: border-color .15s, box-shadow .15s;
  }

  .wc-control:focus {
    border-color: var(--wc-accent);
    box-shadow: 0 0 0 3px rgba(15, 157, 128, 0.14);
  }

  .card-body-soft {
    background: #f7fafc;
  }

  /* Preview */
  #bannerImagePreviewEdit {
    border: 1px dashed var(--wc-border);
    padding: 12px;
    text-align: center;
    border-radius: 10px;
    background: #fff;
  }

  #bannerImagePreviewEdit img {
    max-width: 340px;
    border-radius: 8px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, .08);
  }

  /* === STATUS DROPDOWN (same pattern as Create Banner / Lab Test) === */

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
    box-shadow: 0 0 0 3px rgba(15, 157, 128, 0.18);
  }

  .wc-select-shell i {
    color: var(--wc-accent);
    font-size: 0.85rem;
    flex-shrink: 0;
  }

  /* Native select (hidden visually, used by form) */
  .status-select {
    position: absolute;
    inset: 0;
    opacity: 0;
    pointer-events: none;
  }

  /* Displayed selected text */
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

  /* Arrow icon */
  .wc-select-shell::after {
    content: '\f078';
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 0.75rem;
    color: #6b7280;
    pointer-events: none;
    transition: transform .18s ease, color .18s ease;
  }

  .wc-select-shell.open::after {
    transform: translateY(-50%) rotate(180deg);
    color: var(--wc-accent);
  }

  /* Dropdown menu */
  .wc-select-menu {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 6px);
    background: #ffffff;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    box-shadow:
      0 18px 45px rgba(15, 23, 42, 0.12),
      0 0 0 1px rgba(148, 163, 184, 0.18);
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
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.25);
  }

  .wc-select-option .kbd {
    font-size: 0.7rem;
    border-radius: 6px;
    padding: 2px 6px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #6b7280;
  }

  @media (max-width: 576px) {
    .wc-select-menu {
      font-size: 0.9rem;
    }
  }
</style>

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center bg-white flex-wrap gap-2 border-bottom">
      <div>
        <h5 class="panel-title mb-0">Edit Banner</h5>
        <small class="text-muted">Modify banner details</small>
      </div>

      <a href="{{ route('admin.banners.index') }}" class="btn-ghost-secondary">
        <i class="fa-solid fa-arrow-left"></i> Back to list
      </a>
    </div>

    {{-- Body --}}
    <div class="card-body card-body-soft">

      @if($errors->any())
      <div class="alert alert-danger mb-3">
        <ul class="mb-0">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
      @endif

      @php
      // Old value or current banner status ("1" / "0")
      $oldStatus = old('is_active', (string) $banner->is_active);
      @endphp

      <form action="{{ route('admin.banners.update',$banner) }}"
        method="POST" enctype="multipart/form-data"
        id="bannerEditForm" novalidate>
        @csrf @method('PUT')

        {{-- Image Upload --}}
        <div class="mb-3">
    <label for="url" class="form-label">Banner URL</label>
    <input type="url"
           name="url"
           id="url"
           class="form-control"
           placeholder="https://example.com/page"
           value="{{ old('url', $banner->url ?? '') }}">
</div>
        <div class="mb-3">
          <label class="wc-field-label">Banner Image <span class="text-danger">*</span></label>
          <input type="file" name="image"
            class="form-control wc-control @error('image') is-invalid @enderror"
            id="bannerImageInputEdit"
            accept="image/*">
          @error('image')
          <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror

          <div id="bannerImagePreviewEdit" class="mt-2">
            @if($banner->image)
            <img src="{{ asset('storage/'.$banner->image) }}" alt="banner">
            @endif
          </div>
        </div>
         
        {{-- Status (custom dropdown) --}}
        <div class="col-md-5 mb-3">
          <label class="wc-field-label">Status <span class="text-danger">*</span></label>

          <div class="wc-select-shell" id="isActiveDropdown">
            <i class="fa-solid fa-circle-dot"></i>

            {{-- Displayed text --}}
            <div class="wc-select-display" id="isActiveDisplay">
              @if ($oldStatus === '1')
              Active
              @elseif ($oldStatus === '0')
              Inactive
              @else
              <span class="wc-select-placeholder">-- Select Status --</span>
              @endif
            </div>

            {{-- Native select (hidden visually, used by form) --}}
            <select name="is_active"
              id="isActiveSelect"
              class="status-select @error('is_active') is-invalid @enderror"
              required>
              <option value="" disabled {{ $oldStatus === null || $oldStatus === '' ? 'selected' : '' }}>
                -- Select Status --
              </option>
              <option value="1" {{ $oldStatus === '1' ? 'selected' : '' }}>
                Active
              </option>
              <option value="0" {{ $oldStatus === '0' ? 'selected' : '' }}>
                Inactive
              </option>
            </select>

            {{-- Custom menu --}}
            <div class="wc-select-menu" id="isActiveMenu">
              <button type="button"
                class="wc-select-option"
                data-value="1">
                <span class="label">
                  <span class="badge-dot"></span>
                  Active
                </span>
                <span class="kbd">A</span>
              </button>
              <button type="button"
                class="wc-select-option"
                data-value="0">
                <span class="label">
                  <span class="badge-dot"></span>
                  Inactive
                </span>
                <span class="kbd">I</span>
              </button>
            </div>
          </div>

          @error('is_active')
          <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2 mt-3">
          <button type="submit"
            id="bannerEditSubmit"
            class="btn-primary-pill">
            <i class="fa-solid fa-check"></i> Update Banner
          </button>

          <a href="{{ route('admin.banners.index') }}" class="btn-ghost-secondary">
            Cancel
          </a>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('bannerImageInputEdit');
    const preview = document.getElementById('bannerImagePreviewEdit');
    if (input) {
      input.addEventListener('change', () => {
        preview.innerHTML = '';
        const f = input.files[0];
        if (!f) return;
        const img = document.createElement('img');
        img.src = URL.createObjectURL(f);
        preview.appendChild(img);
      });
    }

    const form = document.getElementById('bannerEditForm');
    const btn = document.getElementById('bannerEditSubmit');

    if (form && btn) {
      form.addEventListener('submit', e => {
        if (btn.disabled) {
          e.preventDefault();
          return;
        }
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Updating...';
        setTimeout(() => {
          btn.disabled = false;
          btn.innerHTML = '<i class="fa-solid fa-check"></i> Update Banner';
        }, 3000);
      });
    }

    // ===== Custom status dropdown (same as Create Banner) =====
    const statusShell = document.getElementById('isActiveDropdown');
    const statusSelect = document.getElementById('isActiveSelect');
    const statusDisplay = document.getElementById('isActiveDisplay');
    const statusMenu = document.getElementById('isActiveMenu');
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

    function getCurrentValue() {
      if (!statusSelect) return '';
      return statusSelect.value || '';
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

    // Initialize based on current / old value
    const initialVal = getCurrentValue();
    if (initialVal !== '') {
      setStatusValue(initialVal);
    } else {
      statusOptions.forEach(btn => btn.classList.remove('active'));
    }

    if (statusShell && statusMenu) {
      statusShell.addEventListener('click', function(e) {
        if (e.target.closest('.wc-select-menu')) return;
        toggleMenu();
      });

      statusMenu.addEventListener('click', function(e) {
        e.stopPropagation();
      });

      statusOptions.forEach(btn => {
        btn.addEventListener('click', function() {
          const val = this.dataset.value;
          setStatusValue(val);
          closeMenu();
        });
      });

      document.addEventListener('click', function(e) {
        if (!statusShell.contains(e.target)) {
          closeMenu();
        }
      });

      // Keyboard shortcuts A / I + Enter/Space, Esc
      statusShell.addEventListener('keydown', function(e) {
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
      statusSelect.addEventListener('change', function() {
        if (this.value !== '') {
          setStatusValue(this.value);
        }
      });
    }

  });
</script>
@endpush