<div class="founder-hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 80px 20px; text-align: center; border-radius: 0 0 35px 35px; margin-bottom: 50px; position: relative;">
    <div class="container">
        <h1>{{ $section->content['title'] ?? '' }}</h1>
        @if(!empty($section->content['subtitle']))
            <p style="font-size: 1.25rem; max-width: 800px; margin: 15px auto 0; color: #94a3b8; line-height: 1.65;">{{ $section->content['subtitle'] }}</p>
        @endif
        <div class="accent-bar" style="width: 120px; height: 4px; background: #3b82f6; margin: 25px auto 0; border-radius: 2px;"></div>
    </div>
</div>
