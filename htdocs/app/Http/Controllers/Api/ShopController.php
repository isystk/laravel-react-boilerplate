<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Models\Stock;

use App\Models\Cart;
use App\Models\Order;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class ShopController extends ApiController
{
    public function index()
    {
        try {
            $stocks = Stock::Paginate(6); //Eloquantで検索
            $result = [
                'result'      => true,
                'stocks'     => $stocks
            ];
        } catch (\Exception $e) {
            $result = [
                'result' => false,
                'error' => [
                    'messages' => [$e->getMessage()]
                ],
            ];
            return $this->resConversionJson($result, $e->getCode());
        }
        return $this->resConversionJson($result);
    }
}
