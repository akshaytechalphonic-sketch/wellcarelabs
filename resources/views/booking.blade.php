

@extends('maindesign')

@section('title', 'Make a Booking - Wellcare Labs')

@section('meta_description', 'Book your lab tests easily with Wellcare Labs. Choose health checkup packages or diagnostic tests, enjoy home sample collection, and get fast, accurate reports.')


@section('content')
{{-- <div style="text-align:center;margin-top:50px;margin-bottom:30px;">
  <h1 style="font-size:2.4rem;font-weight:700;color:#0a2540;position:relative;display:inline-block;">
  Make a Booking
<div style="width:80px;height:4px;margin:10px auto 16px;
                border-radius:3px;
                background:linear-gradient(90deg,#0047ff,#00ccff);">
    </div>  </h1>
</div> --}}
<div class="bk-wrap">
  <div class="bk-card">
    {{-- <h1 class="bk-title">Make a Booking</h1> --}}
    <div style="text-align:center;margin-top:30px;margin-bottom:0px;">
  <h1 style="font-size:2.4rem;font-weight:700;color:#0a2540;position:relative;display:inline-block;">
  Make a Booking
<div style="width:80px;height:4px;margin:10px  auto 16px;
                border-radius:3px;
                background:linear-gradient(90deg,#0047ff,#00ccff);">
    </div>  </h1>
</div>

    @if(($count ?? 0) > 0)
      <p class="bk-sub">
        You’ve selected
        <strong>{{ $count }}</strong>
        {{ \Illuminate\Support\Str::plural('item', $count) }}.
        You can continue to checkout.
      </p>

      {{-- quick summary row --}}
      @if(!empty($items))
        <div class="bk-summary">
          <div class="bk-summary-row">
            <span>Cart Total</span>
            {{-- Match cart style: ₹ 1,849 (no decimals) --}}
            <strong>₹ {{ number_format((int)round($total ?? 0), 0) }}</strong>
          </div>
        </div>
      @endif

      <div class="bk-actions">
        <a href="{{ route('checkout.index') }}" class="bk-btn bk-btn-primary btn-more">Proceed to Checkout</a>
        <a href="{{ url('/packages') }}" class="bk-btn bk-btn-ghost btn-more">Add / Change Plan</a>
      </div>

    @else
      <div class="bk-empty">
        <div class="bk-icon" aria-hidden="true">🩺</div>
        <h2 class="bk-empty-title">For booking, please select a plan</h2>
        <p class="bk-empty-text">Choose a test or package to continue with your booking.</p>

        <div class="bk-actions">
          <a href="{{ url('/packages') }}" class="bk-btn bk-btn-primary btn-more">Browse Wellcare Smart Health Packages</a>
          <a href="{{ url('/packages/special') }}" class="bk-btn bk-btn-primary btn-more">Browse Wellcare Special Packages</a>
          <a href="{{ route('customize.index') }}" class="bk-btn bk-btn-primary btn-more">Browse Wellcare Customise Packages</a>
          <a href="{{ url('/services') }}" class="bk-btn bk-btn-primary btn-more">Browse Wellcare All Tests</a>
        </div>
      </div>
    @endif
  </div>
</div>

<style>
  :root{
    --bk-primary:#0a66c2;
    --bk-primary-600:#095bb0;
    --bk-text:#0b1a2b;
    --bk-muted:#6b7280;
    --bk-border:#e5e7eb;
    --bk-bg:#f6f8fb;
    --bk-card:#ffffff;

    /* compact tab button palette */
    --tab-bg:#eaf2fb;
    --tab-border:#cfe0f5;
    --tab-text:#0a66c2;
    --tab-bg-hover:#dbeafe;
    --tab-border-hover:#bcd7f9;
    --tab-shadow:0 1px 2px rgba(10,102,194,.08);
  }

  body{ background: var(--bk-bg); }
  .bk-wrap{ max-width: 860px; margin: 32px auto; padding: 12px; }
  .bk-card{
    background: var(--bk-card);
    border-radius: 16px;
    border:1px solid var(--bk-border);
    box-shadow: 0 8px 25px rgba(0,0,0,.08);
    padding: 22px 20px;
  }
  .bk-title{
    font-size:1.8rem; font-weight:800; color:var(--bk-text); margin: 0 0 8px;
  }
  .bk-sub{ color:var(--bk-muted); margin:0 0 16px; }
  .bk-empty{ text-align:center; padding: 10px 6px 6px; }
  .bk-icon{ font-size:44px; margin-bottom:6px; }
  .bk-empty-title{ margin:6px 0 6px; font-size:1.25rem; color:var(--bk-text); }
  .bk-empty-text{ color:var(--bk-muted); margin:0 0 14px; }

  .bk-summary{ margin-top: 8px; border-top:1px dashed var(--bk-border); padding-top: 12px; margin-bottom: 8px; }
  .bk-summary-row{
    display:flex; align-items:center; justify-content:space-between;
    font-size:1.05rem; color:var(--bk-text);
  }

  /* === Compact, 2-per-row, tab-like buttons === */
  .bk-actions{
    display:grid;
    grid-template-columns: repeat(2, minmax(0,1fr));  /* 2 per row */
    gap:10px;
    margin-top: 10px;
  }
  .bk-btn{
    display:inline-block;
    width:100%;
    padding: 10px 12px;                    /* less height */
    border-radius: 12px;                   /* tab-ish */
    font-weight:700;
    font-size: 0.98rem;                    /* compact text */
    line-height: 1.1;
    text-decoration:none !important;       /* no underline */
    border:1px solid transparent;
    transition: transform .05s ease, background .15s ease, border-color .15s ease, box-shadow .15s ease;
    box-shadow: var(--tab-shadow);
  }

  /* Primary tab look (soft fill, not heavy gradient) */
  .bk-btn-primary{
    background: var(--tab-bg);
    color: var(--tab-text);
    border-color: var(--tab-border);
  }
  .bk-btn-primary:hover{
    background: var(--tab-bg-hover);
    border-color: var(--tab-border-hover);
    transform: translateY(-1px);
  }
  .bk-btn-primary:active{ transform: translateY(0); }

  /* Secondary/ghost remains calmer */
  .bk-btn-ghost{
    background:#fff;
    color:var(--bk-text);
    border:1px solid var(--bk-border);
  }
  .bk-btn-ghost:hover{
    background:#f8fbff;
    border-color:#d9e7f7;
    transform: translateY(-1px);
  }

  /* New class requested (kept for future hooks) */
  .btn-more{ text-decoration: none !important; }

  /* Responsive: stack on small screens */
  @media (max-width: 560px){
    .bk-actions{ grid-template-columns: 1fr; } /* 1 per row on small screens */
  }
</style>
@endsection
