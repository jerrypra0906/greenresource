{{-- Product Category Detail Partial --}}
{{-- Required variables: $categoryKey, $categoryTitle, $categoryDescription, $products --}}

{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div
                class="about-banner-background"
                style="background-image: url('{{ asset('assets/banners/products.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            ></div>
        </div>
    </div>
</section>

{{-- Section 2: Category Tabs --}}
<section class="section product-tabs-section">
    <div class="container">
        <div class="product-tabs">
            <a href="{{ route('products.feedstocks') }}" class="product-tab {{ $categoryKey === 'feedstocks' ? 'is-active' : '' }}">FEEDSTOCKS</a>
            <a href="{{ route('products.methyl') }}" class="product-tab {{ $categoryKey === 'methyl' ? 'is-active' : '' }}">METHYL ESTER</a>
            <a href="{{ route('products.others') }}" class="product-tab {{ $categoryKey === 'others' ? 'is-active' : '' }}">OTHERS</a>
        </div>
    </div>
</section>

{{-- Section 3: Category Title + Description --}}
<section class="section">
    <div class="container">
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
    </div>
</section>

{{-- Section 4: Product Details Layout --}}
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
    }
    .product-image img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }
    .product-cards-container {
        display: flex;
        flex-direction: column;
    }
    .product-top-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 768px) {
        .product-top-row {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .product-bottom-row-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 1.5rem;
    }
    .product-bottom-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        width: 100%;
    }
    @media (min-width: 768px) {
        .product-bottom-row {
            grid-template-columns: repeat(2, 1fr);
            max-width: 66.666667%;
        }
    }
    .product-card-item {
        background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
        border: 1px solid rgba(4, 101, 84, 0.2);
        border-radius: 1.1rem;
        padding: 1.35rem 1.25rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
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
    /* For categories with 2 products (methyl, others) */
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
<section class="section">
    <div class="container">
        @php
            $imageMap = [
                'feedstocks' => 'assets/images/feedstocks-palm-oil.png',
                'methyl' => 'assets/images/methyl-ester.png',
                'others' => 'assets/images/others.jpeg',
            ];
            $imagePath = $imageMap[$categoryKey] ?? 'assets/images/products.png';
            $productCount = count($products);
        @endphp
        
        <div class="product-layout">
            {{-- Left: Image --}}
            <div class="product-image">
                <img src="{{ asset($imagePath) }}" alt="{{ $categoryTitle }} - Product Image" />
            </div>
            
            {{-- Right: Product Cards --}}
            <div class="product-cards-container">
                @if($categoryKey === 'feedstocks' && $productCount >= 5)
                    {{-- Feedstocks: 3 cards top, 2 cards bottom centered --}}
                    @php
                        $topProducts = array_slice($products, 0, 3);
                        $bottomProducts = array_slice($products, 3, 2);
                    @endphp
                    
                    {{-- Top Row: 3 cards --}}
                    <div class="product-top-row">
                        @foreach($topProducts as $product)
                        <div class="product-card-item">
                            <h3 class="product-card-title">{{ $product['name'] }}</h3>
                            <p class="product-card-desc">{{ $product['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- Bottom Row: 2 cards centered --}}
                    <div class="product-bottom-row-wrapper">
                        <div class="product-bottom-row">
                            @foreach($bottomProducts as $product)
                            <div class="product-card-item">
                                <h3 class="product-card-title">{{ $product['name'] }}</h3>
                                <p class="product-card-desc">{{ $product['desc'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Methyl Ester and Others: 2 cards in centered layout --}}
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
    </div>
</section>
