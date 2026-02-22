<?php

namespace App\Services\Common;

use App\Enums\OperationLogType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class OperationLogService
{
    private const DISK = 'log';

    private const LOG_DIR = 'operation';

    /**
     * フロントユーザーの操作をログに記録する
     */
    public function logUserAction(int $userId, OperationLogType $action, string $description, ?string $ip): void
    {
        $entry = json_encode([
            'timestamp'   => Carbon::now()->format('Y-m-d H:i:s'),
            'user_id'     => $userId,
            'action'      => $action->value,
            'description' => $description,
            'ip'          => $ip,
        ], JSON_UNESCAPED_UNICODE);

        Storage::disk(self::DISK)->append(
            self::LOG_DIR . '/user_' . Carbon::now()->format('Ymd') . '.log',
            $entry
        );
    }

    /**
     * 管理画面スタッフの操作をログに記録する
     */
    public function logAdminAction(int $adminId, OperationLogType $action, string $description, ?string $ip): void
    {
        $entry = json_encode([
            'timestamp'   => Carbon::now()->format('Y-m-d H:i:s'),
            'admin_id'    => $adminId,
            'action'      => $action->value,
            'description' => $description,
            'ip'          => $ip,
        ], JSON_UNESCAPED_UNICODE);

        Storage::disk(self::DISK)->append(
            self::LOG_DIR . '/admin_' . Carbon::now()->format('Ymd') . '.log',
            $entry
        );
    }

    /**
     * 指定ユーザーの操作ログを取得する
     *
     * @return array<int, array<string, mixed>>
     */
    public function getUserLogs(int $userId, int $days = 30, int $limit = 50): array
    {
        return $this->readLogs('user', $days, 'user_id', $userId, $limit);
    }

    /**
     * 指定スタッフの操作ログを取得する
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAdminLogs(int $adminId, int $days = 30, int $limit = 50): array
    {
        return $this->readLogs('admin', $days, 'admin_id', $adminId, $limit);
    }

    /**
     * 対象IDに一致するログを読み込み、降順で返す
     *
     * @return array<int, array<string, mixed>>
     */
    private function readLogs(string $prefix, int $days, string $idKey, int $idValue, int $limit): array
    {
        $logs = [];

        for ($i = 0; $i < $days; $i++) {
            $path = self::LOG_DIR . '/' . $prefix . '_' . Carbon::now()->subDays($i)->format('Ymd') . '.log';

            if (!Storage::disk(self::DISK)->exists($path)) {
                continue;
            }

            $content = Storage::disk(self::DISK)->get($path);
            $lines   = array_filter(explode("\n", $content ?? ''));

            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if (!is_array($data)) {
                    continue;
                }
                if (isset($data[$idKey]) && $data[$idKey] === $idValue) {
                    $logs[] = $data;
                }
            }
        }

        // タイムスタンプ降順でソート
        usort($logs, static fn (array $a, array $b): int => strcmp((string) $b['timestamp'], (string) $a['timestamp']));

        return array_slice($logs, 0, $limit);
    }
}
