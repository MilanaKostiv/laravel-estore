@extends('layouts.master')

@section('title', 'Shopping Cart')

@section('content')
    <div class="cart-section container">
        <div>
            @if ($cartData['amount'])

                <h2>{{ $cartData['amount'] }} item(s) in Shopping Cart </h2>
                <div class="cart-table">
                    @foreach($cartData['items'] as $item)
                        <div class="cart-table-row-wrapper">
                            <div class="cart-table-row">
                                <div class="cart-table-row-left">
                                    <a href="{{ route('shop.show', $item->model->slug) }}"><img src="{{ $item->model->presentImage($item->model->image) }}" alt="item" class="cart-table-img"></a>
                                    <div class="cart-item-details">
                                        <div class="cart-table-item"><a href="{{ route('shop.show', $item->model->slug) }}">{{ $item->model->name }}</a></div>
                                        <div class="cart-table-description">{{ $item->model->details }}</div>
                                    </div>
                                </div>
                                <div class="cart-table-row-right">
                                    <div class="cart-table-actions">
                                        <form action="{{ route('cart.destroy', $item->rowId) }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button type="submit" class="cart-options">Remove</button>
                                        </form>

                                        <form action="{{ route('saveforlater.store', $item->rowId) }}" method="POST">
                                            {{ csrf_field() }}

                                            <button type="submit" class="cart-options">Save for Later</button>
                                        </form>
                                    </div>
                                    <div class="quantity-container">
                                        <div class="minus sprite"></div>
                                        <div><input type="text" class="quantity" value="{{ $item->qty }}" maxlength="2" data-id="{{ $item->rowId }}" product-id="{{ $item->id }}"/></div>
                                        <div class="plus sprite"></div>
                                    </div>
                                    <div class="product-subtotal">{{ $item->formattedPrice }}</div>
                                </div>
                            </div>
                            <div class="overlay"></div>
                        </div>
                    @endforeach
                </div>
                <div class="cart-totals">
                    <div class="cart-totals-left">
                        Shipping will be made later
                    </div>
                    <div class="cart-totals-right">
                        <div>
                            Subtotal <br>
                            Tax ({{config('cart.tax')}}%)<br>
                            <span class="cart-totals-total">Total</span>
                        </div>
                        <div class="cart-totals-subtotal">
                            <span class="result-subtotal">{{ $cartData['subtotal'] }}</span> <br>
                            <span class="result-tax">{{ $cartData['tax_amount'] }}</span><br>
                           <span class="result-total">{{ $cartData['total'] }}</span> <br>
                        </div>
                    </div>
                </div>
                <div class="cart-buttons">
                    <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                    <a href="#" class="button-primary">Proceed to Checkout</a>
                </div>
                @else
                    <h3>No items in Cart!</h3>
                    <div class="spacer"></div>
                    <a href="{{ route('shop.index') }}" class="button">Continue Shopping</a>
                    <div class="spacer"></div>
                @endif

                @if ($cartData['saveForLaterAmount'] > 0)
                    <h2>{{ $cartData['saveForLaterAmount'] }} item's Save For Later</h2>
                    <div class="saved_for_later cart-table">
                        @foreach($cartData['saveForLaterItems'] as $item)
                         <div class="cart-table-row">
                             <div class="cart-table-row-left">
                                <a href="{{ route('shop.show', $item->model->slug) }}"><img src="{{ $item->model->presentImage() }}" alt="item" class="cart-table-img"></a>
                                 <div class="cart-item-details">
                                     <div class="cart-table-item"><a href="{{ route('shop.show', $item->model->slug) }}">{{ $item->model->name }}</a></div>
                                     <div class="cart-table-description">{{ $item->model->details }}</div>
                                 </div>
                             </div>
                             <div class="cart-table-row-right">
                                 <div>{{ $item->qty }}</div>
                                 <div class="cart-table-actions">
                                     <form action="{{ route('saveforlater.destroy', $item->rowId) }}" method="POST">
                                         {{ csrf_field() }}
                                         {{ method_field('DELETE') }}

                                         <button type="submit" class="cart-options">Remove</button>
                                     </form>
                                     <form action="{{ route('saveforlater.movetocart', $item->rowId) }}" method="POST">
                                         {{ csrf_field() }}

                                         <button type="submit" class="cart-options">Move to Cart</button>
                                     </form>
                                 </div>
                             </div>
                         </div>
                        @endforeach
                    </div>
                @else
                    <h3>You have no items Saved for Later.</h3>
                @endif
        </div>
    </div> <!-- end cart-section -->
@endsection
@section('extra-js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('js/cart/counter.js') }}"></script>
@endsection