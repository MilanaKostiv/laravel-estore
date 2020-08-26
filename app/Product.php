<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Product Eloquent Model.
 */
class Product extends Model
{
    /**
     * Product-to-category relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Category');
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
