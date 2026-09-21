<div class="container mb-5">
    <h3 class="fw-bold mb-4" style="color: #0f172a;">{{ $section->content['title'] ?? '' }}</h3>
    @if(!empty($section->content['subtitle']))
        <p class="text-muted mb-4" style="font-size: 1rem;">{{ $section->content['subtitle'] }}</p>
    @endif
    <div class="milestone-timeline" style="position: relative; padding-left: 30px; border-left: 2px solid #e2e8f0; margin-left: 10px;">
        {!! $section->content['body'] ?? '' !!}
    </div>
</div>
