<?php

namespace Tests\Unit\Domain\Repositories\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\Contact\ContactRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private ContactRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ContactRepository::class);
    }

    public function test_getByConditions(): void
    {
        $defaultConditions = [
            'user_name'      => null,
            'title'          => null,
            'sort_name'      => null,
            'sort_direction' => null,
            'limit'          => null,
        ];

        $stocks = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $stocks->count(), 'データがない状態で正常に動作することを始めにテスト');

        $expectContact1 = $this->createDefaultContact(['user_name' => 'user1', 'title' => 'title1']);
        $expectContact2 = $this->createDefaultContact(['user_name' => 'user2', 'title' => 'title2']);

        /** @var Contact $contact */
        $contact = $this->repository->getByConditions([
            ...$defaultConditions,
            'user_name' => 'user1',
        ])->first();
        $this->assertSame($expectContact1->id, $contact->id, 'user_nameで検索が出来ることをテスト');

        /** @var Contact $contact */
        $contact = $this->repository->getByConditions([
            ...$defaultConditions,
            'title' => 'title2',
        ])->first();
        $this->assertSame($expectContact2->id, $contact->id, 'titleで検索が出来ることをテスト');

        $contacts = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $contacts->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
