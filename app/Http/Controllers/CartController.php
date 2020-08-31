<?php

namespace App\Http\Controllers;

use App\Services\Cart\CartService;
use App\Services\Product\ProductsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Product;
use Illuminate\Support\Facades\Validator;
use App\Services\PriceFormatter;
use Gloudemans\Shoppingcart\Exceptions\InvalidRowIDException;

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
     * @var ProductsService
     */
    private $productService;


    public function __construct(
        CartService $cartService,
        PriceFormatter $priceFormatter,
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
        $success = false;
        $errors = [];
        $itemQty = null;
        $itemSubtotal = null;
        $total = $this->priceFormatter->formatPrice(Cart::total());
        $subtotal = $this->priceFormatter->formatPrice(Cart::subtotal());
        $quantity = Cart::count();
        $tax = $this->priceFormatter->formatPrice(Cart::tax());

        try {
            $item = Cart::get($id);
            $itemQty = $item->qty;
            $itemSubtotal = $this->priceFormatter->formatPrice($item->subtotal());
        } catch (InvalidRowIDException $e) {
            $errors[] = 'The product was not found in cart.';
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors[] = 'Wrong format of quantity!';
        }
        try {
            $product = $this->productService->getById($request->get('productId'));
        } catch (ModelNotFoundException $e) {
            $errors[] = 'The product was not found.';
        }

        if ($request->quantity > $product->quantity) {
            $errors[] = 'We currently do not have enough items in stock.';
        }

        if (empty($errors)) {
            try {
                $success = true;
                $item = Cart::update($id, $request->quantity);
                $itemQty = $item->qty;
                $quantity = Cart::count();
                $itemSubtotal = $this->priceFormatter->formatPrice($item->subtotal());
                $total = $this->priceFormatter->formatPrice(Cart::total());
                $subtotal = $this->priceFormatter->formatPrice(Cart::subtotal());
                $tax = $this->priceFormatter->formatPrice(Cart::tax());
            } catch (\Exception $e) {
                $errors[] = 'Something went wrong!';
            }
        }

        $result = [
            'success' => $success,
            'errors' => $errors,
            'quantity' => $quantity,
            'itemSubtotal' => $itemSubtotal,
            'itemQty' => $itemQty,
            'total' => $total,
            'subtotal' => $subtotal,
            'tax' => $tax
        ];

        return response()->json($result);
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
