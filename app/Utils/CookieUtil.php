<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cookie;

class CookieUtil
{
    private const DEFAULT_EXPIRES_MINUTES = 60 * 24 * 365; // 365日

    /**
     * クッキーから配列を取得
     *
     * @return array<string>
     */
    public static function get(string $key): array
    {
        $value = Cookie::get($key);

        return $value ? explode(',', $value) : [];
    }

    /**
     * 値を追加（重複は無視）
     */
    public static function add(string $key, string $value, int $expires = self::DEFAULT_EXPIRES_MINUTES): void
    {
        $items = self::get($key);
        if (!in_array($value, $items, true)) {
            $items[] = $value;
            Cookie::queue($key, implode(',', $items), $expires);
        }
    }

    /**
     * 値を削除
     */
    public static function remove(string $key, string $value, int $expires = self::DEFAULT_EXPIRES_MINUTES): void
    {
        $items    = self::get($key);
        $filtered = array_filter($items, fn ($item) => $item !== $value);

        if (count($items) !== count($filtered)) {
            Cookie::queue($key, implode(',', $filtered), $expires);
        }
    }
}
