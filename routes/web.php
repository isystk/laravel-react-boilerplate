<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 管理画面
|--------------------------------------------------------------------------
*/
require __DIR__ . '/admin.php';


/*
|--------------------------------------------------------------------------
| ソーシャルログイン
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/{provider}', [\App\Http\Controllers\Front\Auth\OAuthController::class, 'socialOAuth'])
        ->where('provider', 'google')
        ->name('socialOAuth');

    Route::get('/{provider}/callback', [\App\Http\Controllers\Front\Auth\OAuthController::class, 'handleProviderCallback'])
        ->where('provider', 'google')
        ->name('oauthCallback');
});

/*
|--------------------------------------------------------------------------
| フロント
|--------------------------------------------------------------------------
*/
Route::get('/{any}', [\App\Http\Controllers\Front\ReactController::class, 'index'])->where('any', '.*')->name('react');
