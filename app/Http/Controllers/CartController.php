<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Product;
use App\Services\PriceFormatter;
use Illuminate\View\View;

/**
 * Cart rendering and modification.
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
     * @return View
     */
    public function index(): View
    {
        $cartData = $this->cartService->getCartData();

        return view('cart')->with('cartData', $cartData);
    }

    /**
     * Adds product to cart.
     *
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function addToCart(Product $product): RedirectResponse
    {
        Cart::instance('default')->add($product->id, $product->name, 1, $product->price)->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was successfully added to your cart!');
    }

    /**
     * Recalculates cart subtotal, total, tax amount.
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
     * Removes product from cart.
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
