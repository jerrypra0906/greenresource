@extends('layouts.app')

@section('title', 'About Us – Green Resources')
@section('description', 'Learn about Green Resources: company overview, vision & mission, and values.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Company overview</div>
            <h1 class="section-title">Who we are.</h1>
            <p class="section-description">
                Green Resources is a forward-looking organization focused on
                sustainable growth, long-term partnerships, and measurable impact
                for people and planet.
            </p>
        </div>

        <div class="two-column">
            <div>
                <h2 class="section-title" style="font-size: 1.3rem">
                    Vision &amp; Mission
                </h2>
                <p class="section-description">
                    This section can be fully managed through the CMS, allowing
                    communications teams to refine the company narrative as Green
                    Resources evolves.
                </p>
                <ul class="list-check">
                    <li>
                        <span class="bullet">✓</span>
                        <span>Vision: a concise statement of long-term ambition.</span>
                    </li>
                    <li>
                        <span class="bullet">✓</span>
                        <span>Mission: how Green Resources creates sustainable value.</span>
                    </li>
                </ul>
            </div>
            <div>
                <h2 class="section-title" style="font-size: 1.3rem">
                    Values
                </h2>
                <p class="section-description">
                    A value-driven culture underpins both sustainability and
                    business performance.
                </p>
                <div class="grid-3" style="margin-top: 1rem; grid-template-columns: repeat(3, minmax(0, 1fr))">
                    <div class="card">
                        <h3 class="card-title">Integrity</h3>
                        <p class="card-body">
                            Doing the right thing for communities, customers, and
                            colleagues.
                        </p>
                    </div>
                    <div class="card">
                        <h3 class="card-title">Sustainability</h3>
                        <p class="card-body">
                            Embedding ESG into decisions across the value chain.
                        </p>
                    </div>
                    <div class="card">
                        <h3 class="card-title">Innovation</h3>
                        <p class="card-body">
                            Continuously improving processes, products, and services.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

