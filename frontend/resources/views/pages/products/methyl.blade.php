@extends('layouts.app')

@section('title', 'Waste-based FAME – Green Resources')
@section('description', 'Explore our waste-based FAME products including PME, SME, and TME.')

@section('content')
@php
$categoryKey = 'methyl';
$categoryTitle = 'WASTE-BASED FAME';
$categoryDescription = "Waste-based FAME (Fatty Acid Methyl Ester) is a renewable biodiesel produced from used cooking oils, processing residues, and other waste-based feedstocks. It delivers greenhouse-gas savings by diverting waste streams into circular, lower-carbon energy.

Our waste-based FAME products meet international quality and sustainability standards and are compatible with existing diesel engines and infrastructure, making them ideal for reducing carbon emissions in transportation and industrial applications.";

$products = [
    ['name' => 'UCO / RUCO', 'desc' => 'Used Cooking Oil – recycled cooking oil collected for biodiesel production.'],
    ['name' => 'EFBO', 'desc' => 'Empty Fruit Bunch Oil – extracted from palm empty fruit bunches.'],
];
@endphp

@include('pages.products.partials.category-detail')
@endsection
