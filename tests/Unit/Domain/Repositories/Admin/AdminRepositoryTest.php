<?php

namespace Tests\Unit\Domain\Repositories\Admin;

use App\Domain\Repositories\Admin\AdminRepository;
use App\Domain\Entities\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRepositoryTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
    use RefreshDatabase;

    private AdminRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(AdminRepository::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(AdminRepository::class, $this->repository);
    }

    /**
     * getByConditionsのテスト
     */
    public function testGetByConditions(): void
    {
        $defaultConditions = [
            'name' => null,
            'email' => null,
            'sort_name' => null,
            'sort_direction' => null,
            'limit' => null,
        ];

        $admins = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $admins->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var Admin $expectAdmin1 */
        $expectAdmin1 = Admin::factory(['name' => 'admin1', 'email' => 'admin1@test.com'])->create();
        /** @var Admin $expectAdmin2 */
        $expectAdmin2 = Admin::factory(['name' => 'admin2', 'email' => 'admin2@test.com'])->create();

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$defaultConditions,
            'name' => 'admin1'
        ])->first();
        $this->assertSame($expectAdmin1->id, $admin->id, 'nameで検索が出来ることをテスト');

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$defaultConditions,
            'email' => 'admin2@test.com'
        ])->first();
        $this->assertSame($expectAdmin2->id, $admin->id, 'emailで検索が出来ることをテスト');

        $admins = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1
        ]);
        $this->assertSame(1, $admins->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
