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
     * @param \Illuminate\Support\Collection $items
     * @return \Illuminate\Support\Collection
     */
    public function addFormattedPriceToProducts(\Illuminate\Support\Collection $items): \Illuminate\Support\Collection
    {
        foreach ($items as $key => $item) {
            isset($item->qty) ? $qty = $item->qty : $qty = 1;
            $items[$key]->formattedPrice = $this->priceFormatter->formatPrice($item->price * $qty);
        }

        return $items;
    }
}