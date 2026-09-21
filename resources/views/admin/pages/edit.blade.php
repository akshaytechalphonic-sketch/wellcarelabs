@extends('layouts.app')

@section('title', 'Dashboard - Edit Dynamic Page')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ asset('Back_end/css/blogs/Create-blogs.css') }}" rel="stylesheet">

    <div class="admin-page-wrapper">
        <div class="card admin-page-card-flush">

            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 bg-white border-bottom">
                <div>
                    <h5 class="panel-title mb-0">Edit Dynamic Page</h5>
                    <small class="text-muted">Modify page layouts and metadata</small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pages.index') }}" class="btn-ghost-secondary">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Back to list</span>
                    </a>
                </div>
            </div>

            <div class="card-body card-body-soft p-4">
                <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" id="pageEditForm" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Page Title <span class="req">*</span></label>
                        <input name="title" value="{{ old('title', $page->title) }}"
                            class="form-control wc-control @error('title') is-invalid @enderror"
                            placeholder="e.g. Meet Our Founder, Smart Blood Test" required>
                        @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- URL Slug --}}
                    <div class="mb-3">
                        <label class="wc-field-label">URL Slug <span class="text-muted">(Optional - auto-generated if left empty)</span></label>
                        <input name="slug" value="{{ old('slug', $page->slug) }}"
                            class="form-control wc-control @error('slug') is-invalid @enderror"
                            placeholder="e.g. founder">
                        @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SEO Meta Title --}}
                    <div class="mb-3">
                        <label class="wc-field-label">SEO Meta Title</label>
                        <input name="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
                            class="form-control wc-control @error('meta_title') is-invalid @enderror"
                            placeholder="Enter Meta Title for search engines">
                        @error('meta_title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SEO Meta Description --}}
                    <div class="mb-3">
                        <label class="wc-field-label">SEO Meta Description</label>
                        <textarea name="meta_description" class="form-control wc-control @error('meta_description') is-invalid @enderror"
                            placeholder="Enter Meta Description for search results" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SEO Meta Keywords --}}
                    {{-- <div class="mb-3">
                        <label class="wc-field-label">SEO Meta Keywords</label>
                        <input name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}"
                            class="form-control wc-control @error('meta_keywords') is-invalid @enderror"
                            placeholder="e.g. diagnostics, health checks, founder bio">
                        @error('meta_keywords')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    {{-- Schema Markup --}}
                    {{-- <div class="mb-3">
                        <label class="wc-field-label">Schema Markup JSON-LD <span class="text-muted">(Optional)</span></label>
                        <textarea name="schema_markup" class="form-control wc-control @error('schema_markup') is-invalid @enderror"
                            placeholder="Enter JSON-LD script for rich search snippets" rows="4">{{ old('schema_markup', $page->schema_markup) }}</textarea>
                        @error('schema_markup')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    {{-- Header Advanced Script Tags --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Advanced Header Script Tags</label>
                        <textarea name="meta_tags" class="form-control wc-control @error('meta_tags') is-invalid @enderror"
                            placeholder="e.g. custom Google Tag Manager, custom CSS, or header code snippets" rows="3">{{ old('meta_tags', $page->meta_tags ?? '') }}</textarea>
                        @error('meta_tags')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Banner Image --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Banner Image <span class="text-muted">(Optional)</span></label>
                        <input type="file" name="banner_image" class="form-control wc-control @error('banner_image') is-invalid @enderror" accept="image/*">
                        @error('banner_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if ($page->banner_image)
                            <div class="mt-2">
                                <label class="text-muted small d-block">Current Banner:</label>
                                <img src="{{ asset('storage/' . $page->banner_image) }}" alt="Banner"
                                    style="height:80px; border-radius:8px; border:1px solid #e5e7eb;">
                            </div>
                        @endif
                    </div>

                    {{-- Image Alt --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Image Alt Text (for SEO)</label>
                        <input name="image_alt" value="{{ old('image_alt', $page->image_alt) }}"
                            class="form-control wc-control @error('image_alt') is-invalid @enderror"
                            placeholder="Enter banner image description">
                        @error('image_alt')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label class="wc-field-label">Status <span class="req">*</span></label>
                        <select name="status" class="form-control wc-control" required style="border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 10px 16px; font-size: 0.95rem;">
                            <option value="Draft" {{ old('status', $page->status) === 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Published" {{ old('status', $page->status) === 'Published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </div>

                    {{-- Associated Packages Selection Box --}}
                    <div class="mb-4 mt-4 border-top pt-4">
                        <label class="wc-field-label fw-bold text-dark fs-6 mb-2">
                            <i class="fa-solid fa-box-archive text-primary me-1"></i> Select Packages to Include on Page
                        </label>
                        <div class="border rounded p-3 bg-white" style="border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important;">
                            <input type="text" id="packageSearchInput" class="form-control mb-3" placeholder="Search packages..." style="border-radius: 8px; font-size: 0.95rem;">
                            
                            <div id="packagesChecklistContainer" style="max-height: 220px; overflow-y: auto; padding-right: 5px;">
                                @if(isset($packages) && count($packages) > 0)
                                    @foreach($packages as $pkg)
                                        <div class="form-check mb-2 package-check-item">
                                            <input class="form-check-input" type="checkbox" name="package_ids[]" value="{{ $pkg->id }}" id="pkg_{{ $pkg->id }}"
                                                {{ ($page->packages->contains($pkg->id)) || (is_array(old('package_ids')) && in_array($pkg->id, old('package_ids'))) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-semibold" for="pkg_{{ $pkg->id }}" style="font-size: 0.92rem; cursor: pointer; margin-left: 5px;">
                                                {{ $pkg->title }} (₹{{ number_format($pkg->discounted_price ?: $pkg->price, 0) }})
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0 small">No active packages found.</p>
                                @endif
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Search and select one or more packages that belong to this page.</small>
                    </div>

                    {{-- Associated FAQs Selection Box --}}
                    <div class="mb-4">
                        <label class="wc-field-label fw-bold text-dark fs-6 mb-2">
                            <i class="fa-solid fa-circle-question text-primary me-1"></i> Select FAQs to Include on Page
                        </label>
                        <div class="border rounded p-3 bg-white" style="border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important;">
                            <input type="text" id="faqSearchInput" class="form-control mb-3" placeholder="Search FAQs..." style="border-radius: 8px; font-size: 0.95rem;">
                            
                            <div id="faqsChecklistContainer" style="max-height: 220px; overflow-y: auto; padding-right: 5px;">
                                @if(isset($faqs) && count($faqs) > 0)
                                    @foreach($faqs as $faq)
                                        <div class="form-check mb-2 faq-check-item">
                                            <input class="form-check-input" type="checkbox" name="faq_ids[]" value="{{ $faq->id }}" id="faq_{{ $faq->id }}"
                                                {{ ($page->faqs->contains($faq->id)) || (is_array(old('faq_ids')) && in_array($faq->id, old('faq_ids'))) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-semibold" for="faq_{{ $faq->id }}" style="font-size: 0.92rem; cursor: pointer; margin-left: 5px;">
                                                {{ $faq->question }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0 small">No active FAQs found.</p>
                                @endif
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Search and select one or more FAQs that belong to this page.</small>
                    </div>

                    {{-- Dynamic Section Builder --}}
                    <div class="mb-4 mt-4 border-top pt-4">
                        <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-list-check me-2"></i>Page Layout Sections</h5>
                        <div id="pageSectionsContainer" class="d-flex flex-column gap-3">
                            @foreach($page->sections as $index => $section)
                                @php $uniq = $section->id; @endphp
                                <div class="card p-3 border rounded shadow-sm mb-3 section-card" data-section-id="{{ $uniq }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold text-dark"><i class="fa-solid fa-layer-group me-1"></i> Section</span>
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-section-btn">
                                            <i class="fa-solid fa-trash-can"></i> Remove
                                        </button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="wc-field-label">Section Type</label>
                                            <select name="sections[{{ $uniq }}][type]" class="form-control wc-control section-type-select">
                                                <option value="text" {{ $section->type === 'text' ? 'selected' : '' }}>Standard Text Block</option>
                                                <option value="hero" {{ $section->type === 'hero' || $section->type === 'banner' ? 'selected' : '' }}>Hero/Banner Accent</option>
                                                <option value="biography" {{ $section->type === 'biography' || $section->type === 'profile' ? 'selected' : '' }}>Biography & Photo</option>
                                                <option value="quote" {{ $section->type === 'quote' ? 'selected' : '' }}>Blockquote Highlight</option>
                                                <option value="timeline" {{ $section->type === 'timeline' ? 'selected' : '' }}>Journey Timeline</option>
                                                <option value="values_grid" {{ $section->type === 'values_grid' ? 'selected' : '' }}>Core Values Grid</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="wc-field-label">Section Title / Header</label>
                                            <input name="sections[{{ $uniq }}][title]" type="text" class="form-control wc-control" value="{{ $section->content['title'] ?? $section->content['author'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="wc-field-label">Sort Order</label>
                                            <input name="sections[{{ $uniq }}][sort_order]" type="number" class="form-control wc-control" value="{{ $section->sort_order }}">
                                        </div>
                                        <div class="col-md-12 section-subtitle-wrap {{ in_array($section->type, ['quote']) ? 'd-none' : '' }}">
                                            <label class="wc-field-label">Section Subtitle</label>
                                            <input name="sections[{{ $uniq }}][subtitle]" type="text" class="form-control wc-control" value="{{ $section->content['subtitle'] ?? '' }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="wc-field-label">Content / Body / HTML Description</label>
                                            <textarea name="sections[{{ $uniq }}][body]" class="form-control wc-control section-body-editor" rows="4">{{ $section->content['body'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-md-12 section-image-wrap {{ in_array($section->type, ['text', 'quote', 'timeline', 'values_grid']) ? 'd-none' : '' }}">
                                            <label class="wc-field-label">Section Image</label>
                                            <input name="sections[{{ $uniq }}][image]" type="file" class="form-control wc-control" accept="image/*">
                                            @if(!empty($section->content['image_url']))
                                                <div class="mt-2">
                                                    <label class="text-muted small d-block">Current Image:</label>
                                                    <img src="{{ $section->content['image_url'] }}" style="height: 60px; border-radius: 4px;" alt="Section Image">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 text-start">
                            <button type="button" id="addSectionBtn" class="btn btn-outline-primary">
                                <i class="fa-solid fa-plus me-1"></i> Add Layout Section
                            </button>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" id="pageSubmitBtn" class="btn-primary-pill" style="border-radius: 50px; padding: 10px 24px; font-weight: 600; cursor: pointer; border: none; background: #0d6efd; color: white;">
                            <i class="fa-solid fa-check"></i>
                            <span>Update Page</span>
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="btn-ghost-secondary">
                            <span>Cancel</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let newSectionCount = 0;
            const container = document.getElementById('pageSectionsContainer');
            const addBtn = document.getElementById('addSectionBtn');
            const editors = {};

            function initEditor(textarea) {
                ClassicEditor
                    .create(textarea, {
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
                    .then(editor => {
                        editors[textarea.name] = editor;
                        editor.model.document.on('change:data', () => {
                            textarea.value = editor.getData();
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            // Initialize existing editors
            document.querySelectorAll('.section-body-editor').forEach(initEditor);

            addBtn.addEventListener('click', function() {
                newSectionCount++;
                const key = 'new_' + newSectionCount;
                
                const card = document.createElement('div');
                card.className = 'card p-3 border rounded shadow-sm mb-3 section-card';
                card.setAttribute('data-section-id', key);
                
                card.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark"><i class="fa-solid fa-layer-group me-1"></i> New Section</span>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-section-btn">
                            <i class="fa-solid fa-trash-can"></i> Remove
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="wc-field-label">Section Type</label>
                            <select name="sections[${key}][type]" class="form-control wc-control section-type-select">
                                <option value="text" selected>Standard Text Block</option>
                                <option value="hero">Hero/Banner Accent</option>
                                <option value="biography">Biography & Photo</option>
                                <option value="quote">Blockquote Highlight</option>
                                <option value="timeline">Journey Timeline</option>
                                <option value="values_grid">Core Values Grid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="wc-field-label">Section Title / Header</label>
                            <input name="sections[${key}][title]" type="text" class="form-control wc-control" value="">
                        </div>
                        <div class="col-md-2">
                            <label class="wc-field-label">Sort Order</label>
                            <input name="sections[${key}][sort_order]" type="number" class="form-control wc-control" value="${container.children.length + 1}">
                        </div>
                        <div class="col-md-12 section-subtitle-wrap">
                            <label class="wc-field-label">Section Subtitle</label>
                            <input name="sections[${key}][subtitle]" type="text" class="form-control wc-control" value="">
                        </div>
                        <div class="col-md-12">
                            <label class="wc-field-label">Content / Body / HTML Description</label>
                            <textarea name="sections[${key}][body]" class="form-control wc-control section-body-editor" rows="4"></textarea>
                        </div>
                        <div class="col-md-12 section-image-wrap d-none">
                            <label class="wc-field-label">Section Image</label>
                            <input name="sections[${key}][image]" type="file" class="form-control wc-control" accept="image/*">
                        </div>
                    </div>
                `;
                
                container.appendChild(card);
                initEditor(card.querySelector('.section-body-editor'));
            });

            // Toggle file upload visibility based on type
            document.addEventListener('change', function(e) {
                const select = e.target.closest('.section-type-select');
                if (!select) return;
                
                const card = select.closest('.section-card');
                const type = select.value;
                
                const imageWrap = card.querySelector('.section-image-wrap');
                const subtitleWrap = card.querySelector('.section-subtitle-wrap');
                
                if (type === 'quote') {
                    subtitleWrap.classList.add('d-none');
                } else {
                    subtitleWrap.classList.remove('d-none');
                }
                
                if (type === 'hero' || type === 'banner' || type === 'biography' || type === 'profile') {
                    imageWrap.classList.remove('d-none');
                } else {
                    imageWrap.classList.add('d-none');
                }
            });

            // Remove section item
            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-section-btn');
                if (!removeBtn) return;
                
                const card = removeBtn.closest('.section-card');
                if (confirm('Are you sure you want to remove this section?')) {
                    card.remove();
                }
            });
            // Live Package Search Filter
            document.getElementById('packageSearchInput')?.addEventListener('keyup', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#packagesChecklistContainer .package-check-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(q) ? 'block' : 'none';
                });
            });

            // Live FAQ Search Filter
            document.getElementById('faqSearchInput')?.addEventListener('keyup', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('#faqsChecklistContainer .faq-check-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(q) ? 'block' : 'none';
                });
            });
        });
    </script>
@endsection
