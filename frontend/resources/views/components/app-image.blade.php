@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'fill' => false,
    'priority' => false,
    'loading' => null,
    'sizes' => null,
    'class' => '',
    'style' => '',
    'objectFit' => 'cover',
    'quality' => 75,
    'fallback' => null,
])

@php
    // Determine loading strategy
    $loadingAttr = $loading ?? ($priority ? 'eager' : 'lazy');
    
    // Generate unique ID for this image instance
    $imageId = 'app-image-' . uniqid();
    
    // Build container classes
    $containerClasses = 'app-image-container';
    if ($fill) {
        $containerClasses .= ' app-image-fill';
    }
    if ($class) {
        $containerClasses .= ' ' . $class;
    }
    
    // Build container styles
    $containerStyles = $style;
    if ($fill) {
        // Don't add position: relative here - let parent handle positioning
        // The container will be positioned by parent CSS if needed
    } elseif ($width && $height) {
        // Set aspect ratio to prevent CLS
        $aspectRatio = $height / $width;
        $containerStyles .= " aspect-ratio: {$width} / {$height};";
    }
    
    // Build image styles
    $imageStyles = '';
    if ($fill) {
        $imageStyles .= ' position: absolute; top: 0; left: 0; width: 100%; height: 100%;';
    }
    $imageStyles .= " object-fit: {$objectFit};";
@endphp

@php
    // Build final container style
    $finalContainerStyle = $containerStyles;
    if (!$fill && $width && $height) {
        $finalContainerStyle = "width: {$width}px; aspect-ratio: {$width} / {$height}; " . $finalContainerStyle;
    }
@endphp

<div 
    class="{{ $containerClasses }}" 
    style="{{ trim($finalContainerStyle) }}"
    data-image-id="{{ $imageId }}"
>
    {{-- Skeleton Shimmer Overlay --}}
    <div class="app-image-skeleton" data-skeleton="{{ $imageId }}" aria-hidden="true">
        <div class="app-image-shimmer"></div>
    </div>
    
    {{-- Actual Image --}}
    <img
        id="{{ $imageId }}"
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="{{ $loadingAttr }}"
        @if($priority)
            fetchpriority="high"
        @endif
        @if($sizes)
            sizes="{{ $sizes }}"
        @endif
        @if($width && !$fill)
            width="{{ $width }}"
        @endif
        @if($height && !$fill)
            height="{{ $height }}"
        @endif
        style="{{ $imageStyles }}"
        class="app-image"
        data-image-src="{{ $src }}"
        onerror="this.classList.add('app-image-error'); this.closest('.app-image-container').querySelector('[data-skeleton]')?.classList.add('app-image-error-skeleton'); {{ $fallback ? "this.src = '{$fallback}';" : '' }}"
        onload="window.handleImageLoad && window.handleImageLoad('{{ $imageId }}')"
    />
    
    {{-- Fallback Error State --}}
    <div class="app-image-error-placeholder" data-error="{{ $imageId }}" style="display: none;" aria-hidden="true">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
        </svg>
        <span>Image unavailable</span>
    </div>
</div>
