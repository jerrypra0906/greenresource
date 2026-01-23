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
                <x-app-image 
                    src="{{ $section->media->url }}" 
                    alt="{{ $section->media->alt_text ?? $section->title }}"
                    sizes="(max-width: 768px) 100vw, 80vw"
                    style="border-radius: 0.5rem;"
                />
            </div>
        @endif
    </div>
</section>

