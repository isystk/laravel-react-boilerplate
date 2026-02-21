<?php

namespace App\Services\Common;

use App\Enums\OperationLogType;
use Carbon\Carbon;
use RuntimeException;

class OperationLogService
{
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

        $this->writeLog('user_' . Carbon::now()->format('Ymd') . '.log', $entry);
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

        $this->writeLog('admin_' . Carbon::now()->format('Ymd') . '.log', $entry);
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
     * ログファイルに1行書き込む
     */
    private function writeLog(string $filename, string $entry): void
    {
        $dir = storage_path('logs/' . self::LOG_DIR . '/');

        if (!file_exists($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }

        file_put_contents($dir . $filename, $entry . "\n", FILE_APPEND | LOCK_EX);
    }

    /**
     * 対象IDに一致するログを読み込み、降順で返す
     *
     * @return array<int, array<string, mixed>>
     */
    private function readLogs(string $prefix, int $days, string $idKey, int $idValue, int $limit): array
    {
        $dir  = storage_path('logs/' . self::LOG_DIR . '/');
        $logs = [];

        for ($i = 0; $i < $days; $i++) {
            $filename = $dir . $prefix . '_' . Carbon::now()->subDays($i)->format('Ymd') . '.log';

            if (!file_exists($filename)) {
                continue;
            }

            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines === false) {
                continue;
            }

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
        usort($logs, static fn (array $a, array $b): int => strcmp($b['timestamp'], $a['timestamp']));

        return array_slice($logs, 0, $limit);
    }
}
