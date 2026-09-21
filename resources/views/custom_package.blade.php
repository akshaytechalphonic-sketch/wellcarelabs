@extends('maindesign')



@section('title', 'Wellcare Labs – Customize Your Health Test Package Online')

@section('meta_description', 'Design your own personalized health test package with Wellcare Labs. Choose specific lab tests, build a tailored health checkup plan, and get accurate diagnostic results with flexible options and expert support.')


@section('content')
<div class="container py-5">
  <div class="text-center mb-4">
    <h1 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:0px;">
      Wellcare Customize<span style="color:#0d6efd;">Your Package</span>
    </h1>
    <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                background:linear-gradient(90deg,#0047ff,#00ccff);"></div>
    <p class="text-muted mb-0" style="font-size:1.1rem;color:#6b7280;margin:0;">
      Select the lab tests you want to include and build your personalized package.
    </p>
  </div>

  <!-- NEW wc-search component: icon left, input center, clear inside input (right) -->
  <div class="row mb-4">
    <div class="col-md-6 mx-auto">
      <div class="wc-search-wrapper">
        <form id="customizeSearchForm" class="wc-search" role="search" onsubmit="return false;">
          <button type="submit" class="wc-search-icon" aria-label="Search">
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>

          <div class="wc-search-field-wrap">
            <input
              id="search-tests"
              class="wc-search-input"
              type="search"
              placeholder="Search package, e.g. CBC, Thyroid..."
              aria-label="Search tests"
              autocomplete="off"
            />

            <button id="clear-search" class="wc-search-clear" aria-label="Clear search" title="Clear search" type="button">
              <i class="fa fa-times" aria-hidden="true"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Selected Tests Panel (mobile: below search bar, above tests list) -->
    <div class="col-lg-4 order-1 order-lg-2">
      <div class="card shadow-sm sticky-top" style="top:20px;">
        <div class="card-body">
          <h5 class="card-title">Selected Tests <small id="selected-count" class="text-muted">(0)</small></h5>

          <!-- Header row (fixed styling and alignment) -->
          <div class="selected-header d-flex align-items-center mb-2 py-2">
            <div class="selected-name flex-grow-1">Test name</div>
            <div class="selected-price text-center">Price</div>
            <div class="selected-remove text-end">Remove</div>
          </div>

          <!-- Selected tests container -->
          <div id="selected-tests" class="list-group mb-3"></div>

          <div class="mb-3">
            <strong>Total Price: ₹<span id="total-price">0</span></strong>
          </div>

          <button id="add-selected-to-cart" class="btn btn-danger w-100" disabled>
            Add selected tests to cart (replace existing)
          </button>

          <!-- Small helper note -->
          <div id="min-tests-note" 
               class="text-center mt-2" 
               style="display:none; 
                      font-size: 1rem; 
                      font-weight: 300; 
                      color: #000000;">
            Please select at least <strong>2 tests</strong> to create a custom package.
          </div>

        </div>
      </div>
    </div>

    <!-- Tests List (desktop: left side, mobile: below selected tests) -->
    <div class="col-lg-8 order-2 order-lg-1">
      <div class="row g-3" id="tests-row" style="row-gap: 1.5rem;">
        @foreach($tests as $test)
          @php
            $price = $test->discounted_price ?? $test->price ?? $test->mrp ?? 0;
            $mrp = $test->mrp ?? $price;
            $discountPercent = ($mrp > 0 && $mrp > $price) ? round( (1 - ($price / $mrp)) * 100 ) : 0;
            $fullDesc = $test->description ?? $test->short_description ?? '';
            $fullDescPlain = strip_tags($fullDesc);
            $extraClass = ($loop->index >= 6) ? 'extra-test d-none' : '';
          @endphp

          <div class="col-12 col-sm-6 col-md-4 test-card {{ $extraClass }}" data-name="{{ strtolower($test->test_name) }}" style="margin-bottom: 1rem;">
            <div class="card package-style-card h-100 shadow-sm border-0 rounded-3">
              <div class="card-body d-flex flex-column p-3" data-test-id="{{ $test->id }}">
                <h5 class="card-title accent-title mb-2">
                  <a href="{{ route('services.show', ['labTest' => $test->slug]) }}" class="text-decoration-none text-reset">
                    {{ $test->test_name }}
                  </a>
                </h5>

                <p class="package-desc clamped mb-2">
                  {!! \Illuminate\Support\Str::limit(strip_tags($test->short_description ?? $test->description ?? ''), 110, '...') !!}
                </p>

                <!-- Read more links to detail page -->
                <a href="{{ route('services.show', ['labTest' => $test->slug]) }}" class="read-more btn btn-link p-0 mb-3 text-decoration-none">Read more</a>

                <div class="mt-auto d-flex justify-content-between align-items-end">
                  <div>
                    <div class="price-row d-flex align-items-center">
                      <div class="disc-price">₹{{ number_format($price, 0, '.', ',') }}</div>
                      @if($mrp > $price)
                        <div class="mrp ms-2">₹{{ number_format($mrp, 0, '.', ',') }}</div>
                      @endif
                    </div>
                    @if($discountPercent > 0)
                      <div class="discount-pill mt-2">{{ $discountPercent }}% OFF</div>
                    @endif
                  </div>

                  <div>
                    <!-- Add Button toggles selection (no cart icon as requested) -->
                    <button class="wc-cart-btn add-to-cart add-btn" data-id="{{ $test->id }}" data-price="{{ $price }}" aria-pressed="false" title="Add to selection">
                      <span class="btn-text">Add</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- View All / Show Less button (moved and styled to avoid overlap) -->
      @if($tests->count() > 6)
        <div class="text-center mt-4 mb-3 view-all-wrap">
          <button id="view-all-btn" class="btn btn-outline-secondary">View all tests</button>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- ===== Read More Modal (updated: inline price row) ===== -->
<div class="modal fade" id="readMoreModal" tabindex="-1" aria-labelledby="readMoreModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content overflow-hidden">

      <!-- Header: Title (left) + top-right circular close (overlapping) -->
      <div class="modal-header position-relative border-0 px-4 pt-4 pb-0">
        <h5 class="modal-title" id="readMoreModalLabel" style="color:#e91e63; font-weight:800; font-size:1.25rem;">
          Test Title
        </h5>

        <!-- circular top-right close (absolute positioned) -->
        <button type="button" class="modal-cancel-icon" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa fa-times" aria-hidden="true"></i>
        </button>
      </div>

      <!-- Body: description -->
      <div class="modal-body px-4 pb-0" id="readMoreModalBody" style="color:#111; font-weight:600; line-height:1.45; font-size:1rem;">
        <!-- description injected here -->
      </div>

      <hr class="my-0">

      <!-- Bottom action bar: price/discount left, buttons right (modal-price-row ensures one-line layout) -->
      <div class="modal-action-bar px-4 py-3 d-flex align-items-center justify-content-between" style="gap:12px;">
        <div class="modal-price-row d-flex align-items-center flex-wrap" style="gap:12px;">
          <div id="modal-price" class="mp-price" style="font-weight:800; font-size:1.15rem; color:#0a3a66;">₹0</div>
          <small id="modal-mrp" class="mp-mrp" style="text-decoration:line-through; color:#8b98a7; display:none;">₹0</small>
          <span id="modal-off" class="mp-off percent-pill" style="display:none;">0%</span>
          <span id="modal-save" class="mp-save you-save-text" style="display:none;">You save ₹0</span>

          <!-- legacy IDs for other templates (kept hidden) -->
          <span id="mSell" style="display:none;">₹0</span>
          <small id="mMrp" style="display:none;"></small>
          <span id="mOff" style="display:none;"></span>
          <span id="mSave" style="display:none;"></span>
        </div>

        <div class="d-flex align-items-center gap-3 modal-action-buttons">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button type="button" class="wc-cart-btn add-to-cart btn-pink" id="modal-add-btn" data-id="">
            <i class="fa fa-cart-plus me-2"></i> Add to Selection
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Discount popup modal -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center py-4" id="discountModalBody">
        <!-- JS fills message -->
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Confirm Replace Modal -->
<div class="modal fade" id="replaceCartConfirmModal" tabindex="-1" aria-labelledby="replaceCartConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="replaceCartConfirmLabel">Replace existing cart items?</h5>
        <button type="button"
                class="btn border-0 bg-transparent p-0"
                data-bs-dismiss="modal"
                aria-label="Close"
                title="Cancel"
                style="position:absolute; right:1rem; top:1rem;">
          <i class="fa fa-times text-danger" style="font-size:1.8rem;"></i>.
        </button>
      </div>

      <div class="modal-body">
        <p>
          If you add these selected tests to the cart, <strong>all items currently in your cart will be removed</strong> and replaced with these tests.
        </p>
        <p class="mb-0"><strong>Do you want to continue?</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button id="confirm-replace-btn" type="button" class="btn btn-danger">Yes — Replace & Add</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){

    // ---------- Config / keys ----------
    const STORAGE_KEY = 'wellcare_custom_selected_tests_v1';
    const ADDED_FLAG = 'wellcare_package_added_flag_v1';
    const SYNC_KEY = 'wellcare_custom_sync';
    const CART_COUNT_URL = "{{ route('cart.count') }}"; // must return JSON { count: number }

    // ---------- Utility ----------
    function debounce(fn, wait = 150){
      let t = null;
      return function(...args){
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this,args), wait);
      };
    }

    function formatRupee(n){
        return Number(n || 0).toLocaleString('en-IN', {maximumFractionDigits:0});
    }

    // ---------- Discount logic ----------
    function getDiscountPercentForPosition(pos){
        if(pos <= 1) return 0;
        if(pos === 2) return 20;
        if(pos === 3) return 30;
        if(pos === 4) return 40;
        return 50;
    }

    // ---------- State ----------
    let selected = [];
    const selectedList = document.getElementById('selected-tests');
    const totalPriceEl = document.getElementById('total-price');
    const addSelectedToCartBtn = document.getElementById('add-selected-to-cart');
    const selectedCountEl = document.getElementById('selected-count');

    // Bootstrap modals if available
    const readMoreModalEl = document.getElementById('readMoreModal');
    const readMoreModal = (typeof bootstrap !== 'undefined' && readMoreModalEl) ? new bootstrap.Modal(readMoreModalEl) : null;
    const replaceModalEl = document.getElementById('replaceCartConfirmModal');
    const replaceModal = (typeof bootstrap !== 'undefined' && replaceModalEl) ? new bootstrap.Modal(replaceModalEl) : null;
    const discountModalEl = document.getElementById('discountModal');
    const discountModal = (typeof bootstrap !== 'undefined' && discountModalEl) ? new bootstrap.Modal(discountModalEl) : null;

    // ---------- Discounts + compute ----------
    function computeDiscounts(){
        selected.forEach((t, index) => {
            const pos = index + 1;
            const pct = getDiscountPercentForPosition(pos);
            t.discountPercent = pct;
            const raw = Number(t.price || 0);
            t.discountedPrice = Math.round(raw * (1 - pct/100));
        });
    }

    function showDiscountPopupForCount(count){
        let msg = '';
        if(count === 1){
            msg = 'Add a 2nd test and get 20% OFF on the second test.';
        } else if(count === 2){
            msg = 'Add a 3rd test and get 30% OFF on the third test.';
        } else if(count === 3){
            msg = 'Add a 4th test and get 40% OFF on the fourth test.';
        } else if(count === 4){
            msg = 'Add a 5th test and get 50% OFF on the fifth test.';
        } else if(count >= 5){
            msg = 'Add more tests to save more.';
        }
        if(msg){
            const body = document.getElementById('discountModalBody');
            if(body) body.textContent = msg;
            if(discountModal) discountModal.show(); else alert(msg);
        }
    }

    // ---------- Panel update ----------
    function updatePanel(){
        computeDiscounts();
        selectedList.innerHTML = '';
        let total = 0;
        selected.forEach(t => {
            const row = document.createElement('div');
            row.className = 'd-flex align-items-center list-group-item py-2';

            const nameDiv = document.createElement('div');
            nameDiv.className = 'flex-grow-1 selected-item-name';
            nameDiv.textContent = t.name;

            const priceDiv = document.createElement('div');
            priceDiv.style.width = '110px';
            priceDiv.className = 'text-center selected-item-price';
            if(t.discountPercent > 0 && Number(t.discountedPrice) !== Number(t.price)){
                priceDiv.innerHTML = '<div>₹' + formatRupee(t.discountedPrice) + '</div><div class="small text-muted" style="text-decoration:line-through">₹' + formatRupee(t.price) + '</div>';
            } else {
                priceDiv.innerHTML = '₹' + formatRupee(t.price);
            }

            const removeDiv = document.createElement('div');
            removeDiv.style.width = '80px';
            removeDiv.className = 'text-end selected-item-remove';

            const removeBtn = document.createElement('button');
            removeBtn.className = 'btn btn-sm btn-outline-danger remove-item';
            removeBtn.dataset.id = t.id;
            removeBtn.setAttribute('aria-label', 'Remove test');
            removeBtn.title = 'Remove';
            removeBtn.innerHTML = '<i class="fa fa-trash" aria-hidden="true"></i>';

            removeDiv.appendChild(removeBtn);

            row.appendChild(nameDiv);
            row.appendChild(priceDiv);
            row.appendChild(removeDiv);

            selectedList.appendChild(row);
            total += Number(t.discountedPrice || t.price || 0);
        });

        totalPriceEl.textContent = formatRupee(total);
        selectedCountEl.textContent = '(' + selected.length + ')';
        updateActionButtonState();
    }

    // ---------- Action button state ----------
    function updateActionButtonState(){
        const wasAdded = localStorage.getItem(ADDED_FLAG) === '1';
        const note = document.getElementById('min-tests-note');

        if(wasAdded){
            if(selected.length > 0){
                addSelectedToCartBtn.disabled = false;
                addSelectedToCartBtn.classList.remove('btn-danger');
                addSelectedToCartBtn.classList.add('btn-primary');
                addSelectedToCartBtn.textContent = 'Go to cart';
                addSelectedToCartBtn.onclick = function(){ window.location.href = "{{ route('cart.index') }}"; };
            } else {
                addSelectedToCartBtn.disabled = true;
                addSelectedToCartBtn.classList.remove('btn-primary');
                addSelectedToCartBtn.classList.add('btn-danger');
                addSelectedToCartBtn.textContent = 'Go to cart';
                addSelectedToCartBtn.onclick = null;
            }
            if(note) note.style.display = 'none';
        } else {
            addSelectedToCartBtn.classList.remove('btn-primary');
            addSelectedToCartBtn.classList.add('btn-danger');
            addSelectedToCartBtn.textContent = 'Add selected tests to cart (replace existing)';
            addSelectedToCartBtn.onclick = null;
            addSelectedToCartBtn.disabled = selected.length < 2;
            if(note) note.style.display = (selected.length < 2) ? 'block' : 'none';
        }
    }

    // ---------- Storage helpers ----------
    function saveSelectedToStorage(){
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(selected)); } catch(e){ console.warn(e); }
    }

    function loadSelectedFromStorage(){
        selected = [];
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if(!raw) return;
            const arr = JSON.parse(raw);
            if(!Array.isArray(arr)) return;
            arr.forEach(item => {
                const btn = document.querySelector(`.add-btn[data-id="${item.id}"]`);
                let name = item.name, price = item.price;
                if(btn){
                    const card = btn.closest('.test-card');
                    const titleEl = card ? card.querySelector('.card-title') : null;
                    const priceAttr = btn.dataset.price;
                    if(!name && titleEl) name = titleEl.textContent.trim();
                    if((price === undefined || price === null || price === '') && priceAttr) price = parseFloat(priceAttr);
                }
                if(name && (typeof price === 'number' || !isNaN(parseFloat(price)))) {
                    selected.push({ id: Number(item.id), name: name, price: Number(price) });
                }
            });
            selected.forEach(s => {
                const btn = document.querySelector(`.add-btn[data-id="${s.id}"]`);
                if(btn) markButtonSelected(btn);
            });
            updatePanel();
        } catch(e){ console.warn(e); }
    }

    function markButtonSelected(el){
        if(!el) return;
        el.classList.remove('add-to-cart');
        el.classList.add('go-to-cart','btn-go-cart');
        el.setAttribute('aria-pressed','true');
        el.setAttribute('title','Selected');
        el.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>';
        el.style.justifyContent = 'center';
        el.style.padding = '8px 10px';
    }

    function markButtonUnselected(el){
        if(!el) return;
        el.classList.remove('go-to-cart','btn-go-cart');
        el.classList.add('add-to-cart');
        el.setAttribute('aria-pressed','false');
        el.setAttribute('title','Add to selection');
        el.innerHTML = '<span class="btn-text">Add</span>';
        el.style.justifyContent = 'center';
        el.style.padding = '8px 14px';
    }

    function clearLocalCustomizeState(){
        try {
          localStorage.removeItem(STORAGE_KEY);
          localStorage.removeItem(ADDED_FLAG);
          localStorage.setItem(SYNC_KEY, Date.now().toString());
        } catch(e){ console.warn('clearLocalCustomizeState storage error', e); }

        // clear in-memory
        selected = [];

        // reset buttons UI
        document.querySelectorAll('.add-btn, .go-to-cart, .btn-go-cart').forEach(btn => {
            try { markButtonUnselected(btn); } catch(e){ /* ignore */ }
        });

        // update panel & actions
        updatePanel();
        updateActionButtonState();
    }

    // ---------- Add/Remove toggle ----------
    function toggleSelection(btn){
        const id = Number(btn.dataset.id);
        const priceRaw = (btn.dataset.price || '0').toString().replace(/,/g,'');
        const price = parseFloat(priceRaw || 0);
        const name = btn.closest('.test-card').querySelector('.card-title').textContent.trim();

        const idx = selected.findIndex(s => s.id === id);
        if(idx === -1){
            // ADD
            selected.push({ id, name, price, addedAt: Date.now() });
            markButtonSelected(btn);
            setModalAddBtnState(id);
            const newCount = selected.length;
            if(newCount >= 1) showDiscountPopupForCount(newCount);
        } else {
            // REMOVE
            selected.splice(idx,1);
            markButtonUnselected(btn);
            setModalAddBtnState(id);
        }

        try { localStorage.removeItem(ADDED_FLAG); } catch(e){}
        updatePanel();
        saveSelectedToStorage();
    }

    // Bind add buttons
    function getAddButtons(){ return Array.from(document.querySelectorAll('.add-btn')); }
    function bindAddButtons(){
        getAddButtons().forEach(btn => {
            if(!btn.classList.contains('add-to-cart') && !btn.classList.contains('go-to-cart')){
                btn.classList.add('add-to-cart');
                btn.innerHTML = '<span class="btn-text">Add</span>';
            }
            if(btn.dataset.bound === '1') return;
            btn.addEventListener('click', function(){
                toggleSelection(this);
            });
            btn.dataset.bound = '1';
        });
    }

    // Remove from selected list handler
    selectedList.addEventListener('click', function(e){
        const target = e.target.closest('.remove-item');
        if(target){
            const id = Number(target.dataset.id);
            selected = selected.filter(s => s.id !== id);
            const btn = document.querySelector(`.add-btn[data-id="${id}"]`);
            if(btn) markButtonUnselected(btn);

            try { localStorage.removeItem(ADDED_FLAG); } catch(e){}
            updatePanel();
            saveSelectedToStorage();
            setModalAddBtnState(id);
        }
    });

    // ---------- Read more modal wiring ----------
    const readMoreLinks = Array.from(document.querySelectorAll('.read-more'));
    const modalPriceEl = document.getElementById('modal-price');
    const modalMrpEl = document.getElementById('modal-mrp');
    const modalOffEl = document.getElementById('modal-off');
    const modalSaveEl = document.getElementById('modal-save');
    const modalAddBtn = document.getElementById('modal-add-btn');
    const legacySell = document.getElementById('mSell');
    const legacyMrp = document.getElementById('mMrp');
    const legacyOff = document.getElementById('mOff');
    const legacySave = document.getElementById('mSave');

    function setModalAddBtnState(id){
        if(!modalAddBtn) return;
        modalAddBtn.dataset.id = id || '';
        const have = selected.some(s => Number(s.id) === Number(id));
        if(have){
            modalAddBtn.classList.remove('add-to-cart');
            modalAddBtn.classList.add('go-to-cart','btn-go-cart');
            modalAddBtn.innerHTML = '<i class="fa fa-check me-2"></i> Selected';
            modalAddBtn.title = 'Selected';
        } else {
            modalAddBtn.classList.remove('go-to-cart','btn-go-cart');
            modalAddBtn.classList.add('add-to-cart');
            modalAddBtn.innerHTML = '<i class="fa fa-cart-plus me-2"></i> Add to Selection';
            modalAddBtn.title = 'Add to selection';
        }
    }

    // Commented out since Read More now redirects to detail page directly
    /*
    readMoreLinks.forEach(link => {
        link.addEventListener('click', function(){
            const title = this.dataset.title || 'Details';
            const desc = this.dataset.desc || 'No description available.';
            const price = Number(this.dataset.price || 0);
            const mrp = Number(this.dataset.mrp || 0);
            const card = this.closest('.test-card');
            const btn = card ? card.querySelector('.add-btn') : null;
            const id = btn ? Number(btn.dataset.id) : (card ? Number(card.querySelector('.add-btn')?.dataset.id || 0) : 0);

            document.getElementById('readMoreModalLabel').textContent = title;
            document.getElementById('readMoreModalBody').innerHTML = desc.replace(/\n/g,'<br>');

            if(modalPriceEl) modalPriceEl.textContent = '₹' + formatRupee(price);
            if(modalMrpEl && mrp > 0 && mrp > price){
                modalMrpEl.textContent = '₹' + formatRupee(mrp);
                modalMrpEl.style.display = '';
            } else if(modalMrpEl){
                modalMrpEl.style.display = 'none';
            }
            if(modalOffEl && mrp > 0 && mrp > price){
                const off = Math.round((1 - (price/mrp)) * 100);
                modalOffEl.textContent = off + '%';
                modalOffEl.style.display = '';
            } else if(modalOffEl){
                modalOffEl.style.display = 'none';
            }
            if(modalSaveEl && mrp > 0 && mrp > price){
                modalSaveEl.textContent = 'You save ₹' + formatRupee(mrp - price);
                modalSaveEl.style.display = '';
            } else if(modalSaveEl){
                modalSaveEl.style.display = 'none';
            }

            if(legacySell) legacySell.textContent = '₹' + formatRupee(price);
            if(legacyMrp) legacyMrp.textContent = (mrp > 0 && mrp > price) ? '₹' + formatRupee(mrp) : '';
            if(legacyOff) legacyOff.textContent = (mrp > 0 && mrp > price) ? Math.round((1 - (price/mrp)) * 100) + '%' : '';
            if(legacySave) legacySave.textContent = (mrp > 0 && mrp > price) ? 'You save ₹' + formatRupee(mrp - price) : '';

            if(btn) modalAddBtn.dataset.id = btn.dataset.id;
            else modalAddBtn.dataset.id = '';

            setModalAddBtnState(id);

            if(readMoreModal) readMoreModal.show();
        });
    });
    */

    if(modalAddBtn){
        modalAddBtn.addEventListener('click', function(){
            const id = Number(this.dataset.id);
            if(!id) return;
            const btn = document.querySelector(`.add-btn[data-id="${id}"]`);
            if(btn) toggleSelection(btn);
            if(readMoreModal) readMoreModal.hide();
        });
    }

    // ---------- Add selected to cart (replace) ----------
    if(addSelectedToCartBtn){
      addSelectedToCartBtn.addEventListener('click', function(e){
          if(localStorage.getItem(ADDED_FLAG) === '1'){
              return;
          }
          if(selected.length < 2){
              alert('Please select at least 2 tests to create your package.');
              return;
          }
          if(replaceModal) {
              replaceModal.show();
          } else if(confirm('This will remove existing cart items and add the selected tests. Continue?')) {
              triggerReplaceAdd({ as_package:false }).catch(()=>{});
          }
      });
    }

    const confirmReplaceBtn = document.getElementById('confirm-replace-btn');
    if(confirmReplaceBtn){
        confirmReplaceBtn.addEventListener('click', function(){
            this.disabled = true;
            this.textContent = 'Working...';
            triggerReplaceAdd({ as_package: false }).finally(() => {
                this.disabled = false;
                this.textContent = 'Yes — Replace & Add';
                if(replaceModal) replaceModal.hide();
            });
        });
    }

    async function triggerReplaceAdd(opts = { as_package: false }){
        if(selected.length < 1) return Promise.reject(new Error('Select tests first'));
        addSelectedToCartBtn.disabled = true;
        const prevText = addSelectedToCartBtn.textContent;
        addSelectedToCartBtn.textContent = 'Processing...';
        computeDiscounts();
        const payloadTests = selected.map((s, idx) => ({
            id: s.id,
            name: s.name,
            original_price: Number(s.price),
            discount_percent: Number(s.discountPercent || 0),
            discounted_price: Number(s.discountedPrice || s.price),
            position: idx + 1,
            from_custom_package: true
        }));

        try {
            const res = await fetch("{{ route('customize.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    tests: payloadTests,
                    replace_cart: true,
                    as_package: !!opts.as_package
                })
            });

            const resClone = res.clone();

            if(!res.ok){
                let serverText = '';
                const ctype = res.headers.get('Content-Type') || '';
                if(ctype.includes('application/json')){
                    const j = await res.json();
                    serverText = j.message || JSON.stringify(j);
                } else {
                    serverText = await resClone.text();
                    if(serverText.length > 1200) serverText = serverText.slice(0,1200) + '...';
                }
                throw new Error(`Server returned ${res.status}: ${serverText}`);
            }

            let data;
            try { data = await res.json(); } catch (parseErr){
                const text = await resClone.text();
                throw new Error('Response not JSON. Server returned: ' + (text.slice(0,1200) || 'empty'));
            }

                        if(data.success){
                  saveSelectedToStorage();
                  localStorage.setItem(ADDED_FLAG, '1');

                  // refresh cart badge from server
                  if (window.refreshCartBadge) {
                      window.refreshCartBadge();
                  }

                  window.location.href = "{{ route('cart.index') }}";
              }

            else {
                throw new Error(data.message || 'Failed to add selected tests to cart');
            }
        } catch(err){
            console.error('triggerReplaceAdd error:', err);
            alert('Error: ' + (err.message || 'Unknown error — check console/network tab.'));
            addSelectedToCartBtn.disabled = false;
            addSelectedToCartBtn.textContent = prevText;
            return Promise.reject(err);
        }
    }

 
    // ---------- Search UI ----------
    const searchInput = document.getElementById('search-tests');
    const clearBtn = document.getElementById('clear-search');
    function toggleClearBtn() {
      if (!searchInput || !clearBtn) return;
      const hasText = searchInput.value && searchInput.value.trim().length > 0;
      clearBtn.classList.toggle('visible', !!hasText);
    }
    if (searchInput) {
      searchInput.addEventListener('input', function(e){
        toggleClearBtn();
        const q = this.value.toLowerCase();
        document.querySelectorAll('#tests-row .test-card').forEach(card => {
            const name = card.dataset.name || '';
            const matches = name.includes(q);
            if(matches) card.classList.remove('d-none'); else card.classList.add('d-none');
        });
      });
      if (clearBtn) {
        clearBtn.addEventListener('click', function(e){
          e.preventDefault();
          searchInput.value = '';
          searchInput.dispatchEvent(new Event('input', { bubbles: true }));
          toggleClearBtn();
          searchInput.focus();
        });
      }
      toggleClearBtn();
      const form = document.getElementById('customizeSearchForm');
      if(form){
        form.addEventListener('submit', function(e){
          e.preventDefault();
        });
      }
    }

    // ---------- View All toggle ----------
    (function(){
      const viewBtn   = document.getElementById('view-all-btn');
      if (!viewBtn) return;

      const EXTRA_CLASS  = 'extra-test';
      const HIDDEN_CLASS = 'd-none';
      const testsGrid    = document.getElementById('tests-row');

      // helper with "isInit" flag so we don't scroll on first load
      function setExpandedState(expanded, isInit){
        document.querySelectorAll('.' + EXTRA_CLASS).forEach(el => {
          if (expanded) el.classList.remove(HIDDEN_CLASS);
          else          el.classList.add(HIDDEN_CLASS);
        });

        viewBtn.textContent = expanded ? 'Show less' : 'View all tests';
        viewBtn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        viewBtn.dataset.expanded = expanded ? '1' : '0';

        // ⛔️ don't scroll on initial load
        if (isInit) return;

        if (!testsGrid) return;

        const stickyOffset = 110;
        const rect         = testsGrid.getBoundingClientRect();
        const absoluteTop  = window.pageYOffset + rect.top;

        if (!expanded) {
          // when collapsing -> scroll a bit up to keep grid visible
          const target = Math.max(absoluteTop - stickyOffset, 0);
          window.scrollTo({ top: target, behavior: 'smooth' });
        } else {
          // when expanding -> scroll slightly above grid
          window.scrollTo({ top: Math.max(absoluteTop - 80, 0), behavior: 'smooth' });
        }
      }

      // initial state: no scroll, just set text + hide extra cards
      const anyVisible = Array
        .from(document.querySelectorAll('.' + EXTRA_CLASS))
        .some(el => !el.classList.contains(HIDDEN_CLASS));

      setExpandedState(anyVisible, true); // 👈 isInit = true (no scroll)

      // button click: now we allow scroll
      viewBtn.addEventListener('click', function(e){
        const expandedNow = this.dataset.expanded === '1';
        setExpandedState(!expandedNow, false); // 👈 isInit = false (scroll OK)
      });
    })();

    // ---------- Storage sync listener (other tabs) ----------
    (function(){
      let __wc_sync_timer = null;
      window.addEventListener('storage', function(e){
        try {
          if(!e) return;
          if (e.key === STORAGE_KEY || e.key === ADDED_FLAG || e.key === SYNC_KEY) {
            clearTimeout(__wc_sync_timer);
            __wc_sync_timer = setTimeout(function(){
              try { loadSelectedFromStorage(); } catch(err){ console.warn('loadSelectedFromStorage error', err); }
              try { updatePanel(); } catch(err){ console.warn('updatePanel error', err); }
              try { updateActionButtonState(); } catch(err){ console.warn('updateActionButtonState error', err); }
            }, 80);
          }
        } catch(err){
          console.warn('storage event handler error', err);
        }
      });
    })();

    // ---------- Robust server sync ----------
    // If the server cart is empty, we must clear local customize selections.
    // This routine is called on load, pageshow, focus, visibilitychange and storage events.
    async function getServerCartCount(){
      try {
        const res = await fetch(CART_COUNT_URL, { credentials: 'same-origin', cache: 'no-store' });
        if(!res.ok) return null;
        const j = await res.json().catch(()=>null);
        return j && typeof j.count !== 'undefined' ? Number(j.count) : null;
      } catch(e){
        console.warn('getServerCartCount error', e);
        return null;
      }
    }

    async function syncWithServerCart(){
      // non-blocking but we act if server says 0
      const count = await getServerCartCount();
      if(count === null){
        // network error: do not automatically clear (we might be offline), but refresh UI from storage
        loadSelectedFromStorage();
        updatePanel();
        return;
      }
      if(count === 0){
        // server has no items -> clear local state so customize page doesn't show stale selection
        clearLocalCustomizeState();
      } else {
        // server has items -> ensure UI reflects storage (do not clear)
        loadSelectedFromStorage();
        updatePanel();
      }
    }

    // Debounced wrapper for events
    const debouncedSync = debounce(syncWithServerCart, 180);

    // Wire sync to relevant events so when checkout or cart clears, this page responds
    window.addEventListener('pageshow', function(){ debouncedSync(); });
    window.addEventListener('focus', function(){ debouncedSync(); });
    document.addEventListener('visibilitychange', function(){ if(document.visibilityState === 'visible') debouncedSync(); });
    // storage event already exists above and triggers loadSelectedFromStorage; also call sync if required
    window.addEventListener('storage', function(e){
      if(e && (e.key === 'cart_cleared' || e.key === SYNC_KEY || e.key === STORAGE_KEY || e.key === ADDED_FLAG)){
        debouncedSync();
      }
    });

    // Also call sync immediately on initial load
    (function initState(){
      // mark all add buttons unselected first
      getAddButtons().forEach(btn => markButtonUnselected(btn));
      bindAddButtons();
      // load from storage to show something quickly
      loadSelectedFromStorage();
      updatePanel();
      updateActionButtonState();
      // then verify with server to avoid stale UI
      debouncedSync();
    })();

    // ---------- View interactions already wired above ----------
    // All other handlers are active after DOMContentLoaded

});
</script>

