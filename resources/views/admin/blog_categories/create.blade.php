@extends('layouts.app')

@section('title', 'Dashboard - Create Blog Category')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('Back_end/css/blogs/Create-blogs.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card admin-page-card-flush">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Create Blog Category</h5>
                    <small class="text-muted">Add a new category for blog posts</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blog-categories.index') }}" class="btn-ghost-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to list</span>
                    </a>
                </div>
            </div>

            <div class="card-body card-body-soft p-4">
                <form action="{{ route('admin.blog-categories.store') }}" method="POST"
                    id="categoryCreateForm" class="blog-create-form" novalidate>
                    @csrf

                    {{-- Category Name --}}
                    <div class="mb-4">
                        <label class="wc-field-label">Category Name <span class="req">*</span></label>
                        <input name="name" value="{{ old('name') }}"
                            class="form-control wc-control @error('name') is-invalid @enderror"
                            placeholder="Enter category name (e.g. Wellness, Diagnostic)" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary" style="border-radius: 6px; padding: 10px 20px;">
                            <i class="fa-solid fa-check me-1"></i>
                            <span>Create Category</span>
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
