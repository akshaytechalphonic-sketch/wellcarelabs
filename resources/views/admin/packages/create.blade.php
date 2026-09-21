@extends('layouts.app')

@section('title', 'Dashboard - Create Package')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- External CSS --}}
    <link href="{{ asset('Back_end/css/packages/create-package.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card shadow-sm admin-page-card-flush">
            {{-- Header --}}
            <div
                class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Create Package</h5>
                    <small class="text-muted">Add a new lab package</small>
                </div>
                <a href="{{ route('admin.packages.index') }}" class="btn-ghost-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to list</span>
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body card-body-soft">
                <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data"
                    id="packageCreateForm" novalidate>
                    @csrf

                    {{-- Package Name --}}
                    <div class="mb-3">
                        <label for="title" class="wc-field-label">
                            Package Name <span class="text-danger">*</span>
                        </label>
                        <input id="title" type="text" name="title" required
                            class="form-control wc-control @error('title') is-invalid @enderror"
                            placeholder="Enter package title" value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="content" class="wc-field-label">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea id="description" name="content" rows="5" required
                            class="ckeditor form-control wc-control @error('content') is-invalid @enderror" placeholder="Enter detailed description">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_tags" class="wc-field-label">
                            Advanced SEO Meta Tags 
                        </label>
                        <textarea id="meta_tags" name="meta_tags" rows="5"
                            class="form-control wc-control @error('meta_tags') is-invalid @enderror" placeholder="Enter advanced SEO meta tags">{{ old('meta_tags') }}</textarea>
                        @error('meta_tags')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Pricing --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="mrp" class="wc-field-label">
                                MRP <span class="text-danger">*</span>
                            </label>
                            <input id="mrp" type="number" step="0.01" name="mrp" required
                                class="form-control wc-control @error('mrp') is-invalid @enderror" placeholder="₹"
                                value="{{ old('mrp') }}">
                            @error('mrp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="discounted_price" class="wc-field-label">
                                Selling Price
                            </label>
                            <input id="discounted_price" type="number" step="0.01" name="discounted_price"
                                class="form-control wc-control @error('discounted_price') is-invalid @enderror"
                                placeholder="₹" value="{{ old('discounted_price') }}">
                            @error('discounted_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Image & Alt Text --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="banner" class="wc-field-label">
                                Package Image <span class="text-danger">*</span>
                            </label>
                            <input id="banner" type="file" name="banner" required
                                accept="image/png, image/jpeg, image/webp"
                                class="form-control wc-control @error('banner') is-invalid @enderror"
                                aria-describedby="bannerHelp">
                            <div id="bannerHelp" class="wc-help-text mt-1">
                                Allowed: JPG, JPEG, PNG, WEBP — Max size 5MB.
                            </div>
                            @error('banner')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="image_alt" class="wc-field-label">
                                Image Alt Text (for SEO)
                            </label>
                            <input id="image_alt" type="text" name="image_alt"
                                class="form-control wc-control @error('image_alt') is-invalid @enderror"
                                placeholder="Enter image alternative description" value="{{ old('image_alt') }}">
                            @error('image_alt')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Preview --}}
                    <div id="bannerPreviewContainer" class="mt-3 p-3" style="display:none;">
                        <label class="wc-field-label" style="font-size:0.9rem;">Preview</label>
                        <img id="bannerPreview" src="#" alt="Preview">
                    </div>

                    {{-- Specifications --}}
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="package_code" class="wc-field-label">Package Code</label>
                            <input id="package_code" type="text" name="package_code"
                                class="form-control wc-control @error('package_code') is-invalid @enderror"
                                placeholder="e.g. PKG001" value="{{ old('package_code') }}">
                            @error('package_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="sample_type" class="wc-field-label">Sample Type</label>
                            <input id="sample_type" type="text" name="sample_type"
                                class="form-control wc-control @error('sample_type') is-invalid @enderror"
                                placeholder="e.g. Blood, Urine" value="{{ old('sample_type') }}">
                            @error('sample_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fasting" class="wc-field-label">Fasting Status</label>
                            <input id="fasting" type="text" name="fasting"
                                class="form-control wc-control @error('fasting') is-invalid @enderror"
                                placeholder="e.g. Yes, No" value="{{ old('fasting') }}">
                            @error('fasting')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="parameters_count" class="wc-field-label">Parameters Count</label>
                            <input id="parameters_count" type="number" name="parameters_count"
                                class="form-control wc-control @error('parameters_count') is-invalid @enderror"
                                placeholder="e.g. 55" value="{{ old('parameters_count') }}">
                            @error('parameters_count')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Associated Lab Tests --}}
                    <div class="mb-4">
                        <label class="wc-field-label">Select Associated Lab Tests</label>
                        <div style="max-height: 250px; overflow-y: auto; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 12px; background: #fff;">
                            <input type="text" id="testSearchInput" class="form-control mb-2" placeholder="Search tests..." style="border-radius: 6px; padding: 6px 12px; font-size: 0.9rem;">
                            <div id="testsChecklist">
                                @foreach($tests as $t)
                                    <div class="form-check mb-1 test-item-row" data-name="{{ strtolower($t->test_name) }}">
                                        <input class="form-check-input" type="checkbox" name="test_ids[]" value="{{ $t->id }}" id="test_{{ $t->id }}"
                                            {{ is_array(old('test_ids')) && in_array($t->id, old('test_ids')) ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark" for="test_{{ $t->id }}" style="font-size: 0.92rem; cursor: pointer; display: inline-block; vertical-align: middle; margin-left: 5px;">
                                            {{ $t->test_name }} ({{ $t->test_code ?: 'No Code' }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Search and select one or more tests that belong to this package.</small>
                    </div>

                    {{-- Dynamic Content Tabs --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="why_done" class="wc-field-label">Why is the Test ?</label>
                            <textarea id="why_done" name="why_done" rows="3"
                                class="ckeditor form-control wc-control @error('why_done') is-invalid @enderror"
                                placeholder="Describe why this package is done">{{ old('why_done') }}</textarea>
                            @error('why_done')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="who_should_test" class="wc-field-label">Who should take it ?</label>
                            <textarea id="who_should_test" name="who_should_test" rows="3"
                                class="ckeditor form-control wc-control @error('who_should_test') is-invalid @enderror"
                                placeholder="Who should choose this package?">{{ old('who_should_test') }}</textarea>
                            @error('who_should_test')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="how_to_read" class="wc-field-label">Why is it done?</label>
                            <textarea id="how_to_read" name="how_to_read" rows="3"
                                class="ckeditor form-control wc-control @error('how_to_read') is-invalid @enderror"
                                placeholder="Why is this test done?">{{ old('how_to_read') }}</textarea>
                            @error('how_to_read')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="what_to_ask" class="wc-field-label">Preparation required?</label>
                            <textarea id="what_to_ask" name="what_to_ask" rows="3"
                                class="ckeditor form-control wc-control @error('what_to_ask') is-invalid @enderror"
                                placeholder="Questions to ask the doctor?">{{ old('what_to_ask') }}</textarea>
                            @error('what_to_ask')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- SEO details --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="meta_title" class="wc-field-label">SEO Meta Title</label>
                            <input id="meta_title" type="text" name="meta_title"
                                class="form-control wc-control @error('meta_title') is-invalid @enderror"
                                placeholder="Enter SEO Meta Title" value="{{ old('meta_title') }}">
                            @error('meta_title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="meta_description" class="wc-field-label">SEO Meta Description</label>
                            <textarea id="meta_description" name="meta_description" rows="2"
                                class="form-control wc-control @error('meta_description') is-invalid @enderror"
                                placeholder="Enter SEO Meta Description">{{ old('meta_description') }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Special toggle --}}
                    <div class="mb-3">
                        <div class="special-area">
                            <div>
                                <label class="special-toggle-pill {{ old('is_special') ? 'active' : '' }}"
                                    id="specialTogglePill">
                                    <span class="special-toggle-pill-icon">
                                        <i class="fa-solid fa-star"></i>
                                    </span>
                                    <div>
                                        <div class="special-toggle-text-main">
                                            Mark as <span style="color:#f97316;">Special package</span>
                                        </div>
                                    </div>
                                    <input type="checkbox" id="is_special" name="is_special" value="1"
                                        {{ old('is_special') ? 'checked' : '' }} style="display:none;">
                                </label>
                            </div>
                        </div>

                    {{-- FAQ Repeater --}}
                    <div class="mb-4">
                        <label class="wc-field-label">Frequently Asked Questions (FAQs)</label>
                        <div style="border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 16px; background: #fff;">
                            <div id="faqContainer">
                                {{-- Loop through old input if any, or show one empty row --}}
                                @php
                                    $oldFaqs = old('faqs', []);
                                @endphp
                                @if(is_array($oldFaqs) && count($oldFaqs) > 0)
                                    @foreach($oldFaqs as $index => $faq)
                                        <div class="faq-row p-3 mb-3 border rounded-3 bg-light position-relative" data-index="{{ $index }}" style="border: 1px solid #cbd5e1 !important;">
                                            <div class="row g-3 align-items-start">
                                                <div class="col-md-5">
                                                    <label class="wc-field-label">Question</label>
                                                    <input type="text" name="faqs[{{ $index }}][question]" class="form-control wc-control" placeholder="Enter Question" value="{{ $faq['question'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="wc-field-label">Answer</label>
                                                    <textarea name="faqs[{{ $index }}][answer]" class="form-control wc-control" rows="2" placeholder="Enter Answer" style="min-height: 80px; height: auto;">{{ $faq['answer'] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-md-1 text-end mt-4 pt-1">
                                                    <button type="button" class="btn btn-danger remove-faq-btn" style="border-radius: 8px; width: 42px; height: 42px; padding: 0; display: inline-flex; align-items: center; justify-content: center; background-color: var(--wc-danger); border: none; color: white;">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="faq-row p-3 mb-3 border rounded-3 bg-light position-relative" data-index="0" style="border: 1px solid #cbd5e1 !important;">
                                        <div class="row g-3 align-items-start">
                                            <div class="col-md-5">
                                                <label class="wc-field-label">Question</label>
                                                <input type="text" name="faqs[0][question]" class="form-control wc-control" placeholder="Enter Question" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="wc-field-label">Answer</label>
                                                <textarea name="faqs[0][answer]" class="form-control wc-control" rows="2" placeholder="Enter Answer" style="min-height: 80px; height: auto;"></textarea>
                                            </div>
                                            <div class="col-md-1 text-end mt-4 pt-1">
                                                <button type="button" class="btn btn-danger remove-faq-btn" style="border-radius: 8px; width: 42px; height: 42px; padding: 0; display: inline-flex; align-items: center; justify-content: center; background-color: var(--wc-danger); border: none; color: white;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-start">
                                <button type="button" class="btn-primary-pill" id="addFaqBtn" style="cursor: pointer; padding: 6px 14px; font-size: 0.85rem;">
                                    <i class="fa-solid fa-plus"></i> Add FAQ
                                </button>
                            </div>
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
                            @php
                                $oldStatus = old('status');
                            @endphp
                            <div class="wc-select-display" id="statusDisplay">
                                @if ($oldStatus === 'Draft')
                                    Draft
                                @elseif ($oldStatus === 'Published')
                                    Published
                                @else
                                    <span class="wc-select-placeholder">-- Select status --</span>
                                @endif
                            </div>

                            {{-- Native select (hidden visually, used by form) --}}
                            <select name="status" id="statusSelect" class="status-select" required>
                                <option value="" disabled {{ $oldStatus === null ? 'selected' : '' }}>-- Select
                                    status --</option>
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

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn-primary-pill">
                            <i class="fa-solid fa-check"></i>
                            <span>Create Package</span>
                        </button>
                        <a href="{{ route('admin.packages.index') }}" class="btn-ghost-secondary">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Checklist Search Filter
            const searchInput = document.getElementById('testSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const items = document.querySelectorAll('.test-item-row');
                    items.forEach(function(item) {
                        const name = item.getAttribute('data-name');
                        if (name.includes(query)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // FAQ Repeater logic
            const faqContainer = document.getElementById('faqContainer');
            const addFaqBtn = document.getElementById('addFaqBtn');

            if (addFaqBtn && faqContainer) {
                let faqIndex = faqContainer.querySelectorAll('.faq-row').length;

                addFaqBtn.addEventListener('click', function() {
                    const rowHtml = `
                        <div class="faq-row p-3 mb-3 border rounded-3 bg-light position-relative" data-index="${faqIndex}" style="border: 1px solid #cbd5e1 !important;">
                            <div class="row g-3 align-items-start">
                                <div class="col-md-5">
                                    <label class="wc-field-label">Question</label>
                                    <input type="text" name="faqs[${faqIndex}][question]" class="form-control wc-control" placeholder="Enter Question" value="">
                                </div>
                                <div class="col-md-6">
                                    <label class="wc-field-label">Answer</label>
                                    <textarea name="faqs[${faqIndex}][answer]" class="form-control wc-control" rows="2" placeholder="Enter Answer" style="min-height: 80px; height: auto;"></textarea>
                                </div>
                                <div class="col-md-1 text-end mt-4 pt-1">
                                    <button type="button" class="btn btn-danger remove-faq-btn" style="border-radius: 8px; width: 42px; height: 42px; padding: 0; display: inline-flex; align-items: center; justify-content: center; background-color: var(--wc-danger); border: none; color: white;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    faqContainer.insertAdjacentHTML('beforeend', rowHtml);
                    faqIndex++;
                });

                faqContainer.addEventListener('click', function(e) {
                    const removeBtn = e.target.closest('.remove-faq-btn');
                    if (removeBtn) {
                        const row = removeBtn.closest('.faq-row');
                        if (row) {
                            row.remove();
                        }
                    }
                });
            }
        });
    </script>

    {{-- External JS --}}
    <script src="{{ asset('Back_end/js/packages/create-package.js') }}"></script>
@endsection
