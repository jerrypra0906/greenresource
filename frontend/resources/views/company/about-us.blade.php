@extends('layouts.app')

@php
$banner = [
    'title' => 'About Us',
    'subtitle' => 'Learn more about Green Resources',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'About Us – Green Resources')
@section('description', 'Learn about Green Resources: our mission, vision, and commitment to sustainability.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Company</div>
            <h1 class="section-title">About Green Resources</h1>
            <p class="section-description">
                Green Resources is a forward-looking organization focused on sustainable 
                growth, innovation, and environmental responsibility. We are committed 
                to providing high-quality products and services that support a greener future.
            </p>
        </div>

        <div class="two-column">
            <div>
                <h2 class="section-title" style="font-size: 1.5rem; margin-top: 2rem">
                    Our Mission
                </h2>
                <p class="section-description">
                    To provide sustainable solutions through innovative products and 
                    services that support environmental stewardship and create value 
                    for our customers, partners, and communities.
                </p>

                <h2 class="section-title" style="font-size: 1.5rem; margin-top: 2rem">
                    Our Vision
                </h2>
                <p class="section-description">
                    To be a leading provider of sustainable resources and solutions, 
                    recognized for our commitment to excellence, innovation, and 
                    environmental responsibility.
                </p>
            </div>
            <div>
                <h2 class="section-title" style="font-size: 1.5rem; margin-top: 2rem">
                    Our Values
                </h2>
                <div class="grid-3" style="margin-top: 1rem; grid-template-columns: repeat(1, minmax(0, 1fr))">
                    <div class="card">
                        <h3 class="card-title">Sustainability</h3>
                        <p class="card-body">
                            We prioritize environmental responsibility in all our operations 
                            and decisions.
                        </p>
                    </div>
                    <div class="card">
                        <h3 class="card-title">Innovation</h3>
                        <p class="card-body">
                            We continuously improve our processes and develop new solutions 
                            to meet evolving needs.
                        </p>
                    </div>
                    <div class="card">
                        <h3 class="card-title">Integrity</h3>
                        <p class="card-body">
                            We conduct business with honesty, transparency, and respect 
                            for all stakeholders.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

