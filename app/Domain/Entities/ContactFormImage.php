<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $contact_form_id
 * @property string|null $file_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ContactFormImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_form_id',
        'file_name',
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

    // 親テーブル
    /**
     * @return BelongsTo<ContactForm, ContactFormImage>
     */
    public function contactForm(): BelongsTo
    {
        return $this->belongsTo(ContactForm::class);
    }
}
