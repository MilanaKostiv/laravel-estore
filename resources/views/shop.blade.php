@extends('layouts.master')

@section('title', 'Products')

@section('content')
    <div class="container">
        <div class="products text-center">
            @forelse ($products as $product)
                <div class="product">
                    <a href="{{ route('shop.show', $product->slug) }}"><img src="{{ $product->presentImage() }}"></a>
                    <a href="{{ route('shop.show', $product->slug) }}"><div class="product-name">{{ $product->name }}</div></a>
                    <div class="product-price">{{ $product->presentPrice() }}</div>
                </div>
            @empty
            <div>No items found</div>
            @endforelse
        </div>
    </div>
@endsection
