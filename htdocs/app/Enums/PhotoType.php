<?php

namespace App\Enums;

enum PhotoType: int
{
    /** 商品 */
    case Stock = 1;
    /** お問い合わせ */
    case Contact = 2;

    /**
     * @return string
     */
    public function label(): string
    {
        return __('enums.PhotoType' . $this->value);
    }

    /**
     * @param int $code
     * @return ?PhotoType
     */
    public static function get(int $code): ?PhotoType
    {
        foreach (self::cases() as $e) {
            if ($e->value === $code) {
                return $e;
            }
        }
        return null;
    }

}
