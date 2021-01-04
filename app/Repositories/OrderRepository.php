<?php

namespace App\Repositories;

use App\Order;
use App\Http\Requests\CheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Collection;

/**
 * Order entities repository.
 */
class OrderRepository
{
    /**
     * Saving order entity to DB.
     *
     * @param CheckoutRequest $request
     * @return Order
     */
    public function save(CheckoutRequest $request): Order
    {
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_state' => $request->state,
            'billing_postalcode' => $request->postal_code,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_subtotal' => Cart::instance('default')->subtotal(),
            'billing_tax' => Cart::instance('default')->tax(),
            'billing_total' => Cart::instance('default')->total()
        ]);

        return $order;
    }

    /**
     * Add products to order.
     *
     * @param Order $order
     * @param Collection $products
     * @return void
     */
    public function addProducts(Order $order, Collection $products): void
    {
        $order->products()->attach($products);
    }

    /**
     * Find order by id.
     *
     * @param int $id
     * @return Order
     */
    public function find(int $id): Order
    {
        return Order::find($id);
    }
}
