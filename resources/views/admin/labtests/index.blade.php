@extends('layouts.app')

@section('title', 'Dashboard - Lab Tests')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- External CSS --}}
<link href="{{ asset('Back_end/css/tests/tests.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">

    {{-- Header --}}
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
      <div>
        <h5 class="panel-title mb-0">Lab Tests</h5>
        <small class="text-muted">Manage lab test records</small>
      </div>

      {{-- Create Test Button --}}
      <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
        <a href="{{ route('admin.labtests.create') }}"
           class="create-test-btn"
           aria-label="Create new test">
          <i class="fa-solid fa-plus"></i>
          <span>Create Test</span>
        </a>
      </div>
    </div>

    {{-- Body --}}
    <div class="card-body p-2 pt-1" style="margin-top:-6px;">
      {{-- Table partial (list + pagination inside) --}}
      <div id="testsListWrapper">
        @include('admin.labtests.partials.list', ['tests' => $tests])
      </div>
    </div>
  </div>
</div>

{{-- Lab Test View Modal --}}
<div class="modal fade" id="labtestViewModal" tabindex="-1" aria-labelledby="labtestViewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="labtestViewModalLabel">Test details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="labtestViewLoading" class="text-center py-4">
          <div class="spinner-border" role="status" aria-hidden="true"></div>
          <div class="small text-muted mt-2">Loading test details…</div>
        </div>

        <div id="labtestViewContent" style="display:none;">
          <div class="mb-2">
            <h4 id="labtestViewTitle" class="mb-1"></h4>
            <div id="labtestViewStatusBadge" class="mb-2"></div>
            <div class="small text-muted" id="labtestViewTimestamps"></div>
          </div>

          <hr>

          <div id="labtestViewBodyHtml" class="mb-3"></div>

          <div class="row">
            <div class="col-md-4">
              <small class="text-muted">MRP</small>
              <div id="labtestViewMrp" class="fw-bold"></div>
            </div>
            <div class="col-md-4">
              <small class="text-muted">B2B</small>
              <div id="labtestViewB2b" class="fw-bold"></div>
            </div>
            <div class="col-md-4">
              <small class="text-muted">Discounted</small>
              <div id="labtestViewDiscounted" class="fw-bold"></div>
            </div>
          </div>

          <div id="labtestViewExtras" class="mt-3"></div>
        </div>
        
        {{-- Error display area --}}
        <div id="labtestViewError" style="display:none;" class="alert alert-danger">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <span id="labtestViewErrorText">Failed to load test details. Please try again.</span>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Load Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- External JS --}}
<script src="{{ asset('Back_end/js/tests/tests.js') }}"></script>
@endsection