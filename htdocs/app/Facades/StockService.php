<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class StockService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StockService';
    }
}
