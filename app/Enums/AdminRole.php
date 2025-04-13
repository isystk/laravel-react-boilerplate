<?php

namespace App\Enums;

enum AdminRole: string
{

    /** 管理者 */
    case Manager = 'manager';
    /** 上位管理者 */
    case HighManager = 'high-manager';

    /**
     * @return string
     */
    public function label(): string
    {
        return __('enums.AdminRole' . $this->value);
    }

    /**
     * @param ?string $role
     * @return ?AdminRole
     */
    public static function get(?string $role): ?AdminRole
    {
        if (null === $role) {
            return null;
        }
        foreach (self::cases() as $e) {
            if ($e->value === $role) {
                return $e;
            }
        }
        return null;
    }

    /**
     * @param string|null $code
     * @return string
     */
    public static function getLabel(?string $code): string
    {
        if (null === $code || null === self::get($code)) {
            return "";
        }
        return self::get($code)->label();
    }

}
