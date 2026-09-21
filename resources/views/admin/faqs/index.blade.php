{{-- resources/views/admin/faqs/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - FAQs')

@section('content')
<style>
  /* Same as packages index wrapper */
  .admin-page-wrapper {
    margin: 20px 22px 25px 22px;
  }

  .admin-page-card-flush {
    margin-left: -18px;
    margin-right: -18px;
    width: calc(100% + 36px);
    border-radius: 10px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
  }

  @media (max-width: 768px) {
    .admin-page-wrapper {
      margin: 14px 12px 18px 12px;
    }
    .admin-page-card-flush {
      margin-left: 0;
      margin-right: 0;
      width: 100%;
    }
  }

  .panel-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: var(--text, #0b2b3a);
    line-height: 1.1;
  }

  /* Create FAQ button like packages */
  .create-faq-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 999px;
    background: linear-gradient(
      90deg,
      var(--wc-accent, #0f9d80),
      var(--wc-accent-2, #1fb28a)
    );
    color: #fff !important;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(15, 157, 128, 0.12);
    transition: all 0.15s ease;
    border: none;
    white-space: nowrap;
  }

  .create-faq-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(15, 157, 128, 0.22);
    text-decoration: none;
    color: #fff !important;
  }

  .create-faq-btn i {
    font-size: 14px;
    line-height: 0;
  }
</style>

<div class="admin-page-wrapper">
  <div class="card shadow-sm admin-page-card-flush">

    {{-- Header --}}
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2 border-bottom bg-white">
      <div>
        <h5 class="panel-title mb-0">FAQs</h5>
        <small class="text-muted">Manage frequently asked questions</small>
      </div>

      <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
        <a href="{{ route('admin.faqs.create') }}" class="create-faq-btn" aria-label="Create new FAQ">
          <i class="fa-solid fa-plus"></i>
          <span>Add FAQ</span>
        </a>
      </div>
    </div>

    {{-- Body --}}
    <div class="card-body p-2 pt-1" style="margin-top:-6px;">
      <div id="faqsListWrapper">
        @include('admin.faqs.fragment', ['faqs' => $faqs])
      </div>
    </div>

  </div>
</div>
@endsection
