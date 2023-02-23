<?php

namespace App\Enums;

enum Gender: int
{

    /** 男性 */
    case Male = 0;
    /** 女性 */
    case Female = 1;

    /**
     * @return string
     */
    public function label(): string
    {
        return __('enums.Gender' . $this->value);
    }

    /**
     * @param int $code
     * @return ?Gender
     */
    public static function get(int $code): ?Gender
    {
        foreach (self::cases() as $e) {
            if ($e->value === $code) {
                return $e;
            }
        }
        return null;
    }

}
