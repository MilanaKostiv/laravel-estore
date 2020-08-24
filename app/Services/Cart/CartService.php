<?php

namespace App\Services\Cart;

use App\Services\PriceFormatter;
use App\Services\Product\ProductPriceFormatter;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * Service for cart management.
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
     * @param ProductPriceFormatter $productPriceFormatter
     * @param PriceFormatter $priceFormatter
     */
    public function __construct(ProductPriceFormatter $productPriceFormatter, PriceFormatter $priceFormatter)
    {
        $this->productPriceFormatter = $productPriceFormatter;
        $this->priceFormatter = $priceFormatter;
    }

    /**
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
     * @return array
     */
    public function getAllData(){

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
     * Get cart subtotal.
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
     * Calculate tax amount based on subtotal and tax rate.
     *
     * @return float|int
     */
    private function getTaxAmount()
    {
        return $this->getTax() / 100 * $this->getSubtotal();
    }


    /**
     * Calculate total price based on subtotal and tax rate.
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
     * Format total price.
     *
     * @return string
     */
    private function getTotalFormatted(): string
    {
        return $this->priceFormatter->formatPrice($this->getTotal());
    }

    /**
     * Format subtotal.
     *
     * @return string
     */
    private function getSubtotalFormatted(): string
    {
        return $this->priceFormatter->formatPrice($this->getSubtotal());
    }

    /**
     * Format tax amount.
     *
     * @return string
     */
    private function getTaxAmountFormatted(): string
    {
        return $this->priceFormatter->formatPrice($this->getTaxAmount());
    }
}