<style>
/* ====================
   NEW wc-search styles
   ==================== */
.wc-search-wrapper { display:flex; justify-content:center; align-items:center; }
.wc-search {
  display:flex;
  align-items:center;
  width:100%;
  max-width:600px;
  background:#fff;
  border-radius:999px;
  box-shadow:0 6px 18px rgba(0,0,0,0.06);
  padding:6px 10px;
  gap:10px;
}

/* search icon (left) */
.wc-search-icon{
  background: linear-gradient(135deg,#0047ff,#00ccff);
  border:0;
  color:#fff;
  width:44px;
  height:44px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  font-size:1.05rem;
  cursor:pointer;
}
.wc-search-icon i { transform: translateY(0); }

/* field wrap to allow absolute clear button */
.wc-search-field-wrap {
  position:relative;
  flex:1 1 auto;
  min-width:0;
}

/* input */
.wc-search-input {
  width:100%;
  border:0;
  outline:0;
  padding:12px 44px 12px 16px; /* right padding to make space for clear icon */
  border-radius:999px;
  font-size:1rem;
  color:#222;
  background:transparent;
  box-sizing:border-box;
}

/* clear button inside input (absolute on right) */
.wc-search-clear {
  position:absolute;
  right:8px;
  top:50%;
  transform:translateY(-50%);
  display:none; /* hidden by default */
  align-items:center;
  justify-content:center;
  width:32px;
  height:32px;
  border-radius:50%;
  border:0;
  background:transparent;
  color:#666;
  cursor:pointer;
  font-size:0.95rem;
  transition:opacity .14s ease, transform .12s ease;
  opacity:0;
}
.wc-search-clear i { pointer-events:none; }

/* visible state toggled by JS */
.wc-search-clear.visible {
  display:inline-flex;
  opacity:1;
  transform:translateY(-50%) scale(1);
}

/* small hover feedback */
.wc-search-clear:hover { color:#222; transform:translateY(-50%) scale(1.05); }

/* fallback for very small screens */
@media (max-width:420px){
  .wc-search-icon { width:40px; height:40px; }
  .wc-search-input { padding:10px 44px 10px 12px; }
  .wc-search-clear { right:6px; width:30px; height:30px; }
}

/* ===== wc-cart-btn styles (Add/Selected buttons) ===== */
.wc-cart-btn{
  display:inline-flex; align-items:center; justify-content:center; gap:8px;
  min-height:44px; padding:8px 14px; font-weight:700; font-size:.95rem; border:none;
  border-radius:10px; transition:background .2s ease, transform .15s ease, box-shadow .15s ease;
}

/* BEFORE ADD: neutral look */
.wc-cart-btn.add-to-cart{
  background:#f0f0f0;
  color:#173b5f;
  border:1px solid rgba(14,63,108,.06);
}
.wc-cart-btn.add-to-cart:hover{
  transform:translateY(-2px);
}

/* AFTER ADD: green background for selected button (icon only) */
.wc-cart-btn.go-to-cart,
.wc-cart-btn.btn-go-cart{
  background:#9dd24a !important;
  color:#fff !important;
  border:0 !important;
  box-shadow:0 6px 12px rgba(157,210,74,.25) !important;
}
.wc-cart-btn.go-to-cart:hover,
.wc-cart-btn.btn-go-cart:hover{
  transform:translateY(-2px);
}

/* ensure icon-only selected button looks centered & compact */
.wc-cart-btn.go-to-cart i,
.wc-cart-btn.go-to-cart .fa-check {
  font-size:1.05rem;
}

/* keep modal pink button relaxed (the wc-cart-btn classes are applied, this keeps a relaxed look) */
.btn-pink { background: transparent; padding: 8px 12px; border-radius: 8px; }

/* modal cancel icon (top-right circular) */
.modal-cancel-icon {
  position: absolute;
  right: 14px;
  top: 10px;
  background: #fff;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  box-shadow: 0 6px 16px rgba(0,0,0,0.12);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;
  color: #e91e63;
  font-size: 1.1rem;
}
.modal-cancel-icon i { color: #e91e63; font-size: 1.1rem; }
.modal-cancel-icon:hover { transform: translateY(-1px); }

.modal-action-bar { background: #fff; border-top: 0; }

.percent-pill {
  display:inline-block;
  padding:4px 8px;
  border-radius:999px;
  background:#e9f9ef;
  color:#2a7b3f;
  font-weight:700;
  font-size:0.85rem;
}

.you-save-text {
  color:#0a66d6;
  font-weight:600;
  font-size:0.95rem;
}

.card-title.accent-title {
    color: #e91e63 !important;
    font-weight: 800 !important;
}
#readMoreModalLabel {
    color: #e91e63 !important;
    font-weight: 800 !important;
}
.package-desc.clamped {
    color: #111 !important;
    font-weight: 600 !important;
}
#readMoreModalBody {
    color: #111 !important;
    font-weight: 600 !important;
    line-height: 1.45;
}

.view-all-wrap { z-index: 0; }
/* 🔥 Premium Gradient Button for View All */
#view-all-btn {
    background: linear-gradient(135deg, #0066ff, #00ccff) !important;
    padding: .6rem 1.2rem !important;
    border-radius: 10px !important;
    width: 100% !important;
    max-width: 220px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    text-decoration: none !important;
    font-weight: 700 !important;
    font-size: 1rem !important;
    color: #000 !important; /* default black text */
    border: none !important;
    cursor: pointer !important;
    transition: all 0.25s ease-in-out !important;
    box-shadow: 0 0 0 transparent !important;
    gap: 8px !important; /* spacing if icon is added */
}

/* ✨ Hover */
#view-all-btn:hover {
    background: linear-gradient(135deg, #0052cc, #00b8e6) !important;
    color: #fff !important;
    transform: translateY(-3px) scale(1.04) !important;
    box-shadow: 0 0 25px rgba(0, 204, 255, 0.5) !important;
}

/* 🖱 Active (pressed) */
#view-all-btn:active {
    transform: translateY(0) scale(0.97) !important;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2) !important;
    background: linear-gradient(135deg, #0047b3, #00a3cc) !important;
    color: #fff !important;
}

/* 🎯 Focus accessibility */
#view-all-btn:focus {
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(0, 204, 255, 0.35) !important;
}

