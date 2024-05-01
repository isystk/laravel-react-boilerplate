<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string|null $your_name
 * @property string|null $title
 * @property string|null $email
 * @property string|null $url
 * @property bool $gender
 * @property string|null $contact
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ContactForm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'your_name',
        'title',
        'email',
        'url',
        'gender',
        'age',
        'contact',
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

    // 子テーブル

    /**
     * @return HasMany
     */
    public function contactFormImages(): HasMany
    {
        return $this->hasMany(ContactFormImage::class);
    }
}
