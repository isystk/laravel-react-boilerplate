<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class NotificationUtil
{
    /**
     * 非本番環境の場合、タイトルに APP_ENV のprefixを付与して返す。
     */
    public static function addEnvPrefix(string $title): string
    {
        if (app()->isProduction()) {
            return $title;
        }

        $env = (string) config('app.env');

        return '【' . $env . '】' . $title;
    }

    /**
     * メールアドレスが通知送信を許可されているか判定する。
     *
     * 本番環境では常に true を返す。
     * 非本番環境では NOTIFICATION_ALLOWED_ADDRESSES の許可リストと照合する。
     * 許可リストが空の場合はすべてブロックする。
     * ドメイン指定（@example.com 形式）にも対応する。
     */
    public static function isAllowed(string $email): bool
    {
        if (app()->isProduction()) {
            return true;
        }

        $raw = (string) config('notification_guard.allowed_addresses', '');

        if ($raw === '') {
            Log::info('許可リストによって送信がブロックされました: ' . $email);

            return false;
        }

        $list = array_map(trim(...), explode(',', $raw));

        foreach ($list as $item) {
            if ($item === '') {
                continue;
            }

            if (str_starts_with($item, '@')) {
                if (str_ends_with($email, $item)) {
                    return true;
                }
            } elseif ($email === $item) {
                return true;
            }
        }

        Log::info('許可リストによって送信がブロックされました: ' . $email);

        return false;
    }
}
