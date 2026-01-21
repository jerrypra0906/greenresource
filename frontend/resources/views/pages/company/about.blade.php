@extends('layouts.app')

@section('title', 'About Us – Green Resources')
@section('description', 'Learn about Green Resources: our mission, vision, and commitment to sustainability.')

@section('content')
{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div
                class="about-banner-background"
                style="background-image: linear-gradient(135deg, rgba(4, 101, 84, 0.8) 0%, rgba(5, 150, 105, 0.6) 100%), url('{{ asset('assets/banners/about.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            ></div>
        </div>
    </div>
</section>

{{-- Section 2: Company Description --}}
<section class="section">
    <div class="container">
        <div class="about-content">
            <p class="about-paragraph">
                At Green Resources Pte Ltd, we are committed to responsible sourcing and trading that supports environmental sustainability, ethical practices, and long-term partnerships. As a trading company specializing in renewable, waste, and residue feedstocks, we prioritize transparency, effective risk management, and continuous improvement across our supply chain, in line with global sustainability standards.
            </p>
            
            <p class="about-paragraph">
                Our mission is to bridge the gap between sustainable feedstock suppliers and global markets, ensuring that renewable resources are efficiently collected, processed, and distributed. We work closely with partners across the supply chain to maintain the highest standards of quality, traceability, and environmental responsibility.
            </p>
            
            <p class="about-paragraph">
                Through our extensive network and expertise in feedstock trading, we help connect producers with end-users who are committed to sustainable practices. Our team brings together deep industry knowledge, rigorous quality control, and a passion for environmental stewardship to deliver value for all stakeholders.
            </p>
            
            <p class="about-paragraph">
                We believe that sustainable feedstock collection and trading is not just a business opportunity, but a critical component of the global transition to a more sustainable economy. By facilitating the flow of renewable resources, we contribute to reducing waste, supporting circular economy principles, and enabling industries to adopt more environmentally friendly practices.
            </p>
        </div>
    </div>
</section>

{{-- Section 3: Centered Heading, Subheading, and Image --}}
<section class="section">
    <div class="container">
        <div class="about-feature">
            <h2 class="about-feature-heading">
                Connecting Global Markets
            </h2>
            <p class="about-feature-subheading">
                Through Sustainable Feedstock Collection
            </p>
            <div class="about-feature-image">
                <div class="image-placeholder-large"></div>
            </div>
        </div>
    </div>
</section>
@endsection
