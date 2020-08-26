<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Category Eloquent Model.
 */
class Category extends Model
{
    /**
     * Category-to-product relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany('App\Product');
    }
}
