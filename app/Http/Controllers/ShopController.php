<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Controller for products management.
 */
class ShopController extends Controller
{
    /**
     * @var ProductsService
     */
    private $productService;

    /**
     * @var int
     */
    private $productsPerPage = 12;

    /**
     * @var int Amount of products displayed in section "You might also like".
     */
    private $proposedProductsPerPage = 4;

    /**
     * @param ProductsService $productService
     */
    public function __construct(ProductsService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display products list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $products = $this->productService->getInRandomOrder($this->productsPerPage);

        return view('shop')->with('products', $products);
    }

    /**
     * Display the specified product.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $slug)
    {
        try {
            $product = $this->productService->getBySlug($slug);
        } catch (ModelNotFoundException $e) {
            session()->flash('errors', collect('Requested product does not exist!'));
            return redirect()->route('shop.index');
        }

        $mightAlsoLike = $this->productService->getBySlugNotInRandomOrder($slug, $this->proposedProductsPerPage);

        return view('product')->with([
            'product' => $product,
            'mightAlsoLike' => $mightAlsoLike
        ]);
    }
}
