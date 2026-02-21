<?php

namespace App\Domain\Repositories\ContactReply;

use App\Domain\Entities\ContactReply;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Support\Collection;

class ContactReplyEloquentRepository extends BaseEloquentRepository implements ContactReplyRepository
{
    protected function model(): string
    {
        return ContactReply::class;
    }

    /**
     * contact_id に紐づく返信一覧を取得します。
     *
     * @return Collection<int, ContactReply>
     */
    public function getByContactId(int $contactId): Collection
    {
        /** @var Collection<int, ContactReply> */
        return $this->model
            ->where('contact_id', $contactId)
            ->with('admin')
            ->orderBy('created_at')
            ->get();
    }
}
