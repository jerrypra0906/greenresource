@extends('layouts.app')

@section('title', 'Sustainability – Green Resources')
@section('description', 'Learn about Green Resources sustainability commitments and certifications.')

@section('content')
<div class="sustainability-page">
{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div class="about-banner-background"></div>
        </div>
    </div>
</section>

{{-- Section 2: Our Commitment --}}
<section class="section sustainability-section">
    <div class="container">
        <div class="about-content">
            <div class="about-feature-heading">OUR COMMITMENT</div>
            <p class="about-paragraph sustainability-paragraph sustainability-paragraph-last">
                Green Resources Pte Ltd is committed to responsible sourcing and trading practices that support environmental sustainability, ethical conduct, and long-term partnerships. As a trading company specializing in renewable, waste, and residue feedstocks, we prioritize transparency, risk management, and continuous improvement across our supply chain.
            </p>
        </div>
    </div>
</section>

{{-- Section 3: Our Certifications --}}
<section class="section sustainability-section">
    <div class="container">
        <div class="about-content">
            <div class="about-feature-heading">OUR CERTIFICATIONS</div>
            <p class="about-paragraph sustainability-paragraph">
                Green Resources is consistently certified under the sustainability schemes below, reinforcing our commitment to maintaining high environmental standards throughout our operations.
            </p>
            <p class="about-paragraph sustainability-paragraph sustainability-paragraph-last">
                These certifications reflect our dedication to sustainable development and responsible business practices across our value chain.
            </p>
        </div>
    </div>
</section>

{{-- Section 4: Certification Grid --}}
<section class="section sustainability-section-grid">
    <div class="container">
        <div class="certification-grid">
            {{-- Certification 1: RINA --}}
            <div class="certification-card">
                <div class="certification-logo">
                    <img src="{{ asset('images/certifications/rina.png') }}" alt="RINA Certification Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="certification-logo-placeholder" style="display: none;">
                        <span>Logo</span>
                    </div>
                </div>
                <h3 class="certification-title">RINA Certification</h3>
                <p class="certification-description">
                    RINA Certification is an internationally recognized certification and assurance system that verifies compliance with regulatory and international standards. It ensures that management systems, products, and personnel meet requirements related to sustainability, safety, and innovation, while promoting transparency and accountability across operations.
                </p>
                <p class="certification-description">
                    This certification covers key areas such as ESG and decarbonization, ICT and cybersecurity, health and safety, diversity and inclusion, food, transport, and green building.
                </p>
                <a href="https://www.rina.org" target="_blank" rel="noopener noreferrer" class="certification-link">
                    Learn More →
                </a>
            </div>

            {{-- Certification 2: ISCC --}}
            <div class="certification-card">
                <div class="certification-logo">
                    <img src="{{ asset('images/certifications/iscc.png') }}" alt="ISCC Certification Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="certification-logo-placeholder" style="display: none;">
                        <span>Logo</span>
                    </div>
                </div>
                <h3 class="certification-title">International Sustainability and Carbon Certification</h3>
                <p class="certification-description">
                    International Sustainability & Carbon Certification (ISCC) is a globally recognized certification system for bio-based feedstocks and renewable materials used in the energy, food, feed, and chemical sectors.
                </p>
                <p class="certification-description">
                    ISCC promotes sustainable practices by addressing key criteria such as greenhouse gas emission reduction, responsible land use, biodiversity protection, and social responsibility.
                </p>
                <p class="certification-description">
                    As a member of the UN Global Compact, ISCC supports sustainable supply chains worldwide. ISCC certification also ensures compliance with the sustainability requirements of the European Union's Renewable Energy Directive (RED).
                </p>
                <a href="https://www.iscc-system.org/" target="_blank" rel="noopener noreferrer" class="certification-link">
                    Learn More →
                </a>
            </div>

            {{-- Certification 3: MPOB --}}
            <div class="certification-card">
                <div class="certification-logo">
                    <img src="{{ asset('images/certifications/mpob.png') }}" alt="MPOB Certification Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="certification-logo-placeholder" style="display: none;">
                        <span>Logo</span>
                    </div>
                </div>
                <h3 class="certification-title">Malaysian Palm Oil Board</h3>
                <p class="certification-description">
                    Malaysian Palm Oil Board (MPOB) is a statutory body under the Government of Malaysia responsible for regulating, promoting, and developing the Malaysian palm oil industry across upstream and downstream sectors.
                </p>
                <p class="certification-description">
                    MPOB establishes standards and guidelines to ensure sustainable palm oil production, covering aspects such as good agricultural practices, environmental management, product quality, and industry compliance.
                </p>
                <p class="certification-description">
                    Through research, certification, and regulatory oversight, MPOB supports responsible palm oil supply chains while strengthening industry competitiveness and compliance with national and international requirements.
                </p>
                <a href="https://mpob.gov.my/" target="_blank" rel="noopener noreferrer" class="certification-link">
                    Learn More →
                </a>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
