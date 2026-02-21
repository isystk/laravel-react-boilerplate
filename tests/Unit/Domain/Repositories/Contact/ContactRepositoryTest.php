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

    /** @var array<string, mixed> */
    private array $defaultConditions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository        = app(ContactRepositoryInterface::class);
        $this->defaultConditions = [
            'keyword'           => null,
            'title'             => null,
            'contact_date_from' => null,
            'contact_date_to'   => null,
            'only_unreplied'    => false,
            'sort_name'         => null,
            'sort_direction'    => null,
            'limit'             => null,
        ];
    }

    public function test_getByConditions_データがない場合0件を返すこと(): void
    {
        $contacts = $this->repository->getByConditions($this->defaultConditions);
        $this->assertSame(0, $contacts->count());
    }

    public function test_getByConditions_keywordで名前検索ができること(): void
    {
        $user1         = $this->createDefaultUser(['name' => 'yamada taro']);
        $user2         = $this->createDefaultUser(['name' => 'suzuki hanako']);
        $expectContact = $this->createDefaultContact(['user_id' => $user1->id]);
        $this->createDefaultContact(['user_id' => $user2->id]);

        /** @var Contact $contact */
        $contact = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'keyword' => 'yamada',
        ])->first();

        $this->assertSame($expectContact->id, $contact->id);
    }

    public function test_getByConditions_keywordでメールアドレス検索ができること(): void
    {
        $user1         = $this->createDefaultUser(['email' => 'target@example.com']);
        $user2         = $this->createDefaultUser(['email' => 'other@example.com']);
        $expectContact = $this->createDefaultContact(['user_id' => $user1->id]);
        $this->createDefaultContact(['user_id' => $user2->id]);

        /** @var Contact $contact */
        $contact = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'keyword' => 'target@example.com',
        ])->first();

        $this->assertSame($expectContact->id, $contact->id);
    }

    public function test_getByConditions_keywordでIDが数値の場合ID検索ができること(): void
    {
        $user1         = $this->createDefaultUser();
        $user2         = $this->createDefaultUser();
        $expectContact = $this->createDefaultContact(['user_id' => $user1->id]);
        $this->createDefaultContact(['user_id' => $user2->id]);

        /** @var Contact $contact */
        $contact = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'keyword' => (string) $expectContact->id,
        ])->first();

        $this->assertSame($expectContact->id, $contact->id);
    }

    public function test_getByConditions_titleで検索ができること(): void
    {
        $user1 = $this->createDefaultUser();
        $user2 = $this->createDefaultUser();
        $this->createDefaultContact(['user_id' => $user1->id, 'title' => 'title1']);
        $expectContact = $this->createDefaultContact(['user_id' => $user2->id, 'title' => 'title2']);

        /** @var Contact $contact */
        $contact = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'title' => 'title2',
        ])->first();

        $this->assertSame($expectContact->id, $contact->id);
    }

    public function test_getByConditions_contact_dateで検索ができること(): void
    {
        $user1 = $this->createDefaultUser();
        $user2 = $this->createDefaultUser();
        $this->createDefaultContact(['user_id' => $user1->id, 'created_at' => '2024-01-01']);
        $expectContact = $this->createDefaultContact(['user_id' => $user2->id, 'created_at' => '2024-06-01']);

        $contacts = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'contact_date_from' => DateUtil::toCarbon('2024-06-01 00:00:00'),
            'contact_date_to'   => DateUtil::toCarbon('2024-06-01 23:59:59'),
        ]);
        $contactIds = $contacts->pluck('id')->all();

        $this->assertSame([$expectContact->id], $contactIds);
    }

    public function test_getByConditions_only_unrepliedがtrueの場合未返信のみ取得できること(): void
    {
        $user1     = $this->createDefaultUser();
        $user2     = $this->createDefaultUser();
        $unreplied = $this->createDefaultContact(['user_id' => $user1->id]);
        $replied   = $this->createDefaultContact(['user_id' => $user2->id]);
        $this->createDefaultContactReply(['contact_id' => $replied->id]);

        $contacts = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'only_unreplied' => true,
        ]);
        $contactIds = $contacts->pluck('id')->all();

        $this->assertContains($unreplied->id, $contactIds);
        $this->assertNotContains($replied->id, $contactIds);
    }

    public function test_getByConditions_only_unrepliedがfalseの場合返信済みも含めて取得できること(): void
    {
        $user1     = $this->createDefaultUser();
        $user2     = $this->createDefaultUser();
        $unreplied = $this->createDefaultContact(['user_id' => $user1->id]);
        $replied   = $this->createDefaultContact(['user_id' => $user2->id]);
        $this->createDefaultContactReply(['contact_id' => $replied->id]);

        $contacts = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'only_unreplied' => false,
        ]);
        $contactIds = $contacts->pluck('id')->all();

        $this->assertContains($unreplied->id, $contactIds);
        $this->assertContains($replied->id, $contactIds);
    }

    public function test_getByConditions_limitで取得件数が指定できること(): void
    {
        $user = $this->createDefaultUser();
        $this->createDefaultContact(['user_id' => $user->id]);
        $this->createDefaultContact(['user_id' => $user->id]);

        $contacts = $this->repository->getByConditions([
            ...$this->defaultConditions,
            'limit' => 1,
        ]);

        $this->assertSame(1, $contacts->count());
    }

    public function test_countUnreplied_未返信の件数が正しく取得できること(): void
    {
        $unrepliedCount = 3;

        // 未返信のデータを作成
        $this->createDefaultContact();
        $this->createDefaultContact();
        $this->createDefaultContact();

        // 返信済みのデータを作成
        $repliedItem = $this->createDefaultContact();
        $this->createDefaultContactReply([
            'contact_id' => $repliedItem->id,
            'body'       => 'This is a reply.',
        ]);

        $result = $this->repository->countUnreplied();

        $this->assertSame($unrepliedCount, $result);
    }

    public function test_countUnreplied_全データが返信済みの場合は0を返すこと(): void
    {
        $repliedItem = $this->createDefaultContact();
        $this->createDefaultContactReply([
            'contact_id' => $repliedItem->id,
            'body'       => 'This is a reply.',
        ]);

        $result = $this->repository->countUnreplied();

        $this->assertSame(0, $result);
    }
}
