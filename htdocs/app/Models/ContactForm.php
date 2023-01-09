<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactForm extends Model
{
    use HasFactory;

    // 子テーブル

    /**
     * @return HasMany
     */
    public function contactFormImages(): HasMany
    {
        return $this->hasMany(ContactFormImage::class);
    }
}
