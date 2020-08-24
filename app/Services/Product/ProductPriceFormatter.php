<?php

namespace App\Services\Product;

use App\Product;
use App\Services\PriceFormatter;

/**
 * Service for formatting prices.
 */
class ProductPriceFormatter
{
    /**
     * @var PriceFormatter
     */
    private $priceFormatter;

    /**
     * @param PriceFormatter $priceFormatter
     */
    public function __construct(PriceFormatter $priceFormatter)
    {
        $this->priceFormatter = $priceFormatter;
    }

    /**
     * Add formattedPrice property to product.
     *
     * @param Product $product
     * @return Product
     */
    public function addFormattedPriceToProduct(Product $product): Product
    {
        $product->formattedPrice = $this->priceFormatter->formatPrice($product->price);

        return $product;
    }

    /**
     * Add formattedPrice property to products in collection.
     *
     * @param \Illuminate\Support\Collection $products
     * @return \Illuminate\Support\Collection
     */
    public function addFormattedPriceToProducts(\Illuminate\Support\Collection $products): \Illuminate\Support\Collection
    {
        foreach ($products as $key => $product) {
            isset($product->qty) ? $qty = $product->qty : $qty = 1;
            $products[$key]->formattedPrice = $this->priceFormatter->formatPrice($product->price * $qty);
        }

        return $products;
    }
}