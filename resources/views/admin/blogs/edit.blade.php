@extends('layouts.app')

@section('title', 'Dashboard - Edit Blog')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/blogs/Edit-blogs.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card admin-page-card-flush">

            {{-- Header --}}
            <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Edit Blog</h5>
                    <small class="text-muted">Update blog article</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blogs.index') }}" class="btn-ghost-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to list</span>
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body card-body-soft">
                <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data"
                    id="blogEditForm" class="blog-edit-form" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Success message --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Title <span class="req">*</span></label>
                        <input name="title" value="{{ old('title', $blog->title) }}"
                            class="form-control wc-control @error('title') is-invalid @enderror"
                            placeholder="Enter blog title" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Short Description</label>
                        <textarea name="short_description" class="form-control wc-control" placeholder="Enter a brief description">{{ old('short_description', $blog->short_description) }}</textarea>
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Content <span class="req">*</span></label>
                        <textarea name="content" id="blogContent" class="form-control wc-control @error('content') is-invalid @enderror"
                            required>{{ old('content', $blog->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Featured Image --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Featured Image</label>
                        <input type="file" name="featured_image" id="featuredImageInput"
                            class="form-control wc-control @error('featured_image') is-invalid @enderror" accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        @if ($blog->featured_image)
                            <div class="image-preview-container current-image-container">
                                <p class="text-muted small mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured Image"
                                    id="currentImage" class="current-image">
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remove_image"
                                            id="removeImageCheckbox" value="1">
                                        <label class="form-check-label" for="removeImageCheckbox">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="newImagePreview" class="mt-2 image-preview-container new-image-preview">
                            <p class="text-muted small mb-2">New Image Preview:</p>
                            <img src="" alt="New Image Preview" class="preview-image">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Category</label>
                        <select name="category_id" class="form-control wc-control @error('category_id') is-invalid @enderror" style="border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 16px; font-size: 0.95rem;">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $blog->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Status <span class="req">*</span></label>

                        <div class="wc-select-shell" id="statusDropdown">
                            <i class="fa-solid fa-circle-dot"></i>

                            <div class="wc-select-display" id="statusDisplay">
                                @php
                                    $oldStatusValue = old('status', $blog->status);
                                @endphp
                                @if ($oldStatusValue === 'Draft')
                                    Draft
                                @elseif ($oldStatusValue === 'Published')
                                    Published
                                @else
                                    <span class="wc-select-placeholder">-- Select Status --</span>
                                @endif
                            </div>

                            <select name="status" id="statusSelect"
                                class="status-select @error('status') is-invalid @enderror" required>
                                <option value="Draft" {{ $oldStatusValue === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Published" {{ $oldStatusValue === 'Published' ? 'selected' : '' }}>Published
                                </option>
                            </select>

                            <div class="wc-select-menu" id="statusMenu">
                                <button type="button" class="wc-select-option" data-value="Draft">
                                    <span class="label"><span class="badge-dot"></span> Draft</span>
                                    <span class="kbd">D</span>
                                </button>
                                <button type="button" class="wc-select-option" data-value="Published">
                                    <span class="label"><span class="badge-dot"></span> Published</span>
                                    <span class="kbd">P</span>
                                </button>
                            </div>
                        </div>

                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" id="blogUpdateBtn" class="btn-primary-pill">
                            <i class="fa-solid fa-check"></i>
                            <span>Update</span>
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn-ghost-secondary">
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#blogContent'), {
                    toolbar: [
                        'heading', '|',
                        'bold', 'italic', 'highlight', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'blockQuote', 'undo', 'redo',
                        'sourceEditing'   // 🔥 ADD THIS

                    ],
                    link: {
                        addTargetToExternalLinks: true
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/blogs/Edit-blogs.js') }}"></script>
@endsection
