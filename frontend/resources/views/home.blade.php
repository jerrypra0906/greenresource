@extends('layouts.app')

@section('title', 'Green Resources – Overview')
@section('description', 'Green Resources is a leading sustainable organization committed to excellence and environmental responsibility.')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <h1 class="hero-title">
                Welcome to <span class="accent">Green Resources</span>
            </h1>
            <p class="hero-subtitle">
                A leading sustainable organization committed to excellence, innovation, 
                and environmental responsibility. We specialize in sustainable feedstocks, 
                methyl ester production, and eco-friendly solutions for a greener future.
            </p>
            <div class="hero-actions">
                <a href="{{ route('company.about-us') }}">
                    <button class="btn-primary" type="button">
                        Learn More About Us
                    </button>
                </a>
                <a href="{{ route('contact-us.fulfill-form') }}">
                    <button class="btn-ghost" type="button">
                        Get in Touch
                    </button>
                </a>
            </div>
        </div>
        <div class="hero-media">
            <img src="{{ asset('assets/HEADER GREEN RESOURCES.png') }}" alt="Green Resources" style="width: 100%; border-radius: 1rem;" />
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Our Overview</div>
            <h2 class="section-title">Who We Are</h2>
            <p class="section-description">
                Green Resources is dedicated to providing sustainable solutions through 
                innovative products and services. Our commitment to environmental 
                stewardship drives everything we do.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌱</div>
                    <h3 class="card-title">Sustainable Solutions</h3>
                </div>
                <p class="card-body">
                    We provide eco-friendly feedstocks and products that support 
                    sustainable practices across industries.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">⚡</div>
                    <h3 class="card-title">Innovation</h3>
                </div>
                <p class="card-body">
                    Our cutting-edge methyl ester production and advanced processing 
                    technologies set industry standards.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌍</div>
                    <h3 class="card-title">Global Impact</h3>
                </div>
                <p class="card-body">
                    Committed to making a positive environmental impact through 
                    responsible business practices worldwide.
                </p>
            </article>
        </div>
    </div>
</section>

<section class="section" style="background: #f1f5f9">
    <div class="container two-column">
        <div>
            <div class="section-kicker">Our Products</div>
            <h2 class="section-title">What We Offer</h2>
            <p class="section-description">
                Explore our range of sustainable products designed to meet your needs 
                while supporting environmental goals.
            </p>
            <ul class="list-check">
                <li>
                    <span class="bullet">✓</span>
                    <span>High-quality feedstocks for sustainable production</span>
                </li>
                <li>
                    <span class="bullet">✓</span>
                    <span>Premium methyl ester products</span>
                </li>
                <li>
                    <span class="bullet">✓</span>
                    <span>Custom solutions for your business needs</span>
                </li>
            </ul>
            <a href="{{ route('product.feedstocks') }}" class="btn-primary" style="display: inline-block; margin-top: 1.5rem">
                Explore Products
            </a>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-icon">📦</div>
                <h3 class="card-title">Product Categories</h3>
            </div>
            <ul class="card-list">
                <li><a href="{{ route('product.feedstocks') }}">Feedstocks</a></li>
                <li><a href="{{ route('product.methyl-ester') }}">Methyl Ester</a></li>
                <li><a href="{{ route('product.other') }}">Other Products</a></li>
            </ul>
        </div>
    </div>
</section>
@endsection
