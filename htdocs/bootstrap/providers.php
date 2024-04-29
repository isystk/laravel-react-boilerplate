<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // App\Providers\BroadcastServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\JetstreamServiceProvider::class,

    /*
     * 追加
     */
    App\Providers\RepositoryServiceProvider::class,
    Barryvdh\DomPDF\ServiceProvider::class,
    Maatwebsite\Excel\ExcelServiceProvider::class,
];
