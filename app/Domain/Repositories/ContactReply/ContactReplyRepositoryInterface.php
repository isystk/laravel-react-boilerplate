<?php

namespace App\Domain\Repositories\ContactReply;

use App\Domain\Entities\ContactReply;
use App\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface ContactReplyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * contact_id に紐づく返信一覧を取得します。
     *
     * @return Collection<int, ContactReply>
     */
    public function getByContactId(int $contactId): Collection;
}
