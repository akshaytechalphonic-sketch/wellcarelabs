@extends('layouts.app')

@section('title', 'Dashboard - Blogs')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- External CSS --}}
<link href="{{ asset('Back_end/css/blogs/blogs.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
    <div class="card shadow-sm admin-page-card-flush">

        {{-- Header --}}
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
            <div>
                <h5 class="panel-title mb-0">Blogs</h5>
                <small class="text-muted">Manage blog articles</small>
            </div>

            {{-- Create Blog Button --}}
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <a href="{{ route('admin.blogs.create') }}"
                    class="create-test-btn"
                    aria-label="Create new blog">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Blog</span>
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body p-2 pt-1" style="margin-top:-6px;">
            <div id="blogsListWrapper">
                @include('admin.blogs.partials.list', ['blogs' => $blogs])
            </div>
        </div>

    </div>
</div>

{{-- Blog View Modal --}}
<div class="modal fade" id="blogViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Blog details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="blogViewLoading" class="text-center py-4">
                    <div class="spinner-border"></div>
                    <div class="small text-muted mt-2">Loading blog details…</div>
                </div>

                <div id="blogViewContent" style="display:none;">

                    <div class="row g-3 mb-3">
                        {{-- LEFT: IMAGE --}}
                        <div class="col-md-5 text-center">
                            <img id="blogViewImage" class="img-fluid rounded shadow-sm"
                                style="max-height:180px;object-fit:cover;width:100%;display:none;">
                        </div>

                        {{-- RIGHT: TITLE + META --}}
                        <div class="col-md-7">
                            <h4 id="blogViewTitle" class="mb-1"></h4>

                            <div id="blogViewStatusBadge" class="mb-2"></div>

                            <div class="small text-muted mb-2" id="blogViewTimestamps"></div>

                            <div id="blogViewShortDescWrap" style="display:none;">
                                <label class="fw-bold text-muted small">SHORT DESCRIPTION</label>
                                <p id="blogViewShortDesc" class="mb-0"></p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    {{-- FULL CONTENT --}}
                    <div>
                        <label class="fw-bold text-muted small">CONTENT</label>
                        <div id="blogViewBodyHtml" class="mt-2"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- Load Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- External JS --}}
<script src="{{ asset('Back_end/js/blogs/blogs.js') }}"></script>
@endsection