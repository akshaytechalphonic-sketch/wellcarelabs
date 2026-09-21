<div class="container mb-5">
    <div class="founder-quote" style="background: #f8fafc; border-left: 5px solid #3b82f6; padding: 20px 25px; font-style: italic; font-size: 1.1rem; color: #334155; border-radius: 0 16px 16px 0; margin-bottom: 30px; position: relative;">
        <i class="fa fa-quote-left" style="color: #bfdbfe; font-size: 1.8rem; margin-right: 10px;"></i>
        {!! $section->content['body'] ?? '' !!}
        @if(!empty($section->content['author']))
            <div class="mt-2 text-end fw-bold" style="font-size: 0.95rem; color: #64748b;">— {{ $section->content['author'] }}</div>
        @endif
    </div>
</div>
