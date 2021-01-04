<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductPriceFormatter;
use App\Services\Product\ProductsService;
use Illuminate\View\View;

/**
 * Landing page rendering.
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
     * @var ProductPriceFormatter
     */
    private $productPriceFormatter;

    /**
     * @param ProductsService $productService
     * @param ProductPriceFormatter $productPriceFormatter
     */
    public function __construct(
        ProductsService $productService,
        ProductPriceFormatter $productPriceFormatter
    ) {
        $this->productService = $productService;
        $this->productPriceFormatter = $productPriceFormatter;
    }

    /**
     * Renders landing page.
     *
     * @return View
     */
    public function index(): View
    {
        $products = $this->productService->getFeaturedList('rand', $this->productsPerPage);
        $formattedProducts = $this->productPriceFormatter->addFormattedPriceToProducts($products);

        return view('landing')->with('products', $formattedProducts);
    }
}
