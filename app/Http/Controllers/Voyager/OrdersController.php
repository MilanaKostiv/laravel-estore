<?php

namespace App\Http\Controllers\Voyager;

use App\Services\OrderService;
use App\Services\Product\ProductPriceFormatter;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Customization of VoyagerBaseController for orders.
 */
class OrdersController extends VoyagerBaseController
{
    /**
     * @var ProductPriceFormatter
     */
    private $productPriceFormatter;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @param ProductPriceFormatter $productPriceFormatter
     * @param OrderService $orderService
     */
    public function __construct(ProductPriceFormatter $productPriceFormatter, OrderService $orderService)
    {
        $this->productPriceFormatter = $productPriceFormatter;
        $this->orderService = $orderService;
    }

    /**
     * Customizes Voyager's display of order.
     *
     * @param Request $request
     * @param mixed $id
     * @return View
     */
    public function show(Request $request, $id): View
    {
        $view = parent::show($request, $id);

        $order = $this->orderService->get($id);
        $products = $order->products;
        if ($products !== null) {
            $view['products'] = $this->productPriceFormatter->addFormattedPriceToProducts($products);
        }

        return $view;
    }
}
