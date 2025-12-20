<?php

namespace Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Enums\AdminRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private AdminRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(AdminRepository::class);
    }

    /**
     * getByConditionsのテスト
     */
    public function test_get_by_conditions(): void
    {
        $defaultConditions = [
            'name' => null,
            'email' => null,
            'role' => null,
            'sort_name' => null,
            'sort_direction' => null,
            'limit' => null,
        ];

        $admins = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $admins->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectAdmin1 = $this->createDefaultAdmin([
            'name' => 'admin1',
            'email' => 'admin1@test.com',
            'role' => AdminRole::HighManager->value,
        ]);
        $expectAdmin2 = $this->createDefaultAdmin([
            'name' => 'admin2',
            'email' => 'admin2@test.com',
            'role' => AdminRole::Manager->value,
        ]);

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$defaultConditions,
            'name' => 'admin1',
        ])->first();
        $this->assertSame($expectAdmin1->id, $admin->id, 'nameで検索が出来ることをテスト');

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$defaultConditions,
            'email' => 'admin2@test.com',
        ])->first();
        $this->assertSame($expectAdmin2->id, $admin->id, 'emailで検索が出来ることをテスト');

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$defaultConditions,
            'role' => AdminRole::HighManager->value,
        ])->first();
        $this->assertSame($expectAdmin1->id, $admin->id, 'roleで検索が出来ることをテスト');

        $admins = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $admins->count(), 'limitで取得件数が指定出来ることをテスト');
    }

    /**
     * getByEmailのテスト
     */
    public function test_get_by_email(): void
    {
        $email = 'admin2@test.com';

        $result = $this->repository->getByEmail($email);
        $this->assertNull($result, 'データがない状態で正常に動作することを始めにテスト');

        $this->createDefaultAdmin([
            'email' => 'admin1@test.com',
        ]);
        $expectAdmin2 = $this->createDefaultAdmin([
            'email' => 'admin2@test.com',
        ]);

        $result = $this->repository->getByEmail($email);
        $this->assertSame($expectAdmin2->id, $result->id, '指定したメールアドレスのスタッフが取得できることのテスト');
    }

    /**
     * getAllOrderByIdのテスト
     */
    public function test_get_all_order_by_id(): void
    {
        $admins = $this->repository->getAllOrderById();
        $this->assertSame(0, $admins->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectAdmin1 = $this->createDefaultAdmin();
        $expectAdmin2 = $this->createDefaultAdmin();

        $admins = $this->repository->getAllOrderById();
        $expect = [$expectAdmin1->id, $expectAdmin2->id];
        $actual = $admins->pluck('id')->all();
        $this->assertSame($expect, $actual, 'すべてのスタッフがIDの昇順で取得できることのテスト');
    }
}
