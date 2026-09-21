@extends('layouts.app')

@section('title', 'Dashboard - Coupons')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- External CSS --}}
<link href="{{ asset('Back_end/css/coupons/coupons.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">

    {{-- Header --}}
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
      <div>
        <h5 class="panel-title mb-0">Coupons</h5>
        <small class="text-muted">Manage discount codes</small>
      </div>

      <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-sm btn-create-primary">
          <i class="fa fa-ticket"></i>
          <span>Create Coupon</span>
        </a>
      </div>
    </div>

    {{-- Body --}}
    <div class="card-body p-2 pt-1" style="margin-top:-6px;">

      <div id="couponAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

        @php
        $statusFilter = request('status');
        $currentStatusLabel = match ($statusFilter) {
        'live' => 'Live',
        'scheduled' => 'Scheduled',
        'expired' => 'Expired',
        'inactive' => 'Inactive',
        default => 'Status',
        };
        $statusDotClass = match ($statusFilter) {
        'live' => 'dot-live',
        'scheduled' => 'dot-scheduled',
        'expired' => 'dot-expired',
        'inactive' => 'dot-inactive',
        default => 'dot-all',
        };
        @endphp

        {{-- Toolbar: results text + STATUS FILTER PILL + search --}}
        <div class="d-flex justify-content-between align-items-center mb-2 admin-fragment-toolbar"
          style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">

          {{-- LEFT: results text + status filter pill --}}
          <div class="toolbar-left d-flex align-items-center gap-3 flex-wrap">
            <div class="results-text text-muted small">
              Showing results for:
              <strong>{{ request('q') ? e(request('q')) : 'All Coupons' }}</strong>
            </div>

            {{-- Status filter pill --}}
            <div class="dropdown status-filter-group">
              <button
                class="btn btn-status-filter dropdown-toggle"
                type="button"
                id="statusFilterDropdownCoupons"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <span class="dot {{ $statusDotClass }}"></span>
                <span>{{ $currentStatusLabel }}</span>
                <i class="fa fa-chevron-down small ms-1"></i>
              </button>

              <ul class="dropdown-menu dropdown-menu-end status-filter-menu"
                aria-labelledby="statusFilterDropdownCoupons">

                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === '' || $statusFilter === null ? 'active fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['status'=>'']) }}">
                    <span class="label-part">
                      <span class="dot dot-all"></span> All coupons
                    </span>
                    @if($statusFilter === '' || $statusFilter === null)
                    <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>

                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === 'live' ? 'active fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['status'=>'live']) }}">
                    <span class="label-part">
                      <span class="dot dot-live"></span> Live only
                    </span>
                    @if($statusFilter === 'live')
                    <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>

                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === 'scheduled' ? 'active fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['status'=>'scheduled']) }}">
                    <span class="label-part">
                      <span class="dot dot-scheduled"></span> Scheduled only
                    </span>
                    @if($statusFilter === 'scheduled')
                    <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>

                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === 'expired' ? 'active fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['status'=>'expired']) }}">
                    <span class="label-part">
                      <span class="dot dot-expired"></span> Expired only
                    </span>
                    @if($statusFilter === 'expired')
                    <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>

                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === 'inactive' ? 'active fw-bold' : '' }}"
                    href="{{ request()->fullUrlWithQuery(['status'=>'inactive']) }}">
                    <span class="label-part">
                      <span class="dot dot-inactive"></span> Inactive only
                    </span>
                    @if($statusFilter === 'inactive')
                    <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>

              </ul>
            </div>
          </div>

          {{-- RIGHT: chip-style Search --}}
          <div class="toolbar-right ms-auto d-flex align-items-center gap-2">
            <form method="GET"
              action="{{ route('admin.coupons.index') }}"
              class="d-flex align-items-center gap-2"
              role="search"
              aria-label="Search coupons">
              {{-- chip search pill --}}
              <div class="chip-search">
                <input name="q"
                  type="search"
                  placeholder="Search code, status..."
                  aria-label="Search coupons"
                  value="{{ request('q') ?? '' }}">
                <button type="submit">
                  <i class="fa fa-search"></i>
                  <span>Search</span>
                </button>
              </div>

              {{-- Keep current status when searching --}}
              @if(request()->has('status'))
              <input type="hidden" name="status" value="{{ request('status') }}">
              @endif
            </form>
          </div>
        </div>

        {{-- LIST VIEW TABLE WRAP --}}
        <div class="table-wrap">
          <div class="table-scroll">
            <table id="couponsTable" class="coupons-table" aria-label="Coupons table">
              <thead>
                <tr>
                  <th style="width:72px;">Sr.No.</th>
                  <th>Code</th>
                  <th style="width:120px;">Type</th>
                  <th style="width:140px;" class="text-end">Value</th>
                  <th style="width:140px;" class="text-end">Min Order</th>
                  <th style="width:120px;">Usage</th>
                  {{-- Simple Status header – filter is in toolbar --}}
                  <th style="width:190px;">Status</th>
                  <th style="min-width:200px;">Window</th>
                  <th style="min-width:140px; text-align:center;">Actions</th>
                </tr>
              </thead>

              <tbody>
                @forelse($coupons as $i => $c)
                @php
                // Usage count
                $used = method_exists($c, 'usages') ? $c->usages()->sum('quantity') : 0;

                // Status via model accessor
                $status = $c->computed_status; // 'live' | 'scheduled' | 'expired' | 'inactive'
                $badgeClass = match ($status) {
                'live' => 'bg-success',
                'scheduled' => 'bg-warning text-dark',
                'expired' => 'bg-danger',
                'inactive' => 'bg-secondary',
                default => 'bg-secondary',
                };

                // Window timezone
                $tz = config('app.timezone', 'Asia/Kolkata');
                @endphp

                <tr id="coupon-row-{{ $c->id }}" data-coupon-id="{{ $c->id }}">
                  {{-- Sr.No. using paginator offset --}}
                  <td style="font-weight:700;">
                    {{ ($coupons->firstItem() ?? 1) + $loop->index }}
                  </td>

                  <td class="coupon-code fw-semibold">
                    {{ $c->code }}
                  </td>

                  <td>
                    @if($c->type === 'percent')
                    <span class="badge bg-info">PERCENT</span>
                    @else
                    <span class="badge bg-primary">FIXED</span>
                    @endif
                  </td>

                  <td class="text-end">
                    @if($c->type === 'percent')
                    {{ number_format($c->value, 2) }}%
                    @else
                    ₹{{ number_format($c->value, 2) }}
                    @endif
                  </td>

                  <td class="text-end">₹{{ number_format($c->min_order_amount ?? 0, 2) }}</td>
                  <td>{{ $used }} / {{ $c->usage_limit ?? '∞' }}</td>

                  {{-- Status badge (from computed_status) --}}
                  <td>
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                  </td>

                  {{-- Window (dates rendered in app timezone) --}}
                  <td>
                    {{ optional($c->starts_at?->timezone($tz))->format('d M Y, h:i A') ?? '—' }}
                    &nbsp;→&nbsp;
                    {{ optional($c->expires_at?->timezone($tz))->format('d M Y, h:i A') ?? '—' }}
                  </td>

                  <td style="text-align:center;">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      {{-- Edit --}}
                      <a href="{{ route('admin.coupons.edit',$c) }}"
                        class="icon-btn btn-edit"
                        data-ajax="true"
                        data-url="{{ route('admin.coupons.edit',$c) }}"
                        title="Edit Coupon">
                        <i class="fa fa-edit"></i>
                      </a>

                      {{-- Delete --}}
                      <form action="{{ route('admin.coupons.destroy',$c) }}"
                        method="POST"
                        class="d-inline delete-form"
                        data-confirm="Are you sure you want to delete coupon {{ e($c->code) }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                          class="icon-btn btn-delete delete-btn"
                          title="Delete Coupon">
                          <i class="fa fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="10" class="text-center text-muted py-3">
                    No coupons found
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
            <div>{!! $coupons->appends(request()->only('q','status'))->links() !!}</div>
          </div>
        </div>

      </div> {{-- /couponAdminBody --}}
    </div>
  </div>
</div>

{{-- External JS --}}
<script src="{{ asset('Back_end/js/coupons/coupons.js') }}"></script>
@endsection