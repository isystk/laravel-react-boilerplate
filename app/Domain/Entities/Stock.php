<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Database\Factories\Domain\Entities\StockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $name
 * @property string|null $detail
 * @property int|null $price
 * @property int|null $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Stock extends Model
{
    /** @phpstan-use HasFactory<StockFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'detail',
        'price',
        'imgpath',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * 在庫がある場合にTrueを返却する
     */
    public function hasQuantity(): bool
    {
        return 0 < $this->quantity;
    }

}
