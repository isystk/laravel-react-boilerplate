<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use Barryvdh\DomPDF\ServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    ServiceProvider::class,
    ExcelServiceProvider::class,
];
