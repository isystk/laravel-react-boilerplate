<?php

namespace Tests\Unit\Domain\Repositories\ContactReply;

use App\Domain\Entities\ContactReply;
use App\Domain\Repositories\ContactReply\ContactReplyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class ContactReplyRepositoryTest extends BaseTest
{
    use RefreshDatabase;

    private ContactReplyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = app(ContactReplyRepository::class);
    }

    public function test_getByContactId(): void
    {
        $contact1 = $this->createDefaultContact();
        $contact2 = $this->createDefaultContact();

        $replies = $this->repository->getByContactId($contact1->id);
        $this->assertSame(0, $replies->count(), 'データがない状態で正常に動作することを始めにテスト');

        $reply1 = $this->createDefaultContactReply(['contact_id' => $contact1->id, 'body' => '返信1']);
        $reply2 = $this->createDefaultContactReply(['contact_id' => $contact1->id, 'body' => '返信2']);
        $this->createDefaultContactReply(['contact_id' => $contact2->id, 'body' => '別お問い合わせの返信']);

        $replies = $this->repository->getByContactId($contact1->id);
        $this->assertSame(2, $replies->count(), 'contact_idで絞り込みが出来ることをテスト');

        $replyIds = $replies->pluck('id')->toArray();
        $this->assertContains($reply1->id, $replyIds);
        $this->assertContains($reply2->id, $replyIds);
    }

    public function test_getByContactId_adminリレーションがeagerロードされる事(): void
    {
        $admin   = $this->createDefaultAdmin(['name' => '管理者A']);
        $contact = $this->createDefaultContact();
        $this->createDefaultContactReply([
            'contact_id' => $contact->id,
            'admin_id'   => $admin->id,
        ]);

        $replies = $this->repository->getByContactId($contact->id);

        /** @var ContactReply $reply */
        $reply = $replies->first();
        $this->assertTrue($reply->relationLoaded('admin'));
        $this->assertSame($admin->id, $reply->admin->id);
    }

    public function test_getByContactId_created_at昇順で取得される事(): void
    {
        $contact = $this->createDefaultContact();
        $reply1  = $this->createDefaultContactReply([
            'contact_id' => $contact->id,
            'created_at' => '2026-01-01 10:00:00',
        ]);
        $reply2 = $this->createDefaultContactReply([
            'contact_id' => $contact->id,
            'created_at' => '2026-01-01 09:00:00',
        ]);

        $replies = $this->repository->getByContactId($contact->id);

        $this->assertSame($reply2->id, $replies->first()->id, '古い返信が先頭に来ることをテスト');
        $this->assertSame($reply1->id, $replies->last()->id);
    }
}
