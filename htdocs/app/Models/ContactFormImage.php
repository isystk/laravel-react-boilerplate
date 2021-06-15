<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFormImage extends Model
{
  // 親テーブル
  public function contactForm()
  {
    return $this->belongsTo('App\Models\ContactForm');
  }
}
