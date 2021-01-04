<?php

namespace App\Http\Controllers\Voyager;

use App\Services\Product\ProductsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Services\CategoriesService;

/**
 * Customization of VoyagerBaseController for products.
 */
class ProductsController extends VoyagerBaseController
{
    /**
     * @var CategoriesService
     */
    private $categoryService;

    /**
     * @var ProductsService
     */
    private $productService;

    /**
     * @param CategoriesService $categoryService
     * @param ProductsService $productService
     */
    public function __construct(CategoriesService $categoryService, ProductsService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * Add a new item of our Data Type BRE(A)D
     *
     * @param Request $request
     * @return View
     */
    public function create(Request $request): View
    {
        $view = parent::create($request);
        $view['allCategories'] = $this->categoryService->getAllCategories();
        $oldInput =  session()->getOldInput();
        $view['categoriesForProduct'] = isset($oldInput['category'])
            ? $oldInput['category']
            : [];

        return $view;
    }

    /**
     * Edit an item of our Data Type BR(E)AD
     *
     * @param Request $request
     * @param $id
     * @return View
     */
    public function edit(Request $request, $id): View
    {
        $view = parent::edit($request, $id);
        $view['allCategories'] = $this->categoryService->getAllCategories();

        $oldInput =  session()->getOldInput();

        $view['categoriesForProduct'] = isset($oldInput['category'])
            ? $oldInput['category']
            : $this->productService->getProductCategories($id)->pluck('id')->toArray();

        return $view;
    }

    /**
     * Read an item of our Data Type B(R)EAD
     *
     * @param Request $request
     * @param $id
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $view = parent::show($request, $id);
        $view['categoriesForProduct'] = $this->productService->getProductCategories($id);

        return $view;
    }
}
