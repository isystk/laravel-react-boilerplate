<?php

namespace App\Enums;

enum PhotoType: int
{
    /** 商品 */
    case Stock = 1;
    /** お問い合わせ */
    case Contact = 2;

    /**
     * ラベルを返却する
     */
    public function label(): string
    {
        return __('enums.PhotoType' . $this->value);
    }

    /**
     * タイプを返却する
     */
    public function type(): string
    {
        return match ($this) {
            self::Stock => 'stock',
            self::Contact => 'contact',
        };
    }

    /**
     * コードに紐づくEnumを返却する
     */
    public static function get(?int $code): ?PhotoType
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

    /**
     * タイプに紐づくEnumを返却する
     */
    public static function getByType(string $type): PhotoType
    {
        return match ($type) {
            'stock' => self::Stock,
            'contact' => self::Contact,
            default => throw new \RuntimeException('An unexpected error occurred.')
        };
    }
}
