<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UserService';
    }
}
