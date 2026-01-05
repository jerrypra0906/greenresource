@extends('layouts.app')

@section('title', 'Careers – Green Resources')
@section('description', 'Explore career opportunities at Green Resources.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Careers</div>
            <h1 class="section-title">Join our team</h1>
            <p class="section-description">
                Green Resources offers opportunities for growth, impact, and professional development.
                This section can be static or CMS-driven, with optional integration for job listings.
            </p>
        </div>

        <div class="two-column">
            <div>
                <h2 class="section-title" style="font-size: 1.3rem">
                    Why work with us
                </h2>
                <ul class="list-check">
                    <li>
                        <span class="bullet">✓</span>
                        <span>Commitment to sustainability and ESG principles</span>
                    </li>
                    <li>
                        <span class="bullet">✓</span>
                        <span>Opportunities for professional growth</span>
                    </li>
                    <li>
                        <span class="bullet">✓</span>
                        <span>Collaborative and inclusive work environment</span>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">💼</div>
                    <h3 class="card-title">Open positions</h3>
                </div>
                <p class="card-body">
                    Job listings can be managed through the CMS or integrated with an HR system in the future.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

