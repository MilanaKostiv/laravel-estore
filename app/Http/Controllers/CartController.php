<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartService;
use App\Services\Product\ProductPriceFormatter;
use App\Services\Product\ProductsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Product;
use Illuminate\Support\Facades\Validator;

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
     * @var ProductPriceFormatter
     */
    private $priceFormatter;

    /**
     * @var ProductsService
     */
    private $productService;

    /**
     * @param CartService $cartService
     * @param ProductPriceFormatter $priceFormatter
     * @param ProductsService $productService
     */
    public function __construct(
        CartService $cartService,
        ProductPriceFormatter $priceFormatter,
        ProductsService $productService
    ) {
        $this->cartService = $cartService;
        $this->priceFormatter = $priceFormatter;
        $this->productService = $productService;
    }

    /**
     * Display cart list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartData = $this->cartService->getAllData();

        return view('cart')->with('cartData', $cartData);
    }

    /**
     * Add product to cart.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product)
    {
        Cart::instance('default')->add($product->id, $product->name, 1, $product->price)->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was successfully added to your cart!');
    }

    /**
     * Update product quantity in cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            session()->flash('errors', collect('Wrong quantity format'));
            return response()->json(['success' => false], 400);
        }

        try {
            $product = $this->productService->getById($request->get('productId'));
        } catch (ModelNotFoundException $e) {
            session()->flash('errors', collect('The product was not found.'));
            return response()->json(['success' => false], 400);
        }

        if ($request->quantity > $product->quantity) {
            session()->flash('errors', collect('We currently do not have enough items in stock.'));
            return response()->json(['success' => false], 400);
        }

        Cart::update($id, $request->quantity);

        session()->flash('success_message', 'Quantity was updated successfully!');
        return response()->json(['success' => true]);
    }

    /**
     * Remove product from cart.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        Cart::instance('default')->remove($id);

        return back()->with('success_message', 'Item was successfully removed from your cart!');
    }
}
