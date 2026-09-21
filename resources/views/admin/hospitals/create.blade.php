@extends('layouts.app')

@section('title', 'Dashboard - Add Hospital (QR Enroll)')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
{{-- External CSS --}}
<link href="{{ asset('Back_end/css/hospitals/hospital-create.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
      <div>
        <h5 class="panel-title mb-0">Register Hospital (QR Enroll)</h5>
        <small class="text-muted">Create a hospital and generate its QR shortlink</small>
      </div>
      <a href="{{ route('admin.hospitals.index') }}" class="btn-ghost-secondary">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Back to List</span>
      </a>
    </div>

    <div class="card-body card-body-soft px-4 py-3">
      <form id="hospitalCreateForm"
            method="POST"
            action="{{ route('admin.hospitals.store') }}"
            enctype="multipart/form-data"
            class="hospital-create-form"
            novalidate>
        @csrf

        <div class="row g-3">
          {{-- Hospital name --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Hospital Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="name"
                   required
                   class="form-control wc-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}"
                   placeholder="Enter hospital name, e.g. City Care Hospital">
            @error('name')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Email --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Email <span class="text-danger">*</span>
            </label>
            <input type="email"
                   name="email"
                   required
                   class="form-control wc-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="hospital@example.com">
            @error('email')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Phone --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Phone <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="phone"
                   required
                   class="form-control wc-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone') }}"
                   placeholder="Enter 10 digit phone number">
            @error('phone')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Owner / Manager Name --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Owner / Manager Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="owner_name"
                   required
                   class="form-control wc-control @error('owner_name') is-invalid @enderror"
                   value="{{ old('owner_name') }}"
                   placeholder="Enter owner / manager name">
            @error('owner_name')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Owner / Manager Mobile --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Owner / Manager Mobile No <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="owner_mobile"
                   required
                   class="form-control wc-control @error('owner_mobile') is-invalid @enderror"
                   value="{{ old('owner_mobile') }}"
                   placeholder="Enter owner / manager mobile no">
            @error('owner_mobile')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Dr. Name --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Dr. Name <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="doctor_name"
                   required
                   class="form-control wc-control @error('doctor_name') is-invalid @enderror"
                   value="{{ old('doctor_name') }}"
                   placeholder="Enter primary doctor name">
            @error('doctor_name')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Dr. Mobile No --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Dr. Mobile No <span class="text-danger">*</span>
            </label>
            <input type="text"
                   name="doctor_mobile"
                   required
                   class="form-control wc-control @error('doctor_mobile') is-invalid @enderror"
                   value="{{ old('doctor_mobile') }}"
                   placeholder="Enter doctor mobile no">
            @error('doctor_mobile')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Unique ID --}}
          <div class="col-md-6 mb-3">
            <label class="wc-field-label">
              Unique ID
              <small>(A–Z, 0–9, - and _ only — leave blank to auto-generate)</small>
            </label>
            <div class="input-group">
              <input id="uniqueId"
                     type="text"
                     name="unique_id"
                     class="form-control wc-control @error('unique_id') is-invalid @enderror"
                     value="{{ old('unique_id') }}"
                     placeholder="Leave blank to auto-generate"
                     maxlength="64">
              <button id="generateIdBtn"
                      type="button"
                      class="btn btn-outline-secondary"
                      title="Generate a random ID">
                Generate
              </button>
            </div>
            @error('unique_id')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- Address --}}
          <div class="col-12 mb-3">
            <label class="wc-field-label">
              Address <span class="text-danger">*</span>
            </label>
            <textarea name="address"
                      required
                      class="form-control wc-control @error('address') is-invalid @enderror"
                      rows="3"
                      placeholder="Street address, area, city, state, pincode">{{ old('address') }}</textarea>
            @error('address')
              <div class="field-error">{{ $message }}</div>
            @enderror
          </div>

          {{-- QR section --}}
          <div class="col-12 mb-3">
            <label class="wc-field-label">
              QR Preview
            </label>
            <div class="qr-preview">
              <div class="qr-box" id="qrBox" aria-hidden="true">
                <img id="qrImg"
                     src=""
                     alt="QR preview"
                     class="qr-image">
                <div id="qrPlaceholder" class="muted">
                  Click "Generate" to create QR image
                </div>
              </div>

              <div class="qr-details">
                <div class="mb-2">
                  <strong>QR Shortlink</strong>
                  <div class="qr-link-row">
                    <input id="qrLinkInput"
                           readonly
                           type="text"
                           class="form-control wc-control"
                           placeholder="Shortlink will appear here when you enter or generate the Unique ID">
                    <button id="copyLinkBtn"
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            title="Copy shortlink">
                      Copy link
                    </button>
                    <a id="openLinkBtn"
                       class="btn btn-sm btn-outline-primary disabled"
                       target="_blank"
                       rel="noopener"
                       aria-disabled="true">
                      Open
                    </a>
                  </div>
                  <div class="wc-help-text mt-1">
                    Use this link in WhatsApp, posters, or QR code to let patients book via this hospital.
                  </div>
                </div>

                <div class="mt-1 shortlink-preview" style="display:none;"></div>
              </div>
            </div>
          </div>

          {{-- Actions --}}
          <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn-primary-pill" id="submitBtn">
              <i class="fa-solid fa-check"></i>
              <span>Create Hospital</span>
            </button>
            <a href="{{ route('admin.hospitals.index') }}" class="btn-ghost-secondary">
              <span>Cancel</span>
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Hidden flash payload for JS (SweetAlert reads this) --}}
<div id="flashPayload"
     data-success="{{ session('success') ? e(session('success')) : '' }}"
     data-danger="{{ session('danger') ? e(session('danger')) : '' }}"></div>

{{-- External JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('Back_end/js/hospitals/hospital-create.js') }}"></script>
@endsection