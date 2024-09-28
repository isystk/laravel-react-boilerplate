<?php

namespace App\Enums;

enum ImportType: int
{
    /** スタッフ */
    case Staff = 1;

    /**
     * @return string
     */
    public function label(): string
    {
        return __('enums.ImportType' . $this->value);
    }

    /**
     * @param ?int $code
     * @return ?ImportType
     */
    public static function get(?int $code): ?ImportType
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
     * @param int|null $code
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
