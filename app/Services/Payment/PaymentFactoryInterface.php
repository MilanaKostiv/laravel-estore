<?php

namespace App\Services\Payment;

/**
 * Interface for Payment Factories, which create payment gateways.
 */
interface PaymentFactoryInterface
{
    /**
     * Creating payment gateway objects.
     *
     * @return PaymentGateway
     */
    public function create(): PaymentGateway;
}
