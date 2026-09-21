{{-- resources/views/viewcartdetails.blade.php --}}
@extends('maindesign')

@section('title', 'Your Cart - Wellcare Labs')

@section('content')
<div class="container my-5">

  @if(count($items) === 0)
    {{-- 🛒 Empty Cart View --}}
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 text-center py-5 px-4 empty-cart-card">
          <div class="mb-3">
            <i class="fa fa-shopping-cart fa-4x text-muted"></i>
          </div>
          <h4 class="mb-2" style="color:#2b4a66;">Your cart is empty</h4>
          <p class="text-muted mb-4">Looks like you haven't added any tests or packages yet. Browse our services and add whatever you need.</p>

          <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="{{ url('/packages') }}" class="btn btn-primary rounded-pill px-4 py-2">Browse Packages / Test</a>
          </div> 
        </div>
      </div>
    </div>

  @else
    {{-- 🧾 Cart View --}}
    <div class="row">
     <div class="col-12">
  <h3 class="mb-4 text-center text-md-start fw-bold" 
      style="font-weight: 600; font-size: 2rem; color:#0a2540; letter-spacing: 0.5px;">
    Your Cart
  </h3>
</div>

      {{-- Cart Table --}}
      <div class="col-12 col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm">
          <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr class="text-muted small">
                    <th>Item</th>
                    <th>Price</th>
                    <th style="width:140px;">Action</th>
                  </tr>
                </thead>
                <tbody id="cart-body">
                  @foreach($items as $it)
                    <tr data-key="{{ $it['key'] }}" data-price="{{ $it['price'] }}">
                      <td>
                        <strong>{{ $it['name'] }}</strong><br>

                        {{-- BEGIN: Type label mapping per requirements --}}
                        @php
                          // Safe get helper for array/object
                          $getVal = function($obj, $key, $default = null) {
                            if (is_array($obj)) return $obj[$key] ?? $default;
                            if (is_object($obj)) return $obj->{$key} ?? $default;
                            return $default;
                          };

                          $type = (string) ($getVal($it, 'item_type', '') ?: '');
                          $fromCustomize = !empty($getVal($it, 'from_custom_package', false));
                          $label = '';

                          if (in_array($type, ['test', 'lab_test'])) {
                              $label = $fromCustomize ? 'Customize Lab Test' : 'Test';
                          } elseif ($type === 'custom_package') {
                              $label = 'Custom Package';
                          } elseif ($type === 'package') {
                              // Try to detect package subtype (package_type or meta.package_type); fallback to name keyword
                              $pkgType = null;
                              $meta = $getVal($it, 'meta', null);

                              if (is_array($meta) && isset($meta['package_type'])) {
                                  $pkgType = $meta['package_type'];
                              } elseif (is_object($meta) && isset($meta->package_type)) {
                                  $pkgType = $meta->package_type;
                              } else {
                                  $pkgType = $getVal($it, 'package_type', $pkgType);
                              }

                              $pkgType = strtolower((string)($pkgType ?? ''));
                              $nameLower = strtolower((string) $getVal($it, 'name', ''));

                              if (str_contains($pkgType, 'basic') || str_contains($nameLower, 'basic')) {
                                  $label = 'Basic Package';
                              } elseif (str_contains($pkgType, 'special') || str_contains($nameLower, 'special')) {
                                  $label = 'Special Package';
                              } else {
                                  $label = 'Special Package';
                              }
                          } else {
                              $label = $getVal($it, 'name') ? $getVal($it, 'name') : ucfirst($type ?: 'item');
                          }
                        @endphp

                        <small class="text-muted">{{ $label }}</small>
                        {{-- END: Type label mapping --}}

                        @if(isset($it['meta']) && is_array($it['meta']))
                          <div class="mt-1 text-muted small">
                            @foreach($it['meta'] as $k => $v)
                              {{ $k }}: {{ $v }}@if(!$loop->last), @endif
                            @endforeach
                          </div>
                        @endif
                        {{-- If this is a custom package, show contained tests count (optional) --}}
                        @if(!empty($it['item_type']) && $it['item_type'] === 'custom_package' && !empty($it['details']))
                          <div class="mt-1 text-muted small">Contains {{ count($it['details']) }} tests</div>
                        @endif
                      </td>
                      <td>₹{{ number_format($it['price'], 2) }}</td>
                      <td class="action-cell">
                        @if(!empty($it['from_custom_package']))
                          {{-- Locked: item added from Customize Package --}}
                          <button class="btn btn-secondary rounded-pill px-3 py-2 btn-sm-md" type="button" aria-label="Locked item" disabled title="Added via Customize Package">Locked</button>
                        @else
                          {{-- Removable item --}}
                          <button class="btn btn-danger btn-remove rounded-pill px-3 py-2 btn-sm-md" type="button" aria-label="Remove item">Remove</button>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Order Summary --}}
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm mb-3">
          <div class="card-body">
            <h5 class="mb-3">Order Summary</h5>
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal:</span>
              <strong>₹ <span id="subtotal">{{ number_format($total, 2) }}</span></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Tax (0%):</span>
              <strong>₹ 0.00</strong>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-3">
              <span><strong>Total Amount</strong></span>
              <strong>₹ <span id="grand-total">{{ number_format($total, 2) }}</span></strong>
            </div>

            <button class="btn btn-outline-danger rounded-pill w-100 py-2 mb-2" id="btn-clear-cart">Clear All Cart</button>
