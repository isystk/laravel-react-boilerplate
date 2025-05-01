<?php

namespace App\Services\Api\Cart;

use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class CreatePaymentService extends BaseCartService
{
    /**
     * @param Request $request
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function createPayment(Request $request): PaymentIntent
    {
        $stripe = new StripeClient(config('const.stripe.secret'));

        $items = [
            'amount' => $request->amount,
            'currency' => 'jpy',
            'description' => 'LaraEC',
            'metadata' => [
                'username' => $request->username,
            ],
        ];

        // @phpstan-ignore-next-line
        return $stripe->paymentIntents->create($items);
    }
}
