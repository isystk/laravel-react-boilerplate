<?php

namespace App\Enums;

enum Gender: int
{

    /** @var int 男性 */
    case Male = 0;
    /** @var int 女性 */
    case Female = 1;

    public static function getDescription($value) {
        return __('enums.Gender'. $value);
    }
}
