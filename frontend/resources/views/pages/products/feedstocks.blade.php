@extends('layouts.app')

@section('title', 'Feedstocks – Green Resources')
@section('description', 'Explore our sustainable feedstock products including POME, UCO, EFBO, and SBEO.')

@section('content')
@php
$categoryKey = 'feedstocks';
$categoryTitle = 'FEEDSTOCKS';
$categoryDescription = "Feedstock refers to the primary raw materials used in the production of energy, particularly renewable and bio-based fuels. As an energy company specializing in feedstock supply, we provide sustainable materials sourced from renewable resources, waste, and industrial residues.

Our feedstocks meet strict quality, traceability, and sustainability standards, supported by integrated logistics and reliable supply networks to ensure consistent delivery and support the global energy transition.";

$products = [
    ['name' => 'POME / RPOME', 'desc' => 'Palm Oil Mill Effluent – a byproduct of palm oil processing used as renewable feedstock.'],
    ['name' => 'UCO / RUCO', 'desc' => 'Used Cooking Oil – recycled cooking oil collected for biodiesel production.'],
    ['name' => 'EFBO', 'desc' => 'Empty Fruit Bunch Oil – extracted from palm empty fruit bunches.'],
    ['name' => 'SBEO', 'desc' => 'Soybean Oil – plant-based oil from soybean processing.'],
    ['name' => 'Trans-esterified Residue', 'desc' => 'Residue generated from biodiesel transesterification process.'],
];
@endphp

@include('pages.products.partials.category-detail')
@endsection
