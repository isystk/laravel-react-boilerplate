<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;
use Throwable;

class SlackUtil
{
    /**
     * Slack Webhook経由でシンプルなテキストメッセージを送信する
     */
    public static function send(string $webhookUrl, string $message): void
    {
        if (empty($webhookUrl)) {
            return;
        }

        Http::post($webhookUrl, ['text' => $message]);
    }

    /**
     * Slack Webhook経由でBlock Kit形式のメッセージを送信する
     *
     * @param array<int, array<string, mixed>> $blocks
     */
    public static function sendBlocks(string $webhookUrl, array $blocks, string $fallbackText = ''): void
    {
        if (empty($webhookUrl)) {
            return;
        }

        $payload = [
            'blocks' => $blocks,
            // メッセージ内リンクをSlackが自動プレビュー取得しないようにする防御策（誤プレビュー防止）
            'unfurl_links' => false,
            'unfurl_media' => false,
        ];

        if ($fallbackText !== '') {
            $payload['text'] = $fallbackText;
        }

        Http::post($webhookUrl, $payload);
    }

    /**
     * システムエラーの内容を整形してSlackへ通知する
     */
    public static function notifySystemError(Throwable $e): void
    {
        $webhookUrl = config('services.slack.webhook_url', '');

        if (empty($webhookUrl)) {
            return;
        }

        $message = implode("\n", [
            '[SystemError] ' . $e::class,
            'Message: ' . $e->getMessage(),
            'File: ' . $e->getFile() . ':' . $e->getLine(),
        ]);

        self::send($webhookUrl, $message);
    }
}
