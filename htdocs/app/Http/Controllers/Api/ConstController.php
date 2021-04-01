<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Enums\Gender;
use App\Enums\Age;

class ConstController extends ApiController
{
    public function index()
    {

      $gender = [];
      foreach (Gender::getValues() as $code) {
        array_push($gender, (object) [
          'key' => $code,
          'value' => Gender::getDescription($code),
        ]);
      }

      $age = [];
      foreach (Age::getValues() as $code) {
        array_push($age, (object) [
          'key' => $code,
          'value' => Age::getDescription($code),
        ]);
      }

      try {
          $consts = [
            [
              'name' => 'stripe_key',
              'data' => env('STRIPE_KEY')
            ],
            [
              'name' => 'gender',
              'data' => $gender
            ],
            [
              'name' => 'age',
              'data' => $age
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
