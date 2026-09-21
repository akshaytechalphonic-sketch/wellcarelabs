@extends('layouts.app')

@section('title', 'Dashboard - Testimonials')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
    .admin-page-wrapper {
        padding: 20px;
    }
    .panel-title {
        font-weight: 700;
        color: #0f172a;
    }
    .create-btn {
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 8px;
        border: 0;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .create-btn:hover {
        background: #1d4ed8;
        color: #fff;
    }
    .avatar-thumbnail {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }
    .star-yellow {
        color: #fbbf24;
    }
    .badge-published {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
    }
    .badge-draft {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.78rem;
    }
    .search-input {
        border-radius: 8px 0 0 8px;
        border: 1.5px solid #cbd5e1;
        padding: 8px 14px;
    }
    .search-btn {
        border-radius: 0 8px 8px 0;
        background: #f1f5f9;
        border: 1.5px solid #cbd5e1;
        border-left: 0;
        color: #475569;
        padding: 8px 16px;
    }
    .filter-select {
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        padding: 8px 14px;
        background-color: #fff;
    }
</style>

<div class="admin-page-wrapper">
    <div class="card shadow-sm border-0">
        {{-- Header --}}
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3 border-bottom bg-white py-3">
            <div>
                <h5 class="panel-title mb-0">Testimonials</h5>
                <small class="text-muted">Manage patient and partner reviews for the homepage</small>
            </div>

            <div>
                <a href="{{ route('admin.testimonials.create') }}" class="create-btn">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Testimonial</span>
                </a>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="card-body bg-light border-bottom p-3">
            <form action="{{ route('admin.testimonials.index') }}" method="GET" class="row g-2 align-items-center">
                {{-- Search query --}}
                <div class="col-md-5">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control search-input" placeholder="Search by name, review text..." value="{{ request('q') }}">
                        <button type="submit" class="search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div class="col-md-3">
                    <select name="status" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">-- All Statuses --</option>
                        <option value="Published" {{ request('status') === 'Published' ? 'selected' : '' }}>Published</option>
                        <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                {{-- Clear Filters --}}
                @if(request()->filled('q') || request()->filled('status'))
                    <div class="col-md-2">
                        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm w-100 py-2">Clear Filters</a>
                    </div>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            @if(session('success'))
                <div class="alert alert-success m-3" role="alert">{{ session('success') }}</div>
            @endif

            @if($testimonials->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">ID</th>
                                <th style="width: 80px;">Avatar</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Video URL</th>
                                <th>Status</th>
                                <th class="text-end pe-3" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testimonials as $t)
                                <tr>
                                    <td class="ps-3 fw-bold text-secondary">{{ $t->id }}</td>
                                    <td>
                                        <img src="{{ $t->avatar_url }}" alt="{{ $t->name }}" class="avatar-thumbnail">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $t->name }}</div>
                                    </td>
                                    <td>
                                        <span class="text-secondary small">{{ $t->designation ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= $t->rating ? 'star-yellow' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted small" title="{{ $t->review }}">
                                            {{ \Illuminate\Support\Str::limit($t->review, 60) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($t->video_url)
                                            <a href="{{ $t->video_url }}" target="_blank" class="btn btn-link btn-sm p-0 text-primary" title="{{ $t->video_url }}">
                                                <i class="fa-solid fa-circle-play me-1"></i>Watch Video
                                            </a>
                                        @else
                                            <span class="text-muted small">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($t->status === 'Published')
                                            <span class="badge-published">Published</span>
                                        @else
                                            <span class="badge-draft">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.testimonials.edit', $t->id) }}" class="btn btn-sm btn-outline-primary" title="Edit Testimonial">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this testimonial?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Testimonial">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-between align-items-center p-3 border-top bg-light">
                    <span class="small text-muted">Showing {{ $testimonials->firstItem() }} to {{ $testimonials->lastItem() }} of {{ $testimonials->total() }} entries</span>
                    <div>
                        {!! $testimonials->links() !!}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="text-muted mb-3"><i class="fa-regular fa-message fa-3x"></i></div>
                    <h5 class="text-secondary">No Testimonials Found</h5>
                    <p class="text-muted small">Click "Add Testimonial" to create your first client review.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
