<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\ContactReply;
use App\Enums\ContactType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class ContactTest extends BaseTest
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultContact([
            'type' => ContactType::Service->value,
        ]);

        $this->assertInstanceOf(ContactType::class, $model->type);
        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }

    public function test_repliesリレーションが取得できる事(): void
    {
        $contact = $this->createDefaultContact();
        $this->createDefaultContactReply(['contact_id' => $contact->id]);
        $this->createDefaultContactReply(['contact_id' => $contact->id]);

        $contact->load('replies');

        $this->assertSame(2, $contact->replies->count());
        $this->assertInstanceOf(ContactReply::class, $contact->replies->first());
    }
}
