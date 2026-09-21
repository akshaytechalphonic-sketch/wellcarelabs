<div id="labtestAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

  @php
  $statusFilter = request('status');
  $currentStatusLabel = $statusFilter === 'published'
  ? 'Published'
  : ($statusFilter === 'draft' ? 'Draft' : 'Status');
  $statusDotClass = $statusFilter === 'published'
  ? 'dot-active'
  : ($statusFilter === 'draft' ? 'dot-inactive' : 'dot-all');
  @endphp

  {{-- Toolbar: results text (left) + status pill + search (right) --}}
  <div class="d-flex justify-content-between align-items-center mb-2 admin-fragment-toolbar"
    style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">

    {{-- LEFT: results text + status filter pill --}}
    <div class="toolbar-left d-flex align-items-center gap-3 flex-wrap">
      <div class="results-text text-muted small">
        Showing results for:
        <strong>{{ request('q') ? e(request('q')) : 'All Lab Tests' }}</strong>
      </div>

      {{-- Status filter pill (same as Packages) --}}
      <div class="dropdown status-filter-group">
        <button
          class="btn btn-status-filter dropdown-toggle"
          type="button"
          id="statusFilterDropdownLabtests"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          <span class="dot {{ $statusDotClass }}"></span>
          <span>{{ $currentStatusLabel }}</span>
          <i class="fa fa-chevron-down small ms-1"></i>
        </button>

        <ul class="dropdown-menu dropdown-menu-end status-filter-menu"
          aria-labelledby="statusFilterDropdownLabtests">

          <li>
            <a class="dropdown-item status-filter-item {{ $statusFilter === '' || $statusFilter === null ? 'active fw-bold' : '' }}"
              href="{{ request()->fullUrlWithQuery(['status'=>'']) }}">
              <span class="label-part">
                <span class="dot dot-all"></span> All tests
              </span>
              @if($statusFilter === '' || $statusFilter === null)
              <i class="fa fa-check checkmark"></i>
              @endif
            </a>
          </li>

          <li>
            <a class="dropdown-item status-filter-item {{ $statusFilter === 'published' ? 'active fw-bold' : '' }}"
              href="{{ request()->fullUrlWithQuery(['status'=>'published']) }}">
              <span class="label-part">
                <span class="dot dot-active"></span> Published only
              </span>
              @if($statusFilter === 'published')
              <i class="fa fa-check checkmark"></i>
              @endif
            </a>
          </li>

          <li>
            <a class="dropdown-item status-filter-item {{ $statusFilter === 'draft' ? 'active fw-bold' : '' }}"
              href="{{ request()->fullUrlWithQuery(['status'=>'draft']) }}">
              <span class="label-part">
                <span class="dot dot-inactive"></span> Draft only
              </span>
              @if($statusFilter === 'draft')
              <i class="fa fa-check checkmark"></i>
              @endif
            </a>
          </li>

        </ul>
      </div>
    </div>

    {{-- RIGHT: chip-style Search --}}
    <div class="toolbar-right ms-auto">
      <form method="GET"
        action="{{ route('admin.labtests.index') }}"
        class="d-flex align-items-center gap-2 labtests-search-form"
        role="search"
        aria-label="Search lab tests">

        <div class="chip-search">
          <input name="q"
            type="search"
            placeholder="Search test name..."
            aria-label="Search by test name"
            value="{{ request('q') ?? '' }}">
          <button type="submit">
            <i class="fa fa-search"></i>
            <span>Search</span>
          </button>
        </div>

        @if(request('q'))
        <a href="{{ route('admin.labtests.index') }}"
          class="chip-clear"
          aria-label="Clear search">
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
      <table class="labtests-table" role="table" aria-describedby="labtestsTable">
        <thead>
          <tr>
            <th style="width:72px;">Sr.No.</th>
            <th>Test Name</th>
            <th style="width:110px;">MRP</th>
            <th style="width:110px;">B2B</th>
            <th style="width:110px;">Discounted</th>
            <th style="width:160px;">Status</th>
            <th style="min-width:160px; text-align:center;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tests as $i => $test)
          <tr id="test-row-{{ $test->id }}">
            <td style="font-weight:700;">
              {{ $i + 1 + (($tests->currentPage() - 1) * $tests->perPage()) }}
            </td>

            <td class="test-name" style="font-weight:600;color:var(--wc-text);">
              {{ $test->test_name ?? $test->name ?? $test->title ?? '—' }}
            </td>

            {{-- MRP --}}
            <td>
              @if(is_null($test->mrp))
              <span class="muted">—</span>
              @else
              ₹{{ number_format($test->mrp, 2) }}
              @endif
            </td>

            {{-- B2B --}}
            <td>
              @php $b2bVal = $test->b2b ?? $test->b2b_price ?? null; @endphp
              @if(is_null($b2bVal))
              <span class="muted">—</span>
              @else
              ₹{{ number_format($b2bVal, 2) }}
              @endif
            </td>

            {{-- Discounted --}}
            <td>
              @if(is_null($test->discounted_price))
              <span class="muted">—</span>
              @else
              ₹{{ number_format($test->discounted_price, 2) }}
              @endif
            </td>

            {{-- Status cell --}}
            <td>
              @php
              $status = strtolower($test->status ?? 'draft');
              $badgeClass = $status === 'published' ? 'bg-primary text-white' : 'bg-secondary text-white';
              @endphp
              <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
            </td>

            {{-- Actions --}}
            <td style="text-align:center;">
              <div class="d-flex justify-content-center align-items-center gap-2">
                {{-- View button (opens modal with full details) --}}
                <button type="button"
                  class="icon-btn view-test-btn"
                  data-test-id="{{ $test->id }}"
                  data-url="{{ route('admin.labtests.show', $test->id) }}"
                  title="View Test">
                  <i class="fa fa-eye"></i>
                </button>

                {{-- Edit --}}
                <a href="{{ route('admin.labtests.edit', $test->id) }}"
                  class="icon-btn btn-edit"
                  data-ajax="true"
                  data-url="{{ route('admin.labtests.edit', $test->id) }}"
                  title="Edit Test">
                  <i class="fa fa-edit"></i>
                </a>

                {{-- Delete --}}
                <form action="{{ route('admin.labtests.destroy', $test->id) }}"
                  method="POST"
                  class="d-inline delete-form"
                  data-confirm="Are you sure you want to delete this test?">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="icon-btn btn-delete delete-btn"
                    data-test-name="{{ e($test->test_name ?? $test->name ?? $test->title ?? 'this test') }}"
                    title="Delete Test">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-3">No tests found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pagination: right-aligned and tight below table --}}
  <div class="pagination-row mt-2">
    <div class="d-flex justify-content-end align-items-center">
      <div>{!! $tests->appends(request()->only('q','status'))->links() !!}</div>
    </div>
  </div>
</div>