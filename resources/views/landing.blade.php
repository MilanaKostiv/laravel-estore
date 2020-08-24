@extends('layouts.master')

@section('header-block')
    <div class="hero container">
        <div class="hero-copy">
            <h1>Welcome to eStore</h1>
            <p>We’re here to get you what you need.</p>
            <div class="hero-buttons">
                <a href="#" class="button-white">Button 1</a>
                <a href="#" class="button-white">Button 2</a>
            </div>
        </div> <!-- end hero-copy -->
        <div class="hero-image">
            <img src="img/laptop-hero.png" alt="hero image">
        </div>
    </div> <!-- end hero -->
@endsection

@section('content')
    <div class="featured-section">
        <div class="container">
            <div class="text-center button-container">
                <a href="#" class="button">Featured</a>
                <a href="#" class="button">On Sale</a>
            </div>
            <div class="products text-center">
                @foreach ($products as $product)
                    <div class="product">
                        <a href="{{ route('shop.show', $product->slug) }}"><img src="{{ $product->presentImage() }}" alt="product"></a>
                        <a href="{{ route('shop.show', $product->slug) }}"><div class="product-name">{{ $product->name }}</div></a>
                        <div class="product-price">{{ $product->formattedPrice }}</div>
                    </div>
                @endforeach
            </div> <!-- end products -->
            <div class="text-center button-container">
                <a href="{{ route('shop.index') }}" class="button">View more products</a>
            </div>
        </div> <!-- end container -->
    </div> <!-- end featured-section -->
@endsection
