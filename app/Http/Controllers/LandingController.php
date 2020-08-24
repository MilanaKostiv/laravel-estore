<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductsService;

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
     * @var ProductsService
     */
    private $productService;

    /**
     * @param ProductsService $productService
     */
    public function __construct(ProductsService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Render landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $products = $this->productService->getFeaturedInRandomOrder($this->productsPerPage);

        return view('landing')->with('products', $products);
    }
}
