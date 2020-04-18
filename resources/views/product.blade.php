@extends('layouts.master')

@section('title', $product->name)

@section('content')
    <div class="product-section container">
        <div class="product-section-image">
            <img src="{{ $product->presentImage() }}" alt="product">
        </div>
        <div class="product-section-information">
            <h1 class="product-section-title">{{ $product->name }}</h1>
            <div class="product-section-subtitle"> {{ $product->details }}</div>
            <div class="product-section-price">{{ $product->presentPrice() }}</div>
            <p>{{ $product->description }}</p>
            <form action="#" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="button button-plain">Add to Cart</button>
            </form>
        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')
@endsection
