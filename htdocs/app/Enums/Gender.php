<?php

namespace App\Enums;

enum Gender: int
{

    /** 男性 */
    case Male = 0;
    /** 女性 */
    case Female = 1;

    /**
     * @param int $value
     * @return string
     */
    public static function getDescription(int $value): string
    {
        return __('enums.Gender' . $value);
    }
}
