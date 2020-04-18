<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    /**
     * Format a currency value.
     *
     * @return string
     */
    public function presentPrice(): string
    {
        $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($this->attributes['price'],'USD');
    }

    /**
     * Generate image URL.
     *
     * @return string
     */
    public function presentImage(): string
    {
        $path =  file_exists('img/' . $this->attributes['image'])
            ? 'img/' . $this->attributes['image']
            : 'img/not-found.jpg';

        return asset($path);
    }
}
