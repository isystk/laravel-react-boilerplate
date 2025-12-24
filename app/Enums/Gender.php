<?php

namespace App\Enums;

enum Gender: int
{
    /** 男性 */
    case Male = 0;
    /** 女性 */
    case Female = 1;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.Gender' . $this->value);
    }
}
