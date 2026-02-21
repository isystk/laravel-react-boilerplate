<?php

namespace Tests\Unit\Services\Common;

use App\Enums\OperationLogType;
use App\Services\Common\OperationLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class OperationLogServiceTest extends BaseTest
{
    private OperationLogService $service;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('log');

        $this->service = app(OperationLogService::class);

        Carbon::setTestNow(Carbon::parse('2026-02-21 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    // -------------------------------------------------------------------------
    // logUserAction
    // -------------------------------------------------------------------------

    public function test_logUserAction_ユーザーログファイルに記録されること(): void
    {
        $this->service->logUserAction(1, OperationLogType::UserLogin, 'ログイン', '127.0.0.1');

        Storage::disk('log')->assertExists('operation/user_20260221.log');

        $content = Storage::disk('log')->get('operation/user_20260221.log');
        $data    = json_decode(trim((string) $content), true);

        $this->assertSame('2026-02-21 12:00:00', $data['timestamp']);
        $this->assertSame(1, $data['user_id']);
        $this->assertSame('user_login', $data['action']);
        $this->assertSame('ログイン', $data['description']);
        $this->assertSame('127.0.0.1', $data['ip']);
    }

    public function test_logUserAction_複数行追記されること(): void
    {
        $this->service->logUserAction(1, OperationLogType::UserLogin, 'ログイン', '127.0.0.1');
        $this->service->logUserAction(2, OperationLogType::UserLogout, 'ログアウト', '192.168.0.1');

        $content = Storage::disk('log')->get('operation/user_20260221.log');
        $lines   = array_filter(explode("\n", (string) $content));

        $this->assertCount(2, $lines);
    }

    public function test_logUserAction_ipがnullの場合も記録されること(): void
    {
        $this->service->logUserAction(1, OperationLogType::UserLogin, 'ログイン', null);

        $content = Storage::disk('log')->get('operation/user_20260221.log');
        $data    = json_decode(trim((string) $content), true);

        $this->assertNull($data['ip']);
    }

    // -------------------------------------------------------------------------
    // logAdminAction
    // -------------------------------------------------------------------------

    public function test_logAdminAction_管理者ログファイルに記録されること(): void
    {
        $this->service->logAdminAction(1, OperationLogType::AdminUserUpdate, 'ユーザー情報を更新 (ユーザーID: 3)', '10.0.0.1');

        Storage::disk('log')->assertExists('operation/admin_20260221.log');

        $content = Storage::disk('log')->get('operation/admin_20260221.log');
        $data    = json_decode(trim((string) $content), true);

        $this->assertSame('2026-02-21 12:00:00', $data['timestamp']);
        $this->assertSame(1, $data['admin_id']);
        $this->assertSame('admin_user_update', $data['action']);
        $this->assertSame('ユーザー情報を更新 (ユーザーID: 3)', $data['description']);
        $this->assertSame('10.0.0.1', $data['ip']);
    }

    public function test_logAdminAction_ipがnullの場合も記録されること(): void
    {
        $this->service->logAdminAction(1, OperationLogType::AdminStaffCreate, 'スタッフ作成', null);

        $content = Storage::disk('log')->get('operation/admin_20260221.log');
        $data    = json_decode(trim((string) $content), true);

        $this->assertNull($data['ip']);
    }

    // -------------------------------------------------------------------------
    // getUserLogs
    // -------------------------------------------------------------------------

    public function test_getUserLogs_対象ユーザーのログのみ取得できること(): void
    {
        $this->service->logUserAction(1, OperationLogType::UserLogin, 'ログイン', '127.0.0.1');
        $this->service->logUserAction(2, OperationLogType::UserLogin, 'ログイン', '127.0.0.1');
        $this->service->logUserAction(1, OperationLogType::UserLogout, 'ログアウト', '127.0.0.1');

        $logs = $this->service->getUserLogs(1);

        $this->assertCount(2, $logs);
        foreach ($logs as $log) {
            $this->assertSame(1, $log['user_id']);
        }
    }

    public function test_getUserLogs_新しい順で返却されること(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-02-21 10:00:00'));
        $this->service->logUserAction(1, OperationLogType::UserLogin, '1回目', '127.0.0.1');

        Carbon::setTestNow(Carbon::parse('2026-02-21 12:00:00'));
        $this->service->logUserAction(1, OperationLogType::UserLogout, '2回目', '127.0.0.1');

        $logs = $this->service->getUserLogs(1);

        $this->assertSame('2026-02-21 12:00:00', $logs[0]['timestamp']);
        $this->assertSame('2026-02-21 10:00:00', $logs[1]['timestamp']);
    }

    public function test_getUserLogs_件数上限で切り詰められること(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->service->logUserAction(1, OperationLogType::UserLogin, 'ログイン', '127.0.0.1');
        }

        $logs = $this->service->getUserLogs(userId: 1, limit: 3);

        $this->assertCount(3, $logs);
    }

    public function test_getUserLogs_ログファイルが存在しない場合は空配列を返すこと(): void
    {
        $logs = $this->service->getUserLogs(999);

        $this->assertSame([], $logs);
    }

    public function test_getUserLogs_指定日数内のログのみ取得されること(): void
    {
        // 現在日(2026-02-21)のログ
        Carbon::setTestNow(Carbon::parse('2026-02-21 12:00:00'));
        $this->service->logUserAction(1, OperationLogType::UserLogin, '当日', '127.0.0.1');

        // 2日前のログを手動作成
        $entry = json_encode([
            'timestamp'   => '2026-02-19 12:00:00',
            'user_id'     => 1,
            'action'      => 'user_login',
            'description' => '2日前',
            'ip'          => '127.0.0.1',
        ], JSON_UNESCAPED_UNICODE);
        Storage::disk('log')->put('operation/user_20260219.log', $entry);

        // days=1 なら当日のみ取得
        $logs = $this->service->getUserLogs(userId: 1, days: 1);
        $this->assertCount(1, $logs);
        $this->assertSame('当日', $logs[0]['description']);

        // days=3 なら2日前も取得
        $logs = $this->service->getUserLogs(userId: 1, days: 3);
        $this->assertCount(2, $logs);
    }

    // -------------------------------------------------------------------------
    // getAdminLogs
    // -------------------------------------------------------------------------

    public function test_getAdminLogs_対象スタッフのログのみ取得できること(): void
    {
        $this->service->logAdminAction(1, OperationLogType::AdminUserUpdate, '更新', '127.0.0.1');
        $this->service->logAdminAction(2, OperationLogType::AdminUserUpdate, '更新', '127.0.0.1');
        $this->service->logAdminAction(1, OperationLogType::AdminStaffCreate, '作成', '127.0.0.1');

        $logs = $this->service->getAdminLogs(1);

        $this->assertCount(2, $logs);
        foreach ($logs as $log) {
            $this->assertSame(1, $log['admin_id']);
        }
    }

    public function test_getAdminLogs_新しい順で返却されること(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-02-21 09:00:00'));
        $this->service->logAdminAction(1, OperationLogType::AdminStaffCreate, '1回目', '127.0.0.1');

        Carbon::setTestNow(Carbon::parse('2026-02-21 11:00:00'));
        $this->service->logAdminAction(1, OperationLogType::AdminStaffUpdate, '2回目', '127.0.0.1');

        $logs = $this->service->getAdminLogs(1);

        $this->assertSame('2026-02-21 11:00:00', $logs[0]['timestamp']);
        $this->assertSame('2026-02-21 09:00:00', $logs[1]['timestamp']);
    }

    public function test_getAdminLogs_ログファイルが存在しない場合は空配列を返すこと(): void
    {
        $logs = $this->service->getAdminLogs(999);

        $this->assertSame([], $logs);
    }
}
