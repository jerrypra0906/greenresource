{{-- Product Category Detail Partial --}}
{{-- Required variables: $categoryKey, $categoryTitle, $categoryDescription, $products --}}

{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div class="about-banner-background"></div>
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

{{-- Section 4: Product Grid --}}
<section class="section">
    <div class="container">
        <div class="product-grid">
            @foreach($products as $product)
            <div class="product-card">
                <div class="product-img">
                    <div class="image-placeholder-product"></div>
                    <div class="product-overlay">
                        <p>{{ $product['desc'] }}</p>
                    </div>
                </div>
                <h3 class="product-name">{{ $product['name'] }}</h3>
                <p class="product-desc-mobile">{{ $product['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
