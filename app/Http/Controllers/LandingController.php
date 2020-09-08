<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductPriceFormatter;
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
     * Render landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $products = $this->productService->getFeaturedList('rand', $this->productsPerPage);
        $formattedProducts = $this->productPriceFormatter->addFormattedPriceToProducts($products);

        return view('landing')->with('products', $formattedProducts);
    }
}
