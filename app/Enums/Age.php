<?php

namespace App\Enums;

enum Age: int
{
    /** ～19歳 */
    case Under19 = 1;
    /** 20歳～29歳 */
    case Over20 = 2;
    /** 30歳～39歳 */
    case Over30 = 3;
    /** 40歳～49歳 */
    case Over40 = 4;
    /** 50歳～59歳 */
    case Over50 = 5;
    /** 60歳～ */
    case Over60 = 6;

    /**
     * @return string
     */
    public function label(): string
    {
        return __('enums.Age' . $this->value);
    }

    /**
     * @param ?int $code
     * @return ?Age
     */
    public static function get(?int $code): ?Age
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
     * @param ?int $code
     * @return ?string
     */
    public static function getLabel(?int $code): ?string
    {
        if (null === $code || null === self::get($code)) {
            return "";
        }
        return self::get($code)->label();
    }

}
