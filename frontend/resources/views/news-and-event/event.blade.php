@extends('layouts.app')

@php
$banner = [
    'title' => 'Events',
    'subtitle' => 'Upcoming and Past Events',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'Events – Green Resources')
@section('description', 'Discover upcoming events and past event highlights from Green Resources.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">News and Event</div>
            <h1 class="section-title">Events</h1>
            <p class="section-description">
                Join us at our upcoming events or explore highlights from past events. 
                This section will be fully manageable through the CMS.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🎉</div>
                    <h3 class="card-title">Upcoming Event 1</h3>
                </div>
                <p class="card-body">
                    Description of upcoming event. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📅</div>
                    <h3 class="card-title">Upcoming Event 2</h3>
                </div>
                <p class="card-body">
                    Description of upcoming event. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌟</div>
                    <h3 class="card-title">Past Event Highlight</h3>
                </div>
                <p class="card-body">
                    Description of past event. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

