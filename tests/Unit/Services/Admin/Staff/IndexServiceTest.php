<?php

namespace Tests\Unit\Services\Admin\Staff;

use App\Domain\Entities\Admin;
use App\Enums\AdminRole;
use App\Services\Admin\Staff\IndexService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\BaseTest;

class IndexServiceTest extends BaseTest
{
    use RefreshDatabase;

    private IndexService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(IndexService::class);
    }

    public function test_search_staff(): void
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
        $this->assertCount(0, $admins->items(), 'データがない状態でエラーにならないことを始めにテスト');

        $admin1 = $this->createDefaultAdmin([
            'name' => 'user1',
            'email' => 'user1@test.com',
            'role' => AdminRole::HighManager->value,
        ]);
        $admin2 = $this->createDefaultAdmin([
            'name' => 'user2',
            'email' => 'user2@test.com',
            'role' => AdminRole::Manager->value,
        ]);

        $input = $default;
        $input['name'] = 'user2';
        /** @var LengthAwarePaginator<int, Admin> $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id], $adminIds, 'nameで検索が出来ることをテスト');

        $input['email'] = 'user2@test.com';
        /** @var LengthAwarePaginator<int, Admin> $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id], $adminIds, 'emailで検索が出来ることをテスト');

        $input['role'] = AdminRole::Manager->value;
        /** @var LengthAwarePaginator<int, Admin> $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id], $adminIds, 'roleで検索が出来ることをテスト');

        $input = $default;
        $input['sort_name'] = 'id';
        $input['sort_direction'] = 'desc';
        /** @var LengthAwarePaginator<int, Admin> $admins */
        $admins = $this->service->searchStaff($input);
        $adminIds = $admins->pluck('id')->all();
        $this->assertSame([$admin2->id, $admin1->id], $adminIds, 'ソート指定で検索が出来ることをテスト');
    }
}
