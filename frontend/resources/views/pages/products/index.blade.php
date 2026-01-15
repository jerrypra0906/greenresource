@extends('layouts.app')

@section('title', 'Products – Green Resources')
@section('description', 'Explore our range of sustainable products including feedstocks, methyl ester, and other solutions.')

@section('content')
{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div class="about-banner-background"></div>
        </div>
    </div>
</section>

{{-- Section 2: Description --}}
<section class="section">
    <div class="container">
        <div class="products-description">
            <p class="products-description-text">
                All our products, including POME, PME, and UCO, bear the prestigious ISCC certification. Green Resources proudly offers a seamless integrated value chain that encompasses origination, precise logistical arrangements, secure storage, and efficient transportation to our valued clients across the Asia-Pacific region and Europe.
            </p>
            <p class="products-description-text">
                Our proven track record in exporting to these regions underscores our unwavering commitment to reliability and uncompromising quality.
            </p>
        </div>
    </div>
</section>

{{-- Section 3: Category Section --}}
<section class="section">
    <div class="container">
        <div class="products-category-header">
            <h2 class="products-category-title">Key Product Categories</h2>
            <p class="products-category-subtitle">Discover More about Our Sustainable Solutions</p>
        </div>
        
        <div class="products-category-grid">
            <a href="{{ route('products.feedstocks') }}" class="products-category-tile">
                <div class="products-category-image">
                    <div class="image-placeholder-category"></div>
                </div>
                <h3 class="products-category-label">FEEDSTOCKS</h3>
            </a>
            
            <a href="{{ route('products.methyl') }}" class="products-category-tile">
                <div class="products-category-image">
                    <div class="image-placeholder-category"></div>
                </div>
                <h3 class="products-category-label">METHYL ESTER</h3>
            </a>
            
            <a href="{{ route('products.others') }}" class="products-category-tile">
                <div class="products-category-image">
                    <div class="image-placeholder-category"></div>
                </div>
                <h3 class="products-category-label">OTHERS</h3>
            </a>
        </div>
    </div>
</section>
@endsection
