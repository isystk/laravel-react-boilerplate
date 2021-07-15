<?php

namespace App\Services;

use App\Constants\ErrorType;
use Illuminate\Http\Request;
use App\Constants\Gender;
use App\Constants\Age;

class ConstService extends Service
{

  public function __construct(
    Request $request
) {
    parent::__construct($request);
  }

  public static function searchConst()
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

    return [
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
  }
}
