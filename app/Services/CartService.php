<?php

namespace App\Services;

use App\Services\PriceFormatter;
use App\Services\Product\ProductPriceFormatter;
use App\Services\Product\ProductsService;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Cart service management.
 */
class CartService
{
    /**
     * @var ProductPriceFormatter
     */
    private $productPriceFormatter;

    /**
     * @var PriceFormatter
     */
    private $priceFormatter;

    /**
     * @var int
     */
    private $tax;

    /**
     * @var string
     */
    private $subtotal;

    /**
     * @var ProductsService
     */
    private $productService;

    /**
     * @param ProductPriceFormatter $productPriceFormatter
     * @param PriceFormatter $priceFormatter
     * @param ProductsService $productService
     */
    public function __construct(
        ProductPriceFormatter $productPriceFormatter,
        PriceFormatter $priceFormatter,
        ProductsService $productService
    ) {
        $this->productPriceFormatter = $productPriceFormatter;
        $this->priceFormatter = $priceFormatter;
        $this->productService = $productService;
    }

    /**
     * Get tax config.
     *
     * @return int
     */
    private function getTax(): int
    {
        if ($this->tax === null) {
            $this->tax = config('cart.tax');
        }

        return $this->tax;
    }

    /**
     * Gets cart related data.
     *
     * @return array
     */
    public function getCartData(): array
    {

        return [
            'amount'   => Cart::instance('default')->count(),
            'items'    => $this->productPriceFormatter->addFormattedPriceToProducts(Cart::instance('default')->content()),
            'subtotal' => $this->getSubtotalFormatted(),
            'tax'   =>  $this->getTax(),
            'tax_amount' => $this->getTaxAmountFormatted(),
            'total' => $this->getTotalFormatted(),
            'saveForLaterItems' => Cart::instance('saveForLater')->content(),
            'saveForLaterAmount' => Cart::instance('saveForLater')->count()
        ];
    }

    /**
     * Gets cart subtotal.
     *
     * @return string
     */
    private function getSubtotal(): string
    {
        if ($this->subtotal == null)
        {
            $this->subtotal = Cart::instance('default')->subtotal();
        }

       return $this->subtotal;
    }

    /**
     * Calculates tax amount based on subtotal and tax rate.
     *
     * @return float
     */
    private function getTaxAmount(): float
    {
        return round($this->getTax() / 100 * $this->getSubtotal(), 2);
    }


    /**
     * Calculates total price based on subtotal and tax rate.
     *
     * @return float
     */
    private function getTotal(): float
    {
        $tax =  $this->getTax() / 100;
        $subtotal = $this->getSubtotal();
        $total = $subtotal * (1 + $tax);

        return $total;
    }

    /**
     * Formats total price.
     *
     * @return string
     */
    private function getTotalFormatted(): string
    {
        return $this->priceFormatter->formatPrice($this->getTotal());
    }

    /**
     * Formats subtotal.
     *
     * @return string
     */
    private function getSubtotalFormatted(): string
    {
        return $this->priceFormatter->formatPrice($this->getSubtotal());
    }

    /**
     * Formats tax amount.
     *
     * @return string
     */
    private function getTaxAmountFormatted(): string
    {
        return $this->priceFormatter->formatPrice($this->getTaxAmount());
    }

    /**
     * Updates item quantity in cart.
     *
     * @param string $cartItemId
     * @param Request $request
     * @return array
     */
    public function updateCart(string $cartItemId, Request $request): array
    {
        $result = [];

        $success = false;
        $errors = $this->validateCart($request);

        if (empty($errors)) {
            $item = Cart::update($cartItemId, $request->quantity);
            $success = true;

            $result['quantity'] = Cart::count();
            $result['itemSubtotal'] = $this->priceFormatter->formatPrice($item->subtotal());
            $result['itemQty'] = $item->qty;
            $result['total'] = $this->priceFormatter->formatPrice(Cart::total());
            $result['subtotal'] = $this->priceFormatter->formatPrice(Cart::subtotal());
            $result['tax'] = $this->priceFormatter->formatPrice(Cart::tax());
        }
        $result['success'] = $success;
        $result['errors'] = $errors;

        return $result;
    }

    /**
     * Validates cart before updating.
     *
     * @param Request $request
     * @return array
     */
    private function validateCart(Request $request): array
    {
        $errors = [];

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            $errors[] = 'Wrong format of quantity.';
        }

        try {
            $product = $this->productService->getById($request->get('productId'));
        } catch (ModelNotFoundException $e) {
            $errors[] = 'The product was not found.';
        }
        if ($request->quantity > $product->quantity) {
            $errors[] = 'We currently do not have enough items in stock.';
        }

        return $errors;
    }
}
