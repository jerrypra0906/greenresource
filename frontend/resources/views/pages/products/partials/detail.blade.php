{{-- Product Detail Partial --}}
{{-- Used for both full page render and AJAX partial updates --}}
{{-- Required variables: $categoryKey, $categoryTitle, $categoryDescription, $products --}}

{{-- Category Title + Description --}}
<div class="category-box">
    <div class="category-title-col">
        <h2 class="category-title">{{ $categoryTitle }}</h2>
    </div>
    <div class="category-desc-col">
        @foreach(explode("\n\n", $categoryDescription) as $paragraph)
            <p class="category-desc">{{ $paragraph }}</p>
        @endforeach
    </div>
</div>

{{-- Product Details Layout --}}
@php
    $imageMap = [
        'feedstocks' => 'assets/images/feedstocks-palm-oil.jpg',
        'methyl' => 'assets/images/methyl-ester.jpg',
        'others' => 'assets/images/others.jpeg',
    ];
    $imagePath = $imageMap[$categoryKey] ?? 'assets/images/products.png';
@endphp

<div class="product-layout">
    {{-- Left: Image --}}
    <div class="product-image">
        <x-app-image 
            src="{{ asset($imagePath) }}" 
            alt="{{ $categoryTitle }} - Product Image"
            fill
            sizes="(max-width: 992px) 100vw, 45vw"
        />
    </div>
    
    {{-- Right: Product Cards --}}
    <div class="product-cards-container">
        @if($categoryKey === 'feedstocks')
            <div class="product-feedstocks-grid">
                @foreach($products as $product)
                <div class="product-card-item">
                    <h3 class="product-card-title">{{ $product['name'] }}</h3>
                    <p class="product-card-desc">{{ $product['desc'] }}</p>
                </div>
                @endforeach
            </div>
        @else
            {{-- Waste-based FAME and Others: 2 cards in centered layout --}}
            <div class="product-two-column">
                @foreach($products as $product)
                <div class="product-card-item">
                    <h3 class="product-card-title">{{ $product['name'] }}</h3>
                    <p class="product-card-desc">{{ $product['desc'] }}</p>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Include product layout styles if not already included --}}
@once
@push('styles')
<style>
    .product-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        align-items: start;
    }
    @media (min-width: 992px) {
        .product-layout {
            grid-template-columns: 1fr 1.2fr;
        }
    }
    .product-image {
        width: 100%;
        border-radius: 1.1rem;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        position: relative;
        aspect-ratio: 4 / 3;
    }
    .product-image img,
    .product-image .app-image-container {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }
    .product-cards-container {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    .product-feedstocks-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        width: 100%;
        align-items: stretch;
    }
    @media (min-width: 768px) {
        .product-feedstocks-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .product-feedstocks-grid .product-card-item:last-child:nth-child(odd):not(:only-child) {
            grid-column: 1 / -1;
            max-width: calc(50% - 0.75rem);
            justify-self: center;
        }
    }
    @media (min-width: 992px) {
        .product-feedstocks-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .product-feedstocks-grid .product-card-item:last-child:nth-child(odd):not(:only-child) {
            grid-column: auto;
            max-width: none;
            justify-self: stretch;
        }
        .product-feedstocks-grid .product-card-item:last-child:nth-child(3n+1):not(:only-child) {
            grid-column: 2;
        }
    }
    .product-card-item {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border: 1px solid rgba(4, 101, 84, 0.2);
        border-radius: 1.1rem;
        padding: 1.35rem 1.25rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        height: 100%;
    }
    .product-card-title {
        font-weight: 700;
        color: var(--brand-primary);
        margin: 0 0 0.75rem 0;
        font-size: 1rem;
    }
    .product-card-desc {
        margin: 0;
        line-height: 1.6;
        color: var(--brand-dark-gray);
        font-size: 0.9rem;
    }
    .product-two-column {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        width: 100%;
    }
    @media (min-width: 768px) {
        .product-two-column {
            grid-template-columns: repeat(2, 1fr);
            max-width: 80%;
            margin: 0 auto;
        }
    }
</style>
@endpush
@endonce
