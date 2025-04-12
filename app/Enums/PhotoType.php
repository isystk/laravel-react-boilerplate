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

    public function dirName(): string
    {
        return match ($this) {
            self::Stock => 'stock',
            self::Contact => 'contact',
        };
    }

    /**
     * @param ?int $code
     * @return ?PhotoType
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

    public static function getIdByDirName(string $dirName): ?PhotoType
    {
        return match ($dirName) {
            'stock' => self::Stock,
            'contact' => self::Contact,
            default => throw new \RuntimeException('An unexpected error occurred.')
        };
    }

}
