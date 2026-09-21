<div id="pagesAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

    @php
    $status = request('status');
    $statusLabel = $status === 'published' ? 'Published' : ($status === 'draft' ? 'Draft' : 'Status');
    $statusDot = $status === 'published' ? 'dot-active' : ($status === 'draft' ? 'dot-inactive' : 'dot-all');
    @endphp

    {{-- ================= TOOLBAR ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-2 toolbar-wrapper"
        style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="text-muted small">
                Showing results for:
                <strong>{{ request('q') ?: 'All Pages' }}</strong>
            </div>

            <div class="dropdown">
                <button class="btn btn-status-filter dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="dot {{ $statusDot }}"></span>
                    {{ $statusLabel }}
                    <i class="fa fa-chevron-down ms-1 small"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end status-filter-menu">
                    <li>
                        <a class="dropdown-item status-filter-item {{ !$status ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                            <span><span class="dot dot-all"></span> All Pages</span>
                            @if (!$status)
                            <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item status-filter-item {{ $status === 'published' ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => 'published']) }}">
                            <span><span class="dot dot-active"></span> Published</span>
                            @if ($status === 'published')
                            <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item status-filter-item {{ $status === 'draft' ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}">
                            <span><span class="dot dot-inactive"></span> Draft</span>
                            @if ($status === 'draft')
                            <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.pages.index') }}" class="d-flex align-items-center gap-2 blog-search-form">
            <div class="chip-search">
                <input name="q" type="search" placeholder="Search page title or slug..." value="{{ request('q') }}">
                <button type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>

            @if (request('q'))
            <a href="{{ route('admin.pages.index') }}" class="chip-clear">
                <i class="fa fa-xmark"></i> Clear
            </a>
            @endif
        </form>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="table-wrap">
        <div class="table-scroll">
            <table class="blogs-table">
                <thead>
                    <tr>
                        <th width="72">Sr.No.</th>
                        <th>Title</th>
                        <th>URL Path</th>
                        <th width="160">Status</th>
                        <th width="160" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $i => $page)
                    @php $st = strtolower($page->status ?? 'draft'); @endphp
                    <tr>
                        <td>{{ $i + 1 + ($pages->currentPage() - 1) * $pages->perPage() }}</td>
                        <td class="fw-semibold">{{ $page->title }}</td>
                        <td>
                            @if($st === 'published')
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-decoration-none text-primary">
                                    <code>/pages/{{ $page->slug }}</code>
                                    <i class="fa-solid fa-external-link ms-1 small"></i>
                                </a>
                            @else
                                <code class="text-muted">/pages/{{ $page->slug }}</code>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $st === 'published' ? 'bg-success' : 'bg-secondary' }} text-white">
                                {{ ucfirst($st) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- <button type="button" class="icon-btn view-page-btn" data-page-id="{{ $page->id }}"
                                    data-url="{{ route('admin.pages.show', $page->id) }}">
                                    <i class="fa fa-eye"></i>
                                </button> --}}

                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="icon-btn btn-edit">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.pages.destroy', $page->id) }}"
                                    class="d-inline delete-form"
                                    data-confirm="Are you sure you want to delete this custom page?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="icon-btn btn-delete delete-btn">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            No custom pages found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-2 pagination-wrapper">
        {!! $pages->appends(request()->only('q', 'status'))->links() !!}
    </div>
</div>
