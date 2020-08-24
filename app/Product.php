<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
