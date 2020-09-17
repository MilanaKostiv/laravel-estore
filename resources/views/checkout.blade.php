@extends('layouts.master')

@section('title', 'Checkout')

@section ('extra-css')
    <script src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    <div class="container">
        <h1 class="checkout-heading stylish-heading">Checkout</h1>
        <div class="checkout-section">
            <div>
                <form action="{{ route('checkout.store') }}" method="POST" id="payment-form">
                    {{ csrf_field() }}
                    <h2>Billing Details</h2>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="user@gmail.com" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>

                    <div class="half-form">
                      <div class="form-group">
                          <label for="city">City</label>
                          <input type="text" class="form-control" id="city" name="city" required>
                      </div>
                      <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                      </div>

                    </div>  <!-- end half-form -->

                    <div class="half-form">
                        <div class="form-group">
                            <label for="postal-code">Postal Code</label>
                            <input type="text" class="form-control" id="postal-code" name="postal-code" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div> <!-- end half-form -->

                    <div class="spacer"></div>

                    <h2>Payment Details</h2>
                    <div class="form-group">
                        <label for="name-on-card">Name on Card</label>
                        <input type="text" class="form-control" id="name-on-card" name="name-on-card">
                    </div>

                    <div class="form-group">
                        <label for="card-element">
                            Credit or debit card
                        </label>
                        <div id="card-element">
                            <!-- a Stripe Element will be inserted here. -->
                        </div>

                        <!-- Used to display form errors -->
                        <div id="card-errors" role="alert"></div>
                    </div>
                    <div class="spacer"></div>

                    <button type="submit" id="complete-order" class="button-primary full-width">Complete Order</button>
                </form>
            </div>

            <div class="checkout-table-container">
                <h2>Your Order</h2>
                <div class="checkout-table">
                    @foreach ($cartData['items'] as $item)
                        <div class="checkout-table-row-wrapper">
                            <div class="checkout-table-row">
                                <div class="checkout-table-row-left">
                                    <img src="{{ $item->model->presentImage($item->model->image) }}" alt="item" class="checkout-table-img"/>
                                    <div class="checkout-item-details">
                                        <div class="checkout-table-item">{{ $item->model->name }}</div>
                                        <div class="checkout-table-description">{{ $item->model->details }}</div>
                                        <div class="checkout-table-price">{{ $item->formattedPrice }}</div>
                                    </div>
                                </div>
                                <div class="checkout-table-row-right">
                                    @include('partials.counter')
                                </div>
                            </div> <!-- end checkout-table-row -->
                            <div class="overlay"></div>
                        </div>
                    @endforeach

                </div> <!-- end checkout-table -->

                <div class="checkout-totals">
                    <div class="checkout-total-left">
                        Subtotal <br>
                        Tax ({{config('cart.tax')}}%)<br>
                        <span class="checkout-totals-total">Total</span>
                    </div>
                    <div class="checkout-total-right">
                       <span class="result-subtotal"> {{ $cartData['subtotal'] }} </span><br>
                        <span class="result-tax">{{ $cartData['tax_amount'] }}</span><br>
                        <span class="result-total">{{ $cartData['total'] }}</span>
                    </div>
                </div> <!-- end checkout-totals -->

            </div> <!-- end checkout-table-container -->

        </div> <!-- end checkout-section -->
    </div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/cart/counter.js') }}"></script>
    <script>
        // Create a Stripe client.
        var stripe = Stripe('{{ config('services.stripe.key') }}');
    </script>
    <script src="{{ asset('js/checkout.js') }}"></script>
@endsection