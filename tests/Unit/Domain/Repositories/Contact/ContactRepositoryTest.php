<?php

namespace Tests\Unit\Domain\Repositories\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\Contact\ContactRepositoryInterface;
use App\Utils\DateUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private ContactRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ContactRepositoryInterface::class);
    }

    public function test_getByConditions(): void
    {
        $defaultConditions = [
            'user_name'         => null,
            'title'             => null,
            'contact_date_from' => null,
            'contact_date_to'   => null,
            'sort_name'         => null,
            'sort_direction'    => null,
            'limit'             => null,
        ];

        $stocks = $this->repository->getByConditions($defaultConditions);
        $this->assertSame(0, $stocks->count(), 'データがない状態で正常に動作することを始めにテスト');

        $user1          = $this->createDefaultUser(['name' => 'user1']);
        $user2          = $this->createDefaultUser(['name' => 'user2']);
        $expectContact1 = $this->createDefaultContact(['user_id' => $user1->id, 'title' => 'title1', 'created_at' => '2024-01-01']);
        $expectContact2 = $this->createDefaultContact(['user_id' => $user2->id, 'title' => 'title2', 'created_at' => '2024-06-01']);

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
            'contact_date_from' => DateUtil::toCarbon('2024-06-01 00:00:00'),
            'contact_date_to'   => DateUtil::toCarbon('2024-06-01 23:59:59'),
        ]);
        $contactIds = $contacts->pluck('id')->all();
        $this->assertSame([$expectContact2->id], $contactIds, 'contact_dateで検索が出来ることをテスト');

        $contacts = $this->repository->getByConditions([
            ...$defaultConditions,
            'limit' => 1,
        ]);
        $this->assertSame(1, $contacts->count(), 'limitで取得件数が指定出来ることをテスト');
    }
}
