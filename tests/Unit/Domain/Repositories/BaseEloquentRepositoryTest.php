<?php

namespace Tests\Unit\Domain\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class BaseEloquentRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private BaseEloquentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new class extends BaseEloquentRepository {
            protected function model(): string
            {
                return User::class;
            }
        };
    }

    public function test_create_データが作成できること(): void
    {
        $data = [
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'password',
        ];

        $result = $this->repository->create($data);

        $this->assertInstanceOf(User::class, $result);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_update_正常に更新できること(): void
    {
        $user       = $this->createDefaultUser(['name' => 'Old Name']);
        $updateData = ['name' => 'New Name'];

        $result = $this->repository->update($updateData, $user->id);

        $this->assertSame('New Name', $result->name);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_update_対象が存在しない場合に例外を投げること(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('An unexpected error occurred.');

        $this->repository->update(['name' => 'New Name'], 9999);
    }

    public function test_delete_正常に削除できること(): void
    {
        $user = $this->createDefaultUser();

        $this->repository->delete($user->id);

        $user->refresh();
        $this->assertNotNull($user->deleted_at);
    }

    public function test_delete_対象が存在しない場合に例外を投げること(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('An unexpected error occurred.');

        $this->repository->delete(9999);
    }

    public function test_restore_正常に復元できること(): void
    {
        $user = $this->createDefaultUser();
        $user->delete();

        $this->repository->restore($user->id);

        $user->refresh();
        $this->assertNull($user->deleted_at);
    }

    public function test_getAll_全件取得できること(): void
    {
        User::factory()->count(3)->create();

        $results = $this->repository->getAll();

        $this->assertCount(3, $results);
    }

    public function test_findById_指定したIDのレコードが取得できること(): void
    {
        $user = $this->createDefaultUser();

        $result = $this->repository->findById($user->id);

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($user->id, $result->id);
    }

    public function test_findById_存在しない場合はnullを返すこと(): void
    {
        $result = $this->repository->findById(9999);

        $this->assertNull($result);
    }
}
