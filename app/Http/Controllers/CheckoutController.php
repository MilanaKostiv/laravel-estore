<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\Cart\CartService;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * Checkout page controller.
 */
class CheckoutController extends Controller
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cartData = $this->cartService->getCartData();

        return view('checkout')->with('cartData', $cartData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CheckoutRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CheckoutRequest $request)
    {
        $contents = Cart::instance('default')->content()->map(function ($item) {
            return $item->model->slug.', '.$item->qty;
        })->values()->toJson();

        try {
            Stripe::charges()->create([
                'amount' => Cart::instance('default')->total(),
                'currency' => 'USD',
                'source' => $request->stripeToken,
                'description' => 'Order',
                'receipt_email' => $request->email,
                'metadata' => [
                    'contents' => $contents,
                    'quantity' => Cart::instance('default')->count()
                ]
            ]);

            Cart::instance('default')->destroy();

            return redirect()->route('confirmation.index')->with('success_message', 'Thank you! Your payment has been successfully accepted.');

        } catch(CardErrorException $e) {
            return back()->withErrors('Error!' . $e->getMessage());
        }
    }
}
