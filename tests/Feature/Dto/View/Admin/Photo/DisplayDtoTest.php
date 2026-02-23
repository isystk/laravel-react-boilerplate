<?php

namespace Tests\Feature\Dto\View\Admin\Photo;

use App\Domain\Entities\Image;
use App\Dto\View\Admin\Photo\DisplayDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTest;

class DisplayDtoTest extends BaseTest
{
    use RefreshDatabase;

    public function test_isUsed_returns_true_when_used_by_stock(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill(['used_by_stock_id' => 1]);

        $dto = new DisplayDto($image);
        $this->assertTrue($dto->isUsed());
    }

    public function test_isUsed_returns_true_when_used_by_contact(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill(['used_by_contact_id' => 1]);

        $dto = new DisplayDto($image);
        $this->assertTrue($dto->isUsed());
    }

    public function test_isUsed_returns_true_when_used_by_user(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill(['used_by_user_id' => 1]);

        $dto = new DisplayDto($image);
        $this->assertTrue($dto->isUsed());
    }

    public function test_isUsed_returns_false_when_not_used(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill([
            'used_by_stock_id'   => null,
            'used_by_contact_id' => null,
            'used_by_user_id'    => null,
        ]);

        $dto = new DisplayDto($image);
        $this->assertFalse($dto->isUsed());
    }

    public function test_getUsedUrl_returns_stock_url(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill(['used_by_stock_id' => 123]);

        $dto = new DisplayDto($image);
        $this->assertSame(route('admin.stock.show', ['stock' => 123]), $dto->getUsedUrl());
    }

    public function test_getUsedUrl_returns_contact_url(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill(['used_by_contact_id' => 456]);

        $dto = new DisplayDto($image);
        $this->assertSame(route('admin.contact.show', ['contact' => 456]), $dto->getUsedUrl());
    }

    public function test_getUsedUrl_returns_user_url(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill(['used_by_user_id' => 789]);

        $dto = new DisplayDto($image);
        $this->assertSame(route('admin.user.show', ['user' => 789]), $dto->getUsedUrl());
    }

    public function test_getUsedUrl_returns_null_when_not_used(): void
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->createDefaultImage()->forceFill([
            'used_by_stock_id'   => null,
            'used_by_contact_id' => null,
            'used_by_user_id'    => null,
        ]);

        $dto = new DisplayDto($image);
        $this->assertNull($dto->getUsedUrl());
    }
}