<a href="{{ url('/checkout') }}" class="btn btn-outline-primary rounded-pill w-100 py-2 mb-2">
  Proceed to Checkout
</a>
            <a href="{{ url('/services') }}" class="btn btn-outline-secondary rounded-pill w-100 py-2">Book more Test / Packages</a>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* ========== Table & Card styling ========== */

/* ================= CART ACTION BUTTON COLORS ================= */

/* 1️⃣ Proceed to Checkout — Primary (Blue) */
.btn-outline-primary {
  background: #ffffff;
  color: #0d6efd;
  border: 2px solid #cfe2ff;
  border-radius: 999px;
  font-weight: 600;
  padding: 10px 18px;
  transition: all 0.25s ease;
}

.btn-outline-primary:hover {
  background: #0d6efd;
  color: #ffffff;
  border-color: #0d6efd;
}

/* 2️⃣ Clear All Cart — Destructive (Red) */
.btn-outline-danger {
  background: #ffffff;
  color: #b02a37;
  border: 2px solid #f1b0b7;
  border-radius: 999px;
  font-weight: 600;
  padding: 10px 18px;
  transition: all 0.25s ease;
}

.btn-outline-danger:hover {
  background: #dc3545;
  color: #ffffff;
  border-color: #dc3545;
}

/* 3️⃣ Book More Tests / Packages — Secondary (Grey) */
.btn-outline-secondary {
  background: #ffffff;
  color: #475569;
  border: 2px solid #e2e8f0;
  border-radius: 999px;
  font-weight: 600;
  padding: 10px 18px;
  transition: all 0.25s ease;
}

.btn-outline-secondary:hover {
  background: #b2ceeb;
  color: #0f172a;
  border-color: #cbd5e1;
}



.table {
  border-collapse: separate;
  border-spacing: 0 10px;
}

.table td, .table th {
  vertical-align: middle !important;
  text-align: center !important;
  font-size: 15px;
  padding: 0.75rem 1rem;
  background-color: #fff;
}

.table tbody tr {
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
  border-radius: 8px;
  transition: transform 0.12s ease, box-shadow 0.12s ease;
  overflow: hidden;
}

.table tbody tr:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

.table tbody tr td:first-child {
  border-top-left-radius: 8px;
  border-bottom-left-radius: 8px;
}

.table tbody tr td:last-child {
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
}

/* Action buttons centered */
.action-cell {
  text-align: center !important;
}

.action-cell .btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
  min-width: 110px;
  padding: 8px 18px;
  font-weight: 500;
  font-size: 0.95rem;
  border-radius: 30px;
}

/* Toast notification */
#cart-toast {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #28a745;
  color: #fff;
  padding: 10px 16px;
  border-radius: 6px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.15);
  z-index: 9999;
  display: none;
  transition: opacity 0.3s ease;
}

