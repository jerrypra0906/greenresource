@extends('layouts.app')

@section('title', 'Green Resources – Overview')
@section('description', 'Green Resources is a leading sustainable organization committed to excellence and environmental responsibility.')

@section('content')
{{-- Section 1: Banner --}}
<section class="home-banner">
    <div
        class="home-banner-background"
        style="background-image: url('{{ asset('assets/banners/home.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
    ></div>
</section>

{{-- Section 2: Green Box --}}
<section class="section">
    <div class="container">
        <div class="green-box">
            <p class="green-box-text">
                At Green Resources Pte Ltd, we are committed to responsible sourcing and trading that supports environmental sustainability, ethical practices, and long-term partnerships. As a trading company specializing in renewable, waste, and residue feedstocks, we prioritize transparency, effective risk management, and continuous improvement across our supply chain, in line with global sustainability standards.
            </p>
            <a href="{{ route('company.about') }}">
                <button class="btn-primary green-box-button" type="button">
                    LEARN MORE ABOUT US
                </button>
            </a>
        </div>
    </div>
</section>

{{-- Section 3: Image Grid --}}
<section class="section">
    <div class="container">
        <div class="image-grid">
            <div class="image-grid-card">
                <x-app-image 
                    src="{{ asset('assets/images/home-grid-1.png') }}" 
                    alt="Green Resources – Image 1"
                    fill
                    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
                />
            </div>
            <div class="image-grid-card">
                <x-app-image 
                    src="{{ asset('assets/images/home-grid-2.png') }}" 
                    alt="Green Resources – Image 2"
                    fill
                    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
                />
            </div>
            <div class="image-grid-card">
                <x-app-image 
                    src="{{ asset('assets/images/home-grid-3.png') }}" 
                    alt="Green Resources – Image 3"
                    fill
                    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
                />
            </div>
            <div class="image-grid-card">
                <x-app-image 
                    src="{{ asset('assets/images/home-grid-4.jpeg') }}" 
                    alt="Green Resources – Image 4"
                    fill
                    sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
                />
            </div>
        </div>
    </div>
</section>
@endsection
