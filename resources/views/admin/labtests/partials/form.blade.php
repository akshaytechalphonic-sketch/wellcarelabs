@php
    $isEdit = isset($labtest) && $labtest->exists;
    $action = $isEdit ? route('admin.labtests.update', $labtest) : route('admin.labtests.store');
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">{{ $isEdit ? 'Edit Test' : 'Create Test' }}</h5>
    <div>
        <a href="{{ route('admin.labtests.index') }}" class="btn btn-outline-secondary" data-ajax="true" data-url="{{ route('admin.labtests.index') }}">Back to list</a>
    </div>
</div>

<form action="{{ $action }}" method="POST" data-ajax="true">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Test Name</label>
            <input type="text" name="test_name" class="form-control" value="{{ old('test_name', $labtest->test_name ?? '') }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Test Code</label>
            <input type="text" name="test_code" class="form-control" value="{{ old('test_code', $labtest->test_code ?? '') }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Parameters Count</label>
            <input type="number" name="parameters_count" class="form-control" value="{{ old('parameters_count', $labtest->parameters_count ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Advanced SEO Meta Tags</label>
            <textarea name="meta_tags" class="form-control" rows="4" placeholder="Enter custom HTML/Script meta tags">{{ old('meta_tags', $labtest->meta_tags ?? '') }}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">MRP (₹)</label>
            <input type="number" step="0.01" name="mrp" class="form-control" value="{{ old('mrp', $labtest->mrp ?? '') }}" required>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">B2B (₹)</label>
            <input type="number" step="0.01" name="b2b" class="form-control" value="{{ old('b2b', $labtest->b2b ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Discounted Price (₹)</label>
            <input type="number" step="0.01" name="discounted_price" class="form-control" value="{{ old('discounted_price', $labtest->discounted_price ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Sample Type</label>
            <input type="text" name="sample_type" class="form-control" placeholder="e.g. Blood, Urine" value="{{ old('sample_type', $labtest->sample_type ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Fasting Status</label>
            <input type="text" name="fasting" class="form-control" placeholder="e.g. Yes, No, Not Required" value="{{ old('fasting', $labtest->fasting ?? '') }}">
        </div>
    </div>

    {{-- Description --}}
    <div class="mb-3">
        <label class="form-label">About the Test (Description)</label>
        <textarea name="description" class="form-control" rows="3" required>{{ old('description', $labtest->description ?? '') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Why is the Test ?</label>
            <textarea name="why_done" class="form-control" rows="3">{{ old('why_done', $labtest->why_done ?? '') }}</textarea>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Who should take it ?</label>
            <textarea name="who_should_test" class="form-control" rows="3">{{ old('who_should_test', $labtest->who_should_test ?? '') }}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Why is it done?</label>
            <textarea name="how_to_read" class="form-control" rows="3">{{ old('how_to_read', $labtest->how_to_read ?? '') }}</textarea>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Preparation required?</label>
            <textarea name="what_to_ask" class="form-control" rows="3">{{ old('what_to_ask', $labtest->what_to_ask ?? '') }}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">SEO Meta Title</label>
            <input type="text" name="meta_title" class="form-control" placeholder="Enter SEO Meta Title" value="{{ old('meta_title', $labtest->meta_title ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">SEO Meta Description</label>
            <textarea name="meta_description" class="form-control" rows="2" placeholder="Enter SEO Meta Description">{{ old('meta_description', $labtest->meta_description ?? '') }}</textarea>
        </div>
    </div>

    {{-- Dynamic Pages Selection --}}
    <div class="row">
        <div class="col-md-12 mb-3">
            <label class="form-label">Dynamic Pages (Optional)</label>
            <select name="page_id" class="form-select">
                <option value="">Select Page</option>
                @foreach ($page as $pageItem)
                    <option value="{{ $pageItem->id }}"
                        {{ old('page_id', $labtest->page_id ?? '') == $pageItem->id ? 'selected' : '' }}>
                        {{ $pageItem->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="draft" {{ (old('status', $labtest->status ?? '') === 'draft') ? 'selected' : '' }}>Draft</option>
            <option value="published" {{ (old('status', $labtest->status ?? '') === 'published') ? 'selected' : '' }}>Published</option>
        </select>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.labtests.index') }}" class="btn btn-outline-secondary" data-ajax="true" data-url="{{ route('admin.labtests.index') }}">Cancel</a>
    </div>
</form>
