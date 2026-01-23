@extends('layouts.app')

@section('title', 'Products – Green Resources')
@section('description', 'Explore our range of sustainable products including feedstocks, methyl ester, and other solutions.')

@section('content')
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
            <a href="{{ route('products.show', 'feedstocks') }}" class="products-category-tile" data-preload-image="{{ asset('assets/banners/products.png') }}">
                <div class="products-category-image">
                    <x-app-image 
                        src="{{ asset('assets/images/feedstocks-palm-oil.png') }}" 
                        alt="Feedstocks"
                        fill
                        sizes="(max-width: 768px) 100vw, 33vw"
                    />
                </div>
                <h3 class="products-category-label">FEEDSTOCKS</h3>
            </a>
            
            <a href="{{ route('products.show', 'methyl-ester') }}" class="products-category-tile" data-preload-image="{{ asset('assets/banners/products.png') }}">
                <div class="products-category-image">
                    <x-app-image 
                        src="{{ asset('assets/images/methyl-ester.png') }}" 
                        alt="Methyl Ester"
                        fill
                        sizes="(max-width: 768px) 100vw, 33vw"
                    />
                </div>
                <h3 class="products-category-label">METHYL ESTER</h3>
            </a>
            
            <a href="{{ route('products.show', 'others') }}" class="products-category-tile" data-preload-image="{{ asset('assets/banners/products.png') }}">
                <div class="products-category-image">
                    <x-app-image 
                        src="{{ asset('assets/images/others.jpeg') }}" 
                        alt="Others"
                        fill
                        sizes="(max-width: 768px) 100vw, 33vw"
                    />
                </div>
                <h3 class="products-category-label">OTHERS</h3>
            </a>
        </div>
    </div>
</section>
@endsection
