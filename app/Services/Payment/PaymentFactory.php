<?php

namespace App\Services\Payment;

use App\Services\Payment\PaymentGateway;

/**
 * Creating payment gateway objects.
 */
class PaymentFactory implements PaymentFactoryInterface
{
    /**
     * Creates gateway objects based on a request.
     *
     * @return \App\Services\Payment\PaymentGateway
     */
    public function create() : PaymentGateway
    {
        if (request()->get('payment_gateway') == 'stripe') {
            return new StripePaymentGateway();
        }

        return new NullPaymentGateway();
    }
}