/* 🚫 Disabled */
#view-all-btn:disabled,
#view-all-btn.disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    background: linear-gradient(135deg, #9ecaff, #b5eaff) !important;
    color: #333 !important;
    box-shadow: none !important;
    transform: none !important;
}

/* 🎨 Icon Motion */
#view-all-btn i {
    transition: transform 0.25s ease-in-out !important;
}
#view-all-btn:hover i {
    transform: translateX(5px) !important;
}

@media(min-width: 992px){
  .view-all-wrap { margin-top: 18px; margin-bottom: 24px; }
  #view-all-btn { max-width: 240px; }
}
@media(max-width: 576px){
  #view-all-btn { width: 100%; max-width: none; padding: 12px 16px; }
}

.selected-header {
  color: #111;
  font-weight: 800;
  font-size: 0.95rem;
  gap: 8px;
}
.selected-header .selected-name { flex: 1 1 0; min-width: 0; }
.selected-header .selected-price { width: 110px; text-align: center; }
.selected-header .selected-remove { width: 80px; text-align: right; }

#selected-tests .list-group-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 0.5rem 0.75rem;
  border: none;
  border-bottom: 1px solid rgba(15,20,30,0.04);
  min-height: 44px;
}

#selected-tests .selected-item-name {
  flex: 1 1 0;
  min-width: 0;
  overflow: visible;
  white-space: normal;
  word-break: break-word;
  font-weight: 600;
  color: #111;
  line-height: 1.2;
}

