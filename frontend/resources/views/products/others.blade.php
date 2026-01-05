@extends('layouts.app')

@php
$banner = [
    'title' => 'Other Products',
    'subtitle' => 'Additional Product Solutions',
    'image' => 'assets/HEADER GREEN RESOURCES.png'
];
@endphp

@section('title', 'Other Products – Green Resources')
@section('description', 'Explore our additional product solutions and services.')

@section('content')
<section class="section">
    <div class="container">
        <div class="section-header">
            <div class="section-kicker">Products</div>
            <h1 class="section-title">Other Products</h1>
            <p class="section-description">
                Additional products and solutions to meet your diverse needs. 
                Content will be fully manageable through the CMS.
            </p>
        </div>

        <div class="grid-3">
            <article class="card">
                <div class="card-header">
                    <div class="card-icon">📦</div>
                    <h3 class="card-title">Product Category 1</h3>
                </div>
                <p class="card-body">
                    Description of product category. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🛠️</div>
                    <h3 class="card-title">Product Category 2</h3>
                </div>
                <p class="card-body">
                    Description of product category. This content will be editable via CMS.
                </p>
            </article>

            <article class="card">
                <div class="card-header">
                    <div class="card-icon">🔧</div>
                    <h3 class="card-title">Product Category 3</h3>
                </div>
                <p class="card-body">
                    Description of product category. This content will be editable via CMS.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection

