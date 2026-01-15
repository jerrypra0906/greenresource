@extends('layouts.app')

@section('title', 'Other Products – Green Resources')
@section('description', 'Explore our other sustainable products including glycerin, fatty acids, and more.')

@section('content')
@php
$categoryKey = 'others';
$categoryTitle = 'OTHERS';
$categoryDescription = "Beyond our core feedstock and methyl ester offerings, we supply a range of complementary products derived from the biofuel production process. These byproducts and derivatives support various industrial applications.

Our diversified product portfolio enables us to maximize resource utilization while providing valuable materials to industries including cosmetics, pharmaceuticals, and chemical manufacturing.";

$products = [
    ['name' => 'Crude Glycerin', 'desc' => 'Byproduct of biodiesel production used in cosmetics and pharmaceuticals.'],
    ['name' => 'Fatty Acids', 'desc' => 'Essential components for soap, detergent, and chemical industries.'],
    ['name' => 'Palm Kernel Expeller', 'desc' => 'High-protein animal feed ingredient from palm kernel processing.'],
];
@endphp

@include('pages.products.partials.category-detail')
@endsection
