@extends('layouts.app')

@section('title', 'Dashboard - Edit Hospital')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/hospitals/hospital-edit.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card shadow-sm admin-page-card-flush">
            {{-- Header --}}
            <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Edit Hospital</h5>
                    <small class="text-muted">Update hospital details & QR link</small>
                </div>
                <a href="{{ route('admin.hospitals.index') }}" class="btn-ghost-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to list</span>
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body card-body-soft">
                <div class="row g-3">
                    {{-- Left: form --}}
                    <div class="col-lg-7 col-md-8">
                        <div class="p-3 p-md-0">
                            <form id="hospitalEditForm" action="{{ route('admin.hospitals.update', $hospital->id) }}"
                                method="POST" class="hospital-edit-form" novalidate>
                                @csrf
                                @method('PUT')

                                {{-- Name --}}
                                <div class="mb-3">
                                    <label for="name" class="wc-field-label">
                                        Name <span class="required-star">*</span>
                                    </label>
                                    <input type="text" id="name" name="name"
                                        class="form-control wc-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $hospital->name) }}" required>
                                    @error('name')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Unique ID (readonly) --}}
                                <div class="mb-3">
                                    <label for="unique_id" class="wc-field-label">
                                        Unique ID <span class="required-star">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="unique_id" name="unique_id"
                                            class="form-control wc-control readonly-input @error('unique_id') is-invalid @enderror"
                                            value="{{ old('unique_id', $hospital->unique_id) }}" maxlength="64"
                                            aria-describedby="uniqueHelp" readonly required>
                                    </div>
                                    @error('unique_id')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                    <div class="wc-help-text mt-1">
                                        Unique ID cannot be changed after creation. This ID is used in the QR link.
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="mb-3">
                                    <label for="phone" class="wc-field-label">
                                        Phone <span class="required-star">*</span>
                                    </label>
                                    <input type="text" id="phone" name="phone"
                                        class="form-control wc-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $hospital->phone) }}" required>
                                    @error('phone')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Owner / Manager Name --}}
                                <div class="mb-3">
                                    <label for="owner_name" class="wc-field-label">
                                        Owner / Manager Name <span class="required-star">*</span>
                                    </label>
                                    <input type="text" id="owner_name" name="owner_name"
                                        class="form-control wc-control @error('owner_name') is-invalid @enderror"
                                        value="{{ old('owner_name', $hospital->owner_name) }}" required>
                                    @error('owner_name')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Owner Mobile --}}
                                <div class="mb-3">
                                    <label for="owner_mobile" class="wc-field-label">
                                        Owner Mobile No <span class="required-star">*</span>
                                    </label>
                                    <input type="text" id="owner_mobile" name="owner_mobile"
                                        class="form-control wc-control @error('owner_mobile') is-invalid @enderror"
                                        value="{{ old('owner_mobile', $hospital->owner_mobile) }}" required>
                                    @error('owner_mobile')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Doctor Name --}}
                                <div class="mb-3">
                                    <label for="doctor_name" class="wc-field-label">
                                        Dr. Name <span class="required-star">*</span>
                                    </label>
                                    <input type="text" id="doctor_name" name="doctor_name"
                                        class="form-control wc-control @error('doctor_name') is-invalid @enderror"
                                        value="{{ old('doctor_name', $hospital->doctor_name) }}" required>
                                    @error('doctor_name')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Doctor Mobile --}}
                                <div class="mb-3">
                                    <label for="doctor_mobile" class="wc-field-label">
                                        Dr. Mobile No <span class="required-star">*</span>
                                    </label>
                                    <input type="text" id="doctor_mobile" name="doctor_mobile"
                                        class="form-control wc-control @error('doctor_mobile') is-invalid @enderror"
                                        value="{{ old('doctor_mobile', $hospital->doctor_mobile) }}" required>
                                    @error('doctor_mobile')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="wc-field-label">
                                        Email <span class="required-star">*</span>
                                    </label>

                                    <input type="email" id="email" name="email"
                                        class="form-control wc-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $hospital->email) }}"
                                        {{ isset($hospital->id) ? 'disabled' : '' }} required>

                                    @error('email')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                    <div class="wc-help-text mt-1">
                                        Email cannot be changed after creation. as we use it for hospital manager credentials.
                                    </div>
                                </div>


                                {{-- Address --}}
                                <div class="mb-3">
                                    <label for="address" class="wc-field-label">
                                        Address <span class="required-star">*</span>
                                    </label>
                                    <textarea id="address" name="address" rows="3"
                                        class="form-control wc-control @error('address') is-invalid @enderror" required>{{ old('address', $hospital->address) }}</textarea>
                                    @error('address')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Actions --}}
                                <div class="d-flex gap-2 align-items-center mt-3">
                                    <button type="submit" id="submitBtn" class="btn-primary-pill">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Update</span>
                                    </button>

                                    <a href="{{ route('admin.hospitals.index') }}" class="btn-ghost-secondary">
                                        <span>Cancel</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Right: QR & link --}}
                    <div class="col-lg-5 col-md-4">
                        <div class="card qr-side-card h-100">
                            <div class="card-header">
                                <i class="fa fa-qrcode me-2"></i>QR & Link
                            </div>
                            <div class="card-body text-center">
                                @php
                                    $qrLink = url('/h/' . $hospital->unique_id);
                                    $currentUid = old('unique_id', $hospital->unique_id);
                                @endphp

                                @if (class_exists(\QrCode::class) || class_exists('QrCode'))
                                    <div class="mb-3 qr-image-container">
                                        {!! QrCode::size(180)->generate($qrLink) !!}
                                    </div>
                                @else
                                    <div class="text-muted mb-3 qr-placeholder">
                                        <i class="fa fa-qrcode fa-3x mb-2"></i>
                                        <p>QR library not available</p>
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <a id="openLinkAnchor" href="{{ $qrLink }}" target="_blank"
                                        class="qr-link-text">
                                        {{ $qrLink }}
                                    </a>
                                </div>

                                <div class="d-grid gap-2 mt-2">
                                    <button type="button" id="copyLinkBtn" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-copy me-1"></i>Copy link
                                    </button>
                                    <button type="button" id="openInNewBtn" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-external-link me-1"></i>Open in new tab
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- /row --}}
            </div> {{-- /card-body --}}
        </div>
    </div>

    {{-- Hidden flash payload for JS (SweetAlert reads this) --}}
    <div id="flashPayload" data-success="{{ session('success') ? e(session('success')) : '' }}"
        data-danger="{{ session('danger') ? e(session('danger')) : '' }}"></div>

    {{-- External JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('Back_end/js/hospitals/hospital-edit.js') }}"></script>
@endsection
