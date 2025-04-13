<?php

namespace App\Utils;

use App\Enums\Age;
use App\Enums\Gender;

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
                'data' => config('const.stripe.key'),
            ],
            [
                'name' => 'gender',
                'data' => $gender,
            ],
            [
                'name' => 'age',
                'data' => $age,
            ],
        ];
    }
}
