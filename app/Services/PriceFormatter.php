<?php

namespace App\Services;

/**
 * Service for formatting prices.
 */
class PriceFormatter
{
    /**
     * Format a currency value.
     *
     * @param float $price
     * @return string
     */
    public function formatPrice(float $price): string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($price,'USD');
    }
}
