<?php

namespace App\Enums;

enum ImageType: int
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
        return __('enums.ImageType' . $this->value);
    }

    /**
     * タイプを返却する
     */
    public function type(): string
    {
        return match ($this) {
            self::Stock   => 'stock',
            self::Contact => 'contact',
        };
    }

    /**
     * タイプに紐づくEnumを返却する
     */
    public static function getByType(string $type): ImageType
    {
        return match ($type) {
            'stock'   => self::Stock,
            'contact' => self::Contact,
            default   => throw new \RuntimeException('An unexpected error occurred.')
        };
    }
}
