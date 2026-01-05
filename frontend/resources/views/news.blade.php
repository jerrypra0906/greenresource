@extends('layouts.app')

@section('title', 'News & Updates – Green Resources')
@section('description', 'Stay updated with the latest news and updates from Green Resources.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">News &amp; Updates</div>
            <h1 class="section-title">Latest from Green Resources</h1>
            <p class="section-description">
                Stay informed about our latest news, updates, and announcements.
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
                    <h3 class="card-title">Announcement</h3>
                </div>
                <p class="card-body">
                    Summary of announcement. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📅</div>
                    <h3 class="card-title">Event Update</h3>
                </div>
                <p class="card-body">
                    Summary of event or update. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

