<?php

namespace Tests\Unit\Utils;

use App\Utils\NotificationUtil;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Tests\BaseTest;

class NotificationUtilTest extends BaseTest
{
    public function test_本番環境ではタイトルにプレフィックスが付与されないこと(): void
    {
        $this->app['env'] = 'production';

        $結果 = NotificationUtil::addEnvPrefix('お知らせ');

        $this->assertEquals('お知らせ', $結果);
    }

    public function test_非本番環境ではタイトルに環境名のプレフィックスが付与されること(): void
    {
        $this->app['env'] = 'local';
        Config::set('app.env', 'local');

        $結果 = NotificationUtil::addEnvPrefix('お知らせ');

        $this->assertEquals('【local】お知らせ', $結果);
    }

    public function test_本番環境では常に送信が許可されること(): void
    {
        $this->app['env'] = 'production';

        $this->assertTrue(NotificationUtil::isAllowed('any@example.com'));
    }

    public function test_許可リストが空の場合は送信がブロックされログが出力されること(): void
    {
        $this->app['env'] = 'local';
        Config::set('notification_guard.allowed_addresses', '');

        Log::shouldReceive('info')
            ->once()
            ->with('許可リストによって送信がブロックされました: test@example.com');

        $this->assertFalse(NotificationUtil::isAllowed('test@example.com'));
    }

    public function test_許可リストに完全一致するメールアドレスは送信が許可されること(): void
    {
        $this->app['env'] = 'local';
        Config::set('notification_guard.allowed_addresses', 'user@example.com, admin@test.com');

        $this->assertTrue(NotificationUtil::isAllowed('user@example.com'));
        $this->assertTrue(NotificationUtil::isAllowed('admin@test.com'));
        $this->assertFalse(NotificationUtil::isAllowed('other@example.com'));
    }

    public function test_ドメイン指定形式で送信が許可されること(): void
    {
        $this->app['env'] = 'local';
        Config::set('notification_guard.allowed_addresses', '@example.jp');

        $this->assertTrue(NotificationUtil::isAllowed('test@example.jp'));
        $this->assertTrue(NotificationUtil::isAllowed('user.name@example.jp'));
        $this->assertFalse(NotificationUtil::isAllowed('test@example.com'));
    }

    public function test_リストにスペースが含まれていても正しく判定されること(): void
    {
        $this->app['env'] = 'staging';
        Config::set('notification_guard.allowed_addresses', ' allow@test.com , @internal.co.jp ');

        $this->assertTrue(NotificationUtil::isAllowed('allow@test.com'));
        $this->assertTrue(NotificationUtil::isAllowed('manager@internal.co.jp'));
    }
}
