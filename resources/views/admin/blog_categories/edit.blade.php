@extends('layouts.app')

@section('title', 'Dashboard - Edit Blog Category')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('Back_end/css/blogs/Edit-blogs.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card admin-page-card-flush">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Edit Blog Category</h5>
                    <small class="text-muted">Update blog category details</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blog-categories.index') }}" class="btn-ghost-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to list</span>
                    </a>
                </div>
            </div>

            <div class="card-body card-body-soft p-4">
                <form action="{{ route('admin.blog-categories.update', $blogCategory) }}" method="POST"
                    id="categoryEditForm" class="blog-edit-form" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Category Name --}}
                    <div class="mb-4">
                        <label class="wc-field-label">Category Name <span class="req">*</span></label>
                        <input name="name" value="{{ old('name', $blogCategory->name) }}"
                            class="form-control wc-control @error('name') is-invalid @enderror"
                            placeholder="Enter category name" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px; padding: 10px 20px;">
                            <i class="fa-solid fa-check me-1"></i>
                            <span>Update Category</span>
                        </button>
                        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary" style="border-radius: 6px; padding: 10px 20px;">
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