#selected-tests .selected-item-price {
  width: 110px;
  text-align: center;
  font-weight: 700;
  color: #0a3a66;
}

#selected-tests .selected-item-remove {
  width: 80px;
  text-align: right;
}
#selected-tests .remove-item {
  min-width: 40px;
  padding: 6px 8px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
#selected-tests .remove-item i { font-size: 0.9rem; }

@media (max-width: 480px) {
  .selected-header {
    display: grid;
    grid-template-columns: 1fr auto;
    grid-column-gap: 8px;
    align-items: center;
  }
  .selected-header .selected-remove { grid-column: 2; text-align: right; }
  .selected-header .selected-price { grid-column: 2; text-align: right; margin-right: 6px; }

  #selected-tests .list-group-item {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 8px;
    align-items: center;
  }
  #selected-tests .selected-item-price {
    grid-column: 2;
    text-align: right;
  }
  #selected-tests .selected-item-remove {
    grid-column: 2;
    text-align: right;
  }
  #selected-tests .selected-item-name {
    grid-column: 1;
  }
  #selected-tests .remove-item { min-width: 56px; padding: 6px 8px; }
}

.modal-price-row {
  display: flex;
  align-items: center;
  gap: 14px;
  flex-wrap: wrap;
  min-width: 0;
}

