<?php

namespace App\Dto\View\Admin\Photo;

use App\Domain\Entities\Image;

class DisplayDto
{
    public function __construct(
        public readonly Image $image,
    ) {}

    /**
     * 画像が在庫、問い合わせ、ユーザーのいずれかで使用されている場合にTrueを返却します。
     */
    public function isUsed(): bool
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image = $this->image;

        return $image->used_by_stock_id || $image->used_by_contact_id || $image->used_by_user_id;
    }

    /**
     * 画像が使用されている場合、そのURLを返却します。
     */
    public function getUsedUrl(): ?string
    {
        /** @var Image&object{used_by_stock_id: int|null, used_by_contact_id: int|null, used_by_user_id: int|null} $image */
        $image   = $this->image;
        $usedUrl = null;

        if ($image->used_by_stock_id) {
            $usedUrl = route('admin.stock.show', [
                'stock' => $image->used_by_stock_id,
            ]);
        } elseif ($image->used_by_contact_id) {
            $usedUrl = route('admin.contact.show', [
                'contact' => $image->used_by_contact_id,
            ]);
        } elseif ($image->used_by_user_id) {
            $usedUrl = route('admin.user.show', [
                'user' => $image->used_by_user_id,
            ]);
        }

        return $usedUrl;
    }
}
