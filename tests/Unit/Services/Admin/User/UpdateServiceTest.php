<?php

namespace Tests\Unit\Services\Admin\User;

use App\Domain\Entities\User;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\Admin\User\UpdateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateServiceTest extends TestCase
{

    use RefreshDatabase;

    private UpdateService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UpdateService::class);
    }

    public function testUpdate(): void
    {
        $user = $this->createDefaultUser([
            'name' => 'aaa',
            'email' => 'aaa@test.com',
        ]);

        $request = new UpdateRequest();
        $request['name'] = 'bbb';
        $request['email'] = 'bbb@test.com';
        $this->service->update($user->id, $request);

        $user->refresh();
        $this->assertEquals('bbb', $user->name, '名前が変更される事');
        $this->assertEquals('bbb@test.com', $user->email, 'メールアドレスが変更される事');
    }
}
