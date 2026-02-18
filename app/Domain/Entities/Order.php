<?php

namespace App\Domain\Entities;

use Database\Factories\Domain\Entities\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int      $id
 * @property int      $user_id
 * @property int|null $sum_price
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 */
class Order extends Model
{
    /** @phpstan-use HasFactory<OrderFactory> */
    use HasFactory;

    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'sum_price',
    ];

    // 親テーブル

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Domain\Entities\OrderStock, $this>
     */
    public function orderStocks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderStock::class);
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
