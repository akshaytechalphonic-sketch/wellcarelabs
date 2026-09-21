@extends('layouts.app')

@section('title', 'Dashboard - Create Blog')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/blogs/Create-blogs.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card admin-page-card-flush">

            <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Create Blog</h5>
                    <small class="text-muted">Add a new blog article</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.blogs.index') }}" class="btn-ghost-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to list</span>
                    </a>
                </div>
            </div>

            <div class="card-body card-body-soft">
                <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data"
                    id="blogCreateForm" class="blog-create-form" novalidate>
                    @csrf

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Title <span class="req">*</span></label>
                        <input name="title" value="{{ old('title') }}"
                            class="form-control wc-control @error('title') is-invalid @enderror"
                            placeholder="Enter blog title" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Short Description</label>
                        <textarea name="short_description" class="form-control wc-control" placeholder="Enter a brief description">{{ old('short_description') }}</textarea>
                    </div>

                    {{-- Content --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Content <span class="req">*</span></label>
                        <textarea name="content" id="blogContent" class="form-control wc-control @error('content') is-invalid @enderror"
                            required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Featured Image --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Featured Image <span class="req">*</span></label>
                        <input type="file" name="featured_image" id="featuredImageInput" class="form-control wc-control"
                            required accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="featuredImagePreview" class="mt-2 image-preview">
                            <img src="" style="height:90px;border-radius:8px;border:1px solid #e5e7eb">
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Category</label>
                        <select name="category_id" class="form-control wc-control @error('category_id') is-invalid @enderror" style="border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 16px; font-size: 0.95rem;">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                                @if (old('status') === 'Draft')
                                    Draft
                                @elseif (old('status') === 'Published')
                                    Published
                                @else
                                    <span class="wc-select-placeholder">-- Select Status --</span>
                                @endif
                            </div>

                            <select name="status" id="statusSelect"
                                class="status-select @error('status') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('status') ? 'selected' : '' }}>-- Select Status --
                                </option>
                                <option value="Draft" {{ old('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Published" {{ old('status') === 'Published' ? 'selected' : '' }}>Published
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
                        <button type="submit" id="blogCreateBtn" class="btn-primary-pill">
                            <i class="fa-solid fa-check"></i>
                            <span>Create</span>
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn-ghost-secondary">
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#blogContent'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'highlight', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'undo', 'redo'
                ],
                link: {
                    addTargetToExternalLinks: true
                }
            })
    </script>

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/blogs/Create-blogs.js') }}"></script>

@endsection
