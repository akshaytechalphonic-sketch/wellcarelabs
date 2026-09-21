@php
    $isEdit = isset($package);
    $action = $isEdit
        ? route('admin.packages.update', $package->id ?? $package)
        : route('admin.packages.store');
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" data-ajax="true">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $package->title ?? '') }}" required>
    </div>

    @if(!empty($hasSlug))
        <div class="mb-3">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug', $package->slug ?? '') }}">
            <small class="text-muted">If left empty, the slug will be generated from the title.</small>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label">Content / Description</label>
        <textarea name="content" rows="6" class="form-control">{{ old('content', $package->content ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Banner (image)</label>
        <input type="file" name="banner" accept="image/*" class="form-control">
        @if(!empty($package->banner ?? null))
            <div class="mt-2">
                {{-- try to use Storage::url if available, fallback to asset(storage/...) --}}
                @php
                    $bannerUrl = (Illuminate\Support\Facades\Storage::disk('public')->exists($package->banner ?? ''))
                        ? Illuminate\Support\Facades\Storage::url($package->banner)
                        : asset('storage/' . ($package->banner ?? ''));
                @endphp

                <img src="{{ $bannerUrl }}" alt="banner" style="height:80px;object-fit:cover;">
            </div>
        @endif
    </div>

    <div class="form-check mb-3">
        @php
            // normalize old / model value into boolean-ish for checked attribute
            $statusValue = old('status', $package->status ?? true);
            $isChecked = in_array($statusValue, [1, '1', true, 'true', 'Published', 'Published'], true);
        @endphp

        <input type="checkbox" name="status" id="status" value="1" class="form-check-input" {{ $isChecked ? 'checked' : '' }}>
        <label for="status" class="form-check-label">Published</label>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">{{ $isEdit ? 'Update Package' : 'Create Package' }}</button>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary" data-ajax="true" data-url="{{ route('admin.packages.index') }}">Cancel</a>
    </div>
</form>
