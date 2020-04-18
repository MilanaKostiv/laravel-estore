<?php

namespace App\Http\Controllers;

use App\Product;

/*
 * Controller for product view.
 */
class ShopController extends Controller
{
    /**
     * @var int
     */
    private $productsPerPage = 12;

    /**
     * @var int Amount of products displayed in section "You might also like".
     */
    private $proposedProductsPerPage = 4;

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $products = Product::inRandomOrder()->take($this->productsPerPage)->get();

        return view('shop')->with('products', $products);
    }

    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug): \Illuminate\View\View
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $mightAlsoLike = Product::where('slug', '!=', $slug)
            ->inRandomOrder()->take($this->proposedProductsPerPage)->get();

        return view('product')->with([
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike
        ]);
    }
}
