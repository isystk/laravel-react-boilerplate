<?php

namespace Tests\Unit\Domain\Repositories\User;

use App\Domain\Repositories\User\UserRepository;
use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{

    use RefreshDatabase;

    private UserRepository $repository;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(UserRepository::class);
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

        $users = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $users->count(), 'データがない状態で正常に動作することを始めにテスト');

        /** @var User $expectUser1 */
        $expectUser1 = User::factory(['name' => 'user1', 'email' => 'user1@test.com'])->create();
        /** @var User $expectUser2 */
        $expectUser2 = User::factory(['name' => 'user2', 'email' => 'user2@test.com'])->create();

        /** @var User $user */
        $user = $this->repository->getByConditions([
            ...$defaultConditions,
            'name' => 'user1'
        ])->first();
        $this->assertSame($expectUser1->id, $user->id, 'nameで検索が出来ることをテスト');

        /** @var User $user */
        $user = $this->repository->getByConditions([
            ...$defaultConditions,
            'email' => 'user2@test.com'
        ])->first();
        $this->assertSame($expectUser2->id, $user->id, 'emailで検索が出来ることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1
        ]);
        $this->assertSame(1, $users->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
