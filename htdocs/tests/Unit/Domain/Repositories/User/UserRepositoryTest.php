<?php

namespace Tests\Unit\Domain\Repositories\User;

use App\Domain\Repositories\User\UserRepository;
use App\Domain\Entities\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /**
     * 各テストの実行後にテーブルを空にする。
     */
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
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(UserRepository::class, $this->repository);
    }

    /**
     * getByConditionsのテスト
     */
    public function testGetByConditions(): void
    {
        $expectUser1 = User::factory(['name' => 'user1', 'email' => 'user1@test.com'])->create();
        $expectUser2 = User::factory(['name' => 'user2', 'email' => 'user2@test.com'])->create();

        $defaultConditions = [
            'name' => null,
            'email' => null,
            'limit' => null,
        ];

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'name' => 'user1'
        ]);
        $this->assertSame($expectUser1->id, $users->first()->id, 'nameで検索が出来ることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'email' => 'user2@test.com'
        ]);
        $this->assertSame($expectUser2->id, $users->first()->id, 'emailで検索が出来ることをテスト');

        $users = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1
        ]);
        $this->assertSame(1, $users->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
