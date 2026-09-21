<div class="container mb-5">
    @if(!empty($section->content['title']))
        <h3 class="fw-bold mb-3" style="color: #0f172a;">{{ $section->content['title'] }}</h3>
    @endif
    @if(!empty($section->content['subtitle']))
        <h5 class="text-secondary mb-4" style="font-size: 1.1rem; font-weight: 500;">{{ $section->content['subtitle'] }}</h5>
    @endif
    <div style="line-height: 1.8; font-size: 1.05rem; color: #475569;">
        {!! $section->content['body'] ?? '' !!}
    </div>
</div>
