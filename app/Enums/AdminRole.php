<?php

namespace App\Enums;

enum AdminRole: string
{
    /** 管理者 */
    case Manager = 'manager';
    /** 上位管理者 */
    case HighManager = 'high-manager';

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.AdminRole' . $this->value);
    }

    /**
     * コードに紐づくEnumを返却する
     */
    public static function get(?string $code): ?AdminRole
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
    public static function getLabel(?string $code): string
    {
        if (null === $code || null === self::get($code)) {
            return '';
        }

        return self::get($code)->label();
    }
}
