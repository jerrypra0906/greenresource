@extends('layouts.app')

@section('title', 'Methyl Ester – Green Resources')
@section('description', 'Explore our methyl ester products including PME, SME, and TME.')

@section('content')
@php
$categoryKey = 'methyl';
$categoryTitle = 'METHYL ESTER';
$categoryDescription = "Methyl Ester, commonly known as biodiesel, is a clean-burning renewable fuel produced through the transesterification of vegetable oils, animal fats, or recycled greases. It serves as a sustainable alternative to petroleum-based diesel.

Our methyl ester products meet international quality standards and are compatible with existing diesel engines and infrastructure, making them ideal for reducing carbon emissions in transportation and industrial applications.";

$products = [
    ['name' => 'PME', 'desc' => 'Palm Methyl Ester – biodiesel derived from palm oil.'],
    ['name' => 'SME', 'desc' => 'Soy Methyl Ester – biodiesel produced from soybean oil.'],
    ['name' => 'TME', 'desc' => 'Tallow Methyl Ester – biodiesel made from animal fats.'],
];
@endphp

@include('pages.products.partials.category-detail')
@endsection
