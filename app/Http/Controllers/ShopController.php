<?php

namespace App\Http\Controllers;

use App\Services\CategoriesService;
use App\Services\Product\ProductsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Services\SearchCriteria;
use App\Services\Product\ProductPriceFormatter;
use Illuminate\View\View;

/**
 * Product list and product page rendering.
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
    private $recommendedProductsPerPage = 4;


    private $searchCriteria;

    /**
     * @var ProductPriceFormatter
     */
    private $productPriceFormatter;

    /**
     * @param ProductsService $productService
     * @param CategoriesService $categoryService
     * @param SearchCriteria $searchCriteria
     * @param ProductPriceFormatter $productPriceFormatter
     */
    public function __construct(
        ProductsService $productService,
        CategoriesService $categoryService,
        SearchCriteria $searchCriteria,
        ProductPriceFormatter $productPriceFormatter
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->searchCriteria = $searchCriteria;
        $this->productPriceFormatter = $productPriceFormatter;
    }

    /**
     * Displays products list.
     *
     * @return View
     */
    public function index(): View
    {
        if (request()->category) {
            $categoryName = optional($this->categoryService->getCategoryBySlug(request()->category))->name;
            $products = $this->productService->getList(request()->sort, request()->category);
        } else {
            $categoryName = 'Featured';
            $order = (request()->sort === null) ? 'rand' : request()->sort;
            $products = $this->productService->getFeaturedList($order);
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
        $products = $this->productPriceFormatter->addFormattedPriceToProducts($paginator->getCollection());

        return view('shop')->with([
            'products' => $products,
            'categories' => $this->categoryService->getAllCategories(),
            'categoryName' => $categoryName,
            'paginator' => $paginator
        ]);
    }

    /**
     * Displays the specified product.
     *
     * @param  string $slug
     * @return View|RedirectResponse
     */
    public function show(string $slug)
    {
        try {
            $product = $this->productService->getBySlug($slug);
            $formattedProduct = $this->productPriceFormatter->addFormattedPriceToProduct($product);
        } catch (ModelNotFoundException $e) {
            session()->flash('errors', collect('Requested product does not exist!'));
            return redirect()->route('shop.index');
        }

        $recommendedList = $this->productPriceFormatter->addFormattedPriceToProducts(
            $this->productService->getRecommendedList($slug, $this->recommendedProductsPerPage)
        );

        return view('product')->with([
            'product' => $formattedProduct,
            'mightAlsoLike' => $recommendedList
        ]);
    }
}
