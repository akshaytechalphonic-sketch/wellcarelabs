{{-- resources/views/admin/packages/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Packages')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- External CSS --}}
<link href="{{ asset('Back_end/css/packages/packages.css') }}" rel="stylesheet">

<style>
    /* ✅ Modal UI same as Blogs */
    #packageViewModal .modal-content {
        border-radius: 14px;
        overflow: hidden;
    }

    #packageViewModal .modal-header {
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 18px;
        background: #fff;
    }

    #packageViewModal .modal-title {
        font-weight: 800;
        color: #0a2540;
    }

    #packageViewModal .modal-body {
        padding: 18px;
        background: #fbfdff;
    }

    #packageViewTitle {
        font-weight: 800;
        color: #0d6efd;
        line-height: 1.3;
    }

    #packageViewBanner {
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #f3f4f6;
        padding: 6px;
    }

    /* Card blocks inside modal */
    .package-modal-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 14px;
        margin-top: 10px;
    }

    #packageViewBodyHtml {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 1rem;
        line-height: 1.8;
        color: #111827;
        text-align: justify;
        word-break: break-word;
    }

    #packageViewBodyHtml p {
        margin-bottom: 12px;
    }

    .package-tests-wrap {
        margin-top: 14px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 14px;
    }

    .package-tests-list {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .package-tests-list li {
        margin-bottom: 6px;
        color: #111827;
    }
</style>

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">
    {{-- Header --}}
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
      <div>
        <h5 class="panel-title mb-0">Packages</h5>
        <small class="text-muted">Manage lab test packages</small>
      </div>

      {{-- Create Package --}}
      <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
        <a href="{{ route('admin.packages.create') }}"
           class="create-package-btn"
           aria-label="Create new package">
          <i class="fa-solid fa-plus"></i>
          <span>Create Package</span>
        </a>
      </div>
    </div>

    {{-- Body --}}
    <div class="card-body p-2 pt-1" style="margin-top:-6px;">
      <div id="packagesListWrapper">
        @include('admin.packages.partials.list', ['packages' => $packages, 'hasSlug' => $hasSlug ?? false])
      </div>
    </div>
  </div>
</div>

{{-- Package View Modal --}}
<div class="modal fade" id="packageViewModal" tabindex="-1" aria-labelledby="packageViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="packageViewModalLabel">Package details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div id="packageViewLoading" class="text-center py-4">
                    <div class="spinner-border" role="status" aria-hidden="true"></div>
                    <div class="small text-muted mt-2">Loading package details…</div>
                </div>

                <div id="packageViewContent" style="display:none;">

                    <div class="row g-3 mb-3 align-items-start">
                        {{-- LEFT: IMAGE --}}
                        <div class="col-md-5">
                            <img id="packageViewBanner" src="" alt=""
                                style="max-height:200px;object-fit:cover;width:100%;display:none;">
                        </div>

                        {{-- RIGHT: DETAILS --}}
                        <div class="col-md-7">
                            <h4 id="packageViewTitle" class="mb-2"></h4>

                            <div id="packageViewSpecialBadge" class="mb-2"></div>

                            <div class="d-flex gap-3 flex-wrap">
                                <div class="package-modal-card">
                                    <small class="text-muted">MRP</small>
                                    <div id="packageViewMrp" class="fw-bold"></div>
                                </div>

                                <div class="package-modal-card">
                                    <small class="text-muted">Selling Price</small>
                                    <div id="packageViewSellingPrice" class="fw-bold"></div>
                                </div>

                                <div class="package-modal-card">
                                    <small class="text-muted">Status</small>
                                    <div id="packageViewStatus" class="fw-bold"></div>
                                </div>
                            </div>

                            <div class="small text-muted mt-2" id="packageViewTimestamps"></div>
                        </div>
                    </div>

                    <hr class="my-3">

                    {{-- CONTENT --}}
                    <div>
                        <label class="fw-bold text-muted small">CONTENT</label>
                        <div id="packageViewBodyHtml" class="mt-2"></div>
                    </div>

                    {{-- INCLUDED TESTS --}}
                    <div id="packageViewExtras"></div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- External JS --}}
<script src="{{ asset('Back_end/js/packages/packages.js') }}"></script>
@endsection
