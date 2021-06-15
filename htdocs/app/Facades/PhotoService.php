<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PhotoService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'PhotoService';
    }
}
