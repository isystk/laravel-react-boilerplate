<?php

namespace App\Repositories;

use App\Models\ContactFormImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class ContactFormImageRepository extends BaseRepository
{

    /**
     * @return string
     */
    function model()
    {
        return ContactFormImage::class;
    }

    /**
     * @param int $contactFormId
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(int $contactFormId, array $options = []): Collection|LengthAwarePaginator
    {
        $query = $this->getModel()->with($this->__with($options))
            ->where([
                'contact_form_id' => $contactFormId
            ]);

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with(array $options = [])
    {
        $with = [];
        return $with;
    }

}
