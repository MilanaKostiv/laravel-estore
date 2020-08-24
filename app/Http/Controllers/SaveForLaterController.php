<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

/**
 * SaveForLater cart management.
 */
class SaveForLaterController extends Controller
{
    /**
     * Add product to saveForLater cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
       $item = Cart::instance('default')->get($request->id);
        Cart::instance('default')->remove($request->id);
        Cart::instance('saveForLater')->add($item->id, $item->name, $item->qty, $item->price)
            ->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was save for later successfully!');
    }

    /**
     * Remove product from saveForLater cart.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        Cart::instance('saveForLater')->remove($id);

        return redirect()->route('cart.index')->with('success_message', 'Item was removed form Save For Later successfully!');
    }

    /**
     * Move product with $id from saveForLater cart to default one.
     *
     * @param string $id Identifier of product in saveForLater cart.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function moveToCart(string $id): \Illuminate\Http\RedirectResponse
    {
        $item = Cart::instance('saveForLater')->get($id);
        Cart::instance('saveForLater')->remove($id);
        Cart::instance('default')->add($item->id, $item->name, $item->qty, $item->price)->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was moved to your cart successfully!');
    }
}
