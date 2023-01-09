<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactFormImage extends Model
{
    // 親テーブル
    /**
     * @return BelongsTo
     */
    public function contactForm(): BelongsTo
    {
        return $this->belongsTo(ContactForm::class);
    }
}
