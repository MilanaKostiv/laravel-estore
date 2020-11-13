<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * CategoryProduct Eloquent Model.
 */
class CategoryProduct extends Model
{
    /**
     * @var string
     */
    protected $table = 'category_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['product_id', 'category_id'];
}
