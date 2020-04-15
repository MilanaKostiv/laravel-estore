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
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
                <div class="product">
                    <a href="#"><img src="img/macbook.png" alt="product"></a>
                    <a href="#"><span class="product-name">Macbook</span></a>
                    <div class="product-price">$2499.99</div>
                </div>
            </div> <!-- end products -->
            <div class="text-center button-container">
                <a href="#" class="button">View more products</a>
            </div>
        </div> <!-- end container -->
    </div> <!-- end featured-section -->
@endsection
