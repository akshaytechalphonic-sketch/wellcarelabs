@extends('maindesign')

@section('title', isset($faqPage) && !empty($faqPage->meta_title) ? $faqPage->meta_title : 'Frequently Asked Questions- Wellcare Labs')
@section('meta_description', isset($faqPage) && !empty($faqPage->meta_description) ? $faqPage->meta_description : 'Find quick answers to common questions about test booking, home sample collection, reports and payments at Wellcare Labs.')

@section('seo')
  @if(isset($faqPage) && !empty($faqPage->meta_tags))
    {!! $faqPage->meta_tags !!}
  @endif

@endsection
@section('content')

  <style>
    /* SAME CSS AS YOURS (NO CHANGE) */
    .wc-page-wrapper {
      max-width: 1500px;
      margin: 40px auto;
      background: #ffffff;
      padding: 35px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
    }

    .wc-page-wrapper h1 {
      font-size: 2.4rem;
      font-weight: 700;
      color: #0a2540;
      text-align: center;
      position: relative;
      margin-bottom: 35px;
    }

    .wc-page-wrapper h1::after {
      content: "";
      width: 80px;
      height: 4px;
      background: linear-gradient(90deg, #0047ff, #00ccff);
      border-radius: 3px;
      display: block;
      margin: 12px auto 0;
    }

    .faq-intro {
      font-size: 15px;
      line-height: 1.7;
      color: #475569;
      text-align: center;
      margin-bottom: 25px;
    }

    .faq-list {
      margin-top: 15px;
    }

    .faq-item {
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      padding: 14px 16px;
      margin-bottom: 10px;
      background: #f9fafb;
      cursor: pointer;
      transition: background 0.2s, box-shadow 0.2s;
    }

    .faq-item:hover {
      background: #f1f5f9;
      box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
    }

    .faq-question {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-weight: 600;
      font-size: 15px;
      color: #0a2540;
    }

    .faq-question span {
      flex: 1;
      margin-right: 10px;
    }

    .faq-toggle-icon {
      font-size: 18px;
    }

    .faq-answer {
      margin-top: 8px;
      font-size: 14px;
      line-height: 1.7;
      color: #475569;
      display: none;
    }

    .faq-item.open .faq-answer {
      display: block;
    }

    .faq-item.open .faq-toggle-icon {
      transform: rotate(90deg);
    }

    @media (max-width: 768px) {
      .wc-page-wrapper {
        margin: 15px;
        padding: 22px;
      }

      .wc-page-wrapper h1 {
        font-size: 1.9rem;
      }
    }

    /* FAQ Pagination Styling */
    .faq-pagination-container {
      margin-top: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .faq-pagination-container .pagination {
      display: flex;
      gap: 6px;
      list-style: none;
      padding: 0;
      margin: 0;
      align-items: center;
    }

    .faq-pagination-container .page-item .page-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 38px;
      height: 38px;
      padding: 0 14px;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      background: #ffffff;
      color: #0a2540;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .faq-pagination-container .page-item.active .page-link {
      background: linear-gradient(90deg, #0047ff, #00ccff);
      color: #ffffff;
      border-color: transparent;
      font-weight: 600;
      box-shadow: 0 2px 6px rgba(0, 71, 255, 0.25);
    }

    .faq-pagination-container .page-item:not(.active):not(.disabled) .page-link:hover {
      background: #f1f5f9;
      border-color: #cbd5e1;
      color: #0047ff;
    }

    .faq-pagination-container .page-item.disabled .page-link {
      color: #94a3b8;
      background: #f8fafc;
      border-color: #e2e8f0;
      cursor: not-allowed;
      opacity: 0.6;
    }

    @media (max-width: 576px) {
      .faq-pagination-container .pagination {
        flex-wrap: wrap;
        justify-content: center;
        gap: 4px;
      }

      .faq-pagination-container .page-item .page-link {
        min-width: 32px;
        height: 32px;
        padding: 0 8px;
        font-size: 13px;
      }
    }
  </style>

  <section class="wc-page-wrapper">
    <h1>Frequently Asked Questions</h1>

    <p class="faq-intro">
      Find quick answers to common questions about test booking, home sample collection,
      reports and payments at Wellcare Labs.
    </p>

    <div class="faq-list">
      @forelse($faqs as $index => $faq)
        <div class="faq-item">
          <div class="faq-question">
            <span>{{ ($faqs->currentPage() - 1) * $faqs->perPage() + $index + 1 }}. {{ $faq->question }}</span>
            <span class="faq-toggle-icon">➤</span>
          </div>
          <div class="faq-answer">
            {!! nl2br(e($faq->answer)) !!}
          </div>
        </div>
      @empty
        <p style="text-align:center;color:#64748b;">No FAQs available right now.</p>
      @endforelse
    </div>

    @if(method_exists($faqs, 'hasPages') && $faqs->hasPages())
      <div class="faq-pagination-container">
        {{ $faqs->links('pagination::bootstrap-4') }}
      </div>
    @endif
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const items = document.querySelectorAll('.faq-item');

      items.forEach(item => {
        item.addEventListener('click', function () {
          items.forEach(i => {
            if (i !== item) i.classList.remove('open');
          });
          item.classList.toggle('open');
        });
      });
    });
  </script>

@endsection