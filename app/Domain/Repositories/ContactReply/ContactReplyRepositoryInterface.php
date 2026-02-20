<?php

namespace App\Domain\Repositories\ContactReply;

use App\Domain\Repositories\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface ContactReplyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * contact_id に紐づく返信一覧を取得します。
     *
     * @return Collection<int, \App\Domain\Entities\ContactReply>
     */
    public function getByContactId(int $contactId): Collection;
}
