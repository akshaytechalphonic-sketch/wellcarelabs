{{-- resources/views/admin/packages/partials/list.blade.php --}}
<div id="packageAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

    {{-- Toolbar: results text + STATUS FILTER + search --}}
    @php
        $statusFilter = request('status');
        $currentStatusLabel =
            $statusFilter === 'published' ? 'Published' : ($statusFilter === 'draft' ? 'Draft' : 'Status');
        $statusDotClass =
            $statusFilter === 'published' ? 'dot-active' : ($statusFilter === 'draft' ? 'dot-inactive' : 'dot-all');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-2 admin-fragment-toolbar"
        style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">

        {{-- Left: results text + status filter pill --}}
        <div class="toolbar-left d-flex align-items-center gap-3 flex-wrap">
            <div class="results-text text-muted small">
                Showing results for:
                <strong>{{ request('q') ? e(request('q')) : 'All Packages' }}</strong>
            </div>

            {{-- Modern status filter pill (like Banners) --}}
            <div class="dropdown status-filter-group">
                <button class="btn btn-status-filter dropdown-toggle" type="button" id="statusFilterDropdownPackages"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="dot {{ $statusDotClass }}"></span>
                    <span>{{ $currentStatusLabel }}</span>
                    <i class="fa fa-chevron-down small ms-1"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end status-filter-menu"
                    aria-labelledby="statusFilterDropdownPackages">

                    <li>
                        <a class="dropdown-item status-filter-item {{ $statusFilter === '' || $statusFilter === null ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                            <span class="label-part">
                                <span class="dot dot-all"></span> All packages
                            </span>
                            @if ($statusFilter === '' || $statusFilter === null)
                                <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter-item {{ $statusFilter === 'published' ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => 'published']) }}">
                            <span class="label-part">
                                <span class="dot dot-active"></span> Published only
                            </span>
                            @if ($statusFilter === 'published')
                                <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter-item {{ $statusFilter === 'draft' ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => 'draft']) }}">
                            <span class="label-part">
                                <span class="dot dot-inactive"></span> Draft only
                            </span>
                            @if ($statusFilter === 'draft')
                                <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        {{-- Right: search bar --}}
        <div class="toolbar-right ms-auto">
            <form method="GET" action="{{ route('admin.packages.index') }}"
                class="d-flex align-items-center gap-2 packages-search-form" role="search"
                aria-label="Search packages">

                {{-- Chip-style search bar --}}
                <div class="chip-search">
                    <input name="q" type="search" placeholder="Search packages…" aria-label="Search packages"
                        value="{{ request('q') ?? '' }}">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                        <span>Search</span>
                    </button>
                </div>

                {{-- Clear only when there is an active query --}}
                @if (request('q'))
                    <a href="{{ route('admin.packages.index') }}" class="chip-clear" aria-label="Clear search">
                        <i class="fa fa-xmark"></i>
                        <span>Clear</span>
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- LIST-VIEW TABLE WRAP --}}
    <div class="table-wrap">
        <div class="table-scroll">
            <table id="packagesTable" class="packages-table" role="table" aria-describedby="packagesTable">
                <thead>
                    <tr>
                        <th style="width:72px;">Sr.No.</th>
                        <th>Package</th>
                        <th style="width:110px;">Pricing</th>
                        <th style="width:140px;">Special</th>
                        <th style="width:160px;">Status</th>
                        <th style="min-width:160px; text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $i => $package)
                        @php
                            $rowIndex = $i + 1 + ($packages->currentPage() - 1) * $packages->perPage();
                            $title = $package->title ?? '—';
                            $status = strtolower($package->status ?? 'draft');
                            $badgeClass = $status === 'published' ? 'bg-primary text-white' : 'bg-secondary text-white';
                            $isSpecial = !empty($package->is_special) && (int) $package->is_special === 1;
                            $specialLabel = trim($package->special_label ?? '');
                        @endphp

                        <tr id="package-row-{{ $package->id }}" data-package-id="{{ $package->id }}">
                            {{-- Sr.No --}}
                            <td style="font-weight:700;">{{ $rowIndex }}</td>

                            {{-- Package cell: name + image + status label --}}
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div
                                        style="width:54px;height:54px;border-radius:10px;overflow:hidden;background:#f3f4f6;border:1px solid #e5e7eb;flex-shrink:0;">
                                        @if (!empty($package->banner))
                                            <img src="{{ asset('storage/' . $package->banner) }}" alt="banner"
                                                style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <span class="muted d-flex align-items-center justify-content-center"
                                                style="height:100%;font-size:.75rem;">No image</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="package-title" style="font-weight:700;color:var(--wc-text);">
                                            {{ $title }}
                                        </div>
                                        @if ($specialLabel)
                                            <div class="mt-1">
                                                <span class="badge bg-warning text-dark"
                                                    title="Special label">{{ e($specialLabel) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Pricing compact block --}}
                            <td>
                                <div style="font-size:.88rem;">
                                    <div>
                                        <span class="muted">MRP:</span>
                                        @if (isset($package->mrp))
                                            <strong> ₹{{ number_format($package->mrp, 2) }}</strong>
                                        @else
                                            <span class="muted"> —</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="muted">Selling:</span>
                                        @if (isset($package->discounted_price))
                                            <strong> ₹{{ number_format($package->discounted_price, 2) }}</strong>
                                        @else
                                            <span class="muted"> —</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Special --}}
                            <td>
                                @if ($isSpecial)
                                    <span class="badge bg-warning text-dark">Special</span>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>

                            {{-- Actions --}}
                            <td style="text-align:center;">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    {{-- View button (opens modal with full details) --}}
                                    <button type="button" class="icon-btn view-package-btn"
                                        data-package-id="{{ $package->id }}"
                                        data-url="{{ route('admin.packages.show', $package->id) }}"
                                        title="View Package">
                                        <i class="fa fa-eye"></i>
                                    </button>

                                    {{-- Edit button --}}
                                    <a href="{{ route('admin.packages.edit', $package->id) }}"
                                        class="icon-btn btn-edit" data-ajax="true"
                                        data-url="{{ route('admin.packages.edit', $package->id) }}"
                                        title="Edit Package">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST"
                                        class="d-inline delete-form"
                                        data-confirm="Are you sure you want to delete this package?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn btn-delete delete-btn"
                                            data-package-name="{{ e($package->title ?? 'this package') }}"
                                            title="Delete Package">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                No packages found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="pagination-row mt-2">
        <div class="d-flex justify-content-end align-items-center">
            <div>{!! $packages->appends(request()->only('q', 'status'))->links() !!}</div>
        </div>
    </div>
</div>