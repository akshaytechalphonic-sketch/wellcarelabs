{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Notifications')

@section('content')
<div class="container-fluid py-4 notifications-page-wrapper">
  <div class="row">
    <div class="col-12">

      {{-- Page header --}}
      <div class="card mb-3 border-0 shadow-sm wc-notification-header-card">
        <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2 py-3">
          <div>
            <h4 class="mb-1 fw-semibold wc-page-title">Notifications</h4>
            <p class="mb-0 text-muted small">
              Stay on top of appointment bookings, coupons and system updates.
            </p>
          </div>

          <div class="d-flex flex-wrap align-items-center gap-2">
            @php
            $unreadCount = $all->whereNull('read_at')->count();
            @endphp

            <span class="badge rounded-pill wc-unread-pill">
              Unread: {{ $unreadCount }}
            </span>

            {{-- Read filter (All / Unread) --}}
            <div class="wc-filter-tabs">
              <button class="wc-filter-tab active" data-filter="all">All</button>
              <button class="wc-filter-tab" data-filter="unread">Unread</button>
            </div>

            <button id="btn-clear-all" class="btn btn-sm wc-clear-all-btn">
              <span class="me-1">🧹</span> Clear All
            </button>
          </div>
        </div>
      </div>

      {{-- Notifications list --}}
      <div class="wc-notification-list-wrapper">

        @if($all->count())
        <div class="wc-notification-list">

          @php
          $currentGroupDate = null;
          @endphp

          @foreach($all as $notification)
          @php
          // normalize data to array
          $data = $notification->data ?? [];
          if ($data instanceof \Illuminate\Contracts\Support\Arrayable) {
          $data = $data->toArray();
          } elseif (!is_array($data)) {
          $data = (array) $data;
          }

          $type = $data['type'] ?? null;

          $rawTitle = $data['title'] ?? 'Notification';

          $title = match ($type) {
          'appointment_created' => 'New Appointment',
          'coupon_live' => 'Coupon Live',
          'coupon_expiring' => 'Coupon Expiring Soon',
          default => $rawTitle,
          };

          $message = $data['message'] ?? $data['body'] ?? '';
          $url = $data['url'] ?? null;

          // appointment info (from notification payload)
          $appt = $data['appointment'] ?? null;
          $appt = is_array($appt) ? $appt : [];

          $patientName = $appt['name']
          ?? $appt['patient_name']
          ?? ($appt['patient']['name'] ?? null)
          ?? 'N/A';

          $apptDate = $appt['date']
          ?? $appt['appointment_date']
          ?? $appt['day']
          ?? null;

          $timeSlot = $appt['time_slot']
          ?? $appt['slot']
          ?? $appt['time']
          ?? null;

          // coupon info (if any)
          $cp = $data['coupon'] ?? null;
          $cp = is_array($cp) ? $cp : [];

          $couponCode = $cp['code']
          ?? $cp['coupon_code']
          ?? $data['code']
          ?? $data['coupon_code']
          ?? null;

          $couponExpires = $cp['expires_at']
          ?? $cp['expiry']
          ?? $cp['valid_to']
          ?? $data['expires_at']
          ?? $data['expiry']
          ?? $data['valid_to']
          ?? null;

          $isRead = !is_null($notification->read_at);

          // nicer message for appointments
          if ($type === 'appointment_created') {
          $displayMessage = 'New appointment booked for ' . ($patientName !== 'N/A' ? $patientName : 'patient');
          } else {
          $displayMessage = $message;
          }

          // status info for styling (kept for badge/row accent only)
          $baseMessage = $displayMessage ?? $message;
          $lowerMessage = strtolower($baseMessage);
          $statusKey = null;

          if (str_contains($lowerMessage, 'cancel')) {
          $statusKey = 'cancelled';
          } elseif (str_contains($lowerMessage, 'completed')) {
          $statusKey = 'completed';
          } elseif (str_contains($lowerMessage, 'approved')) {
          $statusKey = 'approved';
          } elseif (str_contains($lowerMessage, 'pending')) {
          $statusKey = 'pending';
          } elseif (str_contains($lowerMessage, 'resched')) {
          $statusKey = 'rescheduled';
          }

          $statusClass = $statusKey ? 'wc-row-status-' . $statusKey : '';

          // date grouping label
          $created = \Carbon\Carbon::parse($notification->created_at);
          $dateKey = $created->toDateString();
          if ($dateKey === now()->toDateString()) {
          $groupLabel = 'Today';
          } elseif ($dateKey === now()->subDay()->toDateString()) {
          $groupLabel = 'Yesterday';
          } else {
          $groupLabel = $created->format('d M Y');
          }
          @endphp

          {{-- date group heading --}}
          @if($currentGroupDate !== $dateKey)
          @php $currentGroupDate = $dateKey; @endphp
          <div class="wc-date-divider">
            <span class="wc-date-chip">{{ $groupLabel }}</span>
          </div>
          @endif

          {{-- notification card --}}
          <div
            class="wc-notification-row {{ $isRead ? 'wc-notification-read' : 'wc-notification-unread' }} {{ $statusClass }}"
            data-read="{{ $isRead ? '1' : '0' }}">
            <div class="wc-row-icon">
              @if($type === 'appointment_created')
              <span class="wc-icon wc-icon-appointment">📅</span>
              @elseif($type === 'coupon_live')
              <span class="wc-icon wc-icon-coupon-live">🎟️</span>
              @elseif($type === 'coupon_expiring')
              <span class="wc-icon wc-icon-coupon-expiring">⏰</span>
              @else
              <span class="wc-icon wc-icon-generic">🔔</span>
              @endif
            </div>

            <div class="wc-row-content flex-grow-1">
              {{-- Title + type --}}
              <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                <h6 class="mb-0 fw-semibold wc-notification-title">
                  {{ $title }}
                </h6>

                @if($type === 'appointment_created')
                <span class="badge wc-badge-appointment">Appointment</span>
                @elseif($type === 'coupon_live')
                <span class="badge wc-badge-coupon-live">Coupon Live</span>
                @elseif($type === 'coupon_expiring')
                <span class="badge wc-badge-coupon-expiring">Expiring Soon</span>
                @elseif($type === 'appointment_status' && $statusKey)
                <span class="badge wc-status-chip wc-status-chip-{{ $statusKey }}">
                  {{ ucfirst($statusKey === 'rescheduled' ? 'Rescheduled' : $statusKey) }}
                </span>
                @endif
              </div>

              {{-- Message --}}
              <p class="mb-1 small text-muted wc-notification-message">
                {{ \Illuminate\Support\Str::limit($displayMessage, 140) }}
              </p>

              {{-- Appointment info --}}
              @if($type === 'appointment_created')
              <div class="small wc-meta-line mb-1">
                <span><strong>Patient:</strong> {{ $patientName }}</span>
                @if($apptDate)
                <span class="wc-meta-separator">•</span>
                <span><strong>Date:</strong> {{ $apptDate }}</span>
                @endif
                @if($timeSlot)
                <span class="wc-meta-separator">•</span>
                <span><strong>Slot:</strong> {{ $timeSlot }}</span>
                @endif
              </div>
              @endif

              {{-- Coupon info --}}
              @if(($type === 'coupon_live' || $type === 'coupon_expiring') && ($couponCode || $couponExpires))
              <div class="small wc-meta-line mb-1">
                @if($couponCode)
                <span><strong>Code:</strong> {{ $couponCode }}</span>
                @endif
                @if($couponExpires)
                <span class="wc-meta-separator">•</span>
                <span><strong>Expires:</strong> {{ $couponExpires }}</span>
                @endif
              </div>
              @endif

              {{-- Footer: time + actions --}}
              <div class="wc-row-footer">
                <div class="wc-footer-left">
                  <small class="text-muted wc-timestamp">
                    {{ $created->diffForHumans() }}
                  </small>
                </div>

                <div class="wc-footer-actions">
                  @if($url)
                  <a href="{{ $url }}" class="btn btn-sm wc-pill-button wc-view-btn">
                    View
                  </a>
                  @endif

                  @if(!$isRead)
                  <button
                    class="btn btn-sm wc-pill-button wc-mark-read-btn"
                    data-id="{{ $notification->id }}">
                    Mark as read
                  </button>
                  @else
                  <span class="badge wc-badge-read">Read</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @endforeach

        </div>

        <div class="mt-3 d-flex justify-content-end px-1">
          {{ $all->links() }}
        </div>

        @else
        <div class="text-center py-5 text-muted">
          <div class="wc-empty-icon mb-3">🔔</div>
          <div class="fw-semibold mb-1">You’re all caught up</div>
          <div class="small">No notifications found right now.</div>
        </div>
        @endif

      </div> {{-- .wc-notification-list-wrapper --}}

    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  :root {
    --wc-text: #0f172a;
    --wc-muted: #6b7280;
    --wc-border: #e5e7eb;
    --wc-accent: #0f9d80;
    --wc-accent-soft: rgba(15, 157, 128, 0.08);
    --wc-bg-soft: #f9fafb;
    --wc-danger: #ef4444;
  }

  /* Page layout */
  .notifications-page-wrapper {
    padding-top: 8px !important;
  }

  .wc-page-title {
    letter-spacing: 0.01em;
  }

  /* Header card */
  .wc-notification-header-card {
    border-radius: 18px;
    background: radial-gradient(circle at top left, #ecfdf5 0, #ffffff 55%);
    border: 1px solid #e5f4ee;
  }

  .wc-unread-pill {
    background: var(--wc-accent-soft);
    color: var(--wc-accent);
    font-weight: 500;
    padding: 0.35rem 0.75rem;
    font-size: 0.78rem;
  }

  /* Filter tabs (read) */
  .wc-filter-tabs {
    display: inline-flex;
    background: #f3f4f6;
    border-radius: 999px;
    padding: 2px;
  }

  .wc-filter-tab {
    border: none;
    background: transparent;
    padding: 4px 10px;
    font-size: 0.78rem;
    border-radius: 999px;
    color: #6b7280;
    cursor: pointer;
  }

  .wc-filter-tab.active {
    background: #ffffff;
    color: #111827;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
  }

  /* Clear all */
  .wc-clear-all-btn {
    border-radius: 999px;
    border: 1px solid rgba(239, 68, 68, 0.18);
    background: #fff;
    color: var(--wc-danger);
    font-size: 0.78rem;
    padding: 0.35rem 0.9rem;
    display: inline-flex;
    align-items: center;
  }

  .wc-clear-all-btn:hover {
    background: rgba(239, 68, 68, 0.04);
  }

  /* MAIN WRAPPER (no card) */
  .wc-notification-list-wrapper {
    background: transparent;
    border: none;
    padding: 0 !important;
  }

  /* List */
  .wc-notification-list {
    display: flex;
    flex-direction: column;
  }

  /* Date group divider */
  .wc-date-divider {
    display: flex;
    align-items: center;
    margin: 1.2rem 0 0.6rem;
  }

  .wc-date-divider::before,
  .wc-date-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
  }

  .wc-date-chip {
    font-size: 0.7rem;
    color: #6b7280;
    background: #f9fafb;
    border-radius: 999px;
    padding: 2px 10px;
    border: 1px solid #e5e7eb;
    margin: 0 8px;
  }

  /* EACH NOTIFICATION AS CARD */
  .wc-notification-row {
    margin-bottom: 1rem;
    border-radius: 16px;
    border: 1px solid var(--wc-border);
    background: #ffffff;
    padding: 1.1rem 1.2rem;
    display: flex;
    gap: 1rem;
    box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
    transition: box-shadow 0.16s ease, transform 0.16s ease, border-color 0.16s ease, background-color 0.16s ease;
  }

  .wc-notification-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
    border-color: rgba(15, 157, 128, 0.35);
  }

  /* Read / unread */
  .wc-notification-unread {
    background: #f9fafb;
    border-color: #d1d5db;
  }

  .wc-notification-read {
    opacity: 0.96;
  }

  /* Status-based row accents */
  .wc-row-status-cancelled,
  .wc-row-status-completed,
  .wc-row-status-approved,
  .wc-row-status-pending,
  .wc-row-status-rescheduled {
    border-left: 3px solid #e5e7eb;
  }

  /* Icon bubble */
  .wc-row-icon {
    flex: 0 0 auto;
  }

  .wc-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.2);
    background: #f3f4f6;
  }

  /* Content */
  .wc-row-content {
    flex: 1;
    min-width: 0;
  }

  .wc-notification-title {
    font-size: 0.95rem;
    color: var(--wc-text);
  }

  .wc-notification-message {
    font-size: 0.8rem;
    color: var(--wc-muted) !important;
    max-width: 620px;
  }

  .wc-meta-line {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem 0.7rem;
    margin-top: 0.1rem;
    color: #4b5563;
  }

  .wc-meta-line span {
    font-size: 0.78rem;
  }

  .wc-meta-separator {
    opacity: 0.6;
  }

  /* Footer */
  .wc-row-footer {
    margin-top: 0.6rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: wrap;
  }

  .wc-footer-left {
    flex: 1;
  }

  .wc-footer-actions {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-wrap: wrap;
  }

  .wc-timestamp {
    font-size: 0.72rem;
  }

  /* Buttons / badges */
  .wc-pill-button {
    border-radius: 50px;
    font-size: 0.78rem;
    padding: 0.25rem 1rem;
    border-width: 1px;
    font-weight: 500;
  }

  .wc-view-btn {
    border-color: rgba(15, 157, 128, 0.25);
    color: var(--wc-accent);
    background: #ffffff;
  }

  .wc-view-btn:hover {
    background: var(--wc-accent-soft);
    border-color: rgba(15, 157, 128, 0.45);
    color: #047857;
  }

  .wc-mark-read-btn {
    border-color: rgba(22, 163, 74, 0.35);
    color: #15803d;
    background: #f0fdf4;
  }

  .wc-mark-read-btn:hover {
    background: #dcfce7;
    border-color: rgba(22, 163, 74, 0.6);
  }

  .wc-badge-read {
    background: #e5f4ee;
    color: #15803d;
    font-size: 0.7rem;
    border-radius: 999px;
  }

  /* Type badges */
  .wc-badge-appointment,
  .wc-badge-coupon-live,
  .wc-badge-coupon-expiring {
    font-size: 0.7rem;
    border-radius: 999px;
    border: 1px solid transparent;
  }

  .wc-badge-appointment {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bfdbfe;
  }

  .wc-badge-coupon-live {
    background: #dcfce7;
    color: #15803d;
    border-color: #bbf7d0;
  }

  .wc-badge-coupon-expiring {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
  }

  /* Status chips */
  .wc-status-chip {
    font-size: 0.7rem;
    border-radius: 999px;
    padding: 2px 8px;
    border: 1px solid transparent;
  }

  .wc-status-chip-cancelled {
    background: #fee2e2;
    color: #b91c1c;
    border-color: #fecaca;
  }

  .wc-status-chip-completed {
    background: #dcfce7;
    color: #166534;
    border-color: #bbf7d0;
  }

  .wc-status-chip-approved {
    background: #dbeafe;
    color: #1d4ed8;
    border-color: #bfdbfe;
  }

  .wc-status-chip-pending {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
  }

  .wc-status-chip-rescheduled {
    background: #e0f2fe;
    color: #0369a1;
    border-color: #bae6fd;
  }

  /* Empty state */
  .wc-empty-icon {
    font-size: 2.2rem;
  }

  /* SweetAlert overrides */
  .swal2-popup {
    border-radius: 12px !important;
    padding: 1.5rem !important;
    max-width: 560px !important;
  }

  .swal2-popup .swal2-title {
    font-size: 1.4rem;
    margin-bottom: .25rem;
  }

  .swal2-popup .swal2-html-container {
    color: #6c757d;
    font-size: 1rem;
  }

  .swal2-actions {
    gap: 12px;
  }

  /* Small screens */
  @media (max-width: 768px) {
    .wc-notification-row {
      padding: 0.9rem 0.9rem;
      flex-direction: row;
    }

    .wc-notification-message {
      max-width: 100%;
    }

    .wc-row-footer {
      align-items: flex-start;
    }

    .wc-footer-actions {
      justify-content: flex-start;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  (function() {
    const csrfToken =
      document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
      '{{ csrf_token() }}';

    // ===== All / Unread filter =====
    const rows = Array.from(document.querySelectorAll('.wc-notification-row'));
    const filterState = {
      read: 'all', // 'all' | 'unread'
    };

    function applyFilters() {
      rows.forEach(row => {
        const isRead = row.dataset.read === '1';

        if (filterState.read === 'unread' && isRead) {
          row.style.display = 'none';
          return;
        }

        row.style.display = 'flex';
      });
    }

    document.querySelectorAll('.wc-filter-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        const filter = tab.dataset.filter; // 'all' | 'unread'
        filterState.read = filter;

        document.querySelectorAll('.wc-filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        applyFilters();
      });
    });

    // Single notification mark-as-read
    document.querySelectorAll('.wc-mark-read-btn').forEach(btn => {
      btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        this.disabled = true;
        try {
          const res = await fetch("{{ route('notifications.markRead') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              id
            })
          });
          if (res.ok) {
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'Marked as read',
              showConfirmButton: false,
              timer: 1400,
              timerProgressBar: true
            }).then(() => location.reload());
          } else {
            console.error('markRead failed', await res.text());
            this.disabled = false;
            Swal.fire('Error', 'Could not mark notification as read.', 'error');
          }
        } catch (err) {
          console.error(err);
          this.disabled = false;
          Swal.fire('Error', 'Network error occurred.', 'error');
        }
      });
    });

    // Clear All using SweetAlert2 confirm
    const btnClearAll = document.getElementById('btn-clear-all');
    if (btnClearAll) {
      btnClearAll.addEventListener('click', function() {
        Swal.fire({
          title: 'Are you sure?',
          text: "This will mark all notifications as read.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, clear all',
          cancelButtonText: 'Cancel',
          reverseButtons: true,
          focusCancel: true,
          customClass: {
            confirmButton: 'btn btn-danger btn-sm px-3 py-1',
            cancelButton: 'btn btn-secondary btn-sm px-3 py-1'
          },
          buttonsStyling: false,
          didOpen: () => {
            try {
              const actions = document.querySelector('.swal2-actions');
              if (actions) {
                actions.style.display = 'flex';
                actions.style.gap = '12px';
                actions.style.justifyContent = 'center';
                actions.style.alignItems = 'center';
                actions.style.marginTop = '1rem';
              }
              const btns = document.querySelectorAll('.swal2-actions button, .swal2-actions .btn, .swal2-actions .swal2-styled');
              btns.forEach(b => {
                b.style.margin = '0';
                b.style.padding = '6px 14px';
                b.style.borderRadius = '6px';
                b.style.fontSize = '0.88rem';
                b.classList.remove('me-2');
              });
            } catch (err) {
              console.warn('didOpen spacing fix failed', err);
            }
          }
        }).then(async (result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Clearing...',
              didOpen: () => {
                Swal.showLoading();
              },
              allowOutsideClick: false,
              allowEscapeKey: false,
              showConfirmButton: false
            });

            try {
              const res = await fetch("{{ route('notifications.clearAll') }}", {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': csrfToken,
                  'Accept': 'application/json'
                },
                body: JSON.stringify({})
              });

              if (res.ok) {
                Swal.close();
                Swal.fire({
                  toast: true,
                  position: 'top-end',
                  icon: 'success',
                  title: 'All notifications cleared',
                  showConfirmButton: false,
                  timer: 1500,
                  timerProgressBar: true
                }).then(() => location.reload());
              } else {
                const text = await res.text();
                console.error('clearAll failed', text);
                Swal.fire('Error', 'Could not clear notifications.', 'error');
              }
            } catch (err) {
              console.error(err);
              Swal.fire('Error', 'Network error occurred.', 'error');
            }
          }
        });
      });
    }
  })();
</script>
@endpush