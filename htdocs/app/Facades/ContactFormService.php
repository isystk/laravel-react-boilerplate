<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ContactFormService extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'ContactFormService';
  }
}
