@extends('layouts.master')

@section('title', 'Products')

@section('content')
    <div class="products-section container">
        <div class="sidebar">
            <h3>By Category</h3>
            <ul>
                @foreach($categories as $category)
                    <li class="{{ request()->category == $category->slug ? 'active' : '' }}">
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div>
            <div class="products-header">
                <h1 class="stylish-heading">{{ $categoryName }}</h1>
                <div>
                    <strong>Price:</strong>
                    <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'low_high']) }}">Low
                        to High</a>
                    <a href="{{ route('shop.index', ['category' => request()->category, 'sort' => 'high_low']) }}">High
                        to Low</a>
                </div>
            </div>
            <div class="products text-center">
                @forelse ($products as $product)
                    <div class="product">
                        <a href="{{ route('shop.show', $product->slug) }}">
                            <img src="{{ $product->presentImage() }}">
                        </a>
                        <a href="{{ route('shop.show', $product->slug) }}">
                            <div class="product-name">{{ $product->name }}</div>
                        </a>
                        <div class="product-price">{{ $product->formattedPrice }}</div>
                    </div>
                @empty
                    <div>No items found</div>
                @endforelse
            </div>
            <div class="spacer"></div>
            {{ $paginator->links() }}
        </div>
    </div>
@endsection
