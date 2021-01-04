<?php

namespace App\Http\Controllers;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * "Save For Later" list modifications.
 */
class SaveForLaterController extends Controller
{
    /**
     * Adds product to saveForLater cart.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
       $item = Cart::instance('default')->get($request->id);
        Cart::instance('default')->remove($request->id);
        Cart::instance('saveForLater')->add($item->id, $item->name, $item->qty, $item->price)
            ->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was save for later successfully!');
    }

    /**
     * Removes product from saveForLater cart.
     *
     * @param  string  $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        Cart::instance('saveForLater')->remove($id);

        return redirect()->route('cart.index')->with('success_message', 'Item was removed form Save For Later successfully!');
    }

    /**
     * Moves product with $id from saveForLater cart to default one.
     *
     * @param string $id Identifier of product in saveForLater cart.
     * @return RedirectResponse
     */
    public function moveToCart(string $id): RedirectResponse
    {
        $item = Cart::instance('saveForLater')->get($id);
        Cart::instance('saveForLater')->remove($id);
        Cart::instance('default')->add($item->id, $item->name, $item->qty, $item->price)->associate('App\Product');

        return redirect()->route('cart.index')->with('success_message', 'Item was moved to your cart successfully!');
    }
}
