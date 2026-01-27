@extends('layouts.app')

@section('title', 'Sustainability – Green Resources')
@section('description', 'Learn about Green Resources sustainability commitments and certifications.')

@section('content')
<div class="sustainability-page">
{{-- Section 1: Banner --}}
<section class="section banner-section">
    <div class="container banner-container">
        <div class="about-banner">
            <div
                class="about-banner-background"
                style="background-image: url('{{ asset('assets/banners/sustainability.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;"
            ></div>
        </div>
    </div>
</section>

{{-- Section 2: Our Commitment --}}
<section class="section sustainability-section">
    <div class="container">
        <div class="about-content">
            <div class="about-feature-heading">OUR COMMITMENT</div>
            <div class="about-subtitle">Trading Renewable Energy for a Lower-Carbon Future</div>
            <p class="about-paragraph sustainability-paragraph">
                Green Resources Pte Ltd sources and trades renewable energy feedstocks from circular oil palm waste and residues, supporting greenhouse-gas reduction and circular economy outcomes.
            </p>
            <p class="about-paragraph sustainability-paragraph sustainability-paragraph-last">
                We operate with a transparent, risk-based sourcing framework, aligned with international sustainability and renewable energy regulations, delivering credible documentation, traceability, and dependable supply for global markets.
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
                We apply recognized sustainability certifications and declarations, where relevant to the product, origin, and market, to support regulatory compliance, audit readiness, and credible sustainability claims worldwide.
            </p>
            <p class="about-paragraph sustainability-paragraph">
                Our certification approach is fit for purpose and risk-based. Certification is used to enhance supply-chain transparency, traceability, and data integrity, ensuring that sustainability attributes are supported by verifiable and auditable documentation throughout the trading process.
            </p>
            <p class="about-paragraph sustainability-paragraph sustainability-paragraph-last">
                We apply certification with clarity and discipline, ensuring that sustainability claims are accurate, consistent, and transaction-specific. This enables our customers to rely on our documentation with confidence, integrate our products into regulated and voluntary markets, and move forward without uncertainty or reputational risk.
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
                    <img src="{{ asset('assets/certifications/rina.png') }}" alt="RINA Certification Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                    <img src="{{ asset('assets/certifications/iscc.png') }}" alt="ISCC Certification Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                    <img src="{{ asset('assets/certifications/mpob.png') }}" alt="MPOB Certification Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
