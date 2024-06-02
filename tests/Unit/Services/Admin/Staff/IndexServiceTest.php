<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Stock;
use App\Domain\Entities\User;
use App\Enums\AdminRole;
use App\Services\Admin\Staff\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexServiceTest extends TestCase
{

    use RefreshDatabase;

    private IndexService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(IndexService::class, $this->service);
    }

    /**
     * searchStaffのテスト
     */
    public function testSearchStaff(): void
    {
        $default = [
            'name' => null,
            'email' => null,
            'role' => null,
            'sort_name' => 'updated_at',
            'sort_direction' => 'asc',
            'limit' => 20,
        ];

        $admins = $this->service->searchStaff($default);
        $this->assertCount(0, $admins->items(), '引数がない状態でエラーにならないことを始めにテスト');

        /** @var Admin $admin1 */
        $admin1 = Admin::factory([
            'name' => 'user1',
            'email' => 'user1@test.com',
            'role' => AdminRole::HighManager->value
        ])->create();
        /** @var Admin $admin2 */
        $admin2 = Admin::factory([
            'name' => 'user2',
            'email' => 'user2@test.com',
            'role' => AdminRole::Manager->value
        ])->create();

        $input = $default;
        $input['name'] = 'user2';
        /** @var Admin $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id], $adminIds, 'nameで検索が出来ることをテスト');

        $input['email'] = 'user2@test.com';
        /** @var Admin $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id], $adminIds, 'emailで検索が出来ることをテスト');

        $input['role'] = AdminRole::Manager->value;
        /** @var Admin $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id], $adminIds, 'roleで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var Admin $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id, $admin1->id], $adminIds, 'ソート指定で検索が出来ることをテスト');

    }
}
