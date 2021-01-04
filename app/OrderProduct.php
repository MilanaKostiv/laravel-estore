<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * OrderProduct Eloquent Model.
 */
class OrderProduct extends Model
{
    /**
     * @var string
     */
    protected $table = 'order_product';

    /**
     * @var array
     */
    protected $fillable = ['product_id', 'order_id', 'quantity'];
}
