<?php

namespace Tests\Unit\Services\Admin\ContactForm;

use App\Domain\Entities\ContactForm;
use App\Domain\Entities\ContactFormImage;
use App\Services\Admin\ContactForm\DestroyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyServiceTest extends TestCase
{

    use RefreshDatabase;

    private DestroyService $service;

    /**
     * 各テストの実行前に起動する。
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DestroyService::class);
    }

    /**
     * インスタンスがテスト対象のクラスであることのテスト
     */
    public function testInstanceOf(): void
    {
        $this->assertInstanceOf(DestroyService::class, $this->service);
    }

    /**
     * deleteのテスト
     */
    public function testDelete(): void
    {

        // データが存在しない場合にシステムエラーが発生しないことをテスト
        $this->expectException(\Exception::class);
        $this->service->delete(0);

        /** @var ContactForm $contactForm */
        $contactForm = ContactForm::factory()->create();
        /** @var ContactFormImage $contactFormImage */
        $contactFormImage = ContactFormImage::factory()->create([
            'contact_form_id' => $contactForm->id,
        ]);

        $this->service->delete($contactForm->id);

        // データが削除されたことをテスト
        $this->assertDatabaseMissing('contact_forms', ['id' => $contactForm->id]);
        $this->assertDatabaseMissing('contact_form_images', ['id' => $contactFormImage->id]);
    }
}