/* ========== Empty Cart Card Styling ========== */
.empty-cart-card {
  background: linear-gradient(180deg, #ffffff, #f9fbfc);
  border-radius: 16px;
}

.empty-cart-card .fa-shopping-cart {
  width: 88px;
  height: 88px;
  line-height: 88px;
  border-radius: 50%;
  background: #f1f5f9;
  color: #6c757d;
  padding: 18px;
}

/* Mobile responsiveness */
@media (max-width: 576px) {
  .table td, .table th {
    font-size: 0.9rem;
    padding: 0.5rem;
  }

  .action-cell .btn {
    min-width: unset;
    width: 100%;
  }

  .table {
    border-spacing: 0 6px;
  }

  .btn-sm-md {
    width: 100%;
  }
}
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Keep these keys aligned with customize page code
  const STORAGE_KEY = 'wellcare_custom_selected_tests_v1';
  const ADDED_FLAG = 'wellcare_package_added_flag_v1';
  const SYNC_KEY = 'wellcare_custom_sync';

  async function postJson(url, payload) {
    try {
      const res = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload || {})
      });
      const json = await res.json().catch(()=>null);
      return { ok: res.ok, json, status: res.status };
    } catch(err){ return { ok:false, error: err }; }
  }

  function showToast(msg, color = '#28a745') {
    let toast = document.getElementById('cart-toast');
    if(!toast) { toast = document.createElement('div'); toast.id = 'cart-toast'; document.body.appendChild(toast); }
    toast.style.background = color;
    toast.textContent = msg;
    toast.style.display = 'block';
    toast.style.opacity = '1';
    setTimeout(()=>{ toast.style.opacity = '0'; setTimeout(()=>toast.style.display='none', 300); }, 1200);
  }

  function updateTotalsUI(total){
    const t = parseFloat(total||0).toFixed(2);
    const subtotalEl = document.getElementById('subtotal');
    const grandEl = document.getElementById('grand-total');
    if(subtotalEl) subtotalEl.textContent = t;
    if(grandEl) grandEl.textContent = t;
  }

  async function refreshNavBadge(){
    try{
      const route = "{{ route('cart.count') }}";
      const r = await fetch(route, { method: 'GET', credentials: 'same-origin' });
      const json = await r.json();
      const n = Number(json.count||0);
      const badge = document.getElementById('cart-count-badge');
      if(badge) badge.innerText = n;
      return n;
    } catch(err) { return 0; }
  }

  // Remove any selected tests in localStorage that match the cart item name
  function syncRemoveSelectedByName(name){
    if(!name) return;
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if(!raw) return;
      let arr = JSON.parse(raw);
      if(!Array.isArray(arr)) return;
      // Arr entries stored by customize script may be objects {id, name, price,...}
      const filtered = arr.filter(item => {
        const iname = (item && (item.name || item.test_name || item.testName)) ? String(item.name || item.test_name || item.testName).trim() : '';
        return iname.toLowerCase() !== String(name).trim().toLowerCase();
      });
      localStorage.setItem(STORAGE_KEY, JSON.stringify(filtered));
      // Clear ADDED_FLAG if no items remain
      if((filtered.length||0) === 0) localStorage.removeItem(ADDED_FLAG);
      // Touch sync key to notify other tabs/pages
      localStorage.setItem(SYNC_KEY, Date.now().toString());
    } catch(e){ console.warn('syncRemoveSelectedByName error', e); }
  }

  // Clear all customize selections (called on Clear Cart)
  function syncClearAllSelected(){
    try {
      localStorage.removeItem(STORAGE_KEY);
      localStorage.removeItem(ADDED_FLAG);
      localStorage.setItem(SYNC_KEY, Date.now().toString());
    } catch(e){ console.warn('syncClearAllSelected error', e); }
  }

  async function removeItem(key) {
    // Read item name from the DOM row BEFORE removing it from UI
    const row = document.querySelector(`tr[data-key="${key}"]`);
    const itemName = row ? (row.querySelector('td strong')?.textContent || '').trim() : '';

    if (!confirm('Remove this item from your cart?')) return false;
    const res = await postJson("{{ route('cart.remove') }}", { key });
    if (!res.ok || !res.json?.success) {
      // Show server message if present (handles 403 from guard)
      const msg = res.json?.message || 'Failed to remove item';
      showToast(msg, '#dc3545');
      // If server returned 403 (forbid), we keep the row intact
      return false;
    }

    // remove row(s)
    document.querySelectorAll(`tr[data-key="${key}"]`).forEach(e => e.remove());
    updateTotalsUI(res.json.total);
    await refreshNavBadge();
    showToast('Item removed', '#dc3545');

    // Sync customize selection: remove any selected test with same name
    if(itemName) syncRemoveSelectedByName(itemName);

    if ((res.json.count||0) === 0) {
      // if cart empty, reload so empty-cart view shows correctly
      setTimeout(()=>location.reload(), 300);
    }
    return true;
  }

  document.getElementById('btn-clear-cart')?.addEventListener('click', async () => {
    if(!confirm('Are you sure you want to clear all items from your cart?')) return;
    const res = await postJson("{{ route('cart.clear') }}");
    if(!res.ok || !res.json?.success) { showToast(res.json?.message || 'Failed to clear cart', '#dc3545'); return; }

    // sync: clear selected customize package state
    syncClearAllSelected();

    // reload to show empty cart
    location.reload();
  });

  // Attach handlers only to removable items (locked items don't get the `.btn-remove` class)
  document.querySelectorAll('.btn-remove').forEach(btn => {
    const row = btn.closest('tr');
    const key = row?.dataset?.key;
    btn.addEventListener('click', async () => { if (key) await removeItem(key); });
  });

  refreshNavBadge();
});
</script>
@endsection
