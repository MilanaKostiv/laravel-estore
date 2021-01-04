<?php

namespace App\Services\Payment;

use App\Http\Requests\CheckoutRequest;

/**
 * If gateway was not specified.
 */
class NullPaymentGateway implements PaymentGateway
{
    /**
     * Making payment.
     *
     * @param CheckoutRequest $request
     */
    public function charge(CheckoutRequest $request): void {}
}
