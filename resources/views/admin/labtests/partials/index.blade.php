@extends('layouts.app')

@section('title', 'Manage Tests')

@section('content')
<div class="admin-fragment card">
  <div class="card-header d-flex justify-content-between align-items-center admin-fragment-toolbar">
    <div>
      <h5 class="mb-0">Tests</h5>
      <small class="text-muted">Manage lab tests</small>
    </div>

  <div class="card-body p-0">
    {{-- include list partial which contains the flash area --}}
    @includeWhen(view()->exists('admin.labtests.partials.list'), 'admin.labtests.partials.list', ['tests' => $tests])
  </div>
</div>
@endsection
