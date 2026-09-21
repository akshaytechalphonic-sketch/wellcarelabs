{{-- resources/views/admin/hospitals/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Hospitals (QR Enroll)')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/hospitals/hospitals.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card shadow-sm admin-page-card-flush">

            {{-- Header --}}
            <div class="hosp-header">
                <div class="left">
                    <h5 class="panel-title mb-0">Hospitals (QR Enroll)</h5>
                    <small class="panel-sub">Manage hospitals and generate QR codes</small>
                </div>

                <div class="right">
                    <div class="header-search-wrap">
                        {{-- Chip search --}}
                        <form id="hospitalSearchFormHeader" method="GET" action="{{ route('admin.hospitals.index') }}"
                            role="search" aria-label="Search hospitals">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="chip-search">
                                    <input type="search" name="q" value="{{ request('q') }}"
                                        placeholder="Search hospitals by name, ID...">
                                    <button type="submit">
                                        <i class="fa fa-search"></i>
                                        <span>Search</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- Add hospital button --}}
                        <a href="{{ route('admin.hospitals.create') }}" class="btn btn-sm btn-create-primary">
                            <i class="fa fa-plus"></i>
                            <span>Add Hospital</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Hospital Cards --}}
            <div class="hospital-list">
                @forelse($hospitals as $h)
                    @php
                        $qrLink = url('/?ref=' . $h->unique_id);
                        $status = $h->status ?? 'active';
                    @endphp

                    <div class="hospital-row" data-hospital-id="{{ $h->id }}">
                        {{-- QR (clickable for preview) --}}
                        <div class="row-qr qr-preview-trigger" id="qr-{{ $h->id }}"
                            aria-label="QR for {{ $h->name }}" role="button" tabindex="0"
                            data-hospital-name="{{ e($h->name) }}">
                            @if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class) || class_exists('QrCode'))
                                {!! QrCode::size(150)->generate($qrLink) !!}
                            @else
                                <span class="text-muted">QR</span>
                            @endif
                        </div>

                        {{-- MAIN CONTENT --}}
                        <div class="row-main">
                            {{-- Name + ID + STATUS + VERIFIED --}}
                            <div class="row-title">
                                {{ $h->name }}
                                <span class="id-badge">Hospital ID-{{ $h->id }}</span>

                                {{-- Status badge --}}
                                <span
                                    class="badge-pill {{ $status === 'active' ? 'badge-status-active' : 'badge-status-suspended' }}">
                                    <i class="fa {{ $status === 'active' ? 'fa-circle' : 'fa-ban' }}"></i>
                                    {{ ucfirst($status) }}
                                </span>

                                {{-- Verification badge (only if field exists) --}}
                                @if (!is_null($h->is_verified ?? null))
                                    @if ($h->is_verified)
                                        <span class="badge-pill badge-verified">
                                            <i class="fa fa-shield-halved"></i>
                                            Verified
                                        </span>
                                    @else
                                        <span class="badge-pill badge-pending">
                                            <i class="fa fa-clock"></i>
                                            Pending verification
                                        </span>
                                    @endif
                                @endif
                            </div>

                            {{-- UNIQUE ID --}}
                            <div class="row-sub">
                                Unique ID:
                                <strong>{{ $h->unique_id }}</strong>
                            </div>

                            {{-- MANAGER INFO (optional – remove if not needed) --}}
                            <div class="row-sub">
                                @if (method_exists($h, 'manager') || isset($h->manager))
                                    @if ($h->manager)
                                        Manager:
                                        <strong>{{ $h->manager->name }}</strong>
                                        @if (!empty($h->manager->email))
                                            <span class="text-muted ms-1">{{ $h->manager->email }}</span>
                                        @endif
                                    @else
                                        <span class="text-danger">
                                            <i class="fa fa-user-xmark me-1"></i>No manager linked
                                        </span>
                                    @endif
                                @endif
                            </div>

                            {{-- LOCATION + SERVICE FLAGS --}}
                            <div class="row-meta">
                                @if (!empty($h->city))
                                    <span class="badge-pill badge-info">
                                        <i class="fa fa-location-dot"></i>{{ $h->city }}
                                    </span>
                                @endif

                                @if (!is_null($h->home_collection ?? null) && $h->home_collection)
                                    <span class="badge-pill badge-info">
                                        <i class="fa fa-truck-medical"></i>Home collection
                                    </span>
                                @endif

                                @if (!is_null($h->accepting_appointments ?? null))
                                    @if ($h->accepting_appointments)
                                        <span class="badge-pill badge-status-active">
                                            <i class="fa fa-calendar-check"></i>Accepting appointments
                                        </span>
                                    @else
                                        <span class="badge-pill badge-bad">
                                            <i class="fa fa-pause-circle"></i>Paused
                                        </span>
                                    @endif
                                @endif
                            </div>

                            {{-- MINI STATS (optional – depends on query) --}}
                            @if (isset($h->appointments_last_30) || isset($h->appointments_count))
                                <div class="row-sub row-sub-stats">
                                    @if (isset($h->appointments_last_30))
                                        Last 30 days:
                                        <strong>{{ $h->appointments_last_30 }}</strong>
                                    @endif

                                    @if (isset($h->appointments_last_30) && isset($h->appointments_count))
                                        <span class="mx-1">•</span>
                                    @endif

                                    @if (isset($h->appointments_count))
                                        Total:
                                        <strong>{{ $h->appointments_count }}</strong>
                                    @endif
                                </div>
                            @endif

                            {{-- LINK + COPY --}}
                            <div class="row-link">
                                <a href="{{ $qrLink }}" target="_blank" class="link-text">Open link</a>
                                <div class="link-url">{{ $qrLink }}</div>
                                <button class="btn-icon btn-icon-secondary copy-link-btn" type="button" title="Copy link"
                                    data-link="{{ $qrLink }}">
                                    <i data-feather="copy"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="row-actions">
                            {{-- View button --}}
                            <button type="button" class="btn-icon btn-icon-info view-hospital-btn"
                                data-hospital-id="{{ $h->id }}"
                                data-url="{{ route('admin.hospitals.show', $h->id) }}" title="View Hospital">
                                <i class="fa fa-eye"></i>
                            </button>

                            {{-- Download PDF (client-side) --}}
                            <button class="btn-icon btn-icon-primary download-qr-btn" type="button" title="Download QR"
                                data-hospital-id="{{ $h->id }}" data-hospital-name="{{ e($h->name) }}">
                                <i data-feather="download"></i>
                            </button>

                            {{-- Edit button --}}
                            <a href="{{ route('admin.hospitals.edit', $h->id) }}" class="btn-icon btn-icon-primary"
                                data-ajax="true" data-url="{{ route('admin.hospitals.edit', $h->id) }}"
                                title="Edit Hospital">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                            </a>

                            {{-- Delete form --}}
                            <form action="{{ route('admin.hospitals.destroy', $h->id) }}" method="POST"
                                class="d-inline delete-form" data-confirm="Are you sure you want to delete this hospital?"
                                style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger delete-btn"
                                    data-hospital-name="{{ e($h->name) }}" title="Delete Hospital">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <h6>No hospitals yet</h6>
                        <p>Click <strong>Add Hospital</strong> to create one.</p>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="d-flex justify-content-end pt-3">
                    @if (method_exists($hospitals, 'links'))
                        {{ $hospitals->withQueryString()->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Hospital View Modal – with reset-link button --}}
    <div class="modal fade" id="hospitalViewModal" tabindex="-1" aria-labelledby="hospitalViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hospitalViewModalLabel">Hospital details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="hospitalViewLoading" class="text-center py-4">
                        <div class="spinner-border" role="status" aria-hidden="true"></div>
                        <div class="small text-muted mt-2">Loading hospital details…</div>
                    </div>

                    <div id="hospitalViewContent" style="display:none;">
                        <div class="hospital-view">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h4 id="hospitalViewName" class="mb-1"></h4>
                                    <div class="small text-muted mb-3" id="hospitalViewIdUnique"></div>

                                    {{-- Contact section --}}
                                    <div class="hv-section mb-3">
                                        <div class="hv-section-title">Contact</div>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="hv-field">
                                                    <div class="hv-label">Phone</div>
                                                    <div class="hv-value" id="hospitalViewPhone"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="hv-field">
                                                    <div class="hv-label">Email</div>
                                                    <div class="hv-value" id="hospitalViewEmail"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Owner / Doctor --}}
                                    <div class="hv-section mb-3">
                                        <div class="hv-section-title">Owner & Doctor</div>
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="hv-field">
                                                    <div class="hv-label">Owner</div>
                                                    <div class="hv-value" id="hospitalViewOwnerName"></div>
                                                </div>
                                                <div class="hv-field mt-2">
                                                    <div class="hv-label">Owner Mobile</div>
                                                    <div class="hv-value" id="hospitalViewOwnerMobile"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="hv-field">
                                                    <div class="hv-label">Doctor</div>
                                                    <div class="hv-value" id="hospitalViewDoctorName"></div>
                                                </div>
                                                <div class="hv-field mt-2">
                                                    <div class="hv-label">Doctor Mobile</div>
                                                    <div class="hv-value" id="hospitalViewDoctorMobile"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Address --}}
                                    <div class="hv-section mb-3">
                                        <div class="hv-section-title">Address</div>
                                        <div class="hv-value" id="hospitalViewAddress"></div>
                                    </div>

                                    {{-- Website / QR link text only --}}
                                    <div class="hv-section mb-2">
                                        <div class="hv-section-title">Website / QR link</div>
                                        <div class="hv-value" id="hospitalViewLink"></div>
                                    </div>

                                    <div class="small text-muted mt-2" id="hospitalViewTimestamps"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal footer: send reset link --}}
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Send a password reset email to this hospital's login user.
                    </small>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="sendResetLinkBtn">
                        <i class="fa-regular fa-envelope me-1"></i>
                        Send password reset email
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- QR Preview Modal --}}
    <div class="modal fade" id="qrPreviewModal" tabindex="-1" aria-labelledby="qrPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrPreviewModalLabel">Hospital QR</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrPreviewContainer" class="mb-2"></div>
                    <div class="small text-muted">
                        Scan this QR to open the hospital booking link.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form used by the modal reset-link button --}}
    <form id="adminHospitalResetForm" method="POST" style="display:none;">
        @csrf
    </form>

    <!-- Download spinner overlay -->
    <div id="pdfDownloadOverlay" class="pdf-download-overlay" aria-hidden="true" aria-live="polite">
        <div class="pdf-download-spinner" role="status" aria-label="Generating PDF">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span style="font-size:0.95rem; color:#374151; margin-left:6px;">Generating PDF…</span>
        </div>
    </div>

    {{-- External JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('Back_end/js/hospitals/hospitals.js') }}"></script>
@endsection
