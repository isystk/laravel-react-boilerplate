<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactForm extends Model
{
    use HasFactory;
  // 子テーブル
  public function contactFormImages()
  {
    return $this->hasMany('App\Models\ContactFormImage');
  }
}
