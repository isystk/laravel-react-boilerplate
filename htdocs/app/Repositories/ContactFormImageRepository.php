<?php

namespace App\Repositories;

use App\Models\ContactFormImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ContactFormImageRepository
{

    /**
     * @param string $createdAt
     * @param array<string, mixed>|array<int, string> $options
     * @return int
     */
    public function count(string $createdAt, array $options = []): int
    {
        $query = ContactFormImage::whereDay(
            'created_at', $createdAt
        );
        return $query->count();
    }

    /**
     * @param string $contactFormId
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string $contactFormId, array $options = []): Collection|LengthAwarePaginator
    {
        $query = ContactFormImage::with($this->__with($options))
            ->where([
                'contact_form_id' => $contactFormId
            ]);

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param string $id
     * @param array<string, mixed>|array<int, string> $options
     * @return ContactFormImage|null
     */
    public function findById(string $id, array $options = []): ContactFormImage|null
    {
        return ContactFormImage::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
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

    /**
     * @param string|null $id
     * @param string $contactFormId
     * @param string $fileName
     * @return ContactFormImage
     */
    public function store(
        ?string $id,
        string $contactFormId,
        string $fileName
    ): ContactFormImage
    {
        $contactFormImage = new ContactFormImage();
        $contactFormImage['id'] = $id;
        $contactFormImage['contact_form_id'] = $contactFormId;
        $contactFormImage['file_name'] = $fileName;

        $contactFormImage->save();

        return $contactFormImage;
    }

    /**
     * @param string $id
     * @param string $contactFormId
     * @param string $fileName
     * @return ContactFormImage
     */
    public function update(
        string $id,
        string $contactFormId,
        string $fileName
    ): ContactFormImage
    {
        $contactFormImage = $this->findById($id);
        $contactFormImage['contact_form_id'] = $contactFormId;
        $contactFormImage['file_name'] = $fileName;
        $contactFormImage->save();

        return $contactFormImage;
    }

    /**
     * @param string $id
     * @return ContactFormImage|null
     */
    public function delete(
        string $id
    ): ?ContactFormImage
    {
        $contactFormImage = $this->findById($id);
        $contactFormImage->delete();

        return $contactFormImage;
    }

}
