<?php

namespace App\Domain\Entities;

use Database\Factories\Domain\Entities\CartFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int    $id
 * @property int    $stock_id
 * @property int    $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Stock $stock
 */
class Cart extends Model
{
    /** @phpstan-use HasFactory<CartFactory> */
    use HasFactory;

    protected $table = 'carts';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'stock_id',
        'user_id',
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
     * @return BelongsTo<Stock, $this>
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
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
