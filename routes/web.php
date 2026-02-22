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
Route::get('auth/google', [\App\Http\Controllers\Front\Auth\GoogleLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [\App\Http\Controllers\Front\Auth\GoogleLoginController::class, 'handleGoogleCallback']);

/*
|--------------------------------------------------------------------------
| メール認証
|--------------------------------------------------------------------------
*/
Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\Front\Auth\EmailVerificationController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| フロント
|--------------------------------------------------------------------------
*/
Route::get('/login', [\App\Http\Controllers\Front\ReactController::class, 'index'])->where('any', '.*')->name('login');
Route::get('/{any}', [\App\Http\Controllers\Front\ReactController::class, 'index'])->where('any', '.*')->name('react');
