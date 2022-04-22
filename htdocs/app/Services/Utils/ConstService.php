<?php

namespace App\Services\Utils;

use App\Enums\Gender;
use App\Enums\Age;

class ConstService
{

  public static function searchConst()
  {

    $gender = [];
    foreach (Gender::cases() as $case) {
      array_push($gender, (object) [
        'key' => $case->value,
        'value' => Gender::getDescription($case->value),
      ]);
    }

    $age = [];
    foreach (Age::cases() as $case) {
      array_push($age, (object) [
        'key' => $case->value,
        'value' => Age::getDescription($case->value),
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
