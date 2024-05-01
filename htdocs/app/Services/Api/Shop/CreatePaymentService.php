<?php

namespace App\Services\Api\Shop;

use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class CreatePaymentService extends BaseShopService
{
    /**
     * @param Request $request
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function createPayment(Request $request): PaymentIntent
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        return $stripe->paymentIntents->create([
            'amount' => $request->amount,
            'currency' => 'jpy',
            'description' => 'LaraEC',
            'metadata' => [
                'username' => $request->username,
            ],
        ]);
    }
}
