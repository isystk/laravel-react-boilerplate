<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Services\CheckFormData;

class ConstController extends ApiController
{
    public function index()
    {
        $gender = CheckFormData::checkGender($contact);
        $age = CheckFormData::checkAge($contact);

        try {
            $consts = [
              [
                'name' => 'stripe_key',
                'data' => env('STRIPE_KEY')
              ]
            ];
            $result = [
                'result'      => true,
                'consts'     =>  [
                  'data' => $consts,
                ]
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
