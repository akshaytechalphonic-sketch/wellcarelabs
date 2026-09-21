@extends('layouts.app')

@section('title', 'Dashboard - Edit Test')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/tests/edit-test.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card shadow-sm admin-page-card-flush">

            {{-- Header --}}
            <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Edit Test</h5>
                    <small class="text-muted">Edit lab test details</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.labtests.index') }}" class="btn-ghost-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to list</span>
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body card-body-soft">
                @if (session('success'))
                    <div class="alert alert-success mb-3" role="alert">{{ session('success') }}</div>
                @endif

                @php
                    $oldStatus = old('status', $labtest->status ?? null);
                @endphp

                <form action="{{ route('admin.labtests.update', $labtest->id) }}" method="POST" id="labtestEditForm"
                    novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Test Name --}}
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="test_name" class="wc-field-label">
                                Test Name <span class="text-danger">*</span>
                            </label>
                            <input id="test_name" name="test_name" type="text"
                                class="form-control wc-control @error('test_name') is-invalid @enderror"
                                value="{{ old('test_name', $labtest->test_name ?? '') }}" required
                                placeholder="Enter test name">
                            @error('test_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Dynamic Pages Selection --}}
                    {{-- <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="page_id" class="wc-field-label">Dynamic Pages (Optional)</label>
                            <select id="page_id" name="page_id"
                                class="form-control wc-control @error('page_id') is-invalid @enderror">
                                <option value="">Select Page</option>
                                @foreach ($page as $pageItem)
                                    <option value="{{ $pageItem->id }}"
                                        {{ old('page_id', $labtest->page_id ?? '') == $pageItem->id ? 'selected' : '' }}>
                                        {{ $pageItem->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('page_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}

                    

                    {{-- Test Code & Parameters Count --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="test_code" class="wc-field-label">Test Code</label>
                            <input id="test_code" name="test_code" type="text"
                                class="form-control wc-control @error('test_code') is-invalid @enderror"
                                value="{{ old('test_code', $labtest->test_code ?? '') }}" placeholder="Enter test code">
                            @error('test_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="parameters_count" class="wc-field-label">Parameters Count</label>
                            <input id="parameters_count" name="parameters_count" type="number"
                                class="form-control wc-control @error('parameters_count') is-invalid @enderror"
                                value="{{ old('parameters_count', $labtest->parameters_count ?? '') }}"
                                placeholder="Enter parameters count">
                            @error('parameters_count')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="wc-field-label">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea id="" name="description" rows="4"
                            class="ckeditor form-control wc-control @error('description') is-invalid @enderror" required
                            placeholder="Enter test description">{{ old('description', $labtest->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sample Type & Fasting Status --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sample_type" class="wc-field-label">Sample Type</label>
                            <input id="sample_type" name="sample_type" type="text"
                                class="form-control wc-control @error('sample_type') is-invalid @enderror"
                                value="{{ old('sample_type', $labtest->sample_type ?? '') }}"
                                placeholder="e.g. Blood, Urine">
                            @error('sample_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fasting" class="wc-field-label">Fasting Status</label>
                            <input id="fasting" name="fasting" type="text"
                                class="form-control wc-control @error('fasting') is-invalid @enderror"
                                value="{{ old('fasting', $labtest->fasting ?? '') }}"
                                placeholder="e.g. Yes, No, Not Required">
                            @error('fasting')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Advanced SEO Meta Tags --}}
                    <div class="mb-3">
                        <label for="meta_tags" class="wc-field-label">
                            Advanced SEO Meta Tags
                        </label>
                        <textarea id="meta_tags" name="meta_tags" rows="5"
                            placeholder="Enter advanced SEO meta tags"
                            class="form-control wc-control @error('meta_tags') is-invalid @enderror">{{ old('meta_tags', $labtest->meta_tags ?? '') }}</textarea>
                        @error('meta_tags')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Prices --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="mrp" class="wc-field-label">
                                MRP <span class="text-danger">*</span>
                            </label>
                            <input id="mrp" name="mrp" type="number" step="0.01"
                                class="form-control wc-control @error('mrp') is-invalid @enderror"
                                value="{{ old('mrp', $labtest->mrp ?? '') }}" required placeholder="Enter MRP">
                            @error('mrp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="b2b" class="wc-field-label">
                                B2B Price
                            </label>
                            <input id="b2b" name="b2b" type="number" step="0.01"
                                class="form-control wc-control @error('b2b') is-invalid @enderror"
                                value="{{ old('b2b', $labtest->b2b ?? '') }}" placeholder="Enter B2B price">
                            @error('b2b')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="discounted_price" class="wc-field-label">
                                Selling Price
                            </label>
                            <input id="discounted_price" name="discounted_price" type="number" step="0.01"
                                class="form-control wc-control @error('discounted_price') is-invalid @enderror"
                                value="{{ old('discounted_price', $labtest->discounted_price ?? '') }}"
                                placeholder="Enter discounted price">
                            @error('discounted_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Tab Content Textareas --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="why_done" class="wc-field-label">Why is the Test ?</label>
                            <textarea id="why_done" name="why_done" rows="3"
                                class="ckeditor form-control wc-control @error('why_done') is-invalid @enderror" placeholder="Enter details...">{{ old('why_done', $labtest->why_done ?? '') }}</textarea>
                            @error('why_done')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="who_should_test" class="wc-field-label">Who should take it?</label>
                            <textarea id="who_should_test" name="who_should_test" rows="3"
                                class="ckeditor form-control wc-control @error('who_should_test') is-invalid @enderror" placeholder="Enter details...">{{ old('who_should_test', $labtest->who_should_test ?? '') }}</textarea>
                            @error('who_should_test')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="how_to_read" class="wc-field-label">Why is it done?</label>
                            <textarea id="how_to_read" name="how_to_read" rows="3"
                                class="ckeditor form-control wc-control @error('how_to_read') is-invalid @enderror" placeholder="Enter details...">{{ old('how_to_read', $labtest->how_to_read ?? '') }}</textarea>
                            @error('how_to_read')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="what_to_ask" class="wc-field-label">Preparation required?</label>
                            <textarea id="what_to_ask" name="what_to_ask" rows="3"
                                class="ckeditor form-control wc-control @error('what_to_ask') is-invalid @enderror" placeholder="Enter details...">{{ old('what_to_ask', $labtest->what_to_ask ?? '') }}</textarea>
                            @error('what_to_ask')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- SEO Meta Title & Meta Description --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="meta_title" class="wc-field-label">SEO Meta Title</label>
                            <input id="meta_title" name="meta_title" type="text"
                                class="form-control wc-control @error('meta_title') is-invalid @enderror"
                                value="{{ old('meta_title', $labtest->meta_title ?? '') }}"
                                placeholder="Enter SEO Meta Title">
                            @error('meta_title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="meta_description" class="wc-field-label">SEO Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="3"
                                class="form-control wc-control @error('meta_description') is-invalid @enderror"
                                placeholder="Enter SEO Meta Description">{{ old('meta_description', $labtest->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    
                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="wc-field-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <div class="wc-select-shell" id="statusDropdown">
                            <i class="fa-solid fa-circle-dot"></i>

                            {{-- Displayed text --}}
                            <div class="wc-select-display" id="statusDisplay">
                                @if ($oldStatus === 'Draft')
                                    Draft
                                @elseif ($oldStatus === 'Published')
                                    Published
                                @else
                                    <span class="wc-select-placeholder">-- Select Status --</span>
                                @endif
                            </div>

                            {{-- Native select (hidden visually, used by form) --}}
                            <select id="statusSelect" name="status"
                                class="status-select @error('status') is-invalid @enderror" required>
                                <option value="" disabled {{ $oldStatus ? '' : 'selected' }}>-- Select Status --
                                </option>
                                <option value="Draft" {{ $oldStatus === 'Draft' ? 'selected' : '' }}>Draft</option>
                                <option value="Published" {{ $oldStatus === 'Published' ? 'selected' : '' }}>Published
                                </option>
                            </select>

                            {{-- Custom menu --}}
                            <div class="wc-select-menu" id="statusMenu">
                                <button type="button" class="wc-select-option" data-value="Draft">
                                    <span class="label">
                                        <span class="badge-dot"></span>
                                        Draft
                                    </span>
                                    <span class="kbd">D</span>
                                </button>
                                <button type="button" class="wc-select-option" data-value="Published">
                                    <span class="label">
                                        <span class="badge-dot"></span>
                                        Published
                                    </span>
                                    <span class="kbd">P</span>
                                </button>
                            </div>
                        </div>

                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" id="submitBtn" class="btn-primary-pill">
                            <i class="fa-solid fa-check"></i>
                            <span>Update</span>
                        </button>
                        <a href="{{ route('admin.labtests.index') }}" class="btn-ghost-secondary">
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
      <script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('.ckeditor').forEach(function (element) {
        ClassicEditor
            .create(element, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'highlight', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'blockQuote', 'undo', 'redo',
                    'sourceEditing'
                ],
                link: {
                    addTargetToExternalLinks: true
                }
            })
            .catch(error => {
                console.error(error);
            });
    });

});
</script>

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/tests/edit-test.js') }}"></script>


  
@endsection
