<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Database\Factories\Domain\Entities\MonthlySaleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $year_month
 * @property int|null $order_count
 * @property int|null $amount
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MonthlySale extends Model
{
    /** @phpstan-use HasFactory<MonthlySaleFactory> */
    use HasFactory;

    protected $table = 'monthly_sales';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'year_month',
        'order_count',
        'amount',
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

}
