<?php

namespace App\Domain\Repositories\ContactForm;

use App\Domain\Entities\ContactFormImage;
use App\Domain\Repositories\BaseEloquentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactFormImageEloquentEloquentRepository extends BaseEloquentRepository implements ContactFormImageRepository
{

    /**
     * @return string
     */
    protected function model(): string
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
        $query = $this->model->with($this->__with($options))
            ->where([
                'contact_form_id' => $contactFormId,
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
