<?php

namespace Tests\Unit\Services\Admin\User;

use App\Domain\Entities\User;
use App\Services\Admin\User\UpdateService;
use App\Http\Requests\Admin\User\UpdateRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateServiceTest extends TestCase
{

    use RefreshDatabase;

    private UpdateService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    /**
     * updateのテスト
     */
    public function testUpdate(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);

        $request = new UpdateRequest();
        $request['name'] = 'bbb';
        $request['email'] = 'bbb@test.com';
        $this->service->update($user1->id, $request);

        // データが更新されたことをテスト
        $updatedUser = User::find($user1->id);
        $this->assertEquals('bbb', $updatedUser->name);
        $this->assertEquals('bbb@test.com', $updatedUser->email);
    }
}
