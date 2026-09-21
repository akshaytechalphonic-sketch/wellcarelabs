<div class="container mb-5">
    <div class="profile-card" style="background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05); padding: 45px; margin-bottom: 50px;">
        <div class="row align-items-center">
            @if(!empty($section->content['image_url']))
            <div class="col-lg-4 text-center">
                <div class="founder-img-wrapper" style="border-radius: 20px; overflow: hidden; border: 4px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 320px; margin: 0 auto 30px;">
                    <img src="{{ $section->content['image_url'] }}" alt="{{ $section->content['title'] ?? '' }}" class="img-fluid">
                </div>
            </div>
            @endif
            <div class="{{ !empty($section->content['image_url']) ? 'col-lg-8' : 'col-lg-12' }}">
                @if(!empty($section->content['subtitle']))
                    <div class="founder-title-badge" style="background: #eff6ff; color: #2563eb; padding: 6px 16px; border-radius: 50px; font-weight: 600; font-size: 0.9rem; display: inline-block; margin-bottom: 15px;">
                        {{ $section->content['subtitle'] }}
                    </div>
                @endif
                <h2 class="founder-name" style="font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">{{ $section->content['title'] ?? '' }}</h2>
                <div class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
                    {!! $section->content['body'] ?? '' !!}
                </div>
            </div>
        </div>
    </div>
</div>
