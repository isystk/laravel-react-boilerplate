<?php

namespace App\Domain\Entities;

use App\Enums\Age;
use App\Enums\Gender;
use Database\Factories\Domain\Entities\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int|null    $image_id
 * @property string|null $user_name
 * @property string|null $title
 * @property string|null $email
 * @property string|null $url
 * @property Gender      $gender
 * @property Age         $age
 * @property string|null $contact
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property-read Image|null $image
 */
class Contact extends Model
{
    /** @phpstan-use HasFactory<ContactFactory> */
    use HasFactory;

    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_name',
        'title',
        'email',
        'url',
        'gender',
        'age',
        'contact',
        'image_id',
    ];

    /**
     * @return BelongsTo<Image, $this>
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender'     => Gender::class,
            'age'        => Age::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
