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

{{-- Section 4: Certification Slider (infinite loop) --}}
<section class="section sustainability-section-grid">
    <div class="container">
        <div class="certification-slider-wrapper">
            <button type="button" class="certification-slider-btn certification-slider-btn-prev" id="certification-slider-prev" aria-label="Previous certification">
                <span aria-hidden="true">‹</span>
            </button>
            <div class="certification-slider" id="certification-slider" role="region" aria-label="Certifications carousel">
                <div class="certification-slider-track" id="certification-slider-track">
                    @include('pages.company.partials.certification-cards')
                    @include('pages.company.partials.certification-cards')
                </div>
            </div>
            <button type="button" class="certification-slider-btn certification-slider-btn-next" id="certification-slider-next" aria-label="Next certification">
                <span aria-hidden="true">›</span>
            </button>
        </div>
    </div>
</section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var slider = document.getElementById('certification-slider');
    var track = document.getElementById('certification-slider-track');
    if (!slider || !track) return;
    var cards = track.querySelectorAll('.certification-card');
    var totalCards = cards.length;
    var half = totalCards / 2;
    var singleSetWidth = 0;
    var programmatic = false;
    function getGap() {
        var g = getComputedStyle(track).gap;
        return g ? parseFloat(g) || 32 : 32;
    }
    function measureSetWidth() {
        if (cards.length < half) return;
        singleSetWidth = 0;
        var gap = getGap();
        for (var i = 0; i < half; i++) {
            singleSetWidth += cards[i].offsetWidth + (i < half - 1 ? gap : 0);
        }
    }
    function clampScroll() {
        if (programmatic || singleSetWidth <= 0) return;
        var scrollLeft = slider.scrollLeft;
        if (scrollLeft >= singleSetWidth) {
            programmatic = true;
            slider.scrollLeft = scrollLeft - singleSetWidth;
            programmatic = false;
        } else if (scrollLeft <= 0) {
            programmatic = true;
            slider.scrollLeft = scrollLeft + singleSetWidth;
            programmatic = false;
        }
    }
    var ticking = false;
    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(function () {
                clampScroll();
                ticking = false;
            });
            ticking = true;
        }
    }
    measureSetWidth();
    if (singleSetWidth > 0) {
        programmatic = true;
        slider.scrollLeft = singleSetWidth;
        programmatic = false;
    }
    slider.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', function () {
        measureSetWidth();
    });
    setTimeout(function () {
        measureSetWidth();
        if (singleSetWidth > 0 && slider.scrollLeft < singleSetWidth * 0.5) {
            programmatic = true;
            slider.scrollLeft = singleSetWidth;
            programmatic = false;
        }
    }, 100);

    function getStepWidth() {
        if (cards.length === 0) return singleSetWidth;
        var gap = getGap();
        return cards[0].offsetWidth + gap;
    }
    function goNext() {
        var step = getStepWidth();
        programmatic = true;
        slider.scrollLeft += step;
        programmatic = false;
        requestAnimationFrame(function () { clampScroll(); });
    }
    function goPrev() {
        var step = getStepWidth();
        programmatic = true;
        slider.scrollLeft -= step;
        programmatic = false;
        requestAnimationFrame(function () { clampScroll(); });
    }
    var btnPrev = document.getElementById('certification-slider-prev');
    var btnNext = document.getElementById('certification-slider-next');
    if (btnPrev) btnPrev.addEventListener('click', goPrev);
    if (btnNext) btnNext.addEventListener('click', goNext);
})();
</script>
@endpush
