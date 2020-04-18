<?php

namespace App\Http\Controllers;

use App\Product;

/**
 * Controller for landing page.
 */
class LandingController extends Controller
{
    /**
     * @var int
     */
    private $productsPerPage = 8;

    /**
     * Render the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $products = Product::where('featured', true)->inRandomOrder()->take($this->productsPerPage)->get();

        return view('landing')->with('products', $products);
    }
}
