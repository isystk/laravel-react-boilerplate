<?php

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\Admin;
use App\Domain\Entities\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\BaseTest;

class ContactReplyTest extends BaseTest
{
    use RefreshDatabase;

    public function test_正しくキャストされる事(): void
    {
        $model = $this->createDefaultContactReply();

        $this->assertInstanceOf(Carbon::class, $model->created_at);
        $this->assertInstanceOf(Carbon::class, $model->updated_at);
    }

    public function test_contactリレーションが取得できる事(): void
    {
        $contact = $this->createDefaultContact();
        $reply   = $this->createDefaultContactReply(['contact_id' => $contact->id]);

        $this->assertInstanceOf(Contact::class, $reply->contact);
        $this->assertSame($contact->id, $reply->contact->id);
    }

    public function test_adminリレーションが取得できる事(): void
    {
        $admin = $this->createDefaultAdmin(['name' => '管理者A']);
        $reply = $this->createDefaultContactReply(['admin_id' => $admin->id]);

        $this->assertInstanceOf(Admin::class, $reply->admin);
        $this->assertSame($admin->id, $reply->admin->id);
    }
}
