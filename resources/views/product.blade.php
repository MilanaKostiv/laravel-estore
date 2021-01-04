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
            <div class="badge {{ $product->quantity > 0 ? 'badge-success' : 'badge-danger'}}"> {{ $product->quantity > 0 ? 'In Stock': 'Out Of Stock'}}</div>
            <div class="product-section-price">{{ $product->formattedPrice }}</div>
            <p>{!! $product->description !!}</p>
            @if ($product->quantity > 0)
                <form action="{{ route('cart.addToCart', $product) }}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="button button-plain">Add to Cart</button>
                </form>
            @endif
        </div>
    </div> <!-- end product-section -->

    @include('partials.might-like')
@endsection
