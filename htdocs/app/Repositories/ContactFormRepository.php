<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ContactForm;
use Prettus\Repository\Eloquent\BaseRepository;

class ContactFormRepository extends BaseRepository
{

    /**
     * @return string
     */
    function model()
    {
        return ContactForm::class;
    }

    /**
     * @param string|null $yourName
     * @param array<string, mixed>|array<int, string> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(?string $yourName, array $options = []): Collection|LengthAwarePaginator
    {
        $query = $this->getModel()->with($this->__with($options))
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

}
