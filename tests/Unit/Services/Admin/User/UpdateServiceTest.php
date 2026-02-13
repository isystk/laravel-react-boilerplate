<?php

namespace Tests\Unit\Services\Admin\User;

use App\Dto\Request\Admin\User\UpdateDto;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\Admin\User\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class UpdateServiceTest extends BaseTest
{
    use RefreshDatabase;

    private UpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    public function test_update(): void
    {
        $user = $this->createDefaultUser([
            'name'  => 'aaa',
            'email' => 'aaa@test.com',
        ]);

        $request          = new UpdateRequest;
        $request['name']  = 'bbb';
        $request['email'] = 'bbb@test.com';
        $dto              = new UpdateDto($request);
        $this->service->update($user->id, $dto);

        $user->refresh();
        $this->assertEquals('bbb', $user->name, '名前が変更される事');
        $this->assertEquals('bbb@test.com', $user->email, 'メールアドレスが変更される事');
    }
}
