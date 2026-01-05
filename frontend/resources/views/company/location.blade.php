@extends('layouts.app')

@php
$banner = [
    'title' => 'Location',
    'subtitle' => 'Find Us',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'Location – Green Resources')
@section('description', 'Find Green Resources locations and contact information.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Company</div>
            <h1 class="section-title">Our Location</h1>
            <p class="section-description">
                Visit us at our facilities or get in touch with our team. 
                This information can be managed through the CMS.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📍</div>
                    <h3 class="card-title">Head Office</h3>
                </div>
                <p class="card-body">
                    Address information will be configured through CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🏭</div>
                    <h3 class="card-title">Production Facility</h3>
                </div>
                <p class="card-body">
                    Production facility details will be configured through CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌐</div>
                    <h3 class="card-title">Regional Offices</h3>
                </div>
                <p class="card-body">
                    Regional office information will be configured through CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

