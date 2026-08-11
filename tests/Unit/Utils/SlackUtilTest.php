<?php

namespace Tests\Unit\Utils;

use App\Utils\SlackUtil;
use Exception;
use Illuminate\Support\Facades\Http;
use Tests\BaseTest;

class SlackUtilTest extends BaseTest
{
    private const WEBHOOK_URL = 'https://hooks.slack.com/services/test/webhook';

    public function test_send_webhookUrlが空の場合はHTTPリクエストを送信しないこと(): void
    {
        Http::fake();

        SlackUtil::send('', 'テストメッセージ');

        Http::assertNothingSent();
    }

    public function test_send_webhookUrlが設定されている場合はPOSTリクエストを送信すること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);

        SlackUtil::send(self::WEBHOOK_URL, 'テストメッセージ');

        Http::assertSent(fn ($request) => $request->url() === self::WEBHOOK_URL
            && $request->method() === 'POST');
    }

    public function test_send_送信ボディにtextが含まれること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);

        SlackUtil::send(self::WEBHOOK_URL, 'テストメッセージ');

        Http::assertSent(fn ($request) => $request->data()['text'] === 'テストメッセージ');
    }

    public function test_sendBlocks_webhookUrlが空の場合はHTTPリクエストを送信しないこと(): void
    {
        Http::fake();

        SlackUtil::sendBlocks('', [['type' => 'section']]);

        Http::assertNothingSent();
    }

    public function test_sendBlocks_webhookUrlが設定されている場合はPOSTリクエストを送信すること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);

        SlackUtil::sendBlocks(self::WEBHOOK_URL, [['type' => 'section']]);

        Http::assertSent(fn ($request) => $request->url() === self::WEBHOOK_URL
            && $request->method() === 'POST');
    }

    public function test_sendBlocks_送信ボディにblocksが含まれること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        $blocks = [['type' => 'section', 'text' => ['type' => 'mrkdwn', 'text' => 'テスト']]];

        SlackUtil::sendBlocks(self::WEBHOOK_URL, $blocks);

        Http::assertSent(fn ($request) => $request->data()['blocks'] === $blocks);
    }

    public function test_sendBlocks_送信ボディでunfurl_linksとunfurl_mediaが無効化されていること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);

        SlackUtil::sendBlocks(self::WEBHOOK_URL, [['type' => 'section']]);

        Http::assertSent(fn ($request) => $request->data()['unfurl_links'] === false
            && $request->data()['unfurl_media'] === false);
    }

    public function test_sendBlocks_fallbackTextが指定されている場合は送信ボディにtextが含まれること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);

        SlackUtil::sendBlocks(self::WEBHOOK_URL, [['type' => 'section']], 'フォールバックテキスト');

        Http::assertSent(fn ($request) => $request->data()['text'] === 'フォールバックテキスト');
    }

    public function test_sendBlocks_fallbackTextが未指定の場合は送信ボディにtextが含まれないこと(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);

        SlackUtil::sendBlocks(self::WEBHOOK_URL, [['type' => 'section']]);

        Http::assertSent(fn ($request) => !array_key_exists('text', $request->data()));
    }

    public function test_notifySystemError_webhookUrlが未設定の場合はHTTPリクエストを送信しないこと(): void
    {
        Http::fake();
        config(['services.slack.webhook_url' => '']);

        SlackUtil::notifySystemError(new Exception('テストエラー'));

        Http::assertNothingSent();
    }

    public function test_notifySystemError_webhookUrlが設定されている場合はPOSTリクエストを送信すること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        config(['services.slack.webhook_url' => self::WEBHOOK_URL]);

        SlackUtil::notifySystemError(new Exception('テストエラー'));

        Http::assertSent(fn ($request) => $request->url() === self::WEBHOOK_URL
            && $request->method() === 'POST');
    }

    public function test_notifySystemError_送信ボディにエラークラス名が含まれること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        config(['services.slack.webhook_url' => self::WEBHOOK_URL]);

        SlackUtil::notifySystemError(new Exception('テストエラー'));

        Http::assertSent(fn ($request) => str_contains($request->data()['text'], 'Exception'));
    }

    public function test_notifySystemError_送信ボディにエラーメッセージが含まれること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        config(['services.slack.webhook_url' => self::WEBHOOK_URL]);

        SlackUtil::notifySystemError(new Exception('テストエラー'));

        Http::assertSent(fn ($request) => str_contains($request->data()['text'], 'テストエラー'));
    }

    public function test_notifySystemError_送信ボディにファイル名と行番号が含まれること(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        config(['services.slack.webhook_url' => self::WEBHOOK_URL]);

        $exception = new Exception('テストエラー');
        SlackUtil::notifySystemError($exception);

        Http::assertSent(function ($request) use ($exception) {
            $text = $request->data()['text'];

            return str_contains($text, $exception->getFile())
                && str_contains($text, (string) $exception->getLine());
        });
    }
}
