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

    /**
     * コードに紐づくEnumを返却する
     */
    public static function get(?int $code): ?Gender
    {
        if (null === $code) {
            return null;
        }
        foreach (self::cases() as $e) {
            if ($e->value === $code) {
                return $e;
            }
        }

        return null;
    }

    /**
     * 引数の値に紐づくラベルを返却する
     */
    public static function getLabel(?int $code): string
    {
        if (null === $code || null === self::get($code)) {
            return '';
        }

        return self::get($code)->label();
    }
}
