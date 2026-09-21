{{-- resources/views/admin/faqs/fragment.blade.php --}}

<style>
    :root {
        --wc-text: #0f1724;
        --wc-muted: #5f6b6b;
        --wc-border: #e7eef2;
        --wc-accent: #0f9d80;
    }

    /* Chip-style search */
    .chip-search {
        display: flex;
        align-items: center;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid var(--wc-border);
        padding: 2px;
        min-width: 260px;
        max-width: 360px;
    }

    .chip-search input[type="search"] {
        border: none;
        outline: none;
        padding: 8px 10px;
        border-radius: 999px;
        flex: 1;
        font-size: 0.92rem;
    }

    .chip-search button {
        border: 1px solid transparent;
        outline: none;
        border-radius: 999px;
        padding: 7px 16px;
        background: var(--wc-accent);
        color: #fff;
        font-weight: 500;
        font-size: .85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.16);
        transition: background .15s ease, box-shadow .15s ease, transform .1s ease, opacity .15s ease;
    }

    .chip-search button:hover {
        background: #0c7c65;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.2);
        transform: translateY(-1px);
    }

    /* Clear button */
    .chip-clear {
        border-radius: 999px;
        border: 1px solid #fecaca;
        background: #fef2f2;
        padding: 7px 12px;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #b91c1c;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s ease, border-color .15s ease, color .15s ease,
            transform .1s ease, box-shadow .15s ease;
    }

    .chip-clear:hover {
        background: #fee2e2;
        border-color: #f97373;
        color: #991b1b;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(248, 113, 113, 0.28);
    }

    /* Status filter pill */
    .status-filter-group {
        position: relative;
    }

    .btn-status-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.82rem;
        font-weight: 500;
        color: #0f172a;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
        transition: box-shadow .15s ease, transform .1s ease, border-color .15s ease, background .15s ease;
    }

    .btn-status-filter .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        display: inline-block;
    }

    .btn-status-filter .dot-all { background: #9ca3af; }
    .btn-status-filter .dot-active { background: #10b981; }
    .btn-status-filter .dot-inactive { background: #9ca3af; }

    .btn-status-filter:hover {
        transform: translateY(-1px);
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        background: #f9fafb;
    }

    .status-filter-menu {
        min-width: 220px;
        padding: 4px 0;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
    }

    .status-filter-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: 0.85rem;
        padding: 6px 12px;
    }

    .status-filter-item .label-part {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-filter-item .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
    }

    .status-filter-item .dot-all { background: #9ca3af; }
    .status-filter-item .dot-active { background: #10b981; }
    .status-filter-item .dot-inactive { background: #9ca3af; }

    .status-filter-item .checkmark {
        font-size: 0.75rem;
        color: #16a34a;
    }

    .status-filter-item.active,
    .status-filter-item:hover {
        background-color: #f1f5f9;
    }

    /* Table wrap */
    .table-wrap {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--wc-border);
        box-shadow: 0 8px 24px rgba(2, 6, 23, 0.03);
        background: #fff;
    }

    .table-scroll {
        overflow: auto;
        -webkit-overflow-scrolling: touch;
    }

    table.faqs-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 860px;
        font-size: 0.95rem;
    }

    table.faqs-table thead th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        color: var(--wc-text);
        border-bottom: 1px solid var(--wc-border);
        white-space: nowrap;
        background-color: #f8f9fa;
    }

    table.faqs-table tbody td {
        padding: 12px 16px;
        border-bottom: 1px solid var(--wc-border);
        vertical-align: middle;
        color: var(--wc-text);
    }

    table.faqs-table tbody tr:hover {
        background: rgba(15, 157, 128, 0.03);
        transform: translateY(-2px);
    }

    .muted {
        color: var(--wc-muted);
        font-size: .92rem;
    }

    /* Icon action buttons */
    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.5);
        background: #f9fafb;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.10);
        text-decoration: none;
        color: var(--wc-text);
        transition: transform .15s ease, box-shadow .15s ease,
            background .15s ease, border-color .15s ease, color .15s ease;
        padding: 0;
    }

    .icon-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.16);
        border-color: #cbd5e1;
        background: #ffffff;
    }

    .btn-edit {
        color: #0f9d80;
        background: rgba(15, 157, 128, 0.06);
        border-color: rgba(34, 197, 94, 0.35);
    }

    .btn-edit:hover {
        background: #0f9d80;
        color: #fff;
        border-color: #0f9d80;
    }

    .btn-delete {
        color: #b91c1c;
        background: rgba(248, 113, 113, 0.06);
        border-color: rgba(239, 68, 68, 0.38);
    }

    .btn-delete:hover {
        background: #b91c1c;
        color: #fff;
        border-color: #b91c1c;
    }

    /* Toggle pill button */
    .toggle-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform .1s ease, box-shadow .15s ease, background .15s ease, border-color .15s ease;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
        white-space: nowrap;
    }

    .toggle-pill:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.12);
        background: #f9fafb;
        border-color: #cbd5e1;
    }

    .toggle-pill .dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        display: inline-block;
    }

    .toggle-pill.active .dot { background: #10b981; }
    .toggle-pill.inactive .dot { background: #9ca3af; }

    @media (max-width: 640px) {
        .admin-fragment-toolbar { row-gap: 8px; }
        .toolbar-left { gap: 8px; }
        .toolbar-right { margin-top: 4px; }
        .chip-search { width: 100%; max-width: none; }
    }

    .admin-fragment-toolbar { flex-wrap: wrap; }
</style>

<div id="faqAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

    @php
        $statusFilter = request('status'); // active / inactive / null
        $currentStatusLabel =
            $statusFilter === 'active' ? 'Active' : ($statusFilter === 'inactive' ? 'Inactive' : 'Status');

        $statusDotClass =
            $statusFilter === 'active' ? 'dot-active' : ($statusFilter === 'inactive' ? 'dot-inactive' : 'dot-all');
    @endphp

    {{-- Toolbar --}}
    <div class="d-flex justify-content-between align-items-center mb-2 admin-fragment-toolbar"
        style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">

        {{-- Left --}}
        <div class="toolbar-left d-flex align-items-center gap-3 flex-wrap">
            <div class="results-text text-muted small">
                Showing results for:
                <strong>{{ request('q') ? e(request('q')) : 'All FAQs' }}</strong>
            </div>

            {{-- Status Filter --}}
            <div class="dropdown status-filter-group">
                <button class="btn btn-status-filter dropdown-toggle" type="button" id="statusFilterDropdownFaqs"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="dot {{ $statusDotClass }}"></span>
                    <span>{{ $currentStatusLabel }}</span>
                    <i class="fa fa-chevron-down small ms-1"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end status-filter-menu"
                    aria-labelledby="statusFilterDropdownFaqs">

                    <li>
                        <a class="dropdown-item status-filter-item {{ $statusFilter === '' || $statusFilter === null ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                            <span class="label-part">
                                <span class="dot dot-all"></span> All FAQs
                            </span>
                            @if ($statusFilter === '' || $statusFilter === null)
                                <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter-item {{ $statusFilter === 'active' ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">
                            <span class="label-part">
                                <span class="dot dot-active"></span> Active only
                            </span>
                            @if ($statusFilter === 'active')
                                <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item status-filter-item {{ $statusFilter === 'inactive' ? 'active fw-bold' : '' }}"
                            href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}">
                            <span class="label-part">
                                <span class="dot dot-inactive"></span> Inactive only
                            </span>
                            @if ($statusFilter === 'inactive')
                                <i class="fa fa-check checkmark"></i>
                            @endif
                        </a>
                    </li>

                </ul>
            </div>
        </div>

        {{-- Right --}}
        <div class="toolbar-right ms-auto">
            <form method="GET" action="{{ route('admin.faqs.index') }}"
                class="d-flex align-items-center gap-2 faqs-search-form" role="search"
                aria-label="Search FAQs">

                <div class="chip-search">
                    <input name="q" type="search" placeholder="Search FAQs…" aria-label="Search FAQs"
                        value="{{ request('q') ?? '' }}">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                        <span>Search</span>
                    </button>
                </div>

                @if (request('q') || request('status'))
                    <a href="{{ route('admin.faqs.index') }}" class="chip-clear" aria-label="Clear search">
                        <i class="fa fa-xmark"></i>
                        <span>Clear</span>
                    </a>
                @endif
            </form>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrap">
        <div class="table-scroll">
            <table id="faqsTable" class="faqs-table" role="table" aria-describedby="faqsTable">
                <thead>
                    <tr>
                        <th style="width:72px;">Sr.No.</th>
                        <th>Question</th>
                        <th style="width:100px;">Sort</th>
                        <th style="width:160px;">Status</th>
                        <th style="min-width:180px; text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($faqs as $i => $faq)
                        @php
                            $rowIndex = $i + 1 + ($faqs->currentPage() - 1) * $faqs->perPage();
                            $isActive = (int) ($faq->is_active ?? 0) === 1;
                        @endphp

                        <tr id="faq-row-{{ $faq->id }}" data-faq-id="{{ $faq->id }}">
                            <td style="font-weight:700;">{{ $rowIndex }}</td>

                            <td>
                                <div style="font-weight:700;color:var(--wc-text);">
                                    {{ $faq->question ?? '—' }}
                                </div>
                                <div class="muted mt-1">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($faq->answer ?? ''), 80) }}
                                </div>
                            </td>

                            <td>{{ $faq->sort_order ?? 0 }}</td>

                            <td>
                                <form action="{{ route('admin.faqs.toggle', $faq->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="toggle-pill {{ $isActive ? 'active' : 'inactive' }}"
                                        title="Click to toggle status">
                                        <span class="dot"></span>
                                        <span>{{ $isActive ? 'Active' : 'Inactive' }}</span>
                                    </button>
                                </form>
                            </td>

                            <td style="text-align:center;">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                        class="icon-btn btn-edit"
                                        title="Edit FAQ">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-btn btn-delete" title="Delete FAQ">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                No FAQs found
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
            <div>{!! $faqs->appends(request()->only('q', 'status'))->links() !!}</div>
        </div>
    </div>
</div>
