<?php

namespace App\Services\Utils;

use Illuminate\Support\Facades\Cookie;

class CookieService
{

  const COKKIE_KEYNAME_LIKE = 'like';
  const COKKIE_EXPIRES = 60 * 24; // 24時間

  public static function getLike()
  {
    $value = Cookie::get(self::COKKIE_KEYNAME_LIKE);

    // カンマ区切りのデータを分割
    $likes = [];
    if ($value) {
      $likes = explode(",", $value);
    }

    return $likes;
  }

  public static function saveLike($value)
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

  public static function removeLike($value)
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
