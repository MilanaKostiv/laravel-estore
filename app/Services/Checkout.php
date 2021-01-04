<?php

namespace App\Services;

use App\Exceptions\PaymentException;
use App\Http\Requests\CheckoutRequest;
use App\Services\Payment\PaymentFactoryInterface;
use App\Services\OrderService;
use App\Services\Product\ProductsService;
use Gloudemans\Shoppingcart\Facades\Cart;

/**
 * Checkout processing.
 */
class Checkout
{
    /**
     * @var \App\Services\OrderService
     */
    private $ordersService;

    /**
     * @var ProductsService
     */
    private $productsService;

    /**
     * @var PaymentFactoryInterface
     */
    private $paymentFactory;

    /**
     * @param OrderService $ordersService
     * @param ProductsService $productsService
     * @param PaymentFactoryInterface $paymentFactory
     */
    public function __construct(
        OrderService $ordersService,
        ProductsService $productsService,
        PaymentFactoryInterface $paymentFactory
    ) {
        $this->ordersService = $ordersService;
        $this->productsService = $productsService;
        $this->paymentFactory = $paymentFactory;
    }

    /**
     * Making payment and order processing.
     *
     * @param CheckoutRequest $request
     * @throws PaymentException
     */
    public function execute(CheckoutRequest $request)
    {
        $cart = Cart::instance('default')->content();

        $products = Cart::instance('default')->content()->map(function ($item) {
            return ['product_id' => $item->model->id, 'quantity' => $item->qty];
        })->values();

        $this->ordersService->makeOrder($request, $products);
        $this->productsService->updateQuantity($cart->values());

        $gateway = $this->paymentFactory->create();
        $gateway->charge($request);
    }
}
