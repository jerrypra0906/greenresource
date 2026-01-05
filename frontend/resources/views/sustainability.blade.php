@extends('layouts.app')

@section('title', 'Sustainability / ESG – Green Resources')
@section('description', 'Learn about Green Resources sustainability initiatives and ESG commitments.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Sustainability &amp; ESG</div>
            <h1 class="section-title">Our commitment to sustainability</h1>
            <p class="section-description">
                Green Resources is committed to embedding environmental, social, and governance (ESG)
                principles into every aspect of our operations. This section will be fully manageable
                through the CMS to showcase initiatives, reports, and progress.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌍</div>
                    <h3 class="card-title">Environmental</h3>
                </div>
                <p class="card-body">
                    Our environmental initiatives and commitments. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">👥</div>
                    <h3 class="card-title">Social</h3>
                </div>
                <p class="card-body">
                    Our social responsibility programs and community engagement. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">⚖️</div>
                    <h3 class="card-title">Governance</h3>
                </div>
                <p class="card-body">
                    Our governance framework and ethical standards. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

