<?php

namespace App\Utils;

use App\Enums\Gender;
use App\Enums\Age;

class ConstUtil
{

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function searchConst(): array
    {

        $gender = [];
        foreach (Gender::cases() as $e) {
            $gender[] = (object)[
                'key' => $e->value,
                'value' => $e->label(),
            ];
        }

        $age = [];
        foreach (Age::cases() as $e) {
            $age[] = (object)[
                'key' => $e->value,
                'value' => $e->label(),
            ];
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
