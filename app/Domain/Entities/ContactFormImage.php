<?php

namespace App\Domain\Entities;

use Database\Factories\Domain\Entities\ContactFormImageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $contact_form_id
 * @property string|null $file_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ContactFormImage extends Model
{
    /** @phpstan-use HasFactory<ContactFormImageFactory> */
    use HasFactory;

    protected $table = 'contact_form_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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
     * @return BelongsTo<ContactForm, $this>
     */
    public function contactForm(): BelongsTo
    {
        return $this->belongsTo(ContactForm::class);
    }
}
