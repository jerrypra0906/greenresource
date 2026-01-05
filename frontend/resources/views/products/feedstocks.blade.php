@extends('layouts.app')

@php
$banner = [
    'title' => 'Feedstocks',
    'subtitle' => 'Sustainable Feedstock Solutions',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'Feedstocks – Green Resources')
@section('description', 'Explore our range of sustainable feedstocks for your production needs.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Products</div>
            <h1 class="section-title">Feedstocks</h1>
            <p class="section-description">
                High-quality sustainable feedstocks designed to meet your production 
                requirements while supporting environmental goals. Content will be 
                fully manageable through the CMS.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌾</div>
                    <h3 class="card-title">Feedstock Type 1</h3>
                </div>
                <p class="card-body">
                    Description of feedstock type. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌿</div>
                    <h3 class="card-title">Feedstock Type 2</h3>
                </div>
                <p class="card-body">
                    Description of feedstock type. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🌱</div>
                    <h3 class="card-title">Feedstock Type 3</h3>
                </div>
                <p class="card-body">
                    Description of feedstock type. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

