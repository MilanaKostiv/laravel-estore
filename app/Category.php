<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Category Eloquent Model.
 */
class Category extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'slug'];

    /**
     * Category-to-product relation.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany('App\Product');
    }
}
