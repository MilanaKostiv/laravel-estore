<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

/**
 * Checkout page tests.
 */
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $product = factory(Product::class)->create();
        Cart::instance('default')->add($product->id, $product->name, 1, $product->price)
            ->associate('App\Product');
    }

    /**
     * Tests checkout.
     *
     * @return void
     */
    public function testCheckoutForm(): void
    {
        $stripe = Stripe::make(env('STRIPE_SECRET'));

        $token = $stripe->tokens()->create([
            'card' => [
                'number'    => '4242424242424242',
                'exp_month' => 10,
                'cvc'       => 314,
                'exp_year'  => 2025,
            ],
        ]);

        $response = $this->post('/checkout', [
            'email' => 'jhon@testshop.local',
            'name'  => 'Jhon',
            'address' => '1200 Street Street',
            'city' => 'Austin',
            'state' => 'Texas',
            'postal_code' => 78758,
            'phone' => '1111111111',
            'payment_gateway' => 'stripe',
            'name_on_card' => 'Jhon',
            'stripeToken' => $token
        ]);
        $response->assertSee('Thank you! Your payment has been successfully accepted');
    }
}
