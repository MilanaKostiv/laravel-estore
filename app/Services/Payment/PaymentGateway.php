<?php

namespace App\Services\Payment;

use App\Exceptions\PaymentException;
use App\Http\Requests\CheckoutRequest;

/**
 * Interface for Payment Gateway.
 */
interface PaymentGateway
{
    /**
     * Making payment.
     *
     * @param CheckoutRequest $request
     *
     * @throws PaymentException
     * @return void
     */
    public function charge(CheckoutRequest $request): void;
}
