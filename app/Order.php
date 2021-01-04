<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Order Eloquent Model.
 */
class Order extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'billing_email', 'billing_name', 'billing_address', 'billing_city',
        'billing_state', 'billing_postalcode', 'billing_phone', 'billing_name_on_card',
        'billing_subtotal', 'billing_tax', 'billing_total', 'payment_gateway', 'error',
    ];

    /**
     * OrderService-to-user relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        $this->belongsTo('App\User');
    }

    /**
     * OrderService-to-product relation.
     *
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany('App\Product')->withPivot('quantity')->withTimestamps();
    }
}
