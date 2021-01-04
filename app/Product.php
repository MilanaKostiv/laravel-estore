<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Product Eloquent Model.
 */
class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['quantity'];

    /**
     * Product-to-category relation.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany('App\Category')->withTimestamps();
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
