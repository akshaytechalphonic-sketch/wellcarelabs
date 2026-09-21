@extends('layouts.app')

@section('title', 'Dashboard - Dynamic Pages')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- Reuse the Blogs listing CSS since it contains the dashboard classes --}}
<link href="{{ asset('Back_end/css/blogs/blogs.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
    <div class="card shadow-sm admin-page-card-flush">

        {{-- Header --}}
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
            <div>
                <h5 class="panel-title mb-0">Dynamic Pages</h5>
                <small class="text-muted">Manage dynamic custom pages</small>
            </div>

            {{-- Create Page Button --}}
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <a href="{{ route('admin.pages.create') }}"
                    class="create-test-btn"
                    aria-label="Create new page">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Page</span>
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body p-2 pt-1" style="margin-top:-6px;">
            <div id="pagesListWrapper">
                @include('admin.pages.partials.list', ['pages' => $pages])
            </div>
        </div>

    </div>
</div>

{{-- Page View Modal --}}
<div class="modal fade" id="pageViewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Page Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="pageViewLoading" class="text-center py-4">
                    <div class="spinner-border"></div>
                    <div class="small text-muted mt-2">Loading page details…</div>
                </div>

                <div id="pageViewContent" style="display:none;">
                    <div class="mb-3">
                        <h4 id="pageViewTitle" class="mb-1 text-primary fw-bold"></h4>
                        <div class="d-flex gap-2 align-items-center mt-2">
                            <span id="pageViewStatusBadge" class="badge"></span>
                            <span class="text-muted small" id="pageViewSlug"></span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small d-block">SEO TITLE</label>
                            <span id="pageViewMetaTitle" class="text-dark">—</span>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small d-block">SEO DESCRIPTION</label>
                            <span id="pageViewMetaDesc" class="text-dark">—</span>
                        </div>
                    </div>
                    
                    <hr>

                    {{-- CONTENT --}}
                    <div>
                        <label class="fw-bold text-muted small">PAGE CONTENT</label>
                        <div id="pageViewBodyHtml" class="mt-2 p-3 bg-light rounded" style="max-height: 350px; overflow-y: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const listWrapper = document.getElementById('pagesListWrapper');
    const viewModal = new bootstrap.Modal(document.getElementById('pageViewModal'));
    
    // Live preview loader
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.view-page-btn');
        if (!btn) return;
        
        const url = btn.getAttribute('data-url');
        
        // Show loading state
        document.getElementById('pageViewLoading').style.display = 'block';
        document.getElementById('pageViewContent').style.display = 'none';
        viewModal.show();
        
        try {
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            
            document.getElementById('pageViewTitle').textContent = data.title;
            document.getElementById('pageViewSlug').textContent = '/p/' + data.slug;
            document.getElementById('pageViewMetaTitle').textContent = data.meta_title || '—';
            document.getElementById('pageViewMetaDesc').textContent = data.meta_description || '—';
            document.getElementById('pageViewBodyHtml').innerHTML = data.content || '<span class="text-muted italic">Empty page</span>';
            
            const badge = document.getElementById('pageViewStatusBadge');
            badge.textContent = data.status;
            badge.className = data.status === 'Published' ? 'badge bg-success' : 'badge bg-secondary';
            
            document.getElementById('pageViewLoading').style.display = 'none';
            document.getElementById('pageViewContent').style.display = 'block';
        } catch(err) {
            console.error('Failed to load page details', err);
            document.getElementById('pageViewLoading').innerHTML = '<div class="text-danger">Failed to load page.</div>';
        }
    });
});
</script>
@endsection
