<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentException;
use App\Http\Requests\CheckoutRequest;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * Making payment using Stripe payment gateway.
 */
class StripePaymentGateway implements PaymentGateway
{
    /**
     * Makes payment.
     *
     * @param CheckoutRequest $request
     * @throws PaymentException
     * @return void
     */
    public function charge(CheckoutRequest $request): void
    {
        $contents = Cart::instance('default')->content()->map(function ($item) {
            return $item->model->slug . ', ' . $item->qty;
        })->values()->toJson();

        try {
            Stripe::charges()->create([
                'amount' => Cart::instance('default')->total(),
                'currency' => 'USD',
                'source' => $request->stripeToken,
                'description' => 'OrderService',
                'receipt_email' => $request->email,
                'metadata' => [
                    'contents' => $contents,
                    'quantity' => Cart::instance('default')->count()
                ]
            ]);
        } catch (CardErrorException $exception) {
            throw new PaymentException($exception->getMessage());
        }
    }
}
