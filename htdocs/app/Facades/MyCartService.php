<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MyCartService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MyCartService';
    }
}
