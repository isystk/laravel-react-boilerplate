<?php

namespace App\Domain\Entities;

use Database\Factories\Domain\Entities\StockFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property int    $image_id
 * @property string $name
 * @property string $detail
 * @property int    $price
 * @property int    $quantity
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Image $image
 */
class Stock extends Model
{
    /** @phpstan-use HasFactory<StockFactory> */
    use HasFactory;

    protected $table = 'stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'detail',
        'price',
        'image_id',
        'quantity',
    ];

    /**
     * @return BelongsTo<Image, $this>
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * 在庫がある場合にTrueを返却する
     */
    public function hasQuantity(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * 商品画像の表示用URLを返却します。
     */
    public function getImageUrl(): string
    {
        return $this->image->getImageUrl();
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
