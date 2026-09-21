@extends('layouts.app')

@section('title', 'Dashboard - Edit Testimonial')

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
    .form-card {
        border-radius: 12px;
        border: 0;
        background: #ffffff;
    }
    .field-label {
        font-size: 0.9rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }
    .form-control-pill {
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }
    .form-control-pill:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
    .btn-submit-pill {
        background: #2563eb;
        color: #ffffff;
        font-weight: 700;
        padding: 10px 24px;
        border-radius: 8px;
        border: 0;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-submit-pill:hover {
        background: #1d4ed8;
    }
</style>

<div class="admin-page-wrapper">
    <div class="card shadow-sm form-card">
        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom py-3">
            <div>
                <h5 class="panel-title mb-0">Edit Testimonial</h5>
                <small class="text-muted">Modify review details for testimonial ID: {{ $testimonial->id }}</small>
            </div>
            <div>
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i>Back to list
                </a>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="card-body p-4">
            <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="name" class="field-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" required class="form-control form-control-pill @error('name') is-invalid @enderror" placeholder="e.g. Ravi Ranjan" value="{{ old('name', $testimonial->name) }}">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Designation --}}
                    <div class="col-md-6 mb-3">
                        <label for="designation" class="field-label">Designation / Role</label>
                        <input type="text" name="designation" id="designation" class="form-control form-control-pill @error('designation') is-invalid @enderror" placeholder="e.g. Patient, Doctor, Business Partner" value="{{ old('designation', $testimonial->designation) }}">
                        @error('designation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Rating --}}
                    <div class="col-md-4 mb-3">
                        <label for="rating" class="field-label">Rating (Stars) <span class="text-danger">*</span></label>
                        <select name="rating" id="rating" required class="form-select form-control-pill @error('rating') is-invalid @enderror">
                            <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star</option>
                        </select>
                        @error('rating')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- YouTube Video URL --}}
                    <div class="col-md-8 mb-3">
                        <label for="video_url" class="field-label">YouTube Video URL (Optional)</label>
                        <input type="url" name="video_url" id="video_url" class="form-control form-control-pill @error('video_url') is-invalid @enderror" placeholder="e.g. https://www.youtube.com/watch?v=VIDEO_ID" value="{{ old('video_url', $testimonial->video_url) }}">
                        <small class="text-muted mt-1 d-block">If provided, the card will display an embedded video player.</small>
                        @error('video_url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Avatar File --}}
                    <div class="col-md-6 mb-3">
                        <label for="image" class="field-label">Replace Avatar / Profile Photo</label>
                        <input type="file" name="image" id="image" accept="image/*" class="form-control form-control-pill @error('image') is-invalid @enderror" onchange="previewAvatar(this)">
                        <small class="text-muted mt-1 d-block">Square image format recommended (JPG, PNG, WEBP, Max 2MB).</small>
                        @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        
                        {{-- Preview current image --}}
                        <div id="avatarPreviewContainer" class="mt-3">
                            <label class="field-label d-block" style="font-size:0.8rem;">Current Avatar</label>
                            <img id="avatarPreview" src="{{ $testimonial->avatar_url }}" alt="Preview" style="max-width:100px;border-radius:50%;object-fit:cover;height:100px;border:2px solid #e2e8f0;">
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <label for="status" class="field-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" required class="form-select form-control-pill @error('status') is-invalid @enderror">
                            <option value="Published" {{ old('status', $testimonial->status) == 'Published' ? 'selected' : '' }}>Published</option>
                            <option value="Draft" {{ old('status', $testimonial->status) == 'Draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Review Text --}}
                <div class="mb-4">
                    <label for="review" class="field-label">Review / Feedback Content <span class="text-danger">*</span></label>
                    <textarea name="review" id="review" required rows="6" class="form-control form-control-pill @error('review') is-invalid @enderror" placeholder="Write patient testimonial review text here...">{{ old('review', $testimonial->review) }}</textarea>
                    @error('review')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-submit-pill">
                        <i class="fa-solid fa-check"></i>
                        <span>Update Testimonial</span>
                    </button>
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary px-4 py-2" style="border-radius:8px;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewAvatar(input) {
        const preview = document.getElementById('avatarPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
