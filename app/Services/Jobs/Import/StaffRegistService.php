<?php

namespace App\Services\Jobs\Import;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Enums\AdminRole;
use App\Services\BaseService;
use Closure;
use Illuminate\Support\Facades\Hash;

class StaffRegistService extends BaseService
{
    public function __construct(private readonly AdminRepository $adminRepository) {}

    /**
     * 管理者を登録します。
     *
     * @param array<array<string, ?string>> $rows
     */
    public function exec(
        array $rows,
        Closure $outputLog
    ): void {
        foreach ($rows as $row) {
            $admin = $this->adminRepository->getByEmail($row['email']);
            $exist = $admin !== null;

            if ($exist) {
                $this->adminRepository->update([
                    'name' => $row['name'],
                    'role' => AdminRole::from($row['role']),
                ], $admin->id);
                $outputLog('Admin updated. id:[' . $admin->id . ']');
            } else {
                $newAdmin = $this->adminRepository->create([
                    'name'     => $row['name'],
                    'email'    => $row['email'],
                    'password' => Hash::make('password'),
                    'role'     => AdminRole::from($row['role']),
                ]);
                $outputLog('Admin registered. id:[' . $newAdmin->id . ']');
            }
        }
    }
}
