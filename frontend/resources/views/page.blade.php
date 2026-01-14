@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)
@section('description', $page->meta_description ?? '')

@section('content')
@if($banner && isset($banner['image']) && !empty($banner['image']))
    @php
        // Normalize banner image URL
        // If path starts with 'storage/', remove it since asset() will add it
        // If path starts with 'assets/', use as is
        // Otherwise, assume it's a storage path and use as is
        $bannerImageUrl = $banner['image'];
        if (strpos($bannerImageUrl, 'storage/') === 0) {
            // Remove 'storage/' prefix since asset() will add it
            $bannerImageUrl = substr($bannerImageUrl, 8); // Remove 'storage/' (8 chars)
        }
        // Use asset() helper which will add '/storage/' prefix for storage paths
        if (strpos($banner['image'], 'assets/') === 0) {
            $bannerImageUrl = asset($banner['image']);
        } else {
            $bannerImageUrl = asset('storage/' . $bannerImageUrl);
        }
    @endphp
    <section class="banner" style="background-image: url('{{ $bannerImageUrl }}');">
        <div class="container banner-content">
            <h1 class="banner-title">{{ $banner['title'] ?? $page->title }}</h1>
            @if(isset($banner['subtitle']) && !empty($banner['subtitle']))
                <p class="banner-subtitle">{{ $banner['subtitle'] }}</p>
            @endif
        </div>
    </section>
@endif

@if($page->sections->count() > 0)
    @foreach($page->sections as $section)
        @php
            $sectionView = 'sections.' . $section->type;
        @endphp

        @if(view()->exists($sectionView))
            @include($sectionView, ['section' => $section])
        @else
            {{-- Fallback for unknown section types --}}
            <section class="section">
                <div class="container">
                    @if($section->title)
                        <h2>{{ $section->title }}</h2>
                    @endif
                    @if($section->body)
                        <div>{!! $section->body_html !!}</div>
                    @endif
                </div>
            </section>
        @endif
    @endforeach
@else
    {{-- Fallback content if no sections --}}
    <section class="section">
        <div class="container">
            <h1>{{ $page->title }}</h1>
            <p>Content coming soon. Please add sections to this page in the CMS.</p>
        </div>
    </section>
@endif
@endsection

