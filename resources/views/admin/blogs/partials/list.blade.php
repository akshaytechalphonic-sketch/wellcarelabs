<div id="blogsAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

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
                <strong>{{ request('q') ?: 'All Blogs' }}</strong>
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
                            <span><span class="dot dot-all"></span> All blogs</span>
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

        <form method="GET" action="{{ route('admin.blogs.index') }}" class="d-flex align-items-center gap-2 blog-search-form">

            <div class="chip-search">
                <input name="q" type="search" placeholder="Search blog title..." value="{{ request('q') }}">
                <button type="submit">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>

            @if (request('q'))
            <a href="{{ route('admin.blogs.index') }}" class="chip-clear">
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
                        <th width="120">Image</th>
                        <th width="160">Status</th>
                        <th width="160" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $i => $blog)
                    @php $st = strtolower($blog->status ?? 'draft'); @endphp
                    <tr>
                        <td>{{ $i + 1 + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
                        <td class="fw-semibold">{{ $blog->title }}</td>
                        <td>
                            @if ($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Blog image"
                                class="blog-thumbnail"
                                style="width:70px;height:48px;object-fit:cover;border-radius:6px;box-shadow:0 2px 6px rgba(0,0,0,.15);">
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>

                        <td>
                            <span
                                class="badge {{ $st === 'published' ? 'bg-primary' : 'bg-secondary' }} text-white">
                                {{ ucfirst($st) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">

                                <button class="icon-btn view-blog-btn" data-blog-id="{{ $blog->id }}"
                                    data-url="{{ route('admin.blogs.show', $blog->id) }}">
                                    <i class="fa fa-eye"></i>
                                </button>

                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="icon-btn btn-edit">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}"
                                    class="d-inline delete-form"
                                    data-confirm="Are you sure you want to delete this blog?">
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
                            No blogs found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-2 pagination-wrapper">
        {!! $blogs->appends(request()->only('q', 'status'))->links() !!}
    </div>
</div>