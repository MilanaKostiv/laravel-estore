<?php

namespace App\Http\Controllers;

use App\Exceptions\PaymentException;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\Checkout;
use Gloudemans\Shoppingcart\Facades\Cart;
use \Illuminate\Http\RedirectResponse;
use \Illuminate\View\View;

/**
 * Checkout page rendering and order making.
 */
class CheckoutController extends Controller
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var Checkout
     */
    private $checkout;

    /**
     * @param CartService $cartService
     * @param Checkout $checkout
     */
    public function __construct(CartService $cartService, Checkout $checkout)
    {
        $this->cartService = $cartService;
        $this->checkout = $checkout;
    }

    /**
     * Shows the checkout form.
     *
     * @return RedirectResponse|View
     */
    public function index()
    {
        $cartData = $this->cartService->getCartData();

        if (auth()->user() && request()->is('guestCheckout')) {
            return redirect()->route('checkout.index');
        }

        return view('checkout')->with('cartData', $cartData);
    }

    /**
     * Saves order and makes payment.
     *
     * @param CheckoutRequest $request
     * @return RedirectResponse
     */
    public function makeOrder(CheckoutRequest $request): RedirectResponse
    {
        try {
            $this->checkout->execute($request);
            Cart::instance('default')->destroy();

            return redirect()->route('confirmation.index')->with('success_message', 'Thank you! Your payment has been successfully accepted.');

        } catch (PaymentException $e) {
            return back()->withErrors('Error!' . $e->getMessage());
        }
    }
}