.modal-price-row .mp-price,
#readMoreModal #modal-price,
#readMoreModal #mSell {
  font-size: 1.15rem !important;
  font-weight: 800 !important;
  color: #0a3a66 !important;
  line-height: 1;
}

.modal-price-row .mp-mrp,
#readMoreModal #modal-mrp,
#readMoreModal #mMrp {
  color: #8b98a7 !important;
  text-decoration: line-through !important;
  font-size: 0.95rem !important;
}

.modal-price-row .mp-off,
#readMoreModal #modal-off,
#readMoreModal #mOff {
  background: #e9f9ef !important;
  color: #2a7b3f !important;
  font-weight: 700 !important;
  padding: 4px 10px !important;
  border-radius: 999px !important;
  font-size: 0.85rem !important;
}

.modal-price-row .mp-save,
#readMoreModal #modal-save,
#readMoreModal #mSave {
  color: #0a66d6 !important;
  font-weight: 600 !important;
  font-size: 0.95rem !important;
  white-space: nowrap !important;
}

.wc-cart-btn { min-height:44px; padding:8px 14px; }

@media (max-width: 576px) {
  .wc-cart-btn {
    width: 100% !important;
    justify-content: center !important;
    padding: 12px 16px !important;
    font-size: 1rem !important;
  }
  .modal-action-buttons {
    flex-direction: column;
    gap: 8px;
  }
}

