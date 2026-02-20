<?php

namespace App\Domain\Entities;

use App\Enums\ContactType;
use Database\Factories\Domain\Entities\ContactFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $user_id
 * @property ContactType $type
 * @property string      $title
 * @property string      $message
 * @property int|null    $image_id
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property-read User   $user
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
        'user_id',
        'title',
        'type',
        'message',
        'image_id',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
            'type'       => ContactType::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
