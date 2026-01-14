<section class="section">
    <div class="container">
        @if($section->title)
            <div class="section-header">
                <h2 class="section-title">{{ $section->title }}</h2>
            </div>
        @endif
        @if($section->body)
            <div class="section-content">
                {!! $section->body_html !!}
            </div>
        @endif
        @if($section->media)
            <div class="section-media" style="margin-top: 2rem;">
                <img src="{{ $section->media->url }}" alt="{{ $section->media->alt_text ?? $section->title }}" loading="lazy" style="max-width: 100%; border-radius: 0.5rem;" />
            </div>
        @endif
    </div>
</section>

