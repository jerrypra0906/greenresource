<section class="hero">
    <div class="container hero-grid">
        <div>
            @if($section->title)
                <h1 class="hero-title">{{ $section->title }}</h1>
            @endif
            @if($section->body)
                <p class="hero-subtitle">{!! $section->body !!}</p>
            @endif
        </div>
        @if($section->media)
            <div class="hero-media">
                <img src="{{ $section->media->url }}" alt="{{ $section->media->alt_text ?? $section->title }}" loading="lazy" style="width: 100%; border-radius: 1rem;" />
            </div>
        @endif
    </div>
</section>

