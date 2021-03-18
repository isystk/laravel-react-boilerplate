<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactForm extends Model
{
    // 子テーブル
    public function contactFormImages()
    {
        return $this->hasMany('App\Models\ContactFormImage');
    }
}
