<section class="hero">
    <div class="container hero-grid">
        <div>
            @if($section->title)
                <h1 class="hero-title">{{ $section->title }}</h1>
            @endif
            @if($section->body)
                <p class="hero-subtitle">{!! $section->body_html !!}</p>
            @endif
        </div>
        @if($section->media)
            <div class="hero-media">
                <x-app-image 
                    src="{{ $section->media->url }}" 
                    alt="{{ $section->media->alt_text ?? $section->title }}"
                    fill
                    sizes="(max-width: 768px) 100vw, 50vw"
                    class="hero-media-image"
                    style="border-radius: 1rem;"
                />
            </div>
        @endif
    </div>
</section>

