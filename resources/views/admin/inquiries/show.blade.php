{{-- resources/views/admin/inquiries/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Inquiry #' . ($inquiry->id ?? ''))

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous">

@php
  $status = strtolower($inquiry->status ?? 'pending');
  $statusColor = match($status) {
    'pending' => '#f5b800',
    'called' => '#0b6b99',
    'interested' => '#0b7b4f',
    'booked' => '#0b7b99',
    'lost' => '#b71c1c',
    default => '#6b7280'
  };
@endphp

<style>
  :root{
    --card-radius:14px;
    --glass-bg: linear-gradient(180deg, rgba(255,255,255,0.78), rgba(250,250,252,0.6));
    --wc-border: #e7eef2;
    --wc-shadow: 0 10px 30px rgba(15,38,34,0.04);
    --muted: #6b7280;
    --text: #0f1724;
    --max-w: 950px;
  }

  .admin-page-wrapper { max-width: var(--max-w); margin: 24px auto; padding: 18px; }

  .glass-card {
    border-radius: var(--card-radius);
    background: var(--glass-bg);
    border: 1px solid var(--wc-border);
    box-shadow: var(--wc-shadow);
    overflow: hidden;
  }

  .glass-header {
    display:flex; justify-content:space-between; align-items:center;
    padding:16px 20px; border-bottom:1px solid rgba(6,12,30,0.03);
    background: linear-gradient(90deg, rgba(255,255,255,0.05), rgba(255,255,255,0));
  }
  .h-title { font-size:1.05rem; font-weight:800; color:var(--text); margin:0; }
  .h-sub { color:var(--muted); font-size:.9rem; margin-top:3px; }

  .status-pill {
    display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:999px; font-weight:700;
    background:rgba(255,255,255,0.92); border:1px solid rgba(6,12,30,0.05);
  }
  .status-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }

  .back-link {
    text-decoration:none; color:var(--text);
    border:1px solid rgba(6,12,30,0.06); padding:8px 12px; border-radius:8px; background:#fff;
  }

  .details-table { width:100%; padding:22px; }
  .detail-row {
    display:grid; grid-template-columns: 220px 1fr; gap:12px;
    padding:12px 0; border-bottom:1px solid rgba(0,0,0,0.04);
  }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { color:var(--muted); font-weight:700; }
  .detail-value { color:var(--text); font-weight:600; word-break:break-word; }

  @media (max-width:700px){
    .detail-row { grid-template-columns: 1fr; }
    .detail-label { margin-bottom:6px; }
  }
</style>

<div class="admin-page-wrapper">
  <div class="glass-card" role="region" aria-label="Inquiry details">
    <div class="glass-header">
      <div>
        <h2 class="h-title">Inquiry Details</h2>
        <div class="h-sub">Submitted {{ $inquiry->created_at ? \Carbon\Carbon::parse($inquiry->created_at)->diffForHumans() : '-' }}</div>
      </div>

      <div style="display:flex; gap:10px; align-items:center;">
        <div class="status-pill" aria-hidden="true">
          <span class="status-dot" style="background: {{ $statusColor }};"></span>
          <span style="text-transform:capitalize;">{{ $status ?? 'pending' }}</span>
        </div>

        <a href="{{ route('admin.inquiries.index') }}" class="back-link">Back</a>
      </div>
    </div>

    <div class="details-table" role="table" aria-label="Inquiry fields">
      <div class="detail-row" role="row">
        <div class="detail-label">Name</div>
        <div class="detail-value">{{ $inquiry->name ?? '-' }}</div>
      </div>

      <div class="detail-row" role="row">
        <div class="detail-label">Email</div>
        <div class="detail-value">
          @if($inquiry->email)
            <a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a>
          @else
            -
          @endif
        </div>
      </div>

      <div class="detail-row" role="row">
        <div class="detail-label">Phone</div>
        <div class="detail-value">
          @if($inquiry->phone)
            <a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a>
          @else
            -
          @endif
        </div>
      </div>

      <div class="detail-row" role="row">
        <div class="detail-label">Submitted</div>
        <div class="detail-value">{{ $inquiry->created_at ? \Carbon\Carbon::parse($inquiry->created_at)->format('d M Y h:i A') : '-' }}</div>
      </div>

      <div class="detail-row" role="row">
        <div class="detail-label">Status</div>
        <div class="detail-value" style="text-transform:capitalize;">{{ $inquiry->status ?? 'pending' }}</div>
      </div>

      @if($inquiry->prescription)
        <div class="detail-row" role="row">
          <div class="detail-label">Prescription</div>
          <div class="detail-value">
            <a href="{{ asset('storage/' . $inquiry->prescription) }}" target="_blank" style="text-decoration:none; color:#0d6efd; display:inline-flex; align-items:center; gap:6px; font-weight:700;">
              <i class="fa-solid fa-file-medical"></i> View / Download Prescription
            </a>
          </div>
        </div>
      @endif

      <div class="detail-row" role="row">
        <div class="detail-label">Message</div>
        <div class="detail-value" style="white-space:pre-wrap;">{{ $inquiry->message ?? '—' }}</div>
      </div>

      @if(method_exists($inquiry, 'user') && $inquiry->relationLoaded('user') && $inquiry->user)
        <div class="detail-row" role="row">
          <div class="detail-label">User</div>
          <div class="detail-value">{{ $inquiry->user->name ?? '-' }} ({{ $inquiry->user->email ?? '-' }})</div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
