@extends('layouts.app')

@section('title', 'Dashboard - Blog Categories')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="{{ asset('Back_end/css/blogs/blogs.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('danger') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm admin-page-card-flush">
        {{-- Header --}}
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
            <div>
                <h5 class="panel-title mb-0">Blog Categories</h5>
                <small class="text-muted">Manage categories for your blog posts</small>
            </div>

            {{-- Create Category Button --}}
            <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                <a href="{{ route('admin.blog-categories.create') }}"
                    class="create-test-btn"
                    aria-label="Create new category">
                    <i class="fa-solid fa-plus"></i>
                    <span>Create Category</span>
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body p-3">
            {{-- Toolbar --}}
            <div class="d-flex justify-content-between align-items-center mb-3" style="border-bottom:1px solid #eef2f6;padding-bottom:12px;">
                <div class="text-muted small">
                    Showing results for: <strong>{{ request('q') ?: 'All Categories' }}</strong>
                </div>

                <form method="GET" action="{{ route('admin.blog-categories.index') }}" class="d-flex align-items-center gap-2">
                    <div class="chip-search" style="display:flex; align-items:center;">
                        <input name="q" type="search" placeholder="Search categories..." value="{{ request('q') }}" style="border: 1px solid #cbd5e1; border-radius: 6px; padding: 6px 12px; font-size: 0.9rem;">
                        <button type="submit" class="btn btn-primary btn-sm ms-1" style="border-radius: 6px; padding: 6px 12px;">
                            <i class="fa fa-search"></i> Search
                        </button>
                    </div>
                    @if (request('q'))
                        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 6px; padding: 6px 12px;">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="border-collapse: separate; border-spacing: 0;">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Sr.No.</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Post Count</th>
                            <th width="150" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $i => $cat)
                        <tr>
                            <td>{{ $i + 1 + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                            <td class="fw-semibold">{{ $cat->name }}</td>
                            <td><code>{{ $cat->slug }}</code></td>
                            <td>
                                <span class="badge bg-light text-dark border" style="font-size:0.9rem; padding: 5px 10px;">
                                    {{ $cat->blogs()->count() }} Posts
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.blog-categories.edit', $cat->id) }}" class="btn btn-outline-primary btn-sm" style="border-radius:6px; width: 32px; height: 32px; padding:0; display:flex; align-items:center; justify-content:center;">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.blog-categories.destroy', $cat->id) }}"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this category? (Associated blog posts will have category set to null)');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" style="border-radius:6px; width: 32px; height: 32px; padding:0; display:flex; align-items:center; justify-content:center;">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No blog categories found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {!! $categories->appends(request()->only('q'))->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
