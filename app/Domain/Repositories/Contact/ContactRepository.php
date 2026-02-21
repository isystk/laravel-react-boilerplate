<?php

namespace App\Domain\Repositories\Contact;

use App\Domain\Entities\Contact;
use App\Domain\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ContactRepository extends BaseRepository implements ContactRepositoryInterface
{
    protected function model(): string
    {
        return Contact::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getByConditions(array $conditions): Collection|LengthAwarePaginator
    {
        $query = $this->model
            ->select('contacts.*')
            ->leftJoin('users', 'contacts.user_id', '=', 'users.id')
            ->with(['image']);

        if (!is_null($conditions['keyword'] ?? null)) {
            $keyword = $conditions['keyword'];
            $query->where(function ($q) use ($keyword): void {
                $q->where('users.name', 'like', '%' . $keyword . '%')
                    ->orWhere('users.email', 'like', '%' . $keyword . '%');
                if (is_numeric($keyword)) {
                    $q->orWhere('contacts.id', '=', (int) $keyword);
                }
            });
        }
        if (!empty($conditions['only_unreplied'])) {
            $query->whereDoesntHave('replies');
        }
        if (!is_null($conditions['title'] ?? null)) {
            $query->where('contacts.title', 'like', '%' . $conditions['title'] . '%');
        }
        if (!is_null($conditions['contact_date_from'] ?? null)) {
            $query->where('contacts.created_at', '>=', $conditions['contact_date_from']->startOfDay());
        }
        if (!is_null($conditions['contact_date_to'] ?? null)) {
            $query->where('contacts.created_at', '<=', $conditions['contact_date_to']->endOfDay());
        }

        $sortColumn = $this->validateSortColumn(
            $conditions['sort_name'] ?? '',
            ['id', 'users.name', 'title', 'email', 'created_at', 'updated_at'],
        );
        if ($sortColumn !== null) {
            $query->orderBy($sortColumn, $conditions['sort_direction'] ?? 'asc');
        }

        if (!is_null($conditions['limit'] ?? null)) {
            /** @var LengthAwarePaginator<int, Contact> */
            return $query->paginate($conditions['limit']);
        }

        /** @var Collection<int, Contact> */
        return $query->get();
    }
}
