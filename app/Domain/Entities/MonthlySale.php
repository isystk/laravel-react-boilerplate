<?php

namespace App\Domain\Entities;

use Database\Factories\Domain\Entities\MonthlySaleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int      $id
 * @property string   $year_month
 * @property int|null $order_count
 * @property int|null $amount
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
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
