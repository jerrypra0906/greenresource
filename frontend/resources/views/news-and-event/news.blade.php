@extends('layouts.app')

@php
$banner = [
    'title' => 'News',
    'subtitle' => 'Latest Updates from Green Resources',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'News – Green Resources')
@section('description', 'Stay updated with the latest news and updates from Green Resources.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">News and Event</div>
            <h1 class="section-title">Latest News</h1>
            <p class="section-description">
                Stay informed about our latest news, announcements, and updates. 
                This section will be fully manageable through the CMS.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📰</div>
                    <h3 class="card-title">News Article 1</h3>
                </div>
                <p class="card-body">
                    Summary of news article. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📢</div>
                    <h3 class="card-title">News Article 2</h3>
                </div>
                <p class="card-body">
                    Summary of news article. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📅</div>
                    <h3 class="card-title">News Article 3</h3>
                </div>
                <p class="card-body">
                    Summary of news article. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

