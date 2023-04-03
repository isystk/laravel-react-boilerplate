<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cookie;

class CookieUtil
{

    const COKKIE_KEYNAME_LIKE = 'like';
    const COKKIE_EXPIRES = 60 * 24; // 24時間

    /**
     * @return array<string>
     */
    public static function getLike(): array
    {
        $value = Cookie::get(self::COKKIE_KEYNAME_LIKE);

        // カンマ区切りのデータを分割
        $likes = [];
        if ($value) {
            $likes = explode(",", $value);
        }

        return $likes;
    }

    /**
     * @param string $value
     */
    public static function saveLike(string $value): void
    {
        $likes = self::getLike();

        if (!in_array($value, $likes)) {

            // 配列に含まれない場合に追加
            array_push($likes, $value);

            // 配列をカンマ区切りに設定
            $result = implode(',', $likes);

            // クッキーに保存
            Cookie::queue(self::COKKIE_KEYNAME_LIKE, $result, self::COKKIE_EXPIRES);
        }
    }

    /**
     * @param string $value
     */
    public static function removeLike(string $value): void
    {
        $likes = self::getLike();

        if (in_array($value, $likes)) {

            // 配列に含まる場合に削除
            $key = array_search($value, $likes);
            array_splice($likes, $key);

            // 配列をカンマ区切りに設定
            $result = implode(',', $likes);

            // クッキーに保存
            Cookie::queue(self::COKKIE_KEYNAME_LIKE, $result, self::COKKIE_EXPIRES);
        }
    }
}
