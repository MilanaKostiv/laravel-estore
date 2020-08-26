<?php

namespace App\Http\Controllers;

use App\Services\Category\CategoriesService;
use App\Services\Product\ProductsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
     * @var CategoriesService
     */
    private $categoryService;

    /**
     * @var int
     */
    private $productsPerPage = 9;

    /**
     * @var int Amount of products displayed in section "You might also like".
     */
    private $proposedProductsPerPage = 4;

    /**
     * @param ProductsService $productService
     * @param CategoriesService $categoryService
     */
    public function __construct(ProductsService $productService, CategoriesService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display products list.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $categories = $this->categoryService->getAllCategories();

        if (request()->category) {
            $products = $this->productService->getProductsInCategory(request()->category);
            $categoryName = optional($this->categoryService->getCategoryBySlug(request()->category))->name;
        } else {
            $products = $this->productService->getFeaturedInRandomOrder();
            $categoryName = 'Featured';
        }

        if (request()->sort == 'low_high') {
            $products = $products->sortBy('price');
        } elseif (request()->sort == 'high_low') {
            $products = $products->sortByDesc('price');
        }

        $page = Paginator::resolveCurrentPage();
        $paginator = new LengthAwarePaginator(
            $products->forPage($page, $this->productsPerPage),
            $products->count(),
            $this->productsPerPage,
            Paginator::resolveCurrentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );

        /** @var \Illuminate\Support\Collection $products */
        $products = $paginator->getCollection();

        return view('shop')->with([
            'products' => $products,
            'categories' => $categories,
            'categoryName' => $categoryName,
            'paginator' => $paginator
        ]);
    }

    /**
     * Display the specified product.
     *
     * @param  string $slug
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
