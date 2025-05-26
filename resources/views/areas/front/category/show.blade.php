@extends('layouts.front')

@section('content')
    <x-slot name="seo_title">{{ $seo_title }}</x-slot>
    <x-slot name="seo_description">{{ $seo_description }}</x-slot>
    <x-slot name="seo_keywords">{{ $seo_keywords }}</x-slot>

    <x-breadcrumbs :showHome="true" :items="[
        [
            'name' => $category['name'],
            'url' => false,
        ]
    ]" />

    <div class="margins">

        <h1>{{ $category['name'] }}</h1>

        <x-front.product-list :products="$products" />

        {{ $products->links('components.front.paginator') }}

    </div>

@endsection
