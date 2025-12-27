<?php

namespace Domain\Repositories\Admin;

use App\Domain\Entities\Admin;
use App\Domain\Repositories\Admin\AdminRepository;
use App\Enums\AdminRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class AdminRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private AdminRepository $repository;

    /**
     * @var array<string, mixed>
     */
    private array $defaultConditions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(AdminRepository::class);
        $this->defaultConditions = [
            'name' => null,
            'email' => null,
            'role' => null,
            'sort_name' => null,
            'sort_direction' => null,
            'limit' => null,
        ];
    }

    public function test_getByConditions(): void
    {
        $admins = $this->repository->getByConditions($this->defaultConditions);
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
            ...$this->defaultConditions,
            'name' => 'admin1',
        ])->first();
        $this->assertSame($expectAdmin1->id, $admin->id, 'nameで検索が出来ることをテスト');

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'email' => 'admin2@test.com',
        ])->first();
        $this->assertSame($expectAdmin2->id, $admin->id, 'emailで検索が出来ることをテスト');

        /** @var Admin $admin */
        $admin = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'role' => AdminRole::HighManager->value,
        ])->first();
        $this->assertSame($expectAdmin1->id, $admin->id, 'roleで検索が出来ることをテスト');

        $admins = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $admins->count(), 'limitで取得件数が指定出来ることをテスト');
    }

    public function test_getByConditions_ソートと複数条件の組み合わせ(): void
    {
        // テストデータの準備
        $this->createDefaultAdmin(['name' => 'B-admin', 'email' => 'b@test.com', 'role' => AdminRole::Manager->value]);
        $this->createDefaultAdmin(['name' => 'A-admin', 'email' => 'a@test.com', 'role' => AdminRole::Manager->value]);
        $this->createDefaultAdmin(['name' => 'C-admin', 'email' => 'c@test.com', 'role' => AdminRole::HighManager->value]);

        // 1. ソート（名前の昇順）のテスト
        $results = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'sort_name' => 'name',
            'sort_direction' => 'asc',
        ]);
        $this->assertSame('A-admin', $results->values()[0]->name);
        $this->assertSame('B-admin', $results->values()[1]->name);

        // 2. ソート（名前の降順）のテスト
        $results = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'sort_name' => 'name',
            'sort_direction' => 'desc',
        ]);
        $this->assertSame('C-admin', $results->values()[0]->name);

        // 3. 複数条件（role かつ nameの一部一致）のテスト
        $results = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'role' => AdminRole::Manager->value,
            'name' => 'A-',
        ]);
        $this->assertCount(1, $results);
        $this->assertSame('A-admin', $results->first()->name);
    }

    public function test_getByConditions_ソート方向未指定時にデフォルトascで動作すること(): void
    {
        $this->createDefaultAdmin(['name' => 'Z-admin']);
        $this->createDefaultAdmin(['name' => 'A-admin']);

        $results = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'sort_name' => 'name',
            'sort_direction' => null, // sort_directionをあえて渡さない
        ]);

        $this->assertSame('A-admin', $results->first()->name, '未指定時はascでソートされること');
    }

    public function test_getByEmail(): void
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

    public function test_getAllOrderById(): void
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
