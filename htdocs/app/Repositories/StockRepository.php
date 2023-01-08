<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class StockRepository
{

    /**
     * @param string $name
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function count(string $name, array $options = []): mixed
    {
        return Stock::where('name', 'like', '%' . $name . '%')->count();
    }

    /**
     * @param string|null $name
     * @param array<int, string>|array<string, mixed> $options
     * @return Collection|LengthAwarePaginator
     */
    public function findAll(string|null $name, array $options = []): Collection|LengthAwarePaginator
    {
        $query = Stock::with($this->__with($options))
            ->where('name', 'like', '%' . $name . '%')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc');

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param string $id
     * @param array<int, string>|array<string, mixed> $options
     * @return Model|null
     */
    public function findById(string $id, array $options = []): Model|null
    {
        return Stock::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }

    /**
     * @param array<int, string>|array<string, mixed> $options
     * @return array<int, string>
     */
    private function __with(array $options = [])
    {
        $with = [];
        if (!empty($options['with:orders'])) {
            $with[] = 'orders';
        }
        return $with;
    }

    /**
     * @param ?string $id
     * @param string $name
     * @param string $detail
     * @param integer $price
     * @param integer $quantity
     * @param string $imgpath
     * @return Stock
     */
    public function store(
        $id,
        $name,
        $detail,
        $price,
        $quantity,
        $imgpath
    ): Stock
    {
        $stock = new Stock();
        $stock['id'] = $id;
        $stock['name'] = $name;
        $stock['detail'] = $detail;
        $stock['price'] = $price;
        $stock['quantity'] = $quantity;
        $stock['imgpath'] = $imgpath;

        $stock->save();

        return $stock;
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $detail
     * @param integer $price
     * @param integer $quantity
     * @param string $imgpath
     * @return Model|null
     */
    public function update(
        $id,
        $name,
        $detail,
        $price,
        $quantity,
        $imgpath
    )
    {
        $stock = $this->findById($id);
        $stock['name'] = $name;
        $stock['detail'] = $detail;
        $stock['price'] = $price;
        $stock['quantity'] = $quantity;
        if (!empty($imgpath)) {
            $stock['imgpath'] = $imgpath;
        }
        $stock->save();

        return $stock;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function delete(
        string $id
    ): mixed
    {
        $stock = $this->findById($id);
        return $stock->delete();
    }

}
