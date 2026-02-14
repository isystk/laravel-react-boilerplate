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
| Googleログイン
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [App\Http\Controllers\Front\Auth\GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [App\Http\Controllers\Front\Auth\GoogleLoginController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| フロント
|--------------------------------------------------------------------------
*/
Route::get('/{any}', [\App\Http\Controllers\Front\ReactController::class, 'index'])->where('any', '.*')->name('react');
