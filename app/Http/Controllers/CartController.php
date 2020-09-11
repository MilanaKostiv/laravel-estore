<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Product;
use App\Services\PriceFormatter;

/**
 * Controller for cart management.
 */
class CartController extends Controller
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var PriceFormatter
     */
    private $priceFormatter;

    /**
     * @param CartService $cartService
     * @param PriceFormatter $priceFormatter
     */
    public function __construct(CartService $cartService, PriceFormatter $priceFormatter)
    {
        $this->cartService = $cartService;
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * Display cart list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartData = $this->cartService->getCartData();

        return view('cart')->with('cartData', $cartData);
    }

    /**
     * Add product to cart.
     *
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function store(Product $product): RedirectResponse
    {
        Cart::instance('default')->add($product->id, $product->name, 1, $product->price)->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was successfully added to your cart!');
    }

    /**
     * Update product quantity in cart.
     *
     * @param  Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $result = $this->cartService->updateCart($id, $request);

        return response()->json($result);
    }

    /**
     * Remove product from cart.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        Cart::instance('default')->remove($id);

        return back()->with('success_message', 'Item was successfully removed from your cart!');
    }
}
