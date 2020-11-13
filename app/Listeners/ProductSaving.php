<?php

namespace App\Listeners;

use App\Product;
use App\Services\Category\CategoriesService;
use App\Services\Product\ProductsService;
use TCG\Voyager\Events\BreadDataChanged;
use Illuminate\Http\Request;

class ProductSaving
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var CategoriesService
     */
    private $categoriesService;

    /**
     * @var ProductsService
     */
    private $productService;

    /**
     * @param Request $request
     * @param CategoriesService $categoriesService
     * @param ProductsService $productsService
     */
    public function __construct(
        Request $request,
        CategoriesService $categoriesService,
        ProductsService $productsService
    ) {
        $this->request = $request;
        $this->categoriesService = $categoriesService;
        $this->productService = $productsService;
    }

    /**
     * @param BreadDataChanged $event
     * @return void
     */
    public function handle(BreadDataChanged $event): void
    {
        if ($event->data instanceof Product) {
            $productId = $event->data['id'];

            if (($event->changeType === 'Updated') || ($event->changeType === 'Deleted')) {
                $this->productService->deleteProductCategories($productId);
            }

            if ($this->request->has('category')) {
                if (($event->changeType === 'Updated') || ($event->changeType === 'Added')) {
                    $chosenCategories = $this->request->get('category');
                    $this->productService->addProductCategories($productId, $chosenCategories);
                }
            }
        }
    }
}
