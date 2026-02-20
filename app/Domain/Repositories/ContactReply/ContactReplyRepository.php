<?php

namespace App\Domain\Repositories\ContactReply;

use App\Domain\Repositories\BaseRepository;
use Illuminate\Support\Collection;

interface ContactReplyRepository extends BaseRepository
{
    /**
     * contact_id に紐づく返信一覧を取得します。
     *
     * @return Collection<int, \App\Domain\Entities\ContactReply>
     */
    public function getByContactId(int $contactId): Collection;
}
