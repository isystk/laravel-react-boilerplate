<?php

namespace App\Repositories;

use App\Models\Stock;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class StockRepository
{

    /**
     * @param $name
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function count($name, $options = [])
    {
        return Stock::where('name', 'like', '%' . $name . '%')->count();
    }

    /**
     * @param $name
     * @param array<string, mixed> $options
     * @return LengthAwarePaginator|Builder[]|Collection
     */
    public function findAll($name, array $options = []): Collection|LengthAwarePaginator|array
    {
        $query = Stock::with($this->__with($options))
            ->where('name', 'like', '%' . $name . '%')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'asc');

        $limit = !empty($options['limit']) ? (int)$options['limit'] : null;
        return $limit > 0 ? $query->paginate($limit) : $query->get();
    }

    /**
     * @param $id
     * @param array<string, mixed> $options
     * @return Builder|null
     */
    public function findById($id, array $options = []): Builder|null
    {
        return Stock::with($this->__with($options))
            ->where([
                'id' => $id
            ])
            ->first();
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
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
     * @param $id
     * @param $name
     * @param $detail
     * @param $price
     * @param $quantity
     * @param $imgpath
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
        $stock->id = $id;
        $stock->name = $name;
        $stock->detail = $detail;
        $stock->price = $price;
        $stock->quantity = $quantity;
        $stock->imgpath = $imgpath;

        $stock->save();

        return $stock;
    }

    /**
     * @param $id
     * @param $name
     * @param $detail
     * @param $price
     * @param $quantity
     * @param $imgpath
     * @return Illuminate\Database\Eloquent
     */
    public function update(
        $id,
        $name,
        $detail,
        $price,
        $quantity,
        $imgpath
    ):  Illuminate\Database\Eloquent
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
