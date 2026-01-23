@extends('layouts.app')

@section('title', 'Products – Green Resources')
@section('description', 'Explore our range of sustainable products including feedstocks, methyl ester, and other solutions.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Our Products</div>
            <h1 class="section-title">Sustainable Product Solutions</h1>
            <p class="section-description">
                Discover our comprehensive range of sustainable products designed to meet your needs 
                while supporting environmental goals. We offer high-quality feedstocks, premium methyl 
                ester products, and additional solutions for various applications.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌾</div>
                    <h3 class="card-title">Feedstocks</h3>
                </div>
                <p class="card-body">
                    High-quality sustainable feedstocks designed to meet your production requirements 
                    while supporting environmental goals.
                </p>
                <div style="margin-top: 1rem">
                    <a href="{{ route('products.feedstocks') }}">
                        <button class="btn-primary" type="button">Learn More</button>
                    </a>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">⚗️</div>
                    <h3 class="card-title">Methyl Ester</h3>
                </div>
                <p class="card-body">
                    Premium methyl ester products manufactured with the highest quality standards 
                    for various industrial applications.
                </p>
                <div style="margin-top: 1rem">
                    <a href="{{ route('products.methyl') }}">
                        <button class="btn-primary" type="button">Learn More</button>
                    </a>
                </div>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📦</div>
                    <h3 class="card-title">Other Products</h3>
                </div>
                <p class="card-body">
                    Additional products and solutions to meet your diverse needs across different 
                    industries and applications.
                </p>
                <div style="margin-top: 1rem">
                    <a href="{{ route('products.others') }}">
                        <button class="btn-primary" type="button">Learn More</button>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section" style="background: #f1f5f9">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Why Choose Our Products?</h2>
            <p class="section-description">
                Our products are designed with sustainability, quality, and innovation in mind.
            </p>
        </div>

        <div class="two-column">
            <div>
                <ul class="list-check">
                    <li>
                        <span class="bullet">✓</span>
                        <span>High-quality sustainable feedstocks for eco-friendly production</span>
                    </li>
                    <li>
                        <span class="bullet">✓</span>
                        <span>Premium methyl ester products meeting industry standards</span>
                    </li>
                    <li>
                        <span class="bullet">✓</span>
                        <span>Custom solutions tailored to your business needs</span>
                    </li>
                    <li>
                        <span class="bullet">✓</span>
                        <span>Commitment to environmental responsibility</span>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🤝</div>
                    <h3 class="card-title">Ready to Get Started?</h3>
                </div>
                <p class="card-body">
                    Contact us to learn more about our products and how they can meet your specific requirements.
                </p>
                <div style="margin-top: 1rem">
                    <a href="{{ route('contact') }}">
                        <button class="btn-primary" type="button">Contact Us</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