@media (max-width: 520px) {
  .modal-price-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  .modal-action-bar { flex-direction: column; gap: 12px; align-items: stretch; }
  .modal-action-buttons { justify-content: flex-end; width: 100%; display: flex; gap: 8px; }
}

.read-more {
    color: #0d6efd !important;
    text-decoration: none !important;
    font-weight: 600;
    cursor: pointer;
}
.read-more:hover { text-decoration: underline !important; color: #0b5ed7 !important; }

@media (max-width: 420px) {
  .modal-action-buttons button { flex: 1 1 100%; }
}

.package-style-card{
  display:flex; flex-direction:column;
  border-radius: 12px;
  background:#fff; border:1px solid #f0e9ef;
  transition: transform .12s ease, box-shadow .12s ease;
  overflow: visible;
}
.package-style-card:hover{ transform: translateY(-4px); box-shadow: 0 10px 24px rgba(33,45,70,0.06); }

.package-desc-wrapper{
  font-size: 0.95rem;
  line-height: 1.45;
  min-height: calc(3 * 1.45em);
  display:flex; flex-direction:column;
}

.package-desc{
  color:#111 !important; font-weight:600;
  display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:3;
  overflow:hidden; text-overflow:ellipsis;
  line-height:1.32rem; max-height:calc(1.32rem * 3);
  position:relative; margin:0;
}

.percent-pill { display:inline-block; padding:4px 8px; border-radius:999px; background:#e9f9ef; color:#2a7b3f; font-weight:700; font-size:0.85rem; }
.you-save-text { color:#0a66d6; font-weight:600; font-size:0.95rem; }

.modal-action-buttons {
  display: flex !important;
  align-items: center !important;
  gap: 20px !important;
}
.modal-action-buttons .btn {
  min-width: 110px;
}
@media (max-width: 576px) {
  .modal-action-buttons {
    flex-direction: column !important;
    gap: 12px !important;
    width: 100%;
    align-items: stretch !important;
  }
  .modal-action-buttons .btn {
    min-width: 0 !important;
    width: 100% !important;
  }
}
.modal-action-bar {
  padding-top: 18px !important;
  border-top: 1px solid rgba(15,20,30,0.04) !important;
}
.discount-pill {
  display: inline-block;
  padding: 6px 10px;
  border-radius: 999px;
  background: #e9f9ef !important;
  color: #2a7b3f !important;
  font-weight: 700 !important;
  font-size: 0.82rem !important;
  line-height: 1 !important;
}
.discount-pill:where(:not(:disabled)) { opacity: 1; }

@media (max-width: 420px) {
  .discount-pill { padding: 5px 8px; font-size: 0.78rem; }
}
.price-row .disc-price {
    margin-right: 10px !important;
}
.price-row .mrp {
    /* margin-left: 4px !important;
    color: #8b98a7 !important;
    text-decoration: line-through !important;
    font-size: 0.92rem !important;
    font-weight: 500; */


        color: #000 !important;
    text-decoration: line-through;
    margin-left: 6px;
    font-size: 1rem;
    font-weight: 600;
}
@media (max-width: 576px) {
    .price-row .disc-price {
        margin-right: 8px !important;
    }
    .price-row .mrp {
        margin-left: 3px !important;
    }
}
</style>
@endsection
