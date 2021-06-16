<?php

namespace App\Services;

use App\Enums\Gender;
use App\Enums\Age;

class ConstService
{

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
