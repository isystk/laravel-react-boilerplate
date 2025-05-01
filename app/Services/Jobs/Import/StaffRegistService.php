<?php

namespace App\Services\Jobs\Import;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Enums\AdminRole;
use App\Services\BaseService;
use Closure;
use Illuminate\Support\Facades\Hash;

class StaffRegistService extends BaseService
{
    private AdminRepository $adminRepository;

    /**
     * Create a new controller instance.
     *
     * @param AdminRepository $adminRepository
     */
    public function __construct(
        AdminRepository $adminRepository
    ) {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 管理者を登録します。
     * @param array<array<string, ?string>> $rows
     * @param Closure $outputLog
     */
    public function exec(
        array $rows,
        Closure $outputLog
    ): void {
        foreach ($rows as $row) {
            $admin = $this->adminRepository->getByEmail($row['email']);
            $exist = null !== $admin;

            if ($exist) {
                $this->adminRepository->update($admin->id, [
                    'name' => $row['name'],
                    'role' => AdminRole::get($row['role'])?->value,
                ]);
                $outputLog('Admin updated. id:[' . $admin->id . ']');
            } else {
                $newAdmin = $this->adminRepository->create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => Hash::make('password'),
                    'role' => AdminRole::get($row['role'])?->value,
                ]);
                $outputLog('Admin registered. id:[' . $newAdmin->id . ']');
            }
        }
    }

}
