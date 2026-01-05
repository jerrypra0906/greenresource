@extends('layouts.app')

@php
$banner = [
    'title' => 'Methyl Ester',
    'subtitle' => 'Premium Methyl Ester Products',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'Methyl Ester – Green Resources')
@section('description', 'Discover our premium methyl ester products for various applications.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Products</div>
            <h1 class="section-title">Methyl Ester</h1>
            <p class="section-description">
                Premium methyl ester products manufactured with the highest quality 
                standards. Content will be fully manageable through the CMS.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">⚗️</div>
                    <h3 class="card-title">Methyl Ester Product 1</h3>
                </div>
                <p class="card-body">
                    Description of methyl ester product. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🔬</div>
                    <h3 class="card-title">Methyl Ester Product 2</h3>
                </div>
                <p class="card-body">
                    Description of methyl ester product. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🧪</div>
                    <h3 class="card-title">Methyl Ester Product 3</h3>
                </div>
                <p class="card-body">
                    Description of methyl ester product. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

