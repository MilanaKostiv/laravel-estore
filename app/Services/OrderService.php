<?php

namespace App\Services;

use App\Http\Requests\CheckoutRequest;
use App\Repositories\OrderRepository;
use App\Order;
use Illuminate\Support\Collection;

/**
 * Order processing.
 */
class OrderService
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Saves order.
     *
     * @param CheckoutRequest $request
     * @param Collection $products
     * @return Order
     */
    public function makeOrder(CheckoutRequest $request, Collection $products): Order
    {
        // @todo Add product quantity validation.
        // throw an exception if error occurs.

        $order = $this->orderRepository->save($request);
        $this->addProducts($order, $products);

       return $order;
    }

    /**
     * Adds products to order.
     *
     * @param Order $order
     * @param Collection $products
     * @return void
     */
    public function addProducts(Order $order, Collection $products): void
    {

        $this->orderRepository->addProducts($order, $products);
    }

    /**
     * Gets order by id.
     *
     * @param int $id
     * @return Order
     */
    public function get(int $id): Order
    {
        return $this->orderRepository->find($id);
    }
}
