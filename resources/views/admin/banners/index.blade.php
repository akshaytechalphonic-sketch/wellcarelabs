{{-- resources/views/admin/banners/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Banners')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

{{-- External CSS --}}
<link href="{{ asset('Back_end/css/banners/banner.css') }}" rel="stylesheet">

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom bg-white">
      <div>
        <h5 class="panel-title mb-0">Banners</h5>
        <small class="text-muted">Manage site hero banners (slider)</small>
      </div>

      <div class="d-flex gap-2 flex-wrap justify-content-end">
        <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-create-primary">
          <i class="fa fa-plus"></i>
          <span>Create Banner</span>
        </a>
      </div>
    </div>

    {{-- Body --}}
    <div class="card-body p-2 pt-1" style="margin-top:-6px;">
      <div id="bannerAdminBody" class="admin-fragment p-3" data-loaded-url="{{ url()->current() }}">

        @php
          $statusFilter = request('status');
          $currentStatusLabel = $statusFilter === 'active'
              ? 'Active'
              : ($statusFilter === 'inactive' ? 'Inactive' : 'Status');
          $statusDotClass = $statusFilter === 'active'
              ? 'dot-active'
              : ($statusFilter === 'inactive' ? 'dot-inactive' : 'dot-all');
        @endphp

        {{-- Toolbar: results text + status filter pill (same pattern as labtests/coupons) --}}
        <div class="d-flex justify-content-between align-items-center mb-2 admin-fragment-toolbar"
             style="border-bottom:1px solid #eef2f6;padding-bottom:8px;">

          <div class="toolbar-left d-flex align-items-center gap-3 flex-wrap">
            <div class="results-text text-muted small">
              Showing:
              @if($statusFilter === 'active')
                <strong>Active Banners</strong>
              @elseif($statusFilter === 'inactive')
                <strong>Inactive Banners</strong>
              @else
                <strong>All Banners</strong>
              @endif
            </div>

            {{-- Status filter pill --}}
            <div class="dropdown status-filter-group">
              <button
                class="btn btn-status-filter dropdown-toggle"
                type="button"
                id="statusFilterDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <span class="dot {{ $statusDotClass }}"></span>
                <span>{{ $currentStatusLabel }}</span>
                <i class="fa fa-chevron-down small ms-1"></i>
              </button>

              <ul class="dropdown-menu dropdown-menu-end status-filter-menu" aria-labelledby="statusFilterDropdown">
                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === null || $statusFilter === '' ? 'active fw-semibold' : '' }}"
                     href="{{ request()->fullUrlWithQuery(['status' => '']) }}">
                    <span class="label-part">
                      <span class="dot dot-all"></span>
                      <span>All banners</span>
                    </span>
                    @if($statusFilter === null || $statusFilter === '')
                      <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>
                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === 'active' ? 'active fw-semibold' : '' }}"
                     href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}">
                    <span class="label-part">
                      <span class="dot dot-active"></span>
                      <span>Active only</span>
                    </span>
                    @if($statusFilter === 'active')
                      <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>
                <li>
                  <a class="dropdown-item status-filter-item {{ $statusFilter === 'inactive' ? 'active fw-semibold' : '' }}"
                     href="{{ request()->fullUrlWithQuery(['status' => 'inactive']) }}">
                    <span class="label-part">
                      <span class="dot dot-inactive"></span>
                      <span>Inactive only</span>
                    </span>
                    @if($statusFilter === 'inactive')
                      <i class="fa fa-check checkmark"></i>
                    @endif
                  </a>
                </li>
              </ul>
            </div>
          </div>

        </div>

        {{-- LIST VIEW TABLE WRAP --}}
        <div class="table-wrap">
          <div class="table-scroll">
            <table class="banners-table" aria-label="Banners table">
              <thead>
                <tr>
                  <th class="text-center" style="width:60px; id=banner-srno-header">
                    Sr.No.
                  </th>
                  <th class="text-center">Preview</th>
                  <th class="text-center">Status</th>
                  <th class="text-center" style="min-width:160px;">Actions</th>
                </tr>
              </thead>

              <tbody id="banners-tbody">
                @forelse($banners as $i => $banner)
                  <tr id="banner-row-{{ $banner->id }}">
                    {{-- Sr.No. --}}
                    <td class="text-center fw-semibold banner-srno">
                      @if($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $banners->firstItem() + $i }}
                      @else
                        {{ $loop->iteration }}
                      @endif
                    </td>

                    {{-- Preview --}}
                    <td class="text-center">
                      @if(!empty($banner->image))
                        <img src="{{ asset('storage/'.$banner->image) }}"
                             alt="banner-{{ $banner->id }}"
                             style="height:48px; border-radius:6px; object-fit:cover;">
                      @else
                        <span class="text-muted">No image</span>
                      @endif
                    </td>

                    {{-- Status toggle --}}
                    <td class="text-center">
                      <div class="d-inline-flex align-items-center gap-2">
                        <button
                          type="button"
                          class="badge-status-toggle {{ $banner->is_active ? 'is-on' : '' }}"
                          data-id="{{ $banner->id }}"
                          data-active="{{ $banner->is_active ? 1 : 0 }}"
                          data-url="{{ route('admin.banners.toggle-status', $banner->id) }}"
                          aria-label="Toggle banner status"
                        ></button>
                        <span class="status-text fw-semibold {{ $banner->is_active ? 'text-success' : 'text-muted' }}">
                          {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                      </div>
                    </td>

                    {{-- Actions --}}
                    <td class="text-center">
                      <div class="d-flex justify-content-center align-items-center gap-2">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}"
                           class="icon-btn btn-edit"
                           data-ajax="true"
                           data-url="{{ route('admin.banners.edit', $banner->id) }}"
                           title="Edit Banner">
                          <i class="fa fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.banners.destroy', $banner->id) }}"
                              method="POST"
                              class="d-inline delete-form"
                              data-confirm="Are you sure you want to delete this banner?">
                          @csrf
                          @method('DELETE')
                          <button type="submit"
                                  class="icon-btn btn-delete delete-btn"
                                  data-banner-title="{{ e($banner->title ?? 'this banner') }}"
                                  title="Delete Banner">
                            <i class="fa fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3">No banners found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- Pagination --}}
        @if($banners instanceof \Illuminate\Pagination\LengthAwarePaginator)
          <div class="pagination-row mt-2">
            <div class="d-flex justify-content-end align-items-center">
              <div>{!! $banners->appends(request()->only('status'))->links() !!}</div>
            </div>
          </div>
        @endif

      </div> {{-- /bannerAdminBody --}}
    </div>
  </div>
</div>

{{-- External JS --}}
<script src="{{ asset('Back_end/js/banners/banner.js') }}"></script>
@endsection