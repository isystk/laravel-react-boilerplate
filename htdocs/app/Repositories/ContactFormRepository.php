<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ContactForm;

class ContactFormRepository
{

    /**
     * @param string $createdAt
     * @param array<string, mixed>|array<int, string> $options
     * @return int
     */
    public function count(string $createdAt, array $options = []): int
    {
        $query = ContactForm::whereDay(
            'created_at', $createdAt
        );
        return $query->count();
    }

    /**
     * @param string|null $yourName
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(?string $yourName, array $options = []): Collection|LengthAwarePaginator
    {
        $query = ContactForm::with($this->__with($options))
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc');

        // もしキーワードがあったら
        if ($yourName !== null) {
            // 全角スペースを半角に
            $search_split = mb_convert_kana($yourName, 's');

            // 空白で区切る
            $search_split2 = preg_split('/[\s]+/', $search_split);

            // 単語をループで回す
            foreach ($search_split2 as $value) {
                $query->where('your_name', 'like', '%' . $value . '%');
            }
        }

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param string $id
     * @param array<string, mixed>|array<int, string> $options
     * @return ContactForm|null
     */
    public function findById(string $id, array $options = []): ContactForm|null
    {
        return ContactForm::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }

    /**
     * @param array<string, mixed>|array<int, string> $options
     * @return array<int, string>
     */
    private function __with($options = [])
    {
        $with = [];
        if (!empty($options['with:images'])) {
            $with[] = 'contactFormImages';
        }
        return $with;
    }

    /**
     * @param string|null $id
     * @param string $yourName
     * @param string $title
     * @param string $email
     * @param ?string $url
     * @param string $gender
     * @param string $age
     * @param string $contact
     * @return ContactForm
     */
    public function store(
        ?string $id,
        string $yourName,
        string $title,
        string $email,
        ?string $url,
        string $gender,
        string $age,
        string $contact
    ): ContactForm
    {
        $contactForm = new ContactForm();
        $contactForm['id'] = $id;
        $contactForm['your_name'] = $yourName;
        $contactForm['title'] = $title;
        $contactForm['email'] = $email;
        $contactForm['url'] = $url;
        $contactForm['gender'] = $gender;
        $contactForm['age'] = $age;
        $contactForm['contact'] = $contact;

        $contactForm->save();

        return $contactForm;
    }

    /**
     * @param string $id
     * @param string $yourName
     * @param string $title
     * @param string $email
     * @param string|null $url
     * @param int $gender
     * @param int $age
     * @param string $contact
     * @return ContactForm|null
     */
    public function update(
        string $id,
        string $yourName,
        string $title,
        string $email,
        ?string $url,
        int    $gender,
        int    $age,
        string $contact
    ): ?ContactForm
    {
        $contactForm = $this->findById($id);
        $contactForm['your_name'] = $yourName;
        $contactForm['title'] = $title;
        $contactForm['email'] = $email;
        $contactForm['url'] = $url;
        $contactForm['gender'] = $gender;
        $contactForm['age'] = $age;
        $contactForm['contact'] = $contact;
        $contactForm->save();

        return $contactForm;
    }

    /**
     * @param string $id
     * @return ContactForm|null
     */
    public function delete(
        string $id
    )
    {
        $contactForm = $this->findById($id);
        $contactForm->delete();

        return $contactForm;
    }

}